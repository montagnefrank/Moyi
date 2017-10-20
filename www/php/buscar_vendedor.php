<?php

$conexion = new mysqli("localhost", "root","W1nnts3rv3r","dbasebloms",3306);
$matricula = $_GET['term'];
$consulta = "select distinct vendedor FROM tblcliente WHERE vendedor LIKE '%$matricula%'";

$result = $conexion->query($consulta);

if($result->num_rows > 0){
	while($fila = $result->fetch_array()){
		$cedula[] = $fila['vendedor'];
	}
	json_encode($cedula);
	echo json_encode($cedula);
}

?>