<?php
include ("seguridad.php");
session_start();
//Destruir valores de ;la sesion y salir a la pagina de registro
  unset($_SESSION["login"]); 
  session_destroy();
  header("Location: ../index.php");
  exit;
?>