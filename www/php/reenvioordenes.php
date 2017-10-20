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

$id = $_GET['id'];
$error = $_GET['error'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reenvío de Órdenes</title>

  <link rel="icon" type="image/png" href="../images/favicon.ico" />
  <link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
  <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
 <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
  

  <script language="javascript" src="../js/imprimir.js"></script>
  <script type="text/javascript" src="../js/script.js"></script>
  <script src="../bootstrap/js/jquery.js"></script>
  <script src="../bootstrap/js/bootstrap.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
  <script src="../bootstrap/js/bootstrap-submenu.js"></script>
  <script src="../bootstrap/js/docs.js" defer></script>

  <script type="text/javascript">
      /*Esto no estaba funcionando, en su lugar se copio reenviar*/
//  function modificar(valor,item,deliver){
//  	var v=valor;
//  	//var d=document.getElementById('deliver').innerHTML;
//    //var i= document.getElementById('item').innerHTML;
//    var i=item;
//    var d=deliver;
//    alert (i);
//  	window.open("reenviar.php?codigo="+v+"&deliver="+d+"&item="+i+"","Cantidad","width=300,height=150,top=350,left=400");
//  	return false;
//  }

  function reenviar(valor,item,deliver){
      
		var v=valor;
		//var d= document.getElementById('deliver').innerHTML;
		//var i= document.getElementById('item').innerHTML;
		var i=item;
		var d=deliver;
		window.open("reenviar1.php?codigo="+v+"&deliver="+d+"&item="+i+"&pag=reenvioordenes","Cantidad","width=300,height=150,top=350,left=400");
		return false;
  }
  </script>
  <script language="javascript">
    function Compara(frmFec)
    {
      var ponumber = document.getElementById('ponumber').value;	
      if(ponumber == '')
        {
          window.location.href='reenvioordenes.php?error=2';
    	  return false;
        }
    	return true;
    }
  </script>
  <style>
  .contenedor {
       margin:0 auto;
       width:85%;
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
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
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
              <li  class="active">
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
                 
                    <li><a href="administration.php">
                             <strong>EDI</strong>
                         </a> 

                 <!--   <li class="dropdown">
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
<?php
		if($error == 2){
			
        	echo '<div class="alert alert-danger" role="alert">
				  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				  <span class="sr-only">Error:</span>
				  <strong>¡Faltan datos!</strong>, debe escribir algún Número de Teléfono.
				</div>';
		}
?>
    	<form method="post" id="frm1" name="frm1" target="_parent" >
         <div class="table-responsive">
	    <table width="50%" border="0" align="center" class="table table-striped">
        
              <tr>
                    <td  colspan="5" align="center"><span id="result_box" lang="en" xml:lang="en"><strong>BUSCAR ÓRDENES PARA REENVIAR</strong></span></td>
              </tr>
              <tr>
                 <td>
                     <div class="col-mdd-1">
                         <label>Ingrese Ponumber:</label>
                     </div>
                     <div class="col-md-6">
                        <input name="ponumber" type="text" class="form-control"  id="ponumber" onKeyPress="return validar_texto(event)" /> 
                     </div>
                     <div class="col-md-2">
                        <input type="submit" name="buscar" id="buscar" value="Buscar" onclick="return Compara(this.form)" class="btn btn-primary"/> 
                     </div>
                 </td>
              </tr>
              </table>
		</div> <!-- table responsive-->
          </form>
    <form method="post" id="frmreport" name="frmreport">
    <div class="table-responsive">
<div class="row">
     <div class="col-md-11">
    	<h3><strong>Listado de Órdenes</strong></h3>
    </div>
    <div class="col-md-1">
         <input type="image" style="cursor:pointer" id="imprimir"  name="imprimir"class= "imprimir" src="../images/excel.png" heigth="40" value="" data-toggle="tooltip" data-placement="left" title = "Exportar Reporte Excel" width="30" formaction = "crearReportExcel.php"/>
   </div>
</div>
<table width="50%" border="0" align="center" class="table table-striped" > 
  <tr>
    <td align="center"><strong>Estado</strong></td>
    <td align="center"><strong>Reenvío</strong></td>
    <td align="center"><strong>Tracking</strong></td>
    <td align="center"><strong>Phonenumber</strong></td>
    <td align="center"><strong>CustNbr</strong></td>
    <td align="center"><strong>Producto</strong></td>
    <td align="center"><strong>Fecha Órden</strong></td>
    <td align="center"><strong>Fecha Entrega</strong></td>
    <td align="center"><strong>Comprador</strong></td>
    <td align="center"><strong>Reenviar</strong></td>
  </tr>

  <?php
  if(!isset($_POST['buscar'])){
      
	  if($_GET['id']!=''){
		  //verificar que campos tiene valor para buscar
		  if($id != ''){
			  $sql =   "select *
						FROM
						tblorden
						INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
						INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
						INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
						INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
						INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
						WHERE Ponumber = '".$id."'";
			  $val = mysqli_query($link, $sql);
			  if(!$val){
				echo "<tr><td>".mysqli_error()."</td></tr>";
			   }else{
				   $cant = 0;
				   while($row = mysqli_fetch_array($val)){
					   $cant ++;
						echo "<tr>";
							echo "<td align='center'>".$row['estado_orden']."</td>";
							echo "<td align='center'>".$row['reenvio']."</td>";
							echo "<td align='center'><strong>".$row['tracking']."</strong></td>";
							echo "<td align='center'><strong>".$row['Ponumber']."</strong></td>";
							echo "<td align='center'><strong>".$row['Custnumber']."</strong></td>";
							echo "<td align='center'><strong><label id='item'>".$row['cpitem']."</label></strong></td>";
							echo "<td align='center'>".$row['order_date']."</td>";
							echo "<td align='center'><label  id='deliver'>".$row['delivery_traking']."</label></td>";
							echo "<td>".$row['soldto1']."</td>";
						
						if($row['reenvio']=='No'){
						echo "<td align='center'><input type='image' style='cursor:pointer' name='btn_cliente' id='btn_cliente' src='../images/forward.png' heigth='30' value='' title = 'Modificar orden' width='20' onclick = 'modificar(".$row['id_orden_detalle'].",\"".$row['cpitem']."\",\"".$row['delivery_traking']."\")'    />   </td>";
						}else{
					    	echo '<td align="center"><input disabled="true" type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../images/forward.png" heigth="30" value="" width="20"/></td>';
						}
													 
						echo "</tr>";
					 }
							echo " <tr>
								  <td align='right'><strong>".$cant. "</strong></td>
								  <td>Órden(es) encontradas</td>
								  </tr>";						
			   }
			   mysqli_close($conection);
			   
			   //Preparando los datos para el reporte
			   $_SESSION["titulo"] ="Listado de Ordenes";
			   $_SESSION["columnas"] = array("Estado","Reenvío","Tracking","Ponumber","CustNbr","Item","Orddate","Deliver","Bill To"); 
			   $_SESSION["consulta"] =   "select estado_orden,reenvio,tracking,Ponumber,Custnumber,cpitem,order_date,delivery_traking,soldto1
						FROM
						tblorden
						INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
						INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
						INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
						INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
						INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
						WHERE Ponumber = '".$id."'";
			   $_SESSION["nombre_fichero"] = "Listado de Ordenes.xlsx";
		  }
	  }
		 
  }else{
  //if(isset($_POST['buscar'])){
	
	  //recoger datos de busqueda
	  $ponumber = $_POST['ponumber'];
	  //verificar que campos tiene valor para buscar
	  if($ponumber != ''){
		  $sql =   "select *
						FROM
						tblorden
						INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
						INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
						INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
						INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
						INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
						WHERE Ponumber = '".$ponumber."'";
	      $val = mysqli_query($link, $sql);
		  if(!$val){
		  	echo "<tr><td>".mysqli_error()."</td></tr>";
		   }else{
				   $cant = 0;
				   while($row = mysqli_fetch_array($val)){
					   $cant ++;
						echo "<tr>";
							echo "<td align='center'>".$row['estado_orden']."</td>";
							echo "<td align='center'>".$row['reenvio']."</td>";
							echo "<td align='center'><strong>".$row['tracking']."</strong></td>";
							echo "<td align='center'><strong>".$row['Ponumber']."</strong></td>";
							echo "<td align='center'><strong>".$row['Custnumber']."</strong></td>";
							echo "<td align='center'><strong><label id='item'>".$row['cpitem']."</label></strong></td>";
							echo "<td align='center'>".$row['order_date']."</td>";
							echo "<td align='center'><label  id='deliver'>".$row['delivery_traking']."</label></td>";
							echo "<td>".$row['soldto1']."</td>";
						
						if($row['reenvio']=='No'){
							echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/forward.png" heigth="30" value="" title = "Reenviar orden" width="20" onclick = "reenviar('.$row['id_orden_detalle'].',\''.$row['cpitem'].'\',\''.$row['delivery_traking'].'\')"/></td>';
							}else{
								echo '<td align="center"><input disabled="true" type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../images/forward.png" heigth="30" value="" width="20"/></td>';
							}
														 
						echo "</tr>";
					 }
							echo "								  <tr>
								  <td align='right'><strong>".$cant. "</strong></td>
								  <td>Órden(es) encontradas</td>
								  </tr>";						
		   }
		   mysqli_close($conection);
		   
		   //Preparando los datos para el reporte
			   $_SESSION["titulo"] ="Listado de Ordenes";
			   $_SESSION["columnas"] = array("Estado","Reenvío","Tracking","Ponumber","CustNbr","Item","Orddate","Deliver","Bill To"); 
			   $_SESSION["consulta"] =   "select estado_orden,reenvio,tracking,Ponumber,Custnumber,cpitem,order_date,delivery_traking,soldto1
						FROM
						tblorden
						INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
						INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
						INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
						INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
						INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
						WHERE Ponumber = '".$ponumber."'";
			   $_SESSION["nombre_fichero"] = "Listado de Ordenes.xlsx";
	  }

  }
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
</div> <!-- /container -->
</body>
</html>

