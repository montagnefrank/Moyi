<?php

session_start();
$id = $_GET['id'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['cajas'] = $_POST["cajas"];
    if ($id == 1) {
        header('Location: modificar_guia.php?id=1');
    } elseif ($id == 2) {
        header('Location: modificar_guia.php?id=2');
    } elseif ($id == 3) {
        header('Location: modificar_guia.php?id=3');
    }
}
?>