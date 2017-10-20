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
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Etiquetas Existentes</title>
<link rel="icon" type="image/png" href="../images/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
<link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/octicons.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/zenburn.css" rel="stylesheet" type="text/css">
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">

<script language="javascript" src="../js/imprimir.js"></script>
<script type="text/javascript" src="../js/script.js"></script>
<script src="../bootstrap/js/jquery.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
<script src="../bootstrap/js/bootstrap-submenu.js"></script>
<script src="../bootstrap/js/docs.js" defer></script>

<style>
.contenedor {
     margin-left: 10px;
     width:110%;
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
li a{
      	cursor:pointer;/*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
      } 
</style>
<script>
 $(document).ready(function(){
		//tol-tip-text
		$(function () {
		  $('[data-toggle="tooltip"]').tooltip()
		});
		
		// Only enable if the document has a long scroll bar
		// Note the window height + offset
		if ( ($(window).height() + 100) < $(document).height() ) {
			$('#top-link-block').removeClass('hidden').affix({
				// how far to scroll down before link "slides" into view
				offset: {top:100}
			});
		}
    });
</script>
</head>
<body background="../images/fondo.jpg">
<div class="contenedor">
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
         <li>
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
        <li class="active">
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
               if($rol == 4){  
					$sql   = "SELECT id_usuario from tblusuario where cpuser='".$user."'";
					$query = mysqli_query($link,$sql);
					$row = mysqli_fetch_array($query);
					echo '<li><a href="" onclick="cambiar(\''.$row[0].'\')"><strong>Contraseña</strong></a>'; 
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
<div class="table-responsive">
    <table width="50%" border="0" align="center" class="table table-striped" >
      <tr>
                        <td  colspan="5" align="center"><h3><strong>LISTADO DE PEDIDOS A LAS FINCAS</strong></h3></td>
                  </tr>
    </table>
</div>

<form id="form" name="form" method="post">
<div class="table-responsive">
<table id="Exportar_a_Excel" border="0" align="center" class="table table-striped" > 
   <?php
  require ("../scripts/file2.php");
  ?>
 </table>
 </div> <!-- /table-responsive -->
 </form> 

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
</body>
</html>

