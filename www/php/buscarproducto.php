<?php
/*	include ("conectarSQL.php");
	include ("conexion.php");
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link) {
		die('Fallo la conexion con el servidor ' . mysql_error());
	}
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Ubase de datos no encontrada");
	}

$desc = $_GET['term'];
$consulta = "select cpnombre_producto FROM tblproductos_ecu WHERE cpnombre_producto LIKE '%$desc%'";
$result = mysql_query($consulta,$link) or die ('Error');

$cant = mysql_num_rows($result);
if($cant > 0){
	while($fila = mysql_fetch_array()){
		$descripcion[] = $fila['cpnombre_producto'];
	}
}
echo json_encode($descripcion);
*/
$desc = $_GET['term'];
$conexion = new mysqli('localhost','eblooms','eblooms1234@','dbasebloms2',3306);
 
$consulta = "select cpnombre_producto FROM tblproductos_ecu WHERE cpnombre_producto LIKE '%$desc%'";
 
$result = $conexion->query($consulta);
 
if($result->num_rows > 0){
    while($fila = $result->fetch_array()){
        $matriculas[] = $fila['cpnombre_producto'];
    }
echo json_encode($matriculas);
}

?>