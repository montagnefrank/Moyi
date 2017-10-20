<?php
session_start();
require('fpdf/fpdf.php');
require_once('barcode.inc.php');
require_once('barcode39UPS.php');
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");

//hacer conexion
$conection = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysqli_error()); 

if(isset($_GET['master']) && $_GET['master']=='generar')
{
    
class PDF extends FPDF
{
   //Pie de página
   function Footer()
   {
        //Posición: a 1,5 cm del final
        $this->SetY(-15);
        //Arial italic 8
        $this->SetFont('Arial','I',8);
        //Número de página
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
   }

    //Tabla titulos de la tabla1
   function Tabla1()
   {
          $this->SetFont('Arial','B',8);
          $this->Cell(10,5,"Units",1,0,'C');
          $this->CellFitSpace(12,5,"U/M",1,0,'C');
          $this->CellFitSpace(50,5,"Description of Goods/Part No.",1,0,'C');
          $this->CellFitSpace(40,5,"Harm. Code",1,0,'C');
          $this->CellFitSpace(8,5,"C/O",1,0,'C');
          $this->CellFitSpace(30,5,"Unit Value",1,0,'C');
          $this->CellFitSpace(30,5,"Total Value",1,0,'C');
          $this->Ln();

   } 

    
	   //***** Aquí comienza código para ajustar texto *************
    //***********************************************************
    function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
    {
        //Get string width
        $str_width=$this->GetStringWidth($txt);
 
        //Calculate ratio to fit cell
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
        $ratio = ($w-$this->cMargin*2)/$str_width;
 
        $fit = ($ratio < 1 || ($ratio > 1 && $force));
        if ($fit)
        {
            if ($scale)
            {
                //Calculate horizontal scaling
                $horiz_scale=$ratio*100.0;
                //Set horizontal scaling
                $this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
            }
            else
            {
                //Calculate character spacing in points
                $char_space=($w-$this->cMargin*2-$str_width)/max($this->MBGetStringLength($txt)-1,1)*$this->k;
                //Set character spacing
                $this->_out(sprintf('BT %.2F Tc ET',$char_space));
            }
            //Override user alignment (since text will fill up cell)
            $align='';
        }
 
        //Pass on to Cell method
        $this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);
 
        //Reset character spacing/horizontal scaling
        if ($fit)
            $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
    }
 
    function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
    }
 
    //Patch to also work with CJK double-byte text
    function MBGetStringLength($s)
    {
        if($this->CurrentFont['type']=='Type0')
        {
            $len = 0;
            $nbbytes = strlen($s);
            for ($i = 0; $i < $nbbytes; $i++)
            {
                if (ord($s[$i])<128)
                    $len++;
                else
                {
                    $len++;
                    $i++;
                }
            }
            return $len;
        }
        else
            return strlen($s);
    }
    function SetCol($col)
    {
    // Establecer la posición de una columna dada
    $this->col = $col;
    $x = 10+$col*95;
    $this->SetLeftMargin($x);
    $this->SetX($x);
    } 
  
//************** Fin del código para ajustar texto *****************
//******************************************************************
}
	
//Creacion del objeto pdf		
$pdf = new PDF();
$pdf->SetLeftMargin(3);

//$sql ="SELECT ";
//$query = mysqli_query($sql,$conection);

$pdf->AddPage();
$pdf->AliasNbPages();

$pdf->SetFont('Arial','B',18);
$pdf->Cell(180,7,"MASTER INVOICE",0,0,'C');
$pdf->Ln();

//consulta para seleccionar los datos de la cuenta
$cuenta=substr($_GET["guia"], 0,6); 
$sql0="SELECT * FROM tblclienteups WHERE cuenta='".$cuenta."'";
$query0 = mysqli_query($sql0,$conection);
$row=mysqli_fetch_array($query0);

$pdf->SetFont('Arial','B',10);
$pdf->SetCol(0);
$pdf->SetY(20);
$pdf->Cell(85,5,"FROM",1);
$pdf->Ln();
$pdf->Cell(85,5,"TaxID/VAT No:",'LR');
$pdf->Ln();
$pdf->Cell(25,5,"Contact Name: ",'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(60,5,$row['nombre'],'R');
$pdf->Ln();
$pdf->Cell(85,5,$row['compañia'],'LR');
$pdf->Ln();
$pdf->Cell(85,5,$row['direccion'],'LR');
$pdf->Ln();
$pdf->Cell(85,8,'','LR');
$pdf->Ln();
$pdf->Cell(85,5,$row['codigo_postal'],'LR');
$pdf->Ln();
$pdf->Cell(85,5,'','LR');
$pdf->Ln();
$pdf->Cell(85,5,$row['pais'],'LR');
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(20,5,"Phone:",'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(65,5,$row['telefono'],'R');
$pdf->Ln(5);
$pdf->Cell(85,5,'','LR');

$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(85,5,"SHIP TO",1);
$pdf->Ln();
$pdf->Cell(85,5,"TaxID/VAT No:",'LR');
$pdf->Ln();
$pdf->Cell(25,5,"Contact Name: ",'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(60,5,$row['director'],'R');
$pdf->Ln();
$pdf->Cell(85,5,$row['empresa'],'LR');
$pdf->Ln();
$pdf->Cell(85,5,$row['direccion_director'],'LR');
$pdf->Ln();
$pdf->Cell(85,8,'','LR');
$pdf->Ln();
$pdf->Cell(85,5,$row['codigo_postal'],'LR');
$pdf->Ln();
$pdf->Cell(85,5,'','LR');
$pdf->Ln();
if($row['pais_director']=='US')
  $pdf->Cell(85,5,"United States",'LR');
else
 $pdf->Cell(85,5,$row['pais_director'],'LR');   
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(20,5,"Phone:",'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(65,5,$row['tpphone_director'],'R');
$pdf->Ln(5);
$pdf->Cell(85,5,'','T');

$pdf->SetCol(1);
$pdf->SetY(20);
$pdf->Cell(85,5,"",1);
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(35,5,"Master Shipment ID:",'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,$_GET['guia'],'R');
$pdf->Ln();
$pdf->Cell(5,10,"",'L');
Barcode39($_GET['guia']);
$pdf->Image('./barscode/Barcode_'.$_GET['guia'].'.png',$pdf->GetX()+5,$pdf->GetY(),30,10);
$pdf->Cell(80,10,"",'R');
$pdf->Ln(10);
$pdf->Cell(85,10,"Invoice No:",'LR');

$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(20,5,"Date:",'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(65,5,date("d/m/Y"),'R');
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(85,5,"PO No:",'LR');
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(85,10,'Terms of Sale (Incoterm):','LR');
$pdf->Ln(8);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(85,10,'Reason for Export: Sale','LR');

$pdf->Ln();
$pdf->Cell(85,5,"SOLD TO INFORMATION",1);
$pdf->Ln();
$pdf->Cell(85,5,"TaxID/VAT No:",'LR');
$pdf->Ln();
$pdf->Cell(25,5,"Contact Name: ",'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(60,5,$row['director'],'R');
$pdf->Ln();
$pdf->Cell(85,5,$row['empresa'],'LR');
$pdf->Ln();
$pdf->Cell(85,5,$row['direccion_director'],'LR');
$pdf->Ln();
$pdf->Cell(85,8,'','LR');
$pdf->Ln();
$pdf->Cell(85,5,$row['codigo_postal'],'LR');
$pdf->Ln();
$pdf->Cell(85,5,'','LR');
$pdf->Ln();
if($row['pais_director']=='US')
  $pdf->Cell(85,5,"United States",'LR');
else
 $pdf->Cell(85,5,$row['pais_director'],'LR');   
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(20,5,"Phone:",'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(65,5,$row['tpphone_director'],'R');
$pdf->Ln(5);
$pdf->Cell(85,5,'','T');

$pdf->Ln(10);
//llenar la tabla de items
$pdf->SetCol(0);
$pdf->Tabla1();
//recorriendo cada item de detalle_orden
$sql="SELECT
        tbldetalle_orden.cpitem,tblproductos.prod_descripcion,tblproductos.wheigthKg,tblboxtype.tipo_Box,
        COUNT(tbldetalle_orden.cpitem) as cant,dclvalue 
        FROM
        tbldetalle_orden
        INNER JOIN tblproductos ON tbldetalle_orden.cpitem = tblproductos.id_item
        INNER JOIN tblboxtype ON tblproductos.boxtype = tblboxtype.id_Box
        INNER JOIN tblguiamaster ON tbldetalle_orden.guiamaster = tblguiamaster.id
        where guia='".$_GET['guia']."' GROUP BY tbldetalle_orden.cpitem";

$query = mysqli_query($sql,$conection);


$yH=15; //height of the row
$x0=$x = $pdf->GetX();
$y = $pdf->GetY();
$total=0;
$totalpackage=0;
$weigthKg=0;
while ($row = mysqli_fetch_array($query)){
   
    $pdf->SetXY($x, $y);
    $pdf->MultiCell(10,4,$row['cant'],0,'C'); 
    $totalpackage=$totalpackage+$row['cant'];
    
    $pdf->SetXY($x+10, $y);
    $pdf->MultiCell(12,4,$row['tipo_Box'],0,'C'); 


    $pdf->SetXY($x+22, $y);
    $pdf->MultiCell(50,4,$row['prod_descripcion'],0,'L'); 
    $y1 = $pdf->GetY();

    $pdf->SetXY($x+72, $y);
    $pdf->MultiCell(40,4,"",0,'L');

    $pdf->SetXY($x+112, $y);
    $pdf->MultiCell(8,4,"",0,'L');

    $pdf->SetXY($x+120, $y);
    $pdf->MultiCell(30,4,$row['dclvalue'],0,'C');

    $pdf->SetXY($x+150, $y);
    $pdf->MultiCell(30,4,$row['dclvalue']*$row['cant'],0,'C');

    $y=$y1; //move to next row
    $x=$x0; //start from firt column
    $pdf->Ln(); 
    $total=$total+$row['dclvalue']*$row['cant'];
    
    if($pdf->GetY()>260){
        $pdf->AddPage();
       $pdf->AliasNbPages();
        $y=10;
    }
    //peso total de los productos
    $weigthKg=$weigthKg+$row['wheigthKg'];
}

$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',18);
$pdf->Cell(180,7,"MASTER INVOICE",0,0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(180,7,"Master Shipment ID:".$_GET["guia"],0,0,'C');


$pdf->SetY(-116);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(170,0,"",1,0,1);
$pdf->Ln();
$pdf->Cell(170,5,"Additional Comments:",0,0,1);
$pdf->Ln(5);

$pdf->SetY(-110);

$pdf->SetCol(0);
$pdf->Cell(85,0,'',1);
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(85,5,"Declaration Statement:",'LR');
$pdf->Ln();
$pdf->SetFont('Arial',"",10);
$pdf->CellFitSpace(85,5,"I hereby certify that the information on this invoice ",'LR');
$pdf->Ln();
$pdf->CellFitSpace(85,5,"is true and correct and the contents and value of this ",'LR');
$pdf->Ln();
$pdf->Cell(85,5,"shipment is as stated above",'LR');
$pdf->Ln();
$pdf->Cell(85,31,"",'LR');
$pdf->Ln();
$pdf->Cell(40,5,"Shipper",'L');
$pdf->Cell(45,5,"Date",'R');
$pdf->Ln();
$pdf->Cell(85,10,'','T');

$pdf->SetY(-110);
$pdf->SetCol(1);
$pdf->Cell(85,0,'',1);
$pdf->Ln();
$pdf->Cell(50,7,"Invoice Line Total:",'L',0,'R');
$pdf->Cell(35,7,$total,'R',0,'R');
$pdf->Ln();
$pdf->Cell(50,7,"Discount/Rebate:",'L',0,'R');
$pdf->Cell(35,7,"0.00",'R',0,'R');
$pdf->Ln();
$pdf->Cell(50,7,"Invoice Sub-Total:",'L',0,'R');
$pdf->Cell(35,7,$total,'R',0,'R');
$pdf->Ln();
$pdf->Cell(50,7,"Insurance:",'L',0,'R');
$pdf->Cell(35,7,"0.00",'R',0,'R');
$pdf->Ln();
$pdf->Cell(50,7,"Other:",'L',0,'R');
$pdf->Cell(35,7,"0.00",'R',0,'R');
$pdf->Ln();
$pdf->Cell(50,7,"Total Invoice Amount:",'L',0,'R');
$pdf->Cell(35,7,$total,'R',0,'R');
$pdf->Ln();

$pdf->Cell(85,0,"",1,0,1);
$pdf->Ln();
$pdf->Cell(50,7,"Total Number of Packages:",'L',0,'R');
$pdf->Cell(5,7,(int)($totalpackage+1),0,'L');
$pdf->Cell(30,7,"Currency: USD",'R',0);
$pdf->Ln();
$pdf->Cell(50,7,"Total Weight:",'L',0,'R');
$pdf->Cell(35,7,$weigthKg,'R',0,'L');
$pdf->Ln();
$pdf->Cell(85,7,'','T');


//generando el pdf
$pdf->Output("./Documentos/MasterInvoice-".$_GET["guia"].".pdf","F");
$pdf->Output();
return;

}
else if(isset($_GET['master']) && $_GET['master']=='closeout')
{
    
    //verifico que la guia no este como cierre de dia
    $sql="SELECT tblguiamaster.cierredia FROM tblguiamaster WHERE guia='".$_GET["guia"]."'";
    $query = mysqli_query($sql,$conection);
    $row=mysqli_fetch_row($query);
    
    if($row[0]=='')
    {
        //actualizo la tbl guiacomercial para ponerle closeout
        $sql="UPDATE tblguiamaster SET closeout='SI',cierredia='' WHERE guia='".$_GET["guia"]."'";
        $query = mysqli_query($sql,$conection);
        echo json_encode("ok");
        return;
    }
    else
    {
       echo json_encode("error");
       return;  
    }
}
?>


