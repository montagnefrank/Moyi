<?php
session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");
	
$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());

$causa = $_POST['causa'];
$detalle = $_POST['detalle'];
 
if(isset($_POST["accion"]) && $_POST["accion"]=='nuevo'){  

    $sql="INSERT INTO tblcausas (`causa`,`detalle`) VALUES ('".$causa."','".$detalle."')";
    
    $insertado= mysql_query($sql,$link);
    $id=mysql_insert_id($link);
    $jsondata = array(); 
    $jsondata["id"] = $id;     
   
    if($insertado){
     $jsondata["success"] = 'true';
     $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Causa Insertada Correctamente.</strong></div>';

    }else{
        $jsondata["success"] = 'false';
        $jsondata["message"] = mysql_error();
    }
    
   }
   else if(isset($_POST["accion"]) && $_POST["accion"]=='editar')
   {
         
         $sql="UPDATE tblcausas SET causa='".$causa."',detalle='".$detalle."' WHERE id='".$_POST["id"]."'";
        
         $insertado= mysql_query($sql,$link);
         
  	 $jsondata = array(); 
         if($insertado){
	  $jsondata["success"] = 'true';
          $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Causa Actualizada Correctamente.</strong></div>';
           
	 }else{
             $jsondata["success"] = 'false';
             $jsondata["message"] = mysql_error();
         }
   }
   else if(isset($_POST["accion"]) && $_POST["accion"]=='eliminar')
   {
        $sql="DELETE FROM tblcausas WHERE id='".$_POST["id"]."'";
         $insertado= mysql_query($sql,$link);
         
  	 $jsondata = array(); 
         if($insertado){
	  $jsondata["success"] = 'true';
          $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Causa Eliminada Correctamente.</strong></div>';
           
	 }else{
             $jsondata["success"] = 'false';
             $jsondata["message"] = mysql_error();
         }
        
   }
   
    $jsondata["causa"] = $causa;
    $jsondata["detalle"] = $detalle;
    echo json_encode($jsondata);   