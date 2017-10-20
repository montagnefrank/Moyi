<?php

    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
include ("conectarSQL.php");
include ("conexion.php");
require_once('barcode.inc.php');
include ("seguridad.php");
include_once("class_imgUpldr.php"); 

$user     =  $_SESSION["login"];
$rol      =  $_SESSION["rol"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="cache-control" content="no-cache" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Apariencia del sistema</title>
  <link href="../images/favicon.ico" rel="icon" type="image/png"/>
  <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css"/>
 <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css"/>
  
  
  <style>
      li a{
      	cursor:pointer;/*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
      } 
  </style>
  
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
					  <li>
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
				     	<li><a href="mainroom.php"><strong>Cuarto Frío</strong></a></li>     			   					
	            <li><a href="services.php" ><strong>Clientes</strong></a></li> 
	                        <li><a href="administration.php">
	                             <strong>EDI</strong>
	                        </a> 
	                    </li>	
	                 <?php
					 }
					 ?>
	                 
	                 <?php if($rol == 1 || strcasecmp($user,'MFA')==0){ //comparacion del usuario si es mafer tiene acceso la edicion de usuarios ?>
                            <li class="dropdown">
                                         <a tabindex="0" data-toggle="dropdown">
                                             <strong>Parámetros</strong><span class="caret"></span>
                                         </a>
                                        <ul class="dropdown-menu" role="menu">
                                             <li><a href="apariencia.php" ><strong>Apariencia</strong></a></li>   
                                             <li class="divider"></li>
                                             <li class="dropdown-submenu">
                                                <a tabindex="0" data-toggle="dropdown"><strong>Parámetros de Ordenes</strong></a>
                                                <ul class="dropdown-menu">
                                                   <li><a href="crearfiltros.php" ><strong>Filtro de Ciudad/Pais</strong></a></li>
                                                </ul>
                                            </li>
                                            <li class="divider"></li>
                                             <li class="dropdown-submenu">
                                                <a tabindex="0" data-toggle="dropdown"><strong>Parámetros de Productos</strong></a>
                                                <ul class="dropdown-menu">
                                                   <li><a href="crearfiltros_mediatallos.php" ><strong>Valor de Medidastallos</strong></a></li>
                                                    <li class="divider"></li>      
                                                    <li><a href="crearfiltros_colores.php" ><strong>Valor de Colores</strong></a></li>
                                                    <li class="divider"></li>      
                                                    <li><a href="crearfiltros_boxtype.php" ><strong>Valor de BoxType</strong></a></li>
                                                    <li class="divider"></li>      
                                                    <li><a href="crearfiltros_listaproductos.php" ><strong>Valor de Productos y HTS</strong></a></li>
                                                    <li class="divider"></li>      
                                                    <li><a href="crearfiltros_supplies.php" ><strong>Valor de Supplies</strong></a></li>
                                                </ul>
                                            </li> 
                                        </ul>
                                    </li>
                                    <li><a href="usuarios.php"><strong>Usuarios</strong></a></li>
                         <?php }else{
						$sql   = "SELECT id_usuario from tblusuario where cpuser='".$user."'";
						$query = mysql_query($sql,$conection);
						$row = mysql_fetch_array($query);
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
<div class="panel-body">
	<div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Logo cabecera</h3>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-2">
            	<!-- Se muestra la imagen llamada logo.png en la carpeta images -->
            	<?php
            	   echo "<img src='../images/logo.png' border='0' width='100%' height='100%'>";
            	?>
            </div>
            <div class="col-md-1">
            	<?php
            	$directorio1 = opendir("../images/"); //ruta actual
                    while ($archivo = readdir($directorio1)) //obtenemos un archivo y luego otro sucesivamente
                    {
                        if ( $archivo == "logo.png")//verificamos si es o no un directorio
                        {
                            echo "<br><strong>".$archivo."</strong>";
                        }
                    }
                    ?>
            </div>
            <div class="col-md-4">
            	</br>
            	<form action="cargarLogo.php?id=1" method="post" enctype="multipart/form-data" name="Upload" id="Upload">
		    <input type="file" name="archivo_logo" class="btn btn-primary"/>
			        	
            </div>
            <div class="col-md-2">
            	</br>
                <button type="submit" name="subir" id="subir" data-loading-text="Cargando..." class="btn btn-primary" autocomplete="off" data-toggle="tooltip" data-placement="right" title="Cargar Logo">
		    <span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Cargar logo</button>
		 </form>
             </div>
             <div class="col-md-3">
                </br> 
                <?php
                if($_GET['error']== '0'){
                    echo '  <div class="alert alert-success alert-dismissable">
                              <button type="button" class="close" data-dismiss="alert">&times;</button>
                              Éxito.
                            </div>';
                }else{
                    if($_GET['error']== '1'){
                    echo '  <div class="alert alert-danger alert-dismissable">
                              <button type="button" class="close" data-dismiss="alert">&times;</button>
                              No se pudo subir el archivo.
                            </div>';
                    }else{
                        if($_GET['error']== '2'){
                            echo '  <div class="alert alert-danger alert-dismissable">
                                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                                      No se pudo subir el archivo.
                                    </div>';
                            }else{
                                if($_GET['error']== '3'){
                                echo '  <div class="alert alert-danger alert-dismissable">
                                          <button type="button" class="close" data-dismiss="alert">&times;</button>
                                          No se pudo subir el archivo.
                                        </div>';
                                }else{
                                    if($_GET['error']== '4'){
                                        echo '  <div class="alert alert-warning alert-dismissable">
                                              <button type="button" class="close" data-dismiss="alert">&times;</button>
                                              No hay archivo seleccionado.
                                            </div>';
                                    }
                                }
                            }
                    }
                }
                ?>
             </div>
          </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Imagen principal</h3>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-2">
            	<!-- Se muestra la imagen llamada logo.png en la carpeta images -->
            	<?php
            		echo "<img src='../images/inicio.jpg' border='0' width='100%' height='100%'>";
            	?>
            </div>
            <div class="col-md-1">
            	<?php
            	$directorio = opendir("../images/"); //ruta actual
                    while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
                    {
                        if ( $archivo == "inicio.jpg")//verificamos si es o no un directorio
                        {
                            echo "<br><strong>".$archivo."</strong>";
                        }
                    }
                    ?>
            </div>
            <div class="col-md-4">
            	</br>
            	<form action="cargarImagen.php?id=1" method="post" enctype="multipart/form-data" name="Upload" id="Upload">
		   <input type="file" name="archivo" class="btn btn-primary"/>
             </div>
            <div class="col-md-2">
            	</br>
                <button type="submit" name="subir1" id="subir1" data-loading-text="Cargando..." class="btn btn-primary" autocomplete="off" data-toggle="tooltip" data-placement="right" title="Cargar Logo">
		   <span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Cargar imagen</button>
                </form>
             </div>
             <div class="col-md-3">
                </br> 
                <?php
                if($_GET['error']== '5'){
                    echo '  <div class="alert alert-success alert-dismissable">
                              <button type="button" class="close" data-dismiss="alert">&times;</button>
                              Éxito.
                            </div>';
                }else{
                    if($_GET['error']== '6'){
                    echo '  <div class="alert alert-danger alert-dismissable">
                              <button type="button" class="close" data-dismiss="alert">&times;</button>
                              No se pudo subir el archivo.
                            </div>';
                    }else{
                        if($_GET['error']== '7'){
                            echo '  <div class="alert alert-danger alert-dismissable">
                                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                                      No se pudo subir el archivo.
                                    </div>';
                            }else{
                                if($_GET['error']== '8'){
                                echo '  <div class="alert alert-danger alert-dismissable">
                                          <button type="button" class="close" data-dismiss="alert">&times;</button>
                                          No se pudo subir el archivo.
                                        </div>';
                                }else{
                                    if($_GET['error']== '9'){
                                        echo '  <div class="alert alert-warning alert-dismissable">
                                              <button type="button" class="close" data-dismiss="alert">&times;</button>
                                              No hay archivo seleccionado.
                                            </div>';
                                    }
                                }
                            }
                    }
                }
                ?>
             </div>
          </div>
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
    <span id="top-link-block" class="hidden">
    <a href="#top" class="well well-sm"  onclick="$('html,body').animate({scrollTop:0},'slow');return false;">
        <i class="glyphicon glyphicon-chevron-up"></i> Ir arriba
    </a>
</span><!-- /top-link-block --> 
</div> <!-- /container -->

<!-- Page Scripts-->
  <script src="../bootstrap/js/jquery.js"></script>
  <script src="../bootstrap/js/bootstrap.js"></script>
  <script src="../bootstrap/js/bootstrap-submenu.js"></script>
  <script src="../bootstrap/js/docs.js" defer></script>

  <script type="text/javascript">
  $(document).ready(function(){
  		//tol-tip-text
  		$(function () {
  		  $('[data-toggle="tooltip"]').tooltip();
  		});

  		
   });
  </script>
<!-- -->
</body>
</html>