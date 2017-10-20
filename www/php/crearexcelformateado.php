<?php


	ini_set('memory_limit', '-1');
        include('conectarSQL.php');
	require_once ('PHPExcel.php');
	include ("conexion.php");
	include ("seguridad.php");
        include ("codigounico.php");
	session_start();
	$sql    =  $_SESSION["sql"];
	$user   =  $_SESSION["login"];
	$rol    =  $_SESSION["rol"];
	$pais   =  $_SESSION["pais"];
	$ip     =  $_SERVER['REMOTE_ADDR'];

	$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());
	
        //OBTENIENDO LA FINCA DEL USUARIO
        $sqll   = "SELECT finca FROM tblusuario WHERE cpuser = '".$user."'";
        $queryy = mysql_query($sqll, $link) or die ("Error seleccionando la finca de este usuario");
        $roww   = mysql_fetch_array($queryy);
        $finca = $roww['finca'];
        
        //ejecuto la consulta general
        $query=mysql_query($sql,$link);
	
               
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
        $objActSheet->getColumnDimension('Q')->setAutoSize(true); 
        $objActSheet->getColumnDimension('R')->setAutoSize(true);
        $objActSheet->getColumnDimension('S')->setAutoSize(true); 		
        //Se crean el encabezado del documento
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'PackageVoidIndicator')
                    ->setCellValue('B1', 'Collectiondate')
                    ->setCellValue('C1', 'Reference1')
                    ->setCellValue('D1', 'Reference4')
                    ->setCellValue('E1', 'Reference3')
                    ->setCellValue('F1', 'TrackngNumber')
                    ->setCellValue('G1', 'ShipToCompany')
                    ->setCellValue('H1', 'ShipToAttention')
                    ->setCellValue('I1', 'ShipToAddress1')
                    ->setCellValue('J1', 'ShipToCity')
                    ->setCellValue('K1', 'ShipToState')
                    ->setCellValue('L1', 'ShipToPostal')
                    ->setCellValue('M1', 'ShipToCountryTerritory')
                    ->setCellValue('N1', 'Guia_Madre')
                    ->setCellValue('O1', 'Guia_Hija')
                    ->setCellValue('P1', 'Fecha_vuelo')
                    ->setCellValue('Q1', 'Fecha_Llegada_cliente')
                    ->setCellValue('R1', 'Servicio')
                    ->setCellValue('S1', 'Aerolinea')
                    ->setCellValue('T1', 'Consolidado');
	$j=2;
	
               
	//recorro todas las filas y en cada una escribo el valor de cada celda
        while($row=mysql_fetch_array($query))
        {
           if($row["estado_orden"]=='Active' && $row["tracking"]==''){
               //Se genera el codigo unico de la caja
                $codigo = generarCodigoUnico();
                //Se inserta en la tabla de codigos
                $consulta = "INSERT INTO tblcodigo (`codigo`,`finca`) VALUES ('$codigo','$finca')";
                $ejecutar = mysql_query($consulta,$link) or die ("Error insertando el código único");  
                
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$j, "")
                            ->setCellValue('B'.$j, "")
                            ->setCellValueExplicit('C'.$j, $row["Ponumber"],PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValueExplicit('D'.$j, $row["Custnumber"],PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValue('E'.$j, $row["cpitem"])  
                            ->setCellValueExplicit('F'.$j, $codigo,PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValue('G'.$j, "")
                            ->setCellValue('H'.$j, $row["shipto1"]) 
                            ->setCellValue('I'.$j, $row["direccion"])
                            ->setCellValue('J'.$j, $row["cpcuidad_shipto"])
                            ->setCellValue('K'.$j, $row["cpestado_shipto"])
                            ->setCellValue('L'.$j, $row["cpzip_shipto"])
                            ->setCellValue('M'.$j, $row["cppais_envio"])
                            ->setCellValue('N'.$j, "")
                            ->setCellValue('O'.$j, "")
                            ->setCellValue('P'.$j, $row["ShipDT_traking"])
                            ->setCellValue('Q'.$j, $row["delivery_traking"])
                            ->setCellValue('R'.$j, $row["cpservicio"])
                            ->setCellValue('S'.$j, "")
                            ->setCellValue('T'.$j, "Y");
                $j++;     
                //Marco que la orden fue descargado y por quien
                if( $rol >= 2){
                    $codi = $row["id_detalleorden"];
                    $sql="UPDATE tbldetalle_orden SET descargada='Downloaded', user='".$user."', status='Ready to ship' where id_detalleorden=".$codi.";";
                    mysql_query($sql)or die ("Error updating...");

                    //Guardar datos de la operacion de subida de ordenes
                    /******* Subir Orden ********************/
                    /******* Descargar Orden ****************/
                    /******* Subir tracking  ****************/

                    $fecha = date('Y-m-d H:i:s');
                    $SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`) VALUES ('$user','Descargar Orden','$fecha','$ip')";
                    $consultaHist = mysql_query($SqlHistorico,$link) or die ("Error actualizando la bitacora de usuarios");	
                }
                
            }
        }

	//Aqui se crea el docuemnto completo con todas las filas insertadas
	$objPHPExcel->getActiveSheet()->setTitle('Hoja1');
	$objPHPExcel->setActiveSheetIndex(0);	
	
	//Worksheet estilo predeterminado (de forma predeterminada diferentes necesidades y Preferencias Configurar individualmente)
	$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);

   // Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="Order.xlsx"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
?>