<?php
session_start();
require('fpdf/fpdf.php');
//require('fpdf/mc_table.php');
//require_once('barcode.inc.php');
//require_once('barcode39UPS.php');
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");
require ("calculosUPS.php");

//hacer conexion
$conection = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysqli_error()); 
	
class PDF extends FPDF
{	
    var $widths;
    var $aligns;
    
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
   
   function Header()
   {
    $this->SetFont('Arial','B',16);
    $this->Cell(250,7,"CONSOLIDATED INVOICE DETAIL",0,0,'C');
    $this->Ln();
    $this->SetFont('Arial','B',10);
    $this->Cell(130,7,"Master Shipment ID: ",0,0,'R');
    $this->Cell(20,7,$_GET['guia'],0,0,'L');
    $this->Ln(10);
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
    
    function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data,$linea)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb, $this->NbLines($this->widths[$i], $data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
       $this->Rect($x, $y, $w, $h);
       $this->MultiCell($w, 5, $data[$i],$a);
        //Put the position to the right of the cell
       $this->SetXY($x+$w, $y);
              
        
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w, $txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r", '', $txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}
  
//************** Fin del código para ajustar texto *****************
//******************************************************************
}
	
//Creacion del objeto pdf		
$pdf = new PDF("P","mm", array(400, 279.4));

$pdf->AddPage();

$pdf->AliasNbPages();

$queryy="SELECT
tblguiamaster.guia,
tblsoldto.soldto1,
tblsoldto.address1,
COUNT(soldto1) as cant
FROM
tblorden
INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
INNER JOIN tblguiamaster ON tbldetalle_orden.guiamaster = tblguiamaster.id
INNER JOIN tblsoldto ON tblsoldto.id_soldto = tblorden.id_orden
where
tblguiamaster.guia='WY1190334SQ' GROUP BY soldto1
 ORDER BY soldto1 ASC";
$queryyC = mysqli_query($queryy,$conection);
$totalenvios=  mysqli_num_rows($queryyC);
$totalenvios=$totalenvios+1;//sumo 1 envio de la caja de documentos
$j=0;$i=0;$indice=1;

$pdf->SetFont('Arial','',10);
$pdf->Cell(43,7,"CLEARANCE COUNTRY:","LT",0,'L');
$pdf->Cell(25,7,"United States","T",0,'L');
$pdf->Cell(36,7,"CLEARANCE PORT:","T",0,'L');
$pdf->Cell(20,7,"xxxx","T",0,'L');
$pdf->Cell(43,7,"IMPORT ACCOUNT NO:","T",0,'L');
$pdf->Cell(20,7,"","T",0,'L');


$totquery="SELECT
COUNT(guia) as tot
FROM
tblorden
INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
INNER JOIN tblguiamaster ON tbldetalle_orden.guiamaster = tblguiamaster.id
INNER JOIN tblsoldto ON tblsoldto.id_soldto = tblorden.id_orden
where
tblguiamaster.guia='WY1190334SQ'";
$totpack = mysqli_query($totquery,$conection);
$rowpack= mysqli_fetch_row($totpack);
           
$pdf->Cell(40,7,"TOTAL PACKAGES:","T",0,'L');
$pdf->Cell(20,7,(int)$rowpack[0]+1,"TR",0,'L');
$pdf->Ln();
$pdf->Cell(43,7,"SERVICE LEVEL:","LB",0,'L');
$pdf->Cell(25,7,"XPR","B",0,'L');
$pdf->Cell(36,7,"PAYMENT TYPE:","B",0,'L');
$pdf->Cell(20,7,"F/C","B",0,'L');
$pdf->Cell(30,7,"SHIPPER NO:","B",0,'L');
$pdf->Cell(33,7, substr($_GET['guia'],0,6),"B",0,'L');
$pdf->Cell(40,7,"TOTAL SHIPMENTS:","B",0,'L');
$pdf->Cell(20,7,$totalenvios,"BR",0,'L');
$pdf->Ln(10);
$pdf->Cell(150,7,"(MASTER)",0,0,'L');
$pdf->Cell(150,7,"(MASTER)",0,0,'L');


$pdf->Ln(10);
$pdf->SetFont('Arial','',10);
$pdf->Cell(63,7,"Consignee ".$indice." of ".$totalenvios,"LT",0,'L');
$indice++;
$pdf->Cell(40,7,"Invoice No:","T",0,'L');
$pdf->Cell(20,7,"","T",0,'L');
$pdf->Cell(113,7,"No. of Packages:","T",0,'R');
$pdf->Cell(10,7,"1","TR",0,'L');

$pdf->Ln();
$pdf->Cell(43,7,"Service:","LB",0,'L');
$pdf->Cell(20,7,"XPR","B",0,'L');
$pdf->Cell(120,7,"General Description of Goods:","B",0,'L');
$pdf->Cell(63,7,"tyt","BR",0,'L');
$pdf->Ln();

$sql0="SELECT * FROM tblclienteups WHERE cuenta='".$_GET['guia']."'";
$query0 = mysqli_query($sql0,$conection);
$row=mysqli_fetch_array($query0);

$pdf->Cell(75,5,"TaxID/VAT No:",'LR');
$pdf->Cell(65,5,"Associated Commodities:",'R');
$pdf->Cell(25,5,"Unit Value:",0,0,'R');
$pdf->Cell(20,5,"Total Value:",'R',0,'R');
$pdf->Cell(20,5,"Ship ID:",0,0,'L');
$pdf->Cell(41,5,$_GET['guia'],'R',0,'L');

$pdf->Ln();
$pdf->Cell(25,5,"Contact Name: ",'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,$row['nombre'],'R');
$pdf->Cell(65,5,"1 Box of Documents",'R');
$pdf->Cell(25,5,"0.00",0,0,'R');
$pdf->Cell(20,5,"",'R',0,'R');
$pdf->Cell(40,5,"Tracking #",0,0);
$pdf->Cell(21,5,"Wt",'R',0);

$pdf->Ln();
$pdf->Cell(75,5,$row['compañia'],'LR');
$pdf->Cell(65,5,"Marks & Numbers:",'R');
$pdf->Cell(25,5,"",0,0,'R');
$pdf->Cell(20,5,"0.00",'R',0,'R');


$trackquery="SELECT
            tblguiamaster.tracking_documentos
            FROM
            tblguiamaster
            where
            tblguiamaster.guia='WY1190334SQ'";
$querytrack = mysqli_query($trackquery,$conection);
$rowvtrack=  mysqli_fetch_row($querytrack);

$pdf->Cell(40,5,$rowvtrack[0],0,0);
$pdf->Cell(21,5,"0.5",'R',0);


$pdf->Ln();
$pdf->Cell(75,5,$row['direccion'],'LR');
$pdf->Cell(65,5,"",'R');
$pdf->Cell(45,5,"",'R');
$pdf->Cell(61,5,"",'R');

$pdf->Ln();
$pdf->Cell(75,5,'','LR');
$pdf->Cell(65,5,"",'R');
$pdf->Cell(45,5,"",'R');
$pdf->Cell(61,5,"",'R');

$pdf->Ln();
$pdf->Cell(75,5,$row['codigo_postal'],'LR');
$pdf->Cell(65,5,"",'R');
$pdf->Cell(45,5,"",'R');
$pdf->Cell(61,5,"",'R');

$pdf->Ln();
$pdf->Cell(75,5,'','LR');
$pdf->Cell(25,5,'Currency:USD',0,0);
$pdf->Cell(40,5,'Total Shipment Value:',"R",0);
$pdf->Cell(25,5,"",'T',0,'R');
$pdf->Cell(20,5,"0.00",'TR',0,'R');
$pdf->Cell(61,5,"",'R');

$pdf->Ln();
$pdf->Cell(75,5,$row['pais'],'LR');
$pdf->Cell(65,5,"",'R');
$pdf->Cell(45,5,"",'R');
$pdf->Cell(30,5,"Total Ship Wt:",0,0);
$pdf->Cell(10,5,"0.5",0,0);
$pdf->Cell(21,5,"KGS",'R',0);

$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25,5,"Phone:",'LB');
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,$row['telefono'],'RB');
$pdf->Cell(65,5,"",'RB');
$pdf->Cell(45,5,"",'RB');
$pdf->Cell(61,5,"",'RB');
$pdf->Ln(10);



//recorro cada orden agrupada por persona
while($rowwC=mysqli_fetch_array($queryyC))
{
   
        $total=0; 
        $totalGeneral=0;
        $producto="";
        $valordecl="";
        $totalDecl="\n\n";
        $track="";
        $peso="";
        $valorpeso=0;
        $valortotal=0;
        $descproducto="";
        $shipid="";
        $consulta="select tblguiamaster.guia,
                tblguiamaster.tracking_documentos,
                tbldetalle_orden.tracking,
                tbldetalle_orden.cpitem,
                tbldetalle_orden.cppais_envio,
                tblsoldto.soldto1,
                tblsoldto.soldto2,
                tblsoldto.cpstphone_soldto,
                tblsoldto.address1,
                tblsoldto.address2,
                tblsoldto.city,
                tblsoldto.state,
                tblsoldto.postalcode,
                tblsoldto.billcountry,
                tblproductos.prod_descripcion,
                tblproductos.gen_desc,
                tblproductos.dclvalue,
                tbldetalle_orden.cpcantidad,
                tblproductos.wheigthKg
                FROM
                tblorden
                INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                INNER JOIN tblguiamaster ON tbldetalle_orden.guiamaster = tblguiamaster.id
                INNER JOIN tblusuario ON tblguiamaster.usuario = tblusuario.id_usuario
                INNER JOIN tblsoldto ON tblsoldto.id_soldto = tblorden.id_orden
                INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
                where tblguiamaster.guia='WY1190334SQ' and soldto1='".$rowwC['soldto1']."' ORDER BY soldto1 ASC";

                $queryCons = mysqli_query($consulta,$conection);
                $numpaquete=  mysqli_num_rows($queryCons);
                while($rowwCC=mysqli_fetch_array($queryCons))
                {
                //lleno las variables
                  $soldto1=$rowwCC['soldto1'];
                  $address1=$rowwCC['address1'];
                  $state=$rowwCC['state'];
                  $postalcode=$rowwCC['postalcode'];
                  $billcountry=$rowwCC['billcountry'];
                  $telefono=$rowwCC['telefono']; 

                  $total=(float)$rowwCC['cpcantidad']*$rowwCC['dclvalue'];
                  $totalGeneral=$total+$totalGeneral;
                  $totalWeigth=(float)$rowwCC['wheigthKg'];

                  $producto=$producto.$rowwCC['cpcantidad']." ".$rowwCC['prod_descripcion']."\n"."Marks & Numbers:\n\n";

                  $valordecl=$valordecl.$rowwCC['dclvalue']."\n\n\n";

                  $totalDecl=$totalDecl.$rowwCC['cpcantidad']*$rowwCC['dclvalue']."\n\n\n";
                  $valortotal=(double)$valortotal+($rowwCC['cpcantidad']*$rowwCC['dclvalue']);
                  $track=$track.$rowwCC['tracking']."\n";

                  $peso=$peso.$rowwCC['wheigthKg']."\n";
                  $valorpeso=$valorpeso+$rowwCC['wheigthKg'];

                  if($i==0)
                  {
                     $descproducto=$rowwCC['gen_desc'];
                     $shipid=shp($rowwCC['tracking']);
                     $i=1;
                  }
               }
        
                $pdf->SetFont('Arial','',10);
                $pdf->Cell(63,7,"Consignee ".$indice." of ".$totalenvios,"LT",0,'L');
                $pdf->Cell(40,7,"Invoice No:","T",0,'L');
                $pdf->Cell(20,7,"","T",0,'L');
                $pdf->Cell(113,7,"No. of Packages:","T",0,'R');
                $pdf->Cell(10,7,$numpaquete,"TR",0,'L');

                $pdf->Ln();
                $pdf->Cell(43,7,"Service:","LB",0,'L');
                $pdf->Cell(20,7,"XPR","B",0,'L');
                $pdf->Cell(55,7,"General Description of Goods:","B",0,'L');
                $pdf->Cell(128,7,$descproducto,"BR",0,'L');
                $pdf->Ln();

                $pdf->Cell(75,5,"TaxID/VAT No:",'LR');
                $pdf->Cell(65,5,"Associated Commodities:",'R');
                $pdf->Cell(25,5,"Unit Value:",0,0,'R');
                $pdf->Cell(20,5,"Total Value:",'R',0,'R');
                $pdf->Cell(20,5,"Ship ID:",0,0,'L');
                $pdf->Cell(41,5,$shipid,'R',0,'L');


                $pdf->Ln();
                $pdf->SetWidths(array(75, 65,25,20,40,21));
                $pdf->Row(array("Contact Name: ".$soldto1."\n\n".
                        "/".$rowCons['soldto1']."\n".
                        $address1.",".$state."\n".
                        $postalcode."\n\n".
                        $billcountry."\n".
                        "Phone:".$telefono,

                $producto,

                $valordecl,

                $totalDecl,

                "Tracking #\n".$track."\n\n\n\n\n",

                "Wt\n".$peso),array("LB","LB","LB","LB","LB","LBR"));
        
                $pdf->Cell(140,5,'Currency:USD.  Total Shipment Value:','LB',0,'R');
                $pdf->Cell(45,5,$valortotal,'LBR',0,'C');
                $pdf->Cell(61,5,"Total Ship Wt: ".$valorpeso." KGS",'LBR',0,'L');
                $pdf->Ln(10);

                $indice++;
   
}

//generando el pdf
$pdf->Output("./Documentos/MasterInvoice-".$_GET["guia"].".pdf","F");
$pdf->Output();



