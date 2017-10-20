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
?>


<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Modificar Guías</title>

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

        <?php
        if ($_GET['id'] == 1) {
            echo '<script>window.open("modificar_guiamadre.php","Cantidad","width=400,height=300,top=100,left=400")</script>';
        }

        if ($_GET['id'] == 2) {
            echo '<script> window.open("modificar_guiahija.php","Cantidad","width=400,height=350,top=100,left=400");</script>';
        }
        if ($_GET['id'] == 3) {
            echo '<script> window.open("modificar_guiamaster.php","Cantidad","width=400,height=350,top=100,left=400");</script>';
        }
        $cajas[0] = 0;
        ?>

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
        <script type="text/javascript">
            function marcar() {
                for (var i = 0; i < document.form1.elements.length; i++) {
                    if (document.form1.elements[i].type == 'checkbox') {
                        document.form1.elements[i].checked = !(document.form1.elements[i].checked);
                    }
                }
            }
        </script>
        <script language="javascript">

            function Compara(frmFec)
            {
                var fecha1 = document.getElementById('fecha').value;
                var fecha2 = document.getElementById('fecha1').value;

                var guiamadre_search = document.getElementById('guiamadre_search').value;
                var guiahija_search = document.getElementById('guiahija_search').value;
                var guiahija_search = document.getElementById('guiamaster_search').value;

                //alert(guiamadre_search)
                //alert(guiahija_search)


                var Anio = (frmFec.fecha.value).substr(0, 4)
                var Mes = ((frmFec.fecha.value).substr(5, 2)) * 1
                var Dia = (frmFec.fecha.value).substr(8, 2)
                var Anio1 = (frmFec.fecha1.value).substr(0, 4)
                var Mes1 = ((frmFec.fecha1.value).substr(5, 2)) * 1
                var Dia1 = (frmFec.fecha1.value).substr(8, 2)
                var Fecha_Inicio = new Date(Anio, Mes, Dia)
                var Fecha_Fin = new Date(Anio1, Mes1, Dia1)

                if (fecha == '' && fecha1 == '' && guiamadre_search == '' && guiahija_search == '' && guiamaster_search == '')
                {
                    window.location.href = 'modificar_guia.php?error=3';
                    return false;
                }

                if (Fecha_Inicio > Fecha_Fin)
                {
                    window.location.href = 'modificar_guia.php?error=4';
                    return false;
                } else
                {
                    return true;
                }
            }

            $(document).ready(function () {
                //tol-tip-text
                $('[data-toggle="tooltip"]').tooltip();
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

                                    <li ><a href="asig_guia.php" ><strong>Asignar Guía</strong></a></li>     	
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
                                    <li  class="active"><a href="modificar_guia.php" ><strong>Editar Guías</strong></a></li>   
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
                    <form id="form1" name="form1" method="post" action="modificar_guia.php">
                        <div class="table-responsive">
                            <table width="50%" border="0" align="center" class="table table-striped" >
                                <tr align="center"><td><h3><strong>MODIFICAR GUÍAS DE VUELO</strong></h3></td>
                                </tr>
                                <tr>
                                    <td id="guiamadre">
                                        <div class="col-mdd-1">
                                            <label>Fecha de Traqueo:</label>
                                        </div>
                                        <div class="col-md-2">

                                            <div class="input-group date" style="width: auto;" id="datetimepicker">
                                                <input type='text' class="form-control" name="fecha" id="fecha" value="<?php echo date('Y-m-d') ?>"/>
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
                                            <div class="input-group date" style="width: auto;" id="datetimepicker1">
                                                <input type='text' class="form-control" name="fecha1" id="fecha1" value="<?php echo date('Y-m-d') ?>"/>
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
                                        <div class="col-mdd-1">
                                            <label>Finca:</label>
                                        </div>
                                        <div class="col-md-3">
                                            <select type="text" name="finca" id="finca" class="form-control" >
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
                                            <input name="filtrar" type="submit" class= "btn btn-primary" value="Buscar" data-toggle="tooltip" data-placement="right" title="Filtrar búsqueda" onclick="return Compara(this.form)"/>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="buscarguias" align="center">
                                        <div class="col-mdd-1">  
                                            <label>Guía Madre</label>
                                        </div>
                                        <div class="col-md-4">
                                            <input name="guiamadre_search" type="text" id="guiamadre_search" class="form-control"/>
                                        </div>
                                        <div class="col-mdd-1">  
                                            <label>Guía Hija</label>
                                        </div>
                                        <div class="col-md-4">
                                            <input name="guiahija_search" type="text" id="guiahija_search" class="form-control"/>
                                        </div>
                                        <div class="col-md-4" style="padding-top: 16px;">  
                                            <label>Guía Master</label>
                                        </div>
                                        <div class="col-md-4" style="padding-top: 16px;">
                                            <input name="guiamaster_search" type="text" id="guiamaster_search" class="form-control"/>
                                        </div>
                                    </td>

                                </tr>

                            </table>
                        </div>

                        <div class="table-responsive">
                            <table width="50%" border="0" align="center" class="table table-striped" > 
                                <tr>
                                    <td colspan="5" align="center">
                                        <h3><strong>Listado de órdenes (Últimas 25)</strong></h3>
                                    </td>
                                    <td align="right">
                                        <button formaction="recogercheck_modificar.php?id=1" class="btn btn-primary" title = "Modificar Guía Madre">AWB</button>
                                    </td>    
                                    <td>
                                        <button formaction="recogercheck_modificar.php?id=2" class="btn btn-primary" title = "Modificar Guía Hija">HAWB</button>
                                    </td>
                                    <td>
                                        <button formaction="recogercheck_modificar.php?id=3" class="btn btn-primary" title = "Modificar Guía Master">MAST</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center"><strong>Código de Caja</strong></td>
                                    <td align="center"><strong>Producto</strong></td>
                                    <td align="center"><strong>Finca</strong></td>
                                    <td align="center"><strong>Fecha Traqueo</strong></td>
                                    <td align="center"><strong>Guía Madre</strong></td>
                                    <td align="center"><strong>Guía Hija</strong></td>
                                    <td align="center"><strong>Guía Master</strong></td>
                                    <td align="center"><strong><input type="checkbox" value="0" onchange="marcar()" title="Marcar todos"/></strong></td>
                                </tr>
                                <?php
                                if (!isset($_POST['filtrar'])) {    //Si no se ha presionado el boton de busqueda
                                    $sql = "SELECT * FROM tblcoldroom WHERE fecha_tracking <= '" . date('Y-m-d') . "' AND salida='Si' AND (guia_madre != 0 OR guia_hija != 0) ORDER BY codigo DESC LIMIT 25";
                                    $val = mysqli_query($link, $sql);
                                    if (!$val) {
                                        echo "<tr><td>" . mysqli_error() . "</td></tr>";
                                    } else {
                                        $cant = 0;
                                        while ($row = mysqli_fetch_array($val)) {

                                            if ($row['fecha_tracking'] != '') {
                                                $cant ++;
                                                echo "<tr>";
                                                echo "<td align='center'><strong>" . $row['codigo'] . "</strong></td>";
                                                echo "<td align='center'><strong>" . $row['item'] . "</strong></td>";
                                                echo "<td><strong>" . $row['finca'] . "</strong></td>";
                                                echo "<td align='center'>" . $row['fecha_tracking'] . "</td>";
                                                echo "<td align='center'>" . $row['guia_madre'] . "</td>";
                                                echo "<td align='center'>" . $row['guia_hija'] . "</td>";
                                                echo "<td align='center'>" . $row['guia_master'] . "</td>";
                                                echo '<td align="center"><input name="cajas[]" type="checkbox" value="' . $row['codigo'] . '" title="Marcar Caja"/></td>';
                                                echo "</tr>";
                                            }
                                        }
                                        echo "
					  <tr>
              <td></td>
              <td align='right'>Mostrando las últimas</td>
						  <td align='left'><strong>" . $cant . " Órdenes</strong></td>
					  </tr>";
                                    }
                                } else {       //Se presiono el boton de busqueda
                                    //verificar filtros de busqueda
                                    $fecha = $_POST ['fecha'];
                                    $fecha1 = $_POST ['fecha1'];
                                    $finca = $_POST ['finca'];

                                    if (isset($_REQUEST['todo'])) {
                                        $sql = "SELECT * FROM tblcoldroom WHERE fecha_tracking <= '" . date('Y-m-d') . "' AND salida='Si' AND (guia_madre != 0 OR guia_hija != 0)";
                                        $val = mysqli_query($link, $sql);
                                        if (!$val) {
                                            echo "<tr><td>" . mysqli_error() . "</td></tr>";
                                        } else {
                                            $cant = 0;
                                            while ($row = mysqli_fetch_array($val)) {

                                                if ($row['fecha_tracking'] != '') {
                                                    $cant ++;
                                                    echo "<tr>";
                                                    echo "<td align='center'><strong>" . $row['codigo'] . "</strong></td>";
                                                    echo "<td align='center'><strong>" . $row['item'] . "</strong></td>";
                                                    echo "<td><strong>" . $row['finca'] . "</strong></td>";
                                                    echo "<td align='center'>" . $row['fecha_tracking'] . "</td>";
                                                    echo "<td align='center'>" . $row['guia_madre'] . "</td>";
                                                    echo "<td align='center'>" . $row['guia_hija'] . "</td>";
                                                    echo "<td align='center'>" . $row['guia_master'] . "</td>";
                                                    echo '<td align="center"><input name="cajas[]" type="checkbox" value="' . $row['codigo'] . '" title="Marcar Caja"/></td>';
                                                    echo "</tr>";
                                                }
                                            }
                                            echo "
					  <tr>
						  <td align='right'><strong>" . $cant . "</strong></td>
						  <td>Órden(es) encontradas</td>
					  </tr>";

                                            $_SESSION ['filtro'] = $sql;
                                        }
                                    } else {  //No se selecciono buscar todo
                                        $guia_mother = $_POST['guiamadre_search'];
                                        $guia_child = $_POST['guiahija_search'];
                                        $guia_master = $_POST['guiamaster_search'];

                                        //echo $guia_mother;
                                        //echo $guia_child;

                                        if ($guia_mother != '') {
                                            $sql = "select * from tblcoldroom WHERE guia_madre = '$guia_mother' ";

                                            //echo $sql;


                                            $val = mysqli_query($link, $sql);
                                            if (!$val) {
                                                echo "<tr><td>" . mysqli_error() . "</td></tr>";
                                            } else {
                                                $cant = 0;
                                                while ($row = mysqli_fetch_array($val)) {

                                                    if ($row['fecha_tracking'] != '') {
                                                        $cant ++;
                                                        echo "<tr>";
                                                        echo "<td align='center'><strong>" . $row['codigo'] . "</strong></td>";
                                                        echo "<td align='center'><strong>" . $row['item'] . "</strong></td>";
                                                        echo "<td><strong>" . $row['finca'] . "</strong></td>";
                                                        echo "<td align='center'>" . $row['fecha_tracking'] . "</td>";
                                                        echo "<td align='center'>" . $row['guia_madre'] . "</td>";
                                                        echo "<td align='center'>" . $row['guia_hija'] . "</td>";
                                                        echo "<td align='center'>" . $row['guia_master'] . "</td>";
                                                        echo '<td align="center"><input name="cajas[]" type="checkbox" value="' . $row['codigo'] . '" title="Marcar Caja"/></td>';
                                                        echo "</tr>";
                                                    }
                                                }
                                                echo "
             <tr>
               <td align='right'><strong>" . $cant . "</strong></td>
               <td>Órden(es) encontradas</td>
             </tr>";
                                                $_SESSION ['filtro'] = $sql;
                                            }
                                        }


                                        if ($guia_child != '') {
                                            $sql = "select * from tblcoldroom WHERE guia_hija = '$guia_child' ";

                                            //echo $sql;


                                            $val = mysqli_query($link, $sql);
                                            if (!$val) {
                                                echo "<tr><td>" . mysqli_error() . "</td></tr>";
                                            } else {
                                                $cant = 0;
                                                while ($row = mysqli_fetch_array($val)) {

                                                    if ($row['fecha_tracking'] != '') {
                                                        $cant ++;
                                                        echo "<tr>";
                                                        echo "<td align='center'><strong>" . $row['codigo'] . "</strong></td>";
                                                        echo "<td align='center'><strong>" . $row['item'] . "</strong></td>";
                                                        echo "<td><strong>" . $row['finca'] . "</strong></td>";
                                                        echo "<td align='center'>" . $row['fecha_tracking'] . "</td>";
                                                        echo "<td align='center'>" . $row['guia_madre'] . "</td>";
                                                        echo "<td align='center'>" . $row['guia_hija'] . "</td>";
                                                        echo "<td align='center'>" . $row['guia_master'] . "</td>";
                                                        echo '<td align="center"><input name="cajas[]" type="checkbox" value="' . $row['codigo'] . '" title="Marcar Caja"/></td>';
                                                        echo "</tr>";
                                                    }
                                                }
                                                echo "
             <tr>
               <td align='right'><strong>" . $cant . "</strong></td>
               <td>Órden(es) encontradas</td>
             </tr>";
                                                $_SESSION ['filtro'] = $sql;
                                            }
                                        }
                                        
                                        if ($guia_master != '') {
                                            $sql = "select * from tblcoldroom WHERE guia_master = '$guia_master' ";

                                            //echo $sql;


                                            $val = mysqli_query($link, $sql);
                                            if (!$val) {
                                                echo "<tr><td>" . mysqli_error() . "</td></tr>";
                                            } else {
                                                $cant = 0;
                                                while ($row = mysqli_fetch_array($val)) {

                                                    if ($row['fecha_tracking'] != '') {
                                                        $cant ++;
                                                        echo "<tr>";
                                                        echo "<td align='center'><strong>" . $row['codigo'] . "</strong></td>";
                                                        echo "<td align='center'><strong>" . $row['item'] . "</strong></td>";
                                                        echo "<td><strong>" . $row['finca'] . "</strong></td>";
                                                        echo "<td align='center'>" . $row['fecha_tracking'] . "</td>";
                                                        echo "<td align='center'>" . $row['guia_madre'] . "</td>";
                                                        echo "<td align='center'>" . $row['guia_hija'] . "</td>";
                                                        echo "<td align='center'>" . $row['guia_master'] . "</td>";
                                                        echo '<td align="center"><input name="cajas[]" type="checkbox" value="' . $row['codigo'] . '" title="Marcar Caja"/></td>';
                                                        echo "</tr>";
                                                    }
                                                }
                                                echo "
             <tr>
               <td align='right'><strong>" . $cant . "</strong></td>
               <td>Órden(es) encontradas</td>
             </tr>";
                                                $_SESSION ['filtro'] = $sql;
                                            }
                                        }






                                        if ($fecha != '' && $fecha1 != '') {
                                            $sql = "SELECT * FROM tblcoldroom WHERE fecha_tracking BETWEEN '" . $fecha . "' AND '" . $fecha1 . "' AND salida='Si' AND (guia_madre != 0 OR guia_hija != 0)";
                                            $val = mysqli_query($link, $sql);
                                            if (!$val) {
                                                echo "<tr><td>" . mysqli_error() . "</td></tr>";
                                            } else {
                                                $cant = 0;
                                                while ($row = mysqli_fetch_array($val)) {

                                                    if ($row['fecha_tracking'] != '') {
                                                        $cant ++;
                                                        echo "<tr>";
                                                        echo "<td align='center'><strong>" . $row['codigo'] . "</strong></td>";
                                                        echo "<td align='center'><strong>" . $row['item'] . "</strong></td>";
                                                        echo "<td><strong>" . $row['finca'] . "</strong></td>";
                                                        echo "<td align='center'>" . $row['fecha_tracking'] . "</td>";
                                                        echo "<td align='center'>" . $row['guia_madre'] . "</td>";
                                                        echo "<td align='center'>" . $row['guia_hija'] . "</td>";
                                                        echo "<td align='center'>" . $row['guia_master'] . "</td>";
                                                        echo '<td align="center"><input name="cajas[]" type="checkbox" value="' . $row['codigo'] . '" title="Marcar Caja"/></td>';
                                                        echo "</tr>";
                                                    }
                                                }
                                                echo "
					   <tr>
						   <td align='right'><strong>" . $cant . "</strong></td>
						   <td>Órden(es) encontradas</td>
					   </tr>";
                                                $_SESSION ['filtro'] = $sql;
                                            }
                                        } else {
                                            if ($finca != '') {
                                                $sql = "SELECT * FROM tblcoldroom WHERE finca = '" . $finca . "' AND salida='Si' AND (guia_madre != 0 OR guia_hija != 0)";
                                                $val = mysqli_query($link, $sql);
                                                if (!$val) {
                                                    echo "<tr><td>" . mysqli_error() . "</td></tr>";
                                                } else {
                                                    $cant = 0;
                                                    while ($row = mysqli_fetch_array($val)) {

                                                        if ($row['fecha_tracking'] != '') {
                                                            $cant ++;
                                                            echo "<tr>";
                                                            echo "<td align='center'><strong>" . $row['codigo'] . "</strong></td>";
                                                            echo "<td align='center'><strong>" . $row['item'] . "</strong></td>";
                                                            echo "<td><strong>" . $row['finca'] . "</strong></td>";
                                                            echo "<td align='center'>" . $row['fecha_tracking'] . "</td>";
                                                            echo "<td align='center'>" . $row['guia_madre'] . "</td>";
                                                            echo "<td align='center'>" . $row['guia_hija'] . "</td>";
                                                            echo "<td align='center'>" . $row['guia_master'] . "</td>";
                                                            echo '<td align="center"><input name="cajas[]" type="checkbox" value="' . $row['codigo'] . '" title="Marcar Caja"/></td>';
                                                            echo "</tr>";
                                                        }
                                                    }
                                                    echo "<tr>
							   <td align='right'><strong>" . $cant . "</strong></td>
							   <td>Órden(es) encontradas</td>
						   </tr>";
                                                    $_SESSION ['filtro'] = $sql;
                                                }
                                            } else {
                                                //echo "<script> window.location.href='modificar_guia.php?error=2'</script>";
                                            }
                                        }
                                    }
                                }
                                ?>
                            </table>
                        </div> <!-- table responsive-->
                </div> <!-- /panel body --> 
                </form>
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