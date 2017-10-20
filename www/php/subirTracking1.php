<?php
//ini_set('display_errors', 'On');
//ini_set('display_errors', 1);
session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");

$user = $_SESSION["login"];
$passwd = $_SESSION["passwd"];
$rol = $_SESSION["rol"];

$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : ' . mysql_error());
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Subir Tracking</title>
        <link href="../images/favicon.ico"  rel="icon" type="image/png" />
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" media="all"  />
        <link href="../bootstrap/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all"  />
        <link href="../bootstrap/css/prettify-1.0.css" rel="stylesheet" type="text/css" media="all"  />
        <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">

        <script type="text/javascript" src="../js/script.js"></script>
        <script src="../bootstrap/js/jquery.min.js"></script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <script src="../bootstrap/js/bootstrap-submenu.js"></script>
        <script src="../js/moment-with-locales.js"></script>
        <script src="../bootstrap/js/bootstrap-datetimepicker.js"></script>
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
        <script src="../bootstrap/js/docs.js" defer></script>

        <style>
            .contenedor {
                margin:0 auto;
                width:85%;
                text-align:center;
            }
            li a{
                cursor:pointer;/*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
            }
        </style>
    </head>

    <body background="../images/fondo.jpg" class="dt">
        <div class="container">
            <div align="center" width="100%">
                <img src="../images/logo.png" class="img-responsive"/>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">         
                    <nav class="navbar navbar-default" role="navigation">
                        <!-- El logotipo y el icono que despliega el menú se agrupan
                             para mostrarlos mejor en los dispositivos móviles -->

                        <div class="container-fluid">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                    <span class="sr-only">Desplegar navegación</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand" href="../main.php?panel=mainpanel.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
                            </div>

                            <!-- Agrupar los enlaces de navegación, los formularios y cualquier
                                 otro elemento que se pueda ocultar al minimizar la barra -->
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                <ul class="nav navbar-nav">
                                    <?php if ($rol == 3 || $rol == 8) { ?>
                                        <li><a href="../php/subirTracking1.php"><strong>Cargar Tracking</strong></a></li>
                                    <?php } ?> 

                                    <?php if ($rol <= 2) { ?>
                                        <li class="active" >
                                            <a tabindex="0" data-toggle="dropdown">
                                                <strong>Venta</strong><span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a href="crearorden.php"><strong>Punto de Venta</strong></a></li>
                                                <li class="divider"></li>
                                                <li class="dropdown-submenu">
                                                    <a tabindex="0" data-toggle="dropdown"><strong>Cargar</strong></a>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="../php/subirOrden.php"><strong>Cargar Órdenes</strong></a></li>
                                                        <li class="divider"></li>
                                                        <li><a href="../php/subirTracking1.php"><strong>Cargar Tracking</strong></a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
    <?php
}
?>
                                    <li>
                                        <a tabindex="0" data-toggle="dropdown">
                                            <strong>Órdenes</strong><span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="filtros.php"><strong>Ver Órdenes</strong></a>
                                            <li class="divider"></li>    
<?php if ($rol <= 2) { ?>
                                                <li class="dropdown-submenu">
                                                    <a tabindex="0" data-toggle="dropdown"><strong>Gestionar Órdenes</strong></a>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="gestionarordenes.php" ><strong>Modificar Órdenes</strong></a></li> 
                                                        <li class="divider"></li>
                                                        <li><a href="reenvioordenes.php" ><strong>Reenviar Órdenes</strong></a></li>
                                                    </ul>
                                                </li>
                                                <li class="divider"></li>  
                                                <li><a href="filtros.php?accion=buscarPO"><strong>Buscar PO</strong></a>
    <?php
}
?>

                                                <?php if ($rol == 3) { ?>
                                                <li><a href="filtros_fincas.php?accion=buscarPO"><strong>Buscar PO</strong></a>
                                                <?php } ?>
                                        </ul>
                                    </li>

                                                <?php if ($rol <= 2 || $rol == 3) { ?>
                                        <li class="dropdown">
                                            <a tabindex="0" data-toggle="dropdown">
                                                <strong>Registro</strong><span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a href="crearProductos.php"><strong>Registro de Productos</strong></a></li>                      					
    <?php if ($rol <= 2) { ?>
                                                    <li class="divider"></li>
                                                    <li><a href="crearClientes.php" ><strong>Registro de Clientes</strong></a></li>
        <?php
    }
    ?>
                                                <?php if ($rol <= 2) { ?>
                                                    <li class="divider"></li>					  
                                                    <li><a href="crearFincas.php" ><strong>Registro de Fincas</strong></a></li>
                                                    <?php
                                                }
                                                ?>
                                                <?php if ($rol <= 2) { ?>
                                                    <li class="divider"></li>
                                                    <li><a href="crearagencia.php" ><strong>Registro de Agencias de Vuelo</strong></a></li>
                                                    <?php
                                                }
                                                ?>

                                            </ul>
                                        </li>
    <?php
}
?>
                                            <?php if ($rol <= 2) { ?>				 
                                        <li class="dropdown">
                                            <a tabindex="0" data-toggle="dropdown">
                                                <strong>Pedidos</strong><span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a href="asig_etiquetas.php" ><strong>Hacer Pedido</strong></a></li>		
                                                <li class="divider"></li>			
                                                <li><a href="verdae.php" ><strong>Ver DAE</strong></a></li>
                                            </ul>
                                        </li>				 
    <?php
}
?>

<?php if ($rol <= 2) { ?> 
                                        <li><a href="mainroom.php"><strong>Cuarto Frío</strong></a></li>     			   					<li><a href="services.php" ><strong>Clientes</strong></a></li> 
                                        <li class="dropdown">

                                        <li><a href="administration.php">
                                                <strong>EDI</strong>
                                            </a>  


                                            <!--   <a tabindex="0" data-toggle="dropdown">
                                                   <strong>Contabilidad</strong><span class="caret"></span>
                                               </a>
                                            <ul class="dropdown-menu" role="menu">
                                                  <li><a href="administration.php"><strong>Contabilidad</strong></a></li>                      
                                                  <li class="divider"></li>         
                                                  <li><a href="contabilidad.php"><strong>Contabilidad ECU</strong></a></li>
                                             </ul> -->
                                        </li>	
    <?php
}
?>

<?php if ($rol == 1) { ?>
                                        <li ><a href="usuarios.php"><strong>Usuarios</strong></a></li>
<?php
} else {
    $sql = "SELECT id_usuario from tblusuario where cpuser='" . $user . "'";
    $query = mysql_query($sql, $link);
    $row = mysql_fetch_array($query);
    echo '<li><a href="" onclick="cambiar(\'' . $row[0] . '\')"><strong>Contraseña</strong></a></li>';
}
?>	
                                </ul>
                                <ul class="nav navbar-nav navbar-right">
                                    <li><a class="navbar-brand" href="" data-toggle="tooltip" data-placement="bottom" title="Usuario conectado"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo $user ?></a></li>
                                    <li><a class="navbar-brand" href="../index.php" data-toggle="tooltip" data-placement="bottom" title="Salir del sistema" ><span class="glyphicon glyphicon-off" aria-hidden="true"></span></a></li>
                                </ul>
                            </div>
                    </nav>
                </div>

                <div class="panel-body">

                    <div id="tracking">
                        <h3>Seleccione el archivo de tracking</h3>
                        <!-- Subir varios archivos-->
                        <form action="subirarchivo3.php" method="post" enctype="multipart/form-data" name="Upload" id="Upload">
                            <input type="file" name="archivo[]" /><button type="submit" id="myButton" data-loading-text="Cargando..." class="btn btn-primary" autocomplete="off" data-toggle="tooltip" data-placement="right" title="Cargar trackings">Cargar trackings</button>
                        </form>
                    </div> 
                </div>
                <div class="panel-heading">
                    <div class="contenedor" align="center">
                        <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2015 versión 3</strong>
                        <br>
                        <strong><a href="http://www.bit-store.ec/index.php/contactenos/"  style="color:white">Info</a> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></strong>
                    </div>
                </div>

            </div> <!-- /container -->
        </div>
    </body>
</html>