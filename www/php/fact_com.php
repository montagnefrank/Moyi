<?php
///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL); 
//ini_set('display_errors', 1);


session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");

session_start();
$user = $_SESSION["login"];
$rol = $_SESSION["rol"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
$finca = $_GET['finca'];
$cajas[0] = 0;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Factura Comercial</title>

            <link rel="icon" type="image/png" href="../images/favicon.ico" />
            <link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
                <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
                    <link href="../bootstrap/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
                        <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
                            <link href="../bootstrap/css/octicons.css" rel="stylesheet" type="text/css">
                                <link href="../bootstrap/css/zenburn.css" rel="stylesheet" type="text/css">
                                    <link href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1"rel="stylesheet" type="text/css" media="all"  />
                                    <link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">

                                        <script language="javascript" src="../js/imprimir.js"></script>
                                        <script type="text/javascript" src="../js/script.js"></script>
                                        <script src="../bootstrap/js/jquery.js"></script>
                                        <script src="../bootstrap/js/bootstrap.js"></script>
                                        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
                                        <script src="../bootstrap/js/bootstrap-submenu.js"></script>
                                        <script src="../bootstrap/js/docs.js" defer></script>
                                        <script type="text/javascript" src="../js/calendar.js"></script>
                                        <script type="text/javascript" src="../js/calendar-en.js"></script>
                                        <script type="text/javascript" src="../js/calendar-setup.js"></script><style>
                                            .contenedor {
                                                margin-left: 10px;
                                                width:100%;
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
                                            function cambiar(valor) {
                                                var v = valor;
                                                window.open("cambiarcontrasenna.php?codigo=" + v, "Cantidad", "width=400,height=300,top=150,left=400");
                                                return false;
                                            }
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
                                                                    <a class="navbar-brand" href="imp_etiquetas.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
                                                                </div>

                                                                <!-- Agrupar los enlaces de navegación, los formularios y cualquier
                                                                     otro elemento que se pueda ocultar al minimizar la barra -->
                                                                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                                                    <ul class="nav navbar-nav">
                                                                        <li><a href="dae.php"><strong>Ingresar DAE</strong></a></li> 
<?php echo '<li><a href="fact_com.php?finca=' . $finca . '"><strong>Facturas Comerciales</strong></a></li>'; ?>
                                                                        <?php
                                                                        if ($rol == 1) {
                                                                            //no muestra nada
                                                                        } else {
                                                                            $sql = "SELECT id_usuario from tblusuario where cpuser='" . $user . "'";
                                                                            $query = mysqli_query($link, $sql);
                                                                            $row = mysqli_fetch_array($query);
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
                                                    <div class="panel-body" align="center">
                                                        <form id="form1" name="form1" method="post" action="">
                                                            <div class="table-responsive">
                                                                <table width="50%" border="0" align="center" class="table table-striped" >
                                                                    <tr style="visibility:hidden">
                                                                        <td> 
                                                                            <input name="finca" type="text" value="<?php echo $finca; ?>"/>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="4" align="center"><h3><strong>BUSCAR GUIA</strong></h3></td>
                                                                    </tr>
                                                                    <tr> 
                                                                        <td align="center">
                                                                            <strong>AWB-GUIA:</strong>
<?php
echo '<select type="text" name="madre">';
//Obtener las guias asociadas a esa finca
$guiaSql = "SELECT DISTINCT guia_madre FROM tblcoldroom WHERE finca='" . $finca . "' AND guia_madre!='0'";
$consulta = mysqli_query($link, $guiaSql) or die("Error seleccionando guias de sta finca");
while ($fila = mysqli_fetch_array($consulta)) {
    echo '<option value="' . $fila['guia_madre'] . '">' . $fila['guia_madre'] . '</option>';
}
echo '</select>';
?>
                                                                            <input name="filtrar" type="submit" value="Filtrar" class="btn btn-primary" data-toggle="tooltip" data-placement="rigth" title="Filtrar búsqueda"/>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </form>

                                                        <div class="table-responsive">
                                                            <table width="50%" border="0" align="center" class="table table-striped" >
                                                                <tr>
                                                                    <td colspan="8" align="center">
                                                                        <h3><strong>Guías Asignadas</strong></h3> 
                                                                    </td>
                                                                    <td align="right">

<?php
if ($_POST ['madre'] != '')
    echo '<form action="facturacomercial.php" method="post" target="_blank">
			<input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/factura.png" heigth="40" value="" title = "Facturar todo lo marcado" width="30" onclick = "" /> 
			</form>';
?>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td align="center"><strong>Item</strong></td>
                                                                    <td align="center"><strong>Prod Desc.</strong></td>
                                                                    <td align="center"><strong>Finca</strong></td>    
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
    $finca = $_POST ['finca'];

    $sql = "SELECT codigo,item,finca,fecha_vuelo,fecha_entrega,servicio,guia_madre,guia_hija FROM tblcoldroom WHERE guia_madre = '" . $madre . "' AND finca = '" . $finca . "' AND salida='Si' ORDER BY finca";
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
        echo "
										  <tr>
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
    $_SESSION["codigo"] = 1;
}
?>
                                                                </form>
                                                            </table>
                                                        </div>
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

