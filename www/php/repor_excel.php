<?php
session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");
$user = $_SESSION["login"];
$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : ' . mysql_error());

$fecha_inicio0 = $_SESSION["inicio0"];
$fecha_fin0 = $_SESSION["fin0"];

$fecha_inicio = $_SESSION["inicio"];
$fecha_fin = $_SESSION["fin"];

$fecha_inicio1 = $_SESSION["inicio1"];
$fecha_fin1 = $_SESSION["fin1"];

$fecha_inicio2 = $_SESSION["inicio2"];
$fecha_fin2 = $_SESSION["fin2"];

$tracking = $_SESSION["tracking"];

$ponumber = $_SESSION["ponumber"];

$custnumber = $_SESSION["custnumber"];

$item = $_SESSION["item"];

$farm = $_SESSION["farm"];

$shipto1 = $_SESSION["shipto1"];

$direccion = $_SESSION["direccion"];

$soldto1 = $_SESSION["soldto1"];

$cpdireccion_soldto = $_SESSION["cpdireccion_soldto"];

$pais = $_SESSION["pais"];

$origen = $_SESSION["origen"];

$origen2 = $_SESSION["origen2"];

$rol = $_SESSION["rol"];

$alltrack = $_SESSION["alltrack"];

$descarga = $_SESSION["descarga"];
$consolidado = $_SESSION["consolidado"];
$total = 0;

function restaFechas($dFecIni, $dFecFin) {
    $dFecIni = str_replace("-", "", $dFecIni);
    $dFecIni = str_replace("/", "", $dFecIni);
    $dFecFin = str_replace("-", "", $dFecFin);
    $dFecFin = str_replace("/", "", $dFecFin);

    ereg("([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecIni, $aFecIni);
    ereg("([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecFin, $aFecFin);

    $date1 = mktime(0, 0, 0, $aFecIni[2], $aFecIni[1], $aFecIni[3]);
    $date2 = mktime(0, 0, 0, $aFecFin[2], $aFecFin[1], $aFecFin[3]);
    return round(($date2 - $date1) / (60 * 60 * 24));
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Visor de Reportes</title>

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
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
        <style>
            .contenedor {
                margin:10px auto;
                width:99%;
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

            .table-responsive {
                width: 100%;
                height: 600px;    /*change added by me*/
                margin-bottom: 15px;
                overflow-y: scroll;  /*change by me org hidden */
                overflow-x: scroll;
                -ms-overflow-style: -ms-autohiding-scrollbar;
                border: 1px solid #ddd;
                -webkit-overflow-scrolling: touch;
            }

            .table-responsive>.table { 
                margin-bottom: 0;    
            }

            .table-responsive>.table>thead>tr>th, 
            .table-responsive>.table>tbody>tr>th, 
            .table-responsive>.table>tfoot>tr>th, 
            .table-responsive>.table>thead>tr>td, 
            .table-responsive>.table>tbody>tr>td, 
            .table-responsive>.table>tfoot>tr>td {
                white-space: nowrap;
            }
            li a{
                cursor:pointer;/*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
            } 
        </style>

        <script language="javascript">
            $(document).ready(function () {
                $("#reporteExcel").click(function (event) {
                    $("#agencias").attr('action', 'crearcsv6.php');
                    $("#agencias").submit();
                });
                $("#impEtiquetas").click(function (event) {
                    $("#agencias").attr('action', 'print_ups.php');
                    $("#agencias").submit();
                });
            });

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
            function Redir1() {
                document.getElementById("form").action = "crearcsv6.php";
                this.form.submit();
            }

            function Redir2() {
                document.getElementById("form").action = "print_ups.php";
                this.form.submit();
            }
        </script>
    </head>
    <body background="../images/fondo.jpg" class="dt">
        <div class="contenedor">
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
                                        <li><a href="./php/reenvioordenes.php" ><strong>Reenviar Órdenes</strong></a></li>
                                    <?php } ?> 

                                    <?php if ($rol <= 2) { ?>
                                        <li>
                                            <a tabindex="0" data-toggle="dropdown">
                                                <strong>Venta</strong><span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a href="crearorden.php"><strong>Punto de Venta</strong></a></li>
                                                <li class="divider"></li>
                                                <li class="dropdown-submenu">
                                                    <a tabindex="0" data-toggle="dropdown"><strong>Cargar</strong></a>            
                                                    <ul class="dropdown-menu">
                                                        <li><a href="gestionarordenes.php" ><strong>Modificar Órdenes</strong></a></li> 
                                                        <li class="divider"></li>
                                                        <li><a href="reenvioordenes.php" ><strong>Reenviar Órdenes</strong></a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                    <li class="active">
                                        <a tabindex="0" data-toggle="dropdown">
                                            <strong>Órdenes</strong><span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="filtros.php"><strong>Ver Órdenes</strong></a>

                                                <?php if ($rol <= 2) { ?>
                                                <li class="divider"></li>      
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
                                    }
//                                else{
//					$sql   = "SELECT id_usuario from tblusuario where cpuser='".$user."'";
//					$query = mysql_query($sql,$link);
//					$row = mysql_fetch_array($query);
//					echo '<li><a href="" onclick="cambiar(\''.$row[0].'\')"><strong>Contraseña</strong></a></li>';
//					
//					 }
                                    ?>	
                                </ul>
                                <ul class="nav navbar-nav navbar-right">
                                    <li><a class="navbar-brand" href="" data-toggle="tooltip" data-placement="bottom" title="Usuario conectado"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo $user ?></a></li>
                                    <li><a class="navbar-brand" href="../index.php" data-toggle="tooltip" data-placement="bottom" title="Salir del sistema" ><span class="glyphicon glyphicon-off" aria-hidden="true"></span></a></li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>

                <div class="panel-body" align="center">
                    <form id="form" action="" method="post" target="_blank">
                        <div class="row">
                            <div class="col-md-11">
                                <h3><strong>Listado de Agencias</strong></h3>
                            </div>


                            <?php
                            if ($rol <= 2)
                                echo '<div class="col-md-1">';
                            echo '		<input type=image src="../images/excel.gif" width="50" height="50" title="Exportar Archivo Excel"  onclick="Redir1()"/>';
                            echo '	<!--	<input type=image src="../images/shipping_label.png" width="40" height="40" title="Trackear e imprimir etiquetas" onclick="Redir2()"/> -->
                                
		   </div>';
// if($rol <= 2){
//    echo '<div class="col-md-1">
//           <img alt="Exportar Archivo Excel" src="../images/excel.png" width="25" height="25" id="reporteExcel">
//           <img alt="Trackear e imprimir etiquetas" src="../images/shipping_label.png" width="25" height="25" id="impEtiquetas"/>
//         </div>';
// }
                            ?>
                        </div>
                        <div class="table-responsive">

                            <table border="0" align="center" class="table table-condensed"  >
                                <?php
                                echo '<thead>';
                                echo '<tr>';
                                echo '<th align="center">' . "Estado" . '</th>';
                                echo '<th align="center">' . "Estado de Envío" . '</th>';
                                echo '<th align="center">' . "Reenvío" . '</th>';
                                echo '<th align="center">' . "Tracking" . '</th>';
                                echo '<th align="center">' . "Compañia" . '</th>';
                                echo '<th align="center">' . "eBinv" . '</th>';
                                echo '<th align="center">' . "Fecha de Órden" . '</th>';
                                echo '<th align="center">' . "Enviar a" . '</th>';
                                echo '<th align="center">' . "Enviar a2" . '</th>';
                                echo '<th align="center">' . "Dirección" . '</th>';
                                echo '<th align="center">' . "Dirección2" . '</th>';
                                echo '<th align="center">' . "Ciudad" . '</th>';
                                echo '<th align="center">' . "Estado" . '</th>';
                                echo '<th align="center">' . "Cod. Postal" . '</th>';
                                echo '<th align="center">' . "Teléfono" . '</th>';
                                echo '<th align="center">' . "Cobrar a" . '</th>';
                                echo '<th align="center">' . "Cobrar a2" . '</th>';
                                echo '<th align="center">' . "Teléfono" . '</th>';
                                echo '<th align="center">' . "Ponumber" . '</th>';
                                echo '<th align="center">' . "Custnumber" . '</th>';
                                echo '<th align="center">' . "Fecha de Vuelo" . '</th>';
                                echo '<th align="center">' . "Fecha de Entrega" . '</th>';
                                echo '<th align="center">' . "SatDel" . '</th>';
                                echo '<th align="center">' . "Cantidad" . '</th>';
                                echo '<th align="center">' . "Producto" . '</th>';
                                echo '<th align="center">' . "Desc. Prod." . '</th>';
                                echo '<th align="center">' . "Largo" . '</th>';
                                echo '<th align="center"s>' . "Ancho" . '</th>';
                                echo '<th align="center">' . "Alto" . '</th>';
                                echo '<th align="center">' . "Peso Kg" . '</th>';
                                echo '<th align="center">' . "Valor Dcl" . '</th>';
                                echo '<th align="center">' . "Mensaje" . '</th>';
                                echo '<th align="center">' . "MSG" . '</th>';
                                echo '<th align="center">' . "Servicio" . '</th>';
                                echo '<th align="center">' . "Tipo Pack" . '</th>';
                                echo '<th align="center">' . "GenDesc" . '</th>';
                                echo '<th align="center">' . "País de envío" . '</th>';
                                echo '<th align="center">' . "Moneda" . '</th>';
                                echo '<th align="center">' . "Origen" . '</th>';
                                echo '<th align="center">' . "UOM" . '</th>';
                                echo '<th align="center">' . "TPComp" . '</th>';
                                echo '<th align="center">' . "TPAttn" . '</th>';
                                echo '<th align="center">' . "TPAdd1" . '</th>';
                                echo '<th align="center">' . "TPCity" . '</th>';
                                echo '<th align="center">' . "TPState" . '</th>';
                                echo '<th align="center">' . "TPCtrye" . '</th>';
                                echo '<th align="center"s>' . "TPZip" . '</th>';
                                echo '<th align="center">' . "TPPhone" . '</th>';
                                echo '<th align="center">' . "TPAcct" . '</th>';
                                echo '<th align="center"s>' . "Finca" . '</th>';
                                echo "</tr>";
                                echo '</thead>';
                                echo '<tbody>';
//verificar el filtro para buscar
                                if ($fecha_inicio0 != null && $fecha_fin0 != null) {
                                    $sql = "select *
                FROM
                tblorden
                INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
                WHERE `delivery_traking` BETWEEN '" . $fecha_inicio0 . "' AND '" . $fecha_fin0 . "' AND status= 'New'";
                                    // Selecionar todos los campos de la base de datos para el excel a exportar
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sql = $sql . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sql = $sql . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }

                                    if ($pais != null) {
                                        $sql = $sql . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sql = $sql . " AND (`origen`= '" . $origen . "'";
                                    }

                                    //ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sql = $sql . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sql = $sql . ")";
                                    }

                                    $sqlrep = "select tracking,nombre_compania,eBing,order_date,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,Ponumber,Custnumber,ShipDT_traking,delivery_traking,satdel,cpcantidad,cpitem,prod_descripcion,length,width,heigth,wheigthKg,dclvalue,cpmensaje,cpservicio,cptipo_pack,gen_desc,cppais_envio,cpmoneda,cporigen,cpUOM,empresa,director,direccion_director,cuidad_director,estado_director,pais_director,tpzip_director,tpphone_director,tpacct_director,farm,cpmensaje AS mensaje2, estado_orden
            FROM
                tblorden
                INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
                WHERE `delivery_traking` BETWEEN '" . $fecha_inicio0 . "' AND '" . $fecha_fin0 . "' AND status= 'New'";
// Selecionar todos los campos de la base de datos para el excel a exportar
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sqlrep = $sqlrep . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sqlrep = $sqlrep . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }

                                    if ($pais != null) {
                                        $sqlrep = $sqlrep . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sqlrep = $sqlrep . " AND (`origen`= '" . $origen . "'";
                                    }

//ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sqlrep = $sqlrep . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sqlrep = $sqlrep . ")";
                                    }


                                    $query = mysql_query($sql);
                                    //$total = mysql_num_rows($query);
                                    while ($com = mysql_fetch_array($query)) {
                                        if ($rol == 1 || $rol == 2) {

                                            $total++;
                                            if ($com['estado_orden'] == 'Canceled') {
                                                echo '<tr bgcolor="#CC0000" style="color:white;">';
                                            } else {
                                                echo "<tr>";
                                            }
                                            echo "<td>" . $com['estado_orden'] . " </td>"; //ya
                                            echo "<td>" . $com['status'] . " </td>"; //ya
                                            echo "<td>" . $com['reenvio'] . " </td>"; //ya		
                                            echo "<td>" . $com['tracking'] . " </td>"; //ya	
                                            echo "<td>" . $com['nombre_compania'] . " </td>"; //ya
                                            echo "<td>" . $com['eBing'] . " </td>"; //ya
                                            echo "<td>" . $com['order_date'] . " </td>"; //ya
                                            echo "<td>" . $com['shipto1'] . " </td>"; //ya
                                            echo "<td>" . $com['shipto2'] . " </td>"; //ya
                                            echo "<td>" . $com['direccion'] . " </td>"; //ya
                                            echo "<td>" . $com['direccion2'] . " </td>"; //ya
                                            echo "<td>" . $com['cpcuidad_shipto'] . " </td>"; //ya
                                            echo "<td>" . $com['cpestado_shipto'] . " </td>"; //ya
                                            echo "<td>" . $com['cpzip_shipto'] . " </td>"; //ya
                                            echo "<td>" . $com['cptelefono_shipto'] . " </td>"; //ya
                                            echo "<td>" . $com['soldto1'] . " </td>"; //ya
                                            echo "<td>" . $com['soldto2'] . " </td>"; //ya
                                            echo "<td>" . $com['cpstphone_soldto'] . " </td>"; //ya
                                            echo "<td>" . $com['Ponumber'] . " </td>"; //ya
                                            echo "<td>" . $com['Custnumber'] . " </td>"; //ya
                                            echo "<td>" . $com['ShipDT_traking'] . " </td>"; //ya
                                            echo "<td>" . $com['delivery_traking'] . " </td>"; //ya
                                            echo "<td>" . $com['satdel'] . " </td>"; //ya
                                            echo "<td>" . $com['cpcantidad'] . " </td>"; //ya		
                                            echo "<td>" . $com['cpitem'] . " </td>"; //ya
                                            echo "<td>" . $com['prod_descripcion'] . " </td>"; //ya
                                            echo "<td>" . $com['length'] . " </td>"; //ya
                                            echo "<td>" . $com['width'] . " </td>"; //ya
                                            echo "<td>" . $com['heigth'] . " </td>"; //ya
                                            echo "<td>" . $com['wheigthKg'] . " </td>"; //ya
                                            echo "<td>" . $com['dclvalue'] . " </td>"; //ya
                                            echo "<td>" . $com['cpmensaje'] . " </td>"; //ya
                                            echo "<td>";
                                            echo $com['cpmensaje'] == 'To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N' : 'Y';
                                            echo "</td>"; //ya

                                            echo "<td>" . $com['cpservicio'] . " </td>"; //ya
                                            echo "<td>" . $com['cptipo_pack'] . " </td>"; //ya
                                            echo "<td>" . $com['gen_desc'] . " </td>"; //ya
                                            echo "<td>" . $com['cppais_envio'] . " </td>"; //ya
                                            echo "<td>" . $com['cpmoneda'] . " </td>"; //ya
                                            echo "<td>" . $com['cporigen'] . " </td>"; //ya		
                                            echo "<td>" . $com['cpUOM'] . " </td>"; //ya
                                            echo "<td>" . $com['empresa'] . " </td>"; //ya
                                            echo "<td>" . $com['director'] . " </td>"; //ya
                                            echo "<td>" . $com['direccion_director'] . " </td>"; //ya
                                            echo "<td>" . $com['cuidad_director'] . " </td>"; //ya
                                            echo "<td>" . $com['estado_director'] . " </td>"; //ya
                                            echo "<td>" . $com['pais_director'] . " </td>"; //ya
                                            echo "<td>" . $com['tpzip_director'] . " </td>"; //ya
                                            echo "<td>" . $com['tpphone_director'] . " </td>"; //ya
                                            echo "<td>" . $com['tpacct_director'] . " </td>"; //ya
                                            echo "<td>" . $com['farm'] . " </td>"; //ya
                                            echo "</tr>";
                                        } else {
                                            if (strcmp($com['status'], 'New') == 0) {
                                                $total++;
                                                if ($com['estado_orden'] == 'Canceled') {
                                                    echo '<tr bgcolor="#CC0000" style="color:white;">';
                                                } else {
                                                    echo "<tr>";
                                                }
                                                echo "<td>" . $com['estado_orden'] . " </td>"; //ya
                                                echo "<td>" . $com['status'] . " </td>"; //ya	
                                                echo "<td>" . $com['reenvio'] . " </td>"; //ya
                                                echo "<td>" . $com['tracking'] . " </td>"; //ya	
                                                echo "<td>" . $com['nombre_compania'] . " </td>"; //ya
                                                echo "<td>" . $com['eBing'] . " </td>"; //ya
                                                echo "<td>" . $com['order_date'] . " </td>"; //ya
                                                echo "<td>" . $com['shipto1'] . " </td>"; //ya
                                                echo "<td>" . $com['shipto2'] . " </td>"; //ya
                                                echo "<td>" . $com['direccion'] . " </td>"; //ya
                                                echo "<td>" . $com['direccion2'] . " </td>"; //ya
                                                echo "<td>" . $com['cpcuidad_shipto'] . " </td>"; //ya
                                                echo "<td>" . $com['cpestado_shipto'] . " </td>"; //ya
                                                echo "<td>" . $com['cpzip_shipto'] . " </td>"; //ya
                                                echo "<td>" . $com['cptelefono_shipto'] . " </td>"; //ya
                                                echo "<td>" . $com['soldto1'] . " </td>"; //ya
                                                echo "<td>" . $com['soldto2'] . " </td>"; //ya
                                                echo "<td>" . $com['cpstphone_soldto'] . " </td>"; //ya
                                                echo "<td>" . $com['Ponumber'] . " </td>"; //ya
                                                echo "<td>" . $com['Custnumber'] . " </td>"; //ya
                                                echo "<td>" . $com['ShipDT_traking'] . " </td>"; //ya
                                                echo "<td>" . $com['delivery_traking'] . " </td>"; //ya
                                                echo "<td>" . $com['satdel'] . " </td>"; //ya
                                                echo "<td>" . $com['cpcantidad'] . " </td>"; //ya		
                                                echo "<td>" . $com['cpitem'] . " </td>"; //ya
                                                echo "<td>" . $com['prod_descripcion'] . " </td>"; //ya
                                                echo "<td>" . $com['length'] . " </td>"; //ya
                                                echo "<td>" . $com['width'] . " </td>"; //ya
                                                echo "<td>" . $com['heigth'] . " </td>"; //ya
                                                echo "<td>" . $com['wheigthKg'] . " </td>"; //ya
                                                echo "<td>" . $com['dclvalue'] . " </td>"; //ya
                                                echo "<td>" . $com['cpmensaje'] . " </td>"; //ya
                                                echo "<td>";
                                                echo $com['cpmensaje'] == 'To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N' : 'Y';
                                                echo "</td>"; //ya
                                                echo "<td>" . $com['cpservicio'] . " </td>"; //ya
                                                echo "<td>" . $com['cptipo_pack'] . " </td>"; //ya
                                                echo "<td>" . $com['gen_desc'] . " </td>"; //ya
                                                echo "<td>" . $com['cppais_envio'] . " </td>"; //ya
                                                echo "<td>" . $com['cpmoneda'] . " </td>"; //ya
                                                echo "<td>" . $com['cporigen'] . " </td>"; //ya		
                                                echo "<td>" . $com['cpUOM'] . " </td>"; //ya
                                                echo "<td>" . $com['empresa'] . " </td>"; //ya
                                                echo "<td>" . $com['director'] . " </td>"; //ya
                                                echo "<td>" . $com['direccion_director'] . " </td>"; //ya
                                                echo "<td>" . $com['cuidad_director'] . " </td>"; //ya
                                                echo "<td>" . $com['estado_director'] . " </td>"; //ya
                                                echo "<td>" . $com['pais_director'] . " </td>"; //ya
                                                echo "<td>" . $com['tpzip_director'] . " </td>"; //ya
                                                echo "<td>" . $com['tpphone_director'] . " </td>"; //ya
                                                echo "<td>" . $com['tpacct_director'] . " </td>"; //ya
                                                echo "<td>" . $com['farm'] . " </td>"; //ya
                                                echo "</tr>";
                                            }
                                        }
                                    }
                                    session_destroy();
                                    session_start();
                                    $_SESSION["sqlrep"] = $sqlrep;
                                    $_SESSION["sql"] = $sql;
                                    $_SESSION["login"] = $user;
                                    $_SESSION["rol"] = $rol;
                                    $_SESSION["pais"] = $pais;
                                }

                                //verificar el filtro para buscar
                                if ($fecha_inicio != null && $fecha_fin != null) {
                                    $sql = "select *
                    FROM
                    tblorden
                    INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                    INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                    INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                    INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                    INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
                    WHERE `order_date` BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sql = $sql . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sql = $sql . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }

                                    if ($pais != null) {
                                        $sql = $sql . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sql = $sql . " AND (`origen`= '" . $origen . "'";
                                    }

                                    //ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sql = $sql . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sql = $sql . ")";
                                    }

                                    $sqlrep = "select tracking,nombre_compania,eBing,order_date,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,Ponumber,Custnumber,ShipDT_traking,delivery_traking,satdel,cpcantidad,cpitem,prod_descripcion,length,width,heigth,wheigthKg,dclvalue,cpmensaje,cpservicio,cptipo_pack,gen_desc,cppais_envio,cpmoneda,cporigen,cpUOM,empresa,director,direccion_director,cuidad_director,estado_director,pais_director,tpzip_director,tpphone_director,tpacct_director,farm,cpmensaje AS mensaje2, estado_orden
            FROM
                    tblorden
                    INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                    INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                    INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                    INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                    INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
                    WHERE `order_date` BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sqlrep = $sqlrep . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sqlrep = $sqlrep . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }

                                    if ($pais != null) {
                                        $sqlrep = $sqlrep . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sqlrep = $sqlrep . " AND (`origen`= '" . $origen . "'";
                                    }

//ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sqlrep = $sqlrep . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sqlrep = $sqlrep . ")";
                                    }

                                    $query = mysql_query($sql);
                                    //$total = mysql_num_rows($query);
                                    while ($com = mysql_fetch_array($query)) {
                                        if ($rol == 1 || $rol == 2) {
                                            //echo "entro aqui";
                                            $total++;
                                            if ($com['estado_orden'] == 'Canceled') {
                                                echo '<tr bgcolor="#CC0000" style="color:white;">';
                                            } else {
                                                echo "<tr>";
                                            }
                                            echo "<td>" . $com['estado_orden'] . " </td>"; //ya
                                            echo "<td>" . $com['status'] . " </td>"; //ya
                                            echo "<td>" . $com['reenvio'] . " </td>"; //ya	
                                            echo "<td>" . $com['tracking'] . " </td>"; //ya	
                                            echo "<td>" . $com['nombre_compania'] . " </td>"; //ya
                                            echo "<td>" . $com['eBing'] . " </td>"; //ya
                                            echo "<td>" . $com['order_date'] . " </td>"; //ya
                                            echo "<td>" . $com['shipto1'] . " </td>"; //ya
                                            echo "<td>" . $com['shipto2'] . " </td>"; //ya
                                            echo "<td>" . $com['direccion'] . " </td>"; //ya
                                            echo "<td>" . $com['direccion2'] . " </td>"; //ya
                                            echo "<td>" . $com['cpcuidad_shipto'] . " </td>"; //ya
                                            echo "<td>" . $com['cpestado_shipto'] . " </td>"; //ya
                                            echo "<td>" . $com['cpzip_shipto'] . " </td>"; //ya
                                            echo "<td>" . $com['cptelefono_shipto'] . " </td>"; //ya
                                            echo "<td>" . $com['soldto1'] . " </td>"; //ya
                                            echo "<td>" . $com['soldto2'] . " </td>"; //ya
                                            echo "<td>" . $com['cpstphone_soldto'] . " </td>"; //ya
                                            echo "<td>" . $com['Ponumber'] . " </td>"; //ya
                                            echo "<td>" . $com['Custnumber'] . " </td>"; //ya
                                            echo "<td>" . $com['ShipDT_traking'] . " </td>"; //ya
                                            echo "<td>" . $com['delivery_traking'] . " </td>"; //ya
                                            echo "<td>" . $com['satdel'] . " </td>"; //ya
                                            echo "<td>" . $com['cpcantidad'] . " </td>"; //ya		
                                            echo "<td>" . $com['cpitem'] . " </td>"; //ya
                                            echo "<td>" . $com['prod_descripcion'] . " </td>"; //ya
                                            echo "<td>" . $com['length'] . " </td>"; //ya
                                            echo "<td>" . $com['width'] . " </td>"; //ya
                                            echo "<td>" . $com['heigth'] . " </td>"; //ya
                                            echo "<td>" . $com['wheigthKg'] . " </td>"; //ya
                                            echo "<td>" . $com['dclvalue'] . " </td>"; //ya
                                            echo "<td>" . $com['cpmensaje'] . " </td>"; //ya
                                            echo "<td>";
                                            echo $com['cpmensaje'] == 'To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N' : 'Y';
                                            echo "</td>"; //ya
                                            echo "<td>" . $com['cpservicio'] . " </td>"; //ya
                                            echo "<td>" . $com['cptipo_pack'] . " </td>"; //ya
                                            echo "<td>" . $com['gen_desc'] . " </td>"; //ya
                                            echo "<td>" . $com['cppais_envio'] . " </td>"; //ya
                                            echo "<td>" . $com['cpmoneda'] . " </td>"; //ya
                                            echo "<td>" . $com['cporigen'] . " </td>"; //ya		
                                            echo "<td>" . $com['cpUOM'] . " </td>"; //ya
                                            echo "<td>" . $com['empresa'] . " </td>"; //ya
                                            echo "<td>" . $com['director'] . " </td>"; //ya
                                            echo "<td>" . $com['direccion_director'] . " </td>"; //ya
                                            echo "<td>" . $com['cuidad_director'] . " </td>"; //ya
                                            echo "<td>" . $com['estado_director'] . " </td>"; //ya
                                            echo "<td>" . $com['pais_director'] . " </td>"; //ya
                                            echo "<td>" . $com['tpzip_director'] . " </td>"; //ya
                                            echo "<td>" . $com['tpphone_director'] . " </td>"; //ya
                                            echo "<td>" . $com['tpacct_director'] . " </td>"; //ya
                                            echo "<td>" . $com['farm'] . " </td>"; //ya
                                            echo "</tr>";
                                        } else {
                                            if (strcmp($com['status'], 'Ready to ship') == 0 || strcmp($com['status'], 'New') == 0) {
                                                $total++;
                                                if ($com['estado_orden'] == 'Canceled') {
                                                    echo '<tr bgcolor="#CC0000" style="color:white;">';
                                                } else {
                                                    echo "<tr>";
                                                }
                                                echo "<td>" . $com['estado_orden'] . " </td>"; //ya
                                                echo "<td>" . $com['status'] . " </td>"; //ya	
                                                echo "<td>" . $com['reenvio'] . " </td>"; //ya
                                                echo "<td>" . $com['tracking'] . " </td>"; //ya	
                                                echo "<td>" . $com['nombre_compania'] . " </td>"; //ya
                                                echo "<td>" . $com['eBing'] . " </td>"; //ya
                                                echo "<td>" . $com['order_date'] . " </td>"; //ya
                                                echo "<td>" . $com['shipto1'] . " </td>"; //ya
                                                echo "<td>" . $com['shipto2'] . " </td>"; //ya
                                                echo "<td>" . $com['direccion'] . " </td>"; //ya
                                                echo "<td>" . $com['direccion2'] . " </td>"; //ya
                                                echo "<td>" . $com['cpcuidad_shipto'] . " </td>"; //ya
                                                echo "<td>" . $com['cpestado_shipto'] . " </td>"; //ya
                                                echo "<td>" . $com['cpzip_shipto'] . " </td>"; //ya
                                                echo "<td>" . $com['cptelefono_shipto'] . " </td>"; //ya
                                                echo "<td>" . $com['soldto1'] . " </td>"; //ya
                                                echo "<td>" . $com['soldto2'] . " </td>"; //ya
                                                echo "<td>" . $com['cpstphone_soldto'] . " </td>"; //ya
                                                echo "<td>" . $com['Ponumber'] . " </td>"; //ya
                                                echo "<td>" . $com['Custnumber'] . " </td>"; //ya
                                                echo "<td>" . $com['ShipDT_traking'] . " </td>"; //ya
                                                echo "<td>" . $com['delivery_traking'] . " </td>"; //ya
                                                echo "<td>" . $com['satdel'] . " </td>"; //ya
                                                echo "<td>" . $com['cpcantidad'] . " </td>"; //ya		
                                                echo "<td>" . $com['cpitem'] . " </td>"; //ya
                                                echo "<td>" . $com['prod_descripcion'] . " </td>"; //ya
                                                echo "<td>" . $com['length'] . " </td>"; //ya
                                                echo "<td>" . $com['width'] . " </td>"; //ya
                                                echo "<td>" . $com['heigth'] . " </td>"; //ya
                                                echo "<td>" . $com['wheigthKg'] . " </td>"; //ya
                                                echo "<td>" . $com['dclvalue'] . " </td>"; //ya
                                                echo "<td>" . $com['cpmensaje'] . " </td>"; //ya
                                                echo "<td>";
                                                echo $com['cpmensaje'] == 'To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N' : 'Y';
                                                echo "</td>"; //ya
                                                echo "<td>" . $com['cpservicio'] . " </td>"; //ya
                                                echo "<td>" . $com['cptipo_pack'] . " </td>"; //ya
                                                echo "<td>" . $com['gen_desc'] . " </td>"; //ya
                                                echo "<td>" . $com['cppais_envio'] . " </td>"; //ya
                                                echo "<td>" . $com['cpmoneda'] . " </td>"; //ya
                                                echo "<td>" . $com['cporigen'] . " </td>"; //ya		
                                                echo "<td>" . $com['cpUOM'] . " </td>"; //ya
                                                echo "<td>" . $com['empresa'] . " </td>"; //ya
                                                echo "<td>" . $com['director'] . " </td>"; //ya
                                                echo "<td>" . $com['direccion_director'] . " </td>"; //ya
                                                echo "<td>" . $com['cuidad_director'] . " </td>"; //ya
                                                echo "<td>" . $com['estado_director'] . " </td>"; //ya
                                                echo "<td>" . $com['pais_director'] . " </td>"; //ya
                                                echo "<td>" . $com['tpzip_director'] . " </td>"; //ya
                                                echo "<td>" . $com['tpphone_director'] . " </td>"; //ya
                                                echo "<td>" . $com['tpacct_director'] . " </td>"; //ya
                                                echo "<td>" . $com['farm'] . " </td>"; //ya
                                                echo "</tr>";
                                            }
                                        }
                                    }
                                    session_destroy();
                                    session_start();
                                    $_SESSION["sqlrep"] = $sqlrep;
                                    $_SESSION["sql"] = $sql;
                                    $_SESSION["login"] = $user;
                                    $_SESSION["rol"] = $rol;
                                    $_SESSION["pais"] = $pais;
                                }

                                //verificar el filtro para buscar
                                if ($fecha_inicio1 != null && $fecha_fin1 != null) {
                                    //echo ("Fecha 2");	
                                    // Selecionar todos los campos de la base de datops para el excel a exportar
                                    $sql = "select *
                FROM
                tblorden
                INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
                WHERE `ShipDT_traking` BETWEEN '" . $fecha_inicio1 . "' AND '" . $fecha_fin1 . "'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sql = $sql . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sql = $sql . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }

                                    if ($pais != null) {
                                        $sql = $sql . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sql = $sql . " AND (`origen`= '" . $origen . "'";
                                    }

                                    //ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sql = $sql . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sql = $sql . ")";
                                    }

                                    $sqlrep = "select tracking,nombre_compania,eBing,order_date,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,Ponumber,Custnumber,ShipDT_traking,delivery_traking,satdel,cpcantidad,cpitem,prod_descripcion,length,width,heigth,wheigthKg,dclvalue,cpmensaje,cpservicio,cptipo_pack,gen_desc,cppais_envio,cpmoneda,cporigen,cpUOM,empresa,director,direccion_director,cuidad_director,estado_director,pais_director,tpzip_director,tpphone_director,tpacct_director,farm,cpmensaje AS mensaje2, estado_orden
            FROM
                tblorden
                INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
                WHERE `ShipDT_traking` BETWEEN '" . $fecha_inicio1 . "' AND '" . $fecha_fin1 . "'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sqlrep = $sqlrep . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sqlrep = $sqlrep . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }

                                    if ($pais != null) {
                                        $sqlrep = $sqlrep . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sqlrep = $sqlrep . " AND (`origen`= '" . $origen . "'";
                                    }

//ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sqlrep = $sqlrep . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sqlrep = $sqlrep . ")";
                                    }

                                    $query = mysql_query($sql);
                                    //$total = mysql_num_rows($query);
                                    while ($com = mysql_fetch_array($query)) {
                                        if ($rol == 1 || $rol == 2) {
                                            $total++;
                                            if ($com['estado_orden'] == 'Canceled') {
                                                echo '<tr bgcolor="#CC0000" style="color:white;">';
                                            } else {
                                                echo "<tr>";
                                            }
                                            echo "<td>" . $com['estado_orden'] . " </td>"; //ya
                                            echo "<td>" . $com['status'] . " </td>"; //ya
                                            echo "<td>" . $com['reenvio'] . " </td>"; //ya		
                                            echo "<td>" . $com['tracking'] . " </td>"; //ya	
                                            echo "<td>" . $com['nombre_compania'] . " </td>"; //ya
                                            echo "<td>" . $com['eBing'] . " </td>"; //ya
                                            echo "<td>" . $com['order_date'] . " </td>"; //ya
                                            echo "<td>" . $com['shipto1'] . " </td>"; //ya
                                            echo "<td>" . $com['shipto2'] . " </td>"; //ya
                                            echo "<td>" . $com['direccion'] . " </td>"; //ya
                                            echo "<td>" . $com['direccion2'] . " </td>"; //ya
                                            echo "<td>" . $com['cpcuidad_shipto'] . " </td>"; //ya
                                            echo "<td>" . $com['cpestado_shipto'] . " </td>"; //ya
                                            echo "<td>" . $com['cpzip_shipto'] . " </td>"; //ya
                                            echo "<td>" . $com['cptelefono_shipto'] . " </td>"; //ya
                                            echo "<td>" . $com['soldto1'] . " </td>"; //ya
                                            echo "<td>" . $com['soldto2'] . " </td>"; //ya
                                            echo "<td>" . $com['cpstphone_soldto'] . " </td>"; //ya
                                            echo "<td>" . $com['Ponumber'] . " </td>"; //ya
                                            echo "<td>" . $com['Custnumber'] . " </td>"; //ya
                                            echo "<td>" . $com['ShipDT_traking'] . " </td>"; //ya
                                            echo "<td>" . $com['delivery_traking'] . " </td>"; //ya
                                            echo "<td>" . $com['satdel'] . " </td>"; //ya
                                            echo "<td>" . $com['cpcantidad'] . " </td>"; //ya		
                                            echo "<td>" . $com['cpitem'] . " </td>"; //ya
                                            echo "<td>" . $com['prod_descripcion'] . " </td>"; //ya
                                            echo "<td>" . $com['length'] . " </td>"; //ya
                                            echo "<td>" . $com['width'] . " </td>"; //ya
                                            echo "<td>" . $com['heigth'] . " </td>"; //ya
                                            echo "<td>" . $com['wheigthKg'] . " </td>"; //ya
                                            echo "<td>" . $com['dclvalue'] . " </td>"; //ya
                                            echo "<td>" . $com['cpmensaje'] . " </td>"; //ya
                                            echo "<td>";
                                            echo $com['cpmensaje'] == 'To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N' : 'Y';
                                            echo "</td>"; //ya
                                            echo "<td>" . $com['cpservicio'] . " </td>"; //ya
                                            echo "<td>" . $com['cptipo_pack'] . " </td>"; //ya
                                            echo "<td>" . $com['gen_desc'] . " </td>"; //ya
                                            echo "<td>" . $com['cppais_envio'] . " </td>"; //ya
                                            echo "<td>" . $com['cpmoneda'] . " </td>"; //ya
                                            echo "<td>" . $com['cporigen'] . " </td>"; //ya		
                                            echo "<td>" . $com['cpUOM'] . " </td>"; //ya
                                            echo "<td>" . $com['empresa'] . " </td>"; //ya
                                            echo "<td>" . $com['director'] . " </td>"; //ya
                                            echo "<td>" . $com['direccion_director'] . " </td>"; //ya
                                            echo "<td>" . $com['cuidad_director'] . " </td>"; //ya
                                            echo "<td>" . $com['estado_director'] . " </td>"; //ya
                                            echo "<td>" . $com['pais_director'] . " </td>"; //ya
                                            echo "<td>" . $com['tpzip_director'] . " </td>"; //ya
                                            echo "<td>" . $com['tpphone_director'] . " </td>"; //ya
                                            echo "<td>" . $com['tpacct_director'] . " </td>"; //ya
                                            echo "<td>" . $com['farm'] . " </td>"; //ya
                                            echo "</tr>";
                                        } else {
                                            if (strcmp($com['status'], 'Ready to ship') == 0 || strcmp($com['status'], 'New') == 0) {
                                                $total++;
                                                if ($com['estado_orden'] == 'Canceled') {
                                                    echo '<tr bgcolor="#CC0000">';
                                                } else {
                                                    echo "<tr>";
                                                }
                                                echo "<td>" . $com['estado_orden'] . " </td>"; //ya
                                                echo "<td>" . $com['status'] . " </td>"; //ya		
                                                echo "<td>" . $com['reenvio'] . " </td>"; //ya	
                                                echo "<td>" . $com['tracking'] . " </td>"; //ya		
                                                echo "<td>" . $com['nombre_compania'] . " </td>"; //ya
                                                echo "<td>" . $com['eBing'] . " </td>"; //ya
                                                echo "<td>" . $com['order_date'] . " </td>"; //ya
                                                echo "<td>" . $com['shipto1'] . " </td>"; //ya
                                                echo "<td>" . $com['shipto2'] . " </td>"; //ya
                                                echo "<td>" . $com['direccion'] . " </td>"; //ya
                                                echo "<td>" . $com['direccion2'] . " </td>"; //ya
                                                echo "<td>" . $com['cpcuidad_shipto'] . " </td>"; //ya
                                                echo "<td>" . $com['cpestado_shipto'] . " </td>"; //ya
                                                echo "<td>" . $com['cpzip_shipto'] . " </td>"; //ya
                                                echo "<td>" . $com['cptelefono_shipto'] . " </td>"; //ya
                                                echo "<td>" . $com['soldto1'] . " </td>"; //ya
                                                echo "<td>" . $com['soldto2'] . " </td>"; //ya
                                                echo "<td>" . $com['cpstphone_soldto'] . " </td>"; //ya
                                                echo "<td>" . $com['Ponumber'] . " </td>"; //ya
                                                echo "<td>" . $com['Custnumber'] . " </td>"; //ya
                                                echo "<td>" . $com['ShipDT_traking'] . " </td>"; //ya
                                                echo "<td>" . $com['delivery_traking'] . " </td>"; //ya
                                                echo "<td>" . $com['satdel'] . " </td>"; //ya
                                                echo "<td>" . $com['cpcantidad'] . " </td>"; //ya		
                                                echo "<td>" . $com['cpitem'] . " </td>"; //ya
                                                echo "<td>" . $com['prod_descripcion'] . " </td>"; //ya
                                                echo "<td>" . $com['length'] . " </td>"; //ya
                                                echo "<td>" . $com['width'] . " </td>"; //ya
                                                echo "<td>" . $com['heigth'] . " </td>"; //ya
                                                echo "<td>" . $com['wheigthKg'] . " </td>"; //ya
                                                echo "<td>" . $com['dclvalue'] . " </td>"; //ya
                                                echo "<td>" . $com['cpmensaje'] . " </td>"; //ya
                                                echo "<td>";
                                                echo $com['cpmensaje'] == 'To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N' : 'Y';
                                                echo "</td>"; //ya
                                                echo "<td>" . $com['cpservicio'] . " </td>"; //ya
                                                echo "<td>" . $com['cptipo_pack'] . " </td>"; //ya
                                                echo "<td>" . $com['gen_desc'] . " </td>"; //ya
                                                echo "<td>" . $com['cppais_envio'] . " </td>"; //ya
                                                echo "<td>" . $com['cpmoneda'] . " </td>"; //ya
                                                echo "<td>" . $com['cporigen'] . " </td>"; //ya		
                                                echo "<td>" . $com['cpUOM'] . " </td>"; //ya
                                                echo "<td>" . $com['empresa'] . " </td>"; //ya
                                                echo "<td>" . $com['director'] . " </td>"; //ya
                                                echo "<td>" . $com['direccion_director'] . " </td>"; //ya
                                                echo "<td>" . $com['cuidad_director'] . " </td>"; //ya
                                                echo "<td>" . $com['estado_director'] . " </td>"; //ya
                                                echo "<td>" . $com['pais_director'] . " </td>"; //ya
                                                echo "<td>" . $com['tpzip_director'] . " </td>"; //ya
                                                echo "<td>" . $com['tpphone_director'] . " </td>"; //ya
                                                echo "<td>" . $com['tpacct_director'] . " </td>"; //ya
                                                echo "<td>" . $com['farm'] . " </td>"; //ya
                                                echo "</tr>";
                                            }
                                        }
                                    }
                                    session_destroy();
                                    session_start();
                                    $_SESSION["sqlrep"] = $sqlrep;
                                    $_SESSION["sql"] = $sql;
                                    $_SESSION["login"] = $user;
                                    $_SESSION["rol"] = $rol;
                                    $_SESSION["pais"] = $pais;
                                }

                                //verificar el filtro para buscar
                                if ($fecha_inicio2 != null && $fecha_fin2 != null) {
                                    //echo ("Fecha 3");	
                                    // Selecionar todos los campos de la base de datops para el excel a exportar
                                    $sql = "SELECT *
            FROM
            tblorden
            INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
            INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
            INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
            INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
            INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
            WHERE `delivery_traking` BETWEEN '" . $fecha_inicio2 . "' AND '" . $fecha_fin2 . "'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sql = $sql . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sql = $sql . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }

                                    if ($pais != null) {
                                        $sql = $sql . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sql = $sql . " AND (`origen`= '" . $origen . "'";
                                    }

                                    //ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sql = $sql . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sql = $sql . ")";
                                    }

                                    //Query para el reporte que se envia a excel
                                    $sqlrep = "select tracking,nombre_compania,eBing,order_date,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,Ponumber,Custnumber,ShipDT_traking,delivery_traking,satdel,cpcantidad,cpitem,prod_descripcion,length,width,heigth,wheigthKg,dclvalue,cpmensaje,cpservicio,cptipo_pack,gen_desc,cppais_envio,cpmoneda,cporigen,cpUOM,empresa,director,direccion_director,cuidad_director,estado_director,pais_director,tpzip_director,tpphone_director,tpacct_director,farm,cpmensaje AS mensaje2, estado_orden
            FROM
            tblorden
            INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
            INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
            INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
            INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
            INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
            WHERE `delivery_traking` BETWEEN '" . $fecha_inicio2 . "' AND '" . $fecha_fin2 . "'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sqlrep = $sqlrep . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sqlrep = $sqlrep . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }

                                    if ($pais != null) {
                                        $sqlrep = $sqlrep . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sqlrep = $sqlrep . " AND (`origen`= '" . $origen . "'";
                                    }

                                    //ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sqlrep = $sqlrep . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sqlrep = $sqlrep . ")";
                                    }



                                    $query = mysql_query($sql);
                                    //$total = mysql_num_rows($query);
                                    while ($com = mysql_fetch_array($query)) {
                                        if ($rol == 1 || $rol == 2) {
                                            $total++;
                                            if ($com['estado_orden'] == 'Canceled') {
                                                echo '<tr bgcolor="#CC0000" style="color:white;">';
                                            } else {
                                                echo "<tr>";
                                            }
                                            echo "<td>" . $com['estado_orden'] . " </td>"; //ya
                                            echo "<td>" . $com['status'] . " </td>"; //ya
                                            echo "<td>" . $com['reenvio'] . " </td>"; //ya	
                                            echo "<td>" . $com['tracking'] . " </td>"; //ya	
                                            echo "<td>" . $com['nombre_compania'] . " </td>"; //ya
                                            echo "<td>" . $com['eBing'] . " </td>"; //ya
                                            echo "<td>" . $com['order_date'] . " </td>"; //ya
                                            echo "<td>" . $com['shipto1'] . " </td>"; //ya
                                            echo "<td>" . $com['shipto2'] . " </td>"; //ya
                                            echo "<td>" . $com['direccion'] . " </td>"; //ya
                                            echo "<td>" . $com['direccion2'] . " </td>"; //ya
                                            echo "<td>" . $com['cpcuidad_shipto'] . " </td>"; //ya
                                            echo "<td>" . $com['cpestado_shipto'] . " </td>"; //ya
                                            echo "<td>" . $com['cpzip_shipto'] . " </td>"; //ya
                                            echo "<td>" . $com['cptelefono_shipto'] . " </td>"; //ya
                                            echo "<td>" . $com['soldto1'] . " </td>"; //ya
                                            echo "<td>" . $com['soldto2'] . " </td>"; //ya
                                            echo "<td>" . $com['cpstphone_soldto'] . " </td>"; //ya
                                            echo "<td>" . $com['Ponumber'] . " </td>"; //ya
                                            echo "<td>" . $com['Custnumber'] . " </td>"; //ya
                                            echo "<td>" . $com['ShipDT_traking'] . " </td>"; //ya
                                            echo "<td>" . $com['delivery_traking'] . " </td>"; //ya
                                            echo "<td>" . $com['satdel'] . " </td>"; //ya
                                            echo "<td>" . $com['cpcantidad'] . " </td>"; //ya		
                                            echo "<td>" . $com['cpitem'] . " </td>"; //ya
                                            echo "<td>" . $com['prod_descripcion'] . " </td>"; //ya
                                            echo "<td>" . $com['length'] . " </td>"; //ya
                                            echo "<td>" . $com['width'] . " </td>"; //ya
                                            echo "<td>" . $com['heigth'] . " </td>"; //ya
                                            echo "<td>" . $com['wheigthKg'] . " </td>"; //ya
                                            echo "<td>" . $com['dclvalue'] . " </td>"; //ya
                                            echo "<td>" . $com['cpmensaje'] . " </td>"; //ya
                                            echo "<td>";
                                            echo $com['cpmensaje'] == 'To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N' : 'Y';
                                            echo "</td>"; //ya
                                            echo "<td>" . $com['cpservicio'] . " </td>"; //ya
                                            echo "<td>" . $com['cptipo_pack'] . " </td>"; //ya
                                            echo "<td>" . $com['gen_desc'] . " </td>"; //ya
                                            echo "<td>" . $com['cppais_envio'] . " </td>"; //ya
                                            echo "<td>" . $com['cpmoneda'] . " </td>"; //ya
                                            echo "<td>" . $com['cporigen'] . " </td>"; //ya		
                                            echo "<td>" . $com['cpUOM'] . " </td>"; //ya
                                            echo "<td>" . $com['empresa'] . " </td>"; //ya
                                            echo "<td>" . $com['director'] . " </td>"; //ya
                                            echo "<td>" . $com['direccion_director'] . " </td>"; //ya
                                            echo "<td>" . $com['cuidad_director'] . " </td>"; //ya
                                            echo "<td>" . $com['estado_director'] . " </td>"; //ya
                                            echo "<td>" . $com['pais_director'] . " </td>"; //ya
                                            echo "<td>" . $com['tpzip_director'] . " </td>"; //ya
                                            echo "<td>" . $com['tpphone_director'] . " </td>"; //ya
                                            echo "<td>" . $com['tpacct_director'] . " </td>"; //ya
                                            echo "<td>" . $com['farm'] . " </td>"; //ya
                                            echo "</tr>";
                                        } else {
                                            if (strcmp($com['status'], 'Ready to ship') == 0 || strcmp($com['status'], 'New') == 0) {
                                                $total++;
                                                if ($com['estado_orden'] == 'Canceled') {
                                                    echo '<tr bgcolor="#CC0000" style="color:white;">';
                                                } else {
                                                    echo "<tr>";
                                                }
                                                echo "<td>" . $com['estado_orden'] . " </td>"; //ya
                                                echo "<td>" . $com['status'] . " </td>"; //ya			
                                                echo "<td>" . $com['reenvio'] . " </td>"; //ya
                                                echo "<td>" . $com['tracking'] . " </td>"; //ya				
                                                echo "<td>" . $com['nombre_compania'] . " </td>"; //ya
                                                echo "<td>" . $com['eBing'] . " </td>"; //ya
                                                echo "<td>" . $com['order_date'] . " </td>"; //ya
                                                echo "<td>" . $com['shipto1'] . " </td>"; //ya
                                                echo "<td>" . $com['shipto2'] . " </td>"; //ya
                                                echo "<td>" . $com['direccion'] . " </td>"; //ya
                                                echo "<td>" . $com['direccion2'] . " </td>"; //ya
                                                echo "<td>" . $com['cpcuidad_shipto'] . " </td>"; //ya
                                                echo "<td>" . $com['cpestado_shipto'] . " </td>"; //ya
                                                echo "<td>" . $com['cpzip_shipto'] . " </td>"; //ya
                                                echo "<td>" . $com['cptelefono_shipto'] . " </td>"; //ya
                                                echo "<td>" . $com['soldto1'] . " </td>"; //ya
                                                echo "<td>" . $com['soldto2'] . " </td>"; //ya
                                                echo "<td>" . $com['cpstphone_soldto'] . " </td>"; //ya
                                                echo "<td>" . $com['Ponumber'] . " </td>"; //ya
                                                echo "<td>" . $com['Custnumber'] . " </td>"; //ya
                                                echo "<td>" . $com['ShipDT_traking'] . " </td>"; //ya
                                                echo "<td>" . $com['delivery_traking'] . " </td>"; //ya
                                                echo "<td>" . $com['satdel'] . " </td>"; //ya
                                                echo "<td>" . $com['cpcantidad'] . " </td>"; //ya		
                                                echo "<td>" . $com['cpitem'] . " </td>"; //ya
                                                echo "<td>" . $com['prod_descripcion'] . " </td>"; //ya
                                                echo "<td>" . $com['length'] . " </td>"; //ya
                                                echo "<td>" . $com['width'] . " </td>"; //ya
                                                echo "<td>" . $com['heigth'] . " </td>"; //ya
                                                echo "<td>" . $com['wheigthKg'] . " </td>"; //ya
                                                echo "<td>" . $com['dclvalue'] . " </td>"; //ya
                                                echo "<td>" . $com['cpmensaje'] . " </td>"; //ya
                                                echo "<td>";
                                                echo $com['cpmensaje'] == 'To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N' : 'Y';
                                                echo "</td>"; //ya
                                                echo "<td>" . $com['cpservicio'] . " </td>"; //ya
                                                echo "<td>" . $com['cptipo_pack'] . " </td>"; //ya
                                                echo "<td>" . $com['gen_desc'] . " </td>"; //ya
                                                echo "<td>" . $com['cppais_envio'] . " </td>"; //ya
                                                echo "<td>" . $com['cpmoneda'] . " </td>"; //ya
                                                echo "<td>" . $com['cporigen'] . " </td>"; //ya		
                                                echo "<td>" . $com['cpUOM'] . " </td>"; //ya
                                                echo "<td>" . $com['empresa'] . " </td>"; //ya
                                                echo "<td>" . $com['director'] . " </td>"; //ya
                                                echo "<td>" . $com['direccion_director'] . " </td>"; //ya
                                                echo "<td>" . $com['cuidad_director'] . " </td>"; //ya
                                                echo "<td>" . $com['estado_director'] . " </td>"; //ya
                                                echo "<td>" . $com['pais_director'] . " </td>"; //ya
                                                echo "<td>" . $com['tpzip_director'] . " </td>"; //ya
                                                echo "<td>" . $com['tpphone_director'] . " </td>"; //ya
                                                echo "<td>" . $com['tpacct_director'] . " </td>"; //ya
                                                echo "<td>" . $com['farm'] . " </td>"; //ya
                                                echo "</tr>";
                                            }
                                        }
                                    }
                                    session_destroy();
                                    session_start();
                                    $_SESSION["sqlrep"] = $sqlrep;
                                    $_SESSION["sql"] = $sql;
                                    $_SESSION["login"] = $user;
                                    $_SESSION["rol"] = $rol;
                                    $_SESSION["pais"] = $pais;
                                }

                                //verificar el filtro para buscar
                                if ($tracking != '') {
                                    $sql = "select *
                    FROM
                    tblorden
                    INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                    INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                    INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                    INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                    INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `tracking`= '" . $tracking . "'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sql = $sql . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sql = $sql . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }

                                    if ($pais != null) {
                                        $sql = $sql . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sql = $sql . " AND (`origen`= '" . $origen . "'";
                                    }

                                    //ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sql = $sql . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sql = $sql . ")";
                                    }

                                    $sqlrep = "select tracking,nombre_compania,eBing,order_date,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,Ponumber,Custnumber,ShipDT_traking,delivery_traking,satdel,cpcantidad,cpitem,prod_descripcion,length,width,heigth,wheigthKg,dclvalue,cpmensaje,cpservicio,cptipo_pack,gen_desc,cppais_envio,cpmoneda,cporigen,cpUOM,empresa,director,direccion_director,cuidad_director,estado_director,pais_director,tpzip_director,tpphone_director,tpacct_director,farm,cpmensaje AS mensaje2, estado_orden
            FROM
                    tblorden
                    INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                    INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                    INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                    INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                    INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `tracking`= '" . $tracking . "'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sqlrep = $sqlrep . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sqlrep = $sqlrep . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }

                                    if ($pais != null) {
                                        $sqlrep = $sqlrep . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sqlrep = $sqlrep . " AND (`origen`= '" . $origen . "'";
                                    }

//ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sqlrep = $sqlrep . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sqlrep = $sqlrep . ")";
                                    }

                                    $query = mysql_query($sql);
                                    //$total = mysql_num_rows($query);
                                    while ($com = mysql_fetch_array($query)) {
                                        $total++;
                                        if ($com['estado_orden'] == 'Canceled') {
                                            echo '<tr bgcolor="#CC0000" style="color:white;">';
                                        } else {
                                            echo "<tr>";
                                        }
                                        echo "<td>" . $com['estado_orden'] . " </td>"; //ya
                                        echo "<td>" . $com['status'] . " </td>"; //ya
                                        echo "<td>" . $com['reenvio'] . " </td>"; //ya
                                        echo "<td>" . $com['tracking'] . " </td>"; //ya	
                                        echo "<td>" . $com['nombre_compania'] . " </td>"; //ya
                                        echo "<td>" . $com['eBing'] . " </td>"; //ya
                                        echo "<td>" . $com['order_date'] . " </td>"; //ya
                                        echo "<td>" . $com['shipto1'] . " </td>"; //ya
                                        echo "<td>" . $com['shipto2'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion2'] . " </td>"; //ya
                                        echo "<td>" . $com['cpcuidad_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cpestado_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cpzip_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cptelefono_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['soldto1'] . " </td>"; //ya
                                        echo "<td>" . $com['soldto2'] . " </td>"; //ya
                                        echo "<td>" . $com['cpstphone_soldto'] . " </td>"; //ya
                                        echo "<td>" . $com['Ponumber'] . " </td>"; //ya
                                        echo "<td>" . $com['Custnumber'] . " </td>"; //ya
                                        echo "<td>" . $com['ShipDT_traking'] . " </td>"; //ya
                                        echo "<td>" . $com['delivery_traking'] . " </td>"; //ya
                                        echo "<td>" . $com['satdel'] . " </td>"; //ya
                                        echo "<td>" . $com['cpcantidad'] . " </td>"; //ya		
                                        echo "<td>" . $com['cpitem'] . " </td>"; //ya
                                        echo "<td>" . $com['prod_descripcion'] . " </td>"; //ya
                                        echo "<td>" . $com['length'] . " </td>"; //ya
                                        echo "<td>" . $com['width'] . " </td>"; //ya
                                        echo "<td>" . $com['heigth'] . " </td>"; //ya
                                        echo "<td>" . $com['wheigthKg'] . " </td>"; //ya
                                        echo "<td>" . $com['dclvalue'] . " </td>"; //ya
                                        echo "<td>" . $com['cpmensaje'] . " </td>"; //ya
                                        echo "<td>";
                                        echo $com['cpmensaje'] == 'To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N' : 'Y';
                                        echo "</td>"; //ya
                                        echo "<td>" . $com['cpservicio'] . " </td>"; //ya
                                        echo "<td>" . $com['cptipo_pack'] . " </td>"; //ya
                                        echo "<td>" . $com['gen_desc'] . " </td>"; //ya
                                        echo "<td>" . $com['cppais_envio'] . " </td>"; //ya
                                        echo "<td>" . $com['cpmoneda'] . " </td>"; //ya
                                        echo "<td>" . $com['cporigen'] . " </td>"; //ya		
                                        echo "<td>" . $com['cpUOM'] . " </td>"; //ya
                                        echo "<td>" . $com['empresa'] . " </td>"; //ya
                                        echo "<td>" . $com['director'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion_director'] . " </td>"; //ya
                                        echo "<td>" . $com['cuidad_director'] . " </td>"; //ya
                                        echo "<td>" . $com['estado_director'] . " </td>"; //ya
                                        echo "<td>" . $com['pais_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpzip_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpphone_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpacct_director'] . " </td>"; //ya
                                        echo "<td>" . $com['farm'] . " </td>"; //ya
                                        echo "</tr>";
                                    }
                                    session_destroy();
                                    session_start();
                                    $_SESSION["sqlrep"] = $sqlrep;
                                    $_SESSION["sql"] = $sql;
                                    $_SESSION["login"] = $user;
                                    $_SESSION["rol"] = $rol;
                                    $_SESSION["pais"] = $pais;
                                    $_SESSION["alltrack"] = $alltrack;
                                }
                                //verificar el filtro para buscar
                                if ($ponumber != null) {
                                    $sql = "select *
                FROM
                tblorden
                INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE Ponumber = '" . $ponumber . "'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sql = $sql . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sql = $sql . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }
                                    if ($pais != null) {
                                        $sql = $sql . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sql = $sql . " AND (`origen`= '" . $origen . "'";
                                    }

                                    //ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sql = $sql . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sql = $sql . ")";
                                    }

                                    $sqlrep = "select tracking,nombre_compania,eBing,order_date,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,Ponumber,Custnumber,ShipDT_traking,delivery_traking,satdel,cpcantidad,cpitem,prod_descripcion,length,width,heigth,wheigthKg,dclvalue,cpmensaje,cpservicio,cptipo_pack,gen_desc,cppais_envio,cpmoneda,cporigen,cpUOM,empresa,director,direccion_director,cuidad_director,estado_director,pais_director,tpzip_director,tpphone_director,tpacct_director,farm,cpmensaje AS mensaje2, estado_orden
            FROM
                tblorden
                INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE Ponumber = '" . $ponumber . "'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sqlrep = $sqlrep . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sqlrep = $sqlrep . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }
                                    if ($pais != null) {
                                        $sqlrep = $sqlrep . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sqlrep = $sqlrep . " AND (`origen`= '" . $origen . "'";
                                    }

//ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sqlrep = $sqlrep . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sqlrep = $sqlrep . ")";
                                    }

                                    $query = mysql_query($sql);

                                    while ($com = mysql_fetch_array($query)) {
                                        $total++;
                                        if ($com['estado_orden'] == 'Canceled') {
                                            echo '<tr bgcolor="#CC0000" style="color:white;">';
                                        } else {
                                            echo "<tr>";
                                        }
                                        echo "<td>" . $com['estado_orden'] . " </td>"; //ya
                                        echo "<td>" . $com['status'] . " </td>"; //ya
                                        echo "<td>" . $com['reenvio'] . " </td>"; //ya	
                                        echo "<td>" . $com['tracking'] . " </td>"; //ya	
                                        echo "<td>" . $com['nombre_compania'] . " </td>"; //ya
                                        echo "<td>" . $com['eBing'] . " </td>"; //ya
                                        echo "<td>" . $com['order_date'] . " </td>"; //ya
                                        echo "<td>" . $com['shipto1'] . " </td>"; //ya
                                        echo "<td>" . $com['shipto2'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion2'] . " </td>"; //ya
                                        echo "<td>" . $com['cpcuidad_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cpestado_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cpzip_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cptelefono_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['soldto1'] . " </td>"; //ya
                                        echo "<td>" . $com['soldto2'] . " </td>"; //ya
                                        echo "<td>" . $com['cpstphone_soldto'] . " </td>"; //ya
                                        echo "<td>" . $com['Ponumber'] . " </td>"; //ya
                                        echo "<td>" . $com['Custnumber'] . " </td>"; //ya
                                        echo "<td>" . $com['ShipDT_traking'] . " </td>"; //ya
                                        echo "<td>" . $com['delivery_traking'] . " </td>"; //ya
                                        echo "<td>" . $com['satdel'] . " </td>"; //ya
                                        echo "<td>" . $com['cpcantidad'] . " </td>"; //ya		
                                        echo "<td>" . $com['cpitem'] . " </td>"; //ya
                                        echo "<td>" . $com['prod_descripcion'] . " </td>"; //ya
                                        echo "<td>" . $com['length'] . " </td>"; //ya
                                        echo "<td>" . $com['width'] . " </td>"; //ya
                                        echo "<td>" . $com['heigth'] . " </td>"; //ya
                                        echo "<td>" . $com['wheigthKg'] . " </td>"; //ya
                                        echo "<td>" . $com['dclvalue'] . " </td>"; //ya
                                        echo "<td>" . $com['cpmensaje'] . " </td>"; //ya
                                        echo "<td>";
                                        echo $com['cpmensaje'] == 'To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N' : 'Y';
                                        echo "</td>"; //ya
                                        echo "<td>" . $com['cpservicio'] . " </td>"; //ya
                                        echo "<td>" . $com['cptipo_pack'] . " </td>"; //ya
                                        echo "<td>" . $com['gen_desc'] . " </td>"; //ya
                                        echo "<td>" . $com['cppais_envio'] . " </td>"; //ya
                                        echo "<td>" . $com['cpmoneda'] . " </td>"; //ya
                                        echo "<td>" . $com['cporigen'] . " </td>"; //ya		
                                        echo "<td>" . $com['cpUOM'] . " </td>"; //ya
                                        echo "<td>" . $com['empresa'] . " </td>"; //ya
                                        echo "<td>" . $com['director'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion_director'] . " </td>"; //ya
                                        echo "<td>" . $com['cuidad_director'] . " </td>"; //ya
                                        echo "<td>" . $com['estado_director'] . " </td>"; //ya
                                        echo "<td>" . $com['pais_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpzip_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpphone_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpacct_director'] . " </td>"; //ya
                                        echo "<td>" . $com['farm'] . " </td>"; //ya
                                        echo "</tr>";
                                    }
                                    session_destroy();
                                    session_start();
                                    $_SESSION["sqlrep"] = $sqlrep;
                                    $_SESSION["sql"] = $sql;
                                    $_SESSION["login"] = $user;
                                    $_SESSION["rol"] = $rol;
                                    $_SESSION["pais"] = $pais;
                                }

                                //verificar el filtro para buscar
                                if ($custnumber != null) {
                                    //echo ("Tracking");	
                                    // Selecionar todos los campos de la base de datops para el excel a exportar
                                    $sql = "select *
                FROM
                tblorden
                INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `Custnumber`='" . $custnumber . "'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sql = $sql . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sql = $sql . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }
                                    if ($pais != null) {
                                        $sql = $sql . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sql = $sql . " AND (`origen`= '" . $origen . "'";
                                    }

                                    //ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sql = $sql . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sql = $sql . ")";
                                    }

                                    $sqlrep = "select tracking,nombre_compania,eBing,order_date,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,Ponumber,Custnumber,ShipDT_traking,delivery_traking,satdel,cpcantidad,cpitem,prod_descripcion,length,width,heigth,wheigthKg,dclvalue,cpmensaje,cpservicio,cptipo_pack,gen_desc,cppais_envio,cpmoneda,cporigen,cpUOM,empresa,director,direccion_director,cuidad_director,estado_director,pais_director,tpzip_director,tpphone_director,tpacct_director,farm,cpmensaje AS mensaje2, estado_orden
            FROM
                tblorden
                INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `Custnumber`='" . $custnumber . "'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sqlrep = $sqlrep . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sqlrep = $sqlrep . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }
                                    if ($pais != null) {
                                        $sqlrep = $sqlrep . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sqlrep = $sqlrep . " AND (`origen`= '" . $origen . "'";
                                    }

                                    //ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sqlrep = $sqlrep . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sqlrep = $sqlrep . ")";
                                    }


                                    $query = mysql_query($sql);
                                    //$total = mysql_num_rows($query);
                                    while ($com = mysql_fetch_array($query)) {
                                        $total++;
                                        if ($com['estado_orden'] == 'Canceled') {
                                            echo '<tr bgcolor="#CC0000" style="color:white;">';
                                        } else {
                                            echo "<tr>";
                                        }
                                        echo "<td>" . $com['estado_orden'] . " </td>"; //ya
                                        echo "<td>" . $com['status'] . " </td>"; //ya
                                        echo "<td>" . $com['reenvio'] . " </td>"; //ya	
                                        echo "<td>" . $com['tracking'] . " </td>"; //ya	
                                        echo "<td>" . $com['nombre_compania'] . " </td>"; //ya
                                        echo "<td>" . $com['eBing'] . " </td>"; //ya
                                        echo "<td>" . $com['order_date'] . " </td>"; //ya
                                        echo "<td>" . $com['shipto1'] . " </td>"; //ya
                                        echo "<td>" . $com['shipto2'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion2'] . " </td>"; //ya
                                        echo "<td>" . $com['cpcuidad_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cpestado_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cpzip_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cptelefono_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['soldto1'] . " </td>"; //ya
                                        echo "<td>" . $com['soldto2'] . " </td>"; //ya
                                        echo "<td>" . $com['cpstphone_soldto'] . " </td>"; //ya
                                        echo "<td>" . $com['Ponumber'] . " </td>"; //ya
                                        echo "<td>" . $com['Custnumber'] . " </td>"; //ya
                                        echo "<td>" . $com['ShipDT_traking'] . " </td>"; //ya
                                        echo "<td>" . $com['delivery_traking'] . " </td>"; //ya
                                        echo "<td>" . $com['satdel'] . " </td>"; //ya
                                        echo "<td>" . $com['cpcantidad'] . " </td>"; //ya		
                                        echo "<td>" . $com['cpitem'] . " </td>"; //ya
                                        echo "<td>" . $com['prod_descripcion'] . " </td>"; //ya
                                        echo "<td>" . $com['length'] . " </td>"; //ya
                                        echo "<td>" . $com['width'] . " </td>"; //ya
                                        echo "<td>" . $com['heigth'] . " </td>"; //ya
                                        echo "<td>" . $com['wheigthKg'] . " </td>"; //ya
                                        echo "<td>" . $com['dclvalue'] . " </td>"; //ya
                                        echo "<td>" . $com['cpmensaje'] . " </td>"; //ya
                                        echo "<td>";
                                        echo $com['cpmensaje'] == 'To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N' : 'Y';
                                        echo "</td>"; //ya
                                        echo "<td>" . $com['cpservicio'] . " </td>"; //ya
                                        echo "<td>" . $com['cptipo_pack'] . " </td>"; //ya
                                        echo "<td>" . $com['gen_desc'] . " </td>"; //ya
                                        echo "<td>" . $com['cppais_envio'] . " </td>"; //ya
                                        echo "<td>" . $com['cpmoneda'] . " </td>"; //ya
                                        echo "<td>" . $com['cporigen'] . " </td>"; //ya		
                                        echo "<td>" . $com['cpUOM'] . " </td>"; //ya
                                        echo "<td>" . $com['empresa'] . " </td>"; //ya
                                        echo "<td>" . $com['director'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion_director'] . " </td>"; //ya
                                        echo "<td>" . $com['cuidad_director'] . " </td>"; //ya
                                        echo "<td>" . $com['estado_director'] . " </td>"; //ya
                                        echo "<td>" . $com['pais_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpzip_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpphone_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpacct_director'] . " </td>"; //ya
                                        echo "<td>" . $com['farm'] . " </td>"; //ya
                                        echo "</tr>";
                                    }
                                    session_destroy();
                                    session_start();
                                    $_SESSION["sqlrep"] = $sqlrep;
                                    $_SESSION["sql"] = $sql;
                                    $_SESSION["login"] = $user;
                                    $_SESSION["rol"] = $rol;
                                    $_SESSION["pais"] = $pais;
                                }

                                //verificar el filtro para buscar
                                if ($item != null) {
                                    //echo ("Item");	
                                    // Selecionar todos los campos de la base de datops para el excel a exportar
                                    $sql = "select *
                FROM
                tblorden
                INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `cpitem`= '" . $item . "'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sql = $sql . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sql = $sql . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }
                                    if ($pais != null) {
                                        $sql = $sql . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sql = $sql . " AND (`origen`= '" . $origen . "'";
                                    }

                                    //ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sql = $sql . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sql = $sql . ")";
                                    }

                                    $sqlrep = "select tracking,nombre_compania,eBing,order_date,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,Ponumber,Custnumber,ShipDT_traking,delivery_traking,satdel,cpcantidad,cpitem,prod_descripcion,length,width,heigth,wheigthKg,dclvalue,cpmensaje,cpservicio,cptipo_pack,gen_desc,cppais_envio,cpmoneda,cporigen,cpUOM,empresa,director,direccion_director,cuidad_director,estado_director,pais_director,tpzip_director,tpphone_director,tpacct_director,farm,cpmensaje AS mensaje2, estado_orden
            FROM
                tblorden
                INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `cpitem`= '" . $item . "'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sqlrep = $sqlrep . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sqlrep = $sqlrep . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }
                                    if ($pais != null) {
                                        $sqlrep = $sqlrep . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sqlrep = $sqlrep . " AND (`origen`= '" . $origen . "'";
                                    }

                                    //ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sqlrep = $sqlrep . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sqlrep = $sqlrep . ")";
                                    }

                                    $query = mysql_query($sql);
                                    //$total = mysql_num_rows($query);
                                    while ($com = mysql_fetch_array($query)) {
                                        $total++;
                                        if ($com['estado_orden'] == 'Canceled') {
                                            echo '<tr bgcolor="#CC0000" style="color:white;">';
                                        } else {
                                            echo "<tr>";
                                        }
                                        echo "<td>" . $com['estado_orden'] . " </td>"; //ya
                                        echo "<td>" . $com['status'] . " </td>"; //ya
                                        echo "<td>" . $com['reenvio'] . " </td>"; //ya	
                                        echo "<td>" . $com['tracking'] . " </td>"; //ya	
                                        echo "<td>" . $com['nombre_compania'] . " </td>"; //ya
                                        echo "<td>" . $com['eBing'] . " </td>"; //ya
                                        echo "<td>" . $com['order_date'] . " </td>"; //ya
                                        echo "<td>" . $com['shipto1'] . " </td>"; //ya
                                        echo "<td>" . $com['shipto2'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion2'] . " </td>"; //ya
                                        echo "<td>" . $com['cpcuidad_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cpestado_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cpzip_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cptelefono_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['soldto1'] . " </td>"; //ya
                                        echo "<td>" . $com['soldto2'] . " </td>"; //ya
                                        echo "<td>" . $com['cpstphone_soldto'] . " </td>"; //ya
                                        echo "<td>" . $com['Ponumber'] . " </td>"; //ya
                                        echo "<td>" . $com['Custnumber'] . " </td>"; //ya
                                        echo "<td>" . $com['ShipDT_traking'] . " </td>"; //ya
                                        echo "<td>" . $com['delivery_traking'] . " </td>"; //ya
                                        echo "<td>" . $com['satdel'] . " </td>"; //ya
                                        echo "<td>" . $com['cpcantidad'] . " </td>"; //ya		
                                        echo "<td>" . $com['cpitem'] . " </td>"; //ya
                                        echo "<td>" . $com['prod_descripcion'] . " </td>"; //ya
                                        echo "<td>" . $com['length'] . " </td>"; //ya
                                        echo "<td>" . $com['width'] . " </td>"; //ya
                                        echo "<td>" . $com['heigth'] . " </td>"; //ya
                                        echo "<td>" . $com['wheigthKg'] . " </td>"; //ya
                                        echo "<td>" . $com['dclvalue'] . " </td>"; //ya
                                        echo "<td>" . $com['cpmensaje'] . " </td>"; //ya
                                        echo "<td>";
                                        echo $com['cpmensaje'] == 'To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N' : 'Y';
                                        echo "</td>"; //ya
                                        echo "<td>" . $com['cpservicio'] . " </td>"; //ya
                                        echo "<td>" . $com['cptipo_pack'] . " </td>"; //ya
                                        echo "<td>" . $com['gen_desc'] . " </td>"; //ya
                                        echo "<td>" . $com['cppais_envio'] . " </td>"; //ya
                                        echo "<td>" . $com['cpmoneda'] . " </td>"; //ya
                                        echo "<td>" . $com['cporigen'] . " </td>"; //ya		
                                        echo "<td>" . $com['cpUOM'] . " </td>"; //ya
                                        echo "<td>" . $com['empresa'] . " </td>"; //ya
                                        echo "<td>" . $com['director'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion_director'] . " </td>"; //ya
                                        echo "<td>" . $com['cuidad_director'] . " </td>"; //ya
                                        echo "<td>" . $com['estado_director'] . " </td>"; //ya
                                        echo "<td>" . $com['pais_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpzip_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpphone_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpacct_director'] . " </td>"; //ya
                                        echo "<td>" . $com['farm'] . " </td>"; //ya
                                        echo "</tr>";
                                    }
                                    session_destroy();
                                    session_start();
                                    $_SESSION["sqlrep"] = $sqlrep;
                                    $_SESSION["sql"] = $sql;
                                    $_SESSION["login"] = $user;
                                    $_SESSION["rol"] = $rol;
                                    $_SESSION["pais"] = $pais;
                                }

                                //verificar el filtro para buscar
                                if ($farm != null) {
                                    //echo ("Item");	
                                    // Selecionar todos los campos de la base de datops para el excel a exportar
                                    $sql = "select *
                FROM
                tblorden
                INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `farm` LIKE '" . $farm . "%'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sql = $sql . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sql = $sql . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }
                                    if ($pais != null) {
                                        $sql = $sql . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sql = $sql . " AND (`origen`= '" . $origen . "'";
                                    }

                                    //ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sql = $sql . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sql = $sql . ")";
                                    }

                                    $sqlrep = "select tracking,nombre_compania,eBing,order_date,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,Ponumber,Custnumber,ShipDT_traking,delivery_traking,satdel,cpcantidad,cpitem,prod_descripcion,length,width,heigth,wheigthKg,dclvalue,cpmensaje,cpservicio,cptipo_pack,gen_desc,cppais_envio,cpmoneda,cporigen,cpUOM,empresa,director,direccion_director,cuidad_director,estado_director,pais_director,tpzip_director,tpphone_director,tpacct_director,farm,cpmensaje AS mensaje2, estado_orden
            FROM
                tblorden
                INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `farm` LIKE '" . $farm . "%'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sqlrep = $sqlrep . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sqlrep = $sqlrep . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }
                                    if ($pais != null) {
                                        $sqlrep = $sqlrep . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sqlrep = $sqlrep . " AND (`origen`= '" . $origen . "'";
                                    }

//ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sqlrep = $sqlrep . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sqlrep = $sqlrep . ")";
                                    }

                                    $query = mysql_query($sql);
                                    //$total = mysql_num_rows($query);
                                    while ($com = mysql_fetch_array($query)) {
                                        $total++;
                                        if ($com['estado_orden'] == 'Canceled') {
                                            echo '<tr bgcolor="#CC0000" style="color:white;">';
                                        } else {
                                            echo "<tr>";
                                        }
                                        echo "<td>" . $com['estado_orden'] . " </td>"; //ya
                                        echo "<td>" . $com['status'] . " </td>"; //ya
                                        echo "<td>" . $com['reenvio'] . " </td>"; //ya	
                                        echo "<td>" . $com['tracking'] . " </td>"; //ya	
                                        echo "<td>" . $com['nombre_compania'] . " </td>"; //ya
                                        echo "<td>" . $com['eBing'] . " </td>"; //ya
                                        echo "<td>" . $com['order_date'] . " </td>"; //ya
                                        echo "<td>" . $com['shipto1'] . " </td>"; //ya
                                        echo "<td>" . $com['shipto2'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion2'] . " </td>"; //ya
                                        echo "<td>" . $com['cpcuidad_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cpestado_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cpzip_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cptelefono_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['soldto1'] . " </td>"; //ya
                                        echo "<td>" . $com['soldto2'] . " </td>"; //ya
                                        echo "<td>" . $com['cpstphone_soldto'] . " </td>"; //ya
                                        echo "<td>" . $com['Ponumber'] . " </td>"; //ya
                                        echo "<td>" . $com['Custnumber'] . " </td>"; //ya
                                        echo "<td>" . $com['ShipDT_traking'] . " </td>"; //ya
                                        echo "<td>" . $com['delivery_traking'] . " </td>"; //ya
                                        echo "<td>" . $com['satdel'] . " </td>"; //ya
                                        echo "<td>" . $com['cpcantidad'] . " </td>"; //ya		
                                        echo "<td>" . $com['cpitem'] . " </td>"; //ya
                                        echo "<td>" . $com['prod_descripcion'] . " </td>"; //ya
                                        echo "<td>" . $com['length'] . " </td>"; //ya
                                        echo "<td>" . $com['width'] . " </td>"; //ya
                                        echo "<td>" . $com['heigth'] . " </td>"; //ya
                                        echo "<td>" . $com['wheigthKg'] . " </td>"; //ya
                                        echo "<td>" . $com['dclvalue'] . " </td>"; //ya
                                        echo "<td>" . $com['cpmensaje'] . " </td>"; //ya
                                        echo "<td>";
                                        echo $com['cpmensaje'] == 'To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N' : 'Y';
                                        echo "</td>"; //ya
                                        echo "<td>" . $com['cpservicio'] . " </td>"; //ya
                                        echo "<td>" . $com['cptipo_pack'] . " </td>"; //ya
                                        echo "<td>" . $com['gen_desc'] . " </td>"; //ya
                                        echo "<td>" . $com['cppais_envio'] . " </td>"; //ya
                                        echo "<td>" . $com['cpmoneda'] . " </td>"; //ya
                                        echo "<td>" . $com['cporigen'] . " </td>"; //ya		
                                        echo "<td>" . $com['cpUOM'] . " </td>"; //ya
                                        echo "<td>" . $com['empresa'] . " </td>"; //ya
                                        echo "<td>" . $com['director'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion_director'] . " </td>"; //ya
                                        echo "<td>" . $com['cuidad_director'] . " </td>"; //ya
                                        echo "<td>" . $com['estado_director'] . " </td>"; //ya
                                        echo "<td>" . $com['pais_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpzip_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpphone_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpacct_director'] . " </td>"; //ya
                                        echo "<td>" . $com['farm'] . " </td>"; //ya
                                        echo "</tr>";
                                    }
                                    session_destroy();
                                    session_start();
                                    $_SESSION["sqlrep"] = $sqlrep;
                                    $_SESSION["sql"] = $sql;
                                    $_SESSION["login"] = $user;
                                    $_SESSION["rol"] = $rol;
                                    $_SESSION["pais"] = $pais;
                                }


                                //verificar el filtro para buscar
                                if ($shipto1 != null) {
                                    //echo ("Item");	
                                    // Selecionar todos los campos de la base de datops para el excel a exportar
                                    $sql = "select *
                FROM
                tblorden
                INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `shipto1` LIKE '" . $shipto1 . "%'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sql = $sql . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sql = $sql . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }
                                    if ($pais != null) {
                                        $sql = $sql . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sql = $sql . " AND (`origen`= '" . $origen . "'";
                                    }

                                    //ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sql = $sql . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sql = $sql . ")";
                                    }

                                    $sqlrep = "select tracking,nombre_compania,eBing,order_date,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,Ponumber,Custnumber,ShipDT_traking,delivery_traking,satdel,cpcantidad,cpitem,prod_descripcion,length,width,heigth,wheigthKg,dclvalue,cpmensaje,cpservicio,cptipo_pack,gen_desc,cppais_envio,cpmoneda,cporigen,cpUOM,empresa,director,direccion_director,cuidad_director,estado_director,pais_director,tpzip_director,tpphone_director,tpacct_director,farm,cpmensaje AS mensaje2, estado_orden
            FROM
                tblorden
                INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `shipto1` LIKE '" . $shipto1 . "%'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sqlrep = $sqlrep . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sqlrep = $sqlrep . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }
                                    if ($pais != null) {
                                        $sqlrep = $sqlrep . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sqlrep = $sqlrep . " AND (`origen`= '" . $origen . "'";
                                    }

//ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sqlrep = $sqlrep . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sqlrep = $sqlrep . ")";
                                    }

                                    $query = mysql_query($sql);
                                    //$total = mysql_num_rows($query);
                                    while ($com = mysql_fetch_array($query)) {
                                        $total++;
                                        if ($com['estado_orden'] == 'Canceled') {
                                            echo '<tr bgcolor="#CC0000" style="color:white;">';
                                        } else {
                                            echo "<tr>";
                                        }
                                        echo "<td>" . $com['estado_orden'] . " </td>"; //ya
                                        echo "<td>" . $com['status'] . " </td>"; //ya
                                        echo "<td>" . $com['reenvio'] . " </td>"; //ya	
                                        echo "<td>" . $com['tracking'] . " </td>"; //ya	
                                        echo "<td>" . $com['nombre_compania'] . " </td>"; //ya
                                        echo "<td>" . $com['eBing'] . " </td>"; //ya
                                        echo "<td>" . $com['order_date'] . " </td>"; //ya
                                        echo "<td>" . $com['shipto1'] . " </td>"; //ya
                                        echo "<td>" . $com['shipto2'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion2'] . " </td>"; //ya
                                        echo "<td>" . $com['cpcuidad_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cpestado_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cpzip_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cptelefono_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['soldto1'] . " </td>"; //ya
                                        echo "<td>" . $com['soldto2'] . " </td>"; //ya
                                        echo "<td>" . $com['cpstphone_soldto'] . " </td>"; //ya
                                        echo "<td>" . $com['Ponumber'] . " </td>"; //ya
                                        echo "<td>" . $com['Custnumber'] . " </td>"; //ya
                                        echo "<td>" . $com['ShipDT_traking'] . " </td>"; //ya
                                        echo "<td>" . $com['delivery_traking'] . " </td>"; //ya
                                        echo "<td>" . $com['satdel'] . " </td>"; //ya
                                        echo "<td>" . $com['cpcantidad'] . " </td>"; //ya		
                                        echo "<td>" . $com['cpitem'] . " </td>"; //ya
                                        echo "<td>" . $com['prod_descripcion'] . " </td>"; //ya
                                        echo "<td>" . $com['length'] . " </td>"; //ya
                                        echo "<td>" . $com['width'] . " </td>"; //ya
                                        echo "<td>" . $com['heigth'] . " </td>"; //ya
                                        echo "<td>" . $com['wheigthKg'] . " </td>"; //ya
                                        echo "<td>" . $com['dclvalue'] . " </td>"; //ya
                                        echo "<td>" . $com['cpmensaje'] . " </td>"; //ya
                                        echo "<td>";
                                        echo $com['cpmensaje'] == 'To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N' : 'Y';
                                        echo "</td>"; //ya
                                        echo "<td>" . $com['cpservicio'] . " </td>"; //ya
                                        echo "<td>" . $com['cptipo_pack'] . " </td>"; //ya
                                        echo "<td>" . $com['gen_desc'] . " </td>"; //ya
                                        echo "<td>" . $com['cppais_envio'] . " </td>"; //ya
                                        echo "<td>" . $com['cpmoneda'] . " </td>"; //ya
                                        echo "<td>" . $com['cporigen'] . " </td>"; //ya		
                                        echo "<td>" . $com['cpUOM'] . " </td>"; //ya
                                        echo "<td>" . $com['empresa'] . " </td>"; //ya
                                        echo "<td>" . $com['director'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion_director'] . " </td>"; //ya
                                        echo "<td>" . $com['cuidad_director'] . " </td>"; //ya
                                        echo "<td>" . $com['estado_director'] . " </td>"; //ya
                                        echo "<td>" . $com['pais_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpzip_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpphone_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpacct_director'] . " </td>"; //ya
                                        echo "<td>" . $com['farm'] . " </td>"; //ya
                                        echo "</tr>";
                                    }
                                    session_destroy();
                                    session_start();
                                    $_SESSION["sqlrep"] = $sqlrep;
                                    $_SESSION["sql"] = $sql;
                                    $_SESSION["login"] = $user;
                                    $_SESSION["rol"] = $rol;
                                    $_SESSION["pais"] = $pais;
                                }

                                //verificar el filtro para buscar
                                if ($direccion != null) {
                                    //echo ("Item");	
                                    // Selecionar todos los campos de la base de datops para el excel a exportar
                                    $sql = "select *
            FROM
            tblorden
            INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
            INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
            INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
            INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
            INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `direccion` LIKE '" . $direccion . "%'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sql = $sql . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sql = $sql . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }
                                    if ($pais != null) {
                                        $sql = $sql . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sql = $sql . " AND (`origen`= '" . $origen . "'";
                                    }

                                    //ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sql = $sql . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sql = $sql . ")";
                                    }

                                    $sqlrep = "select tracking,nombre_compania,eBing,order_date,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,Ponumber,Custnumber,ShipDT_traking,delivery_traking,satdel,cpcantidad,cpitem,prod_descripcion,length,width,heigth,wheigthKg,dclvalue,cpmensaje,cpservicio,cptipo_pack,gen_desc,cppais_envio,cpmoneda,cporigen,cpUOM,empresa,director,direccion_director,cuidad_director,estado_director,pais_director,tpzip_director,tpphone_director,tpacct_director,farm,cpmensaje AS mensaje2, estado_orden
            FROM
            tblorden
            INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
            INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
            INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
            INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
            INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `direccion` LIKE '" . $direccion . "%'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sqlrep = $sqlrep . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sqlrep = $sqlrep . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }
                                    if ($pais != null) {
                                        $sqlrep = $sqlrep . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sqlrep = $sqlrep . " AND (`origen`= '" . $origen . "'";
                                    }

//ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sqlrep = $sqlrep . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sqlrep = $sqlrep . ")";
                                    }

                                    $query = mysql_query($sql);
                                    //$total = mysql_num_rows($query);
                                    while ($com = mysql_fetch_array($query)) {
                                        $total++;
                                        if ($com['estado_orden'] == 'Canceled') {
                                            echo '<tr bgcolor="#CC0000" style="color:white;">';
                                        } else {
                                            echo "<tr>";
                                        }
                                        echo "<td>" . $com['estado_orden'] . " </td>"; //ya
                                        echo "<td>" . $com['status'] . " </td>"; //ya
                                        echo "<td>" . $com['reenvio'] . " </td>"; //ya	
                                        echo "<td>" . $com['tracking'] . " </td>"; //ya	
                                        echo "<td>" . $com['nombre_compania'] . " </td>"; //ya
                                        echo "<td>" . $com['eBing'] . " </td>"; //ya
                                        echo "<td>" . $com['order_date'] . " </td>"; //ya
                                        echo "<td>" . $com['shipto1'] . " </td>"; //ya
                                        echo "<td>" . $com['shipto2'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion2'] . " </td>"; //ya
                                        echo "<td>" . $com['cpcuidad_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cpestado_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cpzip_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cptelefono_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['soldto1'] . " </td>"; //ya
                                        echo "<td>" . $com['soldto2'] . " </td>"; //ya
                                        echo "<td>" . $com['cpstphone_soldto'] . " </td>"; //ya
                                        echo "<td>" . $com['Ponumber'] . " </td>"; //ya
                                        echo "<td>" . $com['Custnumber'] . " </td>"; //ya
                                        echo "<td>" . $com['ShipDT_traking'] . " </td>"; //ya
                                        echo "<td>" . $com['delivery_traking'] . " </td>"; //ya
                                        echo "<td>" . $com['satdel'] . " </td>"; //ya
                                        echo "<td>" . $com['cpcantidad'] . " </td>"; //ya		
                                        echo "<td>" . $com['cpitem'] . " </td>"; //ya
                                        echo "<td>" . $com['prod_descripcion'] . " </td>"; //ya
                                        echo "<td>" . $com['length'] . " </td>"; //ya
                                        echo "<td>" . $com['width'] . " </td>"; //ya
                                        echo "<td>" . $com['heigth'] . " </td>"; //ya
                                        echo "<td>" . $com['wheigthKg'] . " </td>"; //ya
                                        echo "<td>" . $com['dclvalue'] . " </td>"; //ya
                                        echo "<td>" . $com['cpmensaje'] . " </td>"; //ya
                                        echo "<td>";
                                        echo $com['cpmensaje'] == 'To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N' : 'Y';
                                        echo "</td>"; //ya
                                        echo "<td>" . $com['cpservicio'] . " </td>"; //ya
                                        echo "<td>" . $com['cptipo_pack'] . " </td>"; //ya
                                        echo "<td>" . $com['gen_desc'] . " </td>"; //ya
                                        echo "<td>" . $com['cppais_envio'] . " </td>"; //ya
                                        echo "<td>" . $com['cpmoneda'] . " </td>"; //ya
                                        echo "<td>" . $com['cporigen'] . " </td>"; //ya		
                                        echo "<td>" . $com['cpUOM'] . " </td>"; //ya
                                        echo "<td>" . $com['empresa'] . " </td>"; //ya
                                        echo "<td>" . $com['director'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion_director'] . " </td>"; //ya
                                        echo "<td>" . $com['cuidad_director'] . " </td>"; //ya
                                        echo "<td>" . $com['estado_director'] . " </td>"; //ya
                                        echo "<td>" . $com['pais_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpzip_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpphone_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpacct_director'] . " </td>"; //ya
                                        echo "<td>" . $com['farm'] . " </td>"; //ya
                                        echo "</tr>";
                                    }
                                    session_destroy();
                                    session_start();
                                    $_SESSION["sqlrep"] = $sqlrep;
                                    $_SESSION["sql"] = $sql;
                                    $_SESSION["login"] = $user;
                                    $_SESSION["rol"] = $rol;
                                    $_SESSION["pais"] = $pais;
                                }

                                //verificar el filtro para buscar
                                if ($soldto1 != null) {
                                    //echo ("Item");	
                                    // Selecionar todos los campos de la base de datops para el excel a exportar
                                    $sql = "select *
            FROM
            tblorden
            INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
            INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
            INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
            INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
            INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `soldto1` LIKE '" . $soldto1 . "%'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sql = $sql . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sql = $sql . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }
                                    if ($pais != null) {
                                        $sql = $sql . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sql = $sql . " AND (`origen`= '" . $origen . "'";
                                    }

                                    //ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sql = $sql . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sql = $sql . ")";
                                    }

                                    $sqlrep = "select tracking,nombre_compania,eBing,order_date,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,Ponumber,Custnumber,ShipDT_traking,delivery_traking,satdel,cpcantidad,cpitem,prod_descripcion,length,width,heigth,wheigthKg,dclvalue,cpmensaje,cpservicio,cptipo_pack,gen_desc,cppais_envio,cpmoneda,cporigen,cpUOM,empresa,director,direccion_director,cuidad_director,estado_director,pais_director,tpzip_director,tpphone_director,tpacct_director,farm,cpmensaje AS mensaje2, estado_orden
            FROM
            tblorden
            INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
            INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
            INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
            INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
            INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `soldto1` LIKE '" . $soldto1 . "%'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sqlrep = $sqlrep . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sqlrep = $sqlrep . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }
                                    if ($pais != null) {
                                        $sqlrep = $sqlrep . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sqlrep = $sqlrep . " AND (`origen`= '" . $origen . "'";
                                    }

//ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sqlrep = $sqlrep . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sqlrep = $sqlrep . ")";
                                    }

                                    $query = mysql_query($sql);
                                    //$total = mysql_num_rows($query);
                                    while ($com = mysql_fetch_array($query)) {
                                        $total++;
                                        if ($com['estado_orden'] == 'Canceled') {
                                            echo '<tr bgcolor="#CC0000" style="color:white;">';
                                        } else {
                                            echo "<tr>";
                                        }
                                        echo "<td>" . $com['estado_orden'] . " </td>"; //ya
                                        echo "<td>" . $com['status'] . " </td>"; //ya
                                        echo "<td>" . $com['reenvio'] . " </td>"; //ya
                                        echo "<td>" . $com['tracking'] . " </td>"; //ya	
                                        echo "<td>" . $com['nombre_compania'] . " </td>"; //ya
                                        echo "<td>" . $com['eBing'] . " </td>"; //ya
                                        echo "<td>" . $com['order_date'] . " </td>"; //ya
                                        echo "<td>" . $com['shipto1'] . " </td>"; //ya
                                        echo "<td>" . $com['shipto2'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion2'] . " </td>"; //ya
                                        echo "<td>" . $com['cpcuidad_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cpestado_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cpzip_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cptelefono_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['soldto1'] . " </td>"; //ya
                                        echo "<td>" . $com['soldto2'] . " </td>"; //ya
                                        echo "<td>" . $com['cpstphone_soldto'] . " </td>"; //ya
                                        echo "<td>" . $com['Ponumber'] . " </td>"; //ya
                                        echo "<td>" . $com['Custnumber'] . " </td>"; //ya
                                        echo "<td>" . $com['ShipDT_traking'] . " </td>"; //ya
                                        echo "<td>" . $com['delivery_traking'] . " </td>"; //ya
                                        echo "<td>" . $com['satdel'] . " </td>"; //ya
                                        echo "<td>" . $com['cpcantidad'] . " </td>"; //ya		
                                        echo "<td>" . $com['cpitem'] . " </td>"; //ya
                                        echo "<td>" . $com['prod_descripcion'] . " </td>"; //ya
                                        echo "<td>" . $com['length'] . " </td>"; //ya
                                        echo "<td>" . $com['width'] . " </td>"; //ya
                                        echo "<td>" . $com['heigth'] . " </td>"; //ya
                                        echo "<td>" . $com['wheigthKg'] . " </td>"; //ya
                                        echo "<td>" . $com['dclvalue'] . " </td>"; //ya
                                        echo "<td>" . $com['cpmensaje'] . " </td>"; //ya
                                        echo "<td>";
                                        echo $com['cpmensaje'] == 'To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N' : 'Y';
                                        echo "</td>"; //ya
                                        echo "<td>" . $com['cpservicio'] . " </td>"; //ya
                                        echo "<td>" . $com['cptipo_pack'] . " </td>"; //ya
                                        echo "<td>" . $com['gen_desc'] . " </td>"; //ya
                                        echo "<td>" . $com['cppais_envio'] . " </td>"; //ya
                                        echo "<td>" . $com['cpmoneda'] . " </td>"; //ya
                                        echo "<td>" . $com['cporigen'] . " </td>"; //ya		
                                        echo "<td>" . $com['cpUOM'] . " </td>"; //ya
                                        echo "<td>" . $com['empresa'] . " </td>"; //ya
                                        echo "<td>" . $com['director'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion_director'] . " </td>"; //ya
                                        echo "<td>" . $com['cuidad_director'] . " </td>"; //ya
                                        echo "<td>" . $com['estado_director'] . " </td>"; //ya
                                        echo "<td>" . $com['pais_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpzip_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpphone_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpacct_director'] . " </td>"; //ya
                                        echo "<td>" . $com['farm'] . " </td>"; //ya
                                        echo "</tr>";
                                    }
                                    session_destroy();
                                    session_start();
                                    $_SESSION["sqlrep"] = $sqlrep;
                                    $_SESSION["sql"] = $sql;
                                    $_SESSION["login"] = $user;
                                    $_SESSION["rol"] = $rol;
                                    $_SESSION["pais"] = $pais;
                                }

                                //verificar el filtro para buscar
                                if ($cpdireccion_soldto != null) {
                                    //echo ("Item");	
                                    // Selecionar todos los campos de la base de datops para el excel a exportar
                                    $sql = "select *
                FROM
                tblorden
                INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `cpdireccion_soldto` LIKE '" . $cpdireccion_soldto . "%'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sql = $sql . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sql = $sql . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }
                                    if ($pais != null) {
                                        $sql = $sql . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sql = $sql . " AND (`origen`= '" . $origen . "'";
                                    }

                                    //ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sql = $sql . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sql = $sql . ")";
                                    }

                                    $sqlrep = "select tracking,nombre_compania,eBing,order_date,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,Ponumber,Custnumber,ShipDT_traking,delivery_traking,satdel,cpcantidad,cpitem,prod_descripcion,length,width,heigth,wheigthKg,dclvalue,cpmensaje,cpservicio,cptipo_pack,gen_desc,cppais_envio,cpmoneda,cporigen,cpUOM,empresa,director,direccion_director,cuidad_director,estado_director,pais_director,tpzip_director,tpphone_director,tpacct_director,farm,cpmensaje AS mensaje2, estado_orden
            FROM
                tblorden
                INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `cpdireccion_soldto` LIKE '" . $cpdireccion_soldto . "%'";
                                    if ($consolidado != 'todas') {
                                        if ($consolidado == 'N') {
                                            $sqlrep = $sqlrep . " AND (tbldetalle_orden.consolidado= 'N' OR tbldetalle_orden.consolidado='')";
                                        } else
                                            $sqlrep = $sqlrep . " AND tbldetalle_orden.consolidado= '" . $consolidado . "'";
                                    }
                                    if ($pais != null) {
                                        $sqlrep = $sqlrep . " AND `cppais_envio`= '" . $pais . "'";
                                    }

                                    if ($origen != null) {
                                        $sqlrep = $sqlrep . " AND (`origen`= '" . $origen . "'";
                                    }

//ORIGEN 2

                                    if (($origen != null) && ($origen2 != null)) {
                                        $sqlrep = $sqlrep . " OR `origen`= '" . $origen2 . "')";
                                    }

                                    if (($origen != null) && ($origen2 == null)) {
                                        $sqlrep = $sqlrep . ")";
                                    }

                                    $query = mysql_query($sql);
                                    //$total = mysql_num_rows($query);
                                    while ($com = mysql_fetch_array($query)) {
                                        $total++;
                                        if ($com['estado_orden'] == 'Canceled') {
                                            echo '<tr bgcolor="#CC0000" style="color:white;">';
                                        } else {
                                            echo "<tr>";
                                        }
                                        echo "<td>" . $com['estado_orden'] . " </td>"; //ya
                                        echo "<td>" . $com['status'] . " </td>"; //ya
                                        echo "<td>" . $com['reenvio'] . " </td>"; //ya					
                                        echo "<td>" . $com['tracking'] . " </td>"; //ya	
                                        echo "<td>" . $com['nombre_compania'] . " </td>"; //ya
                                        echo "<td>" . $com['eBing'] . " </td>"; //ya
                                        echo "<td>" . $com['order_date'] . " </td>"; //ya
                                        echo "<td>" . $com['shipto1'] . " </td>"; //ya
                                        echo "<td>" . $com['shipto2'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion2'] . " </td>"; //ya
                                        echo "<td>" . $com['cpcuidad_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cpestado_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cpzip_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['cptelefono_shipto'] . " </td>"; //ya
                                        echo "<td>" . $com['soldto1'] . " </td>"; //ya
                                        echo "<td>" . $com['soldto2'] . " </td>"; //ya
                                        echo "<td>" . $com['cpstphone_soldto'] . " </td>"; //ya
                                        echo "<td>" . $com['Ponumber'] . " </td>"; //ya
                                        echo "<td>" . $com['Custnumber'] . " </td>"; //ya
                                        echo "<td>" . $com['ShipDT_traking'] . " </td>"; //ya
                                        echo "<td>" . $com['delivery_traking'] . " </td>"; //ya
                                        echo "<td>" . $com['satdel'] . " </td>"; //ya
                                        echo "<td>" . $com['cpcantidad'] . " </td>"; //ya		
                                        echo "<td>" . $com['cpitem'] . " </td>"; //ya
                                        echo "<td>" . $com['prod_descripcion'] . " </td>"; //ya
                                        echo "<td>" . $com['length'] . " </td>"; //ya
                                        echo "<td>" . $com['width'] . " </td>"; //ya
                                        echo "<td>" . $com['heigth'] . " </td>"; //ya
                                        echo "<td>" . $com['wheigthKg'] . " </td>"; //ya
                                        echo "<td>" . $com['dclvalue'] . " </td>"; //ya
                                        echo "<td>" . $com['cpmensaje'] . " </td>"; //ya
                                        echo "<td>";
                                        echo $com['cpmensaje'] == 'To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N' : 'Y';
                                        echo "</td>"; //ya
                                        echo "<td>" . $com['cpservicio'] . " </td>"; //ya
                                        echo "<td>" . $com['cptipo_pack'] . " </td>"; //ya
                                        echo "<td>" . $com['gen_desc'] . " </td>"; //ya
                                        echo "<td>" . $com['cppais_envio'] . " </td>"; //ya
                                        echo "<td>" . $com['cpmoneda'] . " </td>"; //ya
                                        echo "<td>" . $com['cporigen'] . " </td>"; //ya		
                                        echo "<td>" . $com['cpUOM'] . " </td>"; //ya
                                        echo "<td>" . $com['empresa'] . " </td>"; //ya
                                        echo "<td>" . $com['director'] . " </td>"; //ya
                                        echo "<td>" . $com['direccion_director'] . " </td>"; //ya
                                        echo "<td>" . $com['cuidad_director'] . " </td>"; //ya
                                        echo "<td>" . $com['estado_director'] . " </td>"; //ya
                                        echo "<td>" . $com['pais_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpzip_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpphone_director'] . " </td>"; //ya
                                        echo "<td>" . $com['tpacct_director'] . " </td>"; //ya
                                        echo "<td>" . $com['farm'] . " </td>"; //ya
                                        echo "</tr>";
                                    }
                                    session_destroy();
                                    session_start();
                                    $_SESSION["sqlrep"] = $sqlrep;
                                    $_SESSION["sql"] = $sql;
                                    $_SESSION["login"] = $user;
                                    $_SESSION["rol"] = $rol;
                                    $_SESSION["pais"] = $pais;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </form> 
                    <div class="row">
                        <div class="col-md-12">
                            <h4><strong>Total de órdenes mostradas: <?php echo $total ?></strong> </h4>
                        </div>
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
                    <a href="#top" class="well well-sm"  onclick="$('html,body').animate({scrollTop: 0}, 'slow');return false;">
                        <i class="glyphicon glyphicon-chevron-up"></i> Ir arriba
                    </a>
                </span><!-- /top-link-block --> 
            </div> <!-- /container -->
    </body>
</html>