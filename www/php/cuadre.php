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

/******************************************************/
						 /*   Codigo   Descripcion
								*CS		Creditos por Calidad Ortorgado por Customer Service
								*RV		Credit por Calidad devuelto a Costco
								*RET	Holiday Retainer
								*DFP	Publicidad
								*QKPY	Creditos por Calidad Ortorgado por Customer Service
								*CK		Esto es una venta.  Son creditos que se descontaron y nosotros les negamos y ellos nos pagan como un monto total
/*******************************************************/
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pagos</title>
<link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" media="all"  />

<script language="javascript" src="../js/imprimir.js"></script>
<script type="text/javascript" src="../js/script.js"></script>
<script src="../bootstrap/js/jquery.min.js"></script>
<script src="../bootstrap/js/moment.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../bootstrap/js/bootstrap-datetimepicker.min.js"></script>
<script src="../bootstrap/js/bootstrap-submenu.js"></script>
<script src="../bootstrap/js/bootstrap-modal.js"></script>
<script src="../bootstrap/js/docs.js" defer></script>


	<script language="javascript">
	  function Compara(frmFec)
	{
		var cliente = document.getElementById('cliente').value;
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
		
			 
		  if(cliente =='' && fecha1 =='' && fecha2 =='' )
			{
			  alert("Las fechas y el cliente no pueden ser vacías. Tiene que tener algún valor para buscar");
			  return false;
			 }	
			 
			if(Fecha_Inicio > Fecha_Fin)
			{
			  alert("La fecha de inicio es mayor que la fecha de fin; Introduzca un período válido");
			  return false;
			 }
			else
			{
			  return true;
			 }
	}
	</script>
	<style>
		.modificar 
		{background-image: url(../images/edit.jpg);}
		.eliminar 
		{background-image: url(../images/delete.jpg);}
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
        <a class="navbar-brand" href="administration.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
         
      </div>



  <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
 <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
        <li class="dropdown">
          <a tabindex="0" data-toggle="dropdown">
            <strong>Pagos</strong><span class="caret"></span>
          </a>
          <ul class="dropdown-menu" role="menu">
              <li><a href="subircostco.php"><strong>Subir Archivo de Costo</strong></a></li>
              <li class="divider"></li>
              <li><a href="pagosycreditos.php"><strong>Pagos y Créditos Manuales</strong></a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a tabindex="0" data-toggle="dropdown">
            <strong>Reportes</strong><span class="caret"></span>     
          </a>
          <ul class="dropdown-menu" role="menu">
            <li class="dropdown-submenu">
              <a tabindex="0" data-toggle="dropdown"><strong>Reportes Manifest Costco</strong></a>            
              <ul class="dropdown-menu">                               
                  <li><a href="manifest.php">Reporte Manifest Costco</a></li>
                  <li class="divider"></li>
                  <li><a href="manifestfull.php">Reporte Manifest Costco Completo</a></li>
              </ul>
            </li>
            <li class="divider"></li>
            <li class="dropdown-submenu">
              <a tabindex="0" data-toggle="dropdown"><strong>Ventas</strong></a>            
              <ul class="dropdown-menu">                               
                  <li><a href="venta.php?id=1">Total Vendidos</a></li>
                  <li class="divider"></li>
                  <li><a href="venta.php?id=2">Créditos</a></li>
                  <li class="divider"></li>
                  <li><a href="venta.php?id=3">Neto Vendidos</a></li>
              </ul>
            </li>
            <li class="divider"></li>
            <li class="dropdown-submenu">
              <a tabindex="0" data-toggle="dropdown"><strong>Pagos</strong></a>            
              <ul class="dropdown-menu">                               
                  <li><a href="pagos.php">Pagos por Costco</a></li>
                  <li class="divider"></li>
                  <li><a href="cuadre.php">Cuadre de pagos</a></li>
              </ul>
            </li>
          </ul>
        </li>

       
    <?php
          if($rol == 4){  
            $sql   = "SELECT id_usuario from tblusuario where cpuser='".$user."'";
            $query = mysqli_query($link,$sql);
            $row = mysqli_fetch_array($query);
            echo '<li><a href="" onclick="cambiar(\''.$row[0].'\')"><strong>Contraseña</strong></a>'; 
           }
    ?> 
    </ul>  <!--Fin del navbar -->

      <ul class="nav navbar-nav navbar-right">
        <li><a class="navbar-brand" href="" data-toggle="tooltip" data-placement="bottom" title="Usuario conectado"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo $user?></a></li>
        <li><a class="navbar-brand" href="../index.php" data-toggle="tooltip" data-placement="bottom" title="Salir del sistema" ><span class="glyphicon glyphicon-off" aria-hidden="true"></span></a></li>
      </ul>
  </div>
</nav>
</div>

<div class="panel-body panel">
<div class="table-responsive">
<form method="post" id="frm1" name="frm1">
<table width="50%" border="0" align="center" class="table table-responsive">
<tr>
    <td  colspan="5" align="center">
        <h3><strong>REPORTE DE CUADRE DE PAGOS</strong></h3>
    </td>
  </tr>
  	
  <tr>
    <td>
        <div class="col-mdd-1">
          <label>Fecha Inicio:</label>
        </div>
        <div class="col-md-2">
        <div class="input-group date"  id="datetimepicker">
          <input type='text' class="form-control" name="txtinicio" id="txtinicio" value=""/>
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
       <div class="col-mdd-1">
        <label>Fecha Fin:</label>
      </div>
      <div class="col-md-2">
        <div class="input-group date"  id="datetimepicker1">
          <input type='text' class="form-control" name="txtfin" id="txtfin" value=""/>
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
      <div class="col-mdd-1">
        <label>Cliente:</label>
      </div>         
        <div class="col-md-3">
            <select name="cliente" id="cliente" class="form-control">
                  <?php
				  //Mostrar lista de checkes leidos
				  $sql   = "SELECT empresa, codigo FROM tblcliente order by codigo";
				  $query = mysqli_query($link,$sql) or die ("Error consultando los checkes");
				  echo "<option value='1' selected='selected'>Todos</option>";
				  while ($row = mysqli_fetch_array($query)){
				  		echo "<option value='".$row['codigo']."'>".$row['codigo']."- ".$row['empresa']."</option>";				  
				  }
				  ?>
                  </select>  
        </div>       
        <div class="col-mdd-1">
            <input type="submit" name="buscar" id="buscar" value="Buscar" onclick="return Compara(this.form)" class="btn btn-primary" />
        </div>        
 </tr>
</table>
</form>
   
  
<div class="table-responsive">
  <table width="50%" border="0" align="center" class="table table-responsive">

		<?php
		if($_POST['buscar']){
			
			//Obteniendo los datos de los filtros
			$fecha1 = $_POST['txtinicio'];
			$fecha2 = $_POST['txtfin'];
			$cliente = $_POST['cliente'];
			
			//Si se filtro por fecha
			if($fecha1 != '' && $fecha2!= '' && $cliente==1){
				
				echo '<tr>';
					echo '<td align="center" valign="top">';
					//Esta es la tabla de las ordenes canceladas de la base 
					    echo "<h3>ÓRDENES CANCELADAS <font color='#FF0000'>(".$fecha1."/".$fecha2.")</font></h3>";
						echo "<table width='100%' border='1' align='center'> ";
						//Consultar las canceladas en la fecha dada
						$sql   = "SELECT * FROM tbldetalle_orden where estado_orden = 'Canceled' AND delivery_traking BETWEEN '".$fecha1."' AND '".$fecha2."' order by Ponumber";
						$query = mysqli_query($link,$sql) or die ("Error al consultar las canceladas");
						echo "<tr>";
							echo '<td align="center">#</td>';
							echo '<td align="center">Cliente</td>';
							echo '<td align="center">Ponumber</td>';
							echo '<td align="center">Costo</td>';
 						echo "</tr>";
						$cont  = 0;
						$total = 0;
						while($row = mysqli_fetch_array($query)){
							$cont++;	
							echo "<tr>";
								echo '<td align="center">'.$cont.'</td>';
								echo '<td align="center">'.$row['vendor'].'</td>';
								echo '<td align="center">'.$row['Ponumber'].'</td>';
								echo '<td align="center">'.-$row['unitprice'].'</td>';		
							echo "</tr>";
							$total += -$row['unitprice'];
						}
						echo "<tr>";
								echo '<td colspan="3" align="right"><strong>Total:</strong></td>';
								echo '<td align="center"><strong>'.number_format($total,2).'</strong></td>';		
							echo "</tr>";
							
						echo "</table>";
					echo '</td>';
					
					// Esta es la tabla de los datos de los archivos 
					echo '<td align="center" valign="top">';
						//Esta es la tabla de las ordenes canceladas de la base 
					    echo "<h3>RETENCIÓN POR FIESTA <font color='#FF0000'>(".$fecha1."/".$fecha2.")</font></h3>";
						echo "<table width='100%' border='1' align='center'> ";
						//Consultar las canceladas en la fecha dada
						$sql   = "SELECT * FROM tblpagos where invoiceDate BETWEEN '".$fecha1."' AND '".$fecha2."'  AND `invoice#` LIKE 'RET%' order by `po#`";
						$query = mysqli_query($link,$sql) or die ("Error al consultar las canceladas");
						echo "<tr>";
							echo '<td align="center">#</td>';
							echo '<td align="center">Cheque</td>';
							echo '<td align="center">Nro</td>';
							echo '<td align="center">Ponumber</td>';
							echo '<td align="center">Total</td>';
							echo '<td align="center">Descuento</td>';
							echo '<td align="center">Neto</td>';
 						echo "</tr>";
						
						$cont  = 0;
						$total = 0;
						$descuento = 0;
						$neto = 0;
						while($row = mysqli_fetch_array($query)){
							$cont++;		
							echo "<tr>";
								echo '<td align="center">'.$cont.'</td>';
								echo '<td align="center">'.$row['check'].'</td>';
								echo '<td align="center">'.$row['invoice#'].'</td>';
								echo '<td align="center">'.$row['po#'].'</td>';
								echo '<td align="center">'.$row['total'].'</td>';	
								echo '<td align="center">'.$row['descuento'].'</td>';
								echo '<td align="center">'.$row['neto'].'</td>';	
								$total += $row['total'];
								$descuento += $row['descuento'];
								$neto += $row['neto'];
						}
						echo "<tr>";
								echo '<td colspan="4" align="right"><strong>Total:</strong></td>';
								echo '<td align="center"><strong>'.number_format($total,2).'</strong></td>';
								echo '<td align="center"><strong>'.number_format($descuento,2).'</strong></td>';
								echo '<td align="center"><strong>'.number_format($neto,2).'</strong></td>';		
							echo "</tr>";
						
						echo "</table>";
					echo '</td>';
				echo '</tr>';
				
				/*************************************************************************/
				echo '<tr>';
					echo '<td align="center" valign="top">';
					//Esta es la tabla de las ordenes canceladas de la base 
					 	echo "<h3>CRÉDITOS CUSTOM SERVICES <font color='#FF0000'>(".$fecha1."/".$fecha2.")</font></h3>";					    
						echo "<table width='100%' border='1' align='center'> ";
						//Consultar las canceladas en la fecha dada
						$sql   = "SELECT DISTINCT vendor,tblcustom_services.ponumber,credito FROM tblcustom_services INNER JOIN tbldetalle_orden ON tblcustom_services.ponumber = tbldetalle_orden.Ponumber where credito > 0 AND fecha BETWEEN '".$fecha1."' AND '".$fecha2."' order by tblcustom_services.ponumber";
						$query = mysqli_query($link,$sql) or die ("Error al consultar las canceladas");
						echo "<tr>";
							echo '<td  align="center">#</td>';
							echo '<td  align="center">Cliente</td>';
							echo '<td  align="center">Ponumber</td>';
							echo '<td  align="center">Crédito</td>';
 						echo "</tr>";
						$cont  = 0;
						$total = 0;
						while($row = mysqli_fetch_array($query)){		
							$cont++;	
							echo "<tr>";
								echo '<td align="center">'.$cont.'</td>';
								echo '<td align="center">'.$row['vendor'].'</td>';
								echo '<td  align="center">'.$row['ponumber'].'</td>';
								echo '<td  align="center">'.$row['credito'].'</td>';		
								$total += $row['credito'];
						}
						echo "<tr>";
								echo '<td colspan="3" align="right"><strong>Total:</strong></td>';
								echo '<td align="center"><strong>'.number_format($total,2).'</strong></td>';		
							echo "</tr>";
						
						echo "</table>";
					echo '</td>';
					
					// Esta es la tabla de los datos de los archivos 
					echo '<td align="center" valign="top">';
						//Esta es la tabla de las ordenes canceladas de la base 
					    echo "<h3>CRÉDITOS DE COSTCO <font color='#FF0000'>(".$fecha1."/".$fecha2.")</font></h3>";
						echo "<table width='100%' border='1' align='center'> ";
						//Consultar las canceladas en la fecha dada
						$sql   = "SELECT * FROM tblpagos where invoiceDate BETWEEN '".$fecha1."' AND '".$fecha2."'  AND (`invoice#` LIKE 'CS%' OR `invoice#` LIKE 'RV%' OR `invoice#` LIKE 'QKPY%') order by `po#`";
						$query = mysqli_query($link,$sql) or die ("Error al consultar las canceladas");
						echo "<tr>";
							echo '<td align="center">#</td>';
							echo '<td align="center">Cheque</td>';
							echo '<td align="center">Nro</td>';
							echo '<td align="center">Ponumber</td>';
							echo '<td align="center">Total</td>';
							echo '<td align="center">Descuento</td>';
							echo '<td align="center">Neto</td>';
 						echo "</tr>";
						
						$cont  = 0;
						$total = 0;
						$descuento = 0;
						$neto = 0;
						while($row = mysqli_fetch_array($query)){
							$cont++;		
							echo "<tr>";
								echo '<td align="center">'.$cont.'</td>';
								echo '<td align="center">'.$row['check'].'</td>';
								echo '<td align="center">'.$row['invoice#'].'</td>';
								echo '<td align="center">'.$row['po#'].'</td>';
								echo '<td align="center">'.$row['total'].'</td>';	
								echo '<td align="center">'.$row['descuento'].'</td>';
								echo '<td align="center">'.$row['neto'].'</td>';	
								$total += $row['total'];
								$descuento += $row['descuento'];
								$neto += $row['neto'];
						}
						echo "<tr>";
								echo '<td colspan="4" align="right"><strong>Total:</strong></td>';
								echo '<td align="center"><strong>'.number_format($total,2).'</strong></td>';
								echo '<td align="center"><strong>'.number_format($descuento,2).'</strong></td>';
								echo '<td align="center"><strong>'.number_format($neto,2).'</strong></td>';		
							echo "</tr>";
						
						echo "</table>";
					echo '</td>';
					
				echo '</tr>';
				/****************************************************************************************/
				echo '<tr>';
					echo '<td align="center" valign="top" colspan="2">';
					//Esta es la tabla de las ordenes canceladas de la base 
					 	echo "<h3>PO CON PAGOS CORRECTOS <font color='#FF0000'>(".$fecha1."/ ".$fecha2.")</font></h3>";					    
						echo "<table width='100%' border='1' align='center'> ";
						//Consultar las pagadas sin diferencias en la fecha dada
						$sql   = "SELECT vendor,po,costo FROM tblcosto INNER JOIN tbldetalle_orden ON tblcosto.po = tbldetalle_orden.Ponumber where credito = 0 AND fecha_facturacion BETWEEN  '".$fecha1."' AND '".$fecha2."' AND pagado='Si' AND EXISTS (SELECT * FROM tblpagos) order by po";
						$query = mysqli_query($link,$sql) or die ("Error al consultar las canceladas");
						
						echo "<tr>";
							echo '<td align="center">#</td>';
							echo '<td align="center">Cliente</td>';
							echo '<td align="center">Ponumber</td>';
							echo '<td align="center">Costo</td>';
 						echo "</tr>";
						$cont  = 0;
						$total = 0;
						while($row = mysqli_fetch_array($query)){
							 		
							$cont++;	
							echo "<tr>";
								echo '<td align="center">'.$cont.'</td>';
								echo '<td align="center">'.$row['vendor'].'</td>';
								echo '<td align="center">'.$row['po'].'</td>';
								echo '<td align="center">'.number_format($row['costo'],2).'</td>';	
							echo "</tr>";
								$total += $row['costo'];
						}
						echo "<tr>";
								echo '<td colspan="3" align="right"><strong>Total:</strong></td>';
								echo '<td align="center"><strong>'.number_format($total,2).'</strong></td>';		
							echo "</tr>";
						
						echo "</table>";
					echo '</td>';
					echo '</tr>';
					
/*************************************************************************/
					echo "<tr>";
					echo '<td align="center" valign="top" colspan="2">';
					//Esta es la tabla de las ordenes canceladas de la base 
					 	echo "<h3>PO SIN REGISTRO EN LA BASE <font color='#FF0000'>(".$fecha1."/ ".$fecha2.")</font></h3>";					    
						echo "<table width='100%' border='1' align='center'> ";
						//Consultar las canceladas en la fecha dada
						$sql   = "SELECT * FROM tblpagos left join tblcosto ON tblpagos.`po#` = tblcosto.po  Where tblcosto.po IS NULL AND (`invoice#` NOT LIKE 'CS%' AND `invoice#` NOT LIKE 'RV%' AND `invoice#` NOT LIKE 'QKPY%' AND `invoice#` NOT LIKE 'RET%' AND `invoice#` NOT LIKE 'DFP%' AND `invoice#` NOT LIKE 'CK%' ) AND  invoiceDate BETWEEN '".$fecha1."' AND '".$fecha2."' order by `po#`";
						$query = mysqli_query($link,$sql) or die ("Error al consultar las canceladas");
						echo "<tr>";
							echo '<td align="center">#</td>';
							echo '<td align="center">Cheque</td>';
							echo '<td align="center">Nro</td>';
							echo '<td align="center">Ponumber</td>';
							echo '<td align="center">Total</td>';
							echo '<td align="center">Descuento</td>';
							echo '<td align="center">Neto</td>';
 						echo "</tr>";
						
						$cont  = 0;
						$total = 0;
						$descuento = 0;
						$neto = 0;
						while($row = mysqli_fetch_array($query)){
							$cont++;		
							echo "<tr>";
								echo '<td align="center">'.$cont.'</td>';
								echo '<td align="center">'.$row['check'].'</td>';
								echo '<td align="center">'.$row['invoice#'].'</td>';
								echo '<td align="center">'.$row['po#'].'</td>';
								echo '<td align="center">'.$row['total'].'</td>';	
								echo '<td align="center">'.$row['descuento'].'</td>';
								echo '<td align="center">'.$row['neto'].'</td>';
							echo "</tr>";	
								$total += $row['total'];
								$descuento += $row['descuento'];
								$neto += $row['neto'];
						}
						echo "<tr>";
								echo '<td colspan="4" align="right"><strong>Total:</strong></td>';
								echo '<td align="center"><strong>'.number_format($total,2).'</strong></td>';
								echo '<td align="center"><strong>'.number_format($descuento,2).'</strong></td>';
								echo '<td align="center"><strong>'.number_format($neto,2).'</strong></td>';		
							echo "</tr>";
						
						echo "</table>";
					echo '</td>';
				echo '</tr>';
				
				/****************************************************************************/
				echo '<tr>';
					echo '<td align="center" valign="top">';
					//Esta es la tabla de las ordenes canceladas de la base 
					 	echo "<h3>PAGOS CON DIFERENCIAS <font color='#FF0000'>(".$fecha1."/".$fecha2.")</font></h3>";					    
						echo "<table width='100%' border='1' align='center'> ";
						//Consultar las canceladas en la fecha dada
						$sql   = "SELECT DISTINCT tbldetalle_orden.vendor,po,costo,credito,total FROM tblcosto INNER JOIN tbldetalle_orden ON tblcosto.po = tbldetalle_orden.Ponumber INNER JOIN tblpagos ON tblcosto.po = tblpagos.`po#` where credito != 0 AND fecha_facturacion BETWEEN '".$fecha1."' AND '".$fecha2."' AND pagado='Si' order by po";
						$query = mysqli_query($link,$sql) or die ("Error al consultar los pagos con diferencias");
						echo "<tr>";
							echo '<td align="center">#</td>';
							echo '<td align="center">Cliente</td>';
							echo '<td align="center">Ponumber</td>';
							echo '<td align="center">Costo</td>';
							echo '<td align="center">Pago</td>';
							echo '<td align="center">Diferencia</td>';
 						echo "</tr>";
						$cont  = 0;
						$credito = 0;
						$costo = 0;
						$total = 0;
						while($row = mysqli_fetch_array($query)){		
							$cont++;	
							echo "<tr>";
								echo '<td align="center">'.$cont.'</td>';
								echo '<td align="center">'.$row['vendor'].'</td>';
								echo '<td align="center">'.$row['po'].'</td>';
								echo '<td align="center">'.number_format($row['costo'],2).'</td>';
								echo '<td align="center">'.number_format($row['total'],2).'</td>';
								echo '<td align="center">'.number_format($row['credito'],2).'</td>';		
								
								$costo += $row['costo'];
								$total += $row['total'];
								$credito += $row['credito'];
							echo "</tr>";
						}
						echo "<tr>";
								echo '<td colspan="3" align="right"><strong>Total:</strong></td>';								
								echo '<td align="center"><strong>'.number_format($costo,2).'</strong></td>';
								echo '<td align="center"><strong>'.number_format($total,2).'</strong></td>';	
								echo '<td align="center"><strong>'.number_format($credito,2).'</strong></td>';		
							echo "</tr>";
						
						echo "</table>";
					echo '</td>';
					
					// Esta es la tabla de los datos de los archivos 
					echo '<td align="center" valign="top">';
						//Esta es la tabla de las ordenes canceladas de la base 
					    echo "<h3>PUBLICIDAD COSTCO <font color='#FF0000'>(".$fecha1."/".$fecha2.")</font></h3>";
						echo "<table width='100%' border='1' align='center'> ";
						//Consultar las canceladas en la fecha dada
						$sql   = "SELECT * FROM tblpagos where invoiceDate BETWEEN '".$fecha1."' AND '".$fecha2."'  AND `invoice#` LIKE 'DFP%' order by `po#`";
						$query = mysqli_query($link,$sql) or die ("Error al consultar las canceladas");
						echo "<tr>";
							echo '<td align="center">#</td>';
							echo '<td align="center">Cheque</td>';
							echo '<td align="center">Nro</td>';
							echo '<td align="center">Ponumber</td>';
							echo '<td align="center">Total</td>';
							echo '<td align="center">Descuento</td>';
							echo '<td align="center">Neto</td>';
 						echo "</tr>";
						
						$cont  = 0;
						$total = 0;
						$descuento = 0;
						$neto = 0;
						while($row = mysqli_fetch_array($query)){
							$cont++;		
							echo "<tr>";
								echo '<td align="center">'.$cont.'</td>';
								echo '<td align="center">'.$row['check'].'</td>';
								echo '<td align="center">'.$row['invoice#'].'</td>';
								echo '<td align="center">'.$row['po#'].'</td>';
								echo '<td align="center">'.$row['total'].'</td>';	
								echo '<td align="center">'.$row['descuento'].'</td>';
								echo '<td align="center">'.$row['neto'].'</td>';	
								$total += $row['total'];
								$descuento += $row['descuento'];
								$neto += $row['neto'];
						}
						echo "<tr>";
								echo '<td colspan="4" align="right"><strong>Total:</strong></td>';
								echo '<td align="center"><strong>'.number_format($total,2).'</strong></td>';
								echo '<td align="center"><strong>'.number_format($descuento,2).'</strong></td>';
								echo '<td align="center"><strong>'.number_format($neto,2).'</strong></td>';		
							echo "</tr>";
						
						echo "</table>";
					echo '</td>';
				echo '</tr>';
				
				/*************************************************************************/
				echo '<tr>';
					echo '<td align="center" valign="top">';
					//Esta es la tabla de las ordenes canceladas de la base 
					 	echo "<h3>PO CON FALTA DE PAGOS <font color='#FF0000'>(".$fecha1."/".$fecha2.")</font></h3>";					    
						echo "<table width='100%' border='1' align='center'> ";
						//Consultar las canceladas en la fecha dada
						$sql   = "SELECT DISTINCT vendor,po,costo FROM tblcosto INNER JOIN tbldetalle_orden ON tblcosto.po = tbldetalle_orden.Ponumber where fecha_facturacion BETWEEN '".$fecha1."' AND '".$fecha2."' AND pagado='No' order by po";
						$query = mysqli_query($link,$sql) or die ("Error al consultar las canceladas");
						echo "<tr>";
							echo '<td align="center">#</td>';
							echo '<td align="center">Cliente</td>';
							echo '<td align="center">Ponumber</td>';
							echo '<td align="center">Costo</td>';
 						echo "</tr>";
						$cont  = 0;
						$total = 0;
						while($row = mysqli_fetch_array($query)){		
							$cont++;	
							echo "<tr>";
								echo '<td align="center">'.$cont.'</td>';
								echo '<td align="center">'.$row['vendor'].'</td>';
								echo '<td align="center">'.$row['po'].'</td>';
								echo '<td align="center">'.number_format($row['costo'],2).'</td>';		
								$total += $row['costo'];
						}
						echo "<tr>";
								echo '<td colspan="3" align="right"><strong>Total:</strong></td>';
								echo '<td align="center"><strong>'.number_format($total,2).'</strong></td>';		
							echo "</tr>";
						
						echo "</table>";
					echo '</td>';
					// Esta es la tabla de los datos de los archivos 
					echo '<td align="center" valign="top">';
						//Esta es la tabla de las ordenes canceladas de la base 
					    echo "<h3>DESCONTADOS Y PAGADOS <font color='#FF0000'>(".$fecha1."/".$fecha2.")</font></h3>";
						echo "<table width='100%' border='1' align='center'> ";
						//Consultar las canceladas en la fecha dada
						$sql   = "SELECT * FROM tblpagos where invoiceDate BETWEEN '".$fecha1."' AND '".$fecha2."'  AND `invoice#` LIKE 'CK%' order by `po#`";
						$query = mysqli_query($link,$sql) or die ("Error al consultar las canceladas");
						echo "<tr>";
							echo '<td align="center">#</td>';
							echo '<td align="center">Cheque</td>';
							echo '<td align="center">Nro</td>';
							echo '<td align="center">Ponumber</td>';
							echo '<td align="center">Total</td>';
							echo '<td align="center">Descuento</td>';
							echo '<td align="center">Neto</td>';
 						echo "</tr>";
						
						$cont  = 0;
						$total = 0;
						$descuento = 0;
						$neto = 0;
						while($row = mysqli_fetch_array($query)){
							$cont++;		
							echo "<tr>";
								echo '<td align="center">'.$cont.'</td>';
								echo '<td align="center">'.$row['check'].'</td>';
								echo '<td align="center">'.$row['invoice#'].'</td>';
								echo '<td align="center">'.$row['po#'].'</td>';
								echo '<td align="center">'.$row['total'].'</td>';	
								echo '<td align="center">'.$row['descuento'].'</td>';
								echo '<td align="center">'.$row['neto'].'</td>';	
								$total += $row['total'];
								$descuento += $row['descuento'];
								$neto += $row['neto'];
						}
						echo "<tr>";
								echo '<td colspan="4" align="right"><strong>Total:</strong></td>';
								echo '<td align="center"><strong>'.number_format($total,2).'</strong></td>';
								echo '<td align="center"><strong>'.number_format($descuento,2).'</strong></td>';
								echo '<td align="center"><strong>'.number_format($neto,2).'</strong></td>';		
							echo "</tr>";
						
						echo "</table>";
					echo '</td>';
				echo '</tr>';
			}else{
				//Si se filtro por cliente
				//echo "Hola 2";
				}
		}
        ?>        
  </table>
</div>
 
</div>
</div>


	
<div class="panel-heading">
  <div class="contenedor" align="center">
    <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2015 versión 3</strong>
    <br>
    <strong><a href="http://www.bit-store.ec/index.php/contactenos/"  style="color:white">Info</a> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></strong>
  </div>
</div>
</div> <!-- panel panel-primary -->
</div>   <!-- /container -->

</body>
</html>

