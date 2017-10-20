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



if(isset($_POST["accion"]) && $_POST["accion"]=='guiahija'){
    $guia    = $_POST['guiah'];
    $check   = explode(",", $_POST['cajas']);
    $resp=array();
    
    for ($i=0; $i < count($check);$i++){
            $sql="Update tblcoldroom set guia_hija='".$guia ."' WHERE codigo='".$check[$i]."'";
            $modificado= mysqli_query($link, $sql) or die("Error");
    }
    echo json_encode("ok");
    return;
}

if(isset($_POST["accion"]) && $_POST["accion"]=='guiamadre'){  
	 $guia    = $_POST['guiam'];
	 $fecha   = $_POST['entrega'];
	 $check   = explode(",", $_POST['cajas']);
	 $service   = $_POST['servicio'];
	 $vuelo   = $_POST['vuelo'];

	  for ($i=0; $i < count($check);$i++){		 
		 $sql   = "SELECT guia_madre FROM tblcoldroom WHERE codigo='".$check[$i]."'";
		 $query = mysqli_query($link, $sql);
		 $row   = mysqli_fetch_array($query);
   		 if($row[0]== 0){  //Si no tiene guia le asigno
		     //identificar la aerolinea de esa guia madre
                        if($guia == 406) { //Es UPS
                            $airline = 'UPS';				 
                        }else if($guia == 369) { //Es ATLAS
                            $airline = 'ATLAS';				 
                        }else if($guia == '129') { //ES TAMPA
                            $airline = 'TAMPA';				 
                        }else if($guia == 145) { //ES LAN CHILE
                            $airline = 'LAN CHILE';				 
                        }else{      //ES KLM
                            $airline = 'KLM';
                        }
                        $sql="Update tblcoldroom set guia_madre='".$guia ."', fecha_entrega='".$fecha."', servicio = '".$service."', fecha_vuelo = '".$vuelo."', airline='".$airline."' WHERE codigo='".$check[$i]."'";
                        $modificado= mysqli_query($link, $sql) or die("Error");
                        
		 }
        }
        echo json_encode("ok");
        return;
}


?>