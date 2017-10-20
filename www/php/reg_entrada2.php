<?php
session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");
require_once('barcode.inc.php');

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
        <title>Registro de Cajas</title>

        <link rel="icon" type="image/png" href="../images/favicon.ico" />
        <link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/octicons.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/zenburn.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/dataTables.jqueryui.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.0.0/css/responsive.jqueryui.min.css">

        <script language="javascript" src="../js/imprimir.js"></script>
        <script type="text/javascript" src="../js/script.js"></script>
        <script src="../bootstrap/js/jquery.js"></script>
        <script src="../bootstrap/js/bootstrap.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
        <script src="../bootstrap/js/bootstrap-submenu.js"></script>
        <script src="../bootstrap/js/bootstrap-modal.js"></script>
        <script src="../bootstrap/js/docs.js" defer></script>

        <script type="text/javascript" src="../js/ion.sound.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.10/js/dataTables.jqueryui.min.js"></script> 
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.0.0/js/dataTables.responsive.min.js"></script> 
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.0.0/js/responsive.jqueryui.min.js"></script>

        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
        <script src="../js/formato.js"></script>
        <style type="text/css">
            .my-error-class {
                color:red;
            }
            li a{
                cursor:pointer; /*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
            }
            .modal-header{
                background-color: #3B5998;
                color: white;
                border-radius : 5px 5px 0 0;
            }    

        </style>

        <script>
            jQuery.validator.addMethod("barcode", function (value, element) {
                return this.optional(element) || /[a-z0-9 -()+]+$/.test(value);
            }, "Enter a valid bar code.");

            jQuery.validator.addMethod("noSpace", function (value, element) {
                return value.indexOf(" ") < 0 && value != "";
            }, "No space please and don't leave it empty");
        </script>

        <style>
            .contenedor {
                margin:0 auto;
                width:85%;
                text-align:center;
            }
        </style>

        <script type="text/javascript">

            function cambiar(valor) {
                var v = valor;
                window.open("cambiarcontrasenna.php?codigo=" + v, "Cantidad", "width=400,height=300,top=150,left=400");
                return false;
            }


            function KeyAscii(e) {
                return (document.all) ? e.keyCode : e.which;
            }

            function TabKey(e, nextobject) {
                nextobject = document.getElementById(nextobject);
                if (nextobject) {
                    if (KeyAscii(e) == 13)
                        nextobject.focus();
                }
            }

            $(document).ready(function () {
                ion.sound({
                    sounds: [
                        {
                            name: "error"
                        }
                    ],
                    volume: 0.5,
                    path: "../sounds/",
                    preload: true
                });

                $('#codigo').keydown(function (e) {
                    if (e.keyCode == 13) {
                        //validadndo que el codigo tenga 10 caracteres
                        var element = $("#codigo").val().split('-');
                        if (element[2].length != 10)
                        {
                            $('#ventModal').find('#mensaje_error').html('<strong>El código del Item no tiene la cantidad correcta de digitos</strong>');
                            $('#ventModal').modal('show');
                            ion.sound.play("error");
                            return;
                        }
                        $.ajax({
                                            data:  "codigo=" + $('#codigo').val(),
                                            url:   'registrarCajas.php',
                                            type:  'post',
                            dataType: 'json',
                            success:  function (response) {
                                $('#codigo').val('');
                                if (response.error == 2) {
                                    $('#mensaje').html('<div class="alert alert-danger" role="alert"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span> Error: No ha insertado ningun codigo de barras.</div>');
                                    $('#mensaje').fadeIn('slow', function () {
                                        setTimeout(function () {
                                            $("#mensaje").fadeOut(1500);
                                        }, 3000);
                                    });
                                }
                                if (response.error == 3) {
                                    $('#mensaje').html('<div class="alert alert-danger" role="alert"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span> Error: Esa caja no ha sido solicitada, no se puede registrar su entrada.</div>');
                                    $('#mensaje').fadeIn('slow', function () {
                                        setTimeout(function () {
                                            $("#mensaje").fadeOut(1500);
                                        }, 3000);
                                    });

                                }
                                if (response.error == 4) {

                                    $('#mensaje').html('<div class="alert alert-danger" role="alert"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span> Error: No se pudo actualizar el estado del pedido.</div>');
                                    $('#mensaje').fadeIn('slow', function () {
                                        setTimeout(function () {
                                            $("#mensaje").fadeOut(1500);
                                        }, 3000);
                                    });
                                }
                                if (response.error == 5) {
                                    $('#mensaje').html('<div class="alert alert-success" role="alert"><span class=\"glyphicon \" aria-hidden=\"true\"></span> Caja registrada correctamente.</div>');

                                    var fila = oTable.row.add([
                                        response.codigo,
                                        response.item,
                                        response.finca,
                                        response.fecha,
                                        response.entrada,
                                        response.salida
                                    ]).draw(false);
                                    $('#mensaje').fadeIn('slow', function () {
                                        setTimeout(function () {
                                            $("#mensaje").fadeOut(1500);
                                        }, 3000);
                                    });
                                }
                                if (response.error == 6) {
                                    $('#ventModal').find('#mensaje_error').html('<strong>Ya esa caja fue registrada</strong>');
                                    $('#ventModal').modal('show');
                                    ion.sound.play("error");
                                }

                            }
                        });
                    }
                });

                var oTable = $('#tabla').DataTable({
                    "scrollX": true,
                    responsive: true,
                    "dom": '<"fg-toolbar ui-toolbar ui-widget-header ui-helper-clearfix ui-corner-tl ui-corner-tr"lifr>' +
                            't' +
                            '<"fg-toolbar ui-toolbar ui-widget-header ui-helper-clearfix ui-corner-bl ui-corner-br"ip>',
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ filas por pág.",
                        "zeroRecords": "No se encontraron elementos",
                        "info": "Mostrando página _PAGE_ de _PAGES_ /Total de Productos(_MAX_)",
                        "infoEmpty": "No se encontraron elementos",
                        "infoFiltered": "(filtrado de un total _MAX_)",
                        "sSearch": "Buscar:"
                    }
                });
                $(".dataTables_info").css({"color": "red", "font-weight": "bold"});//ponerle el color rojo y letra grande a la inormacion del total


            });

        </script>
        <script>
            function countChar(val) {
                var len = val.value.length;
                if (len > 30) {
//                    $('form').submit();

                    function cambiar(valor) {
                        var v = valor;
                        window.open("cambiarcontrasenna.php?codigo=" + v, "Cantidad", "width=400,height=300,top=150,left=400");
                        return false;
                    }


                    function KeyAscii(e) {
                        return (document.all) ? e.keyCode : e.which;
                    }

                    function TabKey(e, nextobject) {
                        nextobject = document.getElementById(nextobject);
                        if (nextobject) {
                            if (KeyAscii(e) == 13)
                                nextobject.focus();
                        }
                    }
                    //validadndo que el codigo tenga 10 caracteres
                    var element = $("#codigo").val().split('-');
                    if (element[2].length != 10)
                    {
                        $('#ventModal').find('#mensaje_error').html('<strong>El código del Item no tiene la cantidad correcta de digitos</strong>');
                        $('#ventModal').modal('show');
                        ion.sound.play("error");
                        return;
                    }
                    $.ajax({
                                        data:  "codigo=" + $('#codigo').val(),
                                        url:   'registrarCajas.php',
                                        type:  'post',
                        dataType: 'json',
                        success:  function (response) {
                            $('#codigo').val('');
                            if (response.error == 2) {
                                $('#mensaje').html('<div class="alert alert-danger" role="alert"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span> Error: No ha insertado ningun codigo de barras.</div>');
                                $('#mensaje').fadeIn('slow', function () {
                                    setTimeout(function () {
                                        $("#mensaje").fadeOut(1500);
                                    }, 3000);
                                });
                            }
                            if (response.error == 3) {
                                $('#mensaje').html('<div class="alert alert-danger" role="alert"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span> Error: Esa caja no ha sido solicitada, no se puede registrar su entrada.</div>');
                                $('#mensaje').fadeIn('slow', function () {
                                    setTimeout(function () {
                                        $("#mensaje").fadeOut(1500);
                                    }, 3000);
                                });

                            }
                            if (response.error == 4) {

                                $('#mensaje').html('<div class="alert alert-danger" role="alert"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span> Error: No se pudo actualizar el estado del pedido.</div>');
                                $('#mensaje').fadeIn('slow', function () {
                                    setTimeout(function () {
                                        $("#mensaje").fadeOut(1500);
                                    }, 3000);
                                });
                            }
                            if (response.error == 5) {
                                $('#mensaje').html('<div class="alert alert-success" role="alert"><span class=\"glyphicon \" aria-hidden=\"true\"></span> Caja registrada correctamente.</div>');

                                var fila = oTable.row.add([
                                    response.codigo,
                                    response.item,
                                    response.finca,
                                    response.fecha,
                                    response.entrada,
                                    response.salida
                                ]).draw(false);
                                $('#mensaje').fadeIn('slow', function () {
                                    setTimeout(function () {
                                        $("#mensaje").fadeOut(1500);
                                    }, 3000);
                                });
                            }
                            if (response.error == 6) {
                                $('#ventModal').find('#mensaje_error').html('<strong>Ya esa caja fue registrada</strong>');
                                $('#ventModal').modal('show');
                                ion.sound.play("error");
                            }

                        }
                    });

                }
            }
            ;
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
                                    <li class="active">
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

                <div class="panel-body">
                    <div id="mensaje" style="display: none;"></div>

                    <div class="table-responsive">
                        <table width="100%" border="0" align="center" class="table table-striped" >
                            <tr>
                                <td colspan="8" align="center">
                                    <h3><strong>REGISTRAR ENTRADA </strong></h3>
                                </td>
                            </tr>
                            <tr>
                                <td align="right"><strong>Código de Barras:</strong></td>
                                <td>
                                    <div class="col-md-8">
                                        <input type="text" id="codigo" class="form-control" name="codigo" autofocus onkeyup="countChar(this)"/>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>


                    <div class="table-responsive">
                        <table width="50%" border="0" align="center" class="table table-striped" >

                            <form method="post" id="frm1" name="frm1">             
                                <tr>
                                    <td colspan="6" align="center">
                                        <h3><strong>Entradas del día: </strong><label style="color:#FF0000"><?php echo '<time datetime="' . date('c') . '">' . date('d - m - Y') . '</time>'; ?></label></h3>
                                    </td>
                                    <td align="right">
                                        <input type="image" style="cursor:pointer" id="imprimir"  name="imprimir"class= "imprimir" src="../images/excel.png" heigth="40" value="" data-toggle="tooltip" data-placement="left" title = "Exportar Reporte Excel" width="30" formaction = "crearReportExcel.php"/>
                                    </td>
                                </tr>
                            </form>
                        </table>
                    </div>

                    <table id="tabla" border="0" class="nowrap stripe row-border" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="all">Código de Caja</th>
                                <th class="all">Producto</th>
                                <th class="all">Finca</th>
                                <th class="all">Fecha Entrada</th>
                                <th class="all">Entrada</th>
                                <th class="all">Salida</th>
                            </tr>
                        </thead>
                        <tbody> 
                            <?php
                            $hoy = date('Y-m-d');
                            //$sql1 = "SELECT * FROM tblcoldroom where fecha='".$hoy."' and entrada='Si' and salida='No'";
                            $sql1 = "SELECT * FROM tblcoldroom INNER JOIN tbletiquetasxfinca ON tblcoldroom.codigo = tbletiquetasxfinca.codigo where tblcoldroom.fecha='" . $hoy . "' AND entrada= 'Si' AND salida ='No' AND tbletiquetasxfinca.archivada='No' AND tbletiquetasxfinca.estado='1'";
                            $res = mysqli_query($sql1);
                            while ($row = mysqli_fetch_array($res)) {
                                echo '<tr>';
                                echo '<td>' . $row['codigo'] . '</td>';
                                echo '<td>' . $row['item'] . '</td>';
                                echo '<td>' . $row['finca'] . '</td>';
                                echo '<td>' . $row['fecha'] . '</td>';
                                echo '<td>' . $row['entrada'] . '</td>';
                                echo '<td>' . $row['salida'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div> 

                <?php
                //Preparando los datos para el reporte
                $hoy = date('Y-m-d');
                $_SESSION["titulo"] = "Listado de Entradas " . $hoy;
                $_SESSION["columnas"] = array("Código de Caja", "Item", "Finca", "Fecha", "Entrada", "Salida");
                $_SESSION["consulta"] = "SELECT codigo,item,finca,fecha,entrada,salida FROM tblcoldroom where fecha like '" . $hoy . "%' AND entrada ='Si' AND salida = 'No'";
                $_SESSION["nombre_fichero"] = "Listado de Entradas " . $hoy . ".xlsx";
                ?>


                <!-- /panel body --> 
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

            <div class="modal fade" id="ventModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="exampleModalLabel">Error</h4>
                        </div>
                        <div class="modal-body">
                            <form id="form1" name="form1" method="post" action="">
                                <table width="372" border="0" align="center" class="alert">
                                    <tr>
                                        <td height="30" colspan="2" align="center"><p><strong><span id="result_box" lang="en" xml:lang="en"> 
                                                        <img src="../images/error.jpg" width="74" height="69" alt="d" />
                                                    </span></strong></p>
                                            <p id="mensaje_error"></p></td>
                                    </tr>

                                </table>
                            </form> 
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="aceptar" class="btn btn-default" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div> 
    </body>
</html>