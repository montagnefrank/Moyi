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

	$matricula = $_POST['matricula1'];
	$consulta = "select * FROM tblproductos_ecu WHERE cpnombre_producto = '$matricula'";

	$result = mysql_query($consulta,$link);
	
	$respuesta = new stdClass();
		$fila = mysql_fetch_array();
		$respuesta->codigo = $fila['cpcodigo_producto'];
	echo json_encode($respuesta);
*/
$matricula = $_POST['matricula1'];
$conexion = new mysqli('localhost','eblooms','eblooms1234@','dbasebloms2',3306);
$consulta = "select * FROM tblproductos_ecu WHERE cpnombre_producto = '$matricula'";
$result = $conexion->query($consulta);

$respuesta = new stdClass();
	if($result->num_rows > 0){
		$fila = $result->fetch_array();
		$respuesta->codigo = $fila['cpcodigo_producto'];
		$respuesta->nombre = $fila['cpnombre_producto'];
		$respuesta->precio = $fila['cpprecioproducto_contado'];
}
echo json_encode($respuesta);
?>