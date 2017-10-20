<?php
///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL); 
//ini_set('display_errors', 1);


session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");

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

$user = $_SESSION["login"];
$passwd = $_SESSION["passwd"];
$bd = $_SESSION["bd"];
$rol = $_SESSION["rol"];

//Identificar que finca es para mostrar sus pediso	
$sql = "Select cpnombre from tblusuario where cpuser='" . $user . "'";
$query = mysqli_query($link, $sql)or die("Error identificando nombre de finca");
$row = mysqli_fetch_array($query);
$finca = $row['cpnombre'];
$_SESSION["finca"] = $finca;

//VErificar si ha registrado los DAE de US y CA
//Obtener el mes de la fecha que estoy pasando
$hoy = date('Y-m-d');
$mes = substr($hoy, 5, 2);
$mes1 = $mes + 1;
$mes1 = str_pad($mes1, 2, "0", STR_PAD_LEFT);
$x = "SELECT * FROM tbldae WHERE nombre_finca = '" . $finca . "' AND url != 'eliminado' AND (ffin like '%-" . $mes . "-%' OR ffin like '%-" . $mes1 . "-%')";
$y = mysqli_query($link, $x);
$z = mysqli_fetch_array($y);
$cant1 = mysqli_num_rows($y);


$a = "SELECT ffin, pais_dae FROM tbldae WHERE nombre_finca = '" . $finca . "' AND ffin < '" . date('Y-m-d') . "' AND url != 'eliminado'";
$b = mysqli_query($link, $a);
$c = mysqli_fetch_array($b);
$cant = mysqli_num_rows($b);
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
        <link href="../bootstrap/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/octicons.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/zenburn.css" rel="stylesheet" type="text/css">
        <link href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1"rel="stylesheet" type="text/css" media="all"  />
        <!-- <link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">  -->
        <link rel="stylesheet" type="text/css" href="../css/lightbox.css">

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

            .modal-header{
                background-color: #3B5998;
                color: white;
                border-radius:5px 5px 0 0;
            }
        </style>
        <script>
            function cambiar(valor) {
                var v = valor;
                window.open("cambiarcontrasenna.php?codigo=" + v, "Cantidad", "width=400,height=300,top=150,left=400");
                return false;
            }
        </script>
        <script type="text/javascript">
            function modificar(valor) {
                var v = valor;
                window.open("modificarentrada.php?codigo=" + v, "Cantidad", "width=500,height=400,top=150,left=350");
                return false;
            }
            function nueva() {
                window.open("nuevaentrada.php", "Cantidad", "width=500,height=400,top=150,left=350");
                return false;
            }
            function eliminar(valor) {
                var v = valor;
                window.open("eliminarentrada.php?codigo=" + v, "Cantidad", "width=250,height=150,top=300,left=400");
                return false;
            }

            function print1(finca, fecha, reimprimir, vuelo, agencia) {
                window.open("print.php?finca=" + finca + "&fecha=" + fecha + "&status=" + reimprimir + "&vuelo=" + vuelo + "&agencia=" + agencia + "", "Imprimir", "width=300,height=200,top=200,left=350");
                return false;
            }
            function print2(finca, fecha, item, reimprimir, vuelo, agencia) {
                window.open("print.php?finca=" + finca + "&fecha=" + fecha + "&item=" + item + "&status=" + reimprimir + "&vuelo=" + vuelo + "&agencia=" + agencia + "", "Imprimir", "width=300,height=200,top=200,left=350");
                return false;
            }

            $(document).ready(function () {
                //tol-tip-text
                $(function () {
                    $('[data-toggle="tooltip"]').tooltip();
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
        <script>

            $(document).ready(function ($) {
                $.ajax({
                            data: "finca=<?php echo $finca ?>",
                            url:   'infodae.php',
                            type:  'post',
                    dataType: 'json',
                    success:  function (response) {
                        if (response.id == 'US' || response.id == 'CA')
                        {
                            $('#DaeModal').find('#mensaje').html('Su DAE de ' + response.id + ' ha expirado, por favor asigne uno válido.');
                            $('#DaeModal').modal('show');
                        }
                        if (response.id == 'New')
                        {
                            $('#DaeModal').find('#mensaje').html('Debe registrar los DAE de US y CA antes de imprimir las etiquetas.');
                            $('#DaeModal').modal('show');
                        }


                    },
                    error: function (response) {

                        
                            }
                       });
            });
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
                                    <?php
                                    if ($rol == 10) {
                                        echo '<li><a href="subirTrackingColombia.php"><strong>Cargar Tracking</strong></a></li>';
                                    }
                                    ?>
                                    <li><a href="dae.php"><strong>Ingresar DAE</strong></a></li>
                                    <?php
                                    echo '<li><a href="fact_com.php?finca=' . $finca . '"><strong>Facturas Comerciales</strong></a></li>';

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
                    <img src="../images/inicio.jpg" alt="Foto Portada" width="607" height="171" class="img-responsive">
                    <br>
                    <form id="form1" name="form1" method="post">
                        <div class="table-responsive">
                            <table width="50%" border="0" align="center" class="table table-striped" >
                                <tr>
                                    <td align="center"><h3><strong>Filtrar por fecha de entrega</strong></h3></td>
                                    <td width="65" align="left">
                                        <a href="crearcsv3.php"> <img src="../images/excel.png" heigth="40" value="" title = "Exportar a excel" width="30"></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" colspan="2"> 
                                        <strong>Fecha de Entrega:</strong>
                                        <input name="fecha" type="text" id="fecha" readonly="readonly" size="20"/>
                                        <input type="submit" name="buscar" id="buscar" class= "btn btn-primary" value="Buscar" data-toggle="tooltip" data-placement="rigth" title="Buscar fecha de entrega"/></td>
                                </tr>
                            </table>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table width="50%" border="0" align="center" class="table table-striped" >
                            <tr>
                                <td colspan="11" align="center">
                                    <h3><strong>Solicitudes sin entregar</strong><font color="#FF0000"></font></h3>
                                </td>
                            </tr>
                            <?php
                            if (!isset($_POST['buscar'])) {

                                $_SESSION["fecha"] = null;
                                $_SESSION["sqlcsv"] = "SELECT distinct fecha,fecha_tentativa,agencia,destino FROM tbletiquetasxfinca where finca='" . $finca . "' AND archivada = 'No' AND estado !='5' order by fecha";
                                $hoy = date('Y-m-d');

                                //selecciono todos los posibles destinos
                                $sqldestino = "SELECT * FROM tblpaises_destino";
                                $querydestino = mysqli_query($link, $sqldestino) or die("Error leyendo los paises");
                                $i = 1;
                                while ($rowdestino = mysqli_fetch_array($querydestino)) {

                                    //Selecciono cada item con solicitud activa
                                    $sql = "SELECT distinct fecha,fecha_tentativa,agencia,destino FROM tbletiquetasxfinca where finca='" . $finca . "' AND archivada = 'No' AND estado !='5' AND destino='" . $rowdestino['codpais'] . "' order by fecha";

                                    $val = mysqli_query($link, $sql)or die("Error seleccionando los pedidos");

                                    //Recorro por cada nro pedido cada item
                                    $contador = 1;
                                    $ponerdestino = false;
                                    while ($row = mysqli_fetch_array($val)) {
                                        if (!$ponerdestino)
                                            echo '<tr><td colspan="12" style="background:#428bca;">' . $rowdestino['pais_dest'] . '</td>';
                                        $ponerdestino = true;
                                        echo "<tr>";
                                        echo "<td align='left' colspan='2'><strong>Fecha de entrega: " . $row['fecha'] . "</strong></td>";
                                        echo "<td align='left' colspan='4'><strong>Fecha de vuelo: " . $row['fecha_tentativa'] . "</strong></td>";
                                        echo "<td align='left' colspan='5'><strong>Agencia de carga: <font color='red'>" . $row['agencia'] . "</font></strong></td>";
                                        echo "</tr>";
                                        echo '<tr>
							<td align="center"><strong>Item</strong></td>
                                                        <td align="center"><strong>Imagen</strong></td>
							<td align="center"><strong>Prod. Desc.</strong></td>
							<td align="center"><strong>Solicitadas</strong></td>
							<td align="center"><strong>Enviadas</strong></td> 
							<td align="center"><strong>Rechazadas</strong></td>
							<td align="center"><strong>Cierre de Día</strong></td>
							<td align="center"><strong>Por enviar</strong></td>
							<td align="center"><strong>Valor unitario</strong></td>
							<td align="center"><strong>Valor a pagar</strong></td>
							<td align="center"><strong>Imprimir etiquetas</strong></td>
						  </tr>	';

                                        //Selecciono cada nropedido los items
                                        $sentencia = "SELECT distinct item,precio FROM tbletiquetasxfinca where fecha='" . $row['fecha'] . "' AND fecha_tentativa='" . $row['fecha_tentativa'] . "' AND finca='" . $finca . "' AND archivada = 'No' AND estado !='5' AND destino='" . $rowdestino['codpais'] . "' AND agencia='" . $row['agencia'] . "' order by item";

                                        $consulta = mysqli_query($link, $sentencia)or die("Error seleccionando los items con solicitudes");
                                        $iii = 0;
                                        //Por cada item cuento cuantas solicitudes hay
                                        while ($fila = mysqli_fetch_array($consulta)) {
                                            //Se cuenta cuantas solicitudes y entregas hay por cada finca e item
                                            $sql1 = "SELECT SUM(solicitado) as solicitado,SUM(entregado) as entregado, item, fecha, precio FROM tbletiquetasxfinca where fecha='" . $row['fecha'] . "' AND fecha_tentativa='" . $row['fecha_tentativa'] . "' AND finca='" . $finca . "' AND precio='" . $fila['precio'] . "' AND estado!='5' AND item = '" . $fila['item'] . "'AND archivada = 'No' AND destino='" . $rowdestino['codpais'] . "' AND agencia='" . $row['agencia'] . "' GROUP BY precio";
                                            $val1 = mysqli_query($link, $sql1) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                            mysqli_data_seek($val1, "" . $iii . "");
                                            $row1 = mysqli_fetch_array($val1);

                                            //$_SESSION["sql1"] = $sql1;
                                            //Se cuenta cuantas solicitudes rechazadas hay por cada finca e item
                                            $sql2 = "SELECT COUNT(*) as rechazado,nropedido FROM tbletiquetasxfinca where estado='2' AND fecha = '" . $row['fecha'] . "' AND fecha_tentativa='" . $row['fecha_tentativa'] . "' AND finca='" . $finca . "' AND item = '" . $fila['item'] . "' AND archivada = 'No' AND destino='" . $rowdestino['codpais'] . "' AND agencia='" . $row['agencia'] . "'";
                                            $val2 = mysqli_query($link, $sql2) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                            $row2 = mysqli_fetch_array($val2);

                                            //Se cuenta cuantas solicitudes con cierre de dia por cada finca e item
                                            $sql3 = "SELECT COUNT(*) as cierre FROM tbletiquetasxfinca where estado= '3' AND fecha = '" . $row['fecha'] . "' AND fecha_tentativa='" . $row['fecha_tentativa'] . "' AND finca='" . $finca . "' AND item = '" . $fila['item'] . "' AND archivada = 'No' AND destino='" . $rowdestino['codpais'] . "' AND agencia='" . $row['agencia'] . "'";
                                            $val3 = mysqli_query($link, $sql3) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                            $row3 = mysqli_fetch_array($val3);

                                            echo "<tr>";
                                            echo "<td align='center'><strong>" . $row1['item'] . "</strong></td>";

//                                                                //busco el id del item
                                            $sql33 = "SELECT tblproductos.item FROM tblproductos where id_item='" . $row1['item'] . "'";
                                            $val33 = mysqli_query($link, $sql33) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                            while ($row33 = mysqli_fetch_row($val33)) {
                                                echo "<td align='center'><strong><a href='../images/productos/" . $row33[0] . ".jpg' data-lightbox='" . $row33[0] . "'><img id='img' width='50px' heigth='50px' src='../images/productos/" . $row33[0] . ".jpg' data-toggle='tooltip' data-placement='left'/></a></strong></td>";
                                            }


                                            //Seleccionando l adescripcion del item
                                            $sql4 = "SELECT prod_descripcion FROM tblproductos where id_item='" . $row1['item'] . "'";
                                            $val4 = mysqli_query($link, $sql4) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                            $row4 = mysqli_fetch_array($val4);

                                            echo "<td><strong>" . $row4['prod_descripcion'] . "</strong></td>";
                                            echo "<td align='center'>" . $row1['solicitado'] . "</td>";
                                            $totalsol += $row1['solicitado'];
                                            echo "<td align='center'>" . $row1['entregado'] . "</td>";
                                            if ($row2['rechazado'] == 0) {
                                                echo "<td align='center'>" . $row2['rechazado'] . "</td>";
                                            } else {
                                                echo "<td align='center' bgcolor='#FF0000'><strong><a href= 'etiqrechazada.php?id=" . $row2['nropedido'] . "' title='Ver cajas rechazadas'>" . $row2['rechazado'] . "</a></td>";
                                            }

                                            //echo "<td align='center'>".$row2['rechazado']."</td>";
                                            echo "<td align='center'>" . $row3['cierre'] . "</td>";

                                            //se restan las solicitudes - entregado - rechazado	

                                            $totalrech += $row2['rechazado'];
                                            $totalent += $row1['entregado'];
                                            $dif = $row1['solicitado'] - $row1['entregado'] - $row2['rechazado'] - $row3['cierre'];
                                            $totalcierre += $row3['cierre'];
                                            $totaldif += $dif;
                                            $totalprecio += $row1['precio'];
                                            $totalvalor += $row1['precio'] * $row1['solicitado'];

                                            echo "<td align='center'>" . $dif . "</td>";
                                            echo "<td align='center'>" . $row1['precio'] . "</td>";
                                            echo "<td align='center'>" . $row1['precio'] * $row1['solicitado'] . "</td>";


                                            //poner boton de imprimir etiquetas por cada item
                                            if ($dif != 0 && $cant == 0 && $cant1 >= 1) {
                                                echo '<td align="center"><input type="image" style="cursor:pointer" name=" " id="btn_cliente" src="../images/print.png" heigth="30" value="" data-toggle="modal" data-target="#myModal-item' . $i . '" width="20"/></td>';
                                                echo "</tr>";
                                                echo '<div class="modal fade" id="myModal-item' . $i . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                                 <div class="modal-dialog">
                                                                                   <div class="modal-content">
                                                                                     <div class="modal-header">
                                                                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                       <h4 class="modal-title" id="myModalLabel">Importante</h4>
                                                                                     </div>
                                                                                     <div class="modal-body">
                                                                                       <p>El tamaño de impresión de las etiquetas debe ser de 140mm de alto por 90mm de ancho</p>
                                                                                     </div>
                                                                                     <div class="modal-footer">
                                                                                       <button type="button" class="btn btn-default" data-dismiss="modal"  onclick="print2(\'' . $finca . '\',\'' . $row['fecha'] . '\',\'' . $fila['item'] . '\',\'false\',\'' . $row['fecha_tentativa'] . '\',\'' . $row['agencia'] . '\')">Continuar</button>
                                                                                     </div>
                                                                                   </div>
                                                                                 </div>
                                                                               </div>';
                                                $i++; //aumento el numero de id que tendra la proxima ventana modal
                                            } else {
                                                echo '<td align="center"><input disabled="true" type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../images/print.png" heigth="30" value="" width="20" data-toggle="tooltip" data-placement="rigth" title = "No hay etiquetas para imprimir de este pedido o el DAE está caducado"/></td>';
                                                echo "</tr>";
                                            }
                                            $iii++;
                                        }//fin while
                                        echo "
							<tr>
							<td align='right'></td>	
                                                        <td align='right'></td>
							<td align='center'><strong>Total por Fecha:</strong></td>
							<td align='center'><strong>" . $totalsol . "</strong></td>
							<td align='center'><strong>" . $totalent . "</strong></td>
							<td align='center'><strong>" . $totalrech . "</strong></td>
							<td align='center'><strong>" . $totalcierre . "</strong></td>";

                                        if ($totaldif == 0) {
                                            echo "<td align='center'><button type='button' class='btn btn-success' data-toggle='tooltip' data-placement='left' title = 'No hay cajas pendientes'><strong>" . $totaldif . "</strong></button></td>";
                                        } else {
                                            echo "<td align='center'><button type='button' class='btn btn-danger' data-toggle='tooltip' data-placement='rigth' title = 'Hay cajas por entregar'><strong>" . $totaldif . "</strong></button></td>";
                                        }

                                        echo "<td align='center'><strong>" . $totalprecio . "</strong></td>";
                                        echo "<td align='center'><strong>" . $totalvalor . "</strong></td>";

                                        //Contar los subtotales
                                        $TOTALSOL += $totalsol;
                                        $TOTALENT += $totalent;
                                        $TOTALRECH += $totalrech;
                                        $TOTALCIERRE += $totalcierre;
                                        $TOTALDIF += $totaldif;
                                        $TOTALPRECIO += $totalprecio;
                                        $TOTALVALOR += $totalvalor;

                                        if ($TOTALDIF != 0 && $cant == 0 && $cant1 >= 1) {
                                            echo '<td align="center"><button id="btn_cliente" class="btn btn-primary" data-toggle="modal" data-target="#myModal' . $contador . '" ><span class="glyphicon glyphicon-print"></span></button></td>';
                                            echo "</tr>";
                                            echo '<div class="modal fade" id="myModal' . $contador . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										  <div class="modal-dialog">
										    <div class="modal-content">
										      <div class="modal-header">
										        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										        <h4 class="modal-title" id="myModalLabel">Importante</h4>
										      </div>
										      <div class="modal-body">
										        <p>El tamaño de impresión de las etiquetas debe ser de 140mm de alto por 90mm de ancho</p>
										      </div>
										      <div class="modal-footer">
										        <button type="button" class="btn btn-default" data-dismiss="modal"  onclick="print1(\'' . $finca . '\',\'' . $row['fecha'] . '\',\'false\',\'' . $row['fecha_tentativa'] . '\',\'' . $row['agencia'] . '\')">Continuar</button>
										      </div>
										    </div>
										  </div>
										</div>';
                                        } else {
                                            echo '<td align="center"><button id="btn_cliente" disabled="true" class="btn btn-primary" data-toggle="tooltip" data-placement="rigth" title = "No hay etiquetas para imprimir de este pedido o el DAE está caducado"><span class="glyphicon glyphicon-print"></span></button></td>';
                                            echo "</tr>";
                                        }
                                        //Resetear los subtotales
                                        $totalsol = 0;
                                        $totalent = 0;
                                        $totalrech = 0;
                                        $totalcierre = 0;
                                        $totaldif = 0;
                                        $totalprecio = 0;
                                        $totalvalor = 0;

                                        //aumentar el contador
                                        $contador++;
                                    }
                                    if ($contador != 1) {
                                        echo " <tr>
			<td align='right'></td>
                        <td align='right'></td>	
			<td align='center'><strong>Total General:</strong></td>
			<td align='center'><strong>" . $TOTALSOL . "</strong></td>
			<td align='center'><strong>" . $TOTALENT . "</strong></td>
			<td align='center'><strong>" . $TOTALRECH . "</strong></td>
			<td align='center'><strong>" . $TOTALCIERRE . "</strong></td>";
                                        if ($TOTALDIF == 0) {
                                            echo "<td align='center'><button type='button' class='btn btn-success btn-lg' data-toggle='tooltip' data-placement='left' title = 'No hay cajas pendientes'><strong>" . $TOTALDIF . "</strong></button></td>";
                                        } else {
                                            echo "<td align='center'><button type='button' class='btn btn-danger btn-lg' data-toggle='tooltip' data-placement='rigth' title = 'Hay cajas por entregar'><strong>" . $TOTALDIF . "</strong></button></td>";
                                        }
                                        echo "<td align='center'><strong>" . $TOTALPRECIO . "</strong></td>
			<td align='center'><strong>" . $TOTALVALOR . "</strong></td>";
                                        //limpio los totales generales
                                        $TOTALCIERRE = 0;
                                        $TOTALDIF = 0;
                                        $TOTALSOL = 0;
                                        $TOTALRECH = 0;
                                        $TOTALENT = 0;
                                    }
                                }
                            } else {

                                $fecha = $_POST['fecha'];
                                $_SESSION["fecha"] = $fecha;

                                $hoy = date('Y-m-d');
                                //selecciono todos los posibles destinos
                                $sqldestino = "SELECT * FROM tblpaises_destino";
                                $querydestino = mysqli_query($link, $sqldestino) or die("Error leyendo los paises");
                                $i = 1;  //para diferenciar las ventanas modales en el id de los items

                                $sql = "SELECT distinct fecha,fecha_tentativa,agencia,destino FROM tbletiquetasxfinca where fecha='" . $fecha . "' and finca='" . $finca . "' AND archivada = 'No' AND estado !='5' order by fecha";
                                $_SESSION["sqlcsv"] = $sql;

                                while ($rowdestino = mysqli_fetch_array($querydestino)) {

                                    //Selecciono cada item con solicitud activa
                                    $sql = "SELECT distinct nropedido,fecha,fecha_tentativa,agencia,destino FROM tbletiquetasxfinca where fecha='" . $fecha . "' and finca='" . $finca . "' AND archivada = 'No' AND estado !='5' AND destino='" . $rowdestino['codpais'] . "' order by fecha";
                                    $val = mysqli_query($link, $sql)or die("Error seleccionando los pedidos");


                                    //Recorro por cada nro pedido cada item
                                    $contador = 1; //para diferenciar las ventanas modales en el id de los totales por fecha
                                    $ponerdestino = false;
                                    while ($row = mysqli_fetch_array($val)) {
                                        if (!$ponerdestino)
                                            echo '<tr><td colspan="12" style="background:#428bca;">' . $rowdestino['pais_dest'] . '</td>';
                                        $ponerdestino = true;
                                        echo "<tr>";
                                        echo "<td align='left' colspan='2'><strong>Fecha de entrega: " . $row['fecha'] . "</strong></td>";
                                        echo "<td align='left' colspan='4'><strong>Fecha de vuelo: " . $row['fecha_tentativa'] . "</strong></td>";
                                        echo "<td align='left' colspan='5'><strong>Agencia de carga: <font color='red'>" . $row['agencia'] . "</font></strong></td>";
                                        echo "</tr>";
                                        echo '<tr>
                                                <td align="center"><strong>Item</strong></td>
                                                <td align="center"><strong>Imagen</strong></td>
                                                <td align="center"><strong>Prod. Desc.</strong></td>
                                                <td align="center"><strong>Solicitadas</strong></td>
                                                <td align="center"><strong>Enviadas</strong></td> 
                                                <td align="center"><strong>Rechazadas</strong></td>
                                                <td align="center"><strong>Cierre de Día</strong></td>
                                                <td align="center"><strong>Por enviar</strong></td>
                                                <td align="center"><strong>Valor unitario</strong></td>
                                                <td align="center"><strong>Valor a pagar</strong></td>
                                                <td align="center"><strong>Imprimir etiquetas</strong></td>
                                                </tr>	';

                                        //Selecciono cada nropedido los items
                                        $sentencia = "SELECT distinct item,precio FROM tbletiquetasxfinca where fecha='" . $row['fecha'] . "' AND fecha_tentativa='" . $row['fecha_tentativa'] . "' AND finca='" . $finca . "' AND archivada = 'No' AND estado !='5' AND destino='" . $rowdestino['codpais'] . "' AND agencia='" . $row['agencia'] . "' order by precio";
                                        $consulta = mysqli_query($link, $sentencia)or die("Error seleccionando los items con solicitudes");

                                        //Por cada item cuento cuantas solicitudes hay
                                        while ($fila = mysqli_fetch_array($consulta)) {

                                            //Se cuenta cuantas solicitudes y entregas hay por cada finca e item
                                            $sql1 = "SELECT SUM(solicitado) as solicitado,SUM(entregado) as entregado, item, fecha,precio FROM tbletiquetasxfinca where fecha='" . $row['fecha'] . "' AND finca='" . $finca . "' AND precio='" . $fila['precio'] . "' AND estado!='5' AND item = '" . $fila['item'] . "'AND archivada = 'No' AND destino='" . $rowdestino['codpais'] . "' AND agencia='" . $row['agencia'] . "'";
                                            $val1 = mysqli_query($link, $sql1) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                            $row1 = mysqli_fetch_array($val1);

                                            //$_SESSION["sql1"] = $sql1;
                                            //Se cuenta cuantas solicitudes rechazadas hay por cada finca e item
                                            $sql2 = "SELECT COUNT(*) as rechazado,nropedido FROM tbletiquetasxfinca where estado='2' AND fecha = '" . $row['fecha'] . "' AND finca='" . $finca . "' AND item = '" . $fila['item'] . "' AND archivada = 'No' AND destino='" . $rowdestino['codpais'] . "' AND agencia='" . $row['agencia'] . "'";
                                            $val2 = mysqli_query($link, $sql2) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                            $row2 = mysqli_fetch_array($val2);

                                            //Se cuenta cuantas solicitudes con cierre de dia por cada finca e item
                                            $sql3 = "SELECT COUNT(*) as cierre FROM tbletiquetasxfinca where estado= '3' AND fecha = '" . $row['fecha'] . "' AND finca='" . $finca . "' AND item = '" . $fila['item'] . "' AND archivada = 'No' AND destino='" . $rowdestino['codpais'] . "' AND agencia='" . $row['agencia'] . "'";
                                            $val3 = mysqli_query($link, $sql3) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                            $row3 = mysqli_fetch_array($val3);

                                            echo "<tr>";
                                            echo "<td align='center'><strong>" . $row1['item'] . "</strong></td>";

//                     //busco el id del item
                                            $sql33 = "SELECT tblproductos.item FROM tblproductos where id_item='" . $row1['item'] . "'";
                                            $val33 = mysqli_query($link, $sql33) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                            while ($row33 = mysqli_fetch_row($val33)) {
                                                echo "<td align='center'><strong><a href='../images/productos/" . $row33[0] . ".jpg' data-lightbox='" . $row33[0] . "'><img id='img' width='50px' heigth='50px' src='../images/productos/" . $row33[0] . ".jpg' data-toggle='tooltip' data-placement='left'/></a></strong></td>";
                                            }


                                            //Seleccionando l adescripcion del item
                                            $sql4 = "SELECT prod_descripcion FROM tblproductos where id_item='" . $row1['item'] . "'";
                                            $val4 = mysqli_query($link, $sql4) or die("Error sumando las cantidades de solicitudes y entregas de las fincas");
                                            $row4 = mysqli_fetch_array($val4);

                                            echo "<td><strong>" . $row4['prod_descripcion'] . "</strong></td>";
                                            echo "<td align='center'>" . $row1['solicitado'] . "</td>";
                                            $totalsol += $row1['solicitado'];
                                            echo "<td align='center'>" . $row1['entregado'] . "</td>";
                                            if ($row2['rechazado'] == 0) {
                                                echo "<td align='center'>" . $row2['rechazado'] . "</td>";
                                            } else {
                                                echo "<td align='center' bgcolor='#FF0000'><strong><a href= 'etiqrechazada.php?id=" . $row2['nropedido'] . "' title='Ver cajas rechazadas'>" . $row2['rechazado'] . "</a></td>";
                                            }

                                            //echo "<td align='center'>".$row2['rechazado']."</td>";
                                            echo "<td align='center'>" . $row3['cierre'] . "</td>";

                                            //se restan las solicitudes - entregado - rechazado	

                                            $totalrech += $row2['rechazado'];
                                            $totalent += $row1['entregado'];
                                            $dif = $row1['solicitado'] - $row1['entregado'] - $row2['rechazado'] - $row3['cierre'];
                                            $totalcierre += $row3['cierre'];
                                            $totaldif += $dif;
                                            $totalprecio += $row1['precio'];
                                            $totalvalor += $row1['precio'] * $row1['solicitado'];

                                            echo "<td align='center'>" . $dif . "</td>";
                                            echo "<td align='center'>" . $row1['precio'] . "</td>";
                                            echo "<td align='center'>" . $row1['precio'] * $row1['solicitado'] . "</td>";

                                            //poner boton de imprimir etiquetas por cada item
                                            if ($dif != 0 && $cant == 0 && $cant1 >= 1) {
                                                echo '<td align="center"><input type="image" style="cursor:pointer" name=" " id="btn_cliente" src="../images/print.png" heigth="30" value="" data-toggle="modal" data-target="#myModal-item' . $i . '" width="20"/></td>';
                                                echo "</tr>";
                                                echo '<div class="modal fade" id="myModal-item' . $i . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                         <div class="modal-dialog">
                                           <div class="modal-content">
                                             <div class="modal-header">
                                               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                               <h4 class="modal-title" id="myModalLabel">Importante</h4>
                                             </div>
                                             <div class="modal-body">
                                               <p>El tamaño de impresión de las etiquetas debe ser de 140mm de alto por 90mm de ancho</p>
                                             </div>
                                             <div class="modal-footer">
                                               <button type="button" class="btn btn-default" data-dismiss="modal"  onclick="print2(\'' . $finca . '\',\'' . $row['fecha'] . '\',\'' . $fila['item'] . '\',\'false\',\'' . $row['fecha_tentativa'] . '\',\'' . $row['agencia'] . '\')">Continuar</button>
                                             </div>
                                           </div>
                                         </div>
                                       </div>';
                                                $i++; //aumento el numero de id que tendra la proxima ventana modal
                                            } else {
                                                echo '<td align="center"><input disabled="true" type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../images/print.png" heigth="30" value="" width="20" data-toggle="tooltip" data-placement="rigth" title = "No hay etiquetas para imprimir de este pedido o el DAE está caducado"/></td>';
                                                echo "</tr>";
                                            }
                                        }//fin while
                                        echo "
                      <tr>
                      <td align='right'></td>	
                      <td align='right'></td>
                      <td align='center'><strong>Total por Fecha:</strong></td>
                      <td align='center'><strong>" . $totalsol . "</strong></td>
                      <td align='center'><strong>" . $totalent . "</strong></td>
                      <td align='center'><strong>" . $totalrech . "</strong></td>
                      <td align='center'><strong>" . $totalcierre . "</strong></td>";

                                        if ($totaldif == 0) {
                                            echo "<td align='center'><button type='button' class='btn btn-success' data-toggle='tooltip' data-placement='left' title = 'No hay cajas pendientes'><strong>" . $totaldif . "</strong></button></td>";
                                        } else {
                                            echo "<td align='center'><button type='button' class='btn btn-danger' data-toggle='tooltip' data-placement='rigth' title = 'Hay cajas por entregar'><strong>" . $totaldif . "</strong></button></td>";
                                        }

                                        echo "<td align='center'><strong>" . $totalprecio . "</strong></td>";
                                        echo "<td align='center'><strong>" . $totalvalor . "</strong></td>";

                                        //Contar los subtotales
                                        $TOTALSOL += $totalsol;
                                        $TOTALENT += $totalent;
                                        $TOTALRECH += $totalrech;
                                        $TOTALCIERRE += $totalcierre;
                                        $TOTALDIF += $totaldif;
                                        $TOTALPRECIO += $totalprecio;
                                        $TOTALVALOR += $totalvalor;

                                        if ($TOTALDIF != 0 && $cant == 0 && $cant1 >= 1) {
                                            echo '<td align="center"><button id="btn_cliente" class="btn btn-primary" data-toggle="modal" data-target="#myModal' . $contador . '" ><span class="glyphicon glyphicon-print"></span></button></td>';
                                            echo "</tr>";
                                            echo '<div class="modal fade" id="myModal' . $contador . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                         <div class="modal-dialog">
                                           <div class="modal-content">
                                             <div class="modal-header">
                                               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                               <h4 class="modal-title" id="myModalLabel">Importante</h4>
                                             </div>
                                             <div class="modal-body">
                                               <p>El tamaño de impresión de las etiquetas debe ser de 140mm de alto por 90mm de ancho</p>
                                             </div>
                                             <div class="modal-footer">
                                               <button type="button" class="btn btn-default" data-dismiss="modal"  onclick="print1(\'' . $finca . '\',\'' . $row['fecha'] . '\',\'false\',\'' . $row['fecha_tentativa'] . '\',\'' . $row['agencia'] . '\')">Continuar</button>
                                             </div>
                                           </div>
                                         </div>
                                       </div>';
                                        } else {
                                            echo '<td align="center"> <button id="btn_cliente" disabled="true" class="btn btn-primary" data-toggle="tooltip" data-placement="rigth" title = "No hay etiquetas para imprimir de este pedido o el DAE está caducado"><span class="glyphicon glyphicon-print"></span></button></td>';
                                            echo "</tr>";
                                        }
                                        //Resetear los subtotales
                                        $totalsol = 0;
                                        $totalent = 0;
                                        $totalrech = 0;
                                        $totalcierre = 0;
                                        $totaldif = 0;
                                        $totalprecio = 0;
                                        $totalvalor = 0;

                                        //aumentar el contador
                                        $contador++;
                                    }
                                    if ($contador != 1) {
                                        echo " <tr>
			<td align='right'></td>
                        <td align='right'></td>	
			<td align='center'><strong>Total General:</strong></td>
			<td align='center'><strong>" . $TOTALSOL . "</strong></td>
			<td align='center'><strong>" . $TOTALENT . "</strong></td>
			<td align='center'><strong>" . $TOTALRECH . "</strong></td>
			<td align='center'><strong>" . $TOTALCIERRE . "</strong></td>";
                                        if ($TOTALDIF == 0) {
                                            echo "<td align='center'><button type='button' class='btn btn-success btn-lg' data-toggle='tooltip' data-placement='left' title = 'No hay cajas pendientes'><strong>" . $TOTALDIF . "</strong></button></td>";
                                        } else {
                                            echo "<td align='center'><button type='button' class='btn btn-danger btn-lg' data-toggle='tooltip' data-placement='rigth' title = 'Hay cajas por entregar'><strong>" . $TOTALDIF . "</strong></button></td>";
                                        }
                                        echo "<td align='center'><strong>" . $TOTALPRECIO . "</strong></td>
			<td align='center'><strong>" . $TOTALVALOR . "</strong></td>";
                                        //limpio los totales generales
                                        $TOTALCIERRE = 0;
                                        $TOTALDIF = 0;
                                        $TOTALSOL = 0;
                                        $TOTALRECH = 0;
                                        $TOTALENT = 0;
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
                    <a href="#top" class="well well-sm"  onclick="$('html,body').animate({scrollTop: 0}, 'slow');
                            return false;">
                        <i class="glyphicon glyphicon-chevron-up"></i> Ir arriba
                    </a>
                </span><!-- /top-link-block --> 
            </div> <!-- /container -->

            <div class="modal fade" id="DaeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">

                            <h4 class="modal-title" id="exampleModalLabel">Información</h4>
                        </div>
                        <div class="modal-body">
                            <form action="dae.php" method="post">
                                <div class="form-group">
                                    <div id="mensaje"></div>
                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="salir" onclick="" class="btn btn-primary">Insertar DAE</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scripts -->
            <script type="text/javascript">
                function catcalc(cal) {
                    var date = cal.date;
                    var time = date.getTime()
                    // use the _other_ field
                    var field = document.getElementById("f_calcdate");
                    if (field == cal.params.inputField) {
                        field = document.getElementById("fecha");
                        time -= Date.WEEK; // substract one week
                    } else {
                        time += Date.WEEK; // add one week
                    }
                    var date2 = new Date(time);
                    field.value = date2.print("%Y-%m-%d");
                }
                Calendar.setup({
                    inputField: "fecha", // id of the input field
                    ifFormat: "%Y-%m-%d ", // format of the input field
                    showsTime: false,
                    timeFormat: "24",
                    onUpdate: catcalc
                });

            </script>
            <script type="text/javascript" language="javascript" src="../js/lightbox.js"></script>
    </body>
</html>

