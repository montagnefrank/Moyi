﻿<?php
session_start();
include ("conectarSQL.php");
include ("conexion.php");
require_once('barcode.inc.php');
include ("seguridad.php");

$user = $_SESSION["login"];
$passwd = $_SESSION["passwd"];
$bd = $_SESSION["bd"];
$rol = $_SESSION["rol"];

$conection = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : ' . mysqli_error());
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Cajas recibidas</title>

        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" media="all"  />
        <link rel="stylesheet" href="../bootstrap/css/font-awesome.min.css">

        <script language="javascript" src="../js/imprimir.js"></script>
        <script type="text/javascript" src="../js/script.js"></script>
        <script src="../bootstrap/js/jquery.min.js"></script>
        <script src="../bootstrap/js/moment.js"></script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <script src="../bootstrap/js/bootstrap-datetimepicker.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
        <script src="../bootstrap/js/bootstrap-submenu.js"></script>
        <script src="../bootstrap/js/bootstrap-modal.js"></script>
        <script src="../bootstrap/js/docs.js" defer></script>
        <script src="../plugins/exportable/src/jquery.table2excel.js"></script>

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
<!--        <script>
            $("#exportable").click(function () {
                $("#exportarTabla").table2excel({
                    // exclude CSS class
                    exclude: ".noExl",
                    name: "Reporte",
                    filename: "Reporte" //do not include extension
                });
            });
        </script>-->
        <script>
            //EXPORTAMOS LA TABLA A EXCEL
            function fnExcelReport()
            {
                var tab_text = "<table border='2px'><tr >";
                var textRange;
                var j = 0;
                tab = document.getElementById('exportarTabla'); // ID DE LA TABLA

                for (j = 0; j < tab.rows.length; j++)
                {
                    tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
                    //tab_text=tab_text+"</tr>";
                }

                tab_text = tab_text + "</table>";
                tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//REMOVER SI DESEA EXPORTAR LINKS
                tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // REMOVER SI DESEA EXPORTAR IMAGENES
                tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // REMOVER PARAMETROS DE LOS INPUT

                var ua = window.navigator.userAgent;
                var msie = ua.indexOf("MSIE ");

                if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // SI ES INTERNET EXPLORER
                {
                    txtArea1.document.open("txt/html", "replace");
                    txtArea1.document.write(tab_text);
                    txtArea1.document.close();
                    txtArea1.focus();
                    sa = txtArea1.document.execCommand("SaveAs", true, "Reporte.xls");
                } else          // SI ES OTRO NAVEGADOR
                    sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));

                return (sa);
            }
        </script>
        <script>
//            //SCRIPT PARA EXPORTAR TABLA A CSV, FUNCIONA SOLO EN CHROME
//            $(document).ready(function () {
//                $("#btnExport").click(function (e) {
//                    //getting values of current time for generating the file name
//                    var dt = new Date();
//                    var day = dt.getDate();
//                    var month = dt.getMonth() + 1;
//                    var year = dt.getFullYear();
//                    var hour = dt.getHours();
//                    var mins = dt.getMinutes();
//                    var postfix = day + "." + month + "." + year + "_" + hour + "." + mins;
//                    //creating a temporary HTML link element (they support setting file names)
//                    var a = document.createElement('a');
//                    //getting data from our div that contains the HTML table
//                    var data_type = 'data:application/vnd.ms-excel';
//                    var table_div = document.getElementById('exportarTabla');
//                    var table_html = table_div.outerHTML.replace(/ /g, '%20');
//                    a.href = data_type + ', ' + table_html;
//                    //setting the file name
//                    a.download = 'exported_table_' + postfix + '.xls';
//                    //triggering the function
//                    a.click();
//                    //just in case, prevent default behaviour
//                    e.preventDefault();
//                });
//            });
        </script>
        <script type="text/javascript" src="../js/tableexport/tableExport.js"></script>
        <script type="text/javascript" src="../js/tableexport/jquery.base64.js"></script>
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
                                    <li>
                                        <a tabindex="0" data-toggle="dropdown">
                                            <strong>Etiquetas</strong><span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="etiqxfincas.php">Imprimir etiquetas por fincas</a></li>
                                            <li class="divider"></li>
                                            <li><a href="etiquetasexistentes.php">Etiquetas existentes</a></li>
                                        </ul>
                                    </li>


                                    <li  class="active">
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
                                        $query = mysqli_query($sql);
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

                    <form method="post" id="frm1" name="frm1">
                        <div class="table-responsive" style=" text-align: center;">
                            <table width="50%" border="0" align="center" class="table table-striped" >
                                <tr>
                                    <td  colspan="5" align="center"><span id="result_box" lang="en" xml:lang="en"><h3><strong>CAJAS RECIBIDAS EN CUARTO FRIO</strong></h3></span></td>
                                </tr>

                                <tr>
                                    <td colspan="5" align="center">  
                                        <div class="col-md-2"></div>
                                        <div class="col-md-3">
                                            <label>Fecha Inicio:</label>
                                            <div class="input-group date" style="width: auto;" id="datetimepicker">
                                                <input type='text' class="form-control" name="txtinicio" id="txtinicio" value="<?php echo date('Y-m-d') ?>"/>
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

                                        <div class="col-md-3">
                                            <label>Fecha Inicio:</label>
                                            <div class="input-group date" style="width: auto;" id="datetimepicker1">
                                                <input type='text' class="form-control" name="txtfin" id="txtfin" value="<?php echo date('Y-m-d') ?>"/>
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

                                        <div class="col-md-2">
                                            </br>
                                            <input type="submit" name="buscar" class = "btn btn-primary"id="buscar" value="Buscar" onclick="return Compara(this.form)"/>
                                            <input type="hidden" name = "id" value="<?php echo $_GET['id']; ?>"/>
                                        </div>      
                                    </td>
                                </tr>
                            </table> 
                        </div>         


                        <div class="table-responsive">
                            <table width="50%" border="0" align="center" class="table table-striped" > 
                                <tr>
                                    <?php
                                    if ($_POST['buscar']) {
                                        echo '<div align="right">' .
                                        '<iframe id="txtArea1" style="display:none"></iframe>' .
                                        '<button id="btnExport" class = "btn btn-primary" onclick="fnExcelReport();"><img src="../images/excel.gif" width="30" height="30" /> Exportar a Excel </button>' .
                                        '</div>';
                                    }
                                    ?>
                                </tr>
                            </table>
                        </div>
                        <div class="table-responsive"> 
                            <table id="exportarTabla" width="50%" border="0" align="center" class="table table-striped" > 
                                <tr>
                                    <?php
                                    if ($_POST['buscar']) {
                                        if ($_GET['id'] == 1) {
                                            echo '<td align="center" colspan="3"><h3><strong>Entradas desde el </strong><font color="#FF0000">'
                                            . $_POST['txtinicio'] . '</font> hasta el <font color="#FF0000">' . $_POST['txtfin'] .
                                            '</font> organizadas por item</h3></td>';
                                        } elseif ($_GET['id'] == 2) {
                                            echo '<td align="center" colspan="3"><h3><strong>Entradas desde el </strong><font color="#FF0000">'
                                            . $_POST['txtinicio'] . '</font> hasta el <font color="#FF0000">' . $_POST['txtfin'] .
                                            '</font> organizadas por fincas</h3></td>';
                                        } elseif ($_GET['id'] == 3) {
                                            echo '<td align="center" colspan="3"><h3><strong>Entradas desde el </strong><font color="#FF0000">'
                                            . $_POST['txtinicio'] . '</font> hasta el <font color="#FF0000">' . $_POST['txtfin'] .
                                            '</font> organizadas por codigo</h3></td>';
                                        } elseif ($_GET['id'] == 4) {
                                            echo '<td align="center" colspan="3"><h3><strong>Entradas desde el </strong><font color="#FF0000">'
                                            . $_POST['txtinicio'] . '</font> hasta el <font color="#FF0000">' . $_POST['txtfin'] .
                                            '</font> organizadas por codigo</h3></td>';
                                        }
                                    }
                                    ?>
                                </tr>
                                <?php
                                if ($_POST['buscar']) {
                                    $fecha1 = $_POST['txtinicio'];
                                    $fecha2 = $_POST['txtfin'];
                                    $id = $_POST['id'];
                                    if ($id == 1) {
                                        $totalitem;
                                        $total;
                                        $sql = "SELECT distinct tblcoldroom.item FROM tblcoldroom INNER JOIN tbletiquetasxfinca ON tblcoldroom.codigo = tbletiquetasxfinca.codigo where entrada= 'Si' AND salida ='No' AND tbletiquetasxfinca.archivada='No' AND tbletiquetasxfinca.estado='1' AND tblcoldroom.fecha BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "' order by item";

                                        $query = mysqli_query($sql) or die('Error en la conexión');
                                        if (!$query) {
                                            echo '<tr><td><strong>No hay entradas al cuarto frío, hasta la fecha</strong></td></tr>';
                                        } else {
                                            $cant = mysqli_num_rows($query);
                                            if ($cant == 0) {
                                                echo '<tr><td><strong>No hay entradas a mostrar</strong></td></tr>';
                                            } else {
                                                while ($row = mysqli_fetch_array($query)) {
                                                    //Si hay mas de un item entonces se construye el reporte por item\
                                                    echo '<tr>
                                                            <td><strong>Producto: <font color="#0000FF">' . $row["item"] . '</font></strong></td>
                                                            </tr>';

                                                    //Consulto la bd con el item y poder sumar los  
                                                    $sql1 = "SELECT distinct tblcoldroom.finca FROM tblcoldroom INNER JOIN tbletiquetasxfinca ON tblcoldroom.codigo = tbletiquetasxfinca.codigo where tblcoldroom.item = '" . $row["item"] . "' AND tblcoldroom.fecha BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "' AND entrada= 'Si' AND salida ='No' AND tbletiquetasxfinca.archivada='No' AND tbletiquetasxfinca.estado='1' order by finca";
                                                    $query1 = mysqli_query($sql1);
                                                    if (!$query1) {
                                                        echo '<td><strong>No hay entradas al cuarto frío, para el item ' . $row["item"] . '</strong></td>';
                                                    } else {
                                                        //Si hay entreadas para ese item en el cuarto frio los imprimo
                                                        echo '<tr bgcolor="#E8F1FD">
                                                                <td align="center"><strong>Finca</strong></td>
                                                                <td align="center"><strong>Cantidad</strong></td></strong>
                                                                </tr>';

                                                        while ($row1 = mysqli_fetch_array($query1)) {
                                                            echo "<tr>";
                                                            echo "<td align=\"center\">" . $row1['finca'] . "</td>";
                                                            $sentencia = "SELECT * FROM tblcoldroom INNER JOIN tbletiquetasxfinca ON tblcoldroom.codigo = tbletiquetasxfinca.codigo where tblcoldroom.item = '" . $row["item"] . "' AND tblcoldroom.fecha BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "' AND entrada= 'Si' AND salida ='No' AND tblcoldroom.finca='" . $row1['finca'] . "' AND tbletiquetasxfinca.archivada='No' AND tbletiquetasxfinca.estado='1'";
                                                            $consulta = mysqli_query($sentencia);
                                                            $Cantfila = mysqli_num_rows($consulta);
                                                            echo "<td align='center'>" . $Cantfila . "</td>";
                                                            $totalitem += $Cantfila;
                                                            echo "<tr>";
                                                        }//fin while	
                                                        echo "<tr>";
                                                        echo "<td align='right'><strong>Total por Producto:</strong></td>";
                                                        echo "<td align='center'><strong>" . $totalitem . "</strong></td>";
                                                        echo "</tr>";
                                                        $total += $totalitem;
                                                        $totalitem = 0;
                                                    }//fin else			  
                                                }//fin while
                                            }//fin else 
                                            echo "<tr>";
                                            echo "<td align='right'><strong>Total General:</strong></td>";
                                            echo "<td align='center'><strong>" . $total . "</strong></td>";
                                            echo "</tr>";
                                        } //fin else 
                                        mysqli_close($conection);

                                        //Preparando los datos para el reporte
                                        $_SESSION["titulo"] = "Cajas recibidas desde " . $fecha1 . " hasta " . $fecha2 . "por código";
                                        $_SESSION["columnas"] = array("Finca", "Cantidad");
                                        $_SESSION["filtro"] = "Producto: ";
                                        $_SESSION["consulta1"] = $sql;
                                        $_SESSION["consulta2"] = $sql1;
                                        $_SESSION["nombre_fichero"] = "Cajas Recibidas(por item).xlsx";
                                        $_SESSION["id"] = $id;
                                    } else {
                                        if ($id == 2) {
                                            $totalitem;
                                            $total;
                                            $sql = "SELECT distinct tblcoldroom.finca FROM tblcoldroom INNER JOIN tbletiquetasxfinca ON tblcoldroom.codigo = tbletiquetasxfinca.codigo where entrada= 'Si' AND salida ='No' AND tbletiquetasxfinca.archivada='No' AND tbletiquetasxfinca.estado='1' order by finca";
//                                            echo "Consulta:" . $sql . "</br>";
                                            $query = mysqli_query($sql) or die('Error en la conexión');
                                            if (!$query) {
                                                echo '<td><strong>No hay entradas al cuarto frío, hasta la fecha</strong></td>';
                                            } else {
                                                $cant = mysqli_num_rows($query);
                                                if ($cant == 0) {
                                                    echo '<td><strong>No hay entradas a mostrar</strong></td>';
                                                } else {
                                                    while ($row = mysqli_fetch_array($query)) {
                                                        //Si hay mas de un item entonces se construye el reporte por item\
                                                        echo '<tr>
						<td><strong>Finca: <font color="#0000FF">' . $row["finca"] . '</font></strong></td>
					  </tr>';

                                                        //Consulto la bd con el item y poder sumar los  
                                                        $sql1 = "SELECT distinct tblcoldroom.item FROM tblcoldroom INNER JOIN tbletiquetasxfinca ON tblcoldroom.codigo = tbletiquetasxfinca.codigo where tblcoldroom.finca = '" . $row["finca"] . "' AND tblcoldroom.fecha BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "' AND entrada= 'Si' AND salida ='No' AND tbletiquetasxfinca.archivada='No' AND tbletiquetasxfinca.estado='1' order by item";
//                                                        echo "Consulta1:" . $sql1 . "</br>";
                                                        $query1 = mysqli_query($sql1);
                                                        if (!$query1) {
                                                            echo '<td><strong>No hay entradas al cuarto frío, para la finca ' . $row["finca"] . '</strong></td>';
                                                        } else {
                                                            //Si hay entreadas para ese item en el cuarto frio los imprimo
                                                            echo '<tr bgcolor="#E8F1FD">
							<td align="center"><strong>Producto</strong></td>
							<td align="center"><strong>Cantidad</strong></td></strong>
						  </tr>';

                                                            while ($row1 = mysqli_fetch_array($query1)) {
                                                                echo "<tr>";
                                                                echo "<td align=\"center\">" . $row1['item'] . "</td>";
                                                                $sentencia = "SELECT * FROM tblcoldroom INNER JOIN tbletiquetasxfinca ON tblcoldroom.codigo = tbletiquetasxfinca.codigo where tblcoldroom.finca = '" . $row["finca"] . "' AND tblcoldroom.fecha BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "' AND entrada= 'Si' AND salida ='No' AND tblcoldroom.item='" . $row1['item'] . "' AND tbletiquetasxfinca.archivada='No' AND tbletiquetasxfinca.estado='1'";
//                                                                echo "Consulta2:" . $sentencia . "</br>";
                                                                $consulta = mysqli_query($sentencia);
                                                                $Cantfila = mysqli_num_rows($consulta);
                                                                echo "<td align='center'>" . $Cantfila . "</td>";
                                                                $totalitem += $Cantfila;
                                                                echo "<tr>";
                                                            }//fin while	
                                                            echo "<tr>";
                                                            echo "<td align='right'><strong>Total por Finca:</strong></td>";
                                                            echo "<td align='center'><strong>" . $totalitem . "</strong></td>";
                                                            echo "</tr>";
                                                            $total += $totalitem;
                                                            $totalitem = 0;
                                                        }//fin else			  
                                                    }//fin while
                                                }//fin else 
                                                echo "<tr>";
                                                echo "<td align='right'><strong>Total General:</strong></td>";
                                                echo "<td align='center'><strong>" . $total . "</strong></td>";
                                                echo "</tr>";
                                            } //fin else 
                                            mysqli_close($conection);
                                        } else {
                                            if ($id == 3) {
                                                echo '
				<tr bgcolor="#E8F1FD">
					<td align="center"><strong>Código</strong></td>
					<td align="center"><strong>Producto</strong></td>
					<td align="center"><strong>Finca</strong></td>
					<td align="center"><strong>Fecha</strong></td>
				 </tr>';


                                                //Leer todas las fincas existentes para modificarlas o crear nuevas
                                                $sql = "SELECT * FROM tblcoldroom INNER JOIN tbletiquetasxfinca ON tblcoldroom.codigo = tbletiquetasxfinca.codigo where tblcoldroom.fecha BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "' AND entrada= 'Si' AND salida ='No' AND tbletiquetasxfinca.archivada='No' AND tbletiquetasxfinca.estado='1'";
                                                //echo $sql;
                                                $val = mysqli_query($sql);
                                                if (!$val) {
                                                    echo "<tr><td>" . mysqli_error() . "</td></tr>";
                                                } else {
                                                    while ($row = mysqli_fetch_array($val)) {
                                                        $cant++;
                                                        echo "<tr>";
                                                        echo "<td align='center'>" . $row['codigo'] . "</td>";
                                                        echo "<td align='center'><strong>" . $row['item'] . "<strong></td>";
                                                        echo "<td><strong>" . $row['finca'] . "</strong></td>";
                                                        echo "<td align='center'>" . $row['fecha'] . "</td>";
                                                    }
                                                    echo "<tr>";
                                                    echo "<td align='right'><strong>Total:</strong></td>";
                                                    echo "<td align='center'><strong>" . $cant . "</strong></td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo '
				<tr bgcolor="#E8F1FD">
					<td align="center"><strong>Código</strong></td>
					<td align="center"><strong>Producto</strong></td>
					<td align="center"><strong>Finca</strong></td>
					<td align="center"><strong>Fecha</strong></td>
				 </tr>';

                                                //Leer todas las fincas existentes para modificarlas o crear nuevas
                                                $sql = "SELECT * FROM tblcoldroom where entrada='Si' AND fecha BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "'";
                                                //echo $sql;
                                                $val = mysqli_query($sql);
                                                if (!$val) {
                                                    echo "<tr><td>" . mysqli_error() . "</td></tr>";
                                                } else {
                                                    while ($row = mysqli_fetch_array($val)) {
                                                        $cant++;
                                                        echo "<tr>";
                                                        echo "<td align='center'>" . $row['codigo'] . "</td>";
                                                        echo "<td align='center'><strong>" . $row['item'] . "<strong></td>";
                                                        echo "<td><strong>" . $row['finca'] . "</strong></td>";
                                                        echo "<td align='center'>" . $row['fecha'] . "</td>";
                                                    }
                                                    echo "<tr>";
                                                    echo "<td align='right'><strong>Total:</strong></td>";
                                                    echo "<td align='center'><strong>" . $cant . "</strong></td>";
                                                    echo "</tr>";
                                                }
                                                mysqli_close($conection);

                                                //Preparando los datos para el reporte
                                                $_SESSION["titulo"] = "Entradas desde el día " . $fecha1 . " hasta el día " . $fecha2 . " organizadas por código";
                                                $_SESSION["columnas"] = array("Código", "Producto", "Finca", "Fecha");

                                                $_SESSION["consulta"] = "SELECT codigo,item,finca,fecha FROM tblcoldroom where entrada='Si' AND fecha BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "'";

                                                $_SESSION["nombre_fichero"] = "Cajas Recibidas.xlsx";
                                            }
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