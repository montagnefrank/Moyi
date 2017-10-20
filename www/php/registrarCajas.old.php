<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL); 
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");
require_once('barcode.inc.php');

$user = $_SESSION["login"];
$rol = $_SESSION["rol"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

$codebar  = strtoupper($_POST['codigo']);
$codebar = limpia_espacios($codebar);

list($codfinca,$item,$codigo) = explode("-", $codebar);
$fecha = date('Y-m-d');
$jsondata=array();

//verifico que los campos no esten vacios
if($codfinca == '' && $item == '' && $codigo == ''){
  $jsondata["error"]=2;
  echo json_encode($jsondata);
  return;
}else{
    //Se busca segun el codigo de la finca el nombre para insertarlo
        $sentencia = "SELECT nombre FROM tblfinca WHERE codigo='".$codfinca."'";
        $consulta  = mysqli_query($link, $sentencia) or die(mysqli_error());
        $fila      = mysqli_fetch_array($consulta);
        $finca     = $fila['nombre'];
  			 
        //Verificar si esa entrada esta solicitada
       $cadena   = "SELECT * FROM tbletiquetasxfinca where codigo = '".$codigo."' AND finca = '".$finca."' AND item = '".$item."'";
       $ejecutar = mysqli_query($link, $cadena);
       $result   = mysqli_num_rows($ejecutar);
       if($result == 0){
          $jsondata["error"]=3;	
          echo json_encode($jsondata);
          return;
       }else{

            $sql="INSERT INTO tblcoldroom (`codigo`, `entrada`, `item`,`finca`,`fecha`, `salida`) VALUES ('$codigo','Si','$item','$finca','$fecha','No')";				 			 
            $insertado= mysqli_query($link, $sql);
  				 
            if($insertado){
                    //Actualizando el estado de los pedidos en el cuarto frio
                    $sql="UPDATE tbletiquetasxfinca set estado='1', entregado = '1' where codigo='".$codigo."'";
                    $modificado= mysqli_query($link, $sql);
                    if($modificado){
                           $jsondata["error"]=5;
                           $sql1 = "SELECT * FROM tblcoldroom where codigo='".$codigo."'";
                           $res= mysqli_query($link, $sql1);
                           while($row = mysqli_fetch_array($res)){
                               $jsondata["codigo"]=$row['codigo'];
                               $jsondata["entrada"]=$row['entrada'];
                               $jsondata["item"]=$row['item'];
                               $jsondata["finca"]=$row['finca'];
                               $jsondata["fecha"]=$row['fecha'];
                               $jsondata["salida"]=$row['salida'];
                           }
                    }else{
                             $jsondata["error"]=4;
                             echo json_encode($jsondata);
                             return;
                    }
            }else{
             //aqui se lanza la ventana modal
                $jsondata["error"]=6;
                echo json_encode($jsondata);
                             return;
                                 
  	   }
       }
  }  
  echo json_encode($jsondata); 
  
  function limpia_espacios($cadena){
    $cadena = str_replace(' ', '', $cadena);
    return $cadena;
  }