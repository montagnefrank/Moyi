<?php
///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

ini_set('max_execution_time', 900);
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
$id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Imprimir Etiquetas</title>

        <link rel="icon" type="image/png" href="../images/favicon.ico" />
        <link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">

        <script language="javascript" src="../js/imprimir.js"></script>
        <script type="text/javascript" src="../js/script.js"></script>
        <script src="../bootstrap/js/jquery.js"></script>
        <script src="../bootstrap/js/bootstrap.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
        <script src="../bootstrap/js/bootstrap-submenu.js"></script>
        <script src="../bootstrap/js/docs.js" defer></script>


        <style>
            .contenedor {
                margin:0 auto;
                width:85%;
                text-align:center;
            }

            .navbar-fixed-top + .content-container {
                margin-top: 70px;
            }
            .content-container {
                margin: 0 130px;
            }

            #top-link-block.affix-top {
                position: absolute; /* allows it to "slide" up into view */
                bottom: -82px; /* negative of the offset - height of link element */
                left: 10px; /* padding from the left side of the window */
            }
            #top-link-block.affix {
                position: fixed; /* keeps it on the bottom once in view */
                bottom: 18px; /* height of link element */
                left: 10px; /* padding from the left side of the window */
            }
            li a{
                cursor:pointer;/*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
            } 
        </style>
        <script>
            function print1(finca, fecha, item, reimprimir, vuelo, agencia) {
                window.open("print.php?finca=" + finca + "&fecha=" + fecha + "&item=" + item + "&status=" + reimprimir + "&vuelo=" + vuelo + "&agencia=" + agencia + "", "Imprimir", "width=300,height=200,top=200,left=350");
                return false;
            }
            function print2(finca, fecha, item, reimprimir, vuelo) {
                window.open("print.php?finca=" + finca + "&fecha=" + fecha + "&item=" + item + "&status=" + reimprimir + "&vuelo=" + vuelo + "", "Imprimir", "width=300,height=200,top=200,left=350");
                return false;
            }
            $(document).ready(function () {
                //tol-tip-text
                $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                });

                // Only enable if the document has a long scroll bar
                // Note the window height + offset
                if (($(window).height() + 100) < $(document).height()) {
                    $('#top-link-block').removeClass('hidden').affix({
                        // how far to scroll down before link "slides" into view
                        offset: {top: 100}
                    });
                }
            });
        </script>

        <script src="../bootstrap/js/moment.js"></script>
        <link href="../bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" media="all"  />
        <script src="../bootstrap/js/bootstrap-datetimepicker.min.js"></script>
    </head>
    <body background="../images/fondo.jpg">
        <div class="container">
            <div align="center" width="100%">
                <img src="../images/logo.png"  class="img-responsive"/>
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
                                <a class="navbar-brand" href="mainroom.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>';

                            </div>

                            <!-- Agrupar los enlaces de navegación, los formularios y cualquier
                                 otro elemento que se pueda ocultar al minimizar la barra -->
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                <ul class="nav navbar-nav">
                                    <li>
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
                                    <li class="active">
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

                <div class="panel-body" align="center">
                    <form method="post" id="frm1" name="frm1" target="_parent" class="form-inline">
                        <div class="table-responsive">
                            <table width="50%" border="0" align="center" class="table table-striped" >
                                <tr>
                                    <td  colspan="5" align="center"><h3><strong>GESTI&Oacute;N DE ETIQUETAS DE CUARTO FRIO </strong></h3></td>
                                </tr>
                                <tr>
                                    <td colspan="5" align="center">
                                        <div class="form-group">
                                            <label>Finca:</label>
                                            <select type="text" name="finca" id="finca" class="form-control" style="width: auto;">
                                                <option value="-1"> TODAS LAS FINCAS </option>
                                                <?php
                                                //Consulto la bd para obtener solo los id de item existentes
                                                $sql = "SELECT nombre FROM tblfinca";
                                                $query = mysqli_query($link, $sql);
                                                //Recorrer los item para mostrar
                                                while ($row1 = mysqli_fetch_array($query)) {

                                                    if (isset($_POST['finca'])) {
                                                        if ($_POST['finca'] != $row1["nombre"])
                                                            echo '<option value="' . $row1["nombre"] . '">' . $row1["nombre"] . '</option>';
                                                        else
                                                            echo '<option value="' . $row1["nombre"] . '" selected="selected">' . $row1["nombre"] . '</option>';
                                                    }
                                                    else {
                                                        echo '<option value="' . $row1["nombre"] . '">' . $row1["nombre"] . '</option>';
                                                    }
                                                }
                                                ?>                       
                                            </select>
                                            <input type="submit" name="buscar" id="buscar" value="Buscar" class="btn btn-primary"/>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <td colspan="5" align="center">
                                        <div class="col-md-2">
                                            <label>Fecha de Entrega:</label>
                                        </div>
                                        <div class="col-md-4">

                                            <div class="input-group date" style="width: auto;" id="datetimepicker">
                                                <input type='text' class="form-control" name="fecha" id="fecha" value="<?php 
                                                if (!isset($_POST['fecha'])){
                                                echo date('Y-m-d'); 
                                                } else {
                                                    echo $_POST['fecha']; 
                                                }
                                                ?>"/>
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
                                        <div class="col-md-4">
                                            <div class="input-group date" style="width: auto;" id="datetimepicker1">
                                                <input type='text' class="form-control" name="fecha1" id="fecha1" value="<?php 
                                                if (!isset($_POST['fecha1'])){
                                                echo date('Y-m-d'); 
                                                } else {
                                                    echo $_POST['fecha1']; 
                                                }
                                                ?>"/>
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
                                    </td>

                                </tr>
                            </table>
                        </div>

                        <div class="table-responsive">
                            <table width="50%" border="0" align="center" class="table table-condensed" > 
                                <tr>
                                    <td colspan="8" align="center">
                                        <h3><strong>Listado de etiquetas</strong></h3>
                                    </td>
                                </tr>
                                <?php
                                if (!isset($_POST['buscar'])) {
                                    $sql1 = "SELECT distinct agencia,finca FROM tbletiquetasxfinca where archivada = 'No'  AND estado!= '5' AND fecha BETWEEN '" . date('Y-m-d') . "' AND '" . date('Y-m-d') . "'  ORDER BY agencia DESC";
                                    $valag = mysqli_query($link, $sql1)or die("Error seleccionando los agencias");
                                    $cant = mysqli_num_rows($valag);

                                    while ($rowag = mysqli_fetch_array($valag)) {
                                        echo "<tr style='background-color:#f9f9f9'><td colspan='8'><strong>Agencia: " . $rowag['agencia'] . "</strong></td></tr>";
                                        echo "<tr style='background-color:#f9f9f9'><td colspan='8'><strong>Finca: " . $rowag['finca'] . "</strong></td></tr>";
                                        //Selecciono cada item con solicitud activa
                                        $sql = "SELECT distinct fecha,fecha_tentativa FROM tbletiquetasxfinca where finca='" . $rowag['finca'] . "' AND archivada = 'No'  AND estado!= '5' AND agencia='" . $rowag['agencia'] . "' AND fecha BETWEEN '" . date('Y-m-d') . "' AND '" . date('Y-m-d') . "' order by fecha";
                                        $val = mysqli_query($link, $sql)or die("Error seleccionando los pedidos");

                                        //Recorro por cada nro pedido cada item
                                        while ($row = mysqli_fetch_array($val)) {

                                            echo "<tr>";
                                            echo "<td align='left' colspan='3'><strong>Fecha de entrega: " . $row['fecha'] . "</strong></td>";
                                            echo "</tr>";
                                            echo '<tr>
                       <td align="center"><strong>Producto</strong></td>
                       <td align="center"><strong>Prod. Desc.</strong></td>
                       <td align="center"><strong>Solicitadas</strong></td>
                       <td align="center"><strong>Enviadas</strong></td> 
                       <td align="center"><strong>Rechazadas</strong></td>
                       <td align="center"><strong>Cierre de Día</strong></td>
                       <td align="center"><strong>Por enviar</strong></td>
                       <td align="center"><strong>Imprimir etiquetas</strong></td>
                 </tr>	';

                                            //Selecciono cada nropedido los items
                                            $sentencia = "SELECT distinct item FROM tbletiquetasxfinca where fecha ='" . $row['fecha'] . "' AND finca='" . $rowag['finca'] . "' AND archivada = 'No'  AND estado!= '5' AND agencia='" . $rowag['agencia'] . "'  order by item";
                                            $consulta = mysqli_query($link, $sentencia)or die("Error seleccionando los items con solicitudes");

                                            //Por cada item cuento cuantas solicitudes hay
                                            while ($fila = mysqli_fetch_array($consulta)) {
                                                //Se cuenta cuantas solicitudes y entregas hay por cada finca e item
                                                $sql1 = "SELECT SUM(solicitado) as solicitado,SUM(entregado) as entregado, item, fecha, precio FROM tbletiquetasxfinca where fecha='" . $row['fecha'] . "' AND finca='" . $rowag['finca'] . "' AND estado!='5' AND item = '" . $fila['item'] . "' AND agencia='" . $rowag['agencia'] . "'";
                                                $val1 = mysqli_query($link, $sql1) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                                $row1 = mysqli_fetch_array($val1);

                                                //Se cuenta cuantas solicitudes rechazadas hay por cada finca e item
                                                $sql2 = "SELECT COUNT(*) as rechazado FROM tbletiquetasxfinca where estado='2' AND fecha = '" . $row['fecha'] . "' AND finca='" . $rowag['finca'] . "' AND item = '" . $fila['item'] . "' AND agencia='" . $rowag['agencia'] . "'";
                                                $val2 = mysqli_query($link, $sql2) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                                $row2 = mysqli_fetch_array($val2);

                                                //Se cuenta cuantas solicitudes con cierre de dia por cada finca e item
                                                $sql3 = "SELECT COUNT(*) as cierre FROM tbletiquetasxfinca where estado= '3' AND fecha = '" . $row['fecha'] . "' AND finca='" . $rowag['finca'] . "' AND item = '" . $fila['item'] . "' AND agencia='" . $rowag['agencia'] . "'";
                                                $val3 = mysqli_query($link, $sql3) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                                $row3 = mysqli_fetch_array($val3);

                                                echo "<tr>";
                                                echo "<td align='center'><strong>" . $row1['item'] . "</strong></td>";

                                                //Seleccionando l adescripcion del item
                                                $sql4 = "SELECT prod_descripcion FROM tblproductos where id_item='" . $row1['item'] . "'";
                                                $val4 = mysqli_query($link, $sql4) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                                $row4 = mysqli_fetch_array($val4);

                                                echo "<td><strong>" . $row4['prod_descripcion'] . "</strong></td>";
                                                echo "<td align='center'>" . $row1['solicitado'] . "</td>";
                                                $totalsol += $row1['solicitado'];
                                                echo "<td align='center'>" . $row1['entregado'] . "</td>";
                                                echo "<td align='center'>" . $row2['rechazado'] . "</td>";
                                                echo "<td align='center'>" . $row3['cierre'] . "</td>";

                                                //se restan las solicitudes - entregado - rechazado	
                                                $totalrech += $row2['rechazado'];
                                                $totalent += $row1['entregado'];
                                                $dif = $row1['solicitado'] - $row1['entregado'] - $row2['rechazado'] - $row3['cierre'];
                                                $totalcierre = $row3['cierre'];
                                                if ($dif < 0) {
                                                    $dif = "0";
                                                }
                                                $totaldif += $dif;
                                                $totalprecio += $row1['precio'];

                                                echo "<td align='center'>" . $dif . "</td>";
                                                if ($dif != 0) {
                                                    echo '<td align="center"><a href="#" onclick="print1(\'' . $rowag['finca'] . '\',\'' . $row['fecha'] . '\',\'' . $row1['item'] . '\',\'false\',\'' . $row['fecha_tentativa'] . '\',\'' . $rowag['agencia'] . '\')"><img src="../images/print.png" name="btn_cliente" id="btn_cliente" data-toggle="tooltip" data-placement="left" title = "Imprimir etiquetas de esta fecha" width="20" height="20"/></a></td>';
                                                    echo "</tr>";
                                                } else {
                                                    echo '<td align="center">
                                <a href="#" style="cursor:not-allowed" ><img src="../images/print.png" name="btn_cliente" id="btn_cliente" data-toggle="tooltip" data-placement="left" title = "Imprimir etiquetas de esta fecha" width="20" height="20"/></a>
                                <a class="btn btn-info" href="#" onclick="print2(\'' . $rowag['finca'] . '\',\'' . $row['fecha'] . '\',\'' . $row1['item'] . '\',\'true\',\'' . $row['fecha_tentativa'] . '\')"><b>!</b></a></td>';
                                                    echo "</tr>";
                                                }
                                            }//fin while

                                            if ($totalsol < 0) {
                                                $totalsol = "0";
                                            }
                                            if ($totalent < 0) {
                                                $totalent = "0";
                                            }
                                            if ($totalrech < 0) {
                                                $totalrech = "0";
                                            }
                                            if ($totalcierre < 0) {
                                                $totalcierre = "0";
                                            }
                                            echo "
                                                <tr>
                                                <td align='right'></td>				  
                                                <td align='center'><strong>Total por Fecha:</strong></td>
                                                <td align='center'><strong>" . $totalsol . "</strong></td>
                                                <td align='center'><strong>" . $totalent . "</strong></td>
                                                <td align='center'><strong>" . $totalrech . "</strong></td>
                                                <td align='center'><strong>" . $totalcierre . "</strong></td>";

                                            if ($totaldif == 0) {
                                                echo "<td align='center'><button type='button' class='btn btn-danger' data-toggle='tooltip' data-placement='left' title = 'No hay etiquetas por imprimir'><strong>" . $totaldif . "</strong></button></td>";
                                            } else {
                                                echo "<td align='center'><button type='button' class='btn btn-success' data-toggle='tooltip' data-placement='left' title = 'Hay etiquetas por imprimir'><strong>" . $totaldif . "</strong></button></td>";
                                            }

                                            //Contar los subtotales
                                            $TOTALSOL += $totalsol;
                                            $TOTALENT += $totalent;
                                            $TOTALRECH += $totalrech;
                                            $TOTALCIERRE += $totalcierre;
                                            $TOTALDIF += $totaldif;

//						 if($TOTALDIF != 0){
//						        echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/print.png" heigth="30" value="" data-toggle="tooltip" data-placement="left" title = "Imprimir etiquetas de esta fecha" width="20" onclick="print1(\''.$_SESSION['dato'].'\',\''.$row['fecha'].'\')"/></td>';
//						        echo "</tr>";
//						}else{				
//							echo '<td align="center"><input disabled="true" type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../images/print.png" heigth="30" value="" width="20" data-toggle="tooltip" data-placement="left" title = "No hay etiquetas para imprimir de este pedido o el DAE está caducado"/></td>';
//						        echo "</tr>";
//					        }
                                            //Resetear los subtotales
                                            $totalsol = 0;
                                            $totalent = 0;
                                            $totalrech = 0;
                                            $totalcierre = 0;
                                            $totaldif = 0;
                                        }
                                    }

                                    if ($cant > 0) {
                                        echo "
                                            <tr>
                                            <td align='right'></td>				  
                                            <td align='center'><strong>Total General:</strong></td>
                                            <td align='center'><strong>" . $TOTALSOL . "</strong></td>
                                            <td align='center'><strong>" . $TOTALENT . "</strong></td>
                                            <td align='center'><strong>" . $TOTALRECH . "</strong></td>
                                            <td align='center'><strong>" . $TOTALCIERRE . "</strong></td>";

                                        if ($TOTALDIF == 0) {
                                            echo "<td align='center'><button type='button' class='btn btn-danger btn-lg' data-toggle='tooltip' data-placement='left' title = 'No hay etiquetas por imprimir'><strong>" . $TOTALDIF . "</strong></button></td>";
                                        } else {
                                            echo "<td align='center'><button type='button' class='btn btn-success btn-lg' data-toggle='tooltip' data-placement='left' title = 'Hay etiquetas por imprimir'><strong>" . $TOTALDIF . "</strong></button></td>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='12'><strong>No hay resultados que mostrar.</strong></td></tr>";
                                    }
                                } else {

                                    if ($_POST ['finca'] != "-1") {

                                        //recoger datos de busqueda
                                        $finca = $_POST['finca'];
                                        $fecha = $_POST ['fecha'];
                                        $fecha1 = $_POST ['fecha1'];

                                        $sql1 = "SELECT distinct agencia FROM tbletiquetasxfinca where finca='" . $finca . "' AND archivada = 'No'  AND estado!= '5' AND estado!= '2'";
                                        if (!empty($_POST ['fecha'])) {
                                            $sql1 .= " AND fecha BETWEEN '" . $fecha . "' AND '" . $fecha1 . "'";
                                        }
                                        $valag = mysqli_query($link, $sql1)or die("Error seleccionando los agencias");
                                        $cant = mysqli_num_rows($valag);

                                        while ($rowag = mysqli_fetch_array($valag)) {
                                            echo "<tr><td colspan='8'><strong>Agencia: " . $rowag['agencia'] . "</strong></td></tr>";
                                            //Selecciono cada item con solicitud activa
                                            $sql = "SELECT distinct fecha,fecha_tentativa FROM tbletiquetasxfinca where finca='" . $finca . "' AND archivada = 'No'  AND estado!= '5' AND agencia='" . $rowag['agencia'] . "' ";
                                            if (!empty($_POST ['fecha'])) {
                                                $sql .= " AND fecha BETWEEN '" . $fecha . "' AND '" . $fecha1 . "'";
                                            }
                                            $val = mysqli_query($link, $sql)or die("Error seleccionando los pedidos");

                                            //Recorro por cada nro pedido cada item
                                            while ($row = mysqli_fetch_array($val)) {

                                                echo "<tr>";
                                                echo "<td align='left' colspan='3'><strong>Fecha de entrega: " . $row['fecha'] . "</strong></td>";
                                                echo "</tr>";
                                                echo '<tr>
                       <td align="center"><strong>Producto</strong></td>
                       <td align="center"><strong>Prod. Desc.</strong></td>
                       <td align="center"><strong>Solicitadas</strong></td>
                       <td align="center"><strong>Enviadas</strong></td> 
                       <td align="center"><strong>Rechazadas</strong></td>
                       <td align="center"><strong>Cierre de Día</strong></td>
                       <td align="center"><strong>Por enviar</strong></td>
                       <td align="center"><strong>Imprimir etiquetas</strong></td>
                 </tr>	';

                                                //Selecciono cada nropedido los items
                                                $sentencia = "SELECT distinct item FROM tbletiquetasxfinca where fecha ='" . $row['fecha'] . "' AND fecha_tentativa ='" . $row['fecha_tentativa'] . "' AND finca='" . $finca . "' AND archivada = 'No'  AND estado!= '5' AND agencia='" . $rowag['agencia'] . "'  order by item";
                                                $consulta = mysqli_query($link, $sentencia)or die("Error seleccionando los items con solicitudes");

                                                //Por cada item cuento cuantas solicitudes hay
                                                while ($fila = mysqli_fetch_array($consulta)) {
                                                    //Se cuenta cuantas solicitudes y entregas hay por cada finca e item
                                                    $sql1 = "SELECT SUM(solicitado) as solicitado,SUM(entregado) as entregado, item, fecha, precio FROM tbletiquetasxfinca where fecha='" . $row['fecha'] . "' AND fecha_tentativa ='" . $row['fecha_tentativa'] . "' AND finca='" . $finca . "' AND estado!='5' AND item = '" . $fila['item'] . "' AND agencia='" . $rowag['agencia'] . "' AND archivada = 'No'";
                                                    $val1 = mysqli_query($link, $sql1) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                                    $row1 = mysqli_fetch_array($val1);

                                                    //Se cuenta cuantas solicitudes rechazadas hay por cada finca e item
                                                    $sql2 = "SELECT COUNT(*) as rechazado FROM tbletiquetasxfinca where estado='2' AND fecha = '" . $row['fecha'] . "' AND fecha_tentativa ='" . $row['fecha_tentativa'] . "' AND finca='" . $finca . "' AND item = '" . $fila['item'] . "' AND agencia='" . $rowag['agencia'] . "'";
                                                    $val2 = mysqli_query($link, $sql2) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                                    $row2 = mysqli_fetch_array($val2);

                                                    //Se cuenta cuantas solicitudes con cierre de dia por cada finca e item
                                                    $sql3 = "SELECT COUNT(*) as cierre FROM tbletiquetasxfinca where estado= '3' AND fecha = '" . $row['fecha'] . "' AND fecha_tentativa ='" . $row['fecha_tentativa'] . "' AND finca='" . $finca . "' AND item = '" . $fila['item'] . "' AND agencia='" . $rowag['agencia'] . "'";
                                                    $val3 = mysqli_query($link, $sql3) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                                    $row3 = mysqli_fetch_array($val3);

                                                    echo "<tr>";
                                                    echo "<td align='center'><strong>" . $row1['item'] . "</strong></td>";

                                                    //Seleccionando l adescripcion del item
                                                    $sql4 = "SELECT prod_descripcion FROM tblproductos where id_item='" . $row1['item'] . "'";
                                                    $val4 = mysqli_query($link, $sql4) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                                    $row4 = mysqli_fetch_array($val4);

                                                    echo "<td><strong>" . $row4['prod_descripcion'] . "</strong></td>";
                                                    echo "<td align='center'>" . $row1['solicitado'] . "</td>";
                                                    $totalsol += $row1['solicitado'];
                                                    echo "<td align='center'>" . $row1['entregado'] . "</td>";
                                                    echo "<td align='center'>" . $row2['rechazado'] . "</td>";
                                                    echo "<td align='center'>" . $row3['cierre'] . "</td>";

                                                    //se restan las solicitudes - entregado - rechazado	
                                                    $totalrech += $row2['rechazado'];
                                                    $totalent += $row1['entregado'];
                                                    $dif = $row1['solicitado'] - $row1['entregado'] - $row2['rechazado'] - $row3['cierre'];
                                                    $totalcierre = $row3['cierre'];
                                                    if ($dif < 0) {
                                                        $dif = "0";
                                                    }
                                                    $totaldif += $dif;
                                                    $totalprecio += $row1['precio'];

                                                    echo "<td align='center'>" . $dif . "</td>";
                                                    if ($dif != 0) {
                                                        echo '<td align="center"><a href="#" name="btn_cliente" id="btn_cliente" onclick="print2(\'' . $finca . '\',\'' . $row['fecha'] . '\',\'' . $row1['item'] . '\',\'true\',\'' . $row['fecha_tentativa'] . '\')" ><img src="../images/print.png" data-toggle="tooltip" data-placement="left" title = "Imprimir etiquetas de esta fecha" width="20" /></a>';
                                                        echo '<a class="btn btn-info" href="#" onclick="print2(\'' . $finca . '\',\'' . $row['fecha'] . '\',\'' . $row1['item'] . '\',\'true\',\'' . $row['fecha_tentativa'] . '\')"><b>!</b></a></td>';
                                                        echo "</tr>";
                                                    } else {
                                                        echo '<td align="center">
                                <a href="#" style="cursor:not-allowed" ><img src="../images/print.png" name="btn_cliente" id="btn_cliente" data-toggle="tooltip" data-placement="left" title = "Imprimir etiquetas de esta fecha" width="20" height="20"/></a>
                                <a class="btn btn-info" href="#" onclick="print2(\'' . $finca . '\',\'' . $row['fecha'] . '\',\'' . $row1['item'] . '\',\'true\',\'' . $row['fecha_tentativa'] . '\')"><b>!</b></a></td>';
                                                        echo "</tr>";
                                                    }
                                                }//fin while
                                                if ($totalsol < 0) {
                                                    $totalsol = "0";
                                                }
                                                if ($totalent < 0) {
                                                    $totalent = "0";
                                                }
                                                if ($totalrech < 0) {
                                                    $totalrech = "0";
                                                }
                                                if ($totalcierre < 0) {
                                                    $totalcierre = "0";
                                                }

                                                echo "
          <tr>
          <td align='right'></td>				  
          <td align='center'><strong>Total por Fecha:</strong></td>
          <td align='center'><strong>" . $totalsol . "</strong></td>
          <td align='center'><strong>" . $totalent . "</strong></td>
          <td align='center'><strong>" . $totalrech . "</strong></td>
          <td align='center'><strong>" . $totalcierre . "</strong></td>";

                                                if ($totaldif == 0) {
                                                    echo "<td align='center'><button type='button' class='btn btn-danger' data-toggle='tooltip' data-placement='left' title = 'No hay etiquetas por imprimir'><strong>" . $totaldif . "</strong></button></td>";
                                                } else {
                                                    echo "<td align='center'><button type='button' class='btn btn-success' data-toggle='tooltip' data-placement='left' title = 'Hay etiquetas por imprimir'><strong>" . $totaldif . "</strong></button></td>";
                                                }

                                                //Contar los subtotales
                                                $TOTALSOL += $totalsol;
                                                $TOTALENT += $totalent;
                                                $TOTALRECH += $totalrech;
                                                $TOTALCIERRE += $totalcierre;
                                                $TOTALDIF += $totaldif;

//						 if($TOTALDIF != 0){
//						        echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/print.png" heigth="30" value="" data-toggle="tooltip" data-placement="left" title = "Imprimir etiquetas de esta fecha" width="20" onclick="print1(\''.$_SESSION['dato'].'\',\''.$row['fecha'].'\')"/></td>';
//						        echo "</tr>";
//						}else{				
//							echo '<td align="center"><input disabled="true" type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../images/print.png" heigth="30" value="" width="20" data-toggle="tooltip" data-placement="left" title = "No hay etiquetas para imprimir de este pedido o el DAE está caducado"/></td>';
//						        echo "</tr>";
//					        }
                                                //Resetear los subtotales
                                                $totalsol = 0;
                                                $totalent = 0;
                                                $totalrech = 0;
                                                $totalcierre = 0;
                                                $totaldif = 0;
                                            }
                                        }

                                        if ($cant > 0) {
                                            echo "
          <tr>
          <td align='right'></td>				  
          <td align='center'><strong>Total General:</strong></td>
          <td align='center'><strong>" . $TOTALSOL . "</strong></td>
          <td align='center'><strong>" . $TOTALENT . "</strong></td>
          <td align='center'><strong>" . $TOTALRECH . "</strong></td>
          <td align='center'><strong>" . $TOTALCIERRE . "</strong></td>";

                                            if ($TOTALDIF == 0) {
                                                echo "<td align='center'><button type='button' class='btn btn-danger btn-lg' data-toggle='tooltip' data-placement='left' title = 'No hay etiquetas por imprimir'><strong>" . $TOTALDIF . "</strong></button></td>";
                                            } else {
                                                echo "<td align='center'><button type='button' class='btn btn-success btn-lg' data-toggle='tooltip' data-placement='left' title = 'Hay etiquetas por imprimir'><strong>" . $TOTALDIF . "</strong></button></td>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='12'><strong>No hay resultados que mostrar.</strong></td></tr>";
                                        }
                                        mysqli_close($conection);
                                    } else {
                                        
                                        $fecha = $_POST ['fecha'];
                                        $fecha1 = $_POST ['fecha1'];
                                        $sql1 = "SELECT distinct agencia,finca FROM tbletiquetasxfinca where archivada = 'No'  AND estado!= '5' AND fecha BETWEEN '" . $fecha . "' AND '" . $fecha1 . "'  ORDER BY agencia DESC";
                                        $valag = mysqli_query($link, $sql1)or die("Error seleccionando los agencias");
                                        $cant = mysqli_num_rows($valag);

                                        while ($rowag = mysqli_fetch_array($valag)) {
                                            echo "<tr style='background-color:#f9f9f9'><td colspan='8'><strong>Agencia: " . $rowag['agencia'] . "</strong></td></tr>";
                                            echo "<tr style='background-color:#f9f9f9'><td colspan='8'><strong>Finca: " . $rowag['finca'] . "</strong></td></tr>";
                                            //Selecciono cada item con solicitud activa
                                            $sql = "SELECT distinct fecha,fecha_tentativa FROM tbletiquetasxfinca where finca='" . $rowag['finca'] . "' AND archivada = 'No'  AND estado!= '5' AND agencia='" . $rowag['agencia'] . "' AND fecha BETWEEN '" . $fecha . "' AND '" . $fecha1 . "' order by fecha";
                                            $val = mysqli_query($link, $sql)or die("Error seleccionando los pedidos");

                                            //Recorro por cada nro pedido cada item
                                            while ($row = mysqli_fetch_array($val)) {

                                                echo "<tr>";
                                                echo "<td align='left' colspan='3'><strong>Fecha de entrega: " . $row['fecha'] . "</strong></td>";
                                                echo "</tr>";
                                                echo '<tr>
                       <td align="center"><strong>Producto</strong></td>
                       <td align="center"><strong>Prod. Desc.</strong></td>
                       <td align="center"><strong>Solicitadas</strong></td>
                       <td align="center"><strong>Enviadas</strong></td> 
                       <td align="center"><strong>Rechazadas</strong></td>
                       <td align="center"><strong>Cierre de Día</strong></td>
                       <td align="center"><strong>Por enviar</strong></td>
                       <td align="center"><strong>Imprimir etiquetas</strong></td>
                 </tr>	';

                                                //Selecciono cada nropedido los items
                                                $sentencia = "SELECT distinct item FROM tbletiquetasxfinca where fecha ='" . $row['fecha'] . "' AND finca='" . $rowag['finca'] . "' AND archivada = 'No'  AND estado!= '5' AND agencia='" . $rowag['agencia'] . "'  order by item";
                                                $consulta = mysqli_query($link, $sentencia)or die("Error seleccionando los items con solicitudes");

                                                //Por cada item cuento cuantas solicitudes hay
                                                while ($fila = mysqli_fetch_array($consulta)) {
                                                    //Se cuenta cuantas solicitudes y entregas hay por cada finca e item
                                                    $sql1 = "SELECT SUM(solicitado) as solicitado,SUM(entregado) as entregado, item, fecha, precio, nropedido FROM tbletiquetasxfinca where fecha='" . $row['fecha'] . "' AND finca='" . $rowag['finca'] . "' AND estado!='5' AND item = '" . $fila['item'] . "' AND agencia='" . $rowag['agencia'] . "'";
                                                    $val1 = mysqli_query($link, $sql1) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                                    $row1 = mysqli_fetch_array($val1);

                                                    //Se cuenta cuantas solicitudes rechazadas hay por cada finca e item
                                                    $sql2 = "SELECT COUNT(*) as rechazado FROM tbletiquetasxfinca where estado='2' AND fecha = '" . $row['fecha'] . "' AND finca='" . $rowag['finca'] . "' AND item = '" . $fila['item'] . "' AND agencia='" . $rowag['agencia'] . "'";
                                                    $val2 = mysqli_query($link, $sql2) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                                    $row2 = mysqli_fetch_array($val2);

                                                    //Se cuenta cuantas solicitudes con cierre de dia por cada finca e item
                                                    $sql3 = "SELECT COUNT(*) as cierre FROM tbletiquetasxfinca where estado= '3' AND fecha = '" . $row['fecha'] . "' AND finca='" . $rowag['finca'] . "' AND item = '" . $fila['item'] . "' AND agencia='" . $rowag['agencia'] . "'";
                                                    $val3 = mysqli_query($link, $sql3) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                                    $row3 = mysqli_fetch_array($val3);

                                                    echo "<tr>";
                                                    echo "<td align='center'><strong>" . $row1['item'] . "</strong></td>";

                                                    //Seleccionando l adescripcion del item
                                                    $sql4 = "SELECT prod_descripcion FROM tblproductos where id_item='" . $row1['item'] . "'";
                                                    $val4 = mysqli_query($link, $sql4) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                                    $row4 = mysqli_fetch_array($val4);

                                                    echo "<td><strong>" . $row4['prod_descripcion'] . "</strong></td>";
                                                    echo "<td align='center'><a href= 'etiqxentregar_rechazar.php?id=" . $row1['nropedido'] . "' title='Rechazar cajas'>" . $row1['solicitado'] . "</a></td>";
                                                    $totalsol += $row1['solicitado'];
                                                    echo "<td align='center'>" . $row1['entregado'] . "</td>";
                                                    echo "<td align='center'>" . $row2['rechazado'] . "</td>";
                                                    echo "<td align='center'>" . $row3['cierre'] . "</td>";

                                                    //se restan las solicitudes - entregado - rechazado	
                                                    $totalrech += $row2['rechazado'];
                                                    $totalent += $row1['entregado'];
                                                    $dif = $row1['solicitado'] - $row1['entregado'] - $row2['rechazado'] - $row3['cierre'];
                                                    $totalcierre = $row3['cierre'];
                                                    if ($dif < 0) {
                                                        $dif = "0";
                                                    }
                                                    $totaldif += $dif;
                                                    $totalprecio += $row1['precio'];

                                                    echo "<td align='center'>" . $dif . "</td>";
                                                    if ($dif != 0) {
                                                        echo '<td align="center"><a href="#" onclick="print1(\'' . $rowag['finca'] . '\',\'' . $row['fecha'] . '\',\'' . $row1['item'] . '\',\'false\',\'' . $row['fecha_tentativa'] . '\',\'' . $rowag['agencia'] . '\')"><img src="../images/print.png" name="btn_cliente" id="btn_cliente" data-toggle="tooltip" data-placement="left" title = "Imprimir etiquetas de esta fecha" width="20" height="20"/></a></td>';
                                                        echo "</tr>";
                                                    } else {
                                                        echo '<td align="center">
                                <a href="#" style="cursor:not-allowed" ><img src="../images/print.png" name="btn_cliente" id="btn_cliente" data-toggle="tooltip" data-placement="left" title = "Imprimir etiquetas de esta fecha" width="20" height="20"/></a>
                                <a class="btn btn-info" href="#" onclick="print2(\'' . $rowag['finca'] . '\',\'' . $row['fecha'] . '\',\'' . $row1['item'] . '\',\'true\',\'' . $row['fecha_tentativa'] . '\')"><b>!</b></a></td>';
                                                        echo "</tr>";
                                                    }
                                                }//fin while

                                                if ($totalsol < 0) {
                                                    $totalsol = "0";
                                                }
                                                if ($totalent < 0) {
                                                    $totalent = "0";
                                                }
                                                if ($totalrech < 0) {
                                                    $totalrech = "0";
                                                }
                                                if ($totalcierre < 0) {
                                                    $totalcierre = "0";
                                                }
                                                echo "
                                                <tr>
                                                <td align='right'></td>				  
                                                <td align='center'><strong>Total por Fecha:</strong></td>
                                                <td align='center'><strong>" . $totalsol . "</strong></td>
                                                <td align='center'><strong>" . $totalent . "</strong></td>
                                                <td align='center'><strong>" . $totalrech . "</strong></td>
                                                <td align='center'><strong>" . $totalcierre . "</strong></td>";

                                                if ($totaldif == 0) {
                                                    echo "<td align='center'><button type='button' class='btn btn-danger' data-toggle='tooltip' data-placement='left' title = 'No hay etiquetas por imprimir'><strong>" . $totaldif . "</strong></button></td>";
                                                } else {
                                                    echo "<td align='center'><button type='button' class='btn btn-success' data-toggle='tooltip' data-placement='left' title = 'Hay etiquetas por imprimir'><strong>" . $totaldif . "</strong></button></td>";
                                                }

                                                //Contar los subtotales
                                                $TOTALSOL += $totalsol;
                                                $TOTALENT += $totalent;
                                                $TOTALRECH += $totalrech;
                                                $TOTALCIERRE += $totalcierre;
                                                $TOTALDIF += $totaldif;

//						 if($TOTALDIF != 0){
//						        echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/print.png" heigth="30" value="" data-toggle="tooltip" data-placement="left" title = "Imprimir etiquetas de esta fecha" width="20" onclick="print1(\''.$_SESSION['dato'].'\',\''.$row['fecha'].'\')"/></td>';
//						        echo "</tr>";
//						}else{				
//							echo '<td align="center"><input disabled="true" type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../images/print.png" heigth="30" value="" width="20" data-toggle="tooltip" data-placement="left" title = "No hay etiquetas para imprimir de este pedido o el DAE está caducado"/></td>';
//						        echo "</tr>";
//					        }
                                                //Resetear los subtotales
                                                $totalsol = 0;
                                                $totalent = 0;
                                                $totalrech = 0;
                                                $totalcierre = 0;
                                                $totaldif = 0;
                                            }
                                        }

                                        if ($cant > 0) {
                                            echo "
                                            <tr>
                                            <td align='right'></td>				  
                                            <td align='center'><strong>Total General:</strong></td>
                                            <td align='center'><strong>" . $TOTALSOL . "</strong></td>
                                            <td align='center'><strong>" . $TOTALENT . "</strong></td>
                                            <td align='center'><strong>" . $TOTALRECH . "</strong></td>
                                            <td align='center'><strong>" . $TOTALCIERRE . "</strong></td>";

                                            if ($TOTALDIF == 0) {
                                                echo "<td align='center'><button type='button' class='btn btn-danger btn-lg' data-toggle='tooltip' data-placement='left' title = 'No hay etiquetas por imprimir'><strong>" . $TOTALDIF . "</strong></button></td>";
                                            } else {
                                                echo "<td align='center'><button type='button' class='btn btn-success btn-lg' data-toggle='tooltip' data-placement='left' title = 'Hay etiquetas por imprimir'><strong>" . $TOTALDIF . "</strong></button></td>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='12'><strong>No hay resultados que mostrar.</strong></td></tr>";
                                        }
                                    }
                                }
                                ?>
                            </table>
                        </div> <!-- table responsive-->
                    </form>
                </div> <!-- /panel body --> 

                <div class="panel-heading">
                    <div class="contenedor" align="center">
                        <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2015 versión 3</strong>
                        <br>
                        <strong><a href="http://www.bit-store.ec/index.php/contactenos/"  style="color:white">Info</a> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></strong>
                    </div>
                </div> 
                <span id="top-link-block" class="hidden">
                    <a href="#top" class="well well-sm"  onclick="$('html,body').animate({scrollTop: 0}, 'slow');
                            return false;">
                        <i class="glyphicon glyphicon-chevron-up"></i> Ir arriba
                    </a>
                </span><!-- /top-link-block --> 
            </div> <!-- /container -->
    </body>
</html>