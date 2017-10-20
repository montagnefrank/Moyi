<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////CONEXION A DB
$user = $_SESSION["login"];
$rol = $_SESSION["rol"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Reporte de Palets</title>

        <link rel="icon" type="image/png" href="../images/favicon.ico" />
        <link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/dataTables.jqueryui.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.0.0/css/responsive.jqueryui.min.css">
        <link href="../bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" media="all"  />

        <script type="text/javascript" src="../js/script.js"></script>
        <script src="../bootstrap/js/jquery.js"></script>
        <script src="../bootstrap/js/bootstrap.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
        <script src="../bootstrap/js/bootstrap-submenu.js"></script>
        <script src="../bootstrap/js/bootstrap-modal.js"></script>
        <script src="../bootstrap/js/docs.js" defer></script>
        <script src="../bootstrap/js/moment.js"></script>
        <script src="../bootstrap/js/bootstrap-datetimepicker.min.js"></script>

        <script type="text/javascript" src="../js/ion.sound.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.10/js/dataTables.jqueryui.min.js"></script> 
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.0.0/js/dataTables.responsive.min.js"></script> 
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.0.0/js/responsive.jqueryui.min.js"></script>

        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

        <style type="text/css">
            .my-error-class {
                color:red;
            }
            li a{
                cursor:pointer; /*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
            }
            .modal-header{
                background-color: #3B5998;
                color: white;
                border-radius : 5px 5px 0 0;
            }    
            .contenedor {
                margin:0 auto;
                width:85%;
                text-align:center;
            }
        </style>
        <script type="text/javascript">

            $(document).ready(function () {
                //tol-tip-text
                $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                });

                $(".botonExcel").click(function (event) {
                    $("#datos_a_enviar").val($("<div>").append($("#tabla").clone()).html());
                    $("#FormularioExportacion").submit();
                });
            });
        </script>


    </head>
    <body background="../images/fondo.jpg">
        <div class="container">
            <div align="center" width="100%">
                <img src="../images/logo.png"  class="img-responsive"/>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">         
                    <nav class="navbar navbar-default" role="navigation">
                        <div class="container-fluid">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                    <span class="sr-only">Desplegar navegación</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand" href="mainroom.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>';
                            </div>

                            <!-- Agrupar los enlaces de navegación, los formularios y cualquier
                                 otro elemento que se pueda ocultar al minimizar la barra -->
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                <ul class="nav navbar-nav">
                                    <li class="active">
                                        <a tabindex="0" data-toggle="dropdown">
                                            <strong>Movimientos</strong><span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="reg_entrada.php"><strong>Registro Cajas</strong></a></li>
                                            <li class="divider"></li>
                                            <li><a href="verificartrack.php" ><strong>Chequear Tracking</strong></a></li>
                                        </ul>
                                    </li>

                                    <li><a href="asig_guia.php" ><strong>Asignar Guía</strong></a></li>     	
                                    <li><a href="reutilizar.php" ><strong>Reutilizar Cajas</strong></a></li>
                                    <li class="dropdown">
                                        <a tabindex="0" data-toggle="dropdown">
                                            <strong>Etiquetas</strong><span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="etiqxfincas.php">Imprimir etiquetas por fincas</a></li>
                                            <li class="divider"></li>
                                            <li><a href="etiquetasexistentes.php">Etiquetas existentes</a></li>
                                        </ul>
                                    </li>


                                    <li class="dropdown">
                                        <a tabindex="0" data-toggle="dropdown">
                                            <strong>Reportes</strong><span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li class="dropdown-submenu">
                                                <a tabindex="0" data-toggle="dropdown"><strong>Cajas Recibidas</strong></a>            
                                                <ul class="dropdown-menu">                               
                                                    <li><a href="reportecold.php?id=1">Por Productos Sin Traquear</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="reportecold.php?id=2">Por Fincas Sin Traquear</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="reportecold.php?id=3">Por Código Sin Traquear</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="reportecold.php?id=4">Total</a></li>
                                                </ul>
                                            </li>


                                            <li class="divider"></li>

                                            <li class="dropdown-submenu">
                                                <a tabindex="0" data-toggle="dropdown"><strong>Cajas Traqueadas</strong></a>            
                                                <ul class="dropdown-menu">                               
                                                    <li><a href="reportecold1.php?id=1">Por Producto</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="reportecold1.php?id=2">Por Fincas</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="reportecold1.php?id=3">Por Código</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="reportecold1.php?id=4">Total</a></li>
                                                </ul>
                                            </li>

                                            <li class="divider"></li>

                                            <li class="dropdown-submenu">
                                                <a tabindex="0" data-toggle="dropdown"><strong>Cajas Rechazadas</strong></a>            
                                                <ul class="dropdown-menu">                               
                                                    <li><a href="reportecold2.php?id=1">Por Producto</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="reportecold2.php?id=2">Por Fincas</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="reportecold2.php?id=3">Por Código</a></li>

                                                </ul>
                                            </li>

                                            <li class="divider"></li>
                                            <li><a href="voladasxfinca.php"><strong>Cajas voladas</strong></a></li>
                                            <li class="divider"></li>
                                            <li><a href="novoladasxfinca.php"><strong>Cajas no voladas</strong></a></li>
                                            <li class="divider"></li>                     
                                            <li><a href="guiasasig.php"><strong>Guías asignadas</strong></a></li>
                                            <li class="divider"></li>
                                            <li><a href="reporte_palets.php"><strong>Guias Master trackeadas</strong></a></li>
                                        </ul> 
                                    </li>
                                    <li><a href="modificar_guia.php" ><strong>Editar Guías</strong></a></li>
                                    <li><a href="closeday.php" ><strong>Cerrar Día</strong></a></li>


                                    <?php
                                    if ($rol == 4) {
                                        $sql = "SELECT id_usuario from tblusuario where cpuser='" . $user . "'";
                                        $query = mysqli_query($link, $sql);
                                        $row = mysqli_fetch_array($query);
                                        echo '<li><a href="" onclick="cambiar(\'' . $row[0] . '\')"><strong>Contraseña</strong></a>';
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
                    <div id="mensaje" style="display: none;"></div>


                    <form class="form-inline" method="post" action="reporte_palets.php">   
                        <div class="panel panel-default">
                            <div class="panel-body">

                                <div class="form-group"> 
                                    <label for="datetimepicker">Fecha Trackeo Desde:</label>

                                </div>
                                <div class="form-group">
                                    <div class="input-group date" style="width: 250px;" id="datetimepicker">
                                        <input type='text' class="form-control" name="txtinicio" id="txtinicio" value="<?php echo $_SESSION['txtinicio'] ?>"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            $('#datetimepicker').datetimepicker({
                                                format: 'YYYY-MM-DD',
                                                showTodayButton: true
                                            });
                                        });
                                    </script>
                                </div>
                                <div class="form-group"> 
                                    <label for="datetimepicker">Hasta:</label>

                                </div>
                                <div class="form-group">
                                    <div class="input-group date" style="width: 250px;" id="datetimepicker1">
                                        <input type='text' class="form-control" name="txtfin" id="txtfin" value="<?php echo $_SESSION['txtfin'] ?>"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            $('#datetimepicker1').datetimepicker({
                                                format: 'YYYY-MM-DD',
                                                showTodayButton: true
                                            });
                                        });
                                    </script>
                                </div>

                                <div class="form-group"> 
                                    <input type="submit" name="buscar" class="btn btn-primary"id="buscar" value="Buscar" />
                                </div>
                                </form>
                                <?php
                                if (isset($_POST['buscar'])) {
                                    echo '
                                        <div class="form-group" style="float: right">
                                            <form action="Fichero_Excel.php" method="post" target="_blank" id="FormularioExportacion">
                                                <button type="button" class="btn btn-primary botonExcel" data-toggle="tooltip" aria-label="Exportar Excell" title = "Exportar Excell">
                                                    <img src="../images/excel.gif" width="30" height="30" /> Exportar a Excel 
                                                </button>
                                                <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
                                            </form>  
                                        </div>';
                                }
                                ?>

                            </div>
                        </div>



                        <div class="table-responsive">
<?php if (isset($_POST['buscar'])) { ?>
                                <table id="tabla" class="table table-condensed" width="100%">
                                    <thead>
                                        <tr>
                                            <td colspan="8" align="center">
                                                <h3><strong>Reporte de Guias Masters Trackeadas</strong></h3>
                                            </td>
                                        </tr>    
                                        <tr>
                                            <th class="all">Guia Master</th>  
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        <?php
                                        $sql1 = "SELECT DISTINCT guia_master FROM tblcoldroom where";

                                        if (isset($_POST['txtinicio']) && isset($_POST['txtfin'])) {
                                            $sql1.=" fecha_tracking>='" . $_POST['txtinicio'] . "' and fecha_tracking<='" . $_POST['txtfin'] . "'";
                                            $_SESSION['txtinicio'] = $_POST['txtinicio'];
                                            $_SESSION['txtfin'] = $_POST['txtfin'];
                                        } else if (isset($_POST['txtinicio']) && !isset($_POST['txtfin'])) {
                                            $sql1.=" fecha_tracking>='" . $_POST['txtinicio'] . "'";
                                            $_SESSION['txtinicio'] = $_POST['txtinicio'];
                                        } else if (!isset($_POST['txtinicio']) && isset($_POST['txtfin'])) {
                                            $sql1.=" fecha_tracking<='" . $_POST['txtfin'] . "'";
                                            $_SESSION['txtfin'] = $_POST['txtfin'];
                                        }
                                        $sql1.=" order by fecha_tracking DESC";

                                        $res = mysqli_query($link, $sql1);
                                        while ($row = mysqli_fetch_array($res)) {
                                            echo '<tr><td><strong><a href="?guia=' . $row['guia_master'] . '">' . $row['guia_master'] . '</a></strong></td><td></td><td></td><td></td><td></td><td></td></tr>';
                                        }
                                    } else if (isset($_GET['guia'])) {
                                        ?>
                                    <table id="tabla" class="table table-condensed" width="100%">
                                        <thead>
                                            <tr>
                                                <td colspan="8" align="center">
                                                    <h3><strong>Reporte de la Guía Máster: <?php echo $_GET['guia'] ?></strong></h3>
                                                </td>
                                            </tr>    
                                            <tr>
                                                <th class="all">No.</th>  
                                                <th class="all">Palet</th>
                                                <th class="all">Codigo</th>
                                                <th class="all">Guia Madre</th>
                                                <th class="all">Guia Hija</th>
                                                <th class="all">Finca</th>
                                            </tr>
                                        </thead>
                                        <tbody> 
                                            <?php
                                            $sql2 = "SELECT tblcoldroom.palet,tblcoldroom.codigo,guia_master,guia_madre,guia_hija,finca FROM tblcoldroom where guia_master='" . $_GET['guia'] . "' ORDER BY guia_master ASC, palet ASC";
                                            $i = 1;
                                            $res2 = mysqli_query($link, $sql2);
                                            while ($row2 = mysqli_fetch_array($res2)) {
                                                echo '<tr>';
                                                echo '<td>' . $i++ . '</td>';
                                                echo '<td>' . $row2['palet'] . '</td>';
                                                echo '<td>' . $row2['codigo'] . '</td>';
                                                echo '<td>' . $row2['guia_madre'] . '</td>';
                                                echo '<td>' . $row2['guia_hija'] . '</td>';
                                                echo '<td>' . $row2['finca'] . '</td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                        </div> 
                </div> 
                <div class="panel-heading">
                    <div class="contenedor" align="center">
                        <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2015 versión 3</strong>
                        <br>
                        <strong><a href="http://www.bit-store.ec/index.php/contactenos/"  style="color:white">Info</a> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></strong>
                    </div>
                </div>
            </div>
    </body>
</html>