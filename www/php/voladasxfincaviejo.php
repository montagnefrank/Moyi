<?php
session_start();
include ("conectarSQL.php");
include ("conexion.php");
require_once('barcode.inc.php');
include ("seguridad.php");

$user     =  $_SESSION["login"];
$passwd   =  $_SESSION["passwd"];
$bd       =  $_SESSION["bd"];
$rol      =  $_SESSION["rol"];

$conection = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cajas Voladas</title>
<link rel="icon" type="image/png" href="../images/favicon.ico" />
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
        <li>
                        <a tabindex="0" data-toggle="dropdown">
                          <strong>Etiquetas</strong><span class="caret"></span>
                        </a>
                         <ul class="dropdown-menu" role="menu">
                              <li><a href="etiqxfincas.php"><strong>Imprimir etiquetas por fincas</strong></a></li>
                            <li class="divider"></li>
                            <li><a href="etiquetasexistentes.php"><strong>Solicitudes Pendientes</strong></a></li>
                      </ul>
        </li>
        

        <li  class="active">
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
                     
                     </ul> 
           </li>
                    <li><a href="modificar_guia.php" ><strong>Editar Guías</strong></a></li>   
                    <li><a href="closeday.php" ><strong>Cerrar Día</strong></a></li>

        
		<?php
               if($rol == 4){  
					$sql   = "SELECT id_usuario from tblusuario where cpuser='".$user."'";
					$query = mysql_query($sql,$conection);
					$row = mysql_fetch_array($query);
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
<form method="post" id="frm1" name="frm1">
<div class="table-responsive">
<table width="50%" border="0" align="center" class="table table-striped" >
<tr>
       <td  colspan="8" align="center"><h3><strong>CAJAS VOLADAS POR FINCAS</strong></h3></td>
</tr>
<tr>
   <td colspan="5" align="center">  
      
   <div class="col-md-3">
   <label>Fecha Inicio:</label>
   <div class="input-group date" style="width: auto;" id="datetimepicker">
       <input type='text' class="form-control" name="txtinicio" id="txtinicio" value="<?php echo date ('Y-m-d')?>"/>
       <span class="input-group-addon">
           <span class="glyphicon glyphicon-calendar"></span>
       </span>
   </div>
   <script type="text/javascript">
       $(function () {
           $('#datetimepicker').datetimepicker({
               format: 'YYYY-MM-DD',
               showTodayButton:true
           });
       });
   </script>
   </div>

   <div class="col-md-3">
   <label>Fecha Inicio:</label>
   <div class="input-group date" style="width: auto;" id="datetimepicker1">
       <input type='text' class="form-control" name="txtfin" id="txtfin" value="<?php echo date ('Y-m-d')?>"/>
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
   
    <div class="col-md-3">
       <label>Finca:</label>
       <select type="text" name="finca" id="finca" class="form-control">
         <?php 
         //Consulto la bd para obtener solo los id de item existentes
         $sql   = "SELECT nombre FROM tblfinca order by nombre";
         $query = mysql_query($sql,$conection);
               //Recorrer los iteme para mostrar
               echo '<option value=""></option>'; 
               while($row1 = mysql_fetch_array($query)){
                  echo '<option value="'.$row1["nombre"].'">'.$row1["nombre"].'</option>'; 
               }
         ?>                       
       </select>
    </div>
   <div class="col-md-2">
       </br>
     <input type="submit" name="buscar" class = "btn btn-primary"id="buscar" value="Buscar" onclick="return Compara(this.form)"/>
     <input type="hidden" name = "id" value="<?php echo $_GET['id'];?>"/>
      
  </div>      
   </td>
  </tr>
</table>
</div>

<div class="table-responsive">
<table width="50%" border="0" align="center" class="table table-striped" > 
  <tr>
		<?php 
		if($_POST['buscar']){
			 $fecha1 = $_POST['txtinicio'];
			 $fecha2 = $_POST['txtfin'];
			 $finca  = $_POST['finca'];
				if($fecha1 !='' && $fecha2 != '' && $finca == ''){ //Si eligio las fechas
				echo '<td align="center" colspan="7"><h3><strong>Cajas voladas desde el día </strong><font color="#FF0000">';
				echo $_POST['txtinicio'].'</font> hasta el día <font color="#FF0000">'.$_POST['txtfin'];
				echo '</font> organizadas por código</h3></td>';
				echo '<td align="right">
							<input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/excel.png" heigth="40" value="" title = "Exportar Reporte Excel" width="30" formaction = "crearReportExcelVol.php"/>
						</td>';	
				}else{
					echo '<td align="center" colspan="7"><h3><strong>Cajas voladas de la finca <font color="#FF0000">'.$finca.'</font></strong>';
					echo' organizadas por codigo</h3></td>';
					echo '<td width="153" align="right">
								<input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/excel.png" heigth="40" value="" title = "Exportar Reporte Excel" width="30" formaction = "crearReportExcel.php"/>
							</td>';						
					}			 
			}
		?>
  </tr>
  <?php
if($_POST['buscar']){
 //Si se oprimio el boton buscar
	 $fecha1 = $_POST['txtinicio'];
	 $fecha2 = $_POST['txtfin'];
	 $finca  = $_POST['finca'];
	 
	if($fecha1 !='' && $fecha2 != '' && $finca == ''){ //Si eligio las fechas
	  $totalfinca;
	  $total;
	  //Primero consulta las diferentes fincas
	  $sql = "SELECT DISTINCT finca FROM tblcoldroom WHERE fecha_vuelo BETWEEN '".$fecha1."' AND '".$fecha2."' AND tracking_asig != '' ORDER BY finca";
	  $query = mysql_query($sql,$conection) or die ('Error en la conexión');
	  $cant = mysql_num_rows($query);
		  
		if($cant==0){
			  echo '<td><strong>No hay envíos de cajas en el período seleccionado</strong></td>';		  
		}else{
			//Por cada finca recorro cada caja enviada
			while($row = mysql_fetch_array($query)){
				//Si hay mas de una finca entonces se construye el reporte por fincas
				  echo '<tr>
					<td colspan="8"><strong>Finca: <font color="#0000FF">'.$row["finca"].'</font></strong></td>
				  </tr>';
				  
				  //Consulto la bd con la finca y poder extraer las cajas enviadas  
				   $sql1 = "SELECT * FROM  tblcoldroom where finca = '".$row['finca']."' AND fecha_vuelo BETWEEN '".$fecha1."' AND '".$fecha2."' AND tracking_asig != '' order by codigo";
				   $query1 = mysql_query($sql1,$conection) or die('Error en la consulta');
				   $cant1 = mysql_num_rows($query1);
				   if($cant==0){
						  echo '<td><strong>No hay envíos de cajas en el período seleccionado para la finca '.$row['finca'].'</strong></td>';		  
				    }else{
						  //Si hay entreadas para ese item en el cuarto frio los imprimo
						  echo '<tr bgcolor="#E8F1FD">
							<td align="center"><strong>Código</strong></td>
							<td align="center"><strong>Producto</strong></td></strong>
							<td align="center"><strong>Fecha Vuelo</strong></td>
							<td align="center"><strong>Guía Madre</strong></td>
							<td align="center"><strong>Guía Hija</strong></td>
							<td align="center"><strong>Tracking</strong></td>
							<td align="center"><strong>Servicio</strong></td>
							<td align="center"><strong>Línea Vuelo</strong></td>
						  </tr>';
						  
						  while($row1 = mysql_fetch_array($query1)){
							  echo "<tr>";
								 echo "<td align='center'>".$row1['codigo']."</td>";
								 echo "<td align='center'>".$row1['item']."</td>";	
								 echo "<td align='center'>".$row1['fecha_vuelo']."</td>";
								 echo "<td align='center'>".$row1['guia_madre']."</td>";
								 echo "<td align='center'>".$row1['guia_hija']."</td>";	
								 echo "<td align='center'>".$row1['tracking_asig']."</td>";	
								 echo "<td align='center'>".$row1['servicio']."</td>";
								 echo "<td align='center'>".$row1['airline']."</td>";											 
								 $totalfinca++;	
							 echo "<tr>";				  
						  }//fin while	
							echo "<tr>";
								echo "<td align='right' colspan='2'><strong>Total por Finca:</strong></td>";
								echo "<td align='center'><strong>".$totalfinca."</strong></td>";
							echo "</tr>";
							$total += $totalfinca;
							$totalfinca = 0;			  
					  }//fin else			  
			   }//fin while
			}//fin else 
		echo "<tr>";
			echo "<td align='right' colspan='2'><strong>Total General:</strong></td>";
			echo "<td align='center'><strong>".$total."</strong></td>";
		echo "</tr>"; 
		
		mysql_close($conection); 
		
		//Preparando los datos para el reporte
		$_SESSION["titulo"]   ="Cajas voladas desde ".$fecha1." hasta ".$fecha2."por código";
		$_SESSION["columnas"] = array("Código","Producto","Producto","Fecha Vuelo","Guía Madre","Guía Hija","Tracking","Servicio","Línea Vuelo"); 
		$_SESSION["filtro"]   = "Finca: "; 
		$_SESSION["consulta1"] =   $sql;		
		$_SESSION["consulta2"] = "SELECT codigo,item,fecha_vuelo,guia_madre,guia_hija,tracking_asig,servicio,airline FROM  tblcoldroom where fecha BETWEEN '".$fecha1."' AND '".$fecha2."' AND tracking_asig != ''";	      
		$_SESSION["nombre_fichero"] = "Cajas Voladas(por codigo).xlsx";
			    
	}else{
		 //Si eligio una finca
		//Consulto la bd con la finca y poder extraer las cajas enviadas  
				   $sql = "SELECT * FROM  tblcoldroom where finca = '".$finca."' AND tracking_asig != '' order by codigo";
				   $query = mysql_query($sql,$conection) or die('Error en la consulta');
				   $cant = mysql_num_rows($query);
				   if($cant==0){
						  echo '<td><strong>No hay envíos de cajas en el período seleccionado para la finca '.$finca.'</strong></td>';		  
				    }else{
						  //Si hay entreadas para ese item en el cuarto frio los imprimo
						  echo '<tr>
								<td colspan="8"><strong>Finca: <font color="#0000FF">'.$finca.'</font></strong></td>
						  </tr>';
						  echo '<tr bgcolor="#E8F1FD">
							<td align="center"><strong>Código</strong></td>
							<td align="center"><strong>Producto</strong></td></strong>
							<td align="center"><strong>Fecha Vuelo</strong></td>
							<td align="center"><strong>Guía Madre</strong></td>
							<td align="center"><strong>Guía Hija</strong></td>
							<td align="center"><strong>Tracking</strong></td>
							<td align="center"><strong>Servicio</strong></td>
							<td align="center"><strong>Línea Vuelo</strong></td>
						  </tr>';
						  
						  while($row = mysql_fetch_array($query)){
							  echo "<tr>";
								 echo "<td align='center'>".$row['codigo']."</td>";
								 echo "<td align='center'>".$row['item']."</td>";	
								 echo "<td align='center'>".$row['fecha_vuelo']."</td>";
								 echo "<td align='center'>".$row['guia_madre']."</td>";
								 echo "<td align='center'>".$row['guia_hija']."</td>";	
								 echo "<td align='center'>".$row['tracking_asig']."</td>";	
								 echo "<td align='center'>".$row['servicio']."</td>";
								 echo "<td align='center'>".$row['airline']."</td>";											 
								 $totalfinca++;	
							 echo "<tr>";				  
						  }//fin while	
							echo "<tr>";
								echo "<td align='right' colspan='2'><strong>Total por Finca:</strong></td>";
								echo "<td align='center'><strong>".$totalfinca."</strong></td>";
							echo "</tr>";
							$total += $totalfinca;
							$totalfinca = 0;			  
					  }//fin else
					  			   
		mysql_close($conection);
		//Preparando los datos para el reporte
		$_SESSION["titulo"] ="Cajas voladas de la finca ".$finca." organizadas por código";
		$_SESSION["columnas"] = array("Código","Producto","Fecha Vuelo","Guía Madre","Guía Hija","Tracking","Servicio","Línea Vuelo"); 
	    $_SESSION["consulta"] =   "SELECT codigo,item,fecha_vuelo,guia_madre,guia_hija,tracking_asig,servicio,airline FROM  tblcoldroom where finca = '".$finca."' AND tracking_asig != '' order by codigo";
		$_SESSION["nombre_fichero"] = "Cajas Voladas.xlsx";
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
<span id="top-link-block" class="hidden">
    <a href="#top" class="well well-sm"  onclick="$('html,body').animate({scrollTop:0},'slow');return false;">
        <i class="glyphicon glyphicon-chevron-up"></i> Ir arriba
    </a>
</span><!-- /top-link-block --> 
</div> <!-- /container -->
</body>
</html>