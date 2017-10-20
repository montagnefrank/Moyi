<?php
///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL); 
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");
require_once('barcode.inc.php');

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
        <title>Traquear Caja</title>

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
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
        <script src="../bootstrap/js/bootstrap.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
        <script src="../bootstrap/js/bootstrap-submenu.js"></script>
        <script src="../bootstrap/js/docs.js" defer></script>
        <script src="../js/formato.js"></script>

        <script type="text/javascript" src="../js/ion.sound.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.10/js/dataTables.jqueryui.min.js"></script> 
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.0.0/js/dataTables.responsive.min.js"></script> 
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.0.0/js/responsive.jqueryui.min.js"></script>

        <style>
            .contenedor {
                margin:0 auto;
                width:85%;
                text-align:center;
            }
        </style>

        <style type="text/css">
            .my-error-class {
                color:red;
            }
            /*.my-valid-class {
                color:green;
            }*/
            li a{
                cursor:pointer;/*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
            }

            .modal-header{
                background-color: #3B5998;
                color: white;
                border-radius : 5px 5px 0 0;
            } 


        </style>

        <script>

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
                $('#ventModal').on('hide.bs.modal', function () {
                    //limpio los campos
                    $('#codigo').val('');
                    $('#tracking').val('');
                });

                $('#codigo').keydown(function (e) {
                    if (e.keyCode === 13) {
                        var codigobarra = $("#codigo").val();
                        $.post('hash_translate.php', 'val=' + codigobarra, function (response) {
                            var string = response;
                            //si estas eleccionado el checkbox significa que estoy traquenado ordenes consolidadas
                            if ($('#consolidado').prop('checked')) {
                                $cons = "ON";
                            } else
                                $cons = "OFF";

                            $.ajax({
                                                data:  "codigo=" + string + "&tracking=" + $('#tracking').val() + "&guia_master=" + $('#master').val() + "&palet=" + $('#palet').val() + "&consolidado=" + $cons,
                                                url:   'registrarTracking.php',
                                                type:  'post',
                                dataType: 'json',
                                success:  function (response) {
                                    $('#codigo').val('');
                                    if (response.error == 1) {
                                        $('#ventModal').find('#mensajee').html(response.mensaje);
                                        $('#ventModal').modal('show');
                                        ion.sound.play("error");

                                    }

                                    if (response.error == 2) {
                                        $('#mensaje').html('<div class="alert alert-success" role="alert"><span class=\"glyphicon \" aria-hidden=\"true\"></span>' + response.mensaje + '</div>');

                                        var fila = oTable.row.add([
                                            "",
                                            response.tracking,
                                            response.finca,
                                            response.item,
                                            response.codigo,
                                            response.guia_master,
                                            response.palet
                                        ]).draw(false);
                                        $('#mensaje').fadeIn('slow', function () {
                                            setTimeout(function () {
                                                $("#mensaje").fadeOut(1500);
                                            }, 3000);
                                        });
                                        $('#codigo').val('');
                                        $('#tracking').val('');

                                        //si tengo seleccionado el consolidado pongo el focus en el codigo
                                        if ($('#consolidado').prop('checked')) {
                                            $('#codigo').focus();
                                        } else
                                        {
                                            $('#tracking').focus();
                                        }
                                    }
                                }
                            });
                        });
                    }
                });

                var oTable = $('#tabla').DataTable({
                    "columnDefs": [{
                            "searchable": false,
                            "orderable": false,
                            "targets": 0
                        }],
                    "order": [[1, 'asc']],
                    "scrollX": true,
                    responsive: true,
                    "dom": '<"fg-toolbar ui-toolbar ui-widget-header ui-helper-clearfix ui-corner-tl ui-corner-tr"lifr>' +
                            't' +
                            '<"fg-toolbar ui-toolbar ui-widget-header ui-helper-clearfix ui-corner-bl ui-corner-br"ip>',
                    "language": {
                        "lengthMenu": "Mostrando _MENU_ filas por pág.",
                        "zeroRecords": "No se encontraron elementos",
                        "info": "Total de Cajas Traquedas(_MAX_)",
                        "infoEmpty": "No se encontraron elementos",
                        "infoFiltered": "(_TOTAL_ elementos filtados)",
                        "sSearch": "Buscar:"
                    }

                });
                $(".dataTables_info").css({"color": "red", "font-weight": "bold"});//ponerle el color rojo y letra grande a la inormacion del total

                oTable.on('order.dt search.dt', function () {
                    oTable.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();

                //filtro que busca solamente por la columna de palets mostrando solo el palet que esta siendo utilizado.
                $.fn.dataTable.ext.search.push(
                        function (settings, data, dataIndex) {
                            var min = parseInt($('#palet').val());
                            var age = parseFloat(data[6]) || 0; // use data for the age column

                            if (min == age)
                            {
                                return true;
                            }
                            return false;
                        }
                );


                $("#ir").on('click', function ()
                {
                    //verificamos en la db si existe esa guia master
                    //si existe le decimos al usuario que existe y si quiere utilizar un palet existente o uno nuevo
                    if ($('#master').val() != "" && $('#master').attr('readonly') != "readonly")
                    {
                        //busco en db si ya se esta utilizando ese palet
                        $.ajax({
                                            data:  "accion=buscarguia&master=" + $('#master').val(),
                                            url:   'registrarTracking.php',
                                            type:  'post',
                            dataType: 'json',
                            success:  function (response) {

                                if (response.existe_guia == "no") {
                                    $("#tracking").removeAttr("readonly");
                                    $("#codigo").removeAttr("readonly");
                                    $('#consolidado').removeAttr("disabled");
                                    $("#consolidado").removeAttr('checked');
                                    $("#master").attr("readonly", "readonly");
                                    $("#palet").val("1");
                                    oTable.column(5).search($("#master").val()).draw();
                                    oTable.draw();
      //                            $('#tabla').DataTable().column(6).search(
      //                                $("#palet").val()
      //                            ).draw();

                                    $('#cambiar_palet').attr('disabled', 'disabled');
                                    $('#palet_existente').attr('disabled', 'disabled');
                                    $("#tracking").focus();

                                } else if (response.existe_guia == "si")
                                {
                                    //lanzo la ventana donde indico al usuario si queire usar esa guia con un palet existente o no
                                    $("#tracking").attr("readonly", "readonly");
                                    $("#codigo").attr("readonly", "readonly");
                                    $('#consolidado').attr("disabled", "disabled");
                                    ///////////////////////
                                    //al pulsar si sobre el boton de aceptar guia master y palet
                                    $('#ventModal2').find('#btn_nuevopalet').on('click', function () {

                                        $('#ventModal2').hide();
                                        $("#tracking").removeAttr("readonly");
                                        $("#codigo").removeAttr("readonly");
                                        $("#consolidado").removeAttr("disabled");
                                        $("#consolidado").removeAttr('checked');
                                        $("#master").attr("readonly", "readonly");
                                        $("#palet").val(parseInt(response.palet) + 1);
                                        $('#cambiar_palet').removeAttr('disabled');
                                        $('#palet_existente').removeAttr('disabled');
                                        oTable.column(5).search($("#master").val()).draw();
                                        oTable.draw();
                                        $("#tracking").focus();
                                    });

                                    $('#ventModal2').find('#btn_paletexistente').on('click', function () {
                                        //busco todos los palets de esa guia master
                                        $.ajax({
                                                            data:  "accion=buscarpalet&master=" + $('#master').val(),
                                                            url:   'registrarTracking.php',
                                                            type:  'post',
                                            dataType: 'json',
                                            success:  function (response) {
                                                $('#palet_existe').html("");
                                                $('#palet_existe').append('<option value=""></option>');
                                                for (var i = 0; i < response.length; i++)
                                                {
                                                    $('#palet_existe').append('<option value="' + response[i] + '">' + response[i] + '</option>');
                                                }
                                                $('#p1').css('display', 'none');
                                                $('#p2').css('display', 'block');
                                                $('#ventModal2').hide();

                                                $('#palet_existe').on('change', function () {
                                                    if ($(this).val() != "") {
                                                        $('#p2').css('display', 'none');
                                                        $('#p1').css('display', 'block');
                                                        $('#palet').val($(this).val());
                                                        $("#tracking").removeAttr("readonly");
                                                        $("#codigo").removeAttr("readonly");
                                                        $("#consolidado").removeAttr("disabled");
                                                        $("#consolidado").removeAttr('checked');
                                                        $("#master").attr("readonly", "readonly");
                                                        oTable.column(5).search($("#master").val()).draw();
                                                        oTable.draw();
                                                        $('#cambiar_palet').removeAttr('disabled');
                                                        $('#palet_existente').removeAttr('disabled');
                                                        $("#tracking").focus();
                                                    }

                                                });



                                            }});
                                    });

                                    $('#ventModal2').modal('show');
                                    ion.sound.play("error");
                                }


                            }
                        });
                    }

                });

                //boton nueva guia
                $('#nueva_guia').on('click', function () {

                    $("#tracking").attr("readonly", "readonly");
                    $("#codigo").attr("readonly", "readonly");
                    $("#consolidado").attr("disabled", "disabled");
                    $("#consolidado").removeAttr('checked');
                    $("#master").removeAttr("readonly");
                    $("#master").val("");
                    $("#p1").css('display', 'block');
                    $("#p2").css('display', 'none');
                    $("#palet").val("");
                    $('#cambiar_palet').attr('disabled', 'disabled');
                    $('#palet_existente').attr('disabled', 'disabled');
                    oTable.search("").draw();
                    $("#master").focus();

                });

                //boton agregar palet EN EL MENU DE LA VENTANA
                $('#cambiar_palet').on('click', function () {
                    $.ajax({
                                        data:  "accion=buscarguia&master=" + $('#master').val(),
                                        url:   'registrarTracking.php',
                                        type:  'post',
                        dataType: 'json',
                        success:  function (response) {
                            if (response.existe_guia == "si")
                            {
                                $("#tracking").removeAttr("readonly");
                                $("#codigo").removeAttr("readonly");
                                $("#consolidado").removeAttr("disabled");
                                $("#consolidado").removeAttr('checked');
                                $("#master").attr("readonly", "readonly");
                                $("#palet").val(parseInt(response.palet) + 1);
                                oTable.column(5).search($("#master").val()).draw();
                                oTable.draw();
                                $("#tracking").focus();
                            }
                        }
                    });

                });

                //boton palet existente
                $('#palet_existente').on('click', function () {
                    //busco todos los palets de esa guia master
                    $.ajax({
                                        data:  "accion=buscarpalet&master=" + $('#master').val(),
                                        url:   'registrarTracking.php',
                                        type:  'post',
                        dataType: 'json',
                        success:  function (response) {
                            $('#palet_existe').html("");
                            $('#palet_existe').append('<option value=""></option>');
                            for (var i = 0; i < response.length; i++)
                            {
                                $('#palet_existe').append('<option value="' + response[i] + '">' + response[i] + '</option>');
                            }
                            $('#p1').css('display', 'none');
                            $('#p2').css('display', 'block');
                            $('#ventModal2').hide();

                            $('#palet_existe').on('change', function () {
                                if ($(this).val() != "") {
                                    $('#p2').css('display', 'none');
                                    $('#p1').css('display', 'block');
                                    $('#palet').val($(this).val());
                                    $("#tracking").removeAttr("readonly");
                                    $("#codigo").removeAttr("readonly");
                                    $("#consolidado").removeAttr("disabled");
                                    $("#consolidado").removeAttr('checked');
                                    $("#master").attr("readonly", "readonly");
                                    oTable.column(5).search($("#master").val()).draw();
                                    oTable.draw();
                                    $('#cambiar_palet').removeAttr('disabled');
                                    $('#palet_existente').removeAttr('disabled');
                                    $("#tracking").focus();
                                }

                            });



                        }});
                });

                //si selecciono consolidado 
                $('#consolidado').on('click', function () {
                    if ($(this).prop('checked')) {
                        //escondo el input del tracking
                        $('#tracking').attr('readonly', 'readonly');
                        $('#tracking').removeAttr('autofocus');
                        $('#codigo').attr('autofocus', 'autofocus');
                        $('#codigo').focus();
                    } else
                    {
                        //escondo el input del tracking
                        $('#tracking').attr('autofocus', 'autofocus');
                        $('#tracking').removeAttr('readonly');
                        $('#codigo').removeAttr('autofocus');
                        $('#tracking').focus();
                    }
                });
            });
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


            function KeyAscii(e) {
                return (document.all) ? e.keyCode : e.which;
            }

            function TabKey(e, nextobject) {
                nextobject = document.getElementById(nextobject);
                if (nextobject) {
                    if (KeyAscii(e) == 13){
                        var tracking = $("#tracking").val();
                        if (tracking.length == 34){
                            $("#tracking").val(tracking.slice(-12));
                        }
                        nextobject.focus();
                    }
                }
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
                                        $query = mysqli_query($sql, $conection);
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
                    <div id="mensaje"></div>
                    <form class="form-inline" style="margin-bottom: 10px;">

                        <div class="form-group"> 
                            <div class="input-group">
                                <label for="master">Guia Máster:</label>
                                <input type="text" id="master" class="form-control" name="master" style="width:auto"/>
                                <span class="input-group-btn" style="width: 0px;">
                                    <button class="btn btn-primary" type="button" id="ir">Ir</button>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div id="p1">
                                <label for="palet">Palet:</label>
                                <input type="text" id="palet" readonly="readonly" class="form-control" name="palet" style="width:100px"/>
                            </div>
                            <div id="p2" style="display: none;">
                                <label for="palet_existe">Palet:</label>
                                <select class="form-control" id="palet_existe" style="width: 110px;"></select>
                            </div>

                        </div>
                        <div class="form-group">
                            <input type="button" id="nueva_guia" class="btn btn-primary" value="Nueva Guia" name="nueva_guia"/>
                            <input type="button" id="cambiar_palet" class="btn btn-primary" value="Agregar Palet" name="cambiar_palet" disabled="disabled"/>
                            <input type="button" id="palet_existente" class="btn btn-primary" value="Utilizar Palet Existente" name="palet_existente" disabled="disabled"/>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table width="100%" border="0" align="center" class="table table-striped" >
                            <tr>
                                <td colspan="8" align="center">
                                    <h3 style="margin-top: 5px;margin-bottom: 5px;"><strong>TRAQUEAR CAJA</strong></h3>
                                </td>
                            </tr>
                            <tr>
                                <td align="right"><strong>Tracking:</strong></td>
                                <td>
                                    <div class="col-md-10">
                                        <input type="text" id="tracking" class="form-control" name="tracking" autofocus onKeyPress="TabKey(event, 'codigo')" readonly="readonly"/>
                                    </div>
                                </td>
                                <td align="right"><strong>Código de Barras:</strong></td>
                                <td>
                                    <div class="col-md-10">
                                        <input type="text" id="codigo" class="form-control" name="codigo" readonly="readonly"/>
                                    </div>
                                </td>
                                <td>
                                    <label>
                                        <input type="checkbox" name="consolidado" id="consolidado" disabled="disabled"> Consolidado
                                    </label>
                                </td>
                            </tr>
                        </table>
                    </div>      


                    <div class="table-responsive">
                        <table width="50%" border="0" align="center" class="table table-striped" >
                            <tr>
                                <td align="right">
                                    <input type="image" style="cursor:pointer" id="imprimir"  name="imprimir"class= "imprimir" src="../images/excel.png" heigth="40" value="" data-toggle="tooltip" data-placement="left" title = "Exportar Reporte Excel" width="30" formaction = "crearReportExcel.php"/>
                                </td>
                            </tr>
                        </table>
                    </div>     

                    <table id="tabla" border="0" class="nowrap stripe row-border" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="all">No.</th>
                                <th class="all">Tracking</th>
                                <th class="all">Finca</th>
                                <th class="all">Producto</th>
                                <th class="all">Código de Caja</th>
                                <th class="all">Guia Master</th>
                                <th class="all">Palet</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $hoy = date('Y-m-d');
                            $sql = "SELECT * FROM tblcoldroom where salida = 'Si' AND guia_madre='0' AND guia_hija='0' order by guia_master,palet ASC";
                            $val = mysqli_query($link, $sql);

                            while ($row = mysqli_fetch_array($val)) {
                                echo '<tr>';
                                echo '<td></td>';
                                echo '<td>' . $row['tracking_asig'] . '</td>';
                                echo '<td>' . $row['finca'] . '</td>';
                                echo '<td>' . $row['item'] . '</td>';
                                echo '<td>' . $row['codigo'] . '</td>';
                                echo '<td>' . $row['guia_master'] . '</td>';
                                echo '<td>' . $row['palet'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>


<?php
$hoy = date('Y-m-d');
//Preparando los datos para el reporte
$_SESSION["titulo"] = "Listado de Cajas Traqueadas " . $hoy;
$_SESSION["columnas"] = array("Tracking", "Farm", "Item", "Código de Caja");
$_SESSION["consulta"] = "SELECT tbldetalle_orden.tracking, finca, item, tblcoldroom.codigo  FROM tblcoldroom INNER JOIN tbldetalle_orden ON tbldetalle_orden.codigo =tblcoldroom.codigo where tblcoldroom.fecha_tracking ='" . $hoy . "' AND salida = 'Si'";
$_SESSION["nombre_fichero"] = "Listado de Cajas Traqueadas " . $hoy . ".xlsx";
?>


                </div> <!-- /panel body --> 
                <div class="panel-heading">
                    <div class="contenedor">
                        <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2015 version 0.2 Beta</strong>
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
<?php
echo '<img src="../images/error.jpg" width="74" height="69" alt="d" />';
?>
                                                    </span></strong></p>
                                            <p><div id="mensajee"></div></p></td>
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
            <div class="modal fade" id="ventModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
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
<?php
echo '<img src="../images/error.jpg" width="74" height="69" alt="d" />';
?>
                                                    </span></strong></p>
                                            <p><strong>Esa Guia master ya fue utilizada.</strong></p>
                                            </br>
                                            <p><strong>Desea utilizar un nuevo palet o uno ya existente?</strong></p>

                                        </td>
                                    </tr>

                                </table>
                            </form> 
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn_nuevopalet" class="btn btn-default" data-dismiss="modal">Nuevo Palet</button>
                            <button type="button" id="btn_paletexistente" class="btn btn-primary" data-dismiss="modal">Utilizar palet existente</button>
                        </div>
                    </div>
                </div>
            </div>
    </body>
</html>

