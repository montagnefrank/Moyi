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

$nombre = $_POST['nombre'];
$codigo = $_POST['codigo'];
$pais = $_POST['pais'];
 
if(isset($_POST["accion"]) && $_POST["accion"]=='nuevo'){  

    $sql="INSERT INTO tblestados(`nombre`,`codigo`,`pais`) VALUES ('".$nombre."',"."'".$codigo."','".$pais."')";
    
    $insertado= mysqli_query($link, $sql);
    $id=mysqli_insert_id($link);
    $jsondata = array(); 
    $jsondata["id"] = $id;     
   
    if($insertado){
     $jsondata["success"] = 'true';
     $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Estado Insertado Correctamente.</strong></div>';

    }else{
        $jsondata["success"] = 'false';
        $jsondata["message"] = mysqli_error();
    }
    
   }
   else if(isset($_POST["accion"]) && $_POST["accion"]=='editar')
   {
         
         $sql="UPDATE tblestados SET nombre='".$nombre."',codigo='".$codigo."',pais='".$pais."' WHERE codigo='".$_POST["id"]."'";
        
         $insertado= mysqli_query($link, $sql);
         
  	 $jsondata = array(); 
         if($insertado){
	  $jsondata["success"] = 'true';
          $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Estado Actualizado Correctamente.</strong></div>';
           
	 }else{
             $jsondata["success"] = 'false';
             $jsondata["message"] = mysqli_error();
         }
   }
   else if(isset($_POST["accion"]) && $_POST["accion"]=='eliminar')
   {
        $sql="DELETE FROM tblestados WHERE codigo='".$_POST["id"]."'";
         $insertado= mysqli_query($link, $sql);
         
  	 $jsondata = array(); 
         if($insertado){
	  $jsondata["success"] = 'true';
          $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Estado Eliminado Correctamente.</strong></div>';
           
	 }else{
             $jsondata["success"] = 'false';
             $jsondata["message"] = mysqli_error();
         }
        
   }
   
    $jsondata["nombre"] = $nombre;
    $jsondata["codigo"] = $codigo;
    $jsondata["pais"] = $pais;
    echo json_encode($jsondata);   