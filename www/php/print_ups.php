<?php 
//ini_set('display_errors', 'On');
//ini_set('display_errors', 1);

	require('fpdf/fpdf.php');
	require_once('barcode.inc.php');
	//require_once('barcode39.php');
	require_once('barcode39UPS.php');

	include ("conectarSQL.php");
        include ("conexion.php");
	include ("seguridad.php");
	include ("calculosUPS.php");
	$finca = $_GET['finca'];
	$fecha = $_GET['fecha'];

	$sql    =  $_SESSION["sql"];
	$user   =  $_SESSION["login"];
	$rol    =  $_SESSION["rol"];
	$pais   =  $_SESSION["pais"];
	$ip     =  $_SERVER['REMOTE_ADDR'];

	shp("1Z123X566620754864");
        
	$directorio = opendir('URC'); //ruta actual

	while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
	{
	    if (!is_dir($archivo))	//verificamos si es o no un directorio
	    {
	    	$archivoNombre = $archivo; 
	    	$archivo = "URC"."\\".$archivo;
			//Leer la primera linea para obtener la version del archivo URC
			$file = fopen($archivo, "r") or exit("Unable to open file!");
			//Output a line of the file until the end is reached
			$line = fgets($file);
			$linepos = strpos($line,'URC')+3;
			$line = substr($line, $linepos);
			$line = str_replace(',','',$line);
			
			//Version del URC
			$urc = str_replace('"',' ',$line);

			/*while(!feof($file))
			{
				echo fgets($file). "<br />";
			}*/
			fclose($file);
		}
	}

	function recibe_imagen ($url_origen,$archivo_destino){ 
		$mi_curl = curl_init ($url_origen); 
		$fs_archivo = fopen ($archivo_destino, "w"); 
		curl_setopt ($mi_curl, CURLOPT_FILE, $fs_archivo); 
		curl_setopt ($mi_curl, CURLOPT_HEADER, 0); 
		curl_exec ($mi_curl); 
		curl_close ($mi_curl); 
		fclose ($fs_archivo); 
	}

	recibe_imagen("http://barcode.tec-it.com/barcode.ashx?translate-esc=off&data=Este+es+mi+Maxi+Code&code=MaxiCode&unit=Fit&dpi=600&imagetype=Jpg&rotation=0&color=000000&bgcolor=FFFFFF&qunit=Mm&quiet=0","etiqueta.jpg");

        $link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());
	$query=mysql_query($sql,$link);



	
	//Obtengo los datos del cliente UPS
	$sql2   = "SELECT * FROM tblclienteups";
	$query2 = mysql_query($sql2,$link);
	$row2 = mysql_fetch_array($query2);

	//Creacion del objeto pdf		
	$pdf = new FPDF('P','mm',array(90,140));

	//$pdf = new FPDF('P','mm',array(102,158));

	//Recorro todos las solicitudes para esa finca con ese item y genero el codigo de barras del codigo unico
	while($row = mysql_fetch_array($query)){
			//new barCodeGenrator(''.$row['codigo'].'',1,'./barscode/Barcode_'.$row['codigo'].'.gif',180,140, true);
			
			Barcode39 ($row['codigo']);

			//Barcode39 ('GA 301 9-01');  //Tener en cuenta que hay que sacar este valor de una consult


			//por cada codigo distinto imprimo una pagina
			$pdf->Ln(0);
			$pdf->SetTopMargin(3);
			$pdf->SetLeftMargin(0);

			$pdf->AddPage();
			$pdf->SetFont('Arial','',8);

			#Establecemos los márgenes izquierda, arriba y derecha: 
			//$pdf->SetMargins(0,0,0); 
			$pdf->SetAutoPageBreak(false,0);
			$pdf->SetLeftMargin(0);
			$pdf->Ln(0);
			$pdf->Cell(0,0,strtoupper($row2['nombre']),0,0,'L');
			
			$pdf->SetLeftMargin(52);
			$pdf->Ln(0);
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(0,0,$row['wheigthKg']." KG",0,0,'L');
			
			$pdf->SetLeftMargin(73);
			$pdf->Ln(0);
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(0,0,'1 OF 1',0,0,'L');

			$pdf->SetFont('Arial','',8);
			$pdf->SetLeftMargin(0);
			$pdf->Ln(2.5);
			$pdf->Cell(0,0,strtoupper($row2['telefono']),0,0,'L');
			$pdf->Ln(2.5);
			$pdf->Cell(0,0,strtoupper ($row2['compañia']),0,0,'L');

			$pdf->SetLeftMargin(55);
			$pdf->Ln(0);
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(0,0,'SHP#: 1X2X 3X33 3VP',0,0,'L');
			$pdf->SetLeftMargin(0);
			$pdf->Ln(2.5);

			$pdf->Cell(0,0,strtoupper($row2['direccion_extendida']),0,0,'L');

			$pdf->SetLeftMargin(55);
			$pdf->Ln(0);
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(0,0,'SHP WT: '.$row['wheigthKg'].' KG',0,0,'L');
			$pdf->SetLeftMargin(0);
			$pdf->Ln(2.5);

			$pdf->Cell(0,0,strtoupper($row2['direccion']),0,0,'L');
			$pdf->SetLeftMargin(55);
			$pdf->Ln(0);
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(0,0,'SHP DWT: 50.5 KG',0,0,'L');
			$pdf->SetLeftMargin(0);
			$pdf->Ln(2.5);
                        
                        $pdf->Cell(0,0,strtoupper($row2['codigo_postal']),0,0,'L');
                        $pdf->SetLeftMargin(55);
			$pdf->Ln(0);
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(0,0,'DATE: 27 JUN 2014',0,0,'L');
			$pdf->SetLeftMargin(0);
                        
                        $pdf->Ln(2.5);
			$pdf->Cell(0,0,strtoupper($row2['pais']),0,0,'L');
			$pdf->Ln(2.5);
			//$pdf->Cell(0,0,'COUNTRY',0,0,'L');

			//Linea separadora del country y el URC
			/*$pdf->Line(27.1,45.5,90,45.5);
			$pdf->Line(27.1,45.6,90,45.6);
			$pdf->Line(27.1,45.7,90,45.7);*/

			// Linea horizontal del Maxicode
			$pdf->Line(0,47,90,47);
			$pdf->Line(0,47.1,90,47.1);
			$pdf->Line(0,47.2,90,47.2);
			$pdf->Line(0,47.3,90,47.3);

			// Linea vertical del Maxicode
			$pdf->Line(27.1,47.1,27.1,72.6);
			$pdf->Line(27.2,47.1,27.2,72.6);
			$pdf->Line(27.3,47.1,27.3,72.6);

			//Maxicode
			$pdf->Image('etiqueta.jpg',1,48.25,25,25);
			
			//$pdf->Image('http://barcode.tec-it.com/barcode.ashx?translate-esc=off&data=Este+es+mi+Maxi+Code&code=MaxiCode&unit=Fit&dpi=256&imagetype=Jpg&rotation=0&color=000000&bgcolor=FFFFFF&qunit=Mm&quiet=0',5,0,80);

			//$pdf->Cell(0,27,''.utf8_decode($_GET['finca']).'',0,0,'C');

			$pdf->SetFont('Arial','B',10);
			$pdf->Ln(2);	
			$pdf->SetLeftMargin(0);
			$pdf->Cell(0,0,'SHIP TO:',0,'L');
			$pdf->Ln(3.5);
			$pdf->SetLeftMargin(5);
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(0,0,strtoupper($row['shipto1']),0,'L');
			$pdf->Ln(3.5);
			//$pdf->SetFont('Arial','B',10);
			//$pdf->Cell(0,0,'TO:',0,'L');
			//$pdf->SetLeftMargin(31);
			$pdf->SetFont('Arial','',10);
			$pdf->Ln(0);
			$pdf->Cell(0,0,$row['cptelefono_shipto'],0,'L');
			$pdf->Ln(3.5);
			if($row['shipto2']!=''){
				$pdf->Cell(0,0,strtoupper($row['shipto1'])." / ".strtoupper($row['shipto2']),0,'L');
			}
			else{
				$pdf->Cell(0,0,strtoupper($row['shipto1']));
			}
			$pdf->Ln(3.5);
			$pdf->Cell(0,0,strtoupper($row['direccion']),0,'L');
			$pdf->Ln(3.5);
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(0,0,strtoupper($row['cpcuidad_shipto'])." ".strtoupper($row['cpestado_shipto'])." ".strtoupper($row['cpzip_shipto']),0,'L');
			$pdf->Ln(4.3);
			//$pdf->Cell(0,0,'POSTAL CODE LINE',0,'L');

			$sqlpais = "SELECT * FROM tblpaises_destino WHERE codpais = '".$row['cppais_envio']."'";
			$querypais = mysql_query($sqlpais);
			$rowpais = mysql_fetch_array($querypais);

			$pdf->Cell(0,0,$rowpais['pais_dest'],0,'L');

			// Salto de línea
			$pdf->SetLeftMargin(30);
			$pdf->Ln(12);
			$pdf->SetFont('Arial','',24);
			$pdf->Cell(0,0,'CAN 516 5-00',0,0,'C');

			//$pdf->Image('./barscode/Barcode_'.$codigo.'.png',10 ,26, 70 , 27);
			//Buscar la descripcion del item
			//$sentencia = "SELECT prod_descripcion, id_item FROM tblproductos WHERE id_item='".$row['item']."'";
			//$consulta  = mysql_query($sentencia,$link);
			//$fila      = mysql_fetch_array($consulta);
			
			//$pdf->Cell(0,35,'ITEM '.utf8_decode($fila['id_item']).'',0,0,'C');

			$pdf->Ln(-5.5);
			$pdf->SetFont('Arial','B',8);
			
			//Codigo de barras del URC

			//$codebar = $codigo.'-'.$row['item'].'-'.$row['codigo'];
			
			$codebar = 'CAN 516 5-00';

			Barcode39 ($codebar);

			//$pdf->Image('./barscode/Barcode_'.$codigo.'-'.$row['item'].'-'.$row['codigo'].'.png',25 ,65, 50 , 15);

			$pdf->Image('./barscode/Barcode_'.$codebar.'.png',30 ,59, 0 , 13);

			//Linea gruesa separadora inferior de Maxicode 
			$pdf->Line(0,72.7,90,72.7);
			$pdf->Line(0,72.8,90,72.8);
			$pdf->Line(0,72.9,90,72.9);
			$pdf->Line(0,73,90,73);
			$pdf->Line(0,73.1,90,73.1);
			$pdf->Line(0,73.2,90,73.2);
			$pdf->Line(0,73.3,90,73.3);
			$pdf->Line(0,73.4,90,73.4);
			$pdf->Line(0,73.5,90,73.5);
			$pdf->Line(0,73.6,90,73.6);
			$pdf->Line(0,73.7,90,73.7);
			//Fin de linea gruesa separadora inferior de Maxicode 

			$pdf->SetLeftMargin(0);
			$pdf->Ln(30);
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(0,0,''.'UPS EXPRESS',0,0,'L');

			$pdf->SetLeftMargin(0);
			$pdf->Ln(4);
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(0,0,''.'TRACKING #: '.$row['tracking'],0,0,'L');    //Tracking #

			$pdf->SetLeftMargin(81);
			$pdf->SetFont('Arial','',30);
			$pdf->Ln(-1.7);
			$pdf->Cell(0,0,''.'1',0,0,'L');   //Service Icon

			$pdf->SetLeftMargin(0);
			$pdf->Ln(3);
			$pdf->SetFont('Arial','',10);

			$pdf->Ln(1);
			$pdf->SetFont('Arial','B',8);
			//$codebar = $codigo.'-'.$row['item'].'-'.$row['codigo'];
			
			//POSTAL CODE codigo de barras del tracking #

			$codebar = '1Z 1X2 X3X 66 1234 5676';
			Barcode39 ($codebar);

			//$pdf->Image('./barscode/Barcode_'.$codigo.'-'.$row['item'].'-'.$row['codigo'].'.png',20 ,78, 50 , 15);
			$pdf->Image('./barscode/Barcode_'.$codebar.'.png',5 ,87, 80, 25);
			
			//Linea de apertura del codigo de barras grande
			$pdf->Line(0,85.5,90,85.5);
			$pdf->Line(0,85.6,90,85.6);
			$pdf->Line(0,85.7,90,85.7);
			$pdf->Line(0,85.8,90,85.8);
			$pdf->Line(0,85.9,90,85.9);
			//Fin de linea de apertura del codigo de barras grande

			//Linea gruesa de cierre del codigo de barras grande
			$pdf->Line(0,112.7,90,112.7);
			$pdf->Line(0,112.8,90,112.8);
			$pdf->Line(0,112.9,90,112.9);
			$pdf->Line(0,113,90,113);
			$pdf->Line(0,113.1,90,113.1);
			$pdf->Line(0,113.2,90,113.2);
			$pdf->Line(0,113.3,90,113.3);
			$pdf->Line(0,113.4,90,113.4);
			$pdf->Line(0,113.5,90,113.5);
			$pdf->Line(0,113.6,90,113.6);
			$pdf->Line(0,113.7,90,113.7);
			//Linea gruesa de cierre del codigo de barras grande

			$pdf->Ln(33);
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(0,0,''.'BILLING: P/P TPS E27A19 US',0,0,'L');
			
			$pdf->SetLeftMargin(65);
			$pdf->SetFont('Arial','B',16);
			$pdf->Ln(0);
			$pdf->Cell(0,0,''.'INV-CC',0,0,'L');
			
			$pdf->SetFont('Arial','',10);
			$pdf->SetLeftMargin(0);
			$pdf->Ln(4);
			$pdf->Cell(0,0,''.'DESC: '.$row['gen_desc'],0,0,'L');  //Descripción del item

			/*$pdf->SetLeftMargin(60);
			$pdf->SetFont('Arial','',10);
			$pdf->Ln(0);
			$pdf->Cell(0,0,''.'POA',0,0,'L');*/

			$pdf->Ln(4);
			$pdf->Cell(0,0,''.'Invoice No.: '.$row['Ponumber'],0,0,'L');   //PONUMBER

			$pdf->Ln(4);
			$pdf->Cell(0,0,''.'Purchase No.: '.$row['Custnumber'].' / '.'ITEM: '.$row['cpitem'],0,0,'L');   //Custnumber


			$pdf->SetLeftMargin(40);
			$pdf->SetFont('Arial','',6);
			$pdf->Ln(3);
			$pdf->Cell(0,0,''.'WS 18.0.30 Zebra ZP 450  '.$urc,0,0,'L');

			//$pdf->Line(0, 100 , 200, 100);  //Horizontal

			//new barCodeGenrator(''.$codigo.'-'.$item.'-'.$row['codigo'].'',1,'./barscode/Barcode_'.$codigo.'-'.$item.'-'.$row['codigo'].'.png',180,140, true);

			$mensaje = $row['cpmensaje'];
                         
			$resultado = strpos($mensaje,'Blank Info');

			if($resultado==false){

				$pdf->AddPage();
				$pdf->SetFont('Arial','B',10);
				$pdf->SetLeftMargin(0);
				$pdf->Ln(0);
				$pdf->Cell(0,0,$row['Ponumber'],0,0,'L');
				$pdf->Ln(4);
				$pdf->Cell(0,0,$row['Custnumber'],0,0,'L');
				$pdf->Ln(4);
				$pdf->Line(0,14,90,14);
				$pdf->Cell(0,0,$row['cpitem'].' - - - '. '1ZWY11906652989421',0,0,'L');
				$pdf->Ln(7);


				$mensaje = $row['cpmensaje'];
				$pos = strripos($mensaje, ':');  //Ultima posicion de :
				$pos++;
				$cortar = iconv_substr($mensaje,0,$pos); //Cortar hasta ultima posicion de : para obtener el To y el From
				//To
				$posTo = strpos($cortar, ':',0);  //Obtener primera posicion de : (hasta ahi llega el To)
				$cortarTo = iconv_substr($mensaje,0,$posTo);
				$cortarTo = str_replace('To-','',$cortarTo);
				//Mensaje
				$mensaje = str_replace($cortar,'',$mensaje);
				$mensaje = str_replace('\\','',$mensaje);
				//From
				$cortarFrom = str_replace('To-'.$cortarTo.'::From-','',$cortar);
				$cortarFrom = str_replace(':','',$cortarFrom);


				$pdf->Cell(0,0,'To - '.$cortarTo,0,0,'L');
				$pdf->Ln(7);
				$pdf->Cell(0,0,'From - '.$cortarFrom,0,0,'L');
				$pdf->Ln(10);
				$pdf->SetFont('Arial','B',12);


				//$pdf->WordWrap($mensaje,80);
				$pdf->Write(5,$mensaje);
			}
	} // Fin del while
	$pdf->Output("Etiquetas.pdf","I");
  ?>
