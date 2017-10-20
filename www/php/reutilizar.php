<?php
///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");

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

$error = $_GET['error'];
$id = $_GET['id'];
$cajas[0] = 0;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Reutilizar Cajas</title>

            <link rel="icon" type="image/png" href="../images/favicon.ico" />
            <link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
                <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
                    <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
                        <link href="../bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" media="all"  />
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
                                <script type="text/javascript">
                                    function marcar() {
                                        for (var i = 0; i < document.frmreport.elements.length; i++) {
                                            if (document.frmreport.elements[i].type == 'checkbox') {
                                                document.frmreport.elements[i].checked = !(document.frmreport.elements[i].checked);
                                            }
                                        }
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
                                                                <li  class="active"><a href="reutilizar.php" ><strong>Reutilizar Cajas</strong></a></li>
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

                                            <div class="panel-body" align="center">
                                                <form id="form" name="form" method="post">
                                                    <div class="table-responsive">
                                                        <table width="50%" border="0" align="center" class="table table-striped" >
                                                            <tr>
                                                                <td align="center"><h3><strong>REUTILIZAR ETIQUETAS RECHAZADAS</strong></h3></td>
                                                            </tr>
                                                            <tr> 
                                                                <td align="center">
                                                                    <div class="col-md-1">
                                                                        <strong>Finca:</strong>
                                                                    </div>
                                                                    <div class="col-md-4">  
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
                                                                    <div class="col-md-1">
                                                                        <strong>Fecha Pedido:</strong>
                                                                    </div>              
                                                                    <div class="col-md-3">        
                                                                        <div class='input-group date' id='datetimepicker'>
                                                                            <input type='text' class="form-control" name="fecha" id="fecha" value=""/>
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

                                                                    <div class="col-md-2">                                  
                                                                        <input name="todo" id="todo" type="checkbox" value="" title="Ver todos"/>
                                                                        <input type="submit" name="buscar" class= "btn btn-primary" id="buscar" value="Buscar"/>
                                                                    </div>
                                                                </td>

                                                            </tr>
                                                        </table>
                                                    </div>

                                                    <div class="table-responsive">
                                                        <table width="50%" border="0" align="center" class="table table-striped" > 
                                                            <tr>
                                                                <td colspan="6" align="center">
                                                                    <h3><strong>Listado de órdenes</strong></h3>
                                                                </td>
                                                                <td align="right">
                                                                    <?php
                                                                    echo '<input type="image" style="cursor:pointer" name="btn_cliente1" id="btn_cliente1" heigth="40" value="" title = "Reutilizar Etiquetas" width="30" src="../images/aceptar.png" formaction="reutilizar.php?id=1"/> ';
                                                                    ?>   
                                                                    <input type="image" style="cursor:pointer" id="imprimir"  name="imprimir"class= "imprimir" src="../images/excel.png" heigth="40" value="" title = "Exportar Reporte Excel" width="30" formaction = "crearReportExcel.php"/>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="center"><strong>Código de Caja</strong></td>
                                                                <td align="center"><strong>Producto</strong></td>
                                                                <td align="center"><strong>Finca</strong></td>
                                                                <td align="center"><strong>Comentarios</strong></td>
                                                                <td align="center"><strong>Razones</strong></td>
                                                                <td align="center"><strong>Fecha Pedido</strong></td>
                                                                <td align="center"><strong><input type="checkbox" value="X" onchange="marcar()" title="Marcar todos"/></strong></td>
                                                            </tr>
                                                            <?php
                                                            if (isset($_POST['buscar'])) {

                                                                $finca = $_POST['finca'];
                                                                $fecha = $_POST['fecha'];

                                                                if ($fecha != '' && $finca != '') {
                                                                    $sql = "SELECT codigo,item,finca,comentario,razones1,fecha FROM tbletiquetasxfinca where (estado= 2 OR estado= 3) AND finca='" . $finca . "' AND fecha='" . $fecha . "'";
                                                                    $val = mysqli_query($link, $sql);
                                                                    if (!$val) {
                                                                        echo "<tr><td>" . mysqli_error() . "</td></tr>";
                                                                    } else {
                                                                        $cant = 0;
                                                                        while ($row = mysqli_fetch_array($val)) {
                                                                            $cant ++;
                                                                            echo "<tr>";
                                                                            echo "<td align='center'><strong>" . $row['codigo'] . "</strong></td>";
                                                                            echo "<td align='center'>" . $row['item'] . "</td>";
                                                                            echo "<td>" . $row['finca'] . "</td>";
                                                                            echo "<td>" . $row['comentario'] . "</td>";
                                                                            echo "<td>" . $row['razones1'] . "</td>";
                                                                            echo "<td align='center'>" . $row['fecha'] . "</td>";
                                                                            echo '<td align="center"><input name="cajas[]" type="checkbox" value="' . $row['codigo'] . '" title="Reutilizar etiqueta"/></td>';
                                                                            echo "</tr>";
                                                                        }
                                                                        echo "<tr><td></td></tr>
										  <tr>
										  <td align='right'><strong>" . $cant . "</strong></td>
										  <td>Órden(es) encontradas</td>
										  </tr>";
                                                                    }

                                                                    //Preparando los datos para el reporte
                                                                    $_SESSION["titulo"] = "Listado de Órdenes HOY" . $hoy;
                                                                    $_SESSION["columnas"] = array("Código de Caja", "Item", "Finca", "Comentarios", "Razones", "Fecha pedido");
                                                                    $_SESSION["consulta"] = $sql;
                                                                    $_SESSION["nombre_fichero"] = "Listado de Órdenes " . $hoy . ".xlsx";
                                                                } else {
                                                                    //Si activo el filtro tod
                                                                    if (isset($_REQUEST['todo'])) {
                                                                        $sql = "SELECT codigo,item,finca,comentario,razones1,fecha FROM tbletiquetasxfinca where (estado= 2 OR estado= 3) AND fecha <= '" . date('Y-m-d') . "'";
                                                                        $val = mysqli_query($link, $sql);
                                                                        if (!$val) {
                                                                            echo "<tr><td>" . mysqli_error() . "</td></tr>";
                                                                        } else {
                                                                            $cant = 0;
                                                                            while ($row = mysqli_fetch_array($val)) {
                                                                                $cant ++;
                                                                                echo "<tr>";
                                                                                echo "<td align='center'><strong>" . $row['codigo'] . "</strong></td>";
                                                                                echo "<td align='center'>" . $row['item'] . "</td>";
                                                                                echo "<td>" . $row['finca'] . "</td>";
                                                                                echo "<td>" . $row['comentario'] . "</td>";
                                                                                echo "<td>" . $row['razones1'] . "</td>";
                                                                                echo "<td align='center'>" . $row['fecha'] . "</td>";
                                                                                echo '<td align="center"><input name="cajas[]" type="checkbox" value="'.$row['codigo'].'" title="Reutilizar etiqueta"/></td>';

                                                                                echo "</tr>";
                                                                            }
                                                                            echo "
										  <tr>
										  <td align='right'><strong>" . $cant . "</strong></td>
										  <td>Órden(es) encontradas</td>
										  </tr>";
                                                                        }

                                                                        //Preparando los datos para el reporte
                                                                        $_SESSION["titulo"] = "Listado de Órdenes " . $hoy;
                                                                        $_SESSION["columnas"] = array("Código de Caja", "Item", "Finca", "Comentarios", "Razones", "Fecha pedido");
                                                                        $_SESSION["consulta"] = $sql;
                                                                        $_SESSION["nombre_fichero"] = "Listado de Órdenes " . $hoy . ".xlsx";
                                                                    } else {
                                                                        if ($finca != '') {
                                                                            $sql = "SELECT codigo,item,finca,comentario,razones1,fecha FROM tbletiquetasxfinca where (estado= 2 OR estado= 3) AND finca='" . $finca . "'";
                                                                            $val = mysqli_query($link, $sql);
                                                                            if (!$val) {
                                                                                echo "<tr><td>" . mysqli_error() . "</td></tr>";
                                                                            } else {
                                                                                $cant = 0;
                                                                                while ($row = mysqli_fetch_array($val)) {
                                                                                    $cant ++;
                                                                                    echo "<tr>";
                                                                                    echo "<td align='center'><strong>" . $row['codigo'] . "</strong></td>";
                                                                                    echo "<td align='center'>" . $row['item'] . "</td>";
                                                                                    echo "<td>" . $row['finca'] . "</td>";
                                                                                    echo "<td>" . $row['comentario'] . "</td>";
                                                                                    echo "<td>" . $row['razones1'] . "</td>";
                                                                                    echo "<td align='center'>" . $row['fecha'] . "</td>";
                                                                                    echo '<td align="center"><input name="cajas[]" type="checkbox" value="'.$row['codigo'].'" title="Reutilizar etiqueta"/></td>';

                                                                                    echo "</tr>";
                                                                                }
                                                                                echo "
											  <tr>
											  <td align='right'><strong>" . $cant . "</strong></td>
											  <td>Órden(es) encontradas</td>
											  </tr>";
                                                                            }

                                                                            //Preparando los datos para el reporte
                                                                            $_SESSION["titulo"] = "Listado de Órdenes " . $hoy;
                                                                            $_SESSION["columnas"] = array("Código de Caja", "Item", "Finca", "Comentarios", "Razones", "Fecha pedido");
                                                                            $_SESSION["consulta"] = $sql;
                                                                            $_SESSION["nombre_fichero"] = "Listado de Órdenes " . $hoy . ".xlsx";
                                                                        } else {
                                                                            if ($fecha != '') {
                                                                                $sql = "SELECT codigo,item,finca,comentario,razones1,fecha FROM tbletiquetasxfinca where (estado= 2 OR estado= 3) AND fecha='" . $fecha . "'";
                                                                                $val = mysqli_query($link, $sql);
                                                                                if (!$val) {
                                                                                    echo "<tr><td>" . mysqli_error() . "</td></tr>";
                                                                                } else {
                                                                                    $cant = 0;
                                                                                    while ($row = mysqli_fetch_array($val)) {
                                                                                        $cant ++;
                                                                                        echo "<tr>";
                                                                                        echo "<td align='center'><strong>" . $row['codigo'] . "</strong></td>";
                                                                                        echo "<td align='center'>" . $row['item'] . "</td>";
                                                                                        echo "<td>" . $row['finca'] . "</td>";
                                                                                        echo "<td>" . $row['comentario'] . "</td>";
                                                                                        echo "<td>" . $row['razones1'] . "</td>";
                                                                                        echo "<td align='center'>" . $row['fecha'] . "</td>";
                                                                                        echo '<td align="center"><input name="cajas[]" type="checkbox" value="'.$row['codigo'].'" title="Reutilizar etiqueta"/></td>';								
                                                                                        echo "</tr>";
                                                                                    }
                                                                                    echo "
											  <tr>
											  <td align='right'><strong>" . $cant . "</strong></td>
											  <td>Órden(es) encontradas</td>
											  </tr>";
                                                                                }

                                                                                //Preparando los datos para el reporte
                                                                                $_SESSION["titulo"] = "Listado de Órdenes " . $hoy;
                                                                                $_SESSION["columnas"] = array("Código de Caja", "Item", "Finca", "Comentarios", "Razones", "Fecha pedido");
                                                                                $_SESSION["consulta"] = $sql;
                                                                                $_SESSION["nombre_fichero"] = "Listado de Órdenes " . $hoy . ".xlsx";
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }

                                                            //Si se mando a reutilizar
                                                            if ($_GET['id'] == 1) {

                                                                $cajas = $_POST['cajas'];
                                                                //hacer ciclo para recoger cada codigo marcado para asignarle la guia
                                                                if (count($cajas) == 0) {
                                                                    echo("<script> alert ('No hay cajas marcadas');
								   window.close();					   
								   window.location='reutilizar.php';
						 </script>");
                                                                } else {
                                                                    $cant = count($cajas);
                                                                    $_SESSION['cajas'] = $cajas;
                                                                    $_SESSION['cant'] = $cant;
                                                                    echo '<script> window.open("reutilizarpedido.php","Cantidad","width=500,height=430,top=100,left=400"); </script>';
                                                                }
                                                            }
                                                            ?>
                                                        </table>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h3><strong>Leyenda de razones</strong></h3>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <strong>FZ:</strong> FROZEN --
                                                                <strong>BO:</strong> BOTRITYS --
                                                                <strong>CV:</strong> CRACKED VASE --
                                                                <strong>IH:</strong> IMPROPER HYDRATION --            
                                                                <strong>UPS:</strong> UPS ERROR --
                                                                <strong>SHC:</strong> SHIPMENT DELIVERED CORRECTLY 
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <strong>SHI:</strong> SHIPMENT DELIVERED INCORRECTLY          
                                                                <strong>GP:</strong> GUARD PETALS --
                                                                <strong>WAB:</strong> WRONG AMOUNT OF BLOOMS --
                                                                <strong>WB:</strong> WRONG BOUQUET --
                                                                <strong>WC:</strong> WRONG COLOR --
                                                                <strong>DWA:</strong> DELIVERED TO WRONG ADDRESS
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <strong>D2DL:</strong> DELIVERY 2+ DAYS LATE --
                                                                <strong>IND:</strong> ITEM NOT DELIVERED -- 
                                                                <strong>NMS:</strong> NO MENSSAGE SENT --
                                                                <strong>IN:</strong> INSECTS --
                                                                <strong>OT:</strong> OTHER
                                                            </div>
                                                        </div>
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

