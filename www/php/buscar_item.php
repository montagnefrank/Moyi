<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////CONEXION A DB
$user = $_SESSION["login"];
$rol = $_SESSION["rol"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

$matricula = $_GET['term'];
//separo la palabra
$porciones = explode(" ", $matricula);
if(count($porciones)>1)
{
  $consulta = "select id_item,prod_descripcion FROM tblproductos WHERE"; 
  
  for($i=0;$i<count($porciones);$i++)
  {
      if ($i == 0) {
            $consulta.= " prod_descripcion LIKE '%$porciones[$i]%'";  
      } 
      else
         $consulta.= " and prod_descripcion LIKE '%$porciones[$i]%'";    
  }
  for($i=0;$i<count($porciones);$i++)
  {
     $consulta .= " OR id_item LIKE '%$porciones[$i]%'" ;
  }
}
else
  $consulta = "select id_item,prod_descripcion FROM tblproductos WHERE prod_descripcion LIKE '%$matricula%'";   


//$consulta = "select id_item,prod_descripcion FROM tblproductos WHERE prod_descripcion LIKE '%$matricula%' OR id_item LIKE '%$matricula%'" ;
$jsondata = array(); 
$query = mysqli_query($link, $consulta);

$jsondata = array(); 

 $i=0;
 while($row = mysqli_fetch_array($query))
 {
    $jsondata[$i] = $row;
    $i++;
 }
 echo json_encode($jsondata);

