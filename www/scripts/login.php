<?php

/////////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require ("conn.php"); /////////////////////////////////////////////////////////////////////////CONEXION A LA DB
require ("islogged.php"); ////////////////////////////////////////////////////////////////////VERIFICA LOGIN VALIDO

session_start();
$user = $_POST["user"];
$pass = $_POST["pass"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

if ($user == "") {
    $_SESSION['msg'] = "Introduzca un usaurio, intente de nuevo";
    $_SESSION['box'] = "primary";
    header("Location: ../index.php?error=show");
} else {
    /////////////////////////////////////////////////////////////////////////////////////////////////VALIDAMOS EL USUARIO Y LA CONTRASE:A Y EL ROL, LUEGO DIRECCIONAMOS A SU RESPECTIVA PAGINA SEGUN SU ROL
    $result_validate = mysqli_query($link, "SELECT cppassword,idrol_user,finca FROM tblusuario WHERE cpuser = '" . $user . "'");
    $row_validate = mysqli_fetch_array($result_validate, MYSQLI_ASSOC);
    if ($_POST["pass"] == $row_validate['cppassword']) {
        $_SESSION["login"] = $user;
        $_SESSION["rol"] = $row_validate['idrol_user'];
        $_SESSION["finca"] = $row_validate['finca'];
        $rol = $_SESSION["rol"];
        if ($rol == 4) {
            header("Location: ../php/mainroom.php");
        } elseif ($rol == 5) {
            header("Location: ../php/administration.php");
        } elseif ($rol == 6) {
            header("Location: ../php/services.php");
        } elseif ($rol == 7) {
            header("Location: ../php/imp_etiquetas.php");
        } elseif ($rol == 9) {
            header("Location: ../php/contabilidad.php");
        } elseif ($rol == 10) {
            header("Location: ../php/imp_etiquetas.php");
        } else {
            header("Location: ../main.php?panel=mainpanel.php");
        }
    } else {
        $_SESSION['msg'] = "Hubo un error en el inicio de sesi&oacute;n, intente de nuevo";
        $_SESSION['box'] = "primary";
        header("Location: ../index.php?error=show");
    }
}

mysqli_close($link);
?>