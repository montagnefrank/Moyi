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

    if(isset($_POST["color"]) && isset($_POST["accion"]) && $_POST["accion"]=='nuevo'){  
	 
	 $color = strtoupper($_POST['color']);
         $sql="INSERT INTO tblcolores (`color`) VALUES ('".$color."')";
         $insertado= mysqli_query($link, $sql);
         $id=mysqli_insert_id($link);
         
         
  	 $jsondata = array(); 
         if($insertado){
	  $jsondata["success"] = 'true';
          $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Color Insertado Correctamente.</strong></div>';
           
	 }else{
             $jsondata["success"] = 'false';
             $jsondata["message"] = mysqli_error();
         }
         
   }
   else if(isset($_POST["color"]) && isset($_POST["accion"]) && $_POST["accion"]=='editar')
   {
         $color = strtoupper($_POST['color']);
         $sql="UPDATE tblcolores SET color='".$color."' WHERE id='".$_POST["id"]."'";
         $insertado= mysqli_query($link, $sql);
         
  	 $jsondata = array(); 
         if($insertado){
	  $jsondata["success"] = 'true';
          $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Color Actualizado Correctamente.</strong></div>';
           
	 }else{
             $jsondata["success"] = 'false';
             $jsondata["message"] = mysqli_error();
         }
   }
   else if(isset($_POST["accion"]) && $_POST["accion"]=='eliminar')
   {
        $sql="DELETE FROM tblcolores WHERE id='".$_POST["id"]."'";
         $insertado= mysqli_query($link, $sql);
         
  	 $jsondata = array(); 
         if($insertado){
	  $jsondata["success"] = 'true';
          $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Color Eliminado Correctamente.</strong></div>';
           
	 }else{
             $jsondata["success"] = 'false';
             $jsondata["message"] = mysqli_error();
         }
          
   }
   $jsondata["id"] = $id;
   $jsondata["color"] = $color;
   echo json_encode($jsondata);
      