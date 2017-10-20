<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION)) {
    session_start();
    session_destroy();
    session_start();
} else {
    session_destroy();
    session_start();
}
require ("scripts/conn.php");

//CONEXION A DB
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

//EN CASO DE RETORNAR CON ERROR, MOSTRAR MENSAJE
if (isset($_GET['error'])) {
    $msg_pass .= " No pudimos validar su contraseña, por favor ingresela nuevamente.";
    $_SESSION['msg'] = $msg_pass;
}
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en" class="body-full-height">
    <head>        
        <title>BurtonTech - Sistema de gestión de pedidos</title>            
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" href="favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" id="theme" href="css/theme-eblooms-dark.css"/>
        <link rel="stylesheet" type="text/css" id="theme" href="css/custom.css"/>
        <link rel="stylesheet" href="css/videocontainer.css" type="text/css"> 
    </head>
    <body>
        <div  class="login-container">
            <video class="fullscreen-bg__video" autoplay="" muted="" loop="">
                <source type="video/mp4" src="background/loop0.mp4"/>
            </video>
            <div class="login-box animated fadeInDown">
                <div class="login-logo"></div>
                <div class="login-title"><strong>Acceso al Sistema</strong>, sistema de gestión de pedidos</div>
                <div class="login-body">
                    <div class="login-title"><strong>Bienvenido</strong>, Estábamos esperando por ti</div>
                    <form id="loginform" name="loginform" class="form-horizontal" action="http://burtonservers.com/aria4/scripts/login.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input name="user" type="text" class="form-control" placeholder="Usuario" id="user"  maxlength="20" data-toggle="tooltip" data-placement="right" title="Ingrese su usuario" autofocus="autofocus"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <input name="pass" type="password" class="form-control" placeholder="Contraseña" id="pass" maxlength="20" data-toggle="tooltip" data-placement="right" title="Ingrese su contraseña"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <!--                            <a href="#" class="btn btn-link btn-block">¿olvidaste tu contraseña?</a>-->
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-info btn-block" name="button"  type="submit"  id="button" data-toggle="tooltip" data-placement="right" title="Ingresar">Iniciar Sesión</button>
                            </div>
                        </div>
                    </form>
                </div>
                <?php
                if (isset($_SESSION['msg'])) {
                    echo '
                            <div style="margin-top: 20px;" class="alert alert-danger" role="alert">
                                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                <span class="sr-only">Error:</span>
                                ' . $_SESSION['msg'] . '
                            </div>';
                    unset($_SESSION['msg']);
                }
                ?>
                <div class="login-footer">
                    <div class="pull-right">&copy; 2017 ARIA <span class="glyphicon glyphicon-registration-mark"></span> versión 4.0</div>
                    <div class="pull-right">
                        <a href="http://www.burtonservers.com">La empresa</a> |
                        <a href="http://www.burtonservers.com">Términos y condiciones</a> |
                        <a href="http://www.burtonservers.com">Contacto</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<?php mysqli_close($link); ?>