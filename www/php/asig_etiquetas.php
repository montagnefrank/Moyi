<?php
session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");
$user = $_SESSION["login"];
$rol = $_SESSION["rol"];

$conection = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : ' . mysql_error());
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Hacer Pedido</title>

        <script type="text/javascript" src="../js/script.js"></script>
        <script language="javascript" src="../js/imprimir.js"></script>
        <link rel="icon" type="image/png" href="../images/favicon.ico" />
        <link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="../css/validationEngine.jquery.css" type="text/css"/>

        <script language="javascript" src="../js/imprimir.js"></script>
        <script type="text/javascript" src="../js/script.js"></script>
        <script src="../bootstrap/js/jquery.js"></script>
        <script src="../bootstrap/js/bootstrap.js"></script>
        <script src="../bootstrap/js/moment.js"></script>
        <script src="../bootstrap/js/bootstrap-datetimepicker.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
        <script src="../bootstrap/js/bootstrap-submenu.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
        <script src="../bootstrap/js/docs.js" defer></script>

        <style>

            li a{
                cursor:pointer;/*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
            }

            .seleccionado{
                background: #0066cc;
                color: #FFF;
            }
            .modal-header{
                background-color : #3B5998;
                color: white;
                border-radius: 5px 5px 0 0;
            }
            th {
                text-align: center; 
            }
            #lista_items thead{
                background: cornflowerblue;
            }
            .my-error-class {
                color: red;
                font-style: italic;
                font-size: 12px;
            }
            .btn-success,.btn-danger{
                width: 50px;
            }
            td .btn-success,td .btn-danger{
                width: auto;
            }
            .cab{
                height: 60px;
                text-align: center;
            }
        </style>

        <script type="text/javascript">

            $(document).ready(function () {
                //tol-tip-text
                $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                });

                //para el boton de  filtrar
                $("#btn_filtro").click(function (event) {
                    window.location.href = "cajasexistentes.php";
                    return;
                });
            });
        </script>

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
                                <?php
                                if ($rol <= 3) {
                                    echo '<a class="navbar-brand" href="../main.php?panel=mainpanel.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>';
                                } else {
                                    echo '<a class="navbar-brand" href=".mainroom.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>';
                                }
                                ?>

                            </div>

                            <!-- Agrupar los enlaces de navegación, los formularios y cualquier
                                 otro elemento que se pueda ocultar al minimizar la barra -->
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                <ul class="nav navbar-nav">
                                    <?php if ($rol == 3 || $rol == 8) { ?>
                                        <li><a href="../php/subirTracking1.php"><strong>Cargar Tracking</strong></a></li>
                                    <?php } ?> 

                                    <?php if ($rol <= 2) { ?>
                                        <li class="dropdown">
                                            <a tabindex="0" data-toggle="dropdown">
                                                <strong>Venta</strong><span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a href="crearorden.php"><strong>Punto de Venta</strong></a></li>
                                                <li class="divider"></li>
                                                <li class="dropdown-submenu">
                                                    <a tabindex="0" data-toggle="dropdown"><strong>Cargar</strong></a>            <ul class="dropdown-menu">
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
                                    <li class="dropdown">
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
    <?php
}
?>
                                            <li class="divider"></li>
                                            <li><a href="filtros.php?accion=buscarPO"><strong>Buscar PO</strong></a>
                                        </ul>
                                    </li>

<?php if ($rol <= 2 || $rol == 3) { ?>
                                        <li>
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
                                        <li   class="active">
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
                                        <li><a href="administration.php">
                                                <strong>EDI</strong>
                                            </a>  
                                        </li>
                                        <?php
                                    }
                                    ?>

<?php if ($rol == 1) { ?>
                                        <li><a href="usuarios.php"><strong>Usuarios</strong></a></li>
<?php
} else {
    $sql = "SELECT id_usuario from tblusuario where cpuser='" . $user . "'";
    $query = mysql_query($sql, $conection);
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
                    <div class="row" style="margin-left:20px;margin-right:20px;">

                        <div style="float: left;">
                            <button type="image" class="btn btn-primary" id="btn_filtro" data-toggle="tooltip" aria-label="Filtros" title = "Filtros">
                                <span class="glyphicon glyphicon-filter" aria-hidden="true"></span>
                            </button>
                        </div>

                        <div class="" style="float: left;">
                            <button type="button" class="btn btn-primary" name="btn_nuevo" id="btn_nuevo" data-toggle="tooltip" aria-label="Nuevo pedido" title = "Nuevo pedido">
                                <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
                            </button>
                        </div>

                        <div class="col-md-9" style="text-align: center;">
                            <h3><strong>LISTADO DE PEDIDOS A LAS FINCAS</strong></h3>
                        </div>
                    </div>

<?php
//Agrupar el reporte por destino
$sql = "SELECT DISTINCT finca FROM tbletiquetasxfinca where archivada = 'No' AND estado!='5' order by finca ASC";
$res = mysql_query($sql, $conection) or die('Error seleccionando el origen');
while ($fila = mysql_fetch_array($res)) {
    ?>
                        <div class="col-md-3"> 
                            <div class="panel panel-primary">
                                <div class="panel-heading cab">
                                    <h3 class="panel-title"><a href="listado_pedido.php?finca=<?php echo $fila['finca']; ?>"><?php echo $fila['finca']; ?></a></h3>
                                </div>
                                <div class="panel-body">

                        <?php
                        $sql1 = "SELECT DISTINCT SUM(tbletiquetasxfinca.solicitado) as solicitado FROM tbletiquetasxfinca where archivada = 'No' AND estado!='5' and finca='" . $fila['finca'] . "'";
                        $res1 = mysql_query($sql1, $conection) or die('Error seleccionando el origen');
                        $fila1 = mysql_fetch_array($res1);
                        ?>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="dom" class="col-md-7 control-label">Ordenadas</label>
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="left"><strong><?php echo $fila1['solicitado'] ?></strong></button>
                                            </div>
                                        </div>
                                    </div>

    <?php
    $sql2 = "SELECT DISTINCT SUM(tbletiquetasxfinca.entregado) as entregado FROM tbletiquetasxfinca where archivada = 'No' AND estado!='5' and finca='" . $fila['finca'] . "'";
    $res2 = mysql_query($sql2, $conection) or die('Error seleccionando el origen');
    $fila2 = mysql_fetch_array($res2);
    ?>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="dom" class="col-md-7 control-label">Entregadas</label>
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="left"><strong><?php echo $fila2['entregado'] ?></strong></button>
                                            </div>
                                        </div>  
                                    </div>  

    <?php
    //Contar cantidad de cajas entregadas sin traquear
    $sql3 = "SELECT * FROM tblcoldroom INNER JOIN tbletiquetasxfinca ON tblcoldroom.codigo = tbletiquetasxfinca.codigo where entrada= 'Si' AND salida ='No' AND tblcoldroom.finca='" . $fila['finca'] . "' AND tbletiquetasxfinca.archivada='No' AND tbletiquetasxfinca.estado='1'";
    $res3 = mysql_query($sql3, $conection);
    $fila3 = mysql_num_rows($res3);
    ?>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="dom" class="col-md-7 control-label">Sin traquear</label>
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="left"><strong><?php echo $fila3; ?></strong></button>
                                            </div>
                                        </div> 
                                    </div> 

    <?php
    //Contar cantidad de cajas entregadas traqueadas
    $sql4 = "SELECT * FROM tblcoldroom INNER JOIN tbletiquetasxfinca ON tblcoldroom.codigo = tbletiquetasxfinca.codigo where tblcoldroom.finca='" . $fila['finca'] . "' AND  salida ='Si' AND tblcoldroom.tracking_asig !='' AND tbletiquetasxfinca.archivada='No' AND tbletiquetasxfinca.estado='1'";
    $res4 = mysql_query($sql4, $conection);
    $fila4 = mysql_num_rows($res4);
    ?>
                                    <div class="row"> 
                                        <div class="form-group">
                                            <label for="dom" class="col-md-7 control-label">Traqueadas</label>
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="left"><strong><?php echo $fila4; ?></strong></button>
                                            </div>
                                        </div>
                                    </div>

    <?php
    //Se cuenta cuantas solicitudes rechazadas hay por cada finca e item
    $sql5 = "SELECT COUNT(*) as rechazado FROM tbletiquetasxfinca where finca ='" . $fila['finca'] . "' AND estado='2' AND archivada = 'No'";
    $res5 = mysql_query($sql5, $conection) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
    $fila5 = mysql_fetch_array($res5);
    ?>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="dom" class="col-md-7 control-label">Rechazadas</label>
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="left">
                                                    <strong><?php echo $fila5['rechazado'] ?></strong>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
    <?php
    $sql6 = "SELECT COUNT(*) as cierre FROM tbletiquetasxfinca where finca ='" . $fila['finca'] . "' AND estado='3' AND archivada = 'No'";
    $val6 = mysql_query($sql6, $conection) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
    $fila6 = mysql_fetch_array($val6);
    ?>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="dom" class="col-md-7 control-label">Cierre de dia</label>
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="left"><strong><?php echo $fila6['cierre'] ?></strong></button>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    //Se cuenta cuantas solicitudes rechazadas hay por cada finca e item
                                    $sql7 = "SELECT COUNT(*) as reutilizadas FROM tbletiquetasxfinca where finca ='" . $fila['finca'] . "' AND estado='4' AND archivada = 'No'";
                                    $val7 = mysql_query($sql7, $conection) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                    $fila7 = mysql_fetch_array($val7);
                                    ?>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="dom" class="col-md-7 control-label">Reutilizadas</label>
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="left"><strong><?php echo $fila7['reutilizadas'] ?></strong></button>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    $dif = $fila1['solicitado'] - $fila2['entregado'] - $fila5['rechazado'] - $fila6['cierre'] - $fila7['reutilizadas'];
                                    if ($dif == 0) {
                                        ?>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="dom" class="col-md-7 control-label">Por entregar</label>
                                                <div class="col-md-4">
                                                    <button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="left" >
                                                        <strong><?php echo $dif; ?></strong></button>
                                                </div>
                                            </div>
                                        </div>
    <?php } else { ?>							
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="dom" class="col-md-7 control-label">Por entregar</label>
                                                <div class="col-md-4">
                                                    <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="left" >
                                                        <strong><?php echo $dif; ?></strong></button>
                                                </div>
                                            </div>
                                        </div>
    <?php } ?>


                                </div>
                            </div>
                        </div>
<?php } ?>
<?php include 'nuevaetiqueta.php'; ?>
                </div> <!-- /panel body --> 
                <div class="panel-heading">
                    <div class="contenedor" align="center">
                        <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2015 versión 3</strong>
                        <br>
                        <strong><a href="http://www.bit-store.ec/index.php/contactenos/"  style="color:white">Info</a> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></strong>
                    </div>
                </div>  
            </div>
        </div>
    </body>
</html>