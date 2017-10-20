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
  <title>Registro de Destinos</title>
  <script type="text/javascript" src="../js/script.js"></script>
  <script language="javascript" src="../js/imprimir.js"></script>
  <link rel="icon" type="image/png" href="../images/favicon.ico" />
  <link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
  <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/octicons.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/zenburn.css" rel="stylesheet" type="text/css">
  <!-- <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"> -->

  <script language="javascript" src="../js/imprimir.js"></script>
  <script type="text/javascript" src="../js/script.js"></script>
  <script src="../bootstrap/js/jquery.js"></script>
  <script src="../bootstrap/js/bootstrap.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
  <script src="../bootstrap/js/bootstrap-submenu.js"></script>
  <script src="../bootstrap/js/docs.js" defer></script>
  
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

  .table-responsive {     /*All of this .table-responsive added by me  */
      width: 100%;
      height: 600px;    /*change added by me*/
      margin-bottom: 15px;
      overflow-y: scroll;  /*change by me org hidden */
      overflow-x: scroll;
      -ms-overflow-style: -ms-autohiding-scrollbar;
      border: 1px solid #ddd;
      -webkit-overflow-scrolling: touch;
  }
  .table-responsive>.table { 
      margin-bottom: 0;    
  }
    
  /*End added by me */

.header-fixed {
    width: 100% 
}

.header-fixed > thead,
.header-fixed > tbody,
.header-fixed > thead > tr,
.header-fixed > tbody > tr,
.header-fixed > thead > tr > th,
.header-fixed > tbody > tr > td {
    display: block;
}

.header-fixed > tbody > tr:after,
.header-fixed > thead > tr:after {
    content: ' ';
    display: block;
    visibility: hidden;
    clear: both;
}

.header-fixed > tbody {
    overflow-y: auto;
    height: 150px;
}

.header-fixed > tbody > tr > td,
.header-fixed > thead > tr > th {
    width: 7.7%;
    float: left;
}

  </style>
  <script type="text/javascript">
  function modificar(valor){
  	var v=valor;
  	window.open("modificardestino.php?codigo="+v,"Cantidad","width=500,height=630,top=10,left=350");
  	return false;
  }
  function nueva(){
  	window.open("nuevodestino.php","Cantidad","width=500,height=630,top=10,left=350");
  	return false;
  }
  function eliminar(valor){
  	var v=valor;
  	window.open("eliminardestino.php?codigo="+v,"Cantidad","width=300,height=150,top=300,left=400");
  	return false;
  }

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

                <!--    <li class="dropdown">
                         <a tabindex="0" data-toggle="dropdown">
                             <strong>Contabilidad</strong><span class="caret"></span>
                         </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="administration.php"><strong>Contabilidad</strong></a></li>                      
                            <li class="divider"></li>         
                            <li><a href="contabilidad.php"><strong>Contabilidad ECU</strong></a></li>
                       </ul>    -->
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
    <form method="post" id="frmreport" name="frmreport">
  <div class="row">
     <div class="col-md-10">
    	<h3><strong>Listado de Destinos</strong></h3>
    </div>
    <div class="col-md-1">
	    <!-- <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="rigth" title = "Crear nuevo destino" onclick = "nueva()">
	    	<input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-37-file.png" value="" title = "Crear nuevo cliente" onclick = "nueva()"/>
	    </button>  -->
    </div>
    <div class="col-md-1">
    <!--    <input type="image" style="cursor:pointer" id="imprimir"  name="imprimir" class= "imprimir" src="../images/excel.png" heigth="40" value="" title = "Exportar Reporte Excel" width="30" formaction = "crearReportExcel.php"/>   -->
    </div>
</div>
<div class="table-responsive header-fixed">

<table id="Exportar_a_Excel" border="0" align="center" class="table  table-hover" >   
<thead>
   <tr>
  	<td align="center"><strong>Código Cliente</strong></td>
    <td align="center"><strong>Destino</strong></td>
    <td align="center"><strong>Shipto1</strong></td>
    <td align="center"><strong>Shipto2</strong></td>
    <td align="center"><strong>Dirección1</strong></td>
    <td align="center"><strong>Dirección2</strong></td>
    <td align="center"><strong>Ciudad</strong></td>
    <td align="center"><strong>Estado</strong></td>
    <td align="center"><strong>País</strong></td>
    <td align="center"><strong>Zip</strong></td>
    <td align="center"><strong>Teléfono</strong></td>
    <td align="center"><strong>E-mail</strong></td>
    <td align="center"><strong>Editar</strong></td>
    <td align="center"><strong>Eliminar</strong></td>
  </tr>
    </thead>
   <tbody> 
  <?php
  //Leer todas las fincas existentes para modificarlas o crear nuevas
  $sql = "SELECT * FROM tbldestinos INNER JOIN tblshipto_venta ON tbldestinos.iddestino=tblshipto_venta.iddestino";
  $val = mysqli_query($link, $sql);
   if(!$val){
      echo "<tr><td>".mysqli_error()."</td></tr>";
   }else{
	   $cant = 0;
	   while($row = mysqli_fetch_array($val)){
		   $cant ++;
			echo "<tr>";
			echo "<td><strong>".$row['codcliente']."</strong></td>";
			echo "<td><strong>".$row['destino']."</strong></td>";
			echo "<td><strong>".$row['shipto1']."</strong></td>";
			echo "<td><strong>".$row['shipto2']."</strong></td>";
			echo "<td align='center'>".$row['direccion']."</td>";
			echo "<td align='center'>".$row['direccion2']."</td>";
			echo "<td align='center'>".$row['cpcuidad_shipto']."</td>";
			echo "<td align='center'>".$row['cpestado_shipto']."</td>";
			echo "<td>".$row['shipcountry']."</td>";
			echo "<td align='center'>".$row['cpzip_shipto']."</td>";
			echo "<td align='center'>".$row['cptelefono_shipto']."</td>";
			echo "<td>".$row['mail']."</td>";
			
			echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-151-edit.png" value="" data-toggle="tooltip" data-placement="left" title = "Modificar destino" onclick = "modificar('.$row['iddestino'].')"/></td>';
			
			echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-17-bin.png" value="" data-toggle="tooltip" data-placement="left" title = "Eliminar destino"  onclick = "eliminar('.$row['iddestino'].')"/></td>';
			echo "</tr>";
	 }
	 			echo "<tr>
				      <td align='right'><strong>".$cant. "</strong></td>
					  <td> Destinos registrados</td>
				      </tr>";						
   }
   
   //Preparando los datos para el reporte
   $_SESSION["titulo"] ="Listado de Clientes";
   $_SESSION["columnas"] = array("Código","Nombre","Dirección","Dirección2","Ciudad","Estado","Zip","País","Teléfono","Vendedor","E-mail"); 
   $_SESSION["consulta"] =   "SELECT codigo, empresa, direccion, direccion2, ciudad, estado, zip, pais, telefono, vendedor,mail FROM tblcliente order by codigo";
   $_SESSION["nombre_fichero"] = "Listado de Clientes.xlsx";
  ?>
  </form>
  </tbody>   
  </table>

 <ul class="pagination">
  <li class="disabled">
    <a href="#">&laquo;</a>
  </li>
  <li class="active">
    <a href="#">1 <span class="sr-only">(página actual)</span></a>
  </li>
  <li>
    <a href="#">2 </a>
    <li>
    <a href="#">&raquo;</a>
  </li>
  </li>
</ul>
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

