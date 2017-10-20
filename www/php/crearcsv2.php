<?php

	include('conectarSQL.php');
	require_once ('PHPExcel.php');
	include ("conexion.php");
	include ("seguridad.php");
	$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());
	
	session_start();
	$sql = $_SESSION["sql2"];
	$user    =  $_SESSION["login"];
	$rol     =  $_SESSION["rol"];
	$nombre_fichero = 'Confirm Manifest FULL.csv';
	$salida_cvs="Vendor,Item,Prod. Desc.,Ponumber,Tracking,Delivery Date,Ship Date,Precio,eBing"."\n";
	
	//Generando el archivo csv
	$query=mysql_query($sql,$link);
	$col  = mysql_num_fields($query);
	while ($rowr = mysql_fetch_row($query)) {
		 
			for ($j=0;$j<$col;$j++) {
				if($j == 1){ //Si la columna 1 es el item le busco su descripcion
				    //Seleccionar la descripcion del item
					$sql1   = "SELECT prod_descripcion FROM tblproductos WHERE id_item = '".$rowr[$j]."'";
					$query1 = mysql_query($sql1,$link) or die ("Error consultando la descripcion del item");
					$row1  = mysql_fetch_array($query1);
											
					$salida_cvs .= $rowr[$j].", ";
					$salida_cvs .= $row1['prod_descripcion'].", ";
				}else{
					$salida_cvs .= $rowr[$j].", ";
				}
			}
			$salida_cvs .= "\n";	
    }

    //Exportando el archivo csv
	header("Content-type: application/vnd.ms-excel");
	header("Content-disposition: csv" . date("Y-m-d") . ".csv");
	header( "Content-disposition: filename=".$nombre_fichero.".csv");
	print $salida_cvs;
	exit;

?>