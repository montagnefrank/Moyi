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
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registro de Estados</title>
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
          $('#form_estado').each(function(){
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
                    case 0: $('#myModal').find('#nombre').val($(this).text());
                            break;
                    case 1: $('#myModal').find('#codigo').val($(this).text());
                            break;
                    case 2: $('#myModal').find('#pais').val($(this).text());
                            break;   
                }
                
            });
        });
            $('#myModal').find('#id_estado').val(id_fila);
            $('#myModal').modal('show');
       });
        
        //codigo que se ejecuta al eliminar un elemento
        $('#tabla tbody').on( 'click', '#eliminar', function () {
            accion="eliminar";
            arreglar_ventana1();
            id_fila=$(this).closest('tr').attr('id');
            valor=$(this).parents('tr').children('td:eq(0)').html();
            $('#EliminarModal').find('#eliminar_id_estado').val(id_fila)
            $('#EliminarModal').modal('show');
        });
        //codigo que se ejecuta al dar si en el eliminar
        $('#elim_estado').on('click', function () {
            var parametros = {
                "id":$('#eliminar_id_estado').val(),
                "accion":accion
              };
              $.ajax({
                    data:  parametros,
                    url:   'nuevofiltro_estados.php',
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
        $("#form_estado").validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class",
    
            // Specify the validation rules
            rules: {
                  nombre: {required:true},
                  codigo: {required:true},
                  pais: {required: true}
             },
        
        // Specify the validation error messages
        messages: {
              nombre: "Campo requerido",
              codigo: "Campo requerido",
              pais: "Campo requerido"              
        },
        submitHandler: function(form) {
           if(accion=="nuevo")
           {
             var parametros = {
                "nombre" : $('#nombre').val(),
                "codigo" : $('#codigo').val(),
                "pais" : $('#pais').val(),
                "accion":accion
             };
             $.ajax({
                    data:  parametros,
                    url:   'nuevofiltro_estados.php',
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
                           response.nombre,
                           response.codigo,
                           response.pais,
                           '<img id="editar" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-151-edit.png" data-toggle="tooltip" data-placement="left" title = "Modificar Estado"/></td>',
		           '<img id="eliminar" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-17-bin.png" data-toggle="tooltip" data-placement="left" title = "Eliminar Estado"/></td>'
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
                "nombre" : $('#nombre').val(),
                "codigo" : $('#codigo').val(),
                "pais" : $('#pais').val(),
                "id":$('#id_boxtype').val(),
                "accion":accion
             };
              $.ajax({
                    data:  parametros,
                    url:   'nuevofiltro_estados.php',
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
                             cell.data(response.nombre).draw();
                         var cell1 = oTable.cell($('tr#'+id_fila+' td:eq(1)'));
                             cell1.data(response.codigo).draw();
                         var cell2 = oTable.cell($('tr#'+id_fila+' td:eq(2)'));
                             cell2.data(response.pais).draw();
                         
                         
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
                    <?php
				    }
                    ?>
                    <li class="divider"></li>
                    <li><a href="filtros.php?accion=buscarPO"><strong>Buscar PO</strong></a>
                </ul>
              </li>
              
              <?php
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
                    </li>	
                 <?php
				 }
				 ?>
                 
                 <?php if($rol == 1){  ?>
			     	<li><a href="usuarios.php"><strong>Usuarios</strong></a></li>
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
  <div id="mensaje" style="display: none;"></div>
  <form action="" method="post" target="">
    <div class="row">
    <div class="col-md-10">
        <div class="col-md-2">
            <button type="button" class="btn btn-primary" id="nuevo" data-toggle="tooltip" data-placement="rigth" title = "Crear nuevo Estado">
                <span class="glyphicon glyphicon-plus-sign black"></span>
            </button>
      </div>
    	<h3><strong>Listado de Estados</strong></h3>
    </div>
</div>
    
     <table id="tabla" cellspacing="0" width="100%" class="display" >  
        <thead>
        <th><strong>Estado</strong></th>
        <th><strong>Codigo</strong></th>
        <th><strong>Pais</strong></th>
        <th ><strong>Editar</strong></th>
        <th><strong>Eliminar</strong></th>
          
       </thead>
       <tbody>
            <?php
            //Leer todas las boxtype
            $sql = "SELECT * FROM tblestados";
            $val = mysqli_query($link, $sql);
             if(!$val){
                echo "<tr><td>".mysqli_error()."</td></tr>";
             }else{
                $cant = 0;
                while($row = mysqli_fetch_array($val))
                {
                  $cant ++;
                  echo "<tr id=\"".$row['codigo']. "\">";
                  echo "<td>".$row['nombre']."</td>";
                  echo "<td>".$row['codigo']."</td>";
                  echo "<td>".$row['pais']."</td>";
                  echo '<td><img id="editar" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-151-edit.png" data-toggle="tooltip" data-placement="left" title = "Modificar Estado"/></td>';
		  echo '<td><img id="eliminar" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-17-bin.png" data-toggle="tooltip" data-placement="left" title = "Eliminar Estado"/></td>';
                  echo "</tr>";
	        }
	 							
             }
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
        <h4 class="modal-title" id="exampleModalLabel">Nuevo Estado</h4>
      </div>
      <form id="form_estado" method="post">  
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8">
            <label for="nombre" class="control-label">Nombre:</label>
            <input type="text" class="form-control" name="nombre" id="nombre"/>
          </div>
              
        </div>  
        <div class="row">
            <div class="col-md-4">
                <label for="codigo" class="control-label">Codigo:</label>
                <input type="text" class="form-control" name="codigo" id="codigo"/>
            </div>
        </div>    
        <div class="row">
           <div class="col-md-4">
               <label for="pais" class="control-label">Pais:</label>
               <select type="text" class="form-control" name="pais" id="pais">
                   <option value="US">Estados Unidos</option>
                   <option value="CA">Canadá</option>
               </select>
           </div>
        </div>
        <input type="hidden" name="id_estado" id="id_estado" />   
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
        <h4 class="modal-title" id="exampleModalLabel">Eliminar Estado</h4>
      </div>
      <div class="modal-body">
          <form action="nuevofiltro_estados.php">
          <div class="form-group">
            <label for="nombre_estado" class="control-label">Está Seguro que desea eliminar este estado</label>
          </div>
          <input type="hidden" name="eliminar_id_estado" id="eliminar_id_estado" />
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button type="button" id="elim_estado" onclick="" class="btn btn-primary">Si</button>
      </div>
    </div>
  </div>
</div>
</body>
</html>

