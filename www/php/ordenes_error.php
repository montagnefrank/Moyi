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
$id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ordenes erróneas</title>

  <link rel="icon" type="image/png" href="../images/favicon.ico" />
  <link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
  <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/octicons.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/zenburn.css" rel="stylesheet" type="text/css">
  <link href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1"rel="stylesheet" type="text/css" media="all"  />
  <!-- <link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">  -->

  <script language="javascript" src="../js/imprimir.js"></script>
  <script type="text/javascript" src="../js/script.js"></script>
  <script src="../bootstrap/js/jquery.js"></script>
  <script src="../bootstrap/js/bootstrap.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
  <script src="../bootstrap/js/bootstrap-submenu.js"></script>
  <script src="../bootstrap/js/docs.js" defer></script>
  <script type="text/javascript" src="../js/calendar.js"></script>
  <script type="text/javascript" src="../js/calendar-en.js"></script>
  <script type="text/javascript" src="../js/calendar-setup.js"></script>
  <script type="text/javascript">
//   function marcar() {
//       
//       
//      for (var i = 0; i < document.form.elements.length; i++) {
//        if(document.form.elements[i].type == 'checkbox'){
//    		if(document.form.elements[i].disabled == false){
//          	    document.form.elements[i].checked = !(document.form.elements[i].checked);
//    		}
//        }
//      }
//    }
//
//    function filtrar(){
//    	var cajas = [];
//        $("input[type=checkbox]:checked").each(function(index){
//            cajas[index]=$(this).val();
//            
//        });
//    	  window.open("archivar.php?codigo=0"+"&cajas="+cajas,"Cantidad","width=500,height=360,top=100,left=400");
//    }   
//   
//   $(document).ready(function(){
//    
//    $("input#todos[type=checkbox]:checked").each(
//       $("input[type=checkbox]").each(
//        function ()
//        {
//            nombres.push($(this).data("nombre"));
//        });
//    );
//    });
  </script>
  <style>
  .contenedor {
       margin:0 auto;
       width:85%;
       text-align:center;
  }
  li a{
      	cursor:pointer;/*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
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
			  	<li><a href="../php/subirTracking1.php"><strong>Cargar Tracking</strong></a></li>
			  <?php }?> 
 
			  <?php 
              if($rol<= 2){ ?>
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
                         <a tabindex="0" data-toggle="dropdown">
                             <strong>Contabilidad</strong><span class="caret"></span>
                         </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="administration.php"><strong>Contabilidad</strong></a></li>                      
                            <li class="divider"></li>         
                            <li><a href="contabilidad.php"><strong>Contabilidad ECU</strong></a></li>
                       </ul>
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
<div class="table-responsive">

<div class="row">
<!--    <div class="col-md-2">
        <a href="arreglarTodos.php" class="btn btn-info" role="button">Procesar Todas</a>
   </div>-->
    <div class="col-md-8">
    	<h3><strong>Órdenes con errores</strong></h3>
    </div>
    
    <div class="col-md-1">
         <input type="image" style="cursor:pointer" id="imprimir"  name="imprimir"class= "imprimir" src="../images/excel.png" heigth="40" value="" data-toggle="tooltip" data-placement="left" title = "Exportar Reporte Excel" width="30" formaction = "crearReportExcel.php"/>
   </div>
</div>
<table width="50%" border="0" align="center" class="table table-striped" >  
  <tr>
<!--      <td align="center">
          <input id="todos" type="checkbox" onchange="marcar()" title="Marcar todos"/>
      </td> -->
    <td width="34%" align="center"><strong>Ponumber</strong></td>
    <td width="66%" align="center"><strong>Error</strong></td>

  </tr>

  <?php
			  $sql =   "SELECT id_orden_detalle, Ponumber, errormsj FROM tblerror";
			  $val = mysqli_query($link, $sql);
			  if(!$val){
				echo "<tr><td>".mysqli_error()."</td></tr>";
			   }else{
				   $cant = 0;
				   while($row = mysqli_fetch_array($val)){
					   $cant ++;
						echo "<tr>";
//                                                echo '<td align="center"><input id="archivo" type="checkbox" value="X" onchange="marcar()" title="Marcar todos"/></td>';
						$valor = $row['id_orden_detalle'];
						echo "<td align='center'><strong><a href='modificarorden_error.php?codigo=".$row['id_orden_detalle']."'>".$row['Ponumber']."</a></strong></td>";
						//".$row['id_orden_detalle']."
						//<a href="http://www.w3schools.com">Visit W3Schools</a>
						echo "<td align='center'>".$row['errormsj']."</td>";
						echo "</tr>";
					 }
							echo "<tr><td></td></tr>
								  <tr>
								  <td align='right'><strong>".$cant. "</strong></td>
								  <td>Órden(es) erróneas encontradas</td>
								  </tr>";						
			   }
			   	    mysqli_close($conection);
	  	
			//Preparando los datos para el reporte
		  /* $_SESSION["titulo"] ="Ordenes por volar ".date('Y-m-d');
		   $_SESSION["columnas"] = array("Estado","Ponumber","Custnumber","Tracking","Item","Deliver","Ship Date","Bill To"); 
		   $_SESSION["consulta"] =   "SELECT estado_orden,Ponumber, Custnumber, tracking, cpitem, delivery_traking,ShipDT_traking, soldto1
						FROM
						tblorden
						INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
						INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
						WHERE ShipDt_traking <= '".date('Y-m-d')."' AND tracking = '' AND estado_orden = 'Active'";
		   $_SESSION["nombre_fichero"] = "Ordenes por volar.xlsx";	
       */
  ?>
   </table>
 </div> <!-- table responsive-->
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