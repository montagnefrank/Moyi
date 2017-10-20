<script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>         
<script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>        
<script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
<script type="text/javascript" src="js/plugins/morris/raphael-min.js"></script>
<script type="text/javascript" src="js/plugins/morris/morris.min.js"></script>       
<script type="text/javascript" src="js/plugins/rickshaw/d3.v3.js"></script>
<script type="text/javascript" src="js/plugins/rickshaw/rickshaw.min.js"></script>
<script type='text/javascript' src='js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js'></script>
<script type='text/javascript' src='js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js'></script>                
<script type='text/javascript' src='js/plugins/bootstrap/bootstrap-datepicker.js'></script>                
<script type="text/javascript" src="js/plugins/owl/owl.carousel.min.js"></script>
<script type="text/javascript" src="js/plugins/moment.min.js"></script>
<script type="text/javascript" src="js/plugins/daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="js/plugins/dropzone/dropzone.min.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-file-input.js"></script>
<script type="text/javascript" src="js/plugins/form/jquery.form.js"></script>
<script type="text/javascript" src="js/plugins/cropper/cropper.min.js"></script>
<script type='text/javascript' src='js/plugins/jquery-validation/jquery.validate.js'></script>
<script type="text/javascript" src="js/plugins/smartwizard/jquery.smartWizard-2.0.min.js"></script>     
<script type="text/javascript" src="js/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/tableexport/tableExport.js"></script>
<script type="text/javascript" src="js/plugins/tableexport/jquery.base64.js"></script>
<script type="text/javascript" src="js/plugins/tableexport/html2canvas.js"></script>
<script type="text/javascript" src="js/plugins/tableexport/jspdf/libs/sprintf.js"></script>
<script type="text/javascript" src="js/plugins/tableexport/jspdf/jspdf.js"></script>
<script type="text/javascript" src="js/plugins/tableexport/jspdf/libs/base64.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="js/plugins/tagsinput/jquery.tagsinput.min.js"></script>
<script src="https://cdn.rawgit.com/download/glyphicons/0.1.0/glyphicons.js"></script>
<!--<script type="text/javascript" src="js/settings.js"></script>-->
<script type="text/javascript" src="js/plugins.js"></script>        
<script type="text/javascript" src="js/actions.js"></script>
<script type="text/javascript" src="js/demo_edit_profile.js"></script>
<script>
    //AUTOCOMPLETE DE AÑADIR PRODUCTO EN PDV.PHP
    $(function () {

        var data_1 = [<?php
$select_productos = "SELECT id_item,prod_descripcion FROM tblproductos";
$result_productos = mysqli_query($link, $select_productos);
while ($row_productos = mysqli_fetch_array($result_productos, MYSQLI_BOTH)) {
    echo '"' . $row_productos["id_item"] . ' - ' . $row_productos["prod_descripcion"] . '",';
}
?>];

        $("#prod_ac").autocomplete({
            source: data_1,
            open: function (event, ui) {

                var autocomplete = $(".ui-autocomplete:visible");
                var oldTop = autocomplete.offset().top;
                var newTop = oldTop - $("#prod_ac").height() + 25;
                autocomplete.css("top", newTop);

            }
        });
    });
    //AUTOCOMPLETE DE EDITAR PEDIDO EN HACPED.PHP
    $(function () {

        var data_1 = [<?php
$select_productos = "SELECT id_item,prod_descripcion FROM tblproductos";
$result_productos = mysqli_query($link, $select_productos);
while ($row_productos = mysqli_fetch_array($result_productos, MYSQLI_BOTH)) {
    echo '"' . $row_productos["id_item"] . ' - ' . $row_productos["prod_descripcion"] . '",';
}
?>];

        $("#prod_ac2").autocomplete({
            source: data_1,
            open: function (event, ui) {

                var autocomplete = $(".ui-autocomplete:visible");
                var oldTop = autocomplete.offset().top;
                var newTop = oldTop - $("#prod_ac2").height() + 25;
                autocomplete.css("top", newTop);

            }
        });
    });
</script>
<script>
    //AUTOCOMPLETE DE AGENCIA
    $(function () {

        var data_1 = [<?php
$select_agencia = "SELECT nombre_agencia FROM tblagencia";
$result_agencia = mysqli_query($link, $select_agencia);
while ($row_agencia = mysqli_fetch_array($result_agencia, MYSQLI_BOTH)) {
    echo '"' . $row_agencia["nombre_agencia"] . '",';
}
?>];

        $("#agencia_ac").autocomplete({
            source: data_1,
            open: function (event, ui) {

                var autocomplete = $(".ui-autocomplete:visible");
                var oldTop = autocomplete.offset().top;
                var newTop = oldTop - $("#agencia_ac").height() + 25;
                autocomplete.css("top", newTop);

            }
        });
    });
</script>
<script>
    //AUTOCOMPLETE DE FINCA
    $(function () {

        var data_1 = [<?php
$select_fincas = "SELECT nombre FROM tblfinca";
$result_fincas = mysqli_query($link, $select_fincas);
while ($row_fincas = mysqli_fetch_array($result_fincas, MYSQLI_BOTH)) {
    echo '"' . $row_fincas["nombre"] . '",';
}
?>];

        $("#finca_ac").autocomplete({
            source: data_1,
            open: function (event, ui) {

                var autocomplete = $(".ui-autocomplete:visible");
                var oldTop = autocomplete.offset().top;
                var newTop = oldTop - $("#finca_ac").height() + 25;
                autocomplete.css("top", newTop);

            }
        });
    });
</script>
<script type="text/javascript">
    //RESALTAMOS LA FILA DEL CLIENTE SELECCIONADO EN PDV.PHP
    $(window).load(function () {
        $('#pdv_client_table').on("click", "tr", function () {
            $(this).addClass(" table_selected cliente_seleccionado").siblings().removeClass(" table_selected cliente_seleccionado");
            var selectedclient = $(this).find(">:first-child").html();
            window.location.href = "/main.php?panel=pdv.php&cliente=" + selectedclient;
        });
    });
</script>
<script type="text/javascript">
//    RESALTAMOS LA COLUMNA DE LA TABLA AL HACER CLIC Y DESELECCIONAMOS CUALQUIER OTRA
    $(window).load(function () {
        $('#pdv_items').on("click", "tr", function () {
            $(this).addClass(" table_selected item_a_editar").siblings().removeClass(" table_selected item_a_editar");
        });
    });
    $(window).load(function () {
        $('#hacped_items_table').on("click", "tr", function () {
            $(this).addClass(" table_selected pedido_a_editar").siblings().removeClass(" table_selected pedido_a_editar");
        });
    });
//    PARA CUANDO SELECCIONAS SIN DESELECCIONAR EL RESTO
    $(window).load(function () {
        $('#listado').on("click", "tr", function () {
            $(this).addClass(" table_selected");
            $('tr.totalgeneral').removeClass(" table_selected");
            $('tr.totalpais').removeClass(" table_selected");
            $(this).find('input').prop('disabled', false);
        });
    });
    $(window).load(function () {
        $('#listado').on("click", "tr.table_selected", function () {
            $(this).removeClass(" table_selected");
            $(this).find('input').prop('disabled', true);
        });
    });
//    RESALTAMOS TODAS LAS COLUMNAS DE LA TABLA CON UN BOTON
    $(window).load(function () {
        $('#btn_seleccionar').click(function () {
            $("#listado").find('tr').addClass(" table_selected");
            $('tr.totalgeneral').removeClass(" table_selected");
            $('tr.totalpais').removeClass(" table_selected");
            $("#listado").find('input').prop('disabled', false);
        });
    });
//     REMOVEMOS EL RESALTADO DE TODAS LAS COLUMNAS DE LA TABLA CON UN BOTON
    $(window).load(function () {
        $('#btn_deseleccionar').click(function () {
            $("#listado").find('tr').removeClass(" table_selected");
            $("#listado").find('input').prop('disabled', true);
        });
    });
</script>
<script type="text/javascript">
    //ELIMINAMOS AL HACER CLIC EN LA PAPELERA EN PDV.PHP
    $(window).load(function () {
        $('#pdv_items').on("click", ".pdv_eliminaritem_btn", function () {
            $(this).parent().parent().remove();
        });
    });
    /////////////////////////////////////////////////////////////////////////////////////////////////////ELIMINAMOS AL HACER CLIC EN LA PAPELERA EN HACPED
    $(window).load(function () {
        $('#hacped_items_table').on("click", ".hacped_eliminaritem_btn", function () {
            $(this).parent().parent().remove();
        });
    });
</script>
<script type="text/javascript">
    //AGREGAR CAMPOS A LA TABLA DE PRODUCTOS AL HACER CLIC EN INSERTAR EN PDV.PHP
    $(window).load(function () {
        $('#insertar_item').click(function () {
            if ($("#pdv_items_insert").valid()) {
                $('#modal_nuevo_producto').modal('hide');
                var rowcount = $("#pdv_items").find('tr').length;
                $("#pdv_items").find('tbody').append("<tr></tr>");
                var fields = $("#pdv_items_insert").find('input');
                jQuery.each(fields, function (i, field) {
                    $("#pdv_items")
                            .find('tbody tr:last')
                            .append("<td>" + field.value + "</td>");
                    $("#pdv_items").find('tbody tr:last')
                            .append("<input type=\"hidden\" class=\"form-control\" " +
                                    "name=\"item_" + rowcount + "_" + fields[i].name + "\" id=\"item\" value=\"" + field.value + "\"/> ");
                });
                $("#pdv_items").find('tbody tr:last')
                        .append("<td><a href=\"#\" type=\"button\"" +
                                "id=\"btn_editar_item\" data-toggle=\"modal\" data-target=\"#modal_editar_item\"" +
                                "data-placement=\"rigth\" title=\"Editar Item\"><i class=\"fa fa-pencil-square-o fa-3x\" aria-hidden=\"true\"></i></a></td>");
                $("#pdv_items").find('tbody tr:last')
                        .append("<td><i style=\"cursor: pointer; cursor: hand; color: red\" class=\"fa fa-trash-o fa-3x pdv_eliminaritem_btn\" aria-hidden=\"true\"></i></td>");
                $("#pdv_items").find('tbody tr:last')
                        .append("<td>" +
                                "<select name=\"item_" + rowcount + "_destino\" class=\"form-control select\" data-style=\"btn-primary\">" +
                                "<?php
$cliente_seleccionado = $_GET['cliente'];
$cliente = $cliente_seleccionado;

$select_cliente_destino = 'SELECT iddestino,destino FROM tbldestinos WHERE codcliente = "' . $cliente . '"';
$result_cliente_destino = mysqli_query($link, $select_cliente_destino);
while ($row_cliente_destino = mysqli_fetch_array($result_cliente_destino, MYSQLI_BOTH)) {
    echo "<option value='" . $row_cliente_destino['iddestino'] . "'>" . $row_cliente_destino['destino'] . "</option>";
}
?>" +
                                "</select></td>");
                $("#pdv_items").find('tbody').append("<input type=\"hidden\" class=\"form-control\" " +
                        "name=\"rowcount\" id=\"rowcount\" value=\"" + rowcount + "\"/> ");
                $('#prod_ac').val('');
                $('#cantidad').val('');
                $('#precioUnitario').val('');
                $('#mensaje').val('');
            }
        });
    });
</script>
<script type="text/javascript">
    //EDITAR LOS CAMPOS DE LA FILA PDV_ITEMS EN PDV.PHP
    $(window).load(function () {
        $('#pdv_editaritem_btn').click(function () {
            if ($("#pdv_editaritem_form").valid()) {
                $('#modal_editar_item').modal('hide');
                $(".item_a_editar").remove();
                var rowcount = $("#pdv_items").find('tr').length;
                $("#pdv_items").find('tbody').append("<tr class=\"table_selected item_a_editar\"></tr>");
                var fields = $("#pdv_editaritem_form").find('input');
                jQuery.each(fields, function (i, field) {
                    $("#pdv_items")
                            .find('tbody tr:last')
                            .append("<td>" + field.value + "</td>");
                    $("#pdv_items").find('tbody tr:last')
                            .append("<input type=\"hidden\" class=\"form-control\" " +
                                    "name=\"item_" + rowcount + "_" + fields[i].name + "\" id=\"item\" value=\"" + field.value + "\"/> ");
                });
                $("#pdv_items").find('tbody tr:last')
                        .append("<td><a href=\"#\" type=\"button\"" +
                                "id=\"btn_editar_item\" data-toggle=\"modal\" data-target=\"#modal_editar_item\"" +
                                "data-placement=\"rigth\" title=\"Editar Item\"><i class=\"fa fa-pencil-square-o fa-3x\" aria-hidden=\"true\"></i></a></td>");
                $("#pdv_items").find('tbody tr:last')
                        .append("<td><i style=\"cursor: pointer; cursor: hand; color: red\" class=\"fa fa-trash-o fa-3x pdv_eliminaritem_btn\" aria-hidden=\"true\"></i></td>");
                $("#pdv_items").find('tbody tr:last')
                        .append("<td>" +
                                "<select name=\"item_" + rowcount + "_destino\" class=\"form-control select\" data-style=\"btn-primary\">" +
                                "<?php
$cliente_seleccionado = $_GET['cliente'];
$cliente = $cliente_seleccionado;

$select_cliente_destino = 'SELECT iddestino,destino FROM tbldestinos WHERE codcliente = "' . $cliente . '"';
$result_cliente_destino = mysqli_query($link, $select_cliente_destino);
while ($row_cliente_destino = mysqli_fetch_array($result_cliente_destino, MYSQLI_BOTH)) {
    echo "<option value='" . $row_cliente_destino['iddestino'] . "'>" . $row_cliente_destino['destino'] . "</option>";
}
?>" +
                                "</select></td>");
                $("#pdv_items").find('tbody').append("<input type=\"hidden\" class=\"form-control\" " +
                        "name=\"rowcount\" id=\"rowcount\" value=\"" + rowcount + "\"/> ");
            }
        });
    });
</script>
<script type="text/javascript">
    //CAMBIAMOS LOS CAMPOS "DESTINO" CON EL SELECT MASTER EN PDV.PHP
    $(window).load(function () {
        $('#select_control').on('change', function (e) {
            var selected = $('#select_control option:selected');
            var fields = $("#pdv_items").find('tbody tr td select option');
            $.each(fields, function (i, field) {
                $('#pdv_items tr td select').val(selected.val()).trigger('change');
            });
            var selects = $("#pdv_items").find('tbody tr td select');
            $.each(selects, function (i, select) {
                if (!selects.val()) {
                    $("#pdv_items").find('tbody tr td select').append("<option class=\"option_trigger\" value=\"" + selected.val() + "\">" + selected.html() + "</option>");
                    $("#pdv_items").find('tbody tr td select').val(selected.val()).trigger('change');
                }
            });
        });
    });
</script>
<script>
    //FILTRAR TABLA DE CLIENTES
    function pdv_filterclients() {
        var input, filter, table, tr, td, i;
        input = document.getElementById("filtrartabla");
        filter = input.value.toUpperCase();
        table = document.getElementById("pdv_client_table_body");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            tdcod = tr[i].getElementsByTagName("td")[0];
            tdname = tr[i].getElementsByTagName("td")[1];
            if (tdcod && tdname) {
                if (tdcod.innerHTML.toUpperCase().indexOf(filter) > -1 || tdname.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
<script>
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////FILTRAR HACPED FECHA DE SALIDA
    function hacped_filtersalida() {
        var input, filter, table, tr, td, i;
        input = document.getElementById("hacped_filtrarsalida_input");
        filter = input.value.toUpperCase();
        table = document.getElementById("hacped_filtros_table_body");
        tr = table.getElementsByClassName("total_hide");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
//                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
    //FILTRAR HACPED FECHA DE VUELO
    function hacped_filtervuelo() {
        var input, filter, table, tr, td, i;
        input = document.getElementById("hacped_filtrarvuelo_input");
        filter = input.value.toUpperCase();
        table = document.getElementById("hacped_filtros_table_body");
        tr = table.getElementsByClassName("total_hide");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
//                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
    //FILTRAR HACPED POR AGENCIA
    function hacped_filteragencia() {
        var input, filter, table, tr, td, i;
        input = document.getElementById("agencia_ac");
        filter = input.value.toUpperCase();
        table = document.getElementById("hacped_filtros_table_body");
        tr = table.getElementsByClassName("total_hide");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[3];
            if (td) {
                if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
//                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
    //FILTRAR VER ORDEN TRACKING
    function hacped_filterfinca() {
        var input, filter, table, tr, td, i;
        input = document.getElementById("finca_ac");
        filter = input.value.toUpperCase();
        table = document.getElementById("hacped_filtros_table_body");
        tr = table.getElementsByClassName("total_hide");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[4];
            if (td) {
                if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
//                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
<script type="text/javascript">
    ///////////////////////////////////////////////////////////////////////////////////////////VALIDADOR GLOBAL DE FORMULARIOS
    var validate_newclient = $("#newclient_form").validate({
        ignore: [],
        rules: {empresa: {required: true},
            empresa2: {required: true},
            direccion: {required: true},
            ciudad: {required: true},
            estado: {required: true},
            zip: {required: true},
            pais: {required: true},
            telefono: {required: true},
            vendedor: {required: true}}
    });
    var validate_newcdest = $("#newdest_form").validate({
        ignore: [],
        rules: {nombredestino: {required: true},
            shipto: {required: true},
            shipto2: {required: true},
            direccion: {required: true},
            ciudad: {required: true},
            estado: {required: true},
            zip: {required: true},
            pais: {required: true},
            telefono: {required: true}}
    });
    var validatepass = $("#passupdate").validate({
        ignore: [],
        rules: {
            oldpass: {
                required: true,
                minlength: 8,
                maxlength: 16
            },
            newpass: {
                required: true,
                minlength: 8,
                maxlength: 16
            },
            reppass: {
                required: true,
                minlength: 8,
                maxlength: 16,
                equalTo: "#newpass"
            }
        }
    });
    var validatenewitem = $("#pdv_items_insert").validate({
        ignore: [],
        rules: {
            prod_ac: {
                required: true,
                prod_ac: true
            },
            cantidad: {
                required: true,
            },
            precioUnitario: {
                required: true,
            }
        }
    });
    var validateedititem = $("#pdv_editaritem_form").validate({
        ignore: [],
        rules: {
            prod_ac: {
                required: true,
                prod_ac: true
            },
            cantidad: {
                required: true,
            },
            precioUnitario: {
                required: true,
            }
        }
    });
    var validatehacepditem = $("#hacped_additem").validate({
        ignore: [],
        rules: {
            prod_ac: {
                required: true,
                prod_ac: true
            },
            hacped_additem_precio: {
                required: true,
            },
            hacped_additem_salidafinca: {
                required: true,
            },
            hacped_additem_tentativa: {
                required: true,
            },
            hacped_additem_cantidad: {
                required: true,
            }
        }
    });
    ///////////////////////////////////////////////  VALIDAPRODUCTOS
    $.validator.addMethod("prod_ac", function (value) {
        var states = [<?php
$select_productos = "SELECT id_item,prod_descripcion FROM tblproductos";
$result_productos = mysqli_query($link, $select_productos);
while ($row_productos = mysqli_fetch_array($result_productos, MYSQLI_BOTH)) {
    echo '"' . $row_productos["id_item"] . ' - ' . $row_productos["prod_descripcion"] . '",';
}
?>] // of course you will need to add more
        var in_array = $.inArray(value.toUpperCase(), states);
        if (in_array == -1) {
            return false;
        } else {
            return true;
        }
    }, "Este producto no existe en la base de datos");

    $.validator.addMethod("finca_ac", function (value) {
        var states = [<?php
$select_fincas = "SELECT nombre FROM tblfinca";
$result_fincas = mysqli_query($link, $select_fincas);
while ($row_fincas = mysqli_fetch_array($result_fincas, MYSQLI_BOTH)) {
    echo '"' . $row_fincas["nombre"] . '",';
}
?>] // of course you will need to add more
        var in_array = $.inArray(value.toUpperCase(), states);
        if (in_array == -1) {
            return false;
        } else {
            return true;
        }
    }, "La finca no existe en la base de datos");

</script>
<script type="text/javascript">
    //AGREGAR CAMPOS A LA TABLA DE PRODUCTOS AL HACER CLIC EN AÑADIR EN HACPED.PHP
    $(window).load(function () {
        $('#hacped_insertar_item').click(function () {
            if ($("#hacped_additem").valid() && $('#finca_ac').valid()) {
                var rowcount = $("#hacped_items_table").find('tr').length;
                $("#hacped_items_table").find('tbody').append("<tr></tr>");
                var fields = $("#hacped_additem").find('.insert_item');
                jQuery.each(fields, function (i, field) {
                    $("#hacped_items_table")
                            .find('tbody tr:last')
                            .append("<td>" + field.value + "</td>");
                    $("#hacped_items_table").find('tbody tr:last')
                            .append("<input type=\"hidden\" class=\"form-control\" " +
                                    "name=\"item_" + rowcount + "_" + fields[i].name + "\" id=\"item\" value=\"" + field.value + "\"/> ");
                });
                $("#hacped_items_table").find('tbody tr:last')
                        .append("<td><a href=\"#\" type=\"button\"" +
                                "id=\"\" data-toggle=\"modal\" data-target=\"#\"" +
                                "data-placement=\"rigth\" class=\"hacped_editar_icon\" title=\"Editar Item\"><i class=\"fa fa-pencil-square-o fa-3x\" aria-hidden=\"true\"></i></a></td>");
                $("#hacped_items_table").find('tbody tr:last')
                        .append("<td><i style=\"cursor: pointer; cursor: hand; color: red\" class=\"fa fa-trash-o fa-3x hacped_eliminaritem_btn\" aria-hidden=\"true\"></i></td>");
                $("#hacped_items_table").find('tbody').append("<input type=\"hidden\" class=\"form-control\" " +
                        "name=\"rowcount\" id=\"rowcount\" value=\"" + rowcount + "\"/> ");
                $('#prod_ac').val('');
                $('#hacped_additem_cantidad').val('');
                $('#hacped_additem_precio').val('');
            }
        });
    });
    //EDITAR CAMPOS A LA TABLA DE PRODUCTOS AL HACER CLIC EN EDITAR PEDIDO EN HACPED.PHP
    $(window).load(function () {
        $('#hacped_editar_item').click(function () {
            if ($('#prod_ac2').valid()) {
                if ($('#hacped_additem_cantidad2').valid()) {
                    if ($('#hacped_additem_precio2').valid()) {
                        $('#hacped_editarpedido').hide();
                        $('#hacped_nuevopedido').show();
                        var rowcount = $("#hacped_items_table").find('tr').length;
                        $("#hacped_items_table").find('tbody').append("<tr></tr>");
                        var fields = $("#hacped_additem").find('.edit_item');
                        jQuery.each(fields, function (i, field) {
                            $("#hacped_items_table")
                                    .find('tbody tr:last')
                                    .append("<td>" + field.value + "</td>");
                            $("#hacped_items_table").find('tbody tr:last')
                                    .append("<input type=\"hidden\" class=\"form-control\" " +
                                            "name=\"item_" + rowcount + "_" + fields[i].name + "\" id=\"item\" value=\"" + field.value + "\"/> ");
                        });
                        $("#hacped_items_table").find('tbody tr:last')
                                .append("<td><a href=\"#\" type=\"button\"" +
                                        "id=\"\" data-toggle=\"modal\" data-target=\"#\"" +
                                        "data-placement=\"rigth\" class=\"hacped_editar_icon\" title=\"Editar Item\"><i class=\"fa fa-pencil-square-o fa-3x\" aria-hidden=\"true\"></i></a></td>");
                        $("#hacped_items_table").find('tbody tr:last')
                                .append("<td><i style=\"cursor: pointer; cursor: hand; color: red\" class=\"fa fa-trash-o fa-3x hacped_eliminaritem_btn\" aria-hidden=\"true\"></i></td>");
                        $("#hacped_items_table").find('tbody').append("<input type=\"hidden\" class=\"form-control\" " +
                                "name=\"rowcount\" id=\"rowcount\" value=\"" + rowcount + "\"/> ");
                        $(".pedido_a_editar").remove();
                    }
                }
            }
        });
    });
</script>
<script type="text/javascript">
//al dar click en el boton de editar pedido
    $('#hacped_edititem_btn').on('click', function () {
        var band = -1;
        $("#listado tbody tr.table_selected").each(function (index)
        {
            band = index;
        });

        if (band != 0)
        {
            alert("Para editar un pedido debe seleccionar una sola fila de la tabla");
            return;
        } else {
            //veo si solicitado>0, entregado,rechazado,cierr=0
            var solicitado = $("#listado tbody tr.table_selected td:eq(6)").html();
            var rechazado = $("#listado tbody tr.table_selected td:eq(10)").html();
            var cierre = $("#listado tbody tr.table_selected td:eq(11)").html();
            var porentregar = $("#listado tbody tr.table_selected td:eq(13) strong").html();
            var entregadas = parseInt(solicitado - porentregar);
            if (rechazado == 0 && cierre == 0)
            {
                var producto = $("#listado tbody tr.table_selected td:eq(1) strong").html();
                $("#listado tbody").append("<input type=\"hidden\" class=\"form-control\" name=\"itemid\" id=\"itemid\" value=\"" + producto + "\" />");
                $("#listado tbody").append("<input type=\"hidden\" class=\"form-control\" name=\"fincalistada\" id=\"fincalistada_input\" value=\"<?php echo $_GET['listarfinca'] ?>\" />");
                $("#hacped_edititem_form").submit();
            } else
            {
                alert("Para editar un pedido tiene que tener valores de entregadas, rechazadas y cierre de dia iguales a cero.");
                return;
            }
        }
    });
</script>
<script type="text/javascript">
//al dar click en el boton de eliminar pedido
    $('#hacped_deleteitem_btn').on('click', function () {
        var band = -1;
        $("#listado tbody tr.table_selected").each(function (index)
        {
            band = index;
        });
        if (band == -1)
        {
            alert("Para ELIMINAR un pedido debe seleccionar al menos una fila de la tabla");
            return;
        } else {
            var i = 0;
            var inputnropedido = "";
            var inputitemid = "";
            //de las filas seleccionadas solo se archivaran las que tengan en el boton de por entregar el valor 0
            $("#listado tbody tr.table_selected").each(function (index) {
                if ($("td:eq('13') strong", this).html() <= 1000) {
                    var id = $(this).attr('id');
                    var producto = $(this).find("td:eq(1) strong").html();
                    inputnropedido += '\'' + id + '\',';
                    inputitemid += '\'' + producto + '\',';
                } else {
                    i++;
                }
            });
            $("#listado").find('input.nropedido').prop('disabled', true);
            $("#listado tbody").append("<input type=\"hidden\" class=\"form-control\" name=\"inputnropedido\" id=\"inputnropedido\" value=\"" + inputnropedido + "\" />");
            $("#listado tbody").append("<input type=\"hidden\" class=\"form-control\" name=\"inputitemid\" id=\"inputitemid\" value=\"" + inputitemid + "\" />");
            $("#listado tbody").append("<input type=\"hidden\" class=\"form-control\" name=\"delete\" id=\"delete_input\" value=\"true\" />");
            $("#listado tbody").append("<input type=\"hidden\" class=\"form-control\" name=\"fincalistada\" id=\"fincalistada_input\" value=\"<?php echo $_GET['listarfinca'] ?>\" />");
            $("#hacped_edititem_form").submit();
        }
    });
</script>
<script type="text/javascript">
    $('#hacped_archiveitem_btn').on('click', function () {
        var band = -1;
        $("#listado tbody tr.table_selected").each(function (index)
        {
            band = index;
        });
        if (band == -1)
        {
            alert("Para archivar un pedido debe seleccionar al menos una fila de la tabla");
            return;
        } else {
            var i = 0;
            var inputnropedido = "";
            var inputitemid = "";
            //de las filas seleccionadas solo se archivaran las que tengan en el boton de por entregar el valor 0
            $("#listado tbody tr.table_selected").each(function (index) {
                if ($("td:eq('13') strong", this).html() <= 0) {
                    var id = $(this).attr('id');
                    var producto = $(this).find("td:eq(1) strong").html();
                    inputnropedido += '\'' + id + '\',';
                    inputitemid += '\'' + producto + '\',';
                } else {
                    i++;
                }
            });
            if (i != 0) {
                alert("Has seleccionado " + i + " pedidos que tienen cajas por entregar");
                return;
            } else {
                $("#listado").find('input.nropedido').prop('disabled', true);
                $("#listado tbody").append("<input type=\"hidden\" class=\"form-control\" name=\"inputnropedido\" id=\"inputnropedido\" value=\"" + inputnropedido + "\" />");
                $("#listado tbody").append("<input type=\"hidden\" class=\"form-control\" name=\"inputitemid\" id=\"inputitemid\" value=\"" + inputitemid + "\" />");
                $("#listado tbody").append("<input type=\"hidden\" class=\"form-control\" name=\"archivar\" id=\"archivar_input\" value=\"true\" />");
                $("#hacped_edititem_form").submit();
            }
        }
    });
</script>
<script type="text/javascript">
    //////////////////////////////////////////////REMOVER TODOS LOS FILTROS EN HACPED_FILTROS
    $(window).load(function () {
        $('#hacped_filtros_clear_btn').click(function () {
            $('tr').show();
            $('input').val('');
        });
    });
</script>
<script type="text/javascript">
    //////////////////////////////////////////////CAMBIAMOS DE INPUTS AL HACER CLIC EN EDITAR PEDIDO DE LA TABLA
    $(window).load(function () {
        $('#hacped_additem').on("click", ".hacped_editar_icon", function () {
            $('#hacped_editarpedido').show();
            $('#hacped_nuevopedido').hide();
            var itemedit = $("#hacped_items_table tbody tr.table_selected td:eq(0)").html();
            var cantedit = $("#hacped_items_table tbody tr.table_selected td:eq(1)").html();
            var priceedit = $("#hacped_items_table tbody tr.table_selected td:eq(2)").html();
            $('#prod_ac2').val(itemedit);
            $('#hacped_additem_cantidad2').val(cantedit);
            $('#hacped_additem_precio2').val(priceedit);
        });
    });
    //////////////////////////////////////////////REVERTIMOS AL HACER CLIC EN CANCELAR
    $(window).load(function () {
        $('#hacped_additem').on("click", "#hacped_editar_item_cancelar", function () {
            $('#hacped_editarpedido').hide();
            $('#hacped_nuevopedido').show();
        });
    });
</script>
<script>////////////////////////////////////////////////////////////////////////////////////////////////////////ESCONDER LOS DATEPICKERS AL SELECCIONAR LA FECHA
    $('#hacped_additem_salidafinca').on('changeDate', function (ev) {
        $(this).datepicker('hide');
    });
    $('#hacped_additem_tentativa').on('changeDate', function (ev) {
        $(this).datepicker('hide');
    });
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////EJECUTAR BUSQUEDA AL HACER CLIC EN FECHA Y OCULTAR DATEPICKER
    $('#hacped_filtrarsalida_input').on('changeDate', function (ev) {
        hacped_filtersalida();
        $(this).datepicker('hide');
    });
    $('#hacped_filtrarvuelo_input').on('changeDate', function (ev) {
        hacped_filtervuelo();
        $(this).datepicker('hide');
    });
</script>
<script>
    $(".botonExcel").click(function (event) {
        $(".clear_before_report").remove();
        $("#datos_a_enviar").val($("<div>").append($('.divtabla').clone()).html());
        $("#FormularioExportacion").submit();
    });
    $(".botonExcel2").click(function (event) {
        $("#FormularioExportacionFINCA").submit();
    });
</script>
<?php
if (isset($listarfinca)) {
    /////////////////////////////////////////////////////////////////////////////////////////////////////MOSTRAR LISTADO DE LA FINCA AUTOMATICAMENTE AL HACER CLIC HACPED
    echo "           
        <script>
            $(document).ready(function () {
                $('#modal_listado_finca').modal('show');
            });
        </script>
        ";
}
?>
<script type="text/javascript">
    //////////////////////////////////////////////////////////////////////////////////////////////////////HACPED NUEVO PEDIDO VACIAR LOS CAMPOS AL HACER CLIC EN CANCELAR
    $(window).load(function () {
        $('#hacped_cancelar').click(function () {
            $('#hacped_additem_precio2').val('');
            $('#hacped_additem_cantidad2').val('');
            $('#prod_ac2').val('');
            $('#hacped_additem_tentativa').val('');
            $('#hacped_additem_salidafinca').val('');
            $('#hacped_additem_precio').val('');
            $('#hacped_additem_cantidad').val('');
            $('#prod_ac').val('');
            $('#finca_ac').val('');
            $('#hacped_items_table tbody tr').remove();
            $('#hacped_editarpedido').hide();
            $('#hacped_nuevopedido').show();
        });
    });
</script>
<!--//////////////////////////////////////////////////////////////////////////FUNCION DE IMPRIMIR ETIQUETA EN CODIGO DEBUG-->
<script>
    function coldroom_print_tag(finca, fecha, item, reimprimir, vuelo) {
        window.open("php/print.php?finca=" + finca + "&fecha=" + fecha + "&item=" + item + "&status=" + reimprimir + "&vuelo=" + vuelo + "", "Imprimir", "width=300,height=200,top=200,left=350");
        return false;
    }
</script>
<script>
    function insertar_nuevodestino() {
        if ($('#newdest_form').valid()) {
            var formData = new FormData(document.getElementById('newdest_form'));
            formData.append('submit_nuevodest', 'true');
            $.ajax({
                                data:  formData,
                                url:   'scripts/pdv_controller.php',
                                type:  'post',
                processData: false,
                contentType: false,
                dataType: 'json',
                success:  function (response) {
                    if (response == "error")
                    {
                        $('#modal_nuevo_destino').modal('hide');
                        var box = $("#message-box-danger");
                        if (box.length > 0) {
                            box.toggleClass("open");
                        }
                        return;
                    }
                    $('#modal_nuevo_destino').modal('hide');
                    $("#select_control").append("<option class=\"option_trigger\" value=\"" + response[0] + "\">" + response[1] + "</option>");
                    $(".bootstrap-select.form-control .dropdown-menu .selectpicker")
                            .append("<li rel=\"" + response[0] + "\"><a tabindex=\"0\" class=\"option_trigger\" style=\"\"><span class=\"text\">" + response[1] + "</span><i class=\"glyphicon glyphicon-ok icon-ok check-mark\"></i></a></li>");
                    var box = $("#message-box-success");
                    if (box.length > 0) {
                        box.toggleClass("open");
                    }
                }
            });
            $("#newdest_form input").val("");
        }
    }
</script>
<script type="text/javascript">
    //CAMBIAMOS LOS CAMPOS "DESTINO" CON EL SELECT MASTER EN PDV.PHP
    $(window).load(function () {
        $('#deliverydate').on('change', function (e) {
            var date = new Date($(this).val()),
                    days = parseInt(2);
            date.setDate(date.getDate() - days);
            var day = date.getDate();
            var month = date.getMonth();
            month = month + 1;
            var year = date.getFullYear();
            if (day < 10) {
                day = "0" + day;
            }
            if (month < 10) {
                month = "0" + month;
            }
            $("#shippingdate").val(year + '-' + month + '-' + day);
            console.log($(this).val());
            console.log(date);
            console.log(days);
        });
    });
</script>
<?php
if ($_SESSION['showmodal'] == 'yes') {//////////////////////////////////////////MOSTRAR REPORTE DE ORDENES SUBIDAS
    ////////////////////////////////////////////////////////////////////////////CONFIRMAMOS QUE NO EXISTA ERROR
    if ($_SESSION['box'] != 'danger'){
        echo "           
        <script>
            $(document).ready(function () {
                $('#cot_modal').modal('toggle');
            });
        </script>
        ";
    
        unset($_SESSION['showmodal']);
    }
}
?>
<script>
    $(document).on('click', 'ul.breadcrumb li.active', function () {
        $('#cot_modal').modal('toggle');
    });
</script>

<script>
    setTimeout(function () {
        var query = window.location.href;
        var res = query.split("?");
        var vars = res[1].split("&");
        var panel = vars[0].split("=");
        if (panel[1] == "pdv.php"){
            if (vars[1] == null){
                $("a.btn").css("display", "none");
            }
        }
    }, 1);
</script>

<script>
    $(document).on('click', '#fedex_btn', function ( event ) {
        event.preventDefault();
        window.open('/scripts/fedexgenerator.php', '_newhtml');
        window.open('/scripts/fedexgenerator_large.php', '_newhtml2');
    });
</script>

<script>
    $(document).on('click', '#fedex_btn', function ( event ) {
        
    });
</script>
