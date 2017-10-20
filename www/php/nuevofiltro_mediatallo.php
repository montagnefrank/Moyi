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

    if(isset($_POST["nombre"]) && isset($_POST["accion"]) && $_POST["accion"]=='nuevo'){  
	 
	 $nombre_mediatallo = strtoupper($_POST['nombre']);
         $sql="INSERT INTO tblmediatallos (`nombre`) VALUES ('".$nombre_mediatallo."')";
         $insertado= mysqli_query($link, $sql);
         $id=mysqli_insert_id($link);
         
         
  	 $jsondata = array(); 
         if($insertado){
	  $jsondata["success"] = 'true';
          $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Mediatallo Insertado Correctamente.</strong></div>';
           
	 }else{
             $jsondata["success"] = 'false';
             $jsondata["message"] = mysqli_error();
         }
         $jsondata["id"] = $id;
         $sql1="select count(*) from tblmediatallos";
         $rs= mysqli_query($link, $sql1);
         $reg = mysqli_fetch_array($rs) ;
         $cant = $reg['count(*)'] ; 
         $jsondata["cant"] = $cant;
         $jsondata["nombre"] = $nombre_mediatallo;
         echo json_encode($jsondata);
   }
   else if(isset($_POST["nombre"]) && isset($_POST["accion"]) && $_POST["accion"]=='editar')
   {
         $nombre_mediatallo = strtoupper($_POST['nombre']);
         $sql="UPDATE tblmediatallos SET nombre='".$nombre_mediatallo."' WHERE id='".$_POST["id"]."'";
         $insertado= mysqli_query($link, $sql);
         
  	 $jsondata = array(); 
         if($insertado){
	  $jsondata["success"] = 'true';
          $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Mediatallo Actualizado Correctamente.</strong></div>';
           
	 }else{
             $jsondata["success"] = 'false';
             $jsondata["message"] = mysqli_error();
         }
         $jsondata["id"] = $_POST["id"];
         $jsondata["nombre"] = $nombre_mediatallo;
         echo json_encode($jsondata);
   }
   else if(isset($_POST["accion"]) && $_POST["accion"]=='eliminar')
   {
        $sql="DELETE FROM tblmediatallos WHERE id='".$_POST["id"]."'";
         $insertado= mysqli_query($link, $sql);
         
  	 $jsondata = array(); 
         if($insertado){
	  $jsondata["success"] = 'true';
          $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Mediatallo Eliminado Correctamente.</strong></div>';
           
	 }else{
             $jsondata["success"] = 'false';
             $jsondata["message"] = mysqli_error();
         }
         echo json_encode($jsondata); 
   }
      