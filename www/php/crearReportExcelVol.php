<?php
    /*****Este fichero permite crear reportes en excel que tienen la estructura ********/
	/************Nombre del fichero, Titulo , Titulo de las columnas , una consulta para el primer filtro y una segunda para los datos a mostrar*************************/
	
	//Se agregan los includes y bibliotecas
	include('conectarSQL.php');
	require_once ('PHPExcel.php');
	include ("conexion.php");
	include ("seguridad.php");
	
	//Se incia la session para tener acceso a las variables de session
	session_start();
	
	//recogiendo los datos para crear el reporte
	$titulo = $_SESSION["titulo"];
	$columnas = $_SESSION["columnas"];
	$filtro = $_SESSION["filtro"];
	
	$sql1 = $_SESSION["consulta1"];
	//echo $sql1."<br>";
	$sql2 = $_SESSION["consulta2"];
	//echo $sql2;
	$nombre_fichero = $_SESSION["nombre_fichero"];

	//exit();


	//Verificar si hay datos en las variables de session
	if($sql1 == '' && $sql2 == '' && $titulo == '' && $columnas == '' && $filtro == '' && $nombre_fichero == ''){
		   header("Location:".$_SERVER['HTTP_REFERER']); 
		}

	//se establece una conecion con la bd
	$conection = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());
	
	//Aqui se crea el objeto de tipo excel 2007
	$objPHPExcel = new PHPExcel();
		
	//Se le crean las propiedades
	$objProps= $objPHPExcel->getProperties();
	$objProps->setCreator("Bit-Store.ec");

	//Aqui se activa la primera hoja del excel
	$objPHPExcel->setActiveSheetIndex(0); 
    $objActSheet = $objPHPExcel->getActiveSheet();
	
	//Escribir los titulos de las columnas
	//Convirtendo la cadena de titulos en un array
	//$TIT_COLUM = array("COLUMNA 1","COLUMNA 2", "COLUMNA 3","COLUMNA 4");
	$TIT_COLUM = $columnas;
	
	// Se combinan las celdas A1 hasta D1, para colocar ahí el titulo del reporte
	$objPHPExcel->setActiveSheetIndex(0)
		->mergeCells('A1:M1');
	
	//Se crea la primera columna fija y las demas on dinamicas en dependencia de la cantidad de titulos que tengamos
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', $titulo);
	
				
	//Se ejecuta la consulta y se van creando los filtros del reporte 
	$query = mysql_query($sql1,$conection) or die ("Error al ejecutar la consulta");
	$cant  = mysql_num_fields($query);
	$fila = 3;
	$letra = 'A';
	while($row=mysql_fetch_array($query)){ //recorro por cada fila 
	
		//Se ejecuta la consulta y se van creando las columnas segun los campos de la 
		$sql2 .= " AND finca = '".$row['finca']."' order by codigo";

		$query1 = mysql_query($sql2,$conection) or die ("Error al ejecutar la consulta");
		$cant1  = mysql_num_fields($query1);
		
		$lastColumn = $letra.$fila; //va iterando desde la E,F,G...n en la fila 1 ($head)
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn,$filtro.$row[0]);

		$fila++;
		$letra = 'B';
		
		for ($i=0; $i < count($TIT_COLUM); $i++) {
				$lastColumn = $letra.$fila;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$fila, $TIT_COLUM[$i]);
				$letra ++;		
		}
		$fila++;
		$letra = 'B';
		while($row1=mysql_fetch_array($query1)){ //recorro por cada fila 
			
			 $objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($letra.$fila, $row1[0]);
			//Devuelve la ultima columna utilizada en este caso la D
			 $lastColumn = $letra.$fila;
			// echo $lastColumn;	
			 for ($i=1; $i < $cant1; $i++) { //recorro por cada columna de esa fila
						$letra ++;
						$lastColumn = $letra.$fila; //va iterando desde la E,F,G...n en la fila 1 ($head)
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn, $row1[$i]);
						//echo $lastColumn;						    
				}
			$letra = 'B';	
			$fila++;
		}
		$letra = 'A';
		$sql2 = $_SESSION["consulta2"];
		
	}
	
	//Dando algunos estilos
	$estiloTituloReporte = array(
		'font' => array(
			'name'      => 'Verdana',
			'bold'      => true,
			'italic'    => false,
			'strike'    => false,
			'size' =>16,
			'color'     => array(
				'rgb' => 'FF220835'
			)
		),
		'fill' => array(
			'type'  => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array(
				'argb' => 'FFFFFF')
		),
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_NONE
			)
		),
		'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			'rotation' => 0,
			'wrap' => TRUE
		)
	);

	//Limpiando las variables de session
	unset($_SESSION["consulta"]);
	unset($_SESSION["titulo"]);
	unset($_SESSION["columnas"]);
	unset($_SESSION["nombre_fichero"]);
	
	
    //Aplicando el estilo 
	$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray($estiloTituloReporte);
	
	//Aqui se crea el docuemnto completo con todas las filas insertadas
	$objPHPExcel->getActiveSheet()->setTitle('Hoja1');
	$objPHPExcel->setActiveSheetIndex(0);	
	
	//Worksheet estilo predeterminado (de forma predeterminada diferentes necesidades y Preferencias Configurar individualmente)
	$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);

   // Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$nombre_fichero.'"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');

?>