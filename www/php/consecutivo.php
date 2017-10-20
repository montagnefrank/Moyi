<?php
include ("seguridad.php");
//generar un consecutivo para el Ebing de la orden
function generarConsecutivo(){
	//Conexion a la BD
    include ("conexion.php");
	$conection = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());
	if (!$conection) {
		die('Could not connect: ' . mysql_error());
	}
	else
	{
		//Seleccionamos el ultimo valor consecutivo
		$query = "select * from tblconsecutivo Order By consecutivo DESC";
		$sql = mysql_query($query,$conection) or die (mysql_error());
		$row= mysql_fetch_array($sql);
		$ultimovalor = $row[1];
		$ultimovalor+=1;
		return $ultimovalor; 
	}
}
function generarPartnerTrxId(){
	//Conexion a la BD
    include ("conexion.php");
	$conection = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());
	if (!$conection) {
		die('Could not connect: ' . mysql_error());
	}
	else
	{
		//Seleccionamos el ultimo valor consecutivo
		$query = "select * from tblconsecutivo Order By partnerTrxID DESC";
		$sql = mysql_query($query,$conection) or die (mysql_error());
		$row= mysql_fetch_array($sql);
		$ultimovalor = $row[3];
		$ultimovalor+=1;
		return $ultimovalor; 
	}
}

?>