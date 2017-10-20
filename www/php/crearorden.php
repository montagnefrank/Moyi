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

//Recogiendo el id del usuario
$sqluser = "SELECT id_usuario FROM tblusuario WHERE cpuser = '".$user."'";
$queryuser = mysqli_query($link, $sqluser);
$rowuser = mysqli_fetch_array($queryuser);
$usuario = $rowuser['id_usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Punto de Venta</title>
  <link href="../images/favicon.ico"  rel="icon" type="image/png" />
  
  <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" media="all"  />
  <link href="../bootstrap/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all"  />
  <link href="../bootstrap/css/prettify-1.0.css" rel="stylesheet" type="text/css" media="all"  />
  <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/dataTables.jqueryui.min.css">
  
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.0.0/css/responsive.jqueryui.min.css">
  
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
  
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script src="../js/jquery.bootstrap.wizard.js"></script>
  <!--<link href="../css/jqueryui.css" type="text/css" rel="stylesheet"/>-->
  
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.10/js/dataTables.jqueryui.min.js"></script> 
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.0.0/js/dataTables.responsive.min.js"></script> 
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.0.0/js/responsive.jqueryui.min.js"></script>
 
  <style type="text/css">
   .my-error-class {
     color:red;
     font-style: italic;
     font-size: 12px;
   }
      li a{
      	cursor:pointer;/*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
      }
     .modal-header{
          background-color : #3B5998;
          color: white;
          border-radius: 5px 5px 0 0;
      }
 
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
  </style>
 
  <script>
    $(document).ready(function() {
    var oTable1;//esta variable es la que contiene la definicion de la tabla de los item 
    var accion="nuevo"; //Accion que se ejecutara
    var id_fila=""; //id de la fila sobre la cual esta sucediendo la accion
   
   //codigo que oculta el mensaje si esta visible
   setTimeout(function() {
     $("#mensaje").fadeOut(1500);
    },3000);
    
    //codigo que crea el wizard steps
    $('#rootwizard').bootstrapWizard({
        onTabClick: function(tab, navigation, index) {
           return false;
	},
       onNext: function(tab, navigation, index) {
	     if(index==2){
              if($('#radio_ponumber_generado').is(':checked'))
              {
                 $validator = $("#form_orden").validate({
                     errorClass: "my-error-class",
                     validClass: "my-valid-class",
                  rules: {
                    ponumber_generado: "required",
                    deliver: "required",
                    shipdt: "required",
                    orddate: "required"
                  },
                  messages: {
                     ponumber_generado: "Campo Requerido",
                     deliver: "Campo Requerido",
                     shipdt:"Campo Requerido",
                     orddate:"Campo Requerido"
                 } 
               }); 
               var $valid = $("#form_orden").valid();
                if(!$valid) {
                   return false;
                }
              }
              else
              {
                 //limpio cualquier mensaje de error que haya tenido de alguna validacion anterior
                 //ej si antes tenia validado en ponumber manual
                 $('label.my-error-class').css('display','none');
              }
             }
	},
        onTabShow: function(tab, navigation, index) {
		var $total = navigation.find('li').length;
		var $current = index+1;
		
		// If it's the last tab then hide the last button and show the finish instead
		if($current >= $total) {
			$('#rootwizard').find('.pager .next').hide();
			$('#rootwizard').find('.pager .finish').show();
                        $('#rootwizard').find('.cancel').show();
			$('#rootwizard').find('.pager .finish').removeClass('disabled');
                       
		} else {
			if(index ==1){
                            $('#rootwizard').find('.pager .next').show();
                            $('#rootwizard').find('.pager .finish').hide();
                            $('#rootwizard').find('.cancel').hide();
                          
                        //consulta a la db para seleccionar el PO Number
                        $.ajax({
                             data:  "tipo=automatico",
                             url:   'buscarCliente.php',
                             type:  'post',
                             dataType: 'json',
                             success:  function (response) {
                                $('#ponumber').val(response);
                                $('#radio_ponumber').attr('checked','checked');
                                
                             }
                         });
                        }
                       
                 }
         }
    });
    
    
    //crear datatabele del cliente
    var oTable = $('#tabla').DataTable({
           "scrollX": true,
           "scrollY": "200px",
           "scrollCollapse": true,
           responsive: true,
                "language": {
                    "lengthMenu": "Mostrando _MENU_ filas por pág.",
                    "zeroRecords": "No se encontraron elementos",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No se encontraron elementos",
                    "infoFiltered": "(filtrado de un total _MAX_)",
                    "sSearch":         "Buscar:"
                }
         });
    
    //crear datatable tabla_item
    oTable1 = $('#tabla_item').DataTable({
         "scrollX": true,
         "scrollY": "200px",
         "scrollCollapse": true,
          "language": {
           "lengthMenu": "Mostrando _MENU_ filas por pág.",
           "zeroRecords": "No se encontraron elementos",
           "info": "Mostrando página _PAGE_ de _PAGES_",
           "infoEmpty": "No se encontraron elementos",
           "infoFiltered": "(filtrado de un total _MAX_)",
           "sSearch":         "Buscar:"
       }
     });
         
      //codigo que se ejecuta al dar click en una fila de la tabla
      $('#tabla tbody').on('click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        //oculto el boton de siguiente
        $('#siguiente').css('display','');
        
        //Obtengo el id del cliente seleccionado y hago una consulta para buscarlo en la db
        var codigo_cliente=$(this).children("td:eq('0')").html();
        $.ajax({
                data:  "id_cliente="+codigo_cliente,
                url:   'buscarCliente.php',
                type:  'post',
                dataType: 'json',
                success:  function (response) {
                   //lleno los campos del formulario del cliente
                   $('#cliente').val(response.empresa); 
                   $('#custnumber').val(response.codigo);
                   $('#billzip').val(response.zip);
                   $('#soldto2').val(response.empresa2);
                   $('#billaddress').val(response.direccion);
                   $('#billaddress2').val(response.direccion2);
                   $('#billphone').val(response.telefono);
                   $('#billcity').val(response.ciudad);
                   $('#billstate').val(response.estado);
                   $('#billcountry').val(response.pais);
                  
                   //MUESTRA LOS CAMPOS DEL CLIENTE LLENOS Y ESCONDE LA TABLA
                   $('#tabla_cliente').css('display','none');
                   $('#div_form_cliente').css('display','block');
                   
                    oTable1.clear().draw();//reseteo la tabla de los items
                    //consulta a la db para seleccionar los items comprados por ese cliente
                    $.ajax({
                        data:  "tipo=buscarItemCliente&codigo_cliente="+$('#custnumber').val(),
                        url:   'buscarCliente.php',
                        type:  'post',
                        dataType: 'json',
                        success:  function (response) {
                            //con este for lleno el combo con todos los destinos de este cliente
                            var liscombo = "<option value='-1'></option>";
                            for(j=0;j<response[1].length;j++)  {
                               liscombo = liscombo + "<option value='"+ response[1][j][0] +"'>" +response[1][j][2]+ "</option>"; 
                            }
                            //esto agrega cada fila de venta a la tabla
                            for(i=0;i<response[0].length;i++)  {
                                var fila=oTable1.row.add([
                                     response[0][i][2],
                                     response[0][i][8],
                                     response[0][i][3],
                                     response[0][i][4],
                                     response[0][i][5],
                                     '<input type="image" style="cursor:pointer" name="btn_modificaritem" id="btn_modificaritem" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-151-edit.png" value="" data-toggle="tooltip" data-placement="left" title = "Modificar Item" />',
                                     '<input type="image" style="cursor:pointer" name="btn_eliminaritem" id="btn_eliminaritem" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-17-bin.png" value="" data-toggle="tooltip" data-placement="left" title = "Eliminar Item" />',
                                     '<select class="form-control combo" style="width:150px" id="venta'+i+'">'+liscombo+'</select>']).draw(false).index();
                                //si tiene destino asociado lo selecciono sino lo pongo en blanco
                                if(response[0][i][9]!=null) 
                                  $('#venta'+i+ ' option[value="'+response[0][i][9]+'"]').attr('selected','selected');
                                else
                                  $('#venta'+i+ ' option[value="-1"]').attr('selected','selected');
                              //poniendole el id de la compra a la fila
                              var row = oTable1.row(fila).node();
                              $(row).attr('id',response[0][i][0]);
                            }
//                            oTable1.responsive.rebuild();
//                            oTable1.responsive.recalc();
                        }
                    });
                    
                    //consulta para seleccionar destinos del cliente para el combo de seleccionar todos los destinos
                    $.ajax({
                        data:  "tipo=buscarDestinos&codigo_cliente="+$('#custnumber').val(),
                        url:   'buscarCliente.php',
                        type:  'post',
                        dataType: 'json',
                        success:  function (response) {
                            //con este for lleno el combo con todos los destinos de este cliente
                            liscombo = "<select id='selTodos' style='width:150px' class='form-control'><option value='-1'></option>";
                            for(j=0;j<response[0].length;j++)  {
                               liscombo = liscombo + "<option value='"+ response[0][j][0] +"'>" +response[0][j][2]+ "</option>"; 
                            }
                            liscombo+='</select>';
                            $(oTable1.column(7).header()).html(liscombo);
                            oTable1.draw(false);
                            
                            //CODIGO PARA SELECCIONAR TODOS LOS DESTINOS DE LOS ITEM
                            $('select#selTodos').on('change', function() {
                                var val= $(this).val();
                                $('.combo').each(function (index){
                                  $(this).find("option:selected").removeProp('selected');
                                  $(this).children('option[value="'+val+'"]').attr('selected','selected');
                                });
                              
                              //codigo para insertar en la db el destino seleccionado en el como de selTodos para todos los items.
                                $.ajax({
                                        data:  "tipo=asignarDestino&iddestino="+val+"&idcompra=todas",
                                        url:   'buscarCliente.php',
                                        type:  'post',
                                        dataType: 'json',
                                        success:  function (response) {
                                        }        
                                 }); 
                           });
                         }});  
                    }
            });
     });
     
        //codigo para crear el timepicker
        $('#datetimepicker1').datetimepicker({
            format: 'YYYY-MM-DD',
            showTodayButton:true
        });
        $('#datetimepicker2').datetimepicker({
            format: 'YYYY-MM-DD',
            showTodayButton:true
        });
        $('#datetimepicker3').datetimepicker({
            format: 'YYYY-MM-DD',
            showTodayButton:true
        });
     
     $('#regresar').on('click', function () {
         $('#tabla_cliente').css('display','block');
         $('#div_form_cliente').css('display','none');
         $('#siguiente').css('display','none');
     });
     
    //validando formulario de nuevo item\
    $("#form_nuevoItem").validate({
        errorClass: "my-error-class",
        validClass: "my-valid-class",
    
        // Specify the validation rules
        rules: {
              item: "required",
              cantidad: {
                required: true,
                number: true
            },
              precioUnitario: {
                required: true,
                number: true
            }
        },
        
        // Specify the validation error messages
        messages: {
              item: "Campo Requerido",
              cantidad: {
                required: "Campo Requerido",
                number: "Solo números y un punto (.)"
              },
              precioUnitario: {
                required: "Campo Requerido",
                number: "Solo números y un punto (.)"
              }
        },
       
        submitHandler: function(form) {
            if(accion=='nuevo')
            {
                var formData = new FormData(document.getElementById('form_nuevoItem'));
                formData.append('accion',accion);
                formData.append('codigo_cliente',$('#custnumber').val());
                $.ajax({
                    data:  formData,
                    url:   'buscarCliente.php',
                    type:  'post',
                    dataType: 'json',
                    processData: false,  // tell jQuery not to process the data
                    contentType: false ,
                    success:  function (response) {
                        //con este for lleno el combo con todos los destinos de este cliente
                        var liscombo = "<option value='-1' selected='selected'></option>";
                        for(j=0;j<response[1].length;j++)  {
                           liscombo = liscombo + "<option value='"+ response[1][j][0] +"'>" +response[1][j][2]+ "</option>"; 
                        }
                        //esto agrega cada fila de venta a la tabla
                        for(i=0;i<response[0].length;i++)  {
                            var fila=oTable1.row.add([
                                response[0][i][2],
                                response[0][i][8],
                                response[0][i][3],
                                response[0][i][4],
                                response[0][i][5],
                                '<input type="image" style="cursor:pointer" name="btn_modificaritem" id="btn_modificaritem" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-151-edit.png" value="" data-toggle="tooltip" data-placement="left" title = "Modificar Item" />',
                                '<input type="image" style="cursor:pointer" name="btn_eliminaritem" id="btn_eliminaritem" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-17-bin.png" value="" data-toggle="tooltip" data-placement="left" title = "Eliminar Item" />',
                                '<select style="width:150px" class="form-control combo" id="venta'+i+'">'+liscombo+'</select>']).draw(false).index();
                           
                                var row = oTable1.row(fila).node();
                               $(row).attr('id',response[0][i][0]);

                         }
                        $('#form_nuevoItem').each (function(){
                            this.reset();
                        });
                        //cierro la venta modal
                        $('#ItemModal').modal('hide');
                  }    
            });
          }
          else if(accion=='editar')
          {
                var formData = new FormData(document.getElementById('form_nuevoItem'));
                formData.append('accion',accion);
                formData.append('codigo_cliente',$('#custnumber').val());
                formData.append('id_item',$('#id_item').val());
                $.ajax({
                    data:  formData,
                    url:   'buscarCliente.php',
                    type:  'post',
                    dataType: 'json',
                     processData: false,  // tell jQuery not to process the data
                     contentType: false ,
                    success:  function (response) {
                        //con este for lleno el combo con todos los destinos de este cliente
                        var liscombo = "<option value='-1' selected='selected'></option>";
                        for(j=0;j<response[1].length;j++)  {
                           liscombo = liscombo + "<option value='"+ response[1][j][0] +"'>" +response[1][j][2]+ "</option>"; 
                        }
                        //esto agrega cada fila de venta a la tabla
                        for(i=0;i<response[0].length;i++)  {
                           
                             var cell = oTable1.cell($('tr#'+id_fila+' td:eq(0)'));
                             cell.data(response[0][i][2]).draw();
                             var cell = oTable1.cell($('tr#'+id_fila+' td:eq(1)'));
                             cell.data(response[0][i][8]).draw();
                             var cell = oTable1.cell($('tr#'+id_fila+' td:eq(2)'));
                             cell.data(response[0][i][3]).draw();
                             var cell = oTable1.cell($('tr#'+id_fila+' td:eq(3)'));
                             cell.data(response[0][i][4]).draw();
                             var cell = oTable1.cell($('tr#'+id_fila+' td:eq(4)'));
                             cell.data(response[0][i][5]).draw();
                             var cell = oTable1.cell($('tr#'+id_fila+' td:eq(5)'));
                             cell.data('<input type="image" style="cursor:pointer" name="btn_modificaritem" id="btn_modificaritem" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-151-edit.png" value="" data-toggle="tooltip" data-placement="left" title = "Modificar Item" />').draw();
                             var cell = oTable1.cell($('tr#'+id_fila+' td:eq(6)'));
                             cell.data('<input type="image" style="cursor:pointer" name="btn_eliminaritem" id="btn_eliminaritem" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-17-bin.png" value="" data-toggle="tooltip" data-placement="left" title = "Eliminar Item" />').draw();
                             var cell = oTable1.cell($('tr#'+id_fila+' td:eq(7)'));
                             cell.data('<select style="width:150px" class="form-control combo" id="venta'+i+'">'+liscombo+'</select>').draw();
                              
                        }
                        $('#form_nuevoItem').each (function(){
                            this.reset();
                        });
                        //cierro la venta modal
                        $('#ItemModal').modal('hide');
                  }    
            }); 
          }
        }
      });
        
    //codigo que se ejecuta al pulsar nuevo item
    $('#nuevo_item').on('click', function () {
        $('#form_nuevoItem').each (function(){
               this.reset();
         });
         accion="nuevo";
        $('#ItemModal').modal('show');
    });
    
    //codigo que autocompleta item1
    $('#ItemModal').find("#item").autocomplete({
    source: "buscar_item.php",
    minLength: 2,
    focus: function(event,ui ) {
        $('#ItemModal').find("#item").val( ui.item.id_item );
        return false;
      },
      select: function( event, ui ) {
        $('#ItemModal').find("#item").val( ui.item.id_item );
        return false;
      }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( "<a>" +item.id_item+"--"+ item.prod_descripcion +"</a>" )
        .appendTo( ul );
   };
            
   
    //codigo para el boton de modificar item
    $('#tabla_item tbody').on('click', '#btn_modificaritem', function () {
            accion="editar";
            $('#form_nuevoItem').each (function(){
               this.reset();
            });
            id_fila=$(this).closest('tr').attr('id');
            var data=oTable1.row('#'+id_fila).data();
                  
            $('#ItemModal').find('#item').val(data[0]);
            $('#ItemModal').find('#cantidad').val(data[2]);
            $('#ItemModal').find('#precioUnitario').val(data[3]);
            $('#ItemModal').find('#mensaje').val(data[4]);
            $('#ItemModal').find('#id_item').val(id_fila);
            $('#ItemModal').modal('show');
      });
   
     //codigo para el boton de insertar destino
    $('#showDestino').on('click', function (){
      $('#DestinoModal').modal('show');
    });
    
     //validacion para el nuevo destino
     $("#form_nuevoDestino").validate({
        errorClass: "my-error-class",
        validClass: "my-valid-class",
    
        // Specify the validation rules
        rules: {
              nombredestino: "required",
              shipto: "required",
              direccion: "required",
              ciudad: "required",
              estado: "required",
              pais: "required",
              zip: "required",
              telefono: "required",
              mail: {
                 email: true
              }
        },
        
        // Specify the validation error messages
        messages: {
              nombredestino: "Campo Requerido",
              shipto: "Campo Requerido",
              direccion: "Campo Requerido",
              ciudad: "Campo Requerido",
              estado: "Campo Requerido",
              pais: "Campo Requerido",
              zip:"Campo Requerido",
              telefono:"Campo Requerido",
              mail: "Inserte una dirección de correo válida"
        },
       
        submitHandler: function(form) {
            var formData = new FormData(document.getElementById('form_nuevoDestino'));
            formData.append('tipo','insertarDestino');
            formData.append('codigo_cliente',$('#custnumber').val());
             $.ajax({
                data:  formData,
                url:   'buscarCliente.php',
                type:  'post',
                processData: false,
                contentType: false ,
                dataType: 'json',
                success:  function (response) {
                    //verifico si ese destino dio error, o sea si esta duplicado
                    if(response=="error")
                    {
                        $('#form_nuevoDestino').each (function(){
                                this.reset();
                            });
                       $("#DestinoModal").modal('hide');
                       $('#mensaje').html("<div class=\"alert alert-danger\" role=\"alert\"><strong>Error: Ya existe un destino con el mismo nombre, direccion, ciudad, estado asociado al cliente seleccionado.</strong></div>");  
                        $('#mensaje').fadeIn('slow',function (){
                            setTimeout(function() {
                                $("#mensaje").fadeOut(1500);
                            },3000);
                        });
                        return; 
                    }
                    
                    //costruyo de nuevo los combo de los destinos
                     //con este for lleno el combo con todos los destinos de este cliente
                            liscombo = "<select id='selTodos' style='width:150px' class='form-control'><option value='-1'></option>";
                            for(j=0;j<response[0].length;j++)  {
                               liscombo = liscombo + "<option value='"+ response[0][j][0] +"'>" +response[0][j][2]+ "</option>"; 
                            }
                            liscombo+='</select>';
                            $(oTable1.column(7).header()).html(liscombo);
                            
                            //CODIGO PARA SELECCIONAR TODOS LOS DESTINOS DE LOS ITEM
                            $('select#selTodos').on('change', function () {
                                var val= $(this).val();
                                $('.combo').each(function(){
                                  $(this).find("option:selected").removeProp('selected');
                                  $(this).children('option[value="'+val+'"]').attr('selected','selected');
                                });
                                
                               //codigo para insertar en la db el destino seleccionado en el combo de selTodos para todos los items.
                                $.ajax({
                                        data:  "tipo=asignarDestino&iddestino="+val+"&idcompra=todas",
                                        url:   'buscarCliente.php',
                                        type:  'post',
                                        dataType: 'json',
                                        success:  function (response) {

                                 }});
                               
                             });
                            $("#tabla_item tbody tr").each(function (index) 
                            {
                                $(this).children("td:eq(7)").each(function (index2){
                                 //obtengo el destino selecionado
                                 var valorSeleccionado=$(this).children('select').children('option:selected').val();

                                 liscombo = "<select id='selTodos' style='width:150px' class='form-control'><option value='-1'></option>";
                                 for(j=0;j<response[0].length;j++)  {
                                    if(response[0][j][0] == valorSeleccionado){
                                      liscombo = liscombo + "<option value='"+ response[0][j][0] +"' selected='selected'>" +response[0][j][2]+ "</option>"; 
                                    }
                                    else
                                    {
                                      liscombo = liscombo + "<option value='"+ response[0][j][0] +"'>" +response[0][j][2]+ "</option>"; 
                                    }
                                 }

                                 var cell = oTable1.cell(this);
                                 cell.data(liscombo).draw();
                                 });
                            });
                            $('#form_nuevoDestino').each (function(){
                                this.reset();
                            });
                            //cierro la ventana modal
                            $('#DestinoModal').modal('hide');
                        }
                     });
                    }
       });
    
    
     //codigo que se ejecuta al dar click en el eliminar de la tabla
    $('#tabla_item tbody').on('click','#btn_eliminaritem', function () {
            accion="eliminar";
            id_fila=$(this).closest('tr').attr('id');
            $('#EliminarModal').find('#eliminar_id_item').val(id_fila);
            $('#EliminarModal').modal('show');
     });
     
     //codigo que se ejecuta al dar si en el eliminar
     $('#elim_item').on('click', function () {
             var parametros = {
                "id_item":$('#eliminar_id_item').val(),
                "accion":accion
              };
              $.ajax({
                    data:  parametros,
                    url:   'buscarCliente.php',
                    type:  'post',
                    dataType: 'json',
                    success:  function (response) {
                       $('#EliminarModal').modal('hide');  
                       $('#mensaje').html(response.message);
                       $('#mensaje').fadeIn('slow',function (){
                            setTimeout(function() {
                                $("#mensaje").fadeOut(1500);
                            },3000);
                       }); 
                       oTable1.row($('tr#'+id_fila)).remove().draw( false );
                   },
                    error: function (response) {
                       $('#EliminarModal').modal('hide');  
                        $('#mensaje').html(response.message);
                        $('#mensaje').fadeIn('slow',function (){
                            setTimeout(function() {
                                $("#mensaje").fadeOut(1500);
                            },3000);
                        }); 
                    }
              });
            });
      
     //codigo que se ejecuta cuando se selecciona un destino en los combo de destinos
     $('#tabla_item tbody').on('change', 'select', function () {
        //obtengo el destino seleccionado
        var iddestino=$(this).children('option:selected').val();
        var idcompra=$(this).closest('tr').attr('id');
        $.ajax({
                data:  "tipo=asignarDestino&iddestino="+iddestino+"&idcompra="+idcompra,
                url:   'buscarCliente.php',
                type:  'post',
                dataType: 'json',
                success:  function (response) {
                    
                }});
     });
          
     //codigo que se ejecuta al puslar el boton de nuevo cliente
     $('#btn_nuevo_cliente').on('click', function (){
         $('#ClienteModal').modal('show');
     });
     
     //validacion del  formulario de cliente
     $("#form_nuevoCliente").validate({
        errorClass: "my-error-class",
        validClass: "my-valid-class",
    
        // Specify the validation rules
        rules: {
              codigo: "required",
              empresa: "required",
              direccion: "required",
              ciudad: "required",
              estado: "required",
              zip: "required",
              pais: "required",
              telefono: "required",
              vendedor: "required",
              mail: {
                email: true
              }
        },
        messages: {
              codigo: "Campo Requerido",
              empresa: "Campo Requerido",
              direccion: "Campo Requerido",
              ciudad:"Campo Requerido",
              estado: "Campo Requerido",
              zip:"Campo Requerido",
              pais:"Campo Requerido",
              telefono:"Campo Requerido",
              vendedor:"Campo Requerido",
              //mail: "Por favor inserte una dirección de correo válida"
        },
       
        submitHandler: function(form) {
            var formData = new FormData(document.getElementById('form_nuevoCliente'));
            formData.append('tipo','insertarCliente');
            $.ajax({
                data:  formData,
                url:   'buscarCliente.php',
                type:  'post',
                processData: false,
                contentType: false ,
                dataType: 'json',
                success:  function (response) {
                   
                    for(i=0;i<response.length;i++)  {
                       
                            var fila=oTable.row.add([
                                response[i][2],
                                response[i][1],
                                response[i][3],
                                response[i][5],
                                response[i][6],
                                response[i][7],
                                response[i][8],
                                response[i][9],
                                response[i][4],
                                response[i][13],
                                response[i][11]
                             ]).draw(false); 
                         }
                        $('#form_nuevoCliente').each (function(){
                            this.reset();
                        });
                        //cierro la venta modal
                        $('#ClienteModal').modal('hide');
                }
            });
        }
      });
     
     //codigo que se ejecuta al dar en registrar
     $('li.finish').on('click',function(){ 
       
             if(oTable1.data().count()!=0) 
             {
                $("#tabla_item tbody tr").each(function (index) 
                 {
                  $(this).children("td:eq('7')").each(function (index2) 
                   {
                      if($(this).children('select').val()==-1)
                      {
                        $('#mensaje').html("<div class=\"alert alert-danger\" role=\"alert\"><strong>No puede Registrar estas ventas hasta que no asigne el destino a todos los items.</strong></div>");  
                        $('#mensaje').fadeIn('slow',function (){
                            setTimeout(function() {
                                $("#mensaje").fadeOut(1500);
                            },3000);
                        });
                        return;
                      }
                   });
                   
               });
             }
             else
             {
                   $('#mensaje').html("<div class=\"alert alert-danger\" role=\"alert\"><strong>No existe ningún Item para Registrar.</strong></div>");  
                        $('#mensaje').fadeIn('slow',function (){
                            setTimeout(function() {
                                $("#mensaje").fadeOut(1500);
                            },3000);
                        });
                        return;
             }
             
             //hago la peticion al servidor enviando todos los datos necesarios
             var parametros = {
                "deliver":$('#deliver').val(),
                "shipdt":$('#shipdt').val(),
                "orddate":$('#orddate').val(),
                "satdel":$('#satdel').val(),
                "consolidado":$('#consolidado').val(),
                "codcliente":$('#custnumber').val(),
                "tipo":"registrar"
              };
              if($('#radio_ponumber_generado').is(':checked'))
              {
               parametros['ponumber']= $('#ponumber_generado').val();
              }
              else
              {
                parametros['ponumber']= $('#ponumber').val();
              }
              
              $.ajax({
                data:  parametros,
                url:   'buscarCliente.php',
                type:  'post',
                dataType: 'json',
                success:  function (response) {
                    if(response.success=='true')
                    {
                      window.location.href='crearorden.php?mensaje=0';  
                    }
                    else
                    {
                       $('#mensaje').html('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>'+
                               '<span class="sr-only">Error:</span> <strong>'+response.message+'<strong></div>');
                       $('#mensaje').fadeIn('slow',function (){
                            setTimeout(function() {
                                $("#mensaje").fadeOut(1500);
                            },3000);
                        }); 
                    }
                }});
    
     });
 });
 function gestionarDestinos(){
      window.open("gestionardestinos.php");
      return false;
    }
  </script>

  <!-- Fin de funcion jquery para buscador -->

  <style>
  .contenedor {
       margin:0 auto;
       width:85%;
       text-align:center;
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
               <?php
			  if($rol == 3 || $rol == 8){?>
			  	<li><a href="./php/reenvioordenes.php" ><strong>Reenviar Órdenes</strong></a></li>
			  <?php }?> 
 
			  <?php 
              if($rol<= 2){ ?>
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
                                  <li><a href="subirOrden.php"><strong>Cargar Órdenes</strong></a></li>
                                    <li class="divider"></li>
                                    <li><a href="subirTracking.php"><strong>Cargar Tracking</strong></a></li>
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
                 <?php
                      if($rol<= 2) { ?>
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
              
              <?php
			  if($rol<= 2 || $rol == 3){?>
				  <li class="dropdown">
                  	<a tabindex="0" data-toggle="dropdown">
                    	<strong>Registro</strong><span class="caret"></span>
                    </a>
				   <ul class="dropdown-menu" role="menu">
					  <li><a href="crearProductos.php"><strong>Registro de Productos</strong></a></li>                      					
                    <?php
                    if($rol <= 2){ ?>
                    <li class="divider"></li>
					  <li><a href="crearClientes.php" ><strong>Registro de Clientes</strong></a></li>
                      <?php 
					}
					  ?>
                      <?php
                      if($rol <= 2){ ?>
                      <li class="divider"></li>					  
					  <li><a href="crearFincas.php" ><strong>Registro de Fincas</strong></a></li>
                       <?php 
					}
					  ?>
                       <?php
                      if($rol <= 2){ ?>
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
              <?php
              if($rol<= 2) {?>				 
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
                  
                 <?php if($rol<= 2) {?> 
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
                 
                 <?php if($rol == 1){  ?>
			     	<li ><a href="usuarios.php"><strong>Usuarios</strong></a></li>
				<?php }else{
					$sql   = "SELECT id_usuario from tblusuario where cpuser='".$user."'";
					$query = mysqli_query($link, $sql);
					$row = mysqli_fetch_array($query);
					echo '<li><a href="" onclick="cambiar(\''.$row[0].'\')"><strong>Contraseña</strong></a></li>';
					
					 }
					 ?>	
    </ul>
   <ul class="nav navbar-nav navbar-right">
        <li><a class="navbar-brand" href="" data-toggle="tooltip" data-placement="bottom" title="Usuario conectado"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo $user?></a></li>
          <li><a class="navbar-brand" href="../index.php" data-toggle="tooltip" data-placement="bottom" title="Salir del sistema" ><span class="glyphicon glyphicon-off" aria-hidden="true"></span></a></li>
        </ul>
  </div>
</nav>
</div>
<div class="panel-body" align="center">
   <?php if(isset($_GET['mensaje']) && $_GET['mensaje']==0)
                {
                ?>
                <div id="mensaje">
                     <div class="alert alert-success" role="alert">Registro Satisfactorio</div> 
                 <?php }else{
                    
                    ?>
                    <div id="mensaje" style="display: none;">
                 <?php }?>
                </div>
  <div id="rootwizard">
        <div class="navbar">
          <div class="navbar-inner">
            <div class="container">
        <ul>
                <li><a href="#tab1" data-toggle="tab">Datos del Cliente</a></li>
                <li><a href="#tab2" data-toggle="tab">Datos de la Orden</a></li>
                <li><a href="#tab3" data-toggle="tab">Datos del Producto</a></li>
                
        </ul>
         </div>
          </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane" id="tab1">
              
              <div id="tabla_cliente"> 
                  <div class="row"> 
                    <div class="col-md-6" style="float: left;">
                       <label style="display: flex;"><h4><strong>Seleccione el cliente</strong></h4></label> 
                    </div>
                    <div class="col-md-2" style="float: right;">
                        <button type="button" class="btn btn-primary" id="btn_nuevo_cliente" data-toggle="tooltip" data-placement="rigth" title="Crear nuevo Cliente">
                            <span class="glyphicon glyphicon-plus-sign black"> </span> Nuevo Cliente
                        </button>
                    </div>
                 </div>
              <!--Creamos la tabla del cliente-->
              <?php
                    $sql = "SELECT tblcliente.*,tblusuario.cpnombre FROM tblcliente INNER JOIN tblusuario ON tblusuario.id_usuario = tblcliente.vendedor";
                    $consulta = mysqli_query($link, $sql);
                    //Obtiene la cantidad de filas que hay en la consulta
                    $filas = mysqli_num_rows($consulta);
              ?>
	
              <table id="tabla" border="0" class="responsive nowrap stripe row-border order-column" cellspacing="0" width="100%">
                <thead>
                   <tr>
                    <th class="all"><strong>Código</strong></th>
                    <th class="all"><strong>Nombre</strong></th>
                    <th class="all"><strong>Dirección</strong></th>
                    <th class="all"><strong>Dirección2</strong></th>
                    <th class="all"><strong>Ciudad</strong></th>
                    <th class="all"><strong>Estado</strong></th>
                    <th class="all"><strong>Zip</strong></th>
                    <th class="all"><strong>País</strong></th>
                    <th class="all"><strong>Teléfono</strong></th>
                    <th class="all"><strong>Vendedor</strong></th>
                    <th class="all"><strong>E-mail</strong></th>
                   </tr>
                </thead>
                <tbody>
                <?php
         	 while($row = mysqli_fetch_array($consulta)) {
			$nombre = $row['empresa'];
			$apellido = $row['vendedor'];
			echo "<tr style='cursor:pointer;'>";
			echo "<td>".$row['codigo']."</td>";
			echo "<td>".$row['empresa']."</td>";
			echo "<td>".$row['direccion']."</td>";
			echo "<td align='center'>".$row['direccion2']."</td>";
			echo "<td align='center'>".$row['ciudad']."</td>";
			echo "<td align='center'>".$row['estado']."</td>";
			echo "<td align='center'>".$row['zip']."</td>";
			echo "<td>".$row['pais']."</td>";
			echo "<td align='center'>".$row['telefono']."</td>";
			echo "<td align='center'>".$row['cpnombre']."</td>";
			echo "<td>".$row['mail']."</td>";
			echo "</tr>";
      
		};//Fin while $resultados
                ?>
	
                </tbody>
              </table>
              </div>
             <div id="div_form_cliente" style="display: none;">
                <div class="panel panel-default">
                    <div class="panel-heading">Datos generales del Cliente</div>
                    <div class="panel-body">
                        <form class="form-horizontal" id="form_cliente">
                            
                      <div class="row">
                     <div class="col-md-1">
                         <button type="button" class="btn btn-default " id="regresar">
                            <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Listado de Clientes
                          </button>
                     </div>
                     </div>
                      <div class="row">
                        <div class="col-md-4">
                            <label for="servicio" class="control-label">Custnumber:</label>
                            <input type="text" readonly="readonly" class="form-control" name="custnumber" id="custnumber"/>
                        </div>
                        <div class="col-md-4">
                            <label for="cliente" class="control-label">Nombre del Cliente:</label>
                            <input type="text" readonly="readonly" class="form-control" name="cliente" id="cliente"/>
                        </div>
                     </div>
                      <div class="row">
                        <div class="col-md-4">
                            <label for="billzip" class="control-label">Cod. Postal:</label>
                            <input type="text" readonly="readonly" class="form-control" name="billzip" id="billzip"/>
                        </div>
                       
                        <div class="col-md-4">
                            <label for="soldto2" class="control-label">Apellido del Cliente:</label>
                            <input type="text" readonly="readonly" class="form-control" name="soldto2" id="soldto2"/>
                        </div> 
                     </div>
                      <div class="row">
                        <div class="col-md-4">
                            <label for="billaddress" class="control-label">Dirección:</label>
                            <input type="text" readonly="readonly" class="form-control" name="billaddress" id="billaddress"/>
                        </div>
                        <div class="col-md-4">
                            <label for="billaddress2" class="control-label">Dirección2:</label>
                            <input type="text" readonly="readonly" class="form-control" name="billaddress2" id="billaddress2"/>
                        </div>
                        <div class="col-md-4">
                            <label for="billphone" class="control-label">Teléfono:</label>
                            <input type="text" readonly="readonly" class="form-control" name="billphone" id="billphone"/>
                        </div> 
                     </div>
                      <div class="row">
                        <div class="col-md-4">
                            <label for="billcity" class="control-label">Ciudad:</label>
                            <input type="text" readonly="readonly" class="form-control" name="billcity" id="billcity"/>
                        </div>
                        <div class="col-md-4">
                            <label for="billstate" class="control-label">Estado:</label>
                            <input type="text" readonly="readonly" class="form-control" name="billstate" id="billstate"/>
                        </div>
                        <div class="col-md-4">
                            <label for="billcountry" class="control-label">País:</label>
                            <input type="text" readonly="readonly" class="form-control" name="billcountry" id="billcountry"/>
                        </div> 
                     </div>
                  </form> 
                    </div>
                  </div>
                    
               
             </div>
            </div>
            <div class="tab-pane" id="tab2">
                <div class="row">
                 <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="text-align: left;">Seleccione origen del Ponumber</div>
                        <div class="panel-body">
                           <form id="form_orden">
                            <table class="table">
                                <tr>
                                    <td>
                                       <label for="ponumber" class="radio-inline"> 
                                           <input type="radio" name="ponumberr" id="radio_ponumber" value="Ponumber"> Ponumber
                                      </label>
                                    </td>
                                    <td>
                                        <input type="text" readonly="readonly" class="form-control" name="ponumber" id="ponumber"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                      <label for="ponumber_generado" class="radio-inline">
                                       <input type="radio" name="ponumberr" id="radio_ponumber_generado" value="Ponumber_generado"> Utilizar PO
                                      </label>  
                                    </td>
                                    <td>
                                       <input type="text" class="form-control" name="ponumber_generado" id="ponumber_generado"/> 
                                    </td>
                                </tr>
                           </table>
                           
                        </div>
                           
                </div>
                </div>
                 <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="text-align: left;">Fechas</div>
                        <div class="panel-body">
                          <div class="form-group">
                                <label for="orddate" class="control-label">Fecha de Entrega:</label>
                                <div class='input-group date' id='datetimepicker2'>
                                    <input type='text' class="form-control" id="deliver" name="deliver" value="<?php echo date ('Y-m-d')?>" tabindex="13" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                             <div class="form-group">
                                <label for="orddate" class="control-label">Fecha de Vuelo:</label>
                                <div class='input-group date' id='datetimepicker3'>
                                <input type='text' class="form-control" id="shipdt" name="shipdt" value="<?php 
                                    $fecha = date('Y-m-d');
                                    $nuevafecha = strtotime ( '-3 day' , strtotime ( $fecha ) ) ;
                                    $nuevafecha = date ( 'Y-m-d' , $nuevafecha );       
                                    echo $nuevafecha?>" tabindex="14" required />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                           </div>
                           <div class="form-group">
                              <label for="orddate" class="control-label">Fecha de Orden:</label>
                              <div class='input-group date' id='datetimepicker1'>
                                 <input type='text' class="form-control" name="orddate" id="orddate" value="<?php echo date ('Y-m-d')?>" tabindex="12" required />
                                  <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                   </span>
                               </div>
                           </div>
                             
                         
                </div>
                </div>
               </div>
                <div class="col-md-2">
                     <div class="form-group">
                                <label for="satdel" class="control-label">SatDel:</label>
                                <select class="form-control" type="text" name="satdel" id="satdel" tabindex="15">
                                    <option selected="selected">N</option>
                                    <option>Y</option>  
                                </select>
                        </div>
                    <div class="form-group" >
                        <label for="consolidado" class="control-label" style="background-color: red;color: white;padding: 10px;">Consolidado:</label>
                        <select class="form-control" type="text" name="consolidado" id="consolidado">
                            <option selected="selected" value="N">N</option>
                            <option value="Y">Y</option>  
                        </select>
                    </div>
                </div>
              </form>
             </div>
            </div>
            <div class="tab-pane" id="tab3">
                
                <div class="row" style="margin-bottom: 10px;">
                  <div class="col-md-2">
                        <button type="button" class="btn btn-primary" id="nuevo_item" data-toggle="tooltip" data-placement="rigth" title="Crear nuevo Item">
                            <span class="glyphicon glyphicon-plus-sign black"> </span> Nuevo Item
                        </button>
                  </div>
                   
                  <div class="col-md-2" style="float: right;">
                      <button type="button" class="btn btn-primary" id="showGestionarDestinos" data-toggle="tooltip" data-placement="rigth" onclick="gestionarDestinos();" title="Gestionar destinos">
                           <span class="glyphicon  black"> </span> Gestionar destino
                        </button>
                   </div>
                    <div class="col-md-2" style="float: right;">
                        <button type="button" class="btn btn-primary" id="showDestino" data-toggle="tooltip" data-placement="rigth" title="Añadir destino">
                            <span class="glyphicon glyphicon-plus-sign black"> </span> Añadir destino
                        </button>
                       
                  </div>
               </div>
     
                <div>
                <table id="tabla_item" border="0" class="responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="all">Código de Producto</th>
                        <th class="all">Producto</th>
                        <th class="all">Cantidad</th>
                        <th class="all">Precio Unitario</th>
                        <th class="all">Mensaje</th>
                        <th class="all">Editar</th>
                        <th class="all">Eliminar</th>
                        <th class="all"></th>
                      </tr>
                    </thead>
                    <tbody>
                     
                    </tbody>
                </table>     
               </div>
            </div>
                
                <ul class="pager wizard">
                     <li class="previous"><a href="#">Anterior</a></li>
                     <li class="next" id="siguiente" style="display: none;"><a href="#">Siguiente</a></li>
                     <li class="next finish" style="display:none;"><a href="javascript:;">Registrar</a></li>
                     <li class="cancel" style="float: right;display:none;"><a href="crearorden.php">Cancelar</a></li>
                     
               </ul>
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
</div> <!-- /container -->

<div class="modal fade" id="ItemModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Nuevo Item</h4>
      </div>
      <div class="modal-body">
          <form id="form_nuevoItem" method="post">
           <div class="row">     
             <div class="col-md-8">
                   <label for="item" class="control-label">Producto:</label>
                   <input type="text" id="item" name="item" class="form-control"/>
                    
             </div>
           </div>
           <div class="row">     
             <div class="col-md-8">
                   <label for="cantidad" class="control-label">Cantidad:</label>
                  <input type="text" id="cantidad" class="form-control" name="cantidad" value="" size="10"/>
             </div>
           </div>
           <div class="row">     
             <div class="col-md-8">
                   <label for="precioUnitario" class="control-label">Precio Unitario:</label>
                  <input type="text" id="precioUnitario" class="form-control" name="precioUnitario" value="" size="10"/>
             </div>
           </div>   
            <div class="row">     
             <div class="col-md-8">
                   <label for="mensaje" class="control-label">Mensaje:</label>
                   <textarea type="text" id="mensaje" name="mensaje" tabindex="19" class="form-control" size="70" rows="4"/></textarea>
             </div>
           </div>   
           <input type="hidden" name="id_item" id="id_item" />
          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" id="insertar_item" class="btn btn-primary">Insertar</button>
      </div>
     </form>
    </div>
  </div>
</div>
<?php include 'nuevodestino.php'; ?>
<?php include 'nuevo_Cliente.php'; ?>

<div class="modal fade" id="EliminarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Eliminar Producto</h4>
      </div>
      <div class="modal-body">
          <form id="form_eliminar_producto">
          <div class="form-group">
            <label for="" class="control-label">Está Seguro que desea eliminar este Item</label>
          </div>
          <input type="hidden" name="eliminar_id_item" id="eliminar_id_item" />
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button type="button" id="elim_item" onclick="" class="btn btn-primary">Si</button>
      </div>
    </div>
  </div>
</div>   
</body>
</html>
