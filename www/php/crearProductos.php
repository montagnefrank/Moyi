<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");
require_once('barcode.inc.php');

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

//OBTENIENDO LA FINCA DEL USUARIO
$sql   = "SELECT finca FROM tblusuario WHERE cpuser = '".$user."'";
$query = mysqli_query($link,$sql) or die ("Error seleccionando la finca de este usuario");
$row   = mysqli_fetch_array($query);
$finca = $row['finca'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registro de Productos</title>
    <script type="text/javascript" src="../js/script.js"></script>
  <script language="javascript" src="../js/imprimir.js"></script>
  <link rel="icon" type="image/png" href="../images/favicon.ico" />
  
  <link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
  <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">

 <script language="javascript" src="../js/imprimir.js"></script>
 <script type="text/javascript" src="../js/script.js"></script>
 <script src="../bootstrap/js/jquery.js"></script>
 <script src="../bootstrap/js/bootstrap.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
 <script src="../bootstrap/js/bootstrap-submenu.js"></script>
 <script src="../bootstrap/js/bootstrap-modal.js"></script>
 <script src="../bootstrap/js/docs.js" defer></script>
 <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

 <link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/dataTables.jqueryui.min.css">
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.0.0/css/responsive.jqueryui.min.css">
 <link rel="stylesheet" type="text/css" href="../bootstrap/css/fileinput.css">
 <link rel="stylesheet" type="text/css" href="../css/lightbox.css">
 
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.10/js/dataTables.jqueryui.min.js"></script> 
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.0.0/js/dataTables.responsive.min.js"></script> 
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.0.0/js/responsive.jqueryui.min.js"></script>
<script type="text/javascript" language="javascript" src="../bootstrap/js/fileinput.js"></script>
<script type="text/javascript" language="javascript" src="../bootstrap/js/fileinput_locale_es.js"></script>

  <style>
  .contenedor {
       margin:0 auto;
       width:99%;
       text-align:center;
  }
 
  .navbar-fixed-top + .content-container {
  	margin-top: 70px;
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
.modal {
    overflow-y:auto;
    max-height:100%;
}
  </style>
  <script type="text/javascript">
     $(document).ready(function ($) {
        var accion="nuevo"; //Accion que se ejecutara
        var id_fila=""; //id de la fila sobre la cual esta sucediendo la accion
        var id_filareceta=0;//variable que tendra el ultimo id de la tabla filarectea para saber a la hora de insertar una nueva receta saber que id ponerle
       
        //para cargar el inputfile
        // with plugin options
        $("#imagen").fileinput({'showUpload':false,
            showCaption: false,
            allowedFileTypes: ["image"],
            'previewFileType':'any',
            allowedFileExtensions: ["jpg"],
            maxImageWidth: 1024,
            maxImageHeight: 768,
            language:'es'});
        


        var oTable = $('#tabla').DataTable({
           "scrollX": true,
           responsive: true,
                "language": {
                    "lengthMenu": "Mostrando _MENU_ filas por pág.",
                    "zeroRecords": "No se encontraron elementos",
                    "info": "Mostrando página _PAGE_ de _PAGES_ /Total de Productos(_MAX_)",
                    "infoEmpty": "No se encontraron elementos",
                    "infoFiltered": "(filtrado de un total _MAX_)",
                    "sSearch":         "Buscar:"
                }
         });
         //Para limpiar todos los campos del formulario
        $.fn.clearForm = function() {
            return this.each(function() {
              var type = this.type, tag = this.tagName.toLowerCase();
              if (tag == 'form')
                return $(':input',this).clearForm();
              if (type == 'text' || type == 'password' || tag == 'textarea')
                this.value = '';
              else if (type == 'checkbox' || type == 'radio')
                this.checked = false;
              else if (tag == 'select')
                this.selectedIndex = -1;
            });
          }; 
        //codigo que se ejecuta al pulsar el boton de nuevo
        $('#nuevo').on('click', function(){
          accion="nuevo";
          id_filareceta=0; //reseteo esta variable para comenzar a ponerle los id a las filas desde 1
          tabla_receta.clear().draw();//reseteo la tabla de la receta
          $('#form_producto').clearForm();
          $('#packtype').val('CP');
          $('#myModal').find('#preview-imagen').html('');
          $('#guardar').attr('disabled','disabled');
          $('#myModal').find('#item').removeAttr('readonly');//habilito el item por si haya estado desabilitado
          $(document).find('body').css('overflow', 'hidden');
          $('#myModal').modal('show');
        });
        
       //codigo que se ejecuta al editar un nuevo elemento
        $('#tabla tbody').on('click', '#editar', function () {
            accion="editar";
            id_filareceta=0; //reseteo esta variable para comenzar a ponerle los id a las filas desde 1
            tabla_receta.clear().draw();//reseteo la tabla de la receta
            $('#form_producto').clearForm();
            $('#packtype').val('CP');
            $('#guardar').removeAttr('disabled'); //habilito el boton de guardar
            id_fila=$(this).closest('tr').attr('id');
            $('#myModal').find('#preview-imagen').html('');
            $('#myModal').find('#item').attr('readonly','readonly');//desabilito el item para que no puedan modificarlo
            
            //llamo para obtener todos los datos del producto
             var item=$(this).parents("tr").find("td:eq('0')").text();
             $.ajax({
                    data:"accion=selTodos&id="+item,
                    url:'nuevofiltro_producto.php',
                    type:'post',
                    dataType: 'json',
                    success:function (response) {
                         $('#myModal').find('#item').val(response.id_item);
                         $('#myModal').find('#desc').val(response.prod_descripcion);
                         $('#myModal').find('#descgen').val(response.gen_desc);
                         $('#myModal').find('#receta').val(response.receta);
                         $('#myModal').find('#origen option[value="'+response.origen+'"]').prop('selected','selected');
                         $('#myModal').find('#finca option[value="'+response.finca+'"]').prop('selected','selected');
                         $('#myModal').find('#servicio').val(response.cpservicio);
                         $('#myModal').find('#boxtype option:contains(\"'+response.nombre_Box+'"\)').prop('selected','selected');
                         $('#myModal').find('#packtype').val(response.cptipo_pack);
                         $('#myModal').find('#Pack').val(response.pack);
                         $('#RecetaModal').find('#Dclvalue').val(response.dclvalue);
                         $('#myModal').find('#largo').val(response.length);
                         $('#myModal').find('#ancho').val(response.width);
                         $('#myModal').find('#alto').val(response.heigth);
                         $('#myModal').find('#peso').val(response.wheigthKg);
                         $('#myModal').find('#preview-imagen').append('<a href="../images/productos/'+response.item+'.jpg" data-lightbox="'+response.item+'"><img id="img" width="50px" heigth="50px" src="../images/productos/'+response.item+'.jpg" data-toggle="tooltip" data-placement="left"/></a>');
                         $('#myModal').find('#preview-imagen').css('display','block');
                         $('#form_receta_producto').find('#id_receta').val(response.id_receta);
                         $('#myModal').find('#id_producto').val(response.item);
                         
                            //llenamos la tabla de recetas
                            $.ajax({
                                data:"id="+$('#id_producto').val()+"&accion=selDetalleReceta",
                                url:'nuevofiltro_producto.php',
                                type:'post',dataType: 'json',
                                success:function (response) {
                                   for(i=0;i<response.length;i++)
                                    {
                                       var fila=tabla_receta.row.add([
                                            response[i][2],
                                            response[i][10],
                                            response[i][3],
                                            response[i][4],
                                            response[i][5],
                                            response[i][9],
                                            response[i][6],
                                            response[i][7],
                                            response[i][8],
                                            '<img id="eliminar_receta" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-17-bin.png" data-toggle="tooltip" data-placement="left" title = "Eliminar Receta"/></td>'
                                        ]).draw(false).index();
                                        var row = tabla_receta.row(fila).node();
                                        
                                         //si estoy insertando una receta sin pack le doy a la fila el valor de la ultima filareceta
                                        if(response[i][10]=="")
                                            $(row).attr('id',id_filareceta);
                                        else
                                        {
                                            id_filareceta+=1;
                                            $(row).attr('id',id_filareceta);//sino inserto la ultima idreceta aunmentado en 1
                                        }
                                        $('#RecetaModal').find('#id_receta').val(response[i][1]);
                                    }

                                   //codigo que se ejecuta cuando se elimina un detalle de kla receta
                                $('#tabla_receta tbody').on('click', '#eliminar_receta',function(){
                                        //obtengo el id de la fila sobre la que estoy ejerciendo la accion
                                         id=$(this).parents('tr').attr('id');
                                        //si lo que estoy borrando es un subpack, elimino solo esa fila, sino elimino todas las filas cuyo id sea igual al suyo
                                        if($(this).parents('tr').children("td:eq('1')").html()=="")
                                          tabla_receta.row($(this).parents('tr')).remove().draw();
                                        else
                                          tabla_receta.row('#'+id).remove().draw();

                                        valordeclarado();
                                        tabla_receta.draw();
                                  });
                              },
                                error: function (response) {}
                            });
                           
                         $('#myModal').modal('show'); 
                    },
                    error: function (response){
                    }
                
            });
           });
         
        //codigo que se ejecuta al eliminar un elemento
         $('#tabla tbody').on('click','#eliminar', function () {
            accion="eliminar";
            id_fila=$(this).closest('tr').attr('id');
            $('#EliminarModal').find('#eliminar_id_producto').val(id_fila);
            $('#EliminarModal').modal('show');
        });
        
        //codigo que se ejecuta al dar si en el eliminar
        $('#elim_producto').on('click',function(){
            var parametros = {"id":$('#eliminar_id_producto').val(),"accion":accion};
    		$.ajax({
                    data:parametros,
                    url:'nuevofiltro_producto.php',
                    type:'post',
                    dataType: 'json',
                    success:function (response) {
                        $('#EliminarModal').modal('hide');  
                        $('#mensaje').html(response.message);
                        $('#mensaje').fadeIn('slow',function (){
                            setTimeout(function() {
                                $("#mensaje").fadeOut(1500);
                            },3000);
                        });
                        oTable.row($('tr#'+id_fila)).remove().draw( false );
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
           
        ///////////////////////VALIDACIONESSSS///////////////////////////
       jQuery.validator.addMethod("noSpace", function (value, element) { 
    return value.indexOf(" ") < 0 && value != ""; 
  }, "No space please and don't leave it empty");

        jQuery.validator.addMethod("alphanumeric", function (value, element) {
          return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
  }, "Enter a valid item.");

         jQuery.validator.addMethod("alphanumeric2", function (value, element) {
          return this.optional(element) || /^[a-zA-Z0-9\-\s\.\,\_\:\+\*\/]+$/.test(value);
  }, "Enter a valid item.");

        jQuery.validator.addMethod("barcode", function (value, element) {
    return this.optional(element) || /[a-z0-9 -()+]+$/.test(value);
  }, "Enter a valid bar code.");
  
        // Setup form validation on the #register-form element
        $("#form_producto").validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class",
            // Reglas de validacion
            rules: {
                item: {
                required: true,
                noSpace: true,
                maxlength: 10,
                number: true
              },
              desc:{required: true,
                alphanumeric2: true},
              servicio: "required",
              descgen: "required",
              finca: "required",
              descripcion: {
                required: true,
                alphanumeric2: true
              },
              origen: "required",
              packtype: "required",
              boxtype:"required" ,
              receta:"required" ,
              largo: {required:true,number:true},
              ancho: {required:true,number:true},
              alto:{required:true,number:true},
              Dclvalue:{required:true,number:true},
              peso:{required:true,number:true},
              Pack:{required:true,number:true}
            },
            // Mensajes de validacion
            messages: {
               item: {
                required: "Campo Requerido",
                maxlength: "10 caracteres como máximo",
                noSpace: "No puede dejar espacios en blanco",
                number: "Solamente números"
              },
              desc: {
                required: "Campo Requerido",
                alphanumeric2: "Caracter no válido"
              },
              receta:"Campo Requerido",
              servicio:"Campo Requerido",
              descgen:"Campo Requerido",
              finca:"Campo Requerido",
              descripcion: {
                required: "Campo Requerido",
                alphanumeric2: "Caracter no válido"
              },              
              origen:"Campo Requerido",
              packtype: "Campo Requerido",
              boxtype: "Campo Requerido",
              largo: {
                  required: "Campo Requerido",
                  number: "Caracter no válido"},
              ancho: {
                  required: "Campo Requerido",
                  number: "Caracter no válido"},
              alto:{
                  required: "Campo Requerido",
                  number: "Caracter no válido"},
              Dclvalue:{
                  required: "Campo Requerido",
                number: "Caracter no válido"},
              Pack:{
                  required: "Campo Requerido",
                  number: "Caracter no válido"},
              peso:{
                  required: "Campo Requerido",
                  number: "Caracter no válido"}
        },
           submitHandler: function(form) {
           if(accion=="nuevo")
           {
             var formData = new FormData(document.getElementById('form_producto'));
             formData.append('accion',accion);
             formData.append('Dclvalue',$('#RecetaModal').find('#Dclvalue').val());
            //recorro el formulario de la receta para obtener todos los campos
             array_receta=new Array();
             if(tabla_receta.data().count()!=0) //si el datatable ded la receta tiene valores
             {
                $("#tabla_receta tbody tr").each(function (index) 
                 {
                  array_detalles = new Array();//array con los valores de la receta
                  texto="";
                  $(this).children("td").each(function (index2) 
                   {

                       switch (index2)  
                       {
                           default: array_detalles.push($(this).text());
                                   break;
                           case 9: break;
                           
                       }

                   });
                    array_receta.push(array_detalles);
               });
             }
             else
              {
              $('#mensaje_producto').html("<span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span> Debe insertar la receta para este item.");
              $('#mensaje_producto').show('slow');
              return;
             }
            formData.append('datoReceta',JSON.stringify(array_receta)); 
                   
            $.ajax({
                data:formData,
                url:'nuevofiltro_producto.php',
                type:'post',
                processData: false,  // tell jQuery not to process the data
                contentType: false ,
                dataType: 'json',
                success:function (response) {
                        $('#myModal').modal('hide'); 
                        $(document).find('body').css('overflow', 'auto');
                        $('#mensaje').html(response.message);
                        $('#mensaje').fadeIn('slow',function (){
                            setTimeout(function() {
                                $("#mensaje").fadeOut(1500);
                            },3000);
                        });
                        if(response.success){
                         var fila=oTable.row.add([
                           '<strong>'+response.item+'</strong>',
                           '<strong>'+response.desc+'</strong>',
                           response.descgen,
                           response.receta,
                           response.origen,
                           response.finca,
                           response.servicio,
                           response.boxtype,
                           response.packtype,
                           response.pack,
                           response.Dclvalue,
                           response.largo,
                           response.ancho,
                           response.alto,
                           response.peso,
                           '<a href="../images/productos/'+response.id+'.jpg" data-lightbox="'+response.id+'"><img id="img" width="50px" heigth="50px" src="../images/productos/'+response.id+'.jpg" data-toggle="tooltip" data-placement="left"/></a>',
                           '<img id="editar" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-151-edit.png" data-toggle="tooltip" data-placement="left" title = "Modificar Producto"/></td>',
		           '<img id="eliminar" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-17-bin.png" data-toggle="tooltip" data-placement="left" title = "Eliminar Producto"/></td>'
                           
                           ]).draw(false).index();
                           var row = oTable.row(fila).node();
                           $(row).attr('id',response.id);
                       }
                   },
                    error: function (response) {
                        $('#myModal').modal('hide');
                        $(document).find('body').css('overflow', 'auto');
                        $('#mensaje').html(response.message);
                        $('#mensaje').fadeIn('slow',function (){
                            setTimeout(function() {
                                $("#mensaje").fadeOut(1500);
                            },3000);
                        });
                    }
                });
            }
            else if(accion=="editar")
            {
             var formData = new FormData(document.getElementById('form_producto'));
             formData.append('accion',accion);
             formData.append('Dclvalue',$('#RecetaModal').find('#Dclvalue').val());
             formData.append('id',$('#id_producto').val());
             formData.append('id_receta',$('#RecetaModal').find('#id_receta').val());
             
             //recorro el formulario de la receta para obtener todos los campos
             array_receta=new Array();
             if(tabla_receta.data().count()!=0) //si el datatable ded la receta tiene valores
             {
              $("#tabla_receta tbody tr").each(function (index) 
              {
               array_detalles = new Array();//array con los valores de la receta
               $(this).children("td").each(function (index2) 
                {
                    switch (index2)  
                    {
                        default: array_detalles.push($(this).text());
                                break;
                        case 9: break;
                    
                    }
                    
                });
                array_receta.push(array_detalles);
              });
            }
            else
            {
              $('#mensaje_producto').html("<span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span> Debe insertar la receta para este item.");
              $('#mensaje_producto').show('slow');
              return;
            }
            formData.append('datoReceta',JSON.stringify(array_receta));
            $.ajax({
                data:formData,
                url: 'nuevofiltro_producto.php',
                type:'post',
                dataType: 'json',
                processData: false,  // tell jQuery not to process the data
                contentType: false ,
                success:function (response) {
                        $('#myModal').modal('hide');
                        $(document).find('body').css('overflow', 'auto');
                        $('#mensaje').html(response.message);
                        $('#mensaje').fadeIn('slow',function (){
                            setTimeout(function() {
                                $("#mensaje").fadeOut(1500);
                            },3000);
                        });
                          
                      if(response.success){   
                         var cell = oTable.cell($('tr#'+id_fila+' td:eq(0)'));
                             cell.data('<strong>' +response.item+'</strong>').draw();
                         var cell1 = oTable.cell($('tr#'+id_fila+' td:eq(1)'));
                             cell1.data('<strong>' +response.desc+'<strong>').draw();
                         var cell2 = oTable.cell($('tr#'+id_fila+' td:eq(2)'));
                             cell2.data(response.descgen).draw();
                         var cell3 = oTable.cell($('tr#'+id_fila+' td:eq(3)'));
                             cell3.data(response.receta).draw();    
                         var cell4 = oTable.cell($('tr#'+id_fila+' td:eq(4)'));
                             cell4.data(response.origen).draw();
                         var cell5 = oTable.cell($('tr#'+id_fila+' td:eq(5)'));
                             cell5.data(response.finca).draw();
                         var cell6 = oTable.cell($('tr#'+id_fila+' td:eq(6)'));
                             cell6.data(response.servicio).draw();
                         var cell7 = oTable.cell($('tr#'+id_fila+' td:eq(7)'));
                             cell7.data(response.boxtype).draw();
                         var cell8 = oTable.cell($('tr#'+id_fila+' td:eq(8)'));
                             cell8.data(response.packtype).draw();
                         var cell9 = oTable.cell($('tr#'+id_fila+' td:eq(9)'));
                             cell9.data(response.pack).draw();
                         var cell10 = oTable.cell($('tr#'+id_fila+' td:eq(10)'));
                             cell10.data(response.Dclvalue).draw();
                         var cell11 = oTable.cell($('tr#'+id_fila+' td:eq(11)'));
                             cell11.data(response.largo).draw();
                         var cell12 = oTable.cell($('tr#'+id_fila+' td:eq(12)'));
                             cell12.data(response.ancho).draw();
                         var cell13 = oTable.cell($('tr#'+id_fila+' td:eq(13)'));
                             cell13.data(response.alto).draw();
                         var cell14 = oTable.cell($('tr#'+id_fila+' td:eq(14)'));
                             cell14.data(response.peso).draw();
                         var cell15 = oTable.cell($('tr#'+id_fila+' td:eq(15)'));
                            $('tr#'+id_fila+' td:eq(15)').html('');
                            cell15.data('<a href="../images/productos/'+response.imagen+'.jpg" data-lightbox="'+response.imagen+'"><img id="img" width="50px" heigth="50px" src="../images/productos/'+response.imagen+'.jpg" data-toggle="tooltip" data-placement="left"/></a>').draw();
                         }
                   },
                    error: function (response) {
                        $('#myModal').modal('hide');
                        $(document).find('body').css('overflow', 'auto');
                        $('#mensaje').html(response.message);
                        $('#mensaje').fadeIn('slow',function (){
                            setTimeout(function() {
                                $("#mensaje").fadeOut(1500);
                            },3000);
                        });
                    }
                });
            }
        }
      });
   
      //seleccion del combo de boxtype
      $('#boxtype').change(function(){
           $.ajax({
                data:"id_Box="+$('#boxtype').val()+"&accion=selBox",
                url:'nuevofiltro_producto.php',
                type:'post',
                dataType: 'json',
                success:function (response) {
                        $('#myModal').find('#alto').val(response.alto_Box);
                        $('#myModal').find('#largo').val(response.longitud_Box);
                        $('#myModal').find('#ancho').val(response.ancho_Box);
                },
                error: function (response) {}
            });
        });
                        
       //codigo para abrir ventana de recetas
      $('#btn_receta').on('click',function(){
        $('#myModal').modal('hide');
        $(document).find('body').css('overflow', 'hidden');
        $('#RecetaModal').modal('show');
      });
      
      $('#cancelar,.close').on('click', function () {
         $(document).find('body').css('overflow', 'auto');
      });
 
        
        //codigo para la tabla de recetas
        var tabla_receta = $('#tabla_receta').DataTable({
           "scrollX": true,
           "scrollY": "260px",
           responsive: true,
           "bSort": false,
                "language": {
                    "lengthMenu": "Mostrando _MENU_ filas por pág.",
                    "zeroRecords": "No se encontraron elementos",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No se encontraron elementos",
                    "infoFiltered": "(filtrado de un total _MAX_)",
                    "sSearch":         "Buscar:"
                }
         });
       
       function mayorquecero(value, element, param) {
            //value es el valor actual del elemento que se está validando
            //element es el elemento DOM que se está validando
            //param son los parámetros especificados por el método
            if(value!="0.0000") {
                return true; //supera la validación
            }
            else{
                return false; //error de validación
            }
        }
       $.validator.addMethod("mayorquecero", mayorquecero, "El total tiene que ser >0");
       
        //codigo para el boton de insertar receta
        $("#form_receta_producto").validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class",
            // Reglas de validacion
            rules: {
                cantidad: {
                required: true,
                noSpace: true,
                number: true
              },
                lista_productos:"required",
                long_tallo: "required",
                hts: "required",
                pack_receta: {
                    number:true
                   
                },
                color: "required",
                stem: "required",
                total: {
                    required:true,
                    mayorquecero:true
                }
            },
            // Mensajes de validacion
            messages: {
               cantidad: {
                required: "Campo Requerido",
                noSpace: "No puede dejar espacios en blanco",
                number: "Solamente números"
              },
              lista_productos:"Campo Requerido",
               pack_receta:
               {
                   number:"Solamente números"
                  
               },
              long_tallo: "Campo Requerido",
              hts:"Campo Requerido",
              color: "Campo Requerido",
              stem: "Campo Requerido",
              total: {
                  required: "Campo Requerido",
                  mayorquecero:"Tiene que ser > 0"
              }
        },
           submitHandler: function(form) {
              //si ocurre esto es que estoy insertando el primer producto de la receta y este no puede tener el pack con valor cero 
              if(tabla_receta.data().count()==0  && $('#pack_receta').val()=="")
              {
                  $('#mensaje_receta').html("Debe ingresar un Pack en el primer producto de la receta");
                        $('#mensaje_receta').fadeIn('slow',function (){
                            setTimeout(function() {
                                $("#mensaje_receta").fadeOut(1500);
                            },3000);
                        });
                        return;
              }
              var fila=tabla_receta.row.add([
                    $('#cantidad').val(),
                    $('#pack_receta').val(),
                    $('#lista_productos option:selected').text(),
                    $('#long_tallo option:selected').text(),
                    $('#hts').val(),
                    $('#nandina').val(),
                    $('#color option:selected').text(),
                    $('#stem').val(),         
                    $('#total').val(), 
                    '<img id="eliminar_receta" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-17-bin.png" data-toggle="tooltip" data-placement="left" title = "Eliminar Receta"/>'
                    ]).draw(false).index();
                    var row = tabla_receta.row(fila).node();
                    
                    //si estoy insertando una receta sin pack le doy a la fila el valor de la ultima filareceta
                    if($('#pack_receta').val()=="")
                        $(row).attr('id',id_filareceta);
                    else
                    {
                        id_filareceta+=1;
                        $(row).attr('id',id_filareceta);//sino inserto la ultima idreceta aunmentado en 1
                    }
                    
                    $('#form_receta_producto').clearForm();
                    //codigo que se ejecuta cuando se elimina un detalle de kla receta
                    $('#tabla_receta tbody').on('click', '#eliminar_receta',function(){
                           //obtengo el id de la fila sobre la que estoy ejerciendo la accion
                            id=$(this).parents('tr').attr('id');
                           //si lo que estoy borrando es un subpack, elimino solo esa fila, sino elimino todas las filas cuyo id sea igual al suyo
                           if($(this).parents('tr').children("td:eq('1')").html()=="")
                             tabla_receta.row($(this).parents('tr')).remove().draw();
                           else
                             tabla_receta.row('#'+id).remove().draw();
                         
                           valordeclarado();
                           tabla_receta.draw();
                     });
                     
                     //calculo el valor declarado
                     valordeclarado();
                }
      });
              
          //codigo para el seleccionar lista de productos en recetas
          //seleccion del combo de boxtype
          $('#lista_productos').change(function(){
            $.ajax({
                data:"id="+$('#lista_productos').val()+"&accion=selListaProd",
                url:'nuevofiltro_producto.php',
                type:'post',
                dataType: 'json',
                success:function (response) {
                    $('#RecetaModal').find('#hts').val(response.HTS);
                    $('#RecetaModal').find('#nandina').val(response.nandina);
                    $('#RecetaModal').find('#stem').val(response.costo_Decl_Stem);
                },
                error: function (response) {}
                });
           });
         
          //codigo que calcula el costo total
          $('#total').on('click',function(){
              $val1=$('#cantidad').val();
              $val2=$('#stem').val();
              $('#total').val(($val1*$val2).toFixed(4));//calculo el total
          });
          
          //codigo que se ejecuta en el boton guardar de la receta
          $('#guardar_receta_producto').on('click',function(){
              valordeclarado();
               $('#RecetaModal').modal('hide');
               $('#myModal').modal('show');
          });    
          
          //codigo que se ejecuta al cambiar el pack
          $('#myModal').find('#Pack').on('change',function(){
              valordeclarado();
          });
 });
 
 function valordeclarado()
 {
    var valor_decl=0;
    var pack='';
    var pack_anterior='';
    var valor_total=0;
    var sumPack=0;
    
    $("#tabla_receta tbody tr").each(function (index) 
    {                             
        $(this).children("td").each(function (index2) 
         {
            switch (index2)  
             {
                 case 1: {
                         pack=$(this).html();
                         if(pack=='')
                         {
                           pack=pack_anterior;   
                         }
                         else
                         {
                          pack_anterior=parseFloat(pack);   
                          sumPack+=pack_anterior;
                          $('#myModal').find('#Pack').val(sumPack);
                         }
                         
                         break;
                     }
                 case 8: 
                 {
                     valor_total=parseFloat($(this).text());
                     valor_decl+=(pack*valor_total);
                     break;
                 }
                 default: break;
             }
             
        });
    });
    $('#RecetaModal').find('#Dclvalue').val(valor_decl.toFixed(2));
}

 function buscar() {
        var textoBusqueda = $("#item").val();
         if (textoBusqueda != "") {
            $.ajax({
                data: "accion=selItem&id="+textoBusqueda,
                url: 'nuevofiltro_producto.php',
                type:'post',
                dataType: 'json',
                success: function (response) {
                        if(response==textoBusqueda)
                        {
                            $('#mensaje_producto').html("<span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span>Ya existe un item registrado con ese código");
                            $('#mensaje_producto').show('slow');
                            $('#guardar').attr('disabled','disabled');
                        }
                        else
                        {
                            $('#mensaje_producto').hide('slow');
                            $('#guardar').removeAttr('disabled');  
                        }
                },
                error: function (response) {}
            });
        } 
    };
 
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
        <?php
            if($rol !=4){?>
                <a class="navbar-brand" href="../main.php?panel=mainpanel.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
            <?php }else{ ?>
                <a class="navbar-brand" href="./mainroom.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
            <?php } ?>
        </div>
 
  <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
 <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
               <?php
			  if($rol == 3 || $rol == 8){?>
			  	<li><a href="../php/subirTracking1.php"><strong>Cargar Tracking</strong></a></li>
			  <?php }?> 
 
			  <?php 
              if($rol<= 2){ ?>
                   <li class="dropdown">
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
                
                if($rol!=4){
            ?>
              <li class="dropdown">
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
                        <li class="divider"></li>
                        <li><a href="filtros.php?accion=buscarPO"><strong>Buscar PO</strong></a>
                    <?php
                        }
                     
                       if($rol== 3) { ?>
                        <li><a href="filtros_fincas.php?accion=buscarPO"><strong>Buscar PO</strong></a>
                      <?php } ?>
                </ul>
              </li>
              
              <?php
                }
                
                if($rol<= 2 || $rol == 3){?>
                    <li  class="active">
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
                      <li><a href="administration.php">
                             <strong>EDI</strong>
                      </a> 

                  <!--  <li class="dropdown">
                         <a tabindex="0" data-toggle="dropdown">
                             <strong>Contabilidad</strong><span class="caret"></span>
                         </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="administration.php"><strong>Contabilidad</strong></a></li>                      
                            <li class="divider"></li>         
                            <li><a href="contabilidad.php"><strong>Contabilidad ECU</strong></a></li>
                       </ul>  -->
                    </li>	
                 <?php
				 }
				 ?>
                 
                 <?php if($rol == 1){  ?>
                        <li><a href="usuarios.php"><strong>Usuarios</strong></a></li>
                    <?php }else if($rol != 4){
                            $sql   = "SELECT id_usuario from tblusuario where cpuser='".$user."'";
                            $query = mysqli_query($link,$sql);
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
<div id="mensaje" style="display: none;"></div>
<div class="row">
    <div class="col-md-2">
        <?php if($rol != 4){  ?>
            <button type="button" class="btn btn-primary" id="nuevo" data-toggle="tooltip" data-placement="rigth" title = "Crear nuevo Producto">
                <span class="glyphicon glyphicon-plus-sign black"></span>
            </button>
        <?php } ?>
      </div>
    <div class="col-md-8">
    	<h3><strong>Listado de Productos</strong></h3>
    </div>
    
    <div class="col-md-1">
        <form class="form-horizontal" id="form_nuevoventa" method="post" action="crearreportExcel.php">  
           <input type="image" style="cursor:pointer" id="imprimir"  name="imprimir"class= "imprimir" src="../images/excel.png" heigth="40" value="" data-toggle="tooltip" data-placement="left" title = "Exportar Reporte Excel" width="30"/>
           
          <?php 
                 $_SESSION["consulta"]="SELECT
                                        tblproductos.id_item,
                                        tblproductos.prod_descripcion,
                                        tblproductos.gen_desc,
                                        tblproductos.receta,
                                        tblproductos.origen,
                                        tblproductos.finca,
                                        tblproductos.cpservicio,
                                        tblboxtype.nombre_Box,
                                        tblproductos.cptipo_pack,
                                        tblproductos.pack,
                                        tblproductos.dclvalue,
                                        tblproductos.length,
                                        tblproductos.width,
                                        tblproductos.heigth,
                                        tblproductos.wheigthKg
                                        FROM
                                        tblproductos
                                        INNER JOIN tblboxtype ON tblproductos.boxtype = tblboxtype.id_Box
                                        ORDER BY id_item ASC";
                $_SESSION["titulo"]="Listado de Prroductos";
                //$titulo = "REPORTE DE PRUEBA";
                $_SESSION["columnas"]=array('Item','Nombre del Producto','Desc. Gen.','Receta Descriptiva','Origen','Finca','Servicio','Tipo Box','Tipo Pack','Pack','Valor Dcl','Largo','Ancho','Alto','Peso');
                $_SESSION["nombre_fichero"]="Listado de Productos.xls";
            ?>
        
        
        
        
        </form>
    
    </div>
</div>
   
<div class="box">
<table id="tabla" border="0" class="responsive nowrap stripe row-border order-column" cellspacing="0" width="100%">
<thead>
   <tr>  
        <th class="all">Item</th>
        <th class="all">Nombre del Producto</th>
        <th class="all">Desc. Gen.</th>
        <th class="all">Receta Descriptiva</th>
        <th class="all">Origen</th>
        <th class="all">Finca</th>
        <th class="all">Servicio</th>
        <th class="all">Tipo Box</th>
        <th class="all">Tipo Pack</th>
        <th class="all">Pack</th>
        <th class="all">Valor Dcl.</th>
        <th class="all">Largo</th>
        <th class="all">Ancho</th>
        <th class="all">Alto</th>
        <th class="all">Peso Kg</th>
        <th class="all">Imagen</th>
        <?php if($rol != 4){ ?>
            <th class="all">Editar</th>
            <th class="all">Eliminar</th>
        <?php  } ?>
       
   </tr>
  </thead>
        <tbody>
  <?php
 //Leer todas las fincas existentes para modificarlas o crear nuevas
  if($rol == 3){
  	$sql = "SELECT *, tblboxtype.id_Box,tblboxtype.nombre_Box
                  FROM tblboxtype INNER JOIN tblproductos ON tblboxtype.id_Box = tblproductos.boxtype 
                  WHERE finca = '".$finca."' order by id_item";
  }else{
	  $sql = "SELECT *, tblboxtype.id_Box,tblboxtype.nombre_Box
                  FROM tblboxtype INNER JOIN tblproductos ON tblboxtype.id_Box = tblproductos.boxtype
                  order by id_item";
  }
  $val = mysqli_query($link,$sql);
   if(!$val){
      echo "<tr><td>".mysqli_error()."</td></tr>";
   }else{
	   $cant = 0;
	   while($row = mysqli_fetch_array($val)){
		   $cant ++;
			echo "<tr id=\"".$row['item']. "\">";
			echo "<td><strong>".$row['id_item']."</strong></td>";
                        echo "<td><strong>".$row['prod_descripcion']."</strong></td>";
                        echo "<td>".$row['gen_desc']."</td>";
                        echo "<td>".$row['receta']."</td>";
                        echo "<td>".$row['origen']."</td>";
			echo "<td>".$row['finca']."</td>";
			echo "<td align='center'>".$row['cpservicio']."</td>";
                        echo "<td align='center'>".$row['nombre_Box']."</td>";
                        echo "<td align='center'>".$row['cptipo_pack']."</td>";
                        echo "<td align='center'>".$row['pack']."</td>";
                        echo "<td align='center'>".$row['dclvalue']."</td>";
			echo "<td align='center'>".$row['length']."</td>";
			echo "<td align='center'>".$row['width']."</td>";
			echo "<td align='center'>".$row['heigth']."</td>";
			echo "<td align='center'>".$row['wheigthKg']."</td>";
			
                        if(file_exists("../images/productos/".$row['item'].".jpg")){
                          echo '<td><a href="../images/productos/'.$row['item'].'.jpg" data-lightbox="'.$row['item'].'"><img id="img" width="50px" heigth="50px" src="../images/productos/'.$row['item'].'.jpg" data-toggle="tooltip" data-placement="left"/></a></td>';
			}
                        else{
                            echo '<td></td>';
                        }  
                        if($rol != 4){ 
                            echo '<td><img id="editar" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-151-edit.png" data-toggle="tooltip" data-placement="left" title = "Modificar Producto"/></td>';
                            echo '<td ><img id="eliminar" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-17-bin.png" data-toggle="tooltip" data-placement="left" title = "Eliminar Producto"/></td>';
		        
                        }
                        
                        
                        
                        echo "</tr>";
                        
	 }
	 									
   }
  ?>
  </tbody>   
  </table>
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
    <a href="#top" class="well well-sm"  onclick="$('html,body').animate({scrollTop:0},'slow');return false;">
        <i class="glyphicon glyphicon-chevron-up"></i> Ir arriba
    </a>
</span><!-- /top-link-block --> 
</div> <!-- /container -->
 </div>
 
    
<!--Ventana modal para crear nuevo boxtype-->
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Nuevo Producto</h4>
      </div>
      <div class="modal-body">
       <form id="form_producto" method="post" enctype="multipart/form-data">
        <div class="row">
            
        <div class="col-md-4">
            <div id="preview-imagen" style="display: none;padding: 5px;"></div>
            <input type="file" id="imagen" name="archivo"/>
	</div>
        <div class="col-md-8">
          <div class="row">
            <div class="col-md-6">
              <label for="item" class="control-label">Item:</label>
              <input type="text" class="form-control" name="item" id="item" maxlength="10" placeholder="10 caracteres máximo" onchange="buscar();" onKeyUp="buscar();"/>
            </div>
             <div class="col-md-6">
                 <label for="desc" class="control-label">Nombre del Producto:</label>
                 <input type="text" class="form-control" maxlength="100" name="desc" id="desc"/>
             </div>  
          </div>  
          <div class="row">
              <div class="col-md-6">
                <div class="">
                     <label for="descgen" class="control-label">Descripción General:</label>
                     <input type="text" class="form-control" maxlength="100" name="descgen" id="descgen"/>
                </div>
                <div class="">
                     <label for="origen" class="control-label">Origen:</label>
                     <select name="origen" id="origen" class="form-control">
                        <?php 
                          $sql1   = "SELECT * FROM tblciudad_origen";
                          $query1 = mysqli_query($link,$sql1);
                            //Recorrer las ciudades para mostrar
                          echo '<option value="" selected="selected"></option>'; 
                          while($row2 = mysqli_fetch_array($query1)){
                                echo '<option value="'.$row2["codciudad"].'-'.$row2["pais_origen"].'">'.$row2["codciudad"].'-'.$row2["pais_origen"].'</option>'; 
                          }
                        ?>
                        </select>
                 </div> 
              </div>
           <div class="col-md-6">
                 <label for="receta" class="control-label">Receta Descriptiva:</label>
                 <textarea class="form-control" maxlength="100" id="receta" name="receta" rows="4"></textarea>
            </div>
          </div>
          <div class="row">
              <div class="col-md-6">
                 <label for="finca" class="control-label">Finca:</label>
                 <select type="text" id="finca" name="finca" class="form-control">
                    <?php
                        //Leyendo las fincas
                        $sqlFinca   = "SELECT * FROM tblfinca order by nombre";
                        $queryFinca = mysqli_query($link,$sqlFinca) or die ("Error seleccionando las fincas");
                        echo '<option value="" selected="selected"></option>';
                        while ($filaFinca = mysqli_fetch_array($queryFinca)){
                                echo '<option value="'.$filaFinca['nombre'].'">'.$filaFinca['nombre'].'</option>';
                        }
                   ?>
                </select>
             </div>
              <div class="col-md-6">
                 <label for="servicio" class="control-label">Servicio:</label>
                 <input type="text" class="form-control" name="servicio" id="servicio"/>
             </div>
         </div>
          <div class="row"> 
            <div class="col-md-6">
                  <label for="Pack" class="control-label">Pack:</label>
                  <input type="text" readonly="readonly" class="form-control" name="Pack" id="Pack"/>
            </div>
            <div class="col-md-6">
                 <label for="packtype" class="control-label">Pack Type:</label>
                 <input type="text" class="form-control" name="packtype" id="packtype" value="CP"/>
            </div>
          </div>
          <div class="row">
           <div class="col-md-6">
                 <label for="boxtype" class="control-label">Boxtype:</label>
                  <select id="boxtype" name="boxtype" class="form-control">
                    <?php
                        //Leyendo las fincas
                        $sqlBox   = "SELECT * FROM tblboxtype";
                        $queryBox = mysqli_query($link,$sqlBox) or die ("Error seleccionando las fincas");
                        echo '<option selected="selected" value=""></option>'; 
                        while ($filaBox = mysqli_fetch_array($queryBox)){
                                echo '<option value="'.$filaBox['id_Box'].'">'.$filaBox['nombre_Box'].'</option>';
                                
                        }
                   ?>
                </select>
                 
             </div>
          </div>
          <div class="row">
            <div class="col-md-3">
                 <label for="largo" class="control-label">Largo:</label>
                 <input type="text" class="form-control" name="largo" id="largo"/>
            </div>
            <div class="col-md-3">
              <label for="ancho" class="control-label">Ancho:</label>
              <input type="text" class="form-control" name="ancho" id="ancho"/>
           </div>
           <div class="col-md-3">
                 <label for="alto" class="control-label">Alto:</label>
                 <input type="text" class="form-control" name="alto" id="alto"/>
           </div>
           <div class="col-md-3">
                 <label for="peso" class="control-label">Peso (Kg):</label>
                 <input type="text" class="form-control" name="peso" id="peso"/>
           </div>
         </div>
          
            <div class="row" style="margin-top: 5px; margin-bottom: 5px;">
             <div class="col-md-4">
                 <button type="button" class="btn btn-primary" id="btn_receta" aria-pressed="false" autocomplete="off">
                    Agregar Receta
                 </button>
             </div>
         </div>
         <input type="hidden" name="id_producto" id="id_producto" />
         
       </div>
        </div>   
      <div class="modal-footer">
        <div class="alert alert-danger" role="alert" id="mensaje_producto" style="display: none;float: left;"> </div>
        <button type="button" id="cancelar" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" id="guardar" disabled="disabled" class="btn btn-primary">Guardar</button>
      </div>
     </form>
    </div>
  </div>
</div>
</div>

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
            <label for="" class="control-label">Está Seguro que desea eliminar este Producto</label>
          </div>
          <input type="hidden" name="eliminar_id_producto" id="eliminar_id_producto" />
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button type="button" id="elim_producto" onclick="" class="btn btn-primary">Si</button>
      </div>
    </div>
  </div>
</div>   

<div class="modal fade bs-example-modal-lg" id="RecetaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Crear Receta del Producto</h4>
      </div>
      <div class="modal-body">
          <form id="form_receta_producto" name="form_receta_producto" method="post">
            <div class="row">
            <div class="col-md-4">
                
                   <div class="row">
                   <div class="col-md-6">
                       <label for="cantidad" class="control-label">Cantidad:</label>
                       <input type="text" class="form-control" name="cantidad" id="cantidad"/>
                   </div>
                   <div class="col-md-6">
                       <label for="pack_receta" class="control-label">Pack:</label>
                       <input type="text" class="form-control" name="pack_receta" id="pack_receta"/>
                   </div>
                   </div>
                   <div >
                       <label for="lista_productos" class="control-label">Lista de Productos:</label>
                       <select id="lista_productos" name="lista_productos" class="form-control">
                       <?php
                          //Leyendo las fincas
                          $sqllistaprod   = "SELECT * FROM tbllistaproducto";
                          $querylistaprod = mysqli_query($link,$sqllistaprod) or die ("Error seleccionando la lista de productos");
                          echo '<option selected="selected" value=""></option>'; 
                          while ($fila = mysqli_fetch_array($querylistaprod)){
                                  echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';

                          }

                      ?>
                      </select>
                   </div>
                   <div>
                       <label for="long_tallo" class="control-label">Longitud:</label>
                       <select id="long_tallo" name="long_tallo" class="form-control">
                       <?php
                          //Leyendo las fincas
                          $sqlmediatallos   = "SELECT * FROM tblmediatallos";
                          $querymediatallos = mysqli_query($link,$sqlmediatallos) or die ("Error seleccionando la lista de Medidas de Tallos");
                          echo '<option selected="selected" value=""></option>'; 
                          while ($fila = mysqli_fetch_array($querymediatallos)){
                                  echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';

                          }

                      ?>
                      </select>
                   </div>
                <div class="row">
                   <div class="col-md-6">
                       <label for="hts" class="control-label">HTS:</label>
                       <input type="text" readonly="readonly" class="form-control" name="hts" id="hts"/>
                   </div>
                
                   <div class="col-md-6">
                       <label for="nandina" class="control-label">Nandina:</label>
                       <input type="text" readonly="readonly" class="form-control" name="nandina" id="nandina"/>
                   </div>
                </div>
                   <div>
                       <label for="color" class="control-label">Color:</label>
                       <select id="color" name="color" class="form-control">
                       <?php
                          //Leyendo las fincas
                          $sqlhts  = "SELECT * FROM tblcolores";
                          $queryhts = mysqli_query($link,$sqlhts) or die ("Error seleccionando la lista de colores");
                          echo '<option selected="selected" value=""></option>'; 
                          while ($fila = mysqli_fetch_array($queryhts)){
                                  echo '<option value="'.$fila['id'].'">'.$fila['color'].'</option>';

                          }
                          mysqli_close($link);
                      ?>
                      </select>
                   </div>
                   <div>
                     <label for="stem" class="control-label">Stem/Value declared:</label>
                     <input type="text" class="form-control" readonly="readonly" name="stem" id="stem"/>
                   </div>
                <div class="row">
                   <div class="col-md-6">
                     <label for="total" class="control-label">Valor total:</label>
                     <input type="text" class="form-control" name="total" id="total"/>
                   </div>
                   <div class="col-md-6">
                        <label for="Dclvalue" class="control-label">Dclvalue:</label>
                        <input type="text" class="form-control" readonly="readonly" name="Dclvalue" id="Dclvalue"/>
                   </div>
                </div>
                <div class="row">
                <div class="co-md-5" style="float:right;margin: 6px;">
                   <button type="submit" id="btninsertar" class="btn btn-primary">Insertar</button>
                </div>
                </div>
               <input type="hidden" name="id_receta" id="id_receta" />
              
            </div>
            <div class="col-md-8">
                
                <table id="tabla_receta" border="0" class="responsive nowrap stripe row-border order-column" cellspacing="0" width="100%">
                <thead>
                   <tr>  
                        <th class="all">Cantidad</th>
                        <th class="all">Pack</th>
                        <th class="all">Lista de Producto</th>
                        <th class="all">Longitud</th>
                        <th class="all">HTS</th>
                        <th class="all">Nandina</th>
                        <th class="all">Color</th>
                        <th class="all">Stem/Value Declarado</th>
                        <th class="all">Valor Total</th>
                        <th class="all">Eliminar</th>
                    </tr>
                  </thead>
                  <tbody>
                      
                  </tbody>
                </table>
               </div>
           </div>
          </form>
      <div class="modal-footer">
        <div class="alert alert-danger" role="alert" id="mensaje_receta" style="display: none;float: left;"> </div>
        <button type="button" id="guardar_receta_producto" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" language="javascript" src="../js/lightbox.js"></script>
</body>
</html>

