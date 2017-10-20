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

$nombre_supplies = $_POST['nombre'];
$costo_supplies = $_POST['costo'];
$iva_supplies = $_POST['iva'];
$costototal_supplies = $_POST['costototal'];

if(isset($_POST["accion"]) && $_POST["accion"]=='nuevo'){  

    $sql="INSERT INTO tblsupplies (`nombre`,`costo`,`IVA`,`costo_total`) VALUES ('".$nombre_supplies."',"
                 ."'".$costo_supplies."','".$iva_supplies."','".$costototal_supplies."')";
    
    $insertado= mysqli_query($link, $sql);
    $id=mysqli_insert_id($link);
    $jsondata = array(); 
    $jsondata["id"] = $id;     
   
    if($insertado){
     $jsondata["success"] = 'true';
     $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Supplie Insertado Correctamente.</strong></div>';

    }else{
        $jsondata["success"] = 'false';
        $jsondata["message"] = mysqli_error();
    }
    
   }
   else if(isset($_POST["accion"]) && $_POST["accion"]=='editar')
   {
         
         $sql="UPDATE tblsupplies SET nombre='".$nombre_supplies."',costo='".$costo_supplies."',IVA='".$iva_supplies."',costo_total='".$costototal_supplies."' WHERE id='".$_POST["id"]."'";
        
         $insertado= mysqli_query($link, $sql);
         
  	 $jsondata = array(); 
         if($insertado){
	  $jsondata["success"] = 'true';
          $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Supplie Actualizado Correctamente.</strong></div>';
           
	 }else{
             $jsondata["success"] = 'false';
             $jsondata["message"] = mysqli_error();
         }
   }
   else if(isset($_POST["accion"]) && $_POST["accion"]=='eliminar')
   {
        $sql="DELETE FROM tblsupplies WHERE id='".$_POST["id"]."'";
         $insertado= mysqli_query($link, $sql);
         
  	 $jsondata = array(); 
         if($insertado){
	  $jsondata["success"] = 'true';
          $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Supplie Eliminado Correctamente.</strong></div>';
           
	 }else{
             $jsondata["success"] = 'false';
             $jsondata["message"] = mysqli_error();
         }
        
   }
   
    $jsondata["nombre"] = $nombre_supplies;
    $jsondata["costo"] = $costo_supplies;
    $jsondata["iva"] = $iva_supplies;
    $jsondata["costototal"] = $costototal_supplies;
    echo json_encode($jsondata);   