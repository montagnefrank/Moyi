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
    var cheque = document.getElementById('Cheque').value;
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

          if(fecha1 =='' && fecha2 =='' && cheque =='')
                {
                  alert("Las fechas y el numero de cheque no pueden ser vacías. Tiene que tener algún valor para buscar");
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
</style>
<style>
    .contenedor {
         margin-left: 10px;
         width:100%;
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

<div class="panel-body">

 <form method="post" id="frm1" name="frm1" target="_parent" >
     <table width="1024" border="0" align="center" class=" table table-striped">
    <tr>
        <td  colspan="5" align="center">
            <h3><strong>REPORTE DE PAGOS DE COSTCO</strong></h3>
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
        <label>Cheque:</label>
      </div>
      <div class="col-md-3">
          <select name="Cheque" class="form-control" >
            <?php
              //Mostrar lista de checkes leidos
              $sql   = "SELECT DISTINCT `check`,vendor FROM tblpagos order by `check`";
              $query = mysqli_query($link,$sql) or die ("Error consultando los checkes");
              echo "<option value='' selected='selected'></option>";
              while ($row = mysqli_fetch_array($query)){
                            echo "<option value='".$row['check']."'>".$row['check']."- ".$row['vendor']."</option>";				  
              }
              ?>
        </select> 
      </div>
      
      <div class="col-mdd-1">
          <input type="submit" class="btn btn-primary" name="buscar" id="buscar" value="Buscar" onclick="return Compara(this.form)"/>
      </div>
</td>
</tr>
</table>
</form>
    
<table width="1024" border="0" align="center">
  <tr>
    <td id="inicio" bgcolor="" height="100"> 
    <table width="800" border="0" align="center"> 
  <tr>
		<?php 
	   if(isset ($_POST['buscar'])){
	   //Si se oprimio el boton de buscar
			  $fecha1 = $_POST['txtinicio'];
			  $fecha2 = $_POST['txtfin'];
			  $check = explode("-",$_POST['Cheque']);
			  $check = $check[0];
			  
			  //Se verifica que filtro se oprimio, para entonces crear el reporte
			  if($fecha1 != '' && $fecha2 != ''){ //Si se pidio rango de fecha se muestra todo lo pagado en ese rango de fecha y luego todo lo no pagado.
			  	echo '<td colspan="6" align="center"><h3><strong>Ebinv pagados desde </strong><font color="#FF0000">';
				echo $fecha1."<font color='#000000'> hasta </font>".$fecha2;
				echo '</font></h3></td>';
				
				//Agrupar el reporte por destino
			    $a = "SELECT distinct pagado FROM tblcosto WHERE fecha_facturacion BETWEEN '".$fecha1."' AND '".$fecha2."' order by pagado DESC";
			    $b = mysqli_query($link,$a) or die('Error seleccionando los pagos'); 
				$cant = mysqli_num_rows($b);
				
				if($cant == 0){
				 echo '<tr>
						<td align="center"><strong>No hay Pagos en este período</strong></td>
					 </tr>';
				}else{
					//Totales generales
					$CostoTotal = 0;
					$PagoTotal  = 0;
					$DifTotal   = 0; 
					$cantFTotal = 0;
				
					while($fila = mysqli_fetch_array($b)){
			  
						  echo '<tr bgcolor="#E8F1FD">
									<td align="center"><strong>Ebinv</strong></td>
									<td align="center"><strong>Ponumber</strong></td>
									<td align="center"><strong>Costo</strong></td>
									<td align="center"><strong>Pago</strong></td>
									<td align="center"><strong>Diferencia</strong></td>
									<td align="center"><strong>Pagado</strong></td>
									<td align="center"><strong>Fecha Entrega</strong></td>
								 </tr>';
						  //Leer los datos  de los pagos
						  $sql = "SELECT * FROM tblcosto where pagado = '".$fila['pagado']."' AND fecha_facturacion BETWEEN '".$fecha1."' AND '".$fecha2."' order by fecha_facturacion,ebinv";
						  $val = mysqli_query($link,$sql) or die ("Error consultando los pagos");
						  
						  //Subtotales
						  $CostoSubT = 0;
						  $PagoSubT  = 0;
						  $DifSubT   = 0;
						  $cantF     = 0;
						  
						  while($row1 = mysqli_fetch_array($val)){
							  echo '<tr>';
							  echo "<td align='center'>".$row1['ebinv']."</td>";
							  echo "<td align='center'>".$row1['po']."</td>";
							  echo "<td align='center'>".number_format($row1['costo'],2)."</td>";
							  echo "<td align='center'>".number_format($row1['pago'],2)."</td>";
							  echo "<td align='center'>".number_format($row1['credito'],2)."</td>";
							  echo "<td align='center'>".$row1['pagado']."</td>";
							  echo "<td align='center'>".$row1['fecha_facturacion']."</td>";
							  echo '<tr>';
							  
							  //Actualizando los subtotales
							  $CostoSubT += $row1['costo'];
							  $PagoSubT  += $row1['pago'];
							  $DifSubT   += $row1['credito'];
							  $cantF++;
						  }
						  
						    //Mostrando los subtotales
						    echo '<tr>';
							  echo "<td align='center'><strong>Cant: ".$cantF."</strong></td>";
							  echo "<td align='center'><strong>Subtotal:</strong></td>";
							  echo "<td align='center'><strong>".number_format($CostoSubT,2)."</strong></td>";
							  echo "<td align='center'><strong>".number_format($PagoSubT,2)."</strong></td>";
							  echo "<td align='center'><strong>".number_format($DifSubT,2)."</strong></td>";
						    echo '<tr>';
							echo '<tr><td> </td></tr>';
						 	echo '<tr><td> </td></tr>';
							echo '<tr><td> </td></tr>';
						 	echo '<tr><td> </td></tr>';
							
						   //Actualizando los Totales
						   $CostoTotal += $CostoSubT;
						   $PagoTotal  += $PagoSubT;
						   $DifTotal   += $DifSubT;
						   $cantFTotal += $cantF;
					
						  //Resetenado los Subtotales
						  $CostoSubT = 0;
						  $PagoSubT  = 0;
						  $DifSubT   = 0;
						  $cantF     = 0;
						
					}
					//Mostrando los Totales
						    echo '<tr>';
							  echo "<td align='center'><strong>Cant: ".$cantFTotal."</strong></td>";
							  echo "<td align='center'><strong>Total:</strong></td>";
							  echo "<td align='center'><strong>".number_format($CostoTotal,2)."</strong></td>";
							  echo "<td align='center'><strong>".number_format($PagoTotal,2)."</strong></td>";
							  echo "<td align='center'><strong>".number_format($DifTotal,2)."</strong></td>";
						    echo '<tr>';
							echo '<tr><td> </td></tr>';
						 	echo '<tr><td> </td></tr>';
				}
	
			  }else{
				  if($check != ''){
					     //Si selecciono el checke
						  echo '<td colspan="6" align="center"><h3><strong>Ebinv Pagados con el cheque </strong><font color="#FF0000"> '.$check.'</font>';
						  
						  //Obteniendo los datos generales de ese cheque
						  $sql1 = "SELECT DISTINCT `check`, vendor, company FROM tblpagos where `check` = '".$check."'";
						  $val1 = mysqli_query($link,$sql1) or die ("Error consultando los pagos");
						  $row = mysqli_fetch_array($val1);
						  
						  echo '<tr bgcolor="#0099FF">
											<td align="center" colspan="2"><strong>Cheque: '.$check.'</strong></td>
											<td align="center" colspan="2"><strong>Compañia: '.$row['company'].'</strong></td>
											<td align="center"colspan="3"><strong>Cliente: '.$row['vendor'].'</strong></td>
										 </tr>';
										 
						 //Obtener las cantidades por cada codigo de pago*/
						 /*   Codigo   Descripcion
								CS		Creditos por Calidad Ortorgado por Customer Service
								RV		Credit por Calidad devuelto a Costco
								RET		Holiday Retainer
								DFP		Publicidad
								QKPY	Creditos por Calidad Ortorgado por Customer Service
								CK		Esto es una venta.  Son creditos que se descontaron y nosotros les negamos y ellos nos pagan como un monto total
						/**********************************/
						//Seleccionando los CS 
						$SQLCS   = "SELECT SUM(neto) FROM tblpagos WHERE `invoice#` LIKE 'CS%' AND `check` = '".$check."'";
						$QUERYCS = mysqli_query($link,$SQLCS) or die ("Error seleccionando los creditos CS");
						$FILACS  = mysqli_fetch_array($QUERYCS);
						$totalCS = $FILACS[0];
						$totalCS = $totalCS?$totalCS:0;
						
						//Seleccionando los RV 
						$SQLRV   = "SELECT SUM(neto) FROM tblpagos WHERE `invoice#` LIKE 'RV%' AND `check` = '".$check."'";
						$QUERYRV = mysqli_query($link,$SQLRV) or die ("Error seleccionando los creditos RV");
						$FILARV  = mysqli_fetch_array($QUERYRV);
						$totalRV = $FILARV[0];
						$totalRV = $totalRV?$totalRV:0;
						
						//Seleccionando los RET 
						$SQLRET  = "SELECT SUM(neto) FROM tblpagos WHERE `invoice#` LIKE 'RET%' AND `check` = '".$check."'";
						$QUERYRET = mysqli_query($link,$SQLRET) or die ("Error seleccionando los creditos RET");
						$FILARET  = mysqli_fetch_array($QUERYRET);
						$totalRET = $FILARET[0];
						$totalRET = $totalRET?$totalRET:0;
						
						//Seleccionando los DFP 
						$SQLDFP  = "SELECT SUM(neto) FROM tblpagos WHERE `invoice#` LIKE 'DFP%' AND `check` = '".$check."'";
						$QUERYDFP = mysqli_query($link,$SQLDFP) or die ("Error seleccionando los creditos DFP");
						$FILADFP  = mysqli_fetch_array($QUERYDFP);
						$totalDFP = $FILADFP[0];
						$totalDFP = $totalDFP?$totalDFP:0;
						
						//Seleccionando los DFP 
						$SQLQKPY  = "SELECT SUM(neto) FROM tblpagos WHERE `invoice#` LIKE 'QKPY%' AND `check` = '".$check."'";
						$QUERYQKPY = mysqli_query($link,$SQLQKPY) or die ("Error seleccionando los creditos QKPY");
						$FILAQKPY  = mysqli_fetch_array($QUERYQKPY);
						$totalQKPY = $FILAQKPY[0];
						$totalQKPY = $totalQKPY?$totalQKPY:0;
						
						//Seleccionando los DFP 
						$SQLQCK  = "SELECT SUM(neto) FROM tblpagos WHERE `invoice#` LIKE 'QKPY%' AND `check` = '".$check."'";
						$QUERYCK = mysqli_query($link,$SQLQCK) or die ("Error seleccionando los creditos CK");
						$FILACK  = mysqli_fetch_array($QUERYQCK);
						$totalCK = $FILAQCK[0];
						$totalCK = $totalQCK?$totalQCK:0;
						
						echo '<tr bgcolor="#0099FF">
											<td align="center" colspan="2"><strong>Total CS: '.number_format($totalCS,2).'</strong></td>
											<td align="center" colspan="2"><strong>Total RV: '.number_format($totalRV,2).'</strong></td>
											<td align="center" colspan="3"><strong>Total RET: '.number_format($totalRET,2).'</strong></td>
										 </tr>';
						echo '<tr bgcolor="#0099FF">
											<td align="center" colspan="2"><strong>Total DFP: '.number_format($totalDFP,2).'</strong></td>
											<td align="center" colspan="2"><strong>Total QKPY: '.number_format($totalQKPY,2).'</strong></td>
											<td align="center" colspan="3"><strong>Total CK: '.number_format($totalCK,2).'</strong></td>
										 </tr>';
						

				
						  echo '<tr bgcolor="#E8F1FD">
											<td align="center"><strong># Factura</strong></td>
											<td align="center"><strong>Fecha Factura</strong></td>
											<td align="center"><strong>Cantidad Total</strong></td>
											<td align="center"><strong>Importe De Descuento</strong></td>
											<td align="center"><strong>Importe Neto</strong></td>
											<td align="center"><strong>Fecha De Vencimiento</strong></td>
											<td align="center"><strong>Ponumber</strong></td>
										 </tr>';
						//Leer los datos  de los pagos
						  $sql = "SELECT * FROM tblpagos where `check` = '".$check."'";
						  $val = mysqli_query($link,$sql) or die ("Error consultando los pagos");
						  
						  //Subtotales
						  $CostoT = 0;
						  $DescT  = 0;
						  $NetT   = 0;
						  $cantF     = 0;
						  
						  while($row1 = mysqli_fetch_array($val)){
							  echo '<tr>';
							  echo "<td align='center'>".$row1['invoice#']."</td>";
							  echo "<td align='center'>".$row1['invoiceDate']."</td>";
							  echo "<td align='center'>".number_format($row1['total'],2)."</td>";
							  echo "<td align='center'>".number_format($row1['descuento'],2)."</td>";
							  echo "<td align='center'>".number_format($row1['neto'],2)."</td>";
							  echo "<td align='center'>".$row1['dueDate']."</td>";
							  echo "<td align='center'>".$row1['po#']."</td>";
							  echo '<tr>';
							  
							  //Actualizando los subtotales
							  $CostoT += $row1['total'];
							  $DescT  += $row1['descuento'];
							  $NetT   += $row1['neto'];
							  $cantF++;
						  }
						  
						    //Mostrando los subtotales
						    echo '<tr>';
							  echo "<td align='center'><strong>Cant: ".$cantF."</strong></td>";
							  echo "<td align='center'><strong>Total:</strong></td>";
							  echo "<td align='center'><strong>".number_format($CostoT,2)."</strong></td>";
							  echo "<td align='center'><strong>".number_format($DescT,2)."</strong></td>";
							  echo "<td align='center'><strong>".number_format($NetT,2)."</strong></td>";
						    echo '<tr>';
							echo '<tr><td> </td></tr>';
						 	echo '<tr><td> </td></tr>';
							echo '<tr><td> </td></tr>';
						 	echo '<tr><td> </td></tr>';
					  
				  }else{
					   echo '<tr>
								<td align="center"><strong>No hay ningun filtro seleccionado, por favor seleccione uno.</strong></td>
					 		</tr>';					  
					  }				   
			  }
                   mysqli_close($conection);  
      }
           ?>
  </table>
  </td>
  </tr>

  </table>
</div>

<div class="panel-heading">
  <div class="contenedor" align="center">
    <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2015 versión 3</strong>
    <br>
    <strong><a href="http://www.bit-store.ec/index.php/contactenos/"  style="color:white">Info</a> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></strong>
  </div>
</div>

</div>   <!-- /primary -->
</div>
</body>
</html>

