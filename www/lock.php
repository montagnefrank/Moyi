<?php
require ("scripts/conn.php");
require ("scripts/islogged.php");

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

//IDENTIFICAMOS EL ROL
$result_rol = mysqli_query($link, "SELECT idrol_user FROM tblusuario WHERE cpuser = '" . $user . "'");
$row_rol = mysqli_fetch_array($result_rol, MYSQLI_ASSOC);
$rol = $row_rol['idrol_user'];

//PANEL A MOSTRAR
$panel = $_GET['panel'];

//OBTENIENDO LA FINCA DEL USUARIO
$result_finca = mysqli_query($link, "SELECT finca FROM tblusuario WHERE cpuser = '" . $user . "'");
$row_finca = mysqli_fetch_array($result_finca, MYSQLI_ASSOC);
$finca = $row_finca['finca'];

//EN CASO DE RETORNAR CON ERROR, MOSTRAR MENSAJE
if (isset($_GET['error'])) {
    $msg_pass .= " No pudimos validar su contraseña, por favor ingresela nuevamente.";
    $_SESSION['msg'] = $msg_pass;
}
?>
<!DOCTYPE html>
<html lang="en" class="body-full-height">
    <head>        
        <title>BurtonTech - Sistema de gestión de pedidos</title>            
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" href="favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" id="theme" href="css/theme-eblooms-<?php
        $result_theme = mysqli_query($link, "SELECT theme FROM tblusuario WHERE cpuser='$user'");
        $row_theme = mysqli_fetch_array($result_theme, MYSQLI_ASSOC);
        $theme = $row_theme['theme'];
        echo $theme;
        ?>.css"/>
        <link rel="stylesheet" type="text/css" id="theme" href="css/custom.css"/>
        <link rel="stylesheet" href="css/videocontainer.css" type="text/css">                                   
    </head>
    <body>
        <div class="lockscreen-container">
            <video class="fullscreen-bg__video" poster="background/poster.png" autoplay="" muted="" loop="">
                <source type="video/mp4" src="background/loop.mp4"/>
            </video>
            <div class="lockscreen-box animated fadeInDown">
                <div class="lsb-access">
                    <div class="lsb-box">
                        <div class="fa fa-lock"></div>
                        <div class="user animated fadeIn">
                            <img src="img/users/<?php echo $user ?>.jpg" alt="<?php echo $user ?>"/>
                            <div class="user_signin animated fadeIn">
                                <div class="fa fa-sign-in"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lsb-form animated fadeInDown">
                    <form id="loginform" name="loginform" class="form-horizontal" action="scripts/login.php" method="post" enctype="multipart/form-data">
                        <div class="form-group sign-in animated fadeInDown">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span class="fa fa-user"></span>
                                    </div>
                                    <input name="user" type="text" class="form-control" placeholder="Usuario" id="user"  maxlength="20" data-toggle="tooltip" data-placement="right" title="Ingrese su usuario" autofocus="autofocus" value="<?php echo $user ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span class="fa fa-lock"></span>
                                    </div>
                                    <input name="pass" type="password" class="form-control" placeholder="Contraseña" id="pass" maxlength="20" data-toggle="tooltip" data-placement="right" title="Ingrese su contraseña"/>
                                </div>
                            </div>
                        </div>
                        <input type="submit" name="button"  id="button" data-toggle="tooltip" data-placement="right" class="hidden"/>
                        <input type="hidden" name="error" id="error" value="<?php echo $error ?>">
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
            </div>
        </div>
        <!-- START SCRIPTS -->
        <!-- START PLUGINS -->
        <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>        
        <!-- END PLUGINS -->
        <!-- START TEMPLATE -->                
        <script type="text/javascript" src="js/plugins.js"></script>
        <script type="text/javascript" src="js/actions.js"></script>
        <!-- END TEMPLATE -->
        <!-- END SCRIPTS --> 
    </body>
</html>
<?php mysqli_close($link); ?>