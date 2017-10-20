<?php
///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////CONEXION A DB
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
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Asignar Trackings</title>

        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" media="all"  />
        <link href="../bootstrap/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all"  />
        <link href="../bootstrap/css/prettify-1.0.css" rel="stylesheet" type="text/css" media="all"  />
        <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/dataTables.jqueryui.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.0.0/css/responsive.jqueryui.min.css">
        <link href="../css/skin-bootstrap/ui.fancytree.css" rel="stylesheet" type="text/css">
        <!--para crear el arbol-->
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css">
        <link href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" type="text/css" rel="stylesheet">
        <link href="../css/skin-bootstrap/ui.fancytree.css" rel="stylesheet" type="text/css">

        <script src="//code.jquery.com/jquery-1.12.1.min.js" type="text/javascript"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>

        <script src="../js/script.js"></script>
        <script src="../bootstrap/js/moment.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

        <script src="//cdn.jsdelivr.net/jquery.ui-contextmenu/1/jquery.ui-contextmenu.min.js"></script>

        <script src="../js/fancytree/jquery.fancytree.js" type="text/javascript"></script>
        <script src="../js/fancytree/jquery.fancytree.gridnav.js" type="text/javascript"></script>
        <script src="../js/fancytree/jquery.fancytree.table.js" type="text/javascript"></script>
        <script src="../js/fancytree/jquery.fancytree.childcounter.js" type="text/javascript"></script>
        <script src="../js/fancytree/jquery.fancytree.filter.js" type="text/javascript"></script>

        <script src="../bootstrap/js/bootstrap.js"></script>
        <script src="../bootstrap/js/bootstrap-datetimepicker.min.js"></script>
        <script src="../bootstrap/js/bootstrap-submenu.js"></script>
        <script src="../bootstrap/js/bootstrap-modal.js"></script>
        <script src="../bootstrap/js/docs.js" defer></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.10/js/dataTables.jqueryui.min.js"></script> 
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.0.0/js/dataTables.responsive.min.js"></script> 
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.0.0/js/responsive.jqueryui.min.js"></script> 
        <script src="../js/jquery.ajaxQueue.js" type="text/javascript"></script>

        <style type="text/css">
            /*Este estilo configura el combobox de autocompletado para que se muestre por encima del modal*/
            ul.ui-autocomplete {
                z-index: 1100;
                width: 200px;
            }
            .ui-autocomplete {
                max-height: 300px;
                overflow-y: auto;
                /* prevent horizontal scrollbar */
                overflow-x: hidden;
            } 
            li a{
                cursor:pointer;/*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
            }
            .my-error-class {
                color:red;
                font-style: italic;
                font-size: 12px;
            }

            .contenedor {
                margin:0 auto;
                width:85%;
                text-align:center;
            }
            .status_msg{
                position: absolute;
                /*nos posicionamos en el centro del navegador*/
                top:50%;
                left:50%;
                /*determinamos una anchura*/
                width:auto;
                /*indicamos que el margen izquierdo, es la mitad de la anchura*/
                margin-left:-200px;
                /*determinamos una altura*/
                /*indicamos que el margen superior, es la mitad de la altura*/
                margin-top:-150px;
                border:1px solid #808080;
                padding:5px;
                border: 0;
            }
            .status_msg img{
                margin: auto 5px 0px 0px;
            }

            .status_msg p{
                vertical-align: top;	
            }
            /*//para el menu del clic derecho del arbol*/
            .ui-menu {
                width: 180px;
                font-size: 83%;
            }
            /*para la cabecera de la ventana modal*/
            .modal-header{
                background-color : #3B5998;
                color: white;
                border-radius: 5px 5px 0 0;
            }
            .closeout
            {
                background-color: #5bc0de;
            }
            .cierredia
            {
                background-color: #069;
            }
        </style>

        <script type="text/javascript">
            $(document).ready(function () {

                var po = '<?php echo $_GET['accion'] ?>';
                if (po == 'buscarPO')
                {
                    $('div#po').css('display', 'block');
                }

                $("#form1 .dropdown-menu a").click(function (event) {
                    $("#form1 .panel").css('display', 'none');
                    var link = event.target.id;

                    $('#form1 div#' + link).css('display', 'block');
                });

                var parametros;
                var oTable = "";
                $('#enviar,#enviar1,#enviar2').click(function (event) {

                    parametros = new Array();

                    if ($("#pais").val() == "")
                    {
                        $(".my-error-class").css('display', 'inline');
                        event.preventDefault();
                        return false;
                    } else
                    {
                        $("#my-error-class").css('display', 'none');

                    }

                    if (event.target.id == "enviar")
                    {
                        parametros = {
                                            "fecha_inicio": $('#txtinicio').val(),
                            "fecha_fin": $('#txtfin').val(),
                            "pais": $('#pais').val(),
                            "estado": $('#estado').val(),
                            "cliente": $('#cliente').val(),
                            "item": $('#item').val(),
                            "tipo": "forden"
                        };
                    } else if (event.target.id == "enviar1")
                    {
                        parametros = {
                                            "fecha_inicio": $('#txtinicio1').val(),
                            "fecha_fin": $('#txtfin1').val(),
                            "pais": $('#pais').val(),
                            "estado": $('#estado').val(),
                            "cliente": $('#cliente').val(),
                            "item": $('#item').val(),
                            "tipo": "fvuelo"
                        };
                    } else if (event.target.id == "enviar2")
                    {
                        parametros = {
                                            "fecha_inicio": $('#txtinicio2').val(),
                            "fecha_fin": $('#txtfin2').val(),
                            "pais": $('#pais').val(),
                            "estado": $('#estado').val(),
                            "cliente": $('#cliente').val(),
                            "item": $('#item').val(),
                            "tipo": "fentrega"
                        };

                    }

                    status_msg({container: $("body"), msg: " Cargando Ordenes..."});

                    $.ajax({
                                  data:  parametros,
                                  url:   'repor_excel_tracking.php',
                                  type:  'GET',
                        dataType: 'json',
                        success:  function (response) {

                            if (oTable !== "")
                            {
                                oTable.clear().draw();
                                oTable.destroy();
                            }
                            for (i = 0; i < response[0].length; i++)
                            {
                                $('#tabla tbody').append("<tr id=" + response[0][i]['id_orden_detalle'] + ">" +
                                        "<td><input type='checkbox' value='" + response[0][i]['id_orden_detalle'] + "'></td>" +
                                        "<td>" + response[0][i]['Ponumber'] + "</td>" +
                                        "<td>" + response[0][i]['Custnumber'] + "</td>" +
                                        "<td>" + response[0][i]['cpitem'] + "</td>" +
                                        "<td>" + response[0][i]['cppais_envio'] + "</td>" +
                                        "<td>" + response[0][i]['cpestado_shipto'] + "</td>" +
                                        "<td>" + response[0][i]['cpcuidad_shipto'] + "</td>" +
                                        "<td>" + response[0][i]['delivery_traking'] + "</td>" +
                                        "<td>" + response[0][i]['ShipDT_traking'] + "</td>" +
                                        "<td>" + response[0][i]['order_date'] + "</td>" +
                                        "</tr>");
                            }
                            //genero el datatable
                            cargar();
                            //muestro el div donde estan la tabla y el arbol
                            $('#tblorden').css('display', 'block');
                            //muestro la tabla y escondo el arbol y pongo colores a los botones correspondientes
                            $('#divtabla').css('display', 'block');
                            $('#arbol').css('display', 'none');
                            $('#asignar').removeClass("btn-info");
                            $('#asignar').addClass("btn-danger");
                            $('#gestionar').removeClass("btn-danger");
                            $('#gestionar').addClass("btn-info");
                        },
                        error: function (response) {
                            //alert("error");
                                  },
                        complete: function ()
                        {
                            $("#status").remove();
                        }
                              });

                });

                var id_orden_detalle = new Array();//este array tendra los id de las ordenes a asiganr tracking
                function cargar()
                {
                    oTable = $('#tabla').DataTable({

                        "dom": '<"fg-toolbar ui-toolbar ui-widget-header ui-helper-clearfix ui-corner-tl ui-corner-tr"lifr>' +
                                't' +
                                '<"fg-toolbar ui-toolbar ui-widget-header ui-helper-clearfix ui-corner-bl ui-corner-br"ip>',
                        "language": {
                            "lengthMenu": "Mostrando _MENU_ filas por pág.",
                            "zeroRecords": "No se encontraron elementos",
                            "info": "Mostrando página _PAGE_ de _PAGES_ /Total de Ordenes(_MAX_)",
                            "infoEmpty": "No se encontraron elementos",
                            "infoFiltered": "(filtrado de un total _MAX_)",
                            "sSearch": "Buscar:"
                        },
                        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        "columnDefs": [
                            {className: "dt-center", "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8]}//esta propiedad centra la tabla
                        ]
                    });
                    $(".dataTables_info").css({"color": "red", "font-weight": "bold"});//ponerle el color rojo y letra grande a la inormacion del total

                    $('<div style="margin-left:50px;" class="col-md-5">' +
                            '<div class="col-mdd-1" style="padding-left: 5px">' +
                            '<label>Asignación por:</label>' +
                            '</div>' +
                            '<div class="col-md-3" style="padding:0 2px 0 2px">' +
                            '<select class="form-control" id="opciones">' +
                            '<option value="-1"></option>' +
                            '<option value="cant">Cantidad</option>' +
                            '<option value="seleccion">Selección</option>' +
                            '<option value="todos">Todos</option>' +
                            '</select>' +
                            '</div>' +
                            '<div class="col-md-3" style="padding-right:2px;display:none;" id="div_cant">' +
                            '<input type="text" class="form-control" id="cantidad">' +
                            '</div>' +
                            '<div class="col-md-2">' +
                            '<input type="button" name="btnasignar" id="btnasignar" value="Asignar Tracking" class="btn btn-primary" title="Asignar Tracking" />' +
                            '</div>' +
                            '</div>').insertAfter('#tabla_length');
                    //para ald  ar click en cantidad
                    $('select#opciones').on('change', function (event) {
                        //deselecciono todos los checkbox de la tabla  
                        var rows = oTable.rows({'search': 'applied'}).nodes();
                        // Check/uncheck checkboxes for all rows in the table
                        $('input[type="checkbox"]', rows).prop('checked', "");

                        if ($(this).val() == "cant")
                        {
                            $('#div_cant').css('display', 'block');  //muestro el textfield
                        } else if ($(this).val() == "todos")
                        {
                            //selecciono todos los checkbox de la tabla  
                            var rows = oTable.rows({'search': 'applied'}).nodes();
                            // Check/uncheck checkboxes for all rows in the table
                            $('input[type="checkbox"]', rows).prop('checked', "checked");
                            $('#div_cant').css('display', 'none');
                        } else
                        {
                            $('#div_cant').css('display', 'none');
                        }
                    });




                    //para el boton de asignar trackings dentro de la tabla

                    $('#btnasignar').on('click', function (event) {
                        //limpio el array
                        id_orden_detalle = [];
                        //lleno el array con los id de orden detalle
                        if ($('#opciones').val() == "cant")
                        {
                            //recorro la tabla 
                            oTable.rows(function (idx, data, node) {
                                if (idx < $('#cantidad').val()) {
                                    id_orden_detalle[idx] = node.id;
                                }
                            });
                        } else if ($('#opciones').val() == "todos")
                        {
                            //recorro la tabla 
                            oTable.rows(function (idx, data, node) {
                                id_orden_detalle[idx] = node.id;
                            });
                        } else if ($('#opciones').val() == "seleccion")
                        {
                            var j = 0;
                            // Iterate over all checkboxes in the table
                            oTable.$('input[type="checkbox"]').each(function () {
                                // If checkbox is checked
                                if (this.checked) {
                                    id_orden_detalle[j++] = this.value;
                                }
                            });
                        }

                        //validando que se haya escogido un valor
                        if (id_orden_detalle.length == 0)
                        {
                            $('#myModal2').find('#mensaje').html("No tiene seleccionada ninguna orden para asignarle tracking.");
                            $('#myModal2').modal('show');

                        } else
                        {
                            $('#myModal1').modal('show');
                        }



                    });

                }

                //codigo que autocompleta item1
                $("#item").autocomplete({
                    source: "buscar_item.php",
                    minLength: 2,
                    focus: function (event, ui) {
                        $("#item").val(ui.item.id_item);
                        return false;
                    },
                    select: function (event, ui) {
                        $("#item").val(ui.item.id_item);
                        return false;
                    }
                })
                        .autocomplete("instance")._renderItem = function (ul, item) {
                    return $("<li>")
                            .append("<a>" + item.id_item + "--" + item.prod_descripcion + "</a>")
                            .appendTo(ul);
                };

                $('#asignar').on('click', function (event) {

                    $('#asignar').removeClass("btn-info");
                    $('#asignar').addClass("btn-danger");
                    $('#gestionar').removeClass("btn-danger");
                    $('#gestionar').addClass("btn-info");
                    $('#arbol').css('display', 'none');
                    $('#divtabla').css('display', 'block');
                });


                //para los checkbox dentro de la ventana modal
                $('#inlineRadio1').on('click', function (event) {
                    $('#myModal1').find('#guiamaster').removeAttr('disabled');
                    $('#myModal1').find('#cuenta').attr('disabled', 'disabled');
                    $('#myModal1').find('#btnsi').removeAttr('disabled');
                    $.ajax({
                                  data:  "obtenerguia=obtenerguia",
                                  url:   'repor_excel_tracking.php',
                                  type:  'post',
                        dataType: 'json',
                        cache: false,
                                  success:  function (response) {
                            $('#myModal1').find('#guiamaster').html('');
                            for (var j = 0; j < response.length; j++)
                            {
                                $('#myModal1').find('#guiamaster').append('<option value="' + response[j]['guia'] + '">' + response[j]['guia'] + '</option>');
                            }
                        }
                              });
                });
                $('#inlineRadio2').on('click', function (event) {
                    $('#myModal1').find('#cuenta').removeAttr('disabled');
                    $('#myModal1').find('#guiamaster').attr('disabled', 'disabled');
                    $('#myModal1').find('#btnsi').removeAttr('disabled');
                });

                //para el boton asignar dentro de ventana modal
                $('#myModal1').find('#btnsi').on('click', function (event) {

                    if ($('#myModal1').find('input:radio[name=inlineRadioOptions]:checked').val() == "option1")
                    {
                        status_msg({container: $("body"), msg: " Asignando Tracking..."});
                        $.ajax({
                                      data:  "asignar=" + JSON.stringify(id_orden_detalle) + "&guia=" + $('#myModal1').find('#guiamaster').val(),
                                      url:   'repor_excel_tracking.php',
                                      type:  'post',
                            dataType: 'json',
                            success:  function (response) {
                                if (response == "ok")
                                {
                                    if ($('#ddeliver').css('display') == "block")
                                    {
                                        $('#enviar2').trigger('click');
                                    } else if ($('#sshipto').css('display') == "block")
                                    {
                                        $('#enviar1').trigger('click');
                                    } else if ($('#oordate').css('display') == "block")
                                    {
                                        $('#enviar').trigger('click');
                                    }

                                }
                            },
                            error: function (response) {

                                      },
                            complete: function ()
                            {
                                $("#status").remove();
                            }
                        });
                    } else if ($('#myModal1').find('input:radio[name=inlineRadioOptions]:checked').val() == "option2")
                    {
                        status_msg({container: $("body"), msg: " Asignando Tracking..."});
                        $.ajax({
                                      data:  "asignar=" + JSON.stringify(id_orden_detalle) + "&guia=nueva&cuenta=" + $('#myModal1').find('#cuenta').val(),
                                      url:   'repor_excel_tracking.php',
                                      type:  'post',
                            dataType: 'json',
                            success:  function (response) {
                                if (response == "ok")
                                {
                                    //status_msg({container: $("body"), msg: " Cargando Ordenes..." });   
                                    if ($('#ddeliver').css('display') == "block")
                                    {
                                        $('#enviar2').trigger('click');
                                    } else if ($('#sshipto').css('display') == "block")
                                    {
                                        $('#enviar1').trigger('click');
                                    } else if ($('#oordate').css('display') == "block")
                                    {
                                        $('#enviar').trigger('click');
                                    }
                                }
                            },
                            error: function (response) {

                                      },
                            complete: function ()
                            {
                                $("#status").remove();
                            }
                        });
                    }

                    $('#myModal1').modal('hide');
                });


                //para el boton de ordenes con trackings
                var source;
                var arbol = "";


                $('#gestionar').on('click', function (event) {
                    $('#gestionar').removeClass("btn-info");
                    $('#gestionar').addClass("btn-danger");
                    $('#asignar').removeClass("btn-danger");
                    $('#asignar').addClass("btn-info");

                    status_msg({container: $("body"), msg: " Cargando Ordenes con Tracking..."});
                    parametros["conTrack"] = "conTrack";
                    $.ajax({
                                  data:  parametros,
                                  url:   'repor_excel_tracking.php',
                                  type:  'GET',
                        dataType: 'json',
                        success:  function (response) {
                            source = response;
                            if (arbol != "")
                            {
                                arbol = $("#tree").fancytree('getTree');
                                arbol.reload(source);
                            } else {
                                cargar_tree(source);
                            }
                            $('#divtabla').css('display', 'none');
                            $('#arbol').css('display', 'block');
                        },
                        error: function (response) {

                                  },
                        complete: function ()
                        {
                            $("#status").remove();
                        }
                              });

                });

                var selKeys = []; //esta variable tendra los trackings seleccionados para eliminar
                var selRootKeys = [];
                function cargar_tree(source)
                {
                    arbol = $("#tree").fancytree({
                        checkbox: true,
                        selectMode: 3,
                        titlesTabbable: true, // Add all node titles to TAB chain
                        quicksearch: true, // Jump to nodes when pressing first character
                        source: source,
                        extensions: ["table", "gridnav", "childcounter", "filter"],
                        table: {
                            indentation: 20,
                            nodeColumnIdx: 2,
                            checkboxColumnIdx: 0
                        },
                        filter: {
                            autoApply: true, // Re-apply last filter if lazy data is loaded
                            counter: true, // Show a badge with number of matching child nodes near parent icons
                            fuzzy: false, // Match single characters in order, e.g. 'fb' will match 'FooBar'
                            hideExpandedCounter: true, // Hide counter badge, when parent is expanded
                            highlight: true, // Highlight matches by wrapping inside <mark> tags
                            mode: "dimm"  // Grayout unmatched nodes (pass "hide" to remove unmatched node instead)
                        },
                        loadChildren: function (event, data) {
                            // update node and parent counters after lazy loading
                            data.node.updateCounters();
                        },
                        renderColumns: function (event, data) {
                            var node = data.node,
                                    $tdList = $(node.tr).find(">td");
                            if (data.node.isFolder())
                            {
                                if (node.data.cierredia == "SI") {
                                    $(node.tr).addClass("cierredia");

                                } else if (node.data.closeout == "SI") {
                                    $(node.tr).addClass("closeout");

                                }
                            }

                            // (index #0 is rendered by fancytree by adding the checkbox)
                            $tdList.eq(1).text(node.getIndexHier());
                            // (index #2 is rendered by fancytree)

                            $tdList.eq(2).text(node.data.tracking);
                            $tdList.eq(3).text(node.data.Ponumber);
                            $tdList.eq(4).text(node.data.Custnumber);
                            $tdList.eq(5).text(node.data.cpitem);
                            $tdList.eq(6).text(node.data.cppais_envio);
                            $tdList.eq(7).text(node.data.cpestado_shipto);
                            $tdList.eq(8).text(node.data.cpcuidad_shipto);
                            $tdList.eq(9).text(node.data.order_date);
                            $tdList.eq(11).text(node.data.delivery_traking);
                            $tdList.eq(10).text(node.data.ShipDT_traking);
                            if (!data.node.isFolder())
                            {
                                $tdList.eq(12).html("<input type='image' style='cursor:pointer' class='subcarpeta' name='subcarpeta' id='" + node.data.tracking + "' src='../images/print.ico' heigth='30' value='' data-toggle='tooltip' data-placement='left' title = 'Imprimir etiqueta' width='20' />");
                            } else
                            {
                                if (node.data.cierredia == "SI") {
                                    $tdList.eq(12).html("<input type='image' style='cursor:pointer;' class='carpeta' name='carpeta' id='" + node.title + "' src='../images/print.ico' heigth='30' title = 'Imprimir Guia' width='20' />");
                                } else
                                {
                                    $tdList.eq(12).html("<input type='image' style='cursor:pointer;display:none;' class='carpeta' name='carpeta' id='" + node.title + "' src='../images/print.ico' heigth='30' title = 'Imprimir Guia' width='20' />");
                                }
                            }


                        },
                        select: function (event, data) {
                            // Get a list of all selected nodes, and convert to a key array:
                            selKeys = $.map(data.tree.getSelectedNodes(), function (node) {
                                return node.data.tracking;
                            });
                            var selRootNodes = data.tree.getSelectedNodes(true);
                            selRootKeys = $.map(selRootNodes, function (node) {
                                return node.key;
                            });
                        }

                    }).on("nodeCommand", function (event, data) {
                        // Custom event handler that is triggered by keydown-handler and
                        // context menu:
                        var tree = $(this).fancytree("getTree");
                        var node = tree.getActiveNode();

                        switch (data.cmd) {
                            case "remove":
                                eliminar = "guia";
                                $('#myModal').find('.modal-title').html("Eliminar Guia Mater");
                                $('#myModal').find('#nodoaeliminar').val(node.title);
                                $('#myModal').find('#mensaje').html("¿Está seguro que desea elimnar esta Guia Mater con todos sus Trackings?");
                                $('#myModal').modal('show');
                                break;

                            case "closeOut":
                                $('#myModal3').find('#mensaje_conf').html("¿Está seguro que desea darle Close Out a esta Guia?");
                                $('#myModal3').find('#btn_conf1').css('display', 'none');
                                $('#myModal3').find('#btn_conf').css('display', 'inline-block');
                                $('#myModal3').modal('show');
                                break;
                            case "cierreDia":
                                $('#myModal3').find('#mensaje_conf').html("¿Está seguro que desea darle Cierre de Dia a esta Guia?");
                                $('#myModal3').find('#btn_conf').css('display', 'none');
                                $('#myModal3').find('#btn_conf1').css('display', 'inline-block');
                                $('#myModal3').modal('show');

                                break;
                            case "masterinv":
                                var tree = $("#tree").fancytree("getTree");
                                var node = tree.getActiveNode();
                                window.open("masterinvoice.php?guia=" + node.title + "&master=generar", '_blank');
                                return false;

                                break;
                            case "manifest":
                                var tree = $("#tree").fancytree("getTree");
                                var node = tree.getActiveNode();
                                window.open("manifestUPS.php?guia=" + node.title + "&master=generar", '_blank');
                                return false;

                                break;
                                return;
                        }
                    });
                }

                //para las ventanas de confirmascion de close out y cierre de dia
                $('#myModal3').find('#btn_conf').on('click', function (event) {
                    var tree = $("#tree").fancytree("getTree");
                    var node = tree.getActiveNode();

                    $.ajax({
                                  data:  "guia=" + node.title + "&master=closeout",
                                  url:   'masterinvoice.php',
                                  type:  'GET',
                        dataType: 'json',
                                  success:  function (response) {
                            if (response == 'ok') {
                                $('#confirmacion').addClass('alert-success');
                                $('#confirmacion').html("Close Out Satisfactorio");
                                $('#confirmacion').fadeIn(function () {
                                    $('#confirmacion').fadeOut(8000);
                                });
                                $('#gestionar').trigger('click');
                                $('#myModal3').modal('hide');
                            } else if (response == 'error') {
                                $('#confirmacion').addClass('alert-success');
                                $('#confirmacion').html("Error: Esta Guia ya tiene Cierre de dia.")
                                $('#confirmacion').fadeIn(function () {
                                    $('#confirmacion').fadeOut(8000);
                                });

                            }
                        },
                        complete: function (response) {
                            $('#myModal3').modal('hide');
                        }
                    });
                });

                $('#myModal3').find('#btn_conf1').on('click', function (event) {
                    var tree = $("#tree").fancytree("getTree");
                    var node = tree.getActiveNode();

                    $.ajax({
                                  data:  "guia=" + node.title + "&master=cierredia",
                                  url:   'manifestUPS.php',
                                  type:  'GET',
                        dataType: 'json',
                                  success:  function (response) {
                            if (response == 'existe') {
                                $('#confirmacion').addClass('alert-success');
                                $('#confirmacion').html("Esta Guia ya tiene cierre de Dia realizado");
                                $('#confirmacion').fadeIn(function () {
                                    $('#confirmacion').fadeOut(8000);
                                });
                            } else if (response == 'ok') {
                                $('#confirmacion').addClass('alert-success');
                                $('#confirmacion').html("Cierre de Dia Satisfactorio");
                                $('#confirmacion').fadeIn(function () {
                                    $('#confirmacion').fadeOut(8000);
                                });
                                $('#gestionar').trigger('click');
                                window.open("pld.php?guia=" + node.title, '_blank');
                            } else
                            {
                                $('#confirmacion').addClass('alert-success');
                                $('#confirmacion').html("Ocurrió un error en el Cierre de Dia.");
                                $('#confirmacion').fadeIn(function () {
                                    $('#confirmacion').fadeOut(8000);
                                });
                                $('#gestionar').trigger('click');
                            }

                        },
                        complete: function (response) {
                            $('#myModal3').modal('hide');
                        }
                    });
                });

                //para imprimir las etiquetas
                $("table#tree").on('click', '.subcarpeta', function (event) {
                    window.open("print_ups.php?tracking=" + event.target.id, '_blank');
                    return false;
                });

                //para imprimir las etiquetas de una carpeta completa
                $('table#tree').on('click', '.carpeta', function (event) {
                    $.ajax({
                                  data:  "numguia=" + event.target.id,
                                  url:   'repor_excel_tracking.php',
                                  type:  'post',
                        cache: false,
                                  success:  function (response) {
                            var num = Math.floor(response / 200);

                            var resto = response % 200;

                            for (var i = 0; i < num; i++)
                            {
                                window.open("print_ups.php?guia=" + event.target.id + "&limite1=" + 200 * i + "&limite2=" + 200, '_blank');
                            }
                            window.open("print_ups.php?guia=" + event.target.id + "&limite1=" + 200 * i + "&limite2=" + resto, '_blank');
                        }
                    });



                });

                //PARA EL BOTON DE FILTRAR
                $("input[name=search]").keyup(function (e) {
                    var n,
                            opts = {
                                autoExpand: true,
                                leavesOnly: false
                            },
                            match = $(this).val();

                    if (e && e.which === $.ui.keyCode.ESCAPE || $.trim(match) === "") {
                        $("#btnResetSearch").click();
                        return;
                    }
                    $("#tree").fancytree("getTree").options.filter["highlight"] = true;
                    $("#tree").fancytree("getTree").options.filter["autoExpand"] = true;
                    if ($("#regex").is(":checked")) {
                        // Pass function to perform match
                        n = $("#tree").fancytree("getTree").filterNodes(function (node) {
                            return new RegExp(match, "i").test(node.title);
                        }, opts);
                    } else {
                        // Pass a string to perform case insensitive matching
                        n = $("#tree").fancytree("getTree").filterNodes(match, opts);
                    }
                    $("#btnResetSearch").attr("disabled", false);
                    $("span#matches").text("(" + n + " matches)");
                }).focus();

                $("#btnResetSearch").click(function (e) {
                    $("input[name=search]").val("");
                    $("span#matches").text("");
                    $("#tree").fancytree("getTree").clearFilter();
                }).attr("disabled", true);

                //para los botones de expandir y colapsar
                $("#expandir").click(function (event) {
                    $("#tree").fancytree("getRootNode").visit(function (node) {
                        node.setExpanded(true);
                    });
                });

                $("#colapsar").click(function (event) {
                    $("#tree").fancytree("getRootNode").visit(function (node) {
                        node.setExpanded(false);
                    });
                });

                var eliminar = ""; //esta es una bandera que te dira que es lo que se esta eliminando si guia master o trackings
                //para el boton eliminar trackings
                $('#eliminar_trackings').on('click', function (event) {
                    if (selKeys.length == 0)
                    {
                        $('#myModal2').find('#mensaje').html("No tiene trackings seleccionados para Eliminar.");
                        $('#myModal2').modal('show');
                        return;
                    }
                    eliminar = "Trackings";
                    $('#myModal').find('.modal-title').html("Eliminar Trackings");
                    $('#myModal').find('#nodoaeliminar').val("");
                    $('#myModal').find('#mensaje').html("¿Está seguro que desea eliminar los Trackings seleccionados?");
                    $('#myModal').modal('show');

                });

                //para el boton de si eliminar en ventana modal
                $('#myModal').find('#btnsi').on('click', function (event) {

                    var node = $("#tree").fancytree("getActiveNode");

                    if (eliminar == "guia")
                    {
                        var nodoElim = $('#myModal').find('#nodoaeliminar').val();
                        //eliminando la guia master
                        $.ajax({
                                      data:  "eliminar=guiamaster&nodoElim=" + nodoElim,
                                      url:   'repor_excel_tracking.php',
                                      type:  'post',
                            cache: false,
                                      success:  function (response) {
                                if (response == "1")
                                {
                                    $('#myModal2').find('#mensaje').html("No se puede eliminar la guia master porque esta como Closeout.");
                                    $('#myModal').modal('hide');
                                    $('#myModal2').modal('show');
                                    return;
                                } else if (response == "2")
                                {
                                    $('#myModal2').find('#mensaje').html("No se puede eliminar la guia master porque esta como Cierre de Dia.");
                                    $('#myModal').modal('hide');
                                    $('#myModal2').modal('show');
                                    return;
                                } else if (response == "0")
                                {
                                    node.remove();
                                    $('#myModal').modal('hide');
                                    return;
                                }

                            }
                        });
                    } else
                    if (eliminar == "Trackings")
                    {

                        $.ajax({
                                      data:  "eliminar=trackings&nodoElim=" + selKeys,
                                      url:   'repor_excel_tracking.php',
                                      type:  'post',
                            cache: false,
                            success:  function (response) {
        //                 for(var i=0;i<selRootKeys.length;i++)
        //                 {
        //                  $("#tree").fancytree("getTree").activateKey(selRootKeys[i]);
        //                  var node = $("#tree").fancytree("getActiveNode");  
        //                  node.remove();
        //                 }
                                $('#gestionar').trigger('click');
                                if (response == "1") {
                                    $('#myModal2').find('#mensaje').html("Existen trackings que no pueden ser eliminados porque su Guia Master esta como cierre de dia.");
                                    $('#myModal2').modal('show');
                                    return;
                                }

                            },
                            complete: function (response) {
                                $('#myModal').modal('hide');
                            }
                        });
                    }
                });

                /*
                 esto es para el arbol, al dar click derecho sobre un nodo raiz
                 */
                $("#tree").contextmenu({
                    delegate: "span.fancytree-node",
                    menu: [
                        {title: "Close Out", cmd: "closeOut", uiIcon: "ui-icon-close"},
                        {title: "Cierre de Dia", cmd: "cierreDia", uiIcon: "ui-icon-close"},
                        {title: "Generar MasterInvoice", cmd: "masterinv", uiIcon: "ui-icon-document"},
                        {title: "Generar UPSManifest", cmd: "manifest", uiIcon: "ui-icon-document"},
                        {title: "Borrar Guia Master", cmd: "remove", uiIcon: "ui-icon-trash"}
                    ],
                    beforeOpen: function (event, ui) {
                        var node = $.ui.fancytree.getNode(ui.target);
                        node.setActive();

                    },
                    select: function (event, ui) {
                        var that = this
                        // delay the event, so the menu can close and the click event does
                        // not interfere with the edit control
                        setTimeout(function () {

                            $(that).trigger("nodeCommand", {cmd: ui.cmd});
                        }, 100);
                    }
                });

                //al seleccionar el combo de pais, selecciono los estados en dependecia del pais seleccionado]
                $('#pais').on('change', function (event) {

                    $.ajax({
                                  data: "accion=selec_estado&pais=" + $(this).val(),
                                  url:   'repor_excel_tracking.php',
                                  type:  'post',
                        dataType: 'json',
                        success:  function (response) {
                            $('#estado').append('<option value=""></option>');
                            for (var i = 0; i < response.length; i++) {
                                $('#estado').append('<option value="' + response[i][1] + '">' + response[i][1] + "-" + response[i][0] + '</option>');
                            }
                        }
                    });
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
} else {
    $sql = "SELECT id_usuario from tblusuario where cpuser='" . $user . "'";
    $query = mysqli_query($link,$sql);
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
                    <form id="form1" name="form1" method="post" novalidate action="" class="form-inline">  
                        <div style="margin-bottom: 10px;">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Fecha <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a id="oordate">Fecha de Orden</a></li>
                                    <li><a id="sshipto">Fecha de Vuelo</a></li>
                                    <li><a id="ddeliver">Fecha de Entrega</a></li>
                                </ul>
                            </div> 
                            <div class="form-group">
                                <label><strong>País:</strong> </label>
                                <select type="text" name="pais" id="pais" class="form-control" style="width: 150px;">
<?php
//Consulto la bd para obtener solo los id de item existentes
$sql = "SELECT * FROM tblpaises_destino";
$query = mysqli_query($link,$sql);
//Recorrer los iteme para mostrar
echo '<option value=""></option>';
while ($row1 = mysqli_fetch_array($query)) {
    echo '<option value="' . $row1["codpais"] . '">' . $row1["pais_dest"] . '</option>';
}
?>    
                                </select>
                                <label class="my-error-class" style="display: none;">Seleccione el pais</label>
                            </div>


                            <div class="form-group">
                                <label><strong>Cliente:</strong></label>
                                <select type="text" name="cliente" id="cliente" class="form-control" style="width: auto;">
<?php
//Consulto la bd para obtener el codigo de la ciudad y el nombre de la ciudad origen
$sql = "SELECT * FROM tblcliente";
$query = mysqli_query($link, $sql);
//Recorrer los iteme para mostrar
echo '<option value=""></option>';
while ($row2 = mysqli_fetch_array($query)) {
    echo '<option value="' . $row2["empresa"] . '">' . $row2["empresa"] . '</option>';
}
?>    
                                </select>
                            </div>

                            <div class="form-group">
                                <label><strong>Item:</strong></label>
                                <input type='text' class="form-control" name="item" id="item" value="" style="width:150px;"/>
                            </div>
                        </div>

                        <div class="panel panel-default" id="oordate" style='display:none;'>
                            <div class="panel-heading" >
                                <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR FECHA DE ORDEN</strong></h3>
                            </div>
                            <div class="panel-body">

                                <div class="col-md-4">
                                    <p><strong>Fecha Inicio:</strong></p>
                                    <div class='input-group date' id='datetimepicker3'>
                                        <input type='text' class="form-control" name="txtinicio" id="txtinicio" value="<?php echo date('Y-m-d') ?>" placeholder="Fecha inicio"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            $('#datetimepicker3').datetimepicker({
                                                format: 'YYYY-MM-DD',
                                                showTodayButton: true
                                            });
                                        });
                                    </script>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Fecha Fin:</strong></p>
                                    <div class='input-group date' id='datetimepicker4'>
                                        <input type='text' class="form-control" name="txtfin" id="txtfin" value="<?php echo date('Y-m-d') ?>" placeholder="Fecha fin"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            $('#datetimepicker4').datetimepicker({
                                                format: 'YYYY-MM-DD',
                                                showTodayButton: true
                                            });
                                        });
                                    </script>
                                </div>
                                <div class="col-md-2">
                                    <br>

                                    <button type="button" name="enviar" id="enviar" class="btn btn-primary" aria-label="Left Align">
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Buscar
                                    </button> 
                                </div>
                                <label class="my-error-class" id='errorFecha' style="display: none;"></label>
                            </div>
                        </div>

                        <div class="panel panel-default" id="sshipto" style="display:none;">
                            <div class="panel-heading" >
                                <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR FECHA DE VUELO</strong></h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-4">
                                    <p><strong>Fecha Inicio:</strong></p>
                                    <div class='input-group date' id='datetimepicker5'>
                                        <input type='text' class="form-control" name="txtinicio1" id="txtinicio1" value="<?php echo date('Y-m-d') ?>" placeholder="Fecha inicio"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            $('#datetimepicker5').datetimepicker({
                                                format: 'YYYY-MM-DD',
                                                showTodayButton: true
                                            });
                                        });
                                    </script>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Fecha Fin:</strong></p>
                                    <div class='input-group date' id='datetimepicker6'>
                                        <input type='text' class="form-control" name="txtfin1" id="txtfin1" value="<?php echo date('Y-m-d') ?>" placeholder="Fecha fin"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            $('#datetimepicker6').datetimepicker({
                                                format: 'YYYY-MM-DD',
                                                showTodayButton: true
                                            });
                                        });
                                    </script>
                                </div>
                                <div class="col-md-2">
                                    <br>
                                    <button type="button" name="enviar1" id="enviar1" class="btn btn-primary" aria-label="Left Align">
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Buscar
                                    </button>
                                </div>
                                <label class="my-error-class" id='errorFecha1' style="display: none;"></label>
                            </div>
                        </div>

                        <div class="panel panel-default" id="ddeliver" style="display:none;">
                            <div class="panel-heading" >
                                <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR FECHA DE ENTREGA</strong></h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-4">
                                    <p><strong>Fecha Inicio:</strong></p>
                                    <div class="input-group date" id="datetimepicker7">
                                        <input type="text" class="form-control" name="txtinicio2" id="txtinicio2" placeholder="Fecha inicio"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            $('#datetimepicker7').datetimepicker({
                                                format: 'YYYY-MM-DD',
                                                showTodayButton: true
                                            });
                                        });
                                    </script>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Fecha Fin:</strong></p>
                                    <div class='input-group date' id='datetimepicker8'>
                                        <input type='text' class="form-control" name="txtfin2" id="txtfin2" placeholder="Fecha fin"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            $('#datetimepicker8').datetimepicker({
                                                format: 'YYYY-MM-DD',
                                                showTodayButton: true
                                            });
                                        });
                                    </script>
                                </div>
                                <div class="col-md-2">
                                    <br>

                                    <button type="button" name="enviar2" id="enviar2" class="btn btn-primary" aria-label="Left Align">
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Buscar
                                    </button> 
                                </div>
                                <label class="my-error-class" id='errorFecha2' style="display: none;"></label>
                            </div>
                        </div>
                    </form>
                    <div style="display:none" id="tblorden">
                        <div  class="row" style="padding: 10px;">
                            <div  class="col-md-2">
                                <input type="button" name="" id="gestionar" value="Ordenes con Tracking" class="btn btn-info" title="Gestionar Tracking" /> 
                            </div> 
                            <div  class="col-md-2">
                                <input type="button" name="" id="asignar" value="Ordenes sin Tracking" class="btn btn-danger" title="Asignar Tracking" /> 
                            </div>
                            <div  class="col-md-8">
                                <div id="confirmacion" class="alert" style="display: none"></div> 
                            </div>
                            <!--            <div  class="col-md-2" style="float: right;">
                                        <label style="background-color: #5bc0de; ">Closeout</label> 
                                        <label style="background-color: #069; ">Cierre de Dia</label>
                                    </div>-->
                        </div>
                        <!--tabla que tendra los ordenes con tracking-->
                        <div id="arbol" style="display: none;">
                            <form class="form-inline" style="float: left;">

                                <button type="button" id="expandir" class="btn btn-warning" aria-label="Left Align">
                                    <span class="glyphicon glyphicon-download" aria-hidden="true"></span> Expandir Todo
                                </button>

                                <button type="button" id="colapsar" class="btn btn-warning" aria-label="Left Align">
                                    <span class="glyphicon glyphicon glyphicon-upload" aria-hidden="true"></span> Colapsar Todo
                                </button>

                                <button type="button" id="eliminar_trackings" class="btn btn-warning" aria-label="Left Align">
                                    <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span> Eliminar trackings
                                </button>
                            </form>
                            <form class="form-inline" style="float: right">
                                <div class="form-group">
                                    <label for="search">Buscar:</label>
                                    <input style="width: auto;" class="form-control" name="search" placeholder="Buscar..." autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-default" id="btnResetSearch">X</button>
                                    <span id="matches"></span>
                                </div>
                            </form>


                            </p>   
                            <table id="tree" class="table table-condensed table-hover ">
                                <colgroup>
                                    <col width="30px"></col>
                                    <col width="30px"></col>
                                    <col width="130"></col>
                                    <col width="170px"></col>
                                    <col width="130px"></col>
                                    <col width="30px"></col>
                                    <col width="30px"></col>
                                    <col width="30px"></col>
                                    <col width="30px"></col>
                                    <col width="130px"></col>
                                    <col width="130px"></col>
                                    <col width="130px"></col>
                                    <col width="40px"></col>
                                </colgroup>
                                <thead>
                                    <tr> 
                                        <th></th>
                                        <th>#</th>
                                        <th></th>
                                        <th>Ponumber</th>
                                        <th>Custnumber</th>
                                        <th>Producto</th>
                                        <th>Pais</th>
                                        <th>Estado</th>
                                        <th>Ciudad</th>
                                        <th>F.Órden</th>
                                        <th>F.Vuelo</th>
                                        <th>F.Entrega</th>
                                        <th>Etiquetas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr> <td></td> <td></td> <td></td> <td></td> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                                </tbody>
                            </table>
                        </div>

                        <!--tabla que tendra los ordenes sin tracking-->
                        <div id="divtabla" >
                            <table id="tabla" cellspacing="0" width="100%">  
                                <thead>
                                <th></th>
                                <th>Ponumber</th>
                                <th>Custnumber</th>
                                <th>Producto</th>
                                <th>Pais</th>
                                <th>Estado</th>
                                <th>Ciudad</th>
                                <th>Fecha de Órden</th>
                                <th>Fecha de Vuelo</th>
                                <th>Fecha de Entrega</th>

                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="panel-heading">
                    <div class="contenedor" align="center">
                        <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2015 versión 3</strong>
                        <br>
                        <strong><a href="http://www.bit-store.ec/index.php/contactenos/"  style="color:white">Info</a> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></strong>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel"></h4>
                        </div>
                        <div class="modal-body">
                            <label id="mensaje">

                            </label>
                            <input type="hidden" value="" id="nodoaeliminar" name="nodoaeliminar"/>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                            <button type="button" id="btnsi" class="btn btn-primary">Si</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title2" id="myModalLabel">Error</h4>
                        </div>
                        <div class="modal-body">
                            <label id="mensaje">

                            </label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>

                        </div>
                    </div>
                </div>
            </div>  
            <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Asignar Trackings</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4"> 
                                    <label>
                                        <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" > Guia Existente
                                    </label>
                                    <label>
                                        <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> Nueva Guia
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <form class="form-horizontal">
                                        <div class="form-group">
                                            <label for="guiamaster" class="col-sm-5 control-label">Guia Master:</label>
                                            <div class="col-sm-3">
                                                <select style="width: 200px;" class="form-control" name="guiamaster" id="guiamaster">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="cuenta" class="col-sm-5 control-label">Nombre de Cuenta</label>
                                            <div class="col-sm-5">
                                                <select style="width: 200px;" class="form-control" name="cuenta" id="cuenta">
<?php
$sql = "SELECT
                        tblclienteups.nombre_cuenta,
                        tblclienteups.cuenta,
                        tblclienteups.id,
                        tblcliente_usuario.id_cliente,
                        tblcliente_usuario.id_user,
                        tblusuario.cpnombre
                        FROM
                        tblclienteups
                        INNER JOIN tblcliente_usuario ON tblclienteups.id = tblcliente_usuario.id_cliente
                        INNER JOIN tblusuario ON tblusuario.id_usuario = tblcliente_usuario.id_user 
                        WHERE tblusuario.cpuser='" . $user . "'";

$query = mysqli_query($link,$sql);
while ($com = mysqli_fetch_array($query)) {
    echo "<option value='" . $com['cuenta'] . "'>" . $com['nombre_cuenta'] . "</option>";
}
?>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" name="guianueva" id="guianueva">
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="button" id="btnsi" class="btn btn-primary" disabled="disabled">Asignar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title2" id="myModalLabel">Confirmación</h4>
                        </div>
                        <div class="modal-body">
                            <label id="mensaje_conf">

                            </label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                            <button type="button" id="btn_conf" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                            <button type="button" id="btn_conf1" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>