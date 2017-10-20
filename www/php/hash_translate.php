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

$value = $_POST['val'];
$identifier = explode('-', $value);
if (isset($identifier[2])) {
    echo $value;
} else {
    $select_hash = "SELECT url FROM phpss WHERE hash = BINARY '" . $value . "' LIMIT 1";
    $result_hash = mysqli_query($link, $select_hash);
    $row_hash = mysqli_fetch_array($result_hash, MYSQLI_BOTH);
    $string = $row_hash[0];
    echo $string;
}
?>