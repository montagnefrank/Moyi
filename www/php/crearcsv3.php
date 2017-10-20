<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL); 
//ini_set('display_errors', 1);


session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");
require_once ('PHPExcel.php');

session_start();
$user = $_SESSION["login"];
$pass = $_SESSION["pass"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
$hoy = date('Y-m-d');
$finca = $_SESSION["finca"];

//Aqui se crea el objeto de tipo excel 2007
	$objPHPExcel = new PHPExcel();	
	//Se le crean las propiedades
	$objProps= $objPHPExcel->getProperties();
	$objProps->setCreator("Bit-Store.ec");

	
	$objPHPExcel->setActiveSheetIndex(0); 
        $objActSheet = $objPHPExcel->getActiveSheet(); 


        $objActSheet->getColumnDimension('A')->setAutoSize(true); 
        $objActSheet->getColumnDimension('B')->setAutoSize(true);
        $objActSheet->getColumnDimension('C')->setAutoSize(true); 
        $objActSheet->getColumnDimension('D')->setAutoSize(true);
        $objActSheet->getColumnDimension('E')->setAutoSize(true); 
        $objActSheet->getColumnDimension('F')->setAutoSize(true);
        $objActSheet->getColumnDimension('G')->setAutoSize(true); 
        $objActSheet->getColumnDimension('H')->setAutoSize(true); 
        $objActSheet->getColumnDimension('I')->setAutoSize(true); 
        $objActSheet->getColumnDimension('J')->setAutoSize(true); 
        $objActSheet->getColumnDimension('K')->setAutoSize(true); 
        $objActSheet->getColumnDimension('L')->setAutoSize(true);
        $objActSheet->getColumnDimension('M')->setAutoSize(true); 
        $objActSheet->getColumnDimension('N')->setAutoSize(true); 
        $objActSheet->getColumnDimension('O')->setAutoSize(true); 
        $objActSheet->getColumnDimension('P')->setAutoSize(true);
        
        //Se crean el encabezado del documento
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Fecha Salida finca');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Fecha de Vuelo');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Agencia Carga');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Item');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Prod. Desc.');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Receta');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Pack');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Solicitadas');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Enviadas');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Rechazadas');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Cierre de Dia');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Por enviar');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'Valor Unitario');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'Valor A Pagar');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'Finca');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'Imagen');
				

//	if(!$_SESSION["fecha"]){
	  $sql = $_SESSION["sqlcsv"];
          $val = mysqli_query($link, $sql)or die ("Error seleccionando los pedidos");
          
//	}

	  //Recorro por cada nro pedido cada item
		$contador = 1;
		$j = 2;
		$i = 1;

		while($row = mysqli_fetch_array($val)){ 
                   
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$j, $row['destino']);
                        $j++;
                        
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$j, $row['fecha']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$j, $row['fecha_tentativa']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$j, $row['agencia']);
			//Selecciono cada nropedido los items
			$sentencia = "SELECT distinct item FROM tbletiquetasxfinca where fecha='".$row['fecha']."' AND fecha_tentativa='".$row['fecha_tentativa']."' AND finca='".$finca."' AND destino='".$row['destino']."' AND agencia='".$row['agencia']."' AND archivada = 'No' AND estado !='5' order by item";

			$consulta = mysqli_query($link, $sentencia)or die ("Error seleccionando los items con solicitudes");		

			//Por cada item cuento cuantas solicitudes hay
			while($fila = mysqli_fetch_array($consulta)){
                                $objPHPExcel->getActiveSheet()->getRowDimension($j)->setRowHeight(50);
				//Se cuenta cuantas solicitudes y entregas hay por cada finca e item
				$sql1 = "SELECT SUM(solicitado) as solicitado,SUM(entregado) as entregado, item, fecha, precio FROM tbletiquetasxfinca where fecha='".$row['fecha']."' AND finca='".$finca."' AND destino='".$row['destino']."' AND agencia='".$row['agencia']."' AND estado!='5' AND item = '".$fila['item']."' AND archivada = 'No' ";

				$val1 = mysqli_query($link, $sql1) or die ("Error sumando las cantidades de solicitudes y entregas de las fincas");
				$row1 = mysqli_fetch_array($val1);
				
				//Se cuenta cuantas solicitudes rechazadas hay por cada finca e item
				$sql2 = "SELECT COUNT(*) as rechazado,nropedido FROM tbletiquetasxfinca where estado='2' AND fecha = '".$row['fecha']."' AND finca='".$finca."' AND item = '".$fila['item']."' AND archivada = 'No' AND agencia='".$row['agencia']."' AND destino='".$row['destino']."'";
				
				$val2 = mysqli_query($link, $sql2) or die ("Error sumando las cantidades de solicitudes y entregas de las fincas");
				$row2 = mysqli_fetch_array($val2);
				
				//Se cuenta cuantas solicitudes con cierre de dia por cada finca e item
				$sql3 = "SELECT COUNT(*) as cierre FROM tbletiquetasxfinca where estado= '3' AND fecha = '".$row['fecha']."' AND finca='".$finca."' AND item = '".$fila['item']."' AND archivada = 'No' AND agencia='".$row['agencia']."' AND destino='".$row['destino']."'";
				$val3 = mysqli_query($link, $sql3) or die ("Error sumando las cantidades de solicitudes y entregas de las fincas");
				$row3 = mysqli_fetch_array($val3);

				//Seleccionando l adescripcion del item
				$sql4 = "SELECT tblproductos.prod_descripcion,tblproductos.pack,tblproductos.finca,tblproductos.item,tblproductos.receta FROM tblproductos
                                        INNER JOIN tbldetallereceta ON tblproductos.id_receta = tbldetallereceta.id_receta where id_item='".$row1['item']."'";

				$val4 = mysqli_query($link, $sql4) or die ("Error sumando las cantidades de solicitudes y entregas de las fincas");
				$row4 = mysqli_fetch_array($val4);

				$totalsol+= $row1['solicitado'];

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$j, $row1['item']);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$j, $row4['prod_descripcion']);
                                
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$j, $row4['receta']);
                                
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$j, $row4['pack']);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$j, $row1['solicitado']);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$j, $row1['entregado']);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$j, $row2['rechazado']);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$j, $row3['cierre']);

				//se restan las solicitudes - entregado - rechazado	
				
				$totalrech+= $row2['rechazado'];
				$totalent+= $row1['entregado'];
				$dif      = $row1['solicitado'] - $row1['entregado']- $row2['rechazado'] -$row3['cierre'];
				$totalcierre += $row3['cierre'];
				$totaldif+=$dif;
				$totalvalor += $row1['precio'];
				$totalprecio += $row1['precio'] * $row1['solicitado']; 

				//Por enviar
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$j, $dif);

				//VALOR unitario
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$j, number_format($row1['precio'],2));
                                
				//valora pagar
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$j, number_format($row1['precio'] * $row1['solicitado'],2));
                                
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$j, $finca);
				
                                
                                //codigo para crear la imagen del producto
                                if(is_file("../images/productos/".$row4['item'].".jpg")){ 
                                   
                                    $objDrawing = new PHPExcel_Worksheet_Drawing();
                                    $objDrawing->setName('Logo');
                                    $objDrawing->setDescription('Logo');
                                    $objDrawing->setPath("../images/productos/".$row4['item'].".jpg");
                                    $objDrawing->setHeight(45);
                                    $objDrawing->setCoordinates('P'.$j);
                                    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
                                }        
                                $j++;
			}//fin while intermedio

			$salida_cvs .= "Total solicitadas: ".$totalsol."\n";
			$salida_cvs .= "Total enviadas: ".$totalent."\n";
			$salida_cvs .= "Total rechazadas: ".$totalrech."\n";
			$salida_cvs .= "Total cierre: ".$totalcierre."\n";
			$salida_cvs .= "Total por enviar: ".$totaldif."\n";

		  	//Contar los subtotales
			$TOTALSOL    += $totalsol;
			$TOTALENT    += $totalent;
			$TOTALRECH   += $totalrech;
			$TOTALCIERRE += $totalcierre;
			$TOTALDIF    += $totaldif;
			$TOTALVALOR  += $totalvalor;
			$TOTALPRECIO += $totalprecio;
			
                        //aumentar el contador
			$contador++;
			$i++;
			//Resultados finales
			//$objPHPExcel->getFont()->setBold(true);
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFont()->setBold(true);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$j, "TOTAL GENERAL");
                        
                        $objPHPExcel->getActiveSheet()->getStyle('H'.$j)->getFont()->setBold(true);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$j, $TOTALSOL);
                        $objPHPExcel->getActiveSheet()->getStyle('I'.$j)->getFont()->setBold(true);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$j, $TOTALENT);
                        $objPHPExcel->getActiveSheet()->getStyle('J'.$j)->getFont()->setBold(true);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$j, $TOTALRECH);
                        $objPHPExcel->getActiveSheet()->getStyle('K'.$j)->getFont()->setBold(true);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$j, $TOTALCIERRE);
                        $objPHPExcel->getActiveSheet()->getStyle('L'.$j)->getFont()->setBold(true);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$j, $TOTALDIF);
                        $objPHPExcel->getActiveSheet()->getStyle('M'.$j)->getFont()->setBold(true);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$j, $TOTALVALOR);
                        $objPHPExcel->getActiveSheet()->getStyle('N'.$j)->getFont()->setBold(true);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$j, $TOTALPRECIO);
                        $j++;
                        //Resetear los subtotales
			$TOTALSOL    = 0;
			$TOTALENT    = 0;
			$TOTALRECH   = 0;						
 			$TOTALCIERRE = 0;
			$TOTALDIF    = 0;
			$TOTALVALOR  = 0;
			$TOTALPRECIO = 0;
                        
                        $totalsol=0;
			$totalent=0;
			$totalrech=0;
			$totalcierre=0;
			$totaldif=0;
			$totalvalor=0;
			$totalprecio=0;
  
		
		}//Fin del while contenedor

	//Aqui se crea el docuemnto completo con todas las filas insertadas
	$objPHPExcel->getActiveSheet()->setTitle('Hoja1');

	$objPHPExcel->setActiveSheetIndex(0);	
	
	//Worksheet estilo predeterminado (de forma predeterminada diferentes necesidades y Preferencias Configurar individualmente)
	$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);

   // Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="Print.xlsx"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
?>