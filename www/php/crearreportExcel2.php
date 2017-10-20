<?php

include('conectarSQL.php');
require_once ('PHPExcel.php');
include ("conexion.php");
include ("seguridad.php");


session_start();
$sql = $_SESSION["consulta"];
$sql1 = $_SESSION["consulta1"];
$sql2 = $_SESSION["consulta2"];
$titulo = $_SESSION["titulo"];
$columnas = $_SESSION["columnas"];
$nombre_fichero = $_SESSION["nombre_fichero"];
$id = $_SESSION["id"];
if ($sql == '' && $titulo == '' && $columnas == '' && $nombre_fichero == '') {
    header("Location:" . $_SERVER['HTTP_REFERER']);
}

echo "VARIABLE SQL " . $sql . " <br /><br />";
echo "VARIABLE SQL1 " . $sql1 . " <br /><br />";
echo "VARIABLE SQL2 " . $sql2 . " <br /><br />";
echo "VARIABLE TITULO " . $titulo . " <br /><br />";
echo "VARIABLE COLUMNAS " . $columnas . " <br /><br />";
echo "VARIABLE NOMBREFICHERO " . $nombre_fichero . " <br /><br />";
echo "VARIABLE ID " . $id . " <br /><br />";

print_r($columnas);

?>