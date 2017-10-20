<?php

	include('conectarSQL.php');
	require_once ('PHPExcel.php');
	include ("conexion.php");
	include ("seguridad.php");
	$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());
	
	session_start();
	$sql = $_SESSION["sql"];
        
	$nombre_fichero = 'Guias Asignadas.csv';
	$salida_cvs="Codigo,Item,Finca,Fligth Date,Deliver Date,Servicio,Guia Madre,Guia Hija,Tracking,Guia Master,Palet"."\n";
	
	//Generando el archivo csv
	$query=mysql_query($sql,$link);
	$col  = mysql_num_fields($query);
	while ($rowr = mysql_fetch_row($query)) {
            for ($j=0;$j<$col;$j++) {
                    if($j != 1 || $j != 5)
                            $salida_cvs .= $rowr[$j].", ";
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