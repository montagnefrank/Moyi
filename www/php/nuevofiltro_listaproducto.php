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

$nombre_listaproducto = $_POST['nombre'];
$hts_listaproducto = $_POST['hts'];
$costo_listaproducto = $_POST['costo'];
$nandina_listaproducto = $_POST['nandina'];
 
if(isset($_POST["accion"]) && $_POST["accion"]=='nuevo'){  

    $sql="INSERT INTO tbllistaproducto (`nombre`,`HTS`,`nandina`,`costo_Decl_Stem`) VALUES ('".$nombre_listaproducto."',"
                 ."'".$hts_listaproducto."','".$nandina_listaproducto."','".$costo_listaproducto."')";
    
    $insertado= mysqli_query($link, $sql);
    $id=mysqli_insert_id($link);
    $jsondata = array(); 
    $jsondata["id"] = $id;     
   
    if($insertado){
     $jsondata["success"] = 'true';
     $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Producto Insertado Correctamente.</strong></div>';

    }else{
        $jsondata["success"] = 'false';
        $jsondata["message"] = mysqli_error();
    }
    
   }
   else if(isset($_POST["accion"]) && $_POST["accion"]=='editar')
   {
         
         $sql="UPDATE tbllistaproducto SET nombre='".$nombre_listaproducto."',HTS='".$hts_listaproducto."',nandina='".$nandina_listaproducto."',costo_Decl_Stem='".$costo_listaproducto."' WHERE id='".$_POST["id"]."'";
               
         $insertado= mysqli_query($link, $sql);
         
  	 $jsondata = array(); 
         if($insertado){
	  $jsondata["success"] = 'true';
          $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Producto Actualizado Correctamente.</strong></div>';
           
	 }else{
             $jsondata["success"] = 'false';
             $jsondata["message"] = mysqli_error();
         }
   }
   else if(isset($_POST["accion"]) && $_POST["accion"]=='eliminar')
   {
        $sql="DELETE FROM tbllistaproducto WHERE id='".$_POST["id"]."'";
         $insertado= mysqli_query($link, $sql);
         
  	 $jsondata = array(); 
         if($insertado){
	  $jsondata["success"] = 'true';
          $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Producto Eliminado Correctamente.</strong></div>';
           
	 }else{
             $jsondata["success"] = 'false';
             $jsondata["message"] = mysqli_error();
         }
        
   }
   
    $jsondata["nombre"] = $nombre_listaproducto;
    $jsondata["hts"] = $hts_listaproducto;
    $jsondata["nandina"] = $nandina_listaproducto;
    $jsondata["costo"] = $costo_listaproducto;
    echo json_encode($jsondata);   