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
$id = $_GET['id'];
$cajas[0] = 0;

function array_envia($array) {
    $tmp = serialize($array);
    $tmp = urlencode($tmp);
    return $tmp;
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Guias Asignadas</title>

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

            function marcar() {
                for (var i = 0; i < document.form2.elements.length; i++) {
                    if (document.form2.elements[i].type == 'checkbox') {
                        if (document.form2.elements[i].disabled == false) {
                            document.form2.elements[i].checked = !(document.form2.elements[i].checked);
                        }
                    }
                }
            }
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
                    <?php
                    if ($error == 2) {

                        echo '<div class="alert alert-danger" role="alert">
				  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				  <span class="sr-only">Error:</span>
				  <strong>¡Error! </strong>Ingrese algún criterio de búsqueda
				</div>';
                    } else {
                        if ($error == 3) {
                            echo '<div class="alert alert-danger" role="alert">
					  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					  <span class="sr-only">Error:</span>
					  <strong>¡Error! </strong>Las fechas no pueden ser vacías. Tiene que tener algún valor para buscar.
					</div>';
                        } else {
                            if ($error == 4) {
                                echo '<div class="alert alert-danger" role="alert">
						  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						  <span class="sr-only">Error:</span>
						  <strong>¡Error! </strong>La fecha de inicio es mayor que la fecha de fin; Introduzca un período válido
						</div>';
                            }
                        }
                    }
                    ?>
                    <form id="form" name="form" method="post">
                        <div class="table-responsive">
                            <table width="50%" border="0" align="center" class="table table-striped" >
                                <tr align="center">
                                    <td><h3><strong>BUSCAR GUIAS ASIGNADAS</strong></h3></td>
                                </tr>

                                <tr> 
                                    <td>
                                        <div class="col-mdd-1">
                                            <label>AWB-GUIA:</label> 
                                        </div>  
                                        <div class="col-md-2">
                                            <input name="madre" type="text" value="" class="form-control"/>
                                        </div>          
                                        <div class="col-mdd-1">
                                            <label>HAWB-GUIA:</label> 
                                        </div>           
                                        <div class="col-md-2">
                                            <input name="hija" type="text"value="" class="form-control"/>
                                        </div>           
                                        <div class="col-mdd-1">
                                            <label>Finca:</label> 
                                        </div> 	       
                                        <div class="col-md-2">
                                            <select type="text" name="finca" id="finca" class="form-control">
                                                <?php
                                                //Consulto la bd para obtener solo los id de item existentes
                                                $sql = "SELECT nombre FROM tblfinca";
                                                $query = mysqli_query($link, $sql);
                                                //Recorrer los iteme para mostrar
                                                echo '<option value=""></option>';
                                                while ($row1 = mysqli_fetch_array($query)) {
                                                    echo '<option value="' . $row1["nombre"] . '">' . $row1["nombre"] . '</option>';
                                                }
                                                ?>                       
                                            </select> 
                                        </div>
                                        <div class="col-md-2">
                                            <input name="todo" id="todo" type="checkbox" value="" title="Ver todos"/>
                                            <input name="filtrar" type="submit" class="btn btn-primary" value="Buscar" title="Filtrar búsqueda"/>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table width="50%" border="0" align="center" class="table table-striped" > 
                            <tr>
                                <?php
                                if ($_POST['filtrar']) {
                                    echo '<div class align="right">' .
                                    '<iframe id="txtArea1" style="display:none"></iframe>' .
                                    '<button id="btnExport" class = "btn btn-primary" onclick="fnExcelReport();"><img src="../images/excel.gif" width="30" height="30" /> Exportar a Excel </button>' .
                                    '';

                                    echo ' <form action="crearPdf.php" method="post" target="_blank">    
                                                <button class = "btn btn-primary" ><img src="../images/pdf.png" heigth="40" width="30"/> Exportar a PDF </button>
                                            </form> ';

                                    if ($_POST ['madre'] != '') {
                                        echo ' <form action="facturacomercial.php" method="post" target="_blank">
                                        <button class = "btn btn-primary" ><img src="../images/factura.png" heigth="40" width="30" /> Facturar todo lo marcado</button>
                                        </form> </div>';
                                    }
                                }
                                ?>
                            </tr>
                        </table>
                    </div>
                    <div class="table-responsive"> 
                        <table id="exportarTabla" width="50%" border="0" align="center" class="table table-striped" >
                            <tr>
                                <td colspan="12" align="center">
                                    <h3><strong>Guías Asignadas</strong></h3> 
                                </td>
                            </tr>
                            <tr>
                                <td align="center"><strong>Producto</strong></td>
                                <td align="center"><strong>Prod Desc.</strong></td>
                                <td align="center"><strong>Finca</strong></td> 
                                <td align="center"><strong>Tracking</strong></td>   
                                <td align="center"><strong>Fecha Vuelo</strong></td>
                                <td align="center"><strong>Fecha Entrega</strong></td>   
                                <td align="center"><strong>Servicio</strong></td>
                                <td align="center"><strong>AWB-GUIA</strong></td>
                                <td align="center"><strong>HAWB-GUIA</strong></td>
                                <td><strong><input type="checkbox" value="X" onchange="marcar()" title="Marcar todos"/></strong></td>
                            </tr>
                            <form id="form2" name="form2" method="post">
                                <?php
                                if (isset($_POST['filtrar'])) {
                                    //verificar filtros de busqueda
                                    $madre = $_POST ['madre'];
                                    $hija = $_POST ['hija'];
                                    $finca = $_POST ['finca'];
                                    $_SESSION["todo"] = 0;

                                    if (isset($_REQUEST['todo'])) {

                                        $sql = "(SELECT codigo,item,finca,fecha_vuelo,fecha_entrega,servicio,guia_madre,guia_hija, tracking_asig,guia_master,palet FROM tblcoldroom WHERE salida='Si' AND (guia_madre != 0 OR guia_hija != 0)) UNION (SELECT codigo_unico,item,finca,vuelo,entrega,servicio,guia_m,guia_h, tracking_asig,guia_master,palet FROM tblcoldrom_fincas WHERE guia_m != 0 OR guia_h != 0) ORDER BY finca";
                                        $val = mysqli_query($link, $sql);
                                        if (!$val) {
                                            echo "<tr><td>" . mysqli_error() . "</td></tr>";
                                        } else {
                                            $cant = 0;
                                            $subtotal = 0;
                                            $row = mysqli_fetch_array($val);
                                            if ($row['fecha_vuelo'] != '') {
                                                $cant ++;
                                                $subtotal++;
                                                echo "<tr>";

                                                echo "<td align='center'><strong>" . $row['item'] . "</strong></td>";
                                                //Buscar descripcion del item a mostrar
                                                $a = "SELECT prod_descripcion FROM tblproductos WHERE id_item = '" . $row['item'] . "'";
                                                $b = mysqli_query($link, $a) or die("Error consultando descripcion del item");
                                                $c = mysqli_fetch_array($b);
                                                $d = $c['prod_descripcion'];
                                                echo "<td><strong>" . $d . "</strong></td>";
                                                echo "<td><strong>" . $row['finca'] . "</strong></td>";
                                                echo "<td align='center'>" . $row['tracking_asig'] . "</td>";
                                                echo "<td align='center'>" . $row['fecha_vuelo'] . "</td>";
                                                echo "<td align='center'>" . $row['fecha_entrega'] . "</td>";
                                                echo "<td align='center'>" . $row['servicio'] . "</td>";
                                                echo "<td align='center'>" . $row['guia_madre'] . "</td>";
                                                echo "<td align='center'>" . $row['guia_hija'] . "</td>";
                                                echo '<td align="center"><input name="cajas[]" type="checkbox" value="' . $row['codigo'] . '" title="Seleccionar pedido" checked/></td>';
                                                echo "</tr>";
                                                $dato = $row['finca'];
                                            }

                                            //Segunda fila   
                                            while ($row = mysqli_fetch_array($val)) {

                                                if ($row['fecha_vuelo'] != '') {
                                                    if ($dato != $row['finca']) {
                                                        echo "<tr>
											  <td align='right'><strong>Subtotal:</strong></td>
											  <td align='center'><strong>" . $subtotal . "</strong></td>										  
										  </tr>";
                                                        $subtotal = 0;
                                                    }

                                                    $cant ++;
                                                    $subtotal++;
                                                    echo "<tr>";

                                                    echo "<td align='center'><strong>" . $row['item'] . "</strong></td>";
                                                    //Buscar descripcion del item a mostrar
                                                    $a = "SELECT prod_descripcion FROM tblproductos WHERE id_item = '" . $row['item'] . "'";
                                                    $b = mysqli_query($link, $a) or die("Error consultando descripcion del item");
                                                    $c = mysqli_fetch_array($b);
                                                    $d = $c['prod_descripcion'];
                                                    echo "<td><strong>" . $d . "</strong></td>";
                                                    echo "<td><strong>" . $row['finca'] . "</strong></td>";
                                                    echo "<td align='center'>" . $row['tracking_asig'] . "</td>";
                                                    echo "<td align='center'>" . $row['fecha_vuelo'] . "</td>";
                                                    echo "<td align='center'>" . $row['fecha_entrega'] . "</td>";
                                                    echo "<td align='center'>" . $row['servicio'] . "</td>";
                                                    echo "<td align='center'>" . $row['guia_madre'] . "</td>";
                                                    echo "<td align='center'>" . $row['guia_hija'] . "</td>";
                                                    echo '<td align="center"><input name="cajas[]" type="checkbox" value="' . $row['codigo'] . '" title="Seleccionar pedido" checked/></td>';
                                                    echo "</tr>";
                                                    $dato = $row['finca'];
                                                }
                                            }
                                            echo "<tr>
											  <td align='right'><strong>Subtotal:</strong></td>
											  <td align='center'><strong>" . $subtotal . "</strong></td>										  
										  </tr>";

                                            echo "<tr>
						  		<td><strong>Cajas encontradas</strong></td>
							    <td align='center'><strong>" . $cant . "</strong></td>							  
						  </tr>";
                                        }
                                        $_SESSION["sql"] = $sql;
                                        $_SESSION["todo"] = 1;
                                        $_SESSION["madre"] = $madre;
                                        $_SESSION["hija"] = $hija;
                                        $_SESSION["finca"] = $finca;
                                    } else {
                                        if ($madre != '') {
                                            $sql = "SELECT codigo,item,finca,fecha_vuelo,fecha_entrega,servicio,guia_madre,guia_hija,tracking_asig,guia_master,palet FROM tblcoldroom WHERE guia_madre = '" . $madre . "' AND salida='Si' UNION (SELECT codigo_unico,item,finca,vuelo,entrega,servicio,guia_m,guia_h, tracking_asig,guia_master,palet FROM tblcoldrom_fincas WHERE guia_m = '" . $madre . "') ORDER BY finca";

                                            $val = mysqli_query($link, $sql);
                                            if (!$val) {
                                                echo "<tr><td>" . mysqli_error() . "</td></tr>";
                                            } else {
                                                $cant = 0;
                                                $subtotal = 0;
                                                $row = mysqli_fetch_array($val);
                                                if ($row['fecha_vuelo'] != '') {
                                                    $cant ++;
                                                    $subtotal++;
                                                    echo "<tr>";
                                                    echo "<td align='center'><strong>" . $row['item'] . "</strong></td>";
                                                    //Buscar descripcion del item a mostrar
                                                    $a = "SELECT prod_descripcion FROM tblproductos WHERE id_item = '" . $row['item'] . "'";
                                                    $b = mysqli_query($link, $a) or die("Error consultando descripcion del item");
                                                    $c = mysqli_fetch_array($b);
                                                    $d = $c['prod_descripcion'];
                                                    echo "<td><strong>" . $d . "</strong></td>";
                                                    echo "<td><strong>" . $row['finca'] . "</strong></td>";
                                                    echo "<td align='center'>" . $row['tracking_asig'] . "</td>";
                                                    echo "<td align='center'>" . $row['fecha_vuelo'] . "</td>";
                                                    echo "<td align='center'>" . $row['fecha_entrega'] . "</td>";
                                                    echo "<td align='center'>" . $row['servicio'] . "</td>";
                                                    echo "<td align='center'>" . $row['guia_madre'] . "</td>";
                                                    echo "<td align='center'>" . $row['guia_hija'] . "</td>";
                                                    echo '<td align="center"><input name="cajas[]" type="checkbox" value="' . $row['codigo'] . '" title="Seleccionar pedido" checked/></td>';
                                                    echo "</tr>";
                                                    $dato = $row['finca'];
                                                }

                                                //Segunda fila   
                                                while ($row = mysqli_fetch_array($val)) {

                                                    if ($row['fecha_vuelo'] != '') {
                                                        if ($dato != $row['finca']) {
                                                            echo "<tr>
                                                  <td align='right'><strong>Subtotal:</strong></td>
                                                  <td align='center'><strong>" . $subtotal . "</strong></td>										  
                                          </tr>";
                                                            $subtotal = 0;
                                                        }

                                                        $cant ++;
                                                        $subtotal++;
                                                        echo "<tr>";

                                                        echo "<td align='center'><strong>" . $row['item'] . "</strong></td>";
                                                        //Buscar descripcion del item a mostrar
                                                        $a = "SELECT prod_descripcion FROM tblproductos WHERE id_item = '" . $row['item'] . "'";
                                                        $b = mysqli_query($link, $a) or die("Error consultando descripcion del item");
                                                        $c = mysqli_fetch_array($b);
                                                        $d = $c['prod_descripcion'];
                                                        echo "<td><strong>" . $d . "</strong></td>";
                                                        echo "<td><strong>" . $row['finca'] . "</strong></td>";
                                                        echo "<td align='center'>" . $row['tracking_asig'] . "</td>";
                                                        echo "<td align='center'>" . $row['fecha_vuelo'] . "</td>";
                                                        echo "<td align='center'>" . $row['fecha_entrega'] . "</td>";
                                                        echo "<td align='center'>" . $row['servicio'] . "</td>";
                                                        echo "<td align='center'>" . $row['guia_madre'] . "</td>";
                                                        echo "<td align='center'>" . $row['guia_hija'] . "</td>";
                                                        echo '<td align="center"><input name="cajas[]" type="checkbox" value="' . $row['codigo'] . '" title="Seleccionar pedido" checked/></td>';
                                                        echo "</tr>";
                                                        $dato = $row['finca'];
                                                    }
                                                }
                                                echo "<tr>
                                                                        <td align='right'><strong>Subtotal:</strong></td>
                                                                        <td align='center'><strong>" . $subtotal . "</strong></td>										  
                                                                </tr>";

                                                echo "<tr>
                                              <td><strong>Cajas encontradas</strong></td>
                                          <td align='center'><strong>" . $cant . "</strong></td>							  
                                </tr>";
                                            }
                                            $_SESSION["sql"] = $sql;
                                            $_SESSION["madre"] = $madre;
                                            $_SESSION["hija"] = $hija;
                                            $_SESSION["finca"] = $finca;
                                        } else {

                                            if ($hija != '') {
                                                $sql = "SELECT codigo,item,finca,fecha_vuelo,fecha_entrega,servicio,guia_madre,guia_hija, tracking_asig,guia_master,palet FROM tblcoldroom WHERE guia_hija = '" . $hija . "' AND salida='Si' UNION (SELECT codigo_unico,item,finca,vuelo,entrega,servicio,guia_m,guia_h, tracking_asig,guia_master,palet FROM tblcoldrom_fincas WHERE guia_h = '" . $hija . "') ORDER BY finca";
                                                $val = mysqli_query($link, $sql);
                                                if (!$val) {
                                                    echo "<tr><td>" . mysqli_error() . "</td></tr>";
                                                } else {
                                                    $cant = 0;
                                                    $subtotal = 0;
                                                    $row = mysqli_fetch_array($val);
                                                    if ($row['fecha_vuelo'] != '') {
                                                        $cant ++;
                                                        $subtotal++;
                                                        echo "<tr>";

                                                        echo "<td align='center'><strong>" . $row['item'] . "</strong></td>";
                                                        //Buscar descripcion del item a mostrar
                                                        $a = "SELECT prod_descripcion FROM tblproductos WHERE id_item = '" . $row['item'] . "'";
                                                        $b = mysqli_query($link, $a) or die("Error consultando descripcion del item");
                                                        $c = mysqli_fetch_array($b);
                                                        $d = $c['prod_descripcion'];
                                                        echo "<td><strong>" . $d . "</strong></td>";
                                                        echo "<td><strong>" . $row['finca'] . "</strong></td>";
                                                        echo "<td align='center'>" . $row['tracking_asig'] . "</td>";
                                                        echo "<td align='center'>" . $row['fecha_vuelo'] . "</td>";
                                                        echo "<td align='center'>" . $row['fecha_entrega'] . "</td>";
                                                        echo "<td align='center'>" . $row['servicio'] . "</td>";
                                                        echo "<td align='center'>" . $row['guia_madre'] . "</td>";
                                                        echo "<td align='center'>" . $row['guia_hija'] . "</td>";
                                                        echo '<td align="center"><input name="cajas[]" type="checkbox" value="' . $row['codigo'] . '" title="Seleccionar pedido" checked/></td>';
                                                        echo "</tr>";
                                                        $dato = $row['finca'];
                                                    }

                                                    //Segunda fila   
                                                    while ($row = mysqli_fetch_array($val)) {

                                                        if ($row['fecha_vuelo'] != '') {
                                                            if ($dato != $row['finca']) {
                                                                echo "<tr>
											  <td align='right'><strong>Subtotal:</strong></td>
											  <td align='center'><strong>" . $subtotal . "</strong></td>										  
										  </tr>";
                                                                $subtotal = 0;
                                                            }

                                                            $cant ++;
                                                            $subtotal++;
                                                            echo "<tr>";

                                                            echo "<td align='center'><strong>" . $row['item'] . "</strong></td>";
                                                            //Buscar descripcion del item a mostrar
                                                            $a = "SELECT prod_descripcion FROM tblproductos WHERE id_item = '" . $row['item'] . "'";
                                                            $b = mysqli_query($link, $a) or die("Error consultando descripcion del item");
                                                            $c = mysqli_fetch_array($b);
                                                            $d = $c['prod_descripcion'];
                                                            echo "<td><strong>" . $d . "</strong></td>";
                                                            echo "<td><strong>" . $row['finca'] . "</strong></td>";
                                                            echo "<td align='center'>" . $row['tracking_asig'] . "</td>";
                                                            echo "<td align='center'>" . $row['fecha_vuelo'] . "</td>";
                                                            echo "<td align='center'>" . $row['fecha_entrega'] . "</td>";
                                                            echo "<td align='center'>" . $row['servicio'] . "</td>";
                                                            echo "<td align='center'>" . $row['guia_madre'] . "</td>";
                                                            echo "<td align='center'>" . $row['guia_hija'] . "</td>";
                                                            echo '<td align="center"><input name="cajas[]" type="checkbox" value="' . $row['codigo'] . '" title="Seleccionar pedido" checked/></td>';
                                                            echo "</tr>";
                                                            $dato = $row['finca'];
                                                        }
                                                    }
                                                    echo "<tr>
											  <td align='right'><strong>Subtotal:</strong></td>
											  <td align='center'><strong>" . $subtotal . "</strong></td>										  
										  </tr>";

                                                    echo "<tr>
						  		<td><strong>Cajas encontradas</strong></td>
							    <td align='center'><strong>" . $cant . "</strong></td>							  
						  </tr>";
                                                }
                                                $_SESSION["sql"] = $sql;
                                                $_SESSION["madre"] = $madre;
                                                $_SESSION["hija"] = $hija;
                                                $_SESSION["finca"] = $finca;
                                            } else {
                                                if ($finca != '') {
                                                    $sql = "SELECT codigo,item,finca,fecha_vuelo,fecha_entrega,servicio,guia_madre,guia_hija, tracking_asig,guia_master,palet FROM tblcoldroom WHERE finca = '" . $finca . "' AND salida='Si' AND (guia_madre != 0 OR guia_hija != 0) UNION (SELECT codigo_unico,item,finca,vuelo,entrega,servicio,guia_m,guia_h, tracking_asig,guia_master,palet FROM tblcoldrom_fincas WHERE finca = '" . $finca . "') ORDER BY finca";
                                                    $val = mysqli_query($link, $sql);
                                                    if (!$val) {
                                                        echo "<tr><td>" . mysqli_error() . "</td></tr>";
                                                    } else {
                                                        $cant = 0;
                                                        $subtotal = 0;
                                                        $row = mysqli_fetch_array($val);
                                                        if ($row['fecha_vuelo'] != '') {
                                                            $cant ++;
                                                            $subtotal++;
                                                            echo "<tr>";

                                                            echo "<td align='center'><strong>" . $row['item'] . "</strong></td>";
                                                            //Buscar descripcion del item a mostrar
                                                            $a = "SELECT prod_descripcion FROM tblproductos WHERE id_item = '" . $row['item'] . "'";
                                                            $b = mysqli_query($link, $a) or die("Error consultando descripcion del item");
                                                            $c = mysqli_fetch_array($b);
                                                            $d = $c['prod_descripcion'];
                                                            echo "<td><strong>" . $d . "</strong></td>";
                                                            echo "<td><strong>" . $row['finca'] . "</strong></td>";
                                                            echo "<td align='center'>" . $row['tracking_asig'] . "</td>";
                                                            echo "<td align='center'>" . $row['fecha_vuelo'] . "</td>";
                                                            echo "<td align='center'>" . $row['fecha_entrega'] . "</td>";
                                                            echo "<td align='center'>" . $row['servicio'] . "</td>";
                                                            echo "<td align='center'>" . $row['guia_madre'] . "</td>";
                                                            echo "<td align='center'>" . $row['guia_hija'] . "</td>";
                                                            echo '<td align="center"><input name="cajas[]" type="checkbox" value="' . $row['codigo'] . '" title="Seleccionar pedido" checked/></td>';
                                                            echo "</tr>";
                                                            $dato = $row['finca'];
                                                        }

                                                        //Segunda fila   
                                                        while ($row = mysqli_fetch_array($val)) {

                                                            if ($row['fecha_vuelo'] != '') {
                                                                if ($dato != $row['finca']) {
                                                                    echo "<tr>
											  <td align='right'><strong>Subtotal:</strong></td>
											  <td align='center'><strong>" . $subtotal . "</strong></td>										  
										  </tr>";
                                                                    $subtotal = 0;
                                                                }

                                                                $cant ++;
                                                                $subtotal++;
                                                                echo "<tr>";

                                                                echo "<td align='center'><strong>" . $row['item'] . "</strong></td>";
                                                                //Buscar descripcion del item a mostrar
                                                                $a = "SELECT prod_descripcion FROM tblproductos WHERE id_item = '" . $row['item'] . "'";
                                                                $b = mysqli_query($link, $a) or die("Error consultando descripcion del item");
                                                                $c = mysqli_fetch_array($b);
                                                                $d = $c['prod_descripcion'];
                                                                echo "<td><strong>" . $d . "</strong></td>";
                                                                echo "<td><strong>" . $row['finca'] . "</strong></td>";
                                                                echo "<td align='center'>" . $row['tracking_asig'] . "</td>";
                                                                echo "<td align='center'>" . $row['fecha_vuelo'] . "</td>";
                                                                echo "<td align='center'>" . $row['fecha_entrega'] . "</td>";
                                                                echo "<td align='center'>" . $row['servicio'] . "</td>";
                                                                echo "<td align='center'>" . $row['guia_madre'] . "</td>";
                                                                echo "<td align='center'>" . $row['guia_hija'] . "</td>";
                                                                echo '<td align="center"><input name="cajas[]" type="checkbox" value="' . $row['codigo'] . '" title="Seleccionar pedido" checked/></td>';
                                                                echo "</tr>";
                                                                $dato = $row['finca'];
                                                            }
                                                        }
                                                        echo "<tr>
											  <td align='right'><strong>Subtotal:</strong></td>
											  <td align='center'><strong>" . $subtotal . "</strong></td>										  
							</tr>";

                                                        echo "<tr>
						  		<td><strong>Cajas encontradas</strong></td>
							    <td align='center'><strong>" . $cant . "</strong></td>							  
						  </tr>";
                                                    }
                                                    $_SESSION["sql"] = $sql;
                                                    $_SESSION["madre"] = $madre;
                                                    $_SESSION["hija"] = $hija;
                                                    $_SESSION["finca"] = $finca;
                                                    $_SESSION ['filtro'] = $sql;
                                                } else {
                                                    echo("<script> alert ('Ingrese algun criterio de busqueda'); </script>");
                                                }
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
                    <a href="#top" class="well well-sm"  onclick="$('html,body').animate({scrollTop: 0}, 'slow');return false;">
                        <i class="glyphicon glyphicon-chevron-up"></i> Ir arriba
                    </a>
                </span><!-- /top-link-block --> 
            </div> <!-- /container -->
    </body>
</html>

