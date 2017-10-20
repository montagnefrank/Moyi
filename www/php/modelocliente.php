<?php
session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");
	
$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());

$pais = $_POST['pais'];

$sql="SELECT
tblestados.nombre,
tblestados.codigo
FROM
tblpaises_destino
INNER JOIN tblestados ON tblestados.pais = tblpaises_destino.codpais WHERE tblestados.pais='".$pais."'";
$row= mysql_query($sql,$link);
$dato=array();
$i=0;
while($fila= mysql_fetch_array($row)){
    $dato[$i++]=$fila;
}
echo json_encode($dato);
return;