<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL); 
//ini_set('display_errors', 1);


session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");

session_start();
$user = $_SESSION["login"];
$pass = $_SESSION["pass"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

//VErificar si ha registrado los DAE de US y CA
  //Obtener el mes de la fecha que estoy pasando
  $hoy = date('Y-m-d');
  $mes = substr($hoy,5,2);
  $mes1 = $mes + 1;
  $mes1 = str_pad($mes1,2,"0",STR_PAD_LEFT);
  $x    = "SELECT * FROM tbldae WHERE nombre_finca = '".$_POST['finca']."' AND url != 'eliminado' AND (ffin like '%-".$mes."-%' OR ffin like '%-".$mes1."-%')";

$y    = mysqli_query($link, $x);
$z    = mysqli_fetch_array($y);
$fin  = $z['ffin'];	
$cant1 = mysqli_num_rows($y);
$jsondata = array(); 
//Si la fecha de valides es menor que el dia de hoy le envio un mensaje y lo redirecciono a la pagina del DAE
if($cant1 < 1){
    $jsondata["id"] = "New";     
 }


//VErificar validez de fecha para el DAE
$a    = "SELECT ffin, pais_dae FROM tbldae WHERE nombre_finca = '".$_POST['finca']."' AND ffin < '".date('Y-m-d')."' AND url != 'eliminado'";


$b    = mysqli_query($link, $a);
$c    = mysqli_fetch_array($b);
$fin  = $c['ffin'];	
$cant = mysqli_num_rows($b);	

//Si la fecha de valides es menor que el dia de hoy le envio un mensaje y lo redirecciono a la pagina del DAE
if($cant > 0){
	$p = $c['pais_dae'];
	if($p == 'US'){
		$jsondata["id"] = "US"; 
	}else{
		$jsondata["id"] = "CA"; 
	}
}
echo json_encode($jsondata);   
?>

