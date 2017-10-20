<?php
///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL); 
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");
require_once ('PHPExcel.php');
include('date.php');
include('convertHex-Dec.php');
include('consecutivo.php');
include ('PHPExcel/IOFactory.php');

session_start();
$user = $_SESSION["login"];
$pass = $_SESSION["pass"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Subir Tracking</title>
            <link href="../images/favicon.ico"  rel="icon" type="image/png" />
            <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
                <link href="../bootstrap/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" media="all"  />
                <link href="../bootstrap/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all"  />
                <link href="../bootstrap/css/prettify-1.0.css" rel="stylesheet" type="text/css" media="all"  />
                <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">

                    <script type="text/javascript" src="../js/script.js"></script>
                    <script src="../bootstrap/js/jquery.min.js"></script>
                    <script src="../bootstrap/js/bootstrap.min.js"></script>
                    <script src="../bootstrap/js/bootstrap-submenu.js"></script>
                    <script src="../js/moment-with-locales.js"></script>
                    <script src="../bootstrap/js/bootstrap-datetimepicker.js"></script>
                    <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
                    <script src="../bootstrap/js/docs.js" defer></script>

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
                                                <a class="navbar-brand" href="../main.php?panel=mainpanel.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
                                            </div>

                                            <!-- Agrupar los enlaces de navegación, los formularios y cualquier
                                                 otro elemento que se pueda ocultar al minimizar la barra -->
                                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                                <ul class="nav navbar-nav">
                                                    <?php if ($rol == 3 || $rol == 8) { ?>
                                                        <li><a href="../php/subirTracking1.php"><strong>Cargar Tracking</strong></a></li>
                                                    <?php } ?> 

                                                    <?php if ($rol <= 2) { ?>
                                                        <li class="active" >
                                                            <a tabindex="0" data-toggle="dropdown">
                                                                <strong>Venta</strong><span class="caret"></span>
                                                            </a>
                                                            <ul class="dropdown-menu" role="menu">
                                                                <li><a href="crearorden.php"><strong>Punto de Venta</strong></a></li>
                                                                <li class="divider"></li>
                                                                <li class="dropdown-submenu">
                                                                    <a tabindex="0" data-toggle="dropdown"><strong>Cargar</strong></a>
                                                                    <ul class="dropdown-menu">
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
                                                    <li>
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
                                                                    <li class="divider"></li>  
                                                                    <li><a href="filtros.php?accion=buscarPO"><strong>Buscar PO</strong></a>
                                                                        <?php
                                                                    }
                                                                    ?>

                                                                    <?php if ($rol == 3) { ?>
                                                                        <li><a href="filtros_fincas.php?accion=buscarPO"><strong>Buscar PO</strong></a>
                                                                        <?php } ?>
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

                                                                        <div class="panel-body">
                                                                            <div class="row">
                                                                                <div align="center">
                                                                                    <strong><h1>REPORTE DE TRACKINGS CARGADOS</h1></strong>
                                                                                </div>
                                                                                <hr width="80%"></hr>
                                                                                <hr width="80%"></hr>
                                                                            </div>
                                                                            <div width="60%">
                                                                                <table width="90%" align="center">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td align="left">
                                                                                                <button type='button' class='btn btn-primary' data-toggle='tooltip' data-placement='left' title = 'Resgistro de eventualidades al cargar'>
                                                                                                    <strong> Registro de Eventos </strong>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                            <?php
                                                                            $orden = 0;
                                                                            $fila = 1;
                                                                            $array = array();
                                                                            $dir = "./Archivos subidos/";
                                                                            //CONTAMOS CUANTOS ARCHIVOS HAY EN LA CARPETA
                                                                            $total_excel = count(glob("$dir/{*.csv}", GLOB_BRACE));  //("$dir/{*.xlsx,*.xls,*.csv}",GLOB_BRACE));
                                                                            if ($total_excel == 0) {
                                                                                echo "<font color='red'>No hay archivo para leer...</font><br>";
                                                                            } else {
                                                                                echo "Total de archivos cargados: " . $total_excel . "<br>";
                                                                                //LOS RENOMBRAMOS PARA LEERLOS AL CSV EN UN LOOP
                                                                                $a = 1;
                                                                                $excels = (glob("$dir/{*.csv}", GLOB_BRACE));
                                                                                foreach ($excels as $cvs) {
                                                                                    $expr = explode("/", $cvs);
                                                                                    $nombre = array_pop($expr);
                                                                                    rename("$dir/$nombre", "$dir/$a.csv");
                                                                                    $a++;
                                                                                }
                                                                            }
                                                                            //LEEMOS TODOS LOS ARCHIVOS Y GUARDAMOS LA INFO A UN ARRAY, LUEGO ELIMINO EL ARCHIVO DEL SERVIDOR
                                                                            for ($i = 1; $i <= $total_excel; $i++) {
                                                                                $orden ++;
                                                                                if (($gestor = fopen("$dir/$i.csv", "r")) !== FALSE) {
                                                                                    while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                                                                                        $numero = count($datos);
                                                                                        for ($col = 0; $col < $numero; $col++) {
                                                                                            $array [$fila][$col] = addslashes($datos[$col]);
                                                                                        }
                                                                                        $fila++;
                                                                                    }
                                                                                    fclose($gestor);
                                                                                    unlink("$dir/$i.csv");
                                                                                }
                                                                            }
                                                                            $j = 2; //COMIENZA A LEER A PARTIR DE LA FILA 2
                                                                            $contador = 1;
                                                                            $fila = $fila - 1;
                                                                            echo "Cantidad de registros analizados: " . $fila . "<br>";
                                                                            $errorlog = 1;
                                                                            //RECORREMOS CADA FILA PARA OBTENER LOS ARRAY PARA LOS QUERY
                                                                            for ($j = 2; $j <= $fila; $j++) {
                                                                                
                                                                                //DECLARAMOS LOS CAMPOS QUE USAREMOS PARA ARMAR LOS QUERY
                                                                                $Tracking = $array [$j]['5'];
                                                                                $Ponumber = trim($array [$j]['2']);
                                                                                $CustNumber = $array [$j]['3'];
                                                                                $item = $array [$j]['4'];

                                                                                //VERIFICAMOS CAMPOS VACIOS, EN CASO DE HABERLOS MOSTRAMOS MENSAJE EN PANTALLA
                                                                                if ($Tracking == '') {
                                                                                    echo 'La fila <b><font color="red">' . $j . '</font></b> del archivo tiene el campo <b><font color="red">"TRACKING"</font></b> vacío<br>';
                                                                                }
                                                                                if ($Ponumber == '') {
                                                                                    echo 'La fila <b><font color="red">' . $j . '</font></b> del archivo tiene el campo <b><font color="red">"PONUMBER"</font></b> vacío<br>';
                                                                                }
                                                                                if ($CustNumber == '') {
                                                                                    echo 'La fila <b><font color="red">' . $j . '</font></b> del archivo tiene el campo <b><font color="red">"CUSTNUMBER"</font></b> vacío<br>';
                                                                                }
                                                                                if ($item == '') {
                                                                                    echo 'La fila <b><font color="red">' . $j . '</font></b> del archivo tiene el campo <b><font color="red">"ITEM"</font></b> vacío<br>';
                                                                                }

                                                                                //DESCARTAMOS LAS FILAS CON CAMPOS VACIOS
                                                                                if ($Tracking !== '' && $Ponumber !== '' && $CustNumber !== '' && $item !== '') {

                                                                                    //ALIMENTAMOS LOS QUERY
                                                                                    $sql_tracking .= "'" . $Tracking . "',";
                                                                                    $sql_ponumber .= "'" . $Ponumber . "',";
                                                                                    $sql_custnumber .= "'" . $CustNumber . "',";
                                                                                    $sql_item .= "'" . $item . "',";
                                                                                }
                                                                            }

                                                                            //CONVERTIMOS LAS COLUMNAS EN ARRAYS PARA ASOCIAR LOS VALORES DEL UPDATE
                                                                            $array_tracking = explode(",", $sql_tracking);
                                                                            $array_ponumber = explode(",", $sql_ponumber);
                                                                            $array_custnumber = explode(",", $sql_custnumber);
                                                                            $array_item = explode(",", $sql_item);

                                                                            //LO ORDENAMOS POR CPITEM PARA EVITAR COLISIONES EN EL UPDATE
                                                                            //array_multisort($array_item,$array_custnumber,$array_ponumber,$array_tracking);
                                                                            //AGREGAMOS UN ELEMENTO AL INICIO PARA EVITAR DESCARTAR VALOR 0
                                                                            array_unshift($array_tracking, "inicio");
                                                                            array_unshift($array_ponumber, "inicio");
                                                                            array_unshift($array_custnumber, "inicio");
                                                                            array_unshift($array_item, "inicio");

//                                                                            //REMOVEMOS LOS TRACKING DUPLICADOS DENTRO DEL MISMO ARCHIVO
//                                                                            $array_tracking = array_unique($array_tracking);
                                                                            //REMOVEMOS LA ULTIMA COMA PARA EVITAR ERROR DE SINTAXIS
                                                                            $sql_tracking = substr(trim($sql_tracking), 0, -1);
                                                                            $sql_ponumber = substr(trim($sql_ponumber), 0, -1);
                                                                            $sql_custnumber = substr(trim($sql_custnumber), 0, -1);
                                                                            $sql_item = substr(trim($sql_item), 0, -1);


                                                                            //VALIDAMOS QUE LOS TRACKINGS NO SE ENCUENTREN YA EN SISTEMA
//                                                                            $query_tracking = "SELECT tracking,ponumber,custnumber,cpitem 
//                                                                                                FROM tbldetalle_orden 
//                                                                                                WHERE tracking IN (" . $sql_tracking . ")";
//                                                                            $result_tracking = mysqli_query($link, $query_tracking) or die("Error validando trackings existentes en el sistema");
//                                                                            while ($row_tracking = mysqli_fetch_array($result_tracking)) {
//                                                                                if ($row_tracking['tracking'] != "") {
//                                                                                    echo 'El Tracking: <b><font color="red">' . $row_tracking["tracking"] . '</font></b> ya se encuentra en sistema con el Ponumber <b><font color="red">' . $row_tracking["ponumber"] . '</b></font> , custnumber <b><font color="red">' . $row_tracking["custnumber"] . '</b></font> e item <b><font color="red">' . $row_tracking["cpitem"] . '</b></font><br>';
//
//                                                                                    //SI EL TRACKING SE ENCUENTRA EN EL SISTEMA REMOVEMOS LA FILA PARA NO HACER UPDATE
//                                                                                    $remover = array_search("'" . $row_tracking["tracking"] . "'", $array_tracking);
//                                                                                    unset($array_tracking[$remover]);
//                                                                                    unset($array_ponumber[$remover]);
//                                                                                    unset($array_custnumber[$remover]);
//                                                                                    unset($array_item[$remover]);
//                                                                                }
//                                                                            }
                                                                            //VALIDAMOS LAS ORDENES QUE TIENEN REENVIO = FORWARDED
                                                                            $query_reshipped = "SELECT id_orden_detalle,ponumber,status,cpitem,custnumber
                                FROM  tbldetalle_orden 
                                WHERE Ponumber IN (" . $sql_ponumber . ") 
                                AND reenvio = 'Forwarded' 
                                AND estado_orden != 'Canceled' 
                                AND tracking = ''";

//        //DEBUG TEST
//        echo "QUERY DE REENVIOS: ". $query_reshipped . "<br />";

                                                                            $result_reshipped = mysqli_query($link, $query_reshipped) or die("Error validando ordenes reenviadas");
                                                                            while ($row_reshipped = mysqli_fetch_array($result_reshipped)) {

                                                                                //SI TIENE ESTADO READY TO SHIP PROCESA LA FILA
//                                                                                if ($row_reshipped['status'] !== "shipped" && $row_reshipped['status'] !== "New") {
                                                                                $tracking_key = array_search("'" . $row_reshipped['ponumber'] . "'", $array_ponumber);

                                                                                $compare_custnumber = "'" . $row_reshipped['custnumber'] . "'";
                                                                                $compare_cpitem = "'" . $row_reshipped['cpitem'] . "'";

                                                                                if ($compare_custnumber == $array_custnumber[$tracking_key] && $compare_cpitem == $array_item[$tracking_key]) {
                                                                                    if (!empty($tracking_key)) {
                                                                                        if (!empty($array_tracking[$tracking_key])) {
                                                                                            $sqlup_case .= "WHEN " . $row_reshipped['id_orden_detalle'] . " THEN  " . $array_tracking[$tracking_key] . " ";
                                                                                            $sql_ordendetalle .= "'" . $row_reshipped['id_orden_detalle'] . "',";

                                                                                            //ANUNCIAMOS LOS TRACKINGS QUE SE HAN CARGADO
                                                                                            echo "Se ha insertado El tracking: <b><font color='red'>" . $array_tracking[$tracking_key] . "</font></b> Para el id: <b><font color='red'>" . $row_reshipped['id_orden_detalle'] . "</font></b> Con Ponumber asignado: <b><font color='red'>" . $array_ponumber[$tracking_key] . "</font></b> Con item: <b><font color='red'>" . $array_item[$tracking_key] . "</font></b> Con estado : <b><font color='red'> REENVIADO </font> </b> <br />";
                                                                                            $countup++;

                                                                                            //REMOVEMOS EL PO PARA NO INSERTAR TRACKING REPETIDO
//                                                                                                    echo "REMOVEMOS LA POSICION: ". $tracking_key ." DE LA COLUMNA PONUMBER <br />"; //DEBUG TEST
                                                                                            $array_ponumber[$tracking_key] = "PONUMBER USADO";
                                                                                        } else {
                                                                                            echo "No se pudo alimentar el id: <b><font color='red'>" . $row_reshipped['id_orden_detalle'] . "</font></b> Con Ponumber asignado: <b><font color='red'>" . $array_ponumber[$tracking_key] . "</font></b> Porque su: <b><font color='red'>TRACKING</font></b> está repetido dentro del archivo <br />";
                                                                                        }
                                                                                    }
                                                                                }
//                                                                                } else { //SI TIENE ESTADO SHIPPED O NEW NO PROCESA LA FILA
//                                                                                    //DOY REPORTE DE QUE NO SE ACTUALIZO POR SER "SHIPPED" O "NEW"
//                                                                                    $tracking_key = array_search("'" . $row_reshipped['ponumber'] . "'", $array_ponumber);
//                                                                                    if (!empty($tracking_key)) {
//                                                                                        echo "El tracking: <b><font color='red'>" . $array_tracking[$tracking_key] . "</font></b> Con el id: <b><font color='red'>" . $row_reshipped['id_orden_detalle'] . "</font></b> Con Ponumber asignado: <b><font color='red'>" . $array_ponumber[$tracking_key] . "</font></b> Con reenvio : <b><font color='red'>REENVIADO </font></b> -- <b><font color='red'> NO  </font></b> se actualizó por tener estado: <b><font color='red'> " . $row_reshipped['status'] . " </font></b><br />";
//                                                                                    }
//                                                                                }
                                                                            }

                                                                            //REMOVEMOS LA ULTIMA COMA PARA EVITAR ERROR DE SINTAXIS
                                                                            $sql_ordendetalle = substr(trim($sql_ordendetalle), 0, -1);

                                                                            //SI ENCUENTRA ORDENES CON FORWARDED HACE EL UPDATE
                                                                            if (!is_null($sqlup_case)) {
                                                                                $update1 = "UPDATE tbldetalle_orden 
                        SET status = 'shipped', tracking = 
                            CASE id_orden_detalle
                                " . $sqlup_case . "
                            END
                        WHERE id_orden_detalle IN (" . $sql_ordendetalle . ");";
                                                                                mysqli_query($link, $update1) or die("Error cargando los trackings de ordenes REENVIADASs");
                                                                            } else {

                                                                                //PRIMER VALIDADOR DE QUE NO HUBO ORDENES SUBIDAS
                                                                                $noorders = 1;
                                                                            }

                                                                            //VALIDAMOS LAS ORDENES QUE ***NO*** TIENEN REENVIO
                                                                            $query_noreshipped = "SELECT id_orden_detalle,tracking,Ponumber,status,cpitem,custnumber 
                                FROM  tbldetalle_orden 
                                WHERE Ponumber IN (" . $sql_ponumber . ")  
                                AND reenvio = 'No'
                                AND estado_orden != 'Canceled' 
                                AND tracking = ''";

//        //DEBUG TEST
//        echo "QUERY DE READY TO SHIP: ". $query_noreshipped . "<br />";

                                                                            $result_noreshipped = mysqli_query($link, $query_noreshipped) or die("Error validando ordenes LISTAS PARA ENVIAR");
                                                                            while ($row_noreshipped = mysqli_fetch_array($result_noreshipped)) {

//                                                                                if ($row_noreshipped['status'] !== "shipped" && $row_noreshipped['status'] !== "New") {
                                                                                $tracking_key = array_search("'" . $row_noreshipped['Ponumber'] . "'", $array_ponumber);

                                                                                $compare_custnumber = "'" . $row_noreshipped['custnumber'] . "'";
                                                                                $compare_cpitem = "'" . $row_noreshipped['cpitem'] . "'";

                                                                                if ($compare_custnumber == $array_custnumber[$tracking_key] && $compare_cpitem == $array_item[$tracking_key]) {
                                                                                    if (!empty($tracking_key)) {
                                                                                        if (!empty($array_tracking[$tracking_key])) {
                                                                                            $sqlup2_case .= "WHEN " . $row_noreshipped['id_orden_detalle'] . " THEN  " . $array_tracking[$tracking_key] . " ";
                                                                                            $sql_ordendetalle .= "'" . $row_noreshipped['id_orden_detalle'] . "',";

                                                                                            //ANUNCIAMOS LOS TRACKINGS QUE SE HAN CARGADO
                                                                                            echo "Se ha insertado El tracking: <b><font color='red'>" . $array_tracking[$tracking_key] . "</font></b> Para el id: <b><font color='red'>" . $row_noreshipped['id_orden_detalle'] . "</font></b> Con Ponumber asignado: <b><font color='red'>" . $array_ponumber[$tracking_key] . "</font></b> Con item: <b><font color='red'>" . $array_item[$tracking_key] . "</font></b> Con estado: <b><font color='red'>LISTO PARA ENVIAR</font></b> <br />";
                                                                                            $countup++;

                                                                                            //REMOVEMOS EL PO PARA NO INSERTAR TRACKING REPETIDO
                                                                                            $array_ponumber[$tracking_key] = "PONUMBER USADO";
                                                                                        } else {
                                                                                            echo "No se pudo alimentar el Ponumber asignado: <b><font color='red'>" . $array_ponumber[$tracking_key] . "</font></b> Porque su: <b><font color='red'>TRACKING</font></b> está repetido dentro del archivo <br />";
                                                                                        }
                                                                                    }
                                                                                }
//                                                                                } else {
//
//                                                                                    //DOY REPORTE DE QUE NO SE ACTUALIZO POR SER "SHIPPED" O "NEW"
//                                                                                    $tracking_key = array_search("'" . $row_noreshipped['Ponumber'] . "'", $array_ponumber);
//                                                                                    if (!empty($tracking_key)) {
//                                                                                        echo "El tracking: <b><font color='red'>" . $array_tracking[$tracking_key] . "</font></b> Con el id: <b><font color='red'>" . $row_noreshipped['id_orden_detalle'] . "</font></b> Con Ponumber asignado: <b><font color='red'>" . $array_ponumber[$tracking_key] . "</font></b> -- <b><font color='red'> NO  </font></b> se actualizó por tener estado: <b><font color='red'> " . $row_noreshipped['status'] . " </font></b><br />";
//                                                                                    }
//                                                                                }
                                                                            }

                                                                            //REMOVEMOS LA ULTIMA COMA PARA EVITAR ERROR DE SINTAXIS
                                                                            $sql_ordendetalle = substr(trim($sql_ordendetalle), 0, -1);

                                                                            //SI ENCUENTRA ORDENES READY TO SHIP HACE EL UPDATE
                                                                            if (!is_null($sqlup2_case)) {
                                                                                $update2 = "UPDATE tbldetalle_orden 
                        SET status = 'shipped', tracking = 
                            CASE id_orden_detalle
                                " . $sqlup2_case . "
                            END
                        WHERE id_orden_detalle IN (" . $sql_ordendetalle . ");";
                                                                                mysqli_query($link, $update2) or die("Error cargando los trackings de ordenes reenviadas");
                                                                            } else {

                                                                                //PRIMERO VALIDAMOS SI SE ALIMENTO EL PRIMER VALIDADOR
                                                                                if ($noorders == 1) {
                                                                                    //SEGUNDO VALIDADOR DE QUE NO HUBO ORDENES SUBIDAS
                                                                                    $noorders = 2;
                                                                                }
                                                                            }

                                                                            //SI NO HUBO ORDENES PARA SUBIR, AVISAMOS EN PANTALLA
                                                                            if ($noorders == 2) {
                                                                                echo '<b><font color="red"> NO </font></b> se subieron Trackings a sistema <br />';
                                                                            } else {
                                                                                echo "Total de trackings subidos " . $countup . " <br />";
                                                                                echo ' Todas las ordenes fueron subidas con éxito <br />';
                                                                            }

                                                                            //ELIMINAMOS EL ARCHIVO DEL SERVIDOR
                                                                            $handle = opendir($dir);
                                                                            while ($file = readdir($handle)) {
                                                                                if (is_file($dir . $file)) {
                                                                                    unlink($dir . $file);
                                                                                }
                                                                            }

                                                                            //BOTON DE REGRESAR
                                                                            echo "  
        <form action = 'javascript:history.back(1)'>
        <input type = 'submit' value = 'Volver atras' />
        </form>
        ";
                                                                            ?>
                                                                        </div>
                                                                        <div class="panel-heading">
                                                                            <div class="contenedor" align="center">
                                                                                <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2015 versión 3</strong>
                                                                                <br>
                                                                                    <strong><a href="http://www.bit-store.ec/index.php/contactenos/"  style="color:white">Info</a> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></strong>
                                                                            </div>
                                                                        </div>

                                                                        </div> <!-- /container -->
                                                                        </div>
                                                                        </body>
                                                                        </html>