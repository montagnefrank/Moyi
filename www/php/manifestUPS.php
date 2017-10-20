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

if(isset($_GET['master']) && $_GET['master']=='generar'){
        class PDF extends FPDF
        {
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

            function Tabla1()
           {
                  $this->SetFont('Arial','B',12);
                  $this->Cell(45,5,"Service",0,0,'L');
                  $this->Cell(35,5,"Shipments",0,0,'C');
                  $this->Cell(35,5,"Packages",0,0,'C');
                  $this->Cell(45,5,"Return Shipments",0,0,'C');
                  $this->Cell(45,5,"Return Packages",0,0,'C');
                  $this->Cell(60,5,"Import Control Shipments",0,0,'C');
                  $this->Ln();

           } 

            function SetCol($col)
            {
            // Establecer la posición de una columna dada
            $this->col = $col;
            $x = 10+$col*190;
            $this->SetLeftMargin($x);
            $this->SetX($x);
            } 

        //************** Fin del código para ajustar texto *****************
        //******************************************************************
        }

        //Creacion del objeto pdf		
        $pdf = new PDF('L','mm','A4');
        $pdf->SetLeftMargin(3);

        $pdf->AddPage();
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(100,7,"UPS Manifest - Summary Section",0,0,'L');
        $pdf->Cell(100,7,"UPS WorldShip 18.0.35",0,0,'C');
        $pdf->Ln();
        $pdf->Cell(290,0,"",1);
        $pdf->Ln();

        //consulta para seleccionar los datos de la cuenta
        $cuenta=substr($_GET["guia"], 0,6); 
        $sql0="SELECT * FROM tblclienteups WHERE cuenta='".$cuenta."'";
        $query0 = mysqli_query($sql0,$conection);
        $row=mysqli_fetch_array($query0);

        $pdf->SetFont('Arial','',10);
        $pdf->SetCol(0);
        $pdf->SetY(20);

        $pdf->Cell(85,5,$row['compañia']);
        $pdf->Ln();
        $pdf->Cell(85,5,$row['direccion']);
        $pdf->Ln();
        $pdf->Cell(10,5,$row['codigo_postal']);
        $pdf->Cell(10,5,$row['pais']);
        $pdf->Ln();
        if($row['pais']=='Ecuador')
            $pdf->Cell(10,5,"EC");
        if($row['pais']=='United States')
            $pdf->Cell(10,5,"US");
        $pdf->Ln();
        $pdf->Cell(20,5,"VAT No.");
        $pdf->Ln();
        $pdf->Cell(20,5,"Telephone");
        $pdf->Cell(65,5,$row['telefono']);

        $pdf->SetCol(1);
        $pdf->SetY(20);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(60,5,"UPS Account Number:");
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(15,5,$_GET['guia'],0,0,"R");
        $pdf->Ln();
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(60,5,"Shipping Record Number:");
        $pdf->Cell(15,5,"");
        $pdf->Ln();
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(60,5,"Report Print Date:");
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(15,5,date("d/M/Y"),0,0,"R");
        $pdf->Ln(15);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(60,5,"Collection Date:");
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(15,5,date("d/M/Y"),0,0,"R");
        $pdf->Ln();

        $pdf->SetX(3);
        $pdf->Cell(290,0,"",1);
        $pdf->Ln(8);

        $pdf->SetX(3);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(60,5,"Shipment Summary");
        $pdf->Ln(10);

        $pdf->SetX(3);
        $pdf->Tabla1();
        $sql="SELECT count(cpservicio) as cpservicio1,cpservicio
                FROM
                tbldetalle_orden
                INNER JOIN tblproductos ON tbldetalle_orden.cpitem = tblproductos.id_item
                INNER JOIN tblguiamaster ON tbldetalle_orden.guiamaster = tblguiamaster.id
                where guia='".$_GET['guia']."' GROUP BY cpservicio";

        $query = mysqli_query($sql,$conection);
        $total=0;

        $pdf->SetCol(0);
        $pdf->SetFont('Arial','',10);
        while ($row = mysqli_fetch_array($query)){
            $pdf->SetCol(0);
           if($row['cpservicio']=="ES")
            $pdf->Cell(40,5,"UPS Worldwide Express",0,0,'L');
           if($row['cpservicio']=="SV")
            $pdf->Cell(40,5,"UPS SAVER",0,0,'L');

            $pdf->Cell(35,5,"1",0,0,'C');
            $pdf->Cell(35,5,$row['cpservicio1'],0,0,'C');
            $pdf->Cell(45,5,"",0,0,'C');
            $pdf->Cell(45,5,"",0,0,'C');
            $pdf->Cell(60,5,"",0,0,'C'); 
            $total=$total+$row['cpservicio1'];
            $pdf->Ln();
        }

        $pdf->SetCol(0);
        $pdf->Cell(40,5,"Collection Totals",0,0,'R');
        $pdf->Cell(35,5,"1",0,0,'C');
        $pdf->Cell(35,5,(int)$total+1,0,0,'C');
        $pdf->Cell(45,5,"",0,0,'C');
        $pdf->Cell(45,5,"",0,0,'C');
        $pdf->Cell(60,5,"",0,0,'C'); 

        $pdf->Ln();
        $pdf->SetY(-85);
        $pdf->SetCol(0);
        $pdf->SetLeftMargin(65);
        $pdf->Cell(160,5,"For UPS Use Only","LT",0);
        $pdf->Cell(40,5,"0","T",0);
        $pdf->Cell(10,5,"0","TR",0);
        $pdf->Ln();
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(160,5,"Additional Handling Waybill","L",0);
        $pdf->Cell(40,5,"0",0,0);
        $pdf->Cell(10,5,"0","R",0);
        $pdf->Ln();
        $pdf->Cell(160,5,"Additional Handling Manifest","L",0);
        $pdf->Cell(40,5,"0",0,0);
        $pdf->Cell(10,5,"0","R",0);
        $pdf->Ln();
        $pdf->Cell(160,5,"Additional Handling Total","L",0);
        $pdf->Cell(40,5,"0",0,0);
        $pdf->Cell(10,5,"0","R",0);
        $pdf->Ln();
        $pdf->Cell(160,5,"Dangerous Goods Total","LB",0);
        $pdf->Cell(40,5,"0","B",0);
        $pdf->Cell(10,5,"0","BR",0);
        $pdf->Ln(10);


        $pdf->SetLeftMargin(65);
        $pdf->Cell(60,5,"","LT",0);
        $pdf->Cell(50,5,"","T",0);
        $pdf->Cell(20,5,"","TR",0);
        $pdf->Ln();

        $pdf->Cell(6,5,"","L",0);
        $pdf->Cell(60,5,"Received for UPS by",0,0);
        $pdf->Cell(50,5,"","B",0);
        $pdf->Cell(14,5,"","R",0);
        $pdf->Ln();
        $pdf->Cell(6,5,"","L",0);
        $pdf->Cell(60,5,"Collection Time",0,0);
        $pdf->Cell(50,5,"","B",0);
        $pdf->Cell(14,5,"","R",0);
        $pdf->Ln();
        $pdf->Cell(6,5,"","L",0);
        $pdf->Cell(60,5,"Collection Date",0,0);
        $pdf->Cell(50,5,"","B",0);
        $pdf->Cell(14,5,"","R",0);
        $pdf->Ln();
        $pdf->Cell(6,5,"","L",0);
        $pdf->Cell(60,5,"Number of Packages",0,0);
        $pdf->Cell(50,5,"","B",0);
        $pdf->Cell(14,5,"","R",0);
        $pdf->Ln();
        $pdf->Cell(60,5,"","LB",0);
        $pdf->Cell(50,5,"","B",0);
        $pdf->Cell(20,5,"","BR",0);

        //generando el pdf
        $pdf->Output("./Documentos/ManifestUPS-".$_GET["guia"].".pdf","F");
        $pdf->Output();
}
else if(isset($_GET['master']) && $_GET['master']=='cierredia')
{
    //verifico si esa guia ya esta como cierre de dia en cuyo caso retorno error
    $sql="SELECT cierredia FROM tblguiamaster WHERE guia='".$_GET["guia"]."'";
    $query = mysqli_query($sql,$conection);
    $row=mysqli_fetch_row($query);
    if($row[0]=='SI'){
     echo json_encode('existe');
       return;
    }
    
    $numcuenta= substr($_GET["guia"],0,6);
    //cuando se hace el cierre de dia, es necesario asignarle a la guia master un numero de libro y una pagina
    $sql1="select MAX(id) from tblguiamaster  where guia like '".$numcuenta."%' and page!=''";

    $query1 = mysqli_query($sql1,$conection);
    $row=mysqli_fetch_row($query1);

    $book='';
    $page='';
    

    //Si la consulta no devuelve filas es que es la primera 
    if($row[0]!='')
    {
      $sql2="select page,book from tblguiamaster where id='".$row[0]."'";
      $query2 = mysqli_query($sql2,$conection);

        while($fila=mysqli_fetch_array($query2))
        {
          $book= $fila['book'];
          $page= substr($fila['page'],0,2);

          //si la pagina que le sigue es la 100, tengo que cambiar de libro
          if(intval($page+1) ==100)
          {
             $sql3= "select book1,book2 from tblclienteups where cuenta='".$numcuenta."'";
             $query3 = mysqli_query($sql3,$conection);
             while($row3=  mysqli_fetch_array($query3))
             {
                if($book==$row3['book1'])
                   $book= $row3['book2'];
                else if($book==$row3['book2'])
                   $book= $row3['book1'];
             }
             $page="00";

          }
          else
            $page= str_pad(intval($page+1),2,"0",STR_PAD_LEFT);
        }    
    }
    else
    {
        $sql3= "select book1 from tblclienteups where cuenta='".$numcuenta."'";

        $query3 = mysqli_query($sql3,$conection);
        while($row3=  mysqli_fetch_array($query3))
        {
          $book= $row3['book1'];
        }
        $page="00"; 
    }


    //algoritmo para el digito de chequeo
    //1.bookpage/7 -- obtener la parte entera

    $paso1=intval(($book.$page)/7);
    //2.el resultado multiplicarlo por 7
    $paso2= intval($paso1*7);
    //3. Restar bookpage-$paso2
    $check= intval($book.$page)-$paso2;

    $page=$page.$check;

    //
    //actualizo la tbl guiacomercial para ponerle closeout
    $sql="UPDATE tblguiamaster SET closeout='',cierredia='SI',book='".$book."',page='".$page."' WHERE guia='".$_GET["guia"]."'";
    $query = mysqli_query($sql,$conection);
    if($query)
    {
       echo json_encode('ok');
       return;
    }
    else
    {
       echo json_encode('error');
       return;  
    }
}