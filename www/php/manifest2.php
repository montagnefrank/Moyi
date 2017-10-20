<?php
///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");
include ("consecutivo.php");

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
        <title>Confirm manifest</title>
        <script type="text/javascript" src="../js/script.js"></script>
        <link rel="icon" type="image/png" href="../images/favicon.ico" />
        <link rel="stylesheet" type="text/css" media="all" href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
        <script type="text/javascript" src="../js/jquery-1.2.1.pack.js"></script>
        <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
        <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
        <script type="text/javascript" src="../js/calendar.js"></script>
        <script type="text/javascript" src="../js/calendar-en.js"></script>
        <script type="text/javascript" src="../js/calendar-setup.js"></script>

        <link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/octicons.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/zenburn.css" rel="stylesheet" type="text/css">
        <!-- <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">  -->

        <script language="javascript" src="../js/imprimir.js"></script>
        <script type="text/javascript" src="../js/script.js"></script>
        <script src="../bootstrap/js/jquery.js"></script>
        <script src="../bootstrap/js/bootstrap.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
        <script src="../bootstrap/js/bootstrap-submenu.js"></script>
        <script src="../bootstrap/js/docs.js" defer></script>


        <script type="text/javascript">
            function modificar(valor) {
                var v = valor;
                window.open("modificarorden.php?codigo=" + v, "Cantidad", "width=900,height=880,top=50,left=300");
                return false;
            }
            function cancelar(valor) {
                //alert ('Hola');
                var v = valor;
                window.open("eliminar.php?codigo=" + v, "Cantidad", "width=300,height=150,top=350,left=400");
                return false;
            }

            function eliminar(valor) {
                //alert ('Hola');
                var v = valor;
                window.open("eliminarorden.php?codigo=" + v, "Cantidad", "width=300,height=150,top=350,left=400");
                return false;
            }
        </script>
        <script language="javascript">
            function Compara(frmFec)
            {
                var fecha1 = document.getElementById('txtinicio').value;
                var fecha2 = document.getElementById('txtfin').value;
                var Anio = (frmFec.txtinicio.value).substr(0, 4)
                var Mes = ((frmFec.txtinicio.value).substr(5, 2)) * 1
                var Dia = (frmFec.txtinicio.value).substr(8, 2)
                var Anio1 = (frmFec.txtfin.value).substr(0, 4)
                var Mes1 = ((frmFec.txtfin.value).substr(5, 2)) * 1
                var Dia1 = (frmFec.txtfin.value).substr(8, 2)
                var Fecha_Inicio = new Date(Anio, Mes, Dia)
                var Fecha_Fin = new Date(Anio1, Mes1, Dia1)

                if (fecha1 == '' && fecha2 == '')
                {
                    alert("Las fechas no pueden ser vacías. Tiene que tener algún valor para buscar");
                    return false;
                }
                if (Fecha_Inicio > Fecha_Fin)
                {
                    alert("La fecha de inicio es mayor que la fecha de fin; Introduzca un período válido");
                    return false;
                } else
                {
                    return true;
                }
            }
        </script>
        <script>
            function validar_texto(e) {
                tecla = (document.all) ? e.keyCode : e.which;

                //Tecla de retroceso para borrar, siempre la permite
                if (tecla == 8) {
                    //alert('No puede ingresar letras');
                    return true;
                }

                // Patron de entrada, en este caso solo acepta numeros
                patron = /[0-9]/;

                tecla_final = String.fromCharCode(tecla);
                return patron.test(tecla_final);
            }
        </script>


        <style>
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
            li a{
                cursor:pointer;/*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
            } 
        </style>
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
                                <a class="navbar-brand" href="administration.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
                            </div>



                            <!-- Agrupar los enlaces de navegación, los formularios y cualquier
                                 otro elemento que se pueda ocultar al minimizar la barra -->
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                <ul class="nav navbar-nav">
                                    <li class="dropdown">
                                        <a tabindex="0" data-toggle="dropdown">
                                            <strong>Pagos</strong><span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="subircostco.php"><strong>Subir Archivo de Costo</strong></a></li>
                                            <li class="divider"></li>
                                            <li><a href="pagosycreditos.php"><strong>Pagos y Créditos Manuales</strong></a></li>
                                        </ul>
                                    </li>

                                    <li class="dropdown">
                                        <a tabindex="0" data-toggle="dropdown">
                                            <strong>Reportes</strong><span class="caret"></span>     
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li class="dropdown-submenu">
                                                <a tabindex="0" data-toggle="dropdown"><strong>Reportes Manifest Costco</strong></a>            
                                                <ul class="dropdown-menu">                               
                                                    <li><a href="manifest.php">Reporte Manifest Costco</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="manifestfull.php">Reporte Manifest Costco Completo</a></li>
                                                </ul>
                                            </li>
                                            <li class="divider"></li>
                                            <li class="dropdown-submenu">
                                                <a tabindex="0" data-toggle="dropdown"><strong>Ventas</strong></a>            
                                                <ul class="dropdown-menu">                               
                                                    <li><a href="venta.php?id=1">Total Vendidos</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="venta.php?id=2">Créditos</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="venta.php?id=3">Neto Vendidos</a></li>
                                                </ul>
                                            </li>
                                            <li class="divider"></li>
                                            <li class="dropdown-submenu">
                                                <a tabindex="0" data-toggle="dropdown"><strong>Pagos</strong></a>            
                                                <ul class="dropdown-menu">                               
                                                    <li><a href="pagos.php">Pagos por Costco</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="cuadre.php">Cuadre de pagos</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>


                                    <?php
                                    if ($rol == 4) {
                                        $sql = "SELECT id_usuario from tblusuario where cpuser='" . $user . "'";
                                        $query = mysqli_query($link, $sql);
                                        $row = mysqli_fetch_array($query);
                                        echo '<li><a href="" onclick="cambiar(\'' . $row[0] . '\')"><strong>Contraseña</strong></a>';
                                    }
                                    ?> 
                                </ul>  <!--Fin del navbar -->
                                <ul class="nav navbar-nav navbar-right">
                                    <li><a class="navbar-brand" href="" data-toggle="tooltip" data-placement="bottom" title="Usuario conectado"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo $user ?></a></li>
                                    <li><a class="navbar-brand" href="../index.php" data-toggle="tooltip" data-placement="bottom" title="Salir del sistema" ><span class="glyphicon glyphicon-off" aria-hidden="true"></span></a></li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="panel-body" align="center">
                    <form id="form" name="form" method="post">
                        <div class="table-responsive">
                            <table width="50%" border="0" align="center" class="table table-responsive">
                                </form>
                                <tr>
                                    <td>
                                        <form method="post" id="frm1" name="frm1" target="_parent" >
                                            <div class="table-responsive">
                                                <table width="50%" border="0" align="center" class="table table-responsive">

                                                    <tr>
                                                        <td  colspan="5" align="center"> <strong>CANCELAR ORDENES DE CONFIRM MANIFEST</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4">
                                                            <div class="row">
                                                                <div class="col-md-4" align="right">
                                                                    <label>Cliente:</label>
                                                                </div>
                                                                <div class="col-md-8" align="left">
                                                                    <select type="text" name="vendor" id="vendor" class="form-control" style="width: 70%;">
                                                                        <option value="-1">Seleccionar</option>
                                                                        <?php
                                                                        //CONSULTO LOS VENDOR EN TBLDETALLE_ORDEN
                                                                        $select_vendor = "SELECT DISTINCT vendor FROM tbldetalle_orden ORDER BY vendor";
                                                                        $result_vendor = mysqli_query($link, $select_vendor);
                                                                        while ($row_vendor = mysqli_fetch_array($result_vendor)) {

                                                                            if (isset($_POST[''])) {
                                                                                if ($_POST['vendor'] != $row_vendor[0])
                                                                                    echo '<option value="' . $row_vendor[0] . '">' . $row_vendor[0] . '</option>';
                                                                                else
                                                                                    echo '<option value="' . $row_vendor[0] . '" selected="selected">' . $row_vendor[0] . '</option>';
                                                                            }
                                                                            else {
                                                                                echo '<option value="' . $row_vendor[0] . '">' . $row_vendor[0] . '</option>';
                                                                            }
                                                                        }
                                                                        ?>                       
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td  align="center">
                                                            <h5 style="margin-top: 6px;"><strong>Fecha de Envio</strong></h5>
                                                        </td>
                                                        <td align="center"> 
                                                            <input class="form-control" name="txtinicio" type="text" id="txtinicio" size="20" placeholder="Desde"/>
                                                            <script type="text/javascript">
                                                                function catcalc(cal) {
                                                                    var date = cal.date;
                                                                    var time = date.getTime()
                                                                    // use the _other_ field
                                                                    var field = document.getElementById("f_calcdate");
                                                                    if (field == cal.params.inputField) {
                                                                        field = document.getElementById("txtinicio");
                                                                        time -= Date.WEEK; // substract one week
                                                                    } else
                                                                    {
                                                                        time += Date.WEEK; // add one week
                                                                    }
                                                                    var date2 = new Date(time);
                                                                    field.value = date2.print("%Y-%m-%d");
                                                                }
                                                                Calendar.setup({
                                                                    inputField: "txtinicio", // id of the input field
                                                                    ifFormat: "%Y-%m-%d ", // format of the input field
                                                                    showsTime: false,
                                                                    timeFormat: "24",
                                                                    onUpdate: catcalc
                                                                });
                                                            </script>
                                                        </td>
                                                        <td align="center">
                                                            <input class="form-control" name="txtfin" type="text" id="txtfin" size="20" placeholder="Hasta"/>
                                                            <script type="text/javascript">
                                                                function catcalc(cal) {
                                                                    var date = cal.date;
                                                                    var time = date.getTime()
                                                                    // use the _other_ field
                                                                    var field = document.getElementById("f_calcdate");
                                                                    if (field == cal.params.inputField) {
                                                                        field = document.getElementById("txtfin");
                                                                        time -= Date.WEEK; // substract one week
                                                                    } else {
                                                                        time += Date.WEEK; // add one week
                                                                    }
                                                                    var date2 = new Date(time);
                                                                    field.value = date2.print("%Y-%m-%d");
                                                                }
                                                                Calendar.setup({
                                                                    inputField: "txtfin", // id of the input field
                                                                    ifFormat: "%Y-%m-%d ", // format of the input field
                                                                    showsTime: false,
                                                                    timeFormat: "24",
                                                                    onUpdate: catcalc
                                                                });

                                                            </script>
                                                        </td>
                                                        <td align="center">
                                                            <input type="submit" name="buscar" id="buscar" value="Buscar" onclick="return Compara(this.form)" class="btn btn-primary"/>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>  <!-- table responsive -->
                                        </form>
                                    </td>
                                </tr>
                                <?php
                                if (isset($_POST['buscar'])) {
                                    $fecha1 = $_POST['txtinicio'];
                                    $fecha2 = $_POST['txtfin'];

                                    if (isset($_POST['vendor'])) {
                                        $vendor = " AND vendor = '" . $_POST['vendor'] . "' ";
                                    } else {
                                        $vendor = "  ";
                                    }

                                    //CABECEROS DE LA TABLA Y BOTONES DE EXPORTACION APARECEN CUANDO HACES CLIC EN BUSCAR
                                    echo '
                                        <tr align="right">
                                            <td align="right">
                                                <div align="right" class="row">
                                                    <div class="col-md-10" align="right">
                                                        <iframe id="txtArea1" style="display:none"></iframe>
                                                        <button id="btnExport" class = "btn btn-primary" onclick="fnExcelReport();"><img src="../images/excel.gif" width="30" height="30" /> Exportar a Excel </button>
                                                    </div>
                                                    <div class="col-md-2" align="right">
                                                        <form action="crearxml2.php" method="post" target="_blank">
                                                            <button name="btn_xml" id="btn_xml" class = "btn btn-primary"><img src="../images/xml.png" width="30" height="30" /> XML para Cancelar </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                        <td id="inicio" bgcolor="" height="100"> 
                                            <div class="table-responsive">
                                                <table id="exportarTabla" width="50%" border="0" align="center" class="table table-responsive"> 
                                                    <tr>
                                                        <td colspan="8" align="center">
                                                            <h3><strong>Lista de Órdenes</strong></h3>
                                                        </td>
                                                    </tr>
                                                    <tr bgcolor="#E8F1FD">
                                                        <td width="132" align="center"><strong>Cliente</strong></td>
                                                        <td width="138" align="center"><strong>Ponumber</strong></td>
                                                        <td width="89" align="center"><strong>Po line #</strong></td>
                                                        <td width="98" align="center"><strong>Cantidad</strong></td>
                                                        <td width="130" align="center"><strong>Código Carrier</strong></td>
                                                        <td width="216" align="center"><strong>Tracking</strong></td>
                                                        <td width="116" align="center"><strong>Fecha de Entrega</strong></td>
                                                        <td width="71" align="center"><strong>eBinv</strong></td>
                                                    </tr>
                                                    <form id="form" name="form" method="post">
                                    ';

                                    //NO ARROJA DATA SI LAS FECHAS ESTAN VACIAS
                                    if ($fecha1 != '' && $fecha2 != '') {

                                        //GENERAMOS EL CONSECUTIVO PARA CARGAR LOS EBING
                                        $select_consecutivo = "SELECT ebing 
                                                                FROM tbldetalle_orden 
                                                                ORDER BY ebing DESC 
                                                                LIMIT 1";
                                        $result_consecutivo = mysqli_query($link, $select_consecutivo);
                                        $row_consecutivo = mysqli_fetch_array($result_consecutivo);
                                        $consecutivo = array_shift($row_consecutivo);

                                        //ESTE ES EL SIGUIENTE EBING A AGREGAR AL SISTEMA
                                        $consecutivo++;

                                        //CONSULTAMOS LOS EBING QUE FALTEN POR GENERAR
                                        $select_ebing = "SELECT DISTINCT Ponumber,eBing,tracking 
                                                            FROM tbldetalle_orden 
                                                            WHERE delivery_traking BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "' 
                                                            AND tracking='' "
                                                . $vendor .
                                                " ;";
                                        $result_ebing = mysqli_query($link, $select_ebing);
                                        while ($row_ebing = mysqli_fetch_array($result_ebing)) {
                                            if ($row_ebing['eBing'] == 0) {
                                                $where_ponumberebing .= "'" . $row_ebing['Ponumber'] . "',";
                                                $case_ebing .= "WHEN " . $row_ebing['Ponumber'] . " THEN " . $consecutivo++ . " ";
                                            }
                                            $where_ponumberin .= "'" . $row_ebing['Ponumber'] . "',";
                                        }
                                        //REMOVEMOS LA ULTIMA COMA PARA EVITAR ERROR DE SINTAXIS
                                        $where_ponumberin = substr(trim($where_ponumberin), 0, -1);
                                        $_SESSION['cancelarordenes'] = $where_ponumberin;
                                        //SI ENCONTRO EBING VACIOS HACEMOS EL UPDATE
                                        if (isset($where_ponumberebing)) {

                                            //REMOVEMOS LA ULTIMA COMA PARA EVITAR ERROR DE SINTAXIS
                                            $where_ponumberebing = substr(trim($where_ponumberebing), 0, -1);
                                            //CARGAMOS LOS EBING GENERADOS AL SISTEMA
                                            $update_ebing = "UPDATE tbldetalle_orden 
                                                                SET eBing = 
                                                                CASE Ponumber " . $case_ebing . " END 
                                                                WHERE Ponumber IN (" . $where_ponumberebing . ") ";
//                                            $update_ebing = "UPDATE tbldetalle_orden 
//                                                                SET estado_orden='Canceled', 
//                                                                status='Not Shipped', 
//                                                                unitprice= (tbldetalle_orden.unitprice*-1),
//                                                                eBing = 
//                                                                CASE Ponumber " . $case_ebing . " END 
//                                                                WHERE Ponumber IN (" . $where_ponumberebing . ") ";
                                            $result_update_ebing = mysqli_query($link, $update_ebing);
                                        }

                                        //REMOVEMOS LA ULTIMA COMA PARA EVITAR ERROR DE SINTAXIS
                                        $where_ponumberin = substr(trim($where_ponumberin), 0, -1);

//                                        //EXTRAEMOS LA DATA DE LA TABLA DE COSTOS
//                                        $select_costo = "SELECT costo
//                                                            FROM tblcosto 
//                                                            WHERE po IN (" . $where_ponumberin . ")";
//                                        $result_costo = mysqli_query($link, $select_costo) or die("Error consultando en la tabla de costos");
//                                        $num_costo = mysqli_num_rows($result_costo);
//                                        //EXTRAEMOS LA SUMATORIA DE LOS COSTOS DE LA TABLA DETALLE_ORDEN
//                                        $select_detallecosto = "SELECT SUM(unitprice),delivery_traking,Ponumber,eBing
//                                                                    FROM tbldetalle_orden 
//                                                                    WHERE Ponumber IN (" . $where_ponumberin . ")
//                                                                    AND estado_orden='Active' 
//                                                                    AND tracking='' 
//                                                                    AND status='Shipped'"
//                                                . $vendor . "
//                                                                    GROUP BY Ponumber";
//                                        $result_detallecosto = mysqli_query($link, $select_detallecosto) or die("Error sumando los costos en el detalle de orden");
                                        //SI EL PONUMBER NO EXISTE EN LA TABLA DE COSTOS
                                        if ($num_costo == 0) {

                                            //ALIMENTAMOS LA DATA PARA LOS QUERY
                                            while ($row_detallecosto = mysqli_fetch_array($result_detallecosto)) {
                                                if ($row_detallecosto[0] >= 0) {
                                                    $insert_valuescosto .= "('" . $row_detallecosto['Ponumber'] . "','" . $row_detallecosto['eBing'] . "','" . $row_detallecosto[0] . "','0','No','" . $row_detallecosto[1] . "'),";
                                                } else {
                                                    $where_ponumbercosto .= "'" . $row_detallecosto['Ponumber'] . "',";
                                                }
                                            }

                                            //REMOVEMOS LA ULTIMA COMA PARA EVITAR ERROR DE SINTAXIS
                                            $insert_valuescosto = substr(trim($insert_valuescosto), 0, -1);

//                                            //EJECUTAMOS EL INSERT EN CASO DE CONTENER DATA
//                                            if (isset($insert_valuescosto)) {
//                                                $insert_costo = "INSERT INTO tblcosto 
//                                                                    (`po`,`ebinv`,`costo`,`credito`,`pagado`, `fecha_facturacion`) 
//                                                                    VALUES " . $insert_valuescosto;
//                                                $result_insert_costo = mysqli_query($link, $insert_costo) or die("Error Insertando en la tabla de costos");
//                                            }
                                            //REMOVEMOS LA ULTIMA COMA PARA EVITAR ERROR DE SINTAXIS
                                            $where_ponumbercosto = substr(trim($where_ponumbercosto), 0, -1);

//                                            //EJECUTAMOS EL UPDATE EN CASO DE CONTENER DATA
//                                            if (isset($where_ponumbercosto)) {
//                                                $delete_costo = "DELETE FROM tblcosto
//                                                                    WHERE po IN (" . $where_ponumbercosto . ")";
//                                                $result_delete_costo = mysqli_query($link, $delete_costo) or die("Error Eliminando de la tabla de costos");
//                                            }
                                        } else {    //SI EL PONUMBER YA EXISTE EN LA TALBA DE COSTOS
                                            while ($row_detallecosto = mysqli_fetch_array($result_detallecosto)) {
                                                $where_ponumbercosto .= "'" . $row_detallecosto['Ponumber'] . "',";
                                                $case_costo .= "WHEN '" . $row_detallecosto['Ponumber'] . "' THEN '" . $row_detallecosto[0] . "' ";
                                            }

                                            //REMOVEMOS LA ULTIMA COMA PARA EVITAR ERROR DE SINTAXIS
                                            $where_ponumbercosto = substr(trim($where_ponumbercosto), 0, -1);

                                            //EJECUTAMOS EL UPDATE EN CASO DE CONTENER DATA
//                                            if (isset($where_ponumbercosto)) {
//                                                $update_costo = "UPDATE tblcosto 
//                                                                SET costo =
//                                                                CASE po " . $case_costo . " END 
//                                                                WHERE po IN (" . $where_ponumbercosto . ") ";
//                                                $result_update_costo = mysqli_query($link, $update_costo) or die("Error Actualizando la tabla de costos");
//                                            }
                                        }

                                        //GENERAMOS LOS PACKAGEDETAILID
                                        $select_pack = "SELECT tracking 
                                                            FROM tbldetalle_orden 
                                                            WHERE delivery_traking 
                                                            BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "' 
                                                            AND tracking='' 
                                                            AND status='Shipped'"
                                                . $vendor . "
                                                            AND estado_orden='Active'";
                                        $result_pack = mysqli_query($link, $select_pack) or die("Error consultando la tabla para los PackageDetailId");

                                        //ALIMENTAMOS LA DATA PARA EL QUERY
                                        while ($row_pack = mysqli_fetch_array($result_pack)) {
                                            $pack = 'PDID-' . $row_pack['tracking'];
                                            $where_trackingpack .= "'" . $row_pack['tracking'] . "',";
                                            $case_pack .= "WHEN '" . $row_pack['tracking'] . "' THEN '" . $pack . "' ";
                                        }

                                        //REMOVEMOS LA ULTIMA COMA PARA EVITAR ERROR DE SINTAXIS
                                        $where_trackingpack = substr(trim($where_trackingpack), 0, -1);

//                                        //EJECUTAMOS EL UPDATE EN CASO DE CONTENER DATA
//                                        if (isset($where_trackingpack)) {
//                                            $update_pack = "UPDATE tbldetalle_orden 
//                                                                SET packagedetailid =
//                                                                CASE tracking " . $case_pack . " END 
//                                                                WHERE tracking IN (" . $where_trackingpack . ") ";
//                                            $result_update_pack = mysqli_query($link, $update_pack) or die("Error Actualizando los PackageDetailId");
//                                        }

                                        $select_report = "SELECT vendor,Ponumber,poline,cpcantidad,ups, 
                                                                 tracking,delivery_traking,eBing,cpitem, 
                                                                 merchantSKU,shippingWeight,weightUnit, 
                                                                 partnerTrxID,packageDetailID,merchantLineNumber 
                                                            FROM tbldetalle_orden 
                                                            WHERE delivery_traking BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "' 
                                                            AND eBing != '0' AND tracking='' 
                                                            AND status!='Shipped' "
                                                . $vendor .
                                                "ORDER BY eBing";
                                        $result_report = mysqli_query($link, $select_report) or die("Error Consultando el reporte en pantalla");
                                        while ($row_report = mysqli_fetch_array($result_report)) {
                                            $cant ++;
                                            echo "<tr>";
                                            echo "<td align='center'>" . $row_report['vendor'] . "</td>";
                                            echo "<td align='center'>" . $row_report['Ponumber'] . "</td>";
                                            echo "<td align='center'><strong>" . $row_report['poline'] . "</strong></td>";
                                            echo "<td align='center'><strong>" . $row_report['cpcantidad'] . "</strong></td>";
                                            echo "<td align='center'><strong>" . $row_report['ups'] . "</strong></td>";
                                            echo "<td align='center'>" . $row_report['tracking'] . "</td>";
                                            echo "<td align='center'>" . $row_report['delivery_traking'] . "</td>";
                                            echo "<td align='center'>" . $row_report['eBing'] . "</td>";
                                            echo "</tr>";
                                        }
                                        echo "<tr><td align ='center'><strong>" . $cant . "</strong></td><td><strong>Órdenes generadas</strong></td></tr>";
                                        echo "</td></tr></table>";
                                        $_SESSION["sql2"] = $select_report;
                                    } //IF FECHAS VACIAS
                                } //IF POST BUSCAR
                                ?>
                                </form>
                            </table>
                        </div>   <!-- table responsive -->
                    </form>
                </div>    <!-- table responsive -->
                <div class="panel-heading">
                    <div class="contenedor" align="center">
                        <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2015 versión 3</strong>
                        <br />
                        <strong><a href="http://www.bit-store.ec/index.php/contactenos/"  style="color:white">Info</a> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></strong>
                    </div>
                </div>
            </div> <!-- panel panel-primary -->
        </div> <!-- panel heading -->
    </div>   <!-- /container -->
</body>
</html>

