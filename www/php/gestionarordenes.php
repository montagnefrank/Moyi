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
  <title>Modificar Orden</title>
  <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" media="all"  />
  
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

  <script type="text/javascript">
   $(document).ready(function(){
  		//tol-tip-text
  		$(function () {
  		  $('[data-toggle="tooltip"]').tooltip()
  		});
      });
  </script>
  <script type="text/javascript">
    function modificar(valor){
    	var v=valor;
    	window.open("modificarorden.php?codigo="+v,"Cantidad","width=900,height=880,top=50,left=300");
    	//alert ('Hola');
      //location.href = "modificarorden.php";
      //window.location = "modificarorden.php";
      return false;
    }
    function cancelar(valor){
    	var v=valor;
    	window.open("eliminar.php?codigo="+v,"Cantidad","width=310,height=200,top=350,left=400");
    	return false;
    }
    function eliminar(valor){
    	var v=valor;
    	window.open("eliminarorden.php?codigo="+v,"Cantidad","width=310,height=200,top=350,left=400");
    	return false;
    }

    function eliminar_tracking(valor){
      var v=valor;
      window.open("eliminar_tracking.php?codigo="+v,"Cantidad","width=310,height=200,top=350,left=400");
      return false;
    }
  </script>
  <script language="javascript">
    function Compara(frmFec)
    {
      var ponumber = document.getElementById('ponumber').value;
    	var fecha1 = document.getElementById('txtinicio').value;
    	var fecha2 = document.getElementById('txtfin').value;
    	var Anio = (frmFec.txtinicio.value).substr(0,4)
      var Mes = ((frmFec.txtinicio.value).substr(5,2))*1     
      var Dia = (frmFec.txtinicio.value).substr(8,2)
      var Anio1 = (frmFec.txtfin.value).substr(0,4)
      var Mes1 = ((frmFec.txtfin.value).substr(5,2))*1 
      var Dia1 = (frmFec.txtfin.value).substr(8,2)
      var Fecha_Inicio = new Date(Anio,Mes,Dia)
      var Fecha_Fin = new Date(Anio1,Mes1,Dia1)
    	
    	//alert('fecha de inicio'+Fecha_Fin);

      if(ponumber == '' && fecha1 == '' && fecha2 == '' )
      {
        window.location.href='gestionarordenes.php?error=2';
    	  return false;
      }
      if(Fecha_Inicio > Fecha_Fin)
      {
        //alert("La fecha de inicio es mayor que la fecha de fin; Introduzca un período válido");
    	  window.location.href='gestionarordenes.php?error=3';
    	  return false;
      }
      else
      {
        return true;
      }
    }
  </script>
  <script>
  function validar_texto(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
    //alert('No puede ingresar letras');
        return true;
    }
        
    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-9]/;

    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
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
              <li  class="active" >
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



               <!--     <li class="dropdown">
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
<?php
		if($error == 2){
			
        	echo '<div class="alert alert-danger" role="alert">
				  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				  <span class="sr-only">Error:</span>
				  <strong>¡Faltan datos!</strong>, debe llenar algún filtro de búsqueda.
				</div>';
		}else{
			if($error == 3)
			echo '<div class="alert alert-danger" role="alert">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				    <span class="sr-only">Error:</span>
				    <strong>¡Error!</strong> La fecha inicial debe ser menor que la fecha final.
				 </div>';
		}
?>
<form method="post" id="frm1" name="frm1" target="_parent" >
<div class="table-responsive">
<table width="50%" border="0" align="center" class="table table-striped" >
              <tr>
                    <td  colspan="5" align="center"><span id="result_box" lang="en" xml:lang="en"><h3><strong>BUSCAR ÓRDENES PARA MODIFICAR SUS DATOS</strong></h3></span></td>
              </tr>
              <tr>
                  <td>
                    <div class="row"> 
                      <div class="col-md-2">
                        <p><strong>Ponumber:</strong></p>
                        <input name="ponumber" type="text" class="form-control"  id="ponumber" onKeyPress="return validar_texto(event)" value="" size="20" autofocus />
                      </div>
                      <div class="col-md-4">
                        <p><strong>Fecha Inicio:</strong></p>
                      <div class='input-group date' id='datetimepicker1'>
                                <input type='text' class="form-control" name="txtinicio" id="txtinicio" value="<?php echo date ('Y-m-d')?>" placeholder="Fecha inicio"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            <script type="text/javascript">
                                $(function () {
                                    $('#datetimepicker1').datetimepicker({
                                        format: 'YYYY-MM-DD',
                                        showTodayButton:true
                                    });
                                });
                            </script>
                        </div>
                        <div class="col-md-4">
                       <p><strong>Fecha Fin:</strong></p>
                       <div class='input-group date' id='datetimepicker2'>
                                <input type='text' class="form-control" name="txtfin" id="txtfin" value="<?php echo date ('Y-m-d')?>" placeholder="Fecha fin"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            <script type="text/javascript">
                                $(function () {
                                    $('#datetimepicker2').datetimepicker({
                                        format: 'YYYY-MM-DD',
                                        showTodayButton:true
                                    });
                                });
                            </script>
                        </div>
                        <div class="col-md-2">
                          <br>
                          <input type="submit" name="buscar" id="buscar" value="Buscar"  onclick="Compara(this.form)" class="btn btn-primary" title="Buscar" />
                        </div>
                    </div>
                  </td>
              </tr>
              </table>
      </form>
</div> <!-- table responsive-->
<form method="post" id="frmreport" name="frmreport">
<div class="row">
     <div class="col-md-11">
    	<h3><strong>Listado de Órdenes</strong></h3>
    </div>
    <div class="col-md-1">
         <input type="image" style="cursor:pointer" id="imprimir"  name="imprimir"class= "imprimir" src="../images/excel.png" heigth="40" value="" data-toggle="tooltip" data-placement="left" title = "Exportar Reporte Excel" width="30" formaction = "crearReportExcel.php"/>
   </div>
</div>
<div class="table-responsive">
<table width="100%" border="0" class="table table-condensed">  
  <thead>
   <tr>
    <th align="center">Estado</th>
    <th align="center">Reenvío</th>
    <th align="center">Ponumber</th>
    <th align="center">Custnumber</th>
    <th align="center">Tracking</th>
    <th align="center">Producto</th>
    <th align="center">Deliver</th>
    <th align="center">Comprador</th>
    <th align="center">Editar</th>
    <th align="center">Cancelar/Activar</th>
    <th align="center">Eliminar</th>
    <th align="center">Eliminar Tracking</th>
  </tr>
    </thead>
    <tbody>
  <?php
  if(!isset($_POST['buscar'])){
	  if($_GET['id']!=''){
		  //verificar que campos tiene valor para buscar
		  if($id != ''){	
			  $sql =   "SELECT id_orden_detalle,estado_orden,Ponumber, Custnumber, tracking, cpitem, delivery_traking, reenvio, soldto1
						FROM
						tblorden
						INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
						INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
						WHERE Ponumber = '".$id."' order by Ponumber";
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
					echo "<td align='center'><strong>".$row['Ponumber']."</strong></td>";
					echo "<td align='center'><strong>".$row['Custnumber']."</strong></td>";
					echo "<td align='center'><strong>".$row['tracking']."</strong></td>";
					echo "<td align='center'><strong>".$row['cpitem']."</strong></td>";
					echo "<td align='center'>".$row['delivery_traking']."</td>";
					echo "<td>".$row['soldto1']."</td>";
						
						if($row['estado_orden']=='Active'){
						echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/edit.png" heigth="30" value="" title = "Modificar orden" width="20" onclick = "modificar('.$row['id_orden_detalle'].')"/></td>';
            //echo '<td align="center"><a href="ordenes_error.php"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/edit.png" heigth="30" value="" title = "Modificar orden" width="20"</a></td>';
            //echo '<td align="center"><a href="ordenes_error.php">aa</a></td>';
						}else{
					    	echo '<td align="center"><input disabled="true" type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../images/edit.png" heigth="30" value="" width="20"/></td>';
						}
						
						if($row['estado_orden']=='Active'){
						  echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/activar.png" heigth="30" value="" title = "Cancelar orden" width="20" onclick = "cancelar('.$row['id_orden_detalle'].')"/></td>';
						  }else{
							 echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/activar.png" heigth="30" value="" title = "Activar orden" width="20" onclick = "cancelar('.$row['id_orden_detalle'].')"/></td>';
					    }
							echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/delete.png" heigth="30" value="" title = "Eliminar orden" width="20" onclick = "eliminar('.$row['id_orden_detalle'].')"/></td>';
              if($row['tracking']!=''){
                echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/eliminar_tracking.png" heigth="30" value="" data-toggle="tooltip" data-placement="top" title = "Eliminar Tracking" width="20" onclick = "eliminar_tracking('.$row['id_orden_detalle'].')"/></td>';
              }
						  
						echo "</tr>";
					 }
							echo "<tr><td></td></tr>
								  <tr>
								  <td align='right'><strong>".$cant. "</strong></td>
								  <td>Órden(es) encontradas</td>
								  </tr>";						
			   }
			   mysqli_close($conection);
			   
			   //Preparando los datos para el reporte
			   $_SESSION["titulo"] ="Listado de Ordenes";
			   $_SESSION["columnas"] = array("Estado","Reenvío","Ponumber","Custnumber","Tracking","Item","Deliver","Bill To"); 
			   $_SESSION["consulta"] =   "SELECT estado_orden,reenvio, Ponumber, Custnumber, tracking, cpitem, delivery_traking,soldto1
						FROM
						tblorden
						INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
						INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
						WHERE Ponumber = '".$id."' order by Ponumber";
			   $_SESSION["nombre_fichero"] = "Listado de Ordenes.xlsx";
		  }
	  }
		 
  }else{
  //if(isset($_POST['buscar'])){
	  
	  //recoger datos de busqueda
	  $ponumber = $_POST['ponumber'];
	  $fecha1   = $_POST['txtinicio'];
	  $fecha2   = $_POST['txtfin'];
	  
	  //verificar que campos tiene valor para buscar
	  if($ponumber != ''){
		  $sql =   "SELECT id_orden_detalle,estado_orden,Ponumber, Custnumber, tracking, cpitem, delivery_traking, reenvio, soldto1
					FROM
					tblorden
					INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
					INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
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
					echo "<td align='center'><strong>".$row['Ponumber']."</strong></td>";
					echo "<td align='center'><strong>".$row['Custnumber']."</strong></td>";
					echo "<td align='center'><strong>".$row['tracking']."</strong></td>";
					echo "<td align='center'><strong>".$row['cpitem']."</strong></td>";
					echo "<td align='center'>".$row['delivery_traking']."</td>";
					echo "<td>".$row['soldto1']."</td>";
					
					if($row['estado_orden']=='Active'){
						echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/edit.png" heigth="30" value="" data-toggle="tooltip" data-placement="top" title = "Modificar orden" width="20" onclick = "modificar('.$row['id_orden_detalle'].')"/></td>';
						}else{
					    	echo '<td align="center"><input disabled="true" type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../images/edit.png" heigth="30" value="" width="20"/></td>';
						}
						
					if($row['estado_orden']=='Active'){
						echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/activar.png" heigth="30" value="" data-toggle="tooltip" data-placement="top" title = "Cancelar orden" width="20" onclick = "cancelar('.$row['id_orden_detalle'].')"/></td>';
						}else{
							echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/activar.png" heigth="30" value="" title = "Activar orden" width="20" onclick = "cancelar('.$row['id_orden_detalle'].')"/></td>';
					         }

						echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/delete.png" heigth="30" value="" data-toggle="tooltip" data-placement="top" title = "Eliminar orden" width="20" onclick = "eliminar('.$row['id_orden_detalle'].')"/></td>';
						if($row['tracking']!=''){
              echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/eliminar_tracking.png" heigth="30" value="" data-toggle="tooltip" data-placement="top" title = "Eliminar Tracking" width="20" onclick = "eliminar_tracking('.$row['id_orden_detalle'].')"/></td>';
						}
					echo "</tr>";
			 }
						echo "<tr>
							  <td align='right'><strong>".$cant. "</strong></td>
							  <td>Órden(es) encontradas</td>
							  </tr>";						
		   }
		   mysqli_close($conection);
		   
		   //Preparando los datos para el reporte
		   $_SESSION["titulo"] ="Listado de Ordenes";
		   $_SESSION["columnas"] = array("Estado","Reenvío","Ponumber","Custnumber","Tracking","Item","Deliver","Bill To"); 
		   $_SESSION["consulta"] =   "SELECT estado_orden, reenvio, Ponumber, Custnumber, tracking, cpitem, delivery_traking, soldto1
					FROM
					tblorden
					INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
					INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
				  WHERE Ponumber = '".$ponumber."'";
		   $_SESSION["nombre_fichero"] = "Listado de Ordenes.xlsx";
		  
	  }else{
		//verifico que las fechas tengan valor  
		if($fecha1 != '' && $fecha2 != ''){
		  $sql =   "SELECT id_orden_detalle,estado_orden,Ponumber, Custnumber,tracking,  cpitem, delivery_traking, reenvio, soldto1
					FROM
					tblorden
					INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
					INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
				  WHERE delivery_traking BETWEEN '".$fecha1."' AND '".$fecha2."' order by Ponumber";
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
					echo "<td align='center'><strong>".$row['Ponumber']."</strong></td>";
					echo "<td align='center'><strong>".$row['Custnumber']."</strong></td>";
					echo "<td align='center'><strong>".$row['tracking']."</strong></td>";
					echo "<td align='center'><strong>".$row['cpitem']."</strong></td>";
					echo "<td align='center'>".$row['delivery_traking']."</td>";
					echo "<td>".$row['soldto1']."</td>";
					
					if($row['estado_orden']=='Active'){
						echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/edit.png" heigth="30" value="" data-toggle="tooltip" data-placement="top" title = "Modificar orden" width="20" onclick = "modificar('.$row['id_orden_detalle'].')"/></td>';
						}else{
					    	echo '<td align="center"><input disabled="true" type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../images/edit.png" heigth="30" value="" width="20"/></td>';
						}
					if($row['estado_orden']=='Active'){
						echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/activar.png" heigth="30" value="" data-toggle="tooltip" data-placement="top"  title = "Cancelar orden" width="20" onclick = "cancelar('.$row['id_orden_detalle'].')"/></td>';
						}else{
							echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/activar.png" heigth="30" value="" title = "Activar orden" width="20" onclick = "cancelar('.$row['id_orden_detalle'].')"/></td>';
					         }
							echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/delete.png" heigth="30" value="" data-toggle="tooltip" data-placement="top" title = "Eliminar orden" width="20" onclick = "eliminar('.$row['id_orden_detalle'].')"/></td>';
              
              if($row['tracking']!=''){
                echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/eliminar_tracking.png" heigth="30" value="" data-toggle="tooltip" data-placement="top" title = "Eliminar Tracking" width="20" onclick = "eliminar_tracking('.$row['id_orden_detalle'].')"/></td>';
              }
			 }
						echo "<tr>
							  <td align='right'><strong>".$cant. "</strong></td>
							  <td>Órden(es) encontradas</td>
							  </tr>";						
		   }
		   mysqli_close($conection);	
		   
		   //Preparando los datos para el reporte
		   $_SESSION["titulo"] ="Listado de Ordenes";
		   $_SESSION["columnas"] = array("Estado","Reenvío","Ponumber","Custnumber","Tracking","Item","Deliver","Bill To"); 
		   $_SESSION["consulta"] =   "SELECT estado_orden,reenvio,Ponumber,Custnumber,tracking,cpitem, delivery_traking,soldto1
					FROM
					tblorden
					INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
					INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
				  WHERE delivery_traking BETWEEN '".$fecha1."' AND '".$fecha2."' order by Ponumber";
		   $_SESSION["nombre_fichero"] = "Listado de Ordenes.xlsx";
		}
	  }
  }
  ?>
    </tbody>
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

