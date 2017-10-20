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

$nombre_boxtype = $_POST['nombre'];
$tipo_boxtype = $_POST['tipo'];
$largo_boxtype = $_POST['largo'];
$ancho_boxtype = $_POST['ancho'];
$alto_boxtype = $_POST['alto'];
$qbx_boxtype = $_POST['qbx'];
 
if(isset($_POST["accion"]) && $_POST["accion"]=='nuevo'){  

    $sql="INSERT INTO tblboxtype (`nombre_Box`,`tipo_Box`,`longitud_Box`,`ancho_Box`,`alto_Box`,`QBX`) VALUES ('".$nombre_boxtype."',"
                 ."'".$tipo_boxtype."','".$largo_boxtype."','".$ancho_boxtype."','".$alto_boxtype."','".$qbx_boxtype."')";
    
    $insertado= mysqli_query($link, $sql);
    $id=mysqli_insert_id($link);
    $jsondata = array(); 
    $jsondata["id"] = $id;     
   
    if($insertado){
     $jsondata["success"] = 'true';
     $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Boxtype Insertado Correctamente.</strong></div>';

    }else{
        $jsondata["success"] = 'false';
        $jsondata["message"] = mysqli_error();
    }
    
   }
   else if(isset($_POST["accion"]) && $_POST["accion"]=='editar')
   {
         
         $sql="UPDATE tblboxtype SET nombre_Box='".$nombre_boxtype."',tipo_Box='".$tipo_boxtype."',longitud_Box='".$largo_boxtype."',ancho_Box='".$ancho_boxtype."',alto_Box='".$alto_boxtype."',QBX='".$qbx_boxtype."' WHERE id_Box='".$_POST["id"]."'";
        
         $insertado= mysqli_query($link, $sql);
         
  	 $jsondata = array(); 
         if($insertado){
	  $jsondata["success"] = 'true';
          $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Boxtype Actualizado Correctamente.</strong></div>';
           
	 }else{
             $jsondata["success"] = 'false';
             $jsondata["message"] = mysqli_error();
         }
   }
   else if(isset($_POST["accion"]) && $_POST["accion"]=='eliminar')
   {
        $sql="DELETE FROM tblboxtype WHERE id_Box='".$_POST["id"]."'";
         $insertado= mysqli_query($link, $sql);
         
  	 $jsondata = array(); 
         if($insertado){
	  $jsondata["success"] = 'true';
          $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Boxtype Eliminado Correctamente.</strong></div>';
           
	 }else{
             $jsondata["success"] = 'false';
             $jsondata["message"] = mysqli_error();
         }
        
   }
   
    $jsondata["nombre"] = $nombre_boxtype;
    $jsondata["tipo"] = $tipo_boxtype;
    $jsondata["largo"] = $largo_boxtype;
    $jsondata["ancho"] = $ancho_boxtype;
    $jsondata["alto"] = $alto_boxtype;
    $jsondata["qbx"] = $qbx_boxtype;
    echo json_encode($jsondata);   