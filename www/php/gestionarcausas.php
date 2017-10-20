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
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registro de Causas de Rechazo y Quejas</title>
<script type="text/javascript" src="../js/script.js"></script>
<script language="javascript" src="../js/imprimir.js"></script>
<link rel="icon" type="image/png" href="../images/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
<link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/dataTables.jqueryui.min.css">

<script language="javascript" src="../js/imprimir.js"></script>
<script type="text/javascript" src="../js/script.js"></script>
<script src="../bootstrap/js/jquery.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
<script src="../bootstrap/js/bootstrap-submenu.js"></script>
<script src="../bootstrap/js/bootstrap-modal.js"></script>
<script src="../bootstrap/js/docs.js" defer></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.10/js/dataTables.jqueryui.min.js"></script> 
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.0.0/js/dataTables.responsive.min.js"></script> 
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.0.0/js/responsive.bootstrap.min.js"></script> 
  
  
  <style>
  .contenedor {
       margin:0 auto;
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
  .my-error-class {
     color:red;
     font-style: italic;
     font-size: 12px;
   }
  li a{
      	cursor:pointer;/*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
      }  
  /*End added by me */

  </style>
  
  <script type="text/javascript">
     $(document).ready(function ($) {
        var accion="nuevo"; //Accion que se ejecutara
        var id_fila=""; //id de la fila sobre la cual esta sucediendo la accion
        
        //Adiconar datatable
        var oTable = $('#tabla').DataTable({
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
       
        //codigo que se ejecuta al pulsar el boton de nuevo
        $('#nuevo').on('click', function(){
          accion="nuevo";
          arreglar_ventana();
          $('#form_causas').each (function(){
            this.reset();
          });
          $('#myModal').modal('show');
        });
        
       //codigo que se ejecuta al editar un nuevo elemento
        $('#tabla tbody').on( 'click', '#editar', function () {
            accion="editar";
            arreglar_ventana();
            id_fila=$(this).closest('tr').attr('id');
                
            $(this).parents('tr').each(function (index) 
            {
              $(this).children("td").each(function (index2) 
              {
                switch (index2) 
                {
                    case 0: $('#myModal').find('#causa').val($(this).text());
                            break;
                    case 1: $('#myModal').find('#detalle').val($(this).text());
                            break;
                    
                }
                
            });
        });
            $('#myModal').find('#id_causa').val(id_fila);
            $('#myModal').modal('show');
       });
        
        //codigo que se ejecuta al eliminar un elemento
        $('#tabla tbody').on( 'click', '#eliminar', function () {
            accion="eliminar";
            arreglar_ventana1();
            id_fila=$(this).closest('tr').attr('id');
            valor=$(this).parents('tr').children('td:eq(0)').html();
            $('#EliminarModal').find('#eliminar_id_causa').val(id_fila)
            $('#EliminarModal').modal('show');
        });
        //codigo que se ejecuta al dar si en el eliminar
        $('#elim_causa').on('click', function () {
            
             var parametros = {
                "id":$('#eliminar_id_causa').val(),
                "accion":accion
              };
              $.ajax({
                    data:  parametros,
                    url:   'nuevofiltro_causa.php',
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
    
        // Setup form validation on the #register-form element
        $("#form_causa").validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class",
    
            // Specify the validation rules
            rules: {
                  causa: {required:true},
                  detalle: {required:true}
             },
        
        // Specify the validation error messages
        messages: {
              causa: "Campo requerido",
              detalle: "Campo requerido"
              
        },
        submitHandler: function(form) {
           if(accion=="nuevo")
           {
             var parametros = {
                "causa" : $('#causa').val(),
                "detalle" : $('#detalle').val(),
                "accion":accion
             };
             $.ajax({
                    data:  parametros,
                    url:   'nuevofiltro_causa.php',
                    type:  'post',
                    dataType: 'json',
                    success:  function (response) {
                        $('#myModal').modal('hide');  
                        $('#mensaje').html(response.message);
                        $('#mensaje').fadeIn('slow',function (){
                            setTimeout(function() {
                                $("#mensaje").fadeOut(1500);
                            },3000);
                        });
                           // location.reload();
                           var fila=oTable.row.add([
                           response.causa,
                           response.detalle,
                           '<img id="editar" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-151-edit.png" data-toggle="tooltip" data-placement="left" title = "Modificar Causa"/></td>',
		           '<img id="eliminar" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-17-bin.png" data-toggle="tooltip" data-placement="left" title = "Eliminar Causa"/></td>'
                           ]).draw(false).index();
                           var row = oTable.row(fila).node();
                           $(row).attr('id',response.id);
                   },
                    error: function (response) {
                        $('#myModal').modal('hide');  
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
              var parametros = {
                "causa" : $('#causa').val(),
                "detalle" : $('#detalle').val(),
                "id":$('#id_causa').val(),
                "accion":accion
             };
              $.ajax({
                    data:  parametros,
                    url:   'nuevofiltro_causa.php',
                    type:  'post',
                    dataType: 'json',
                    success:  function (response) {
                        $('#myModal').modal('hide');  
                        $('#mensaje').html(response.message);
                        $('#mensaje').fadeIn('slow',function (){
                            setTimeout(function() {
                                $("#mensaje").fadeOut(1500);
                            },3000);
                        });
                         
                         var cell = oTable.cell($('tr#'+id_fila+' td:eq(0)'));
                             cell.data(response.causa).draw();
                         var cell1 = oTable.cell($('tr#'+id_fila+' td:eq(1)'));
                             cell1.data(response.detalle).draw();
                         
                         
                   },
                    error: function (response) {
                        $('#myModal').modal('hide');  
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
   
 
 });
   
  function arreglar_ventana()
  {
            var winSize = {
              wheight : $(window).height(),
              wwidth : $(window).width()
            };
            var modSize = {
              mheight : $('#myModal').height(),
              mwidth : $('#myModal').width()
            };
          $('#myModal').css({
            'padding-top' :  ((winSize.wheight - (modSize.mheight/2))/2)
          });
          $('#myModal .modal-header').css({'background-color' : '#3B5998','color' : 'white',
                                            'border-radius' : '5px 5px 0 0'}); 
        }
        
  function arreglar_ventana1()
  {
            var winSize = {
              wheight : $(window).height(),
              wwidth : $(window).width()
            };
            var modSize = {
              mheight : $('#EliminarModal').height(),
              mwidth : $('#EliminarModal').width()
            };
          $('#EliminarModal').css({
            'padding-top' :  ((winSize.wheight - (modSize.mheight/2))/2)
          });
          $('#EliminarModal .modal-header').css({'background-color' : '#3B5998','color' : 'white',
                                            'border-radius' : '5px 5px 0 0'}); 
        }      
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
 
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
    	<li><a href="cust_services.php"><strong>Atención al Cliente</strong></a></li>
        <li><a href="report_cust.php"><strong>Reportes</strong></a></li>
        <li><a href="gestionarcausas.php"><strong>Gestionar Causas</strong></a></li>
		<?php
		if($rol == 6)
                     echo '<li><a href="filtros.php"><strong>Ver Órdenes</strong></a></li>';
                     if($rol == 1){  
			     	 //no muestra nada
					 }else{
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
  <div id="mensaje" style="display: none;"></div>
  <form action="" method="post" target="">
    <div class="row">
    <div class="col-md-10">
        <div class="col-md-2">
            <button type="button" class="btn btn-primary" id="nuevo" data-toggle="tooltip" data-placement="rigth" title = "Crear nuevo Boxtype">
                <span class="glyphicon glyphicon-plus-sign black"></span>
            </button>
      </div>
      <h3><strong>Listado de Causas</strong></h3>
    </div>
</div>
    
     <table id="tabla" cellspacing="0" width="100%" class="display" >  
        <thead>
        <th><strong>Causa</strong></th>
        <th><strong>Detalle</strong></th>
        <th ><strong>Editar</strong></th>
        <th><strong>Eliminar</strong></th>
          
       </thead>
       <tbody>
            <?php
            //Leer todas las boxtype
            $sql = "SELECT * FROM tblcausas";
            $val = mysqli_query($link, $sql);
             if(!$val){
                echo "<tr><td>".mysqli_error()."</td></tr>";
             }else{
                $cant = 0;
                while($row = mysqli_fetch_array($val))
                {
                  $cant ++;
                  echo "<tr id=\"".$row['id']. "\">";
                  echo "<td >".$row['causa']."</td>";
                  echo "<td >".$row['detalle']."</td>";
                  echo '<td ><img id="editar" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-151-edit.png" data-toggle="tooltip" data-placement="left" title = "Modificar Boxtype"/></td>';
		  echo '<td ><img id="eliminar" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-17-bin.png" data-toggle="tooltip" data-placement="left" title = "Eliminar Boxtype"/></td>';
                  echo "</tr>";
	        }
	 							
             }
            mysqli_close($link);
            ?>
     </tbody>   
    </table>
   
  </form> 
    
</div> 
    
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


<!--Ventana modal para crear nuevo boxtype-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Nueva Causa</h4>
      </div>
      <div class="modal-body">
          <form id="form_causa" method="post">
          <div class="row">
            <div class="col-md-8">
              <label for="causa" class="control-label">Causa:</label>
              <input type="text" class="form-control" name="causa" id="causa"/>
            </div>
          </div>
          <div class="row">
            <div class="col-md-8">
                  <label for="detalle" class="control-label">Detalle:</label>
                  <textarea type="text" class="form-control" rows="3" name="detalle" id="detalle"></textarea>
            </div>    
          </div>  
          
          <input type="hidden" name="id_causa" id="id_causa" />
          
          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" id="guardar" class="btn btn-primary">Guardar</button>
      </div>
     </form>
    </div>
  </div>
</div>
<div class="modal fade" id="EliminarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Eliminar Causa</h4>
      </div>
      <div class="modal-body">
          <form id="" action="nuevofiltro_causa.php">
          <div class="form-group">
            <label for="" class="control-label">Está Seguro que desea eliminar esta causa</label>
          </div>
          <input type="hidden" name="eliminar_id_causa" id="eliminar_id_causa" />
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button type="button" id="elim_causa" onclick="" class="btn btn-primary">Si</button>
      </div>
    </div>
  </div>
</div>
</body>
</html>

