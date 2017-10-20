<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");
require_once('barcode.inc.php');

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
        <title>Cajas Traqueadas</title>
        <link rel="icon" type="image/png" href="../images/favicon.ico" />
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" media="all"  />

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
                    <form method="post" id="frm1" name="frm1">
                        <div class="table-responsive">
                            <table width="50%" border="0" align="center" class="table table-striped" >
                                <tr>
                                    <td  colspan="5" align="center"><h3><strong>CAJAS TRAQUEADAS EN CUARTO FRIO</strong></h3></td>
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
                                            <input type="submit" name="buscar" class = "btn btn-primary"id="buscar" value="Buscar" />
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
                                            echo '<td align="center"><h3><strong>Cajas traqueadas desde el </strong><font color="#FF0000">';
                                            echo $_POST['txtinicio'] . '</font> hasta el  <font color="#FF0000">' . $_POST['txtfin'];
                                            echo '</font> organizadas por item<h3></td>';
                                        } elseif ($_GET['id'] == 2) {

                                            echo '<td align="center"><h3><strong>Cajas traqueadas desde el </strong><font color="#FF0000">';
                                            echo $_POST['txtinicio'] . '</font> hasta el <font color="#FF0000">' . $_POST['txtfin'];
                                            echo '</font> organizadas por fincas<h3></td>';
                                        } elseif ($_GET['id'] == 3) {

                                            echo '<td align="center" colspan="4"><h3><strong>Cajas traqueadas desde el  </strong><font color="#FF0000">';
                                            echo $_POST['txtinicio'] . '</font> hasta el  <font color="#FF0000">' . $_POST['txtfin'];
                                            echo '</font> organizadas por código<h3></td>';
                                        } elseif ($_GET['id'] == 4) {
                                            echo '<td align="center" colspan="4"><h3><strong>Total de Cajas traqueadas desde el </strong><font color="#FF0000">';
                                            echo $_POST['txtinicio'] . '</font> hasta el <font color="#FF0000">' . $_POST['txtfin'];
                                            echo '</font> organizadas por código<h3></td>';
                                        }
                                    }
                                    ?>
                                </tr>
                                <?php
                                if ($_POST['buscar']) {
                                    //Si se oprimio el boton buscar
                                    $fecha1 = $_POST['txtinicio'];
                                    $fecha2 = $_POST['txtfin'];
                                    $id = $_POST['id'];

                                    if ($id == 1) {
                                        $totalitem;
                                        $total;
                                        $sql = "SELECT distinct tblcoldroom.item FROM tblcoldroom  INNER JOIN tbletiquetasxfinca ON tblcoldroom.codigo = tbletiquetasxfinca.codigo WHERE  salida ='Si' AND tblcoldroom.tracking_asig !='' AND tbletiquetasxfinca.archivada='No' AND tbletiquetasxfinca.estado='1' order by item";
                                        $query = mysqli_query($link, $sql) or die('Error en la conexión');
                                        if (!$query) {
                                            echo '<td><strong>No hay cajas traqueadas en cuarto frío, hasta la fecha</strong></td>';
                                        } else {
                                            $cant = mysqli_num_rows($query);
                                            if ($cant == 0) {
                                                echo '<td><strong>No hay cajas traqueadas a mostrar</strong></td>';
                                            } else {
                                                while ($row = mysqli_fetch_array($query)) {
                                                    //Si hay mas de un item entonces se construye el reporte por item\
                                                    echo '<tr>
					<td><strong>Producto: <font color="#0000FF">' . $row["item"] . '</font></strong></td>
				  </tr>';

                                                    //Consulto la bd con el item y poder sumar los  
                                                    $sql1 = "SELECT distinct tblcoldroom.finca FROM tblcoldroom INNER JOIN tbletiquetasxfinca ON tblcoldroom.codigo = tbletiquetasxfinca.codigo where tblcoldroom.item = '" . $row["item"] . "' AND tblcoldroom.fecha BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "' AND salida= 'Si' AND tblcoldroom.tracking_asig !='' AND tbletiquetasxfinca.archivada='No' AND tbletiquetasxfinca.estado='1' order by finca";
                                                    $query1 = mysqli_query($link, $sql1);
                                                    if (!$query1) {
                                                        echo '<td><strong>No hay cajas traqueadas en cuarto frío, para el item ' . $row["item"] . '</strong></td>';
                                                    } else {
                                                        //Si hay entreadas para ese item en el cuarto frio los imprimo
                                                        echo '<tr>
						<td align="center"><strong>Finca</strong></td>
						<td align="center"><strong>Cantidad</strong></td></strong>
					  </tr>';

                                                        while ($row1 = mysqli_fetch_array($query1)) {
                                                            echo "<tr>";
                                                            echo "<td align=\"center\">" . $row1['finca'] . "</td>";
                                                            $sentencia = "SELECT * FROM tblcoldroom INNER JOIN tbletiquetasxfinca ON tblcoldroom.codigo = tbletiquetasxfinca.codigo where tblcoldroom.item = '" . $row["item"] . "' AND tblcoldroom.fecha BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "' AND tblcoldroom.finca='" . $row1['finca'] . "' AND  salida ='Si' AND tblcoldroom.tracking_asig !='' AND tbletiquetasxfinca.archivada='No' AND tbletiquetasxfinca.estado='1' ";
                                                            $consulta = mysqli_query($link, $sentencia);
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

                                        //Preparando los datos para el reporte
                                        $_SESSION["titulo"] = "Cajas traqueadas desde " . $fecha1 . " hasta " . $fecha2 . "por código";
                                        $_SESSION["columnas"] = array("Finca", "Cantidad");
                                        $_SESSION["filtro"] = "Producto: ";

                                        $_SESSION["consulta1"] = $sql;

                                        $_SESSION["consulta2"] = $sql1;

                                        $_SESSION["nombre_fichero"] = "Cajas Traqueadas(por item).xlsx";
                                    } else {
                                        if ($id == 2) {
                                            $totalitem;
                                            $total;
                                            $finca = "";
                                            $i = 1;
                                            $sql = "SELECT tblcoldroom.finca,tblcoldroom.item,COUNT(tblcoldroom.item) as cant FROM tblcoldroom  
                    INNER JOIN tbletiquetasxfinca ON tblcoldroom.codigo = tbletiquetasxfinca.codigo 
                    WHERE 
                    salida ='Si' 
                    AND tblcoldroom.tracking_asig !='' 
                    AND tbletiquetasxfinca.archivada='No' 
                    AND tbletiquetasxfinca.estado='1'
                    AND tblcoldroom.fecha BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "'
                    GROUP BY tblcoldroom.item,tblcoldroom.finca
                    order by finca";

                                            $query = mysqli_query($link, $sql) or die('Error en la conexión');
                                            $cantfilas = mysqli_num_rows($query);

                                            if (!$query) {
                                                echo '<td><strong>No hay cajas traqueadas en cuarto frío, hasta la fecha</strong></td>';
                                            } else {
                                                while ($row = mysqli_fetch_array($query)) {

                                                    if (($row['finca'] != $finca && $finca != "")) {
                                                        echo "<tr>";
                                                        echo "<td align='right'><strong>Total por Finca:</strong></td>";
                                                        echo "<td align='center'><strong>" . $totalitem . "</strong></td>";
                                                        echo "</tr>";
                                                        $total += $totalitem;
                                                        $totalitem = 0;
                                                    }
                                                    if ($row['finca'] != $finca) {
                                                        echo '<tr>
                            <td><strong>Finca: <font color="#0000FF">' . $row["finca"] . '</font></strong></td>
                      </tr>';
                                                        echo '<tr>
                            <td align="center"><strong>Producto</strong></td>
                            <td align="center"><strong>Cantidad</strong></td></strong>
                      </tr>';
                                                    }

                                                    echo "<tr>";
                                                    echo "<td align=\"center\">" . $row['item'] . "</td>";
                                                    echo "<td align='center'>" . $row['cant'] . "</td>";
                                                    $totalitem += $row['cant'];
                                                    echo "<tr>";
                                                    if ($i == $cantfilas) {
                                                        echo "<tr>";
                                                        echo "<td align='right'><strong>Total por Finca:</strong></td>";
                                                        echo "<td align='center'><strong>" . $totalitem . "</strong></td>";
                                                        echo "</tr>";
                                                        $total += $totalitem;
                                                        $totalitem = 0;
                                                    }

                                                    $finca = $row['finca'];
                                                    $i++;
                                                }
                                            }//fin else			  

                                            echo "<tr>";
                                            echo "<td align='right'><strong>Total General:</strong></td>";
                                            echo "<td align='center'><strong>" . $total . "</strong></td>";
                                            echo "</tr>";

                                        } else {
                                            if ($id == 3) {
                                                echo '
				<tr>
					<td align="center"><strong>Código</strong></td>
					<td align="center"><strong>Producto</strong></td>
					<td align="center"><strong>Finca</strong></td>
					<td align="center"><strong>Tracking</strong></td>
					<td align="center"><strong>Fecha</strong></td>
				 </tr>';

                                                $hoy = date('Y-m-d');
                                                //Leer todas las fincas existentes para modificarlas o crear nuevas
                                                $sql = "SELECT * FROM tblcoldroom INNER JOIN tbletiquetasxfinca ON tblcoldroom.codigo = tbletiquetasxfinca.codigo where tblcoldroom.fecha BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "' AND  salida ='Si' AND tblcoldroom.tracking_asig !='' AND tbletiquetasxfinca.archivada='No' AND tbletiquetasxfinca.estado='1'";
                                                //echo $sql;
                                                $val = mysqli_query($link, $sql);
                                                if (!$val) {
                                                    echo "<tr><td>" . mysqli_error() . "</td></tr>";
                                                } else {
                                                    while ($row = mysqli_fetch_array($val)) {
                                                        $cant++;
                                                        echo "<tr>";
                                                        echo "<td align='center'>" . $row['codigo'] . "</td>";
                                                        echo "<td align='center'><strong>" . $row['item'] . "<strong></td>";
                                                        echo "<td><strong>" . $row['finca'] . "</strong></td>";
                                                        echo "<td><strong>" . $row['tracking_asig'] . "</strong></td>";
                                                        echo "<td align='center'>" . $row['fecha'] . "</td>";
                                                    }
                                                    echo "<tr>";
                                                    echo "<td align='right'><strong>Total:</strong></td>";
                                                    echo "<td align='center'><strong>" . $cant . "</strong></td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo '
				<tr>
					<td align="center" width="20"><strong>Código</strong></td>
					<td align="center" width="20"><strong>Producto</strong></td>
					<td align="center" width="80"><strong>Finca</strong></td>
					<td align="center" width="10"><strong>Tracking</strong></td>
					<td align="center" width="30"><strong>Fecha</strong></td>
				 </tr>';
                                                //Leer todas las fincas existentes para modificarlas o crear nuevas
                                                $sql = "SELECT * FROM tblcoldroom where tracking_asig !='' AND fecha BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "'";
                                                //echo $sql;
                                                $val = mysqli_query($link, $sql);
                                                if (!$val) {
                                                    echo "<tr><td>" . mysqli_error() . "</td></tr>";
                                                } else {
                                                    while ($row = mysqli_fetch_array($val)) {
                                                        $cant++;
                                                        echo "<tr>";
                                                        echo "<td align='center'>" . $row['codigo'] . "</td>";
                                                        echo "<td align='center'><strong>" . $row['item'] . "<strong></td>";
                                                        echo "<td><strong>" . $row['finca'] . "</strong></td>";
                                                        echo "<td align='center'><strong>" . $row['tracking_asig'] . "</strong></td>";
                                                        echo "<td align='center'>" . $row['fecha'] . "</td>";
                                                    }
                                                    echo "<tr>";
                                                    echo "<td align='right'><strong>Total:</strong></td>";
                                                    echo "<td align='center'><strong>" . $cant . "</strong></td>";
                                                    echo "</tr>";
                                                }

                                                //Preparando los datos para el reporte
                                                $_SESSION["titulo"] = "Total de Cajas traqueadas desde el día " . $fecha1 . " hasta el día " . $fecha2 . " organizadas por código";
                                                $_SESSION["columnas"] = array("Código", "Producto", "Finca", "Tracking", "Fecha");

                                                $_SESSION["consulta"] = "SELECT codigo,item,finca,tracking_asig,fecha FROM tblcoldroom where tracking_asig !='' AND fecha BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "'";

                                                $_SESSION["nombre_fichero"] = "Cajas Traqueadas.xlsx";
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