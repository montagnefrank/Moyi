<?php

//////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

if (isset($_POST["mensaje"])){
    
$para      = "myrcons@myrcons.net";
$titulo    = "Contacto MYR Website";
$mensaje   = $_POST["mensaje"] . " - Tel: " . $_POST["telefono"];
$cabeceras = 'From: ' . $_POST["nombre"] . "\r\n" .
    'Reply-To: ' . $_POST["email"] . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
mail($para, $titulo, $mensaje, $cabeceras);
    header("Location: index.php");
} else {
    echo "No hay mensaje de contacto para enviar";
}
?>