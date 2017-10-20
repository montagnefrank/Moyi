<?php
    session_start();
	include ("conectarSQL.php");
    include ("conexion.php");
	include ("track.php");
	include ("seguridad.php");
	$user     =  $_SESSION["login"];
	$rol      =  $_SESSION["rol"];
	
	//hacer conexion
    $conection = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error()); 
	$id = $_GET['id'];
	$contador = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Atención al Cliente</title>

	<link href="../css/tooltip.css" rel="stylesheet" type="text/css" />
	<link rel="icon" type="image/png" href="../images/favicon.ico" />
	<link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
	<link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="../bootstrap/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
	<link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
	<link href="../bootstrap/css/octicons.css" rel="stylesheet" type="text/css">
	<link href="../bootstrap/css/zenburn.css" rel="stylesheet" type="text/css">
	<!-- <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"> -->

	<script src="../js/tooltip.js" type="text/javascript"></script>
	<script type="text/javascript" src="../js/jquery-1.2.1.pack.js"></script>
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
	     /*width:210%;*/
	     text-align:center;
	}

	.navbar-fixed-top + .content-container {
		margin-top: 70px;
	}
	.content-container {
		margin: 0 130px;
	}

	.table-responsive {
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

	.table-responsive>.table>thead>tr>th, 
	.table-responsive>.table>tbody>tr>th, 
	.table-responsive>.table>tfoot>tr>th, 
	.table-responsive>.table>thead>tr>td, 
	.table-responsive>.table>tbody>tr>td, 
	.table-responsive>.table>tfoot>tr>td {
	    white-space: nowrap;
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
	</style>

	<script type="text/javascript">
	function quejas(valor){
		var v=valor;
		window.open("quejas.php?codigo="+v,"Cantidad","width=505,height=390,top=50,left=300");
		return false;
	}
	function credito(valor){
		var v=valor;
		window.open("credito.php?codigo="+v,"Cantidad","width=550,height=700,top=10,left=400");
		return false;
	}

	function reenviar(valor,item,deliver){
		var v=valor;
		//var d= document.getElementById('deliver').innerHTML;
		//var i= document.getElementById('item').innerHTML;
		var i=item;
		var d=deliver;
		window.open("reenviar1.php?codigo="+v+"&deliver="+d+"&item="+i+"","Cantidad","width=300,height=150,top=350,left=400");
		return false;
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
<div class="contenedor">
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
<a class="navbar-brand" href="services.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
</div>
 
  <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
 <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
    	<li><a href="cust_services.php"><strong>Atención al Cliente</strong></a></li>     	<li><a href="report_cust.php"><strong>Reportes</strong></a></li>
		<?php
					if($rol == 6)
                     echo '<li><a href="filtros.php"><strong>Ver Órdenes</strong></a></li>';
                     if($rol == 1){  
			     	 //no muestra nada
					 }else{
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
<div class="panel-body" align="center">
<div class="table-responsive">
<form method="post" id="frm1" name="frm1" target="_parent" >
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-3">
            <h3><strong>BUSCAR CLIENTE</strong></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1">
            <strong>Ponumber:</strong>
        </div>
        <div class="col-md-1">
            <input name="ponumber" type="text" class="form-control"  id="ponumber" onKeyPress="return validar_texto(event)" value="" size="20" />
        </div>
        <div class="col-md-1">
            <strong>CustNumber:</strong>
        </div>
        <div class="col-md-1">
            <input name="custnumber" type="text" class="form-control"  id="custnumber" onKeyPress="return validar_texto(event)" value="" size="20" />
        </div>
        <div class="col-md-1">
            <strong>Comprador:</strong>
        </div>
        <div class="col-md-1">
            <input name="billto" type="text" class="form-control"  id="billto" value="" size="20" />
        </div>
        <div class="col-md-1">
            <input type="submit" class="btn-primary" name="buscar" id="buscar" value="Buscar" onclick="return Compara(this.form)"/>
    </div>
</div>
</form>

  <form method="post" id="frmreport" name="frmreport">
      <div class="row">
         <div class="col-md-11">
            <h3><strong>Listado de Órdenes</strong></h3>
        </div>
        <div class="col-md-1">
             <input type="image" style="cursor:pointer" id="imprimir"  name="imprimir"class= "imprimir" src="../images/excel.png" heigth="40" value="" data-toggle="tooltip" data-placement="left" title = "Exportar Reporte Excel" width="30" formaction = "crearReportExcel.php"/>
       </div>
    </div>
  <table width="50%" border="0" align="center" class="table table-hover" >
 <tr>
    <td align="center"><strong>Acciones</strong></td>
    <td align="center"><strong>Estado</strong></td>
    <td align="center"><strong>Reenvío</strong></td>
    <td align="center"><strong>Tracking</strong></td>
  	<td align="center"><strong>Ponumber</strong></td>
    <td align="center"><strong>Custnumber</strong></td>
    <td align="center"><strong>Producto</strong></td>
    <td align="center"><strong>Cantidad</strong></td>
    <td align="center"><strong>Precio Unitario</strong></td>
    <td align="center"><strong>Fecha Orden</strong></td>
    <td align="center"><strong>Fecha Entrega</strong></td>
    <td align="center"><strong>Nombre Receptor</strong></td>
    <td align="center"><strong>Nombre Receptor2</strong></td>
    <td align="center"><strong>Dirección</strong></td>
    <td align="center"><strong>Dirección2</strong></td>
    <td align="center"><strong>Ciudad</strong></td>
    <td align="center"><strong>Estado</strong></td>
    <td align="center"><strong>Cod. Postal</strong></td>
    <td align="center"><strong>Teléfono</strong></td>
    <td align="center"><strong>Nombre Comprador</strong></td>
    <td align="center"><strong>Nombre Comprador2</strong></td>
    <td align="center"><strong>Teléfono</strong></td>
    <td align="center"><strong>Fecha de Vuelo</strong></td>
    <td align="center"><strong>Servicio</strong></td>
    <td align="center"><strong>Tipo Pack</strong></td>
    <td align="center"><strong> Desc. Gen.</strong></td>
    <td align="center"><strong>Código Caja</strong></td>
    <td align="center"><strong>Finca</strong></td>
    <td align="center"><strong>Guía Madre</strong></td>
    <td align="center"><strong>Guía Hija</strong></td>
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
			  $val = mysql_query($sql,$conection);
			  if(!$val){
				echo "<tr><td>".mysql_error()."</td></tr>";
			   }else{
				   $cant = 0;
				   while($row = mysql_fetch_array($val)){
					   $cant ++;
						echo "<tr>";
						echo "<td>";
						if($row['estado_orden']=='Active'){
						echo '<input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/money.png" heigth="30" value="" title = "Crédito al cliente" width="20" onclick = "credito('.$row['id_orden_detalle'].')"/>';
						}else{
							echo '<input disabled="true" type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../images/money.png" heigth="30" value="" width="20"/>';
					         }
 
							if($row['reenvio']=='No'){
						echo '<input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/forward.png" heigth="30" value="" title = "Reenviar orden" width="20" onclick = "reenviar('.$row['id_orden_detalle'].',\''.$row['cpitem'].'\',\''.$row['delivery_traking'].'\')"/>';
						}else{
					    	echo '<input disabled="true" type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../images/forward.png" heigth="30" value="" width="20"/>';
						}
						echo "</td>";
						
					echo "<td align='center'><strong>".$row['estado_orden']."</strong></td>";
					echo "<td align='center'><strong>".$row['reenvio']."</strong></td>";
					
					//******Verificar si ese tracking ya esta entregado por UPS******/
					//* Si el tracking es delivered ---> color verde*****************/
					//* Si el tracking esta en ups ---> color rojo*****************/
					//* Si el tracking esta vuelo ---> color amarillo*****************/
					
					$tracking_url = 'http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums='.$row['tracking'];
					$obj_upstraking = new upstraking();
					$account_data = array();
					$account_data['access_lic_no']='1CEA1F8720EA71B6';
					$account_data['username']='janpaul.sanchez';
					$account_data['password']='Welcome1!';
					$entregado = $obj_upstraking->upstrack($trackingno=''.$row['tracking'].'',$account_data);
					
					if($entregado['TRACKRESPONSE']['RESPONSE']['RESPONSESTATUSDESCRIPTION']=='Success'){
						$shipment=$entregado['TRACKRESPONSE']['SHIPMENT'];
						$address_arr=$shipment['PACKAGE']['ACTIVITY']['ACTIVITYLOCATION']['ADDRESS'];
						$address=$address_arr['CITY'].', '.$address_arr['STATEPROVINCECODE'].', '.$address_arr['COUNTRYCODE'];
						
						if($shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] == 'DELIVERED'){
						echo "<td align='center'><button type='button' class='btn btn-success' style=\"color:#000;\" href=\"#demo".$cant."_tip\" onmouseover=\"tooltip.pop(this, '#demo".$cant."_tip', {sticky:true})\"><strong>".$row['tracking']."</strong></button>";
						}else{
								echo "<td align='center'><button type='button' class='btn btn-warning' style=\"color:#000;\" href=\"#demo".$cant."_tip\" onmouseover=\"tooltip.pop(this, '#demo".$cant."_tip', {sticky:true})\"><strong>".$row['tracking']."</strong></button>";
							}
						
                        ?>
                        <div style="display:none;">
                            <?php echo "<div id=\"demo".$cant."_tip\">"; ?>
                                <!--<img src="src/tooltips.gif" style="float:right;margin-left:12px;" alt="" -->
                                <?php
								//Si es delivered muestro toda la info
								if($shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] == 'DELIVERED'){
									//Organizar la fecha y la hora para mostrarla en un buen formato
									$fecha = $shipment['PACKAGE']['ACTIVITY']['DATE'];
									$anno  = substr($fecha,0,4);
									$mes   = substr($fecha,4,2);
									$dia   = substr($fecha,6,2);
									
									$fecha = $dia."-".$mes."-".$anno;
									
									$hora  = $shipment['PACKAGE']['ACTIVITY']['TIME'];
									$hr    = substr($hora,0,2);
									$min   = substr($hora,2,2);
									$seg   = substr($hora,4,2);
									
									$hora  = $hr.":".$min.":".$seg;
									$hora  = date("g:i a",strtotime($hora));
									
									echo '<strong>'.$shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'].'</strong><br /><br />';
									echo '<b> Delivered On: '.$fecha.' at '.$hora.'</b></br>';
									echo '<b> To: '.$address.'</b></br>';
									echo '<b> <a href="'.$tracking_url.'" target = "_new">See more details</a></b>';
								}else{
									if($shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] == 'BILLING INFORMATION RECEIVED'){
										//Organizar la fecha y la hora para mostrarla en un buen formato
										$fecha = $shipment['PACKAGE']['ACTIVITY']['DATE'];
										$anno  = substr($fecha,0,4);
										$mes   = substr($fecha,4,2);
										$dia   = substr($fecha,6,2);
										
										$fecha = $dia."-".$mes."-".$anno;
										
										$hora  = $shipment['PACKAGE']['ACTIVITY']['TIME'];
										$hr    = substr($hora,0,2);
										$min   = substr($hora,2,2);
										$seg   = substr($hora,4,2);
										
										$hora  = $hr.":".$min.":".$seg;
										$hora  = date("g:i a",strtotime($hora));
										
										echo '<strong>'.$shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'].'</strong><br /><br />';
										echo '<b> Order Processed: Ready for UPS</b></br>';
										echo '<b> Label Created On: '.$fecha.' at '.$hora.'</b></br>';
										echo 'A UPS shipping label has been created. Once the shipment arrives at our facility, the tracking status--including the scheduled delivery date--will be updated.</br>';
										echo '<b> <a href="'.$tracking_url.'" target = "_new">See more details</a></b>';
									}else{
											if($shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] == 'OUT FOR DELIVERY'){
												//Organizar la fecha y la hora para mostrarla en un buen formato
												$fecha = $shipment['PACKAGE']['ACTIVITY']['DATE'];
												$anno  = substr($fecha,0,4);
												$mes   = substr($fecha,4,2);
												$dia   = substr($fecha,6,2);
												
												$fecha = $dia."-".$mes."-".$anno;
												
												$hora  = $shipment['PACKAGE']['ACTIVITY']['TIME'];
												$hr    = substr($hora,0,2);
												$min   = substr($hora,2,2);
												$seg   = substr($hora,4,2);
												
												$hora  = $hr.":".$min.":".$seg;
												$hora  = date("g:i a",strtotime($hora));
												echo '<strong>'.$shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'].'</strong><br /><br />'; 
												echo '<b> Scheduled Delivery: '.$fecha.', By End of Day</b></br>';
												echo '<b>Last Location:'.$address.' at '.$hora.'</b>';
												echo '<b> <a href="'.$tracking_url.'" target = "_new">See more details</a></b>';
											}else{
												if($shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] == 'DEPARTURE SCAN'){
													//Organizar la fecha y la hora para mostrarla en un buen formato
													$fecha = $shipment['PACKAGE']['ACTIVITY']['DATE'];
													$anno  = substr($fecha,0,4);
													$mes   = substr($fecha,4,2);
													$dia   = substr($fecha,6,2);
													
													$fecha = $dia."-".$mes."-".$anno;
													
													$hora  = $shipment['PACKAGE']['ACTIVITY']['TIME'];
													$hr    = substr($hora,0,2);
													$min   = substr($hora,2,2);
													$seg   = substr($hora,4,2);
													
													$hora  = $hr.":".$min.":".$seg;
													$hora  = date("g:i a",strtotime($hora));
													echo '<strong>'.$shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'].'</strong><br /><br />'; 
													echo '<b> In Transit: On Time</b></br>';
													echo '<b> Scheduled Delivery: '.$fecha.', By End of Day</b></br>';
													echo '<b> Last Location:'.$address.' at '.$hora.'</b>';
													echo '<b> <a href="'.$tracking_url.'" target = "_new">See more details</a></b>';
												}
											}
										
										}
								}
								?>
                            </div>
                           </div>
                        </td>
                    <?php
					}else{
						echo "<td align='center'><strong><label>".$row['tracking']."</label></strong></td>";							
						}
					
					echo "<td align='center'><strong>".$row['Ponumber']."</strong></td>";					
					echo "<td align='center'><strong>".$row['Custnumber']."</strong></td>";
					echo "<td align='center'><strong><label id='item'>".$row['cpitem']."</label></strong></td>";
					echo "<td align='center'>".$row['cpcantidad']."</td>";
					echo "<td align='center'>".$row['unitprice']."</td>";
					echo "<td align='center'>".$row['order_date']."</td>";
					echo "<td align='center'><label  id='deliver'>".$row['delivery_traking']."</label></td>";
					echo "<td align='center'>".$row['shipto1']."</td>";
					echo "<td align='center'>".$row['shipto2']."</td>"; 
					echo "<td align='center'>".$row['direccion']."</td>";
					echo "<td align='center'>".$row['direccion2']."</td>";
					echo "<td align='center'>".$row['cpcuidad_shipto']."</td>";
					echo "<td align='center'>".$row['cpestado_shipto']."</td>";
					echo "<td align='center'>".$row['cpzip_shipto']."</td>";
					echo "<td align='center'>".$row['cptelefono_shipto']."</td>";
					echo "<td align='center'>".$row['soldto1']."</td>";
					echo "<td align='center'>".$row['soldto2']."</td>";
					echo "<td align='center'>".$row['cpstphone_soldto']."</td>";
					echo "<td align='center'>".$row['ShipDT_traking']."</td>";					
					echo "<td align='center'>".$row['cpservicio']."</td>";					
					echo "<td align='center'>".$row['cptipo_pack']."</td>";					
					echo "<td align='center'>".$row['gen_desc']."</td>";
					echo "<td align='center'>".$row['codigo']."</td>";
					$_SESSION['codigo'] = $row['codigo'];		
					echo "<td align='center'>".$row['farm']."</td>";
					
					//Obtener las guias madre e hija de esa orden
					$SQL   = "SELECT guia_madre, guia_hija FROM tblcoldroom where codigo ='".$row['codigo']."' UNION (SELECT guia_m,guia_h FROM tblcoldrom_fincas where codigo_unico ='".$row['codigo']."' )";
					$QUERY = mysql_query($SQL,$conection) or die ("Error consultando las guias."); 
					$ROW   = mysql_fetch_array($QUERY);
					
					echo "<td align='center'>".$ROW['guia_madre']."</td>";
					echo "<td align='center'>".$ROW['guia_hija']."</td>";		 
					echo "</tr>";
					$total += $row['unitprice'];
					 }
							echo "
								  <tr>
								  <td align='right'><strong>".$cant. "</strong></td>
								  <td>Órden(es) encontradas</td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td align='center'><strong>Total</strong></td>
								  <td align='center'><strong>".$total."</strong></td>
								  </tr>";						
			   }
		  }
	  }
	  	
			//Preparando los datos para el reporte
		   $_SESSION["titulo"] ="Listado de Ordenes en Customer Services";
		   //Verificar si la caja es del cuarto frio o de ls fincas autonomas
		   $SQLfrio   = "SELECT guia_madre, guia_hija FROM tblcoldroom where codigo ='".$_SESSION['codigo']."' UNION (SELECT guia_m,guia_h FROM tblcoldrom_fincas where codigo_unico ='".$_SESSION['codigo']."')";
		  // echo $SQLfrio;
		   $consulta = mysql_query($SQLfrio,$conection) or die ("Error consultando guias en cuarto frio 1");
		   $numFilas = mysql_num_rows($consulta);
		   
		    if($numFilas > 0){ //Esta en el cuarto frio
					$_SESSION["columnas"] = array("Estado","Reenvío","Tracking","Ponumber","CustNbr","Item","Quantity","UnitPrice","Orddate","Deliver","ShipTo","ShipTo2","Address","Address2","City","State","Zip","Phone","SoldTo","SoldTo2","STPhone","SHIPDT","Service","PkgType","GenDesc","Código","Finca","Guia Madre","Guia Hija"); 
				   
				$_SESSION["consulta"] = "select estado_orden,reenvio,tracking,Ponumber,Custnumber,cpitem,cpcantidad,unitprice,order_date,delivery_traking,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,ShipDT_traking,cpservicio,cptipo_pack,gen_desc,tbldetalle_orden.codigo,farm, guia_madre,guia_hija
									FROM
									tblorden
									INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
									INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
									INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
									INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
									INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
									INNER JOIN tblcoldroom ON tblcoldroom.codigo = tbldetalle_orden.codigo
									WHERE Ponumber = '".$id."' 
									UNION
									select estado_orden,reenvio,tracking,Ponumber,Custnumber,cpitem,cpcantidad,unitprice,order_date,delivery_traking,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,ShipDT_traking,cpservicio,cptipo_pack,gen_desc,codigo,farm, guia_m,guia_h
									FROM
									tblorden
									INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
									INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
									INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
									INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
									INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
									INNER JOIN tblcoldrom_fincas ON tblcoldrom_fincas.codigo_unico = tbldetalle_orden.codigo
									WHERE Ponumber = '".$id."'";
									
									unset ($_SESSION['codigo']);
			}else{
				 //No imprimo bada	
				 		   $_SESSION["columnas"] = array("Estado","Reenvío","Tracking","Ponumber","CustNbr","Item","Quantity","UnitPrice","Orddate","Deliver","ShipTo","ShipTo2","Address","Address2","City","State","Zip","Phone","SoldTo","SoldTo2","STPhone","SHIPDT","Service","PkgType","GenDesc","Código","Finca"); 
						   
				 $_SESSION["consulta"] = "select estado_orden,reenvio,tracking,Ponumber,Custnumber,cpitem,cpcantidad,unitprice,order_date,delivery_traking,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,ShipDT_traking,cpservicio,cptipo_pack,gen_desc,codigo,farm
									FROM
									tblorden
									INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
									INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
									INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
									INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
									INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
									WHERE Ponumber = '".$id."'";
									
									unset ($_SESSION['codigo']);			 
				 }
			 // }
			   $_SESSION["nombre_fichero"] = "Listado de Ordenes.xlsx";
		 
  }else{
  //if(isset($_POST['buscar'])){
	  
	  //recoger datos de busqueda
	  $ponumber = $_POST['ponumber'];
	  $custnumber = $_POST['custnumber'];
	  $billto = $_POST['billto'];
	  
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
	      $val = mysql_query($sql,$conection);
		  if(!$val){
		  	echo "<tr><td>".mysql_error()."</td></tr>";
		   }else{
			   $cant = 0;
			   while($row = mysql_fetch_array($val)){
				    $cant ++;
					echo "<tr>";
					echo "<td>";

						if($row['estado_orden']=='Active'){
						echo '<input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/money.png" heigth="30" value="" title = "Crédito al cliente" width="20" onclick = "credito('.$row['id_orden_detalle'].')"/>';
						}else{
							echo '<input disabled="true" type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../images/money.png" heigth="30" value="" width="20"/>';
					         } 
							 
							if($row['reenvio']=='No'){
						echo '<input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/forward.png" heigth="30" value="" title = "Reenviar orden" width="20" onclick = "reenviar('.$row['id_orden_detalle'].',\''.$row['cpitem'].'\',\''.$row['delivery_traking'].'\')"/>';
						}else{
					    	echo '<input disabled="true" type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../images/forward.png" heigth="30" value="" width="20"/>';
						}
						echo "</td>";
						
					echo "<td align='center'><strong>".$row['estado_orden']."</strong></td>";
					echo "<td align='center'><strong>".$row['reenvio']."</strong></td>";
					
					//******Verificar si ese tracking ya esta entregado por UPS******/
					//* Si el tracking es delivered ---> color verde*****************/
					//* Si el tracking esta en ups ---> color rojo*****************/
					//* Si el tracking esta vuelo ---> color amarillo*****************/
					
					$tracking_url = 'http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums='.$row['tracking'];
					$obj_upstraking = new upstraking();
					$account_data = array();
					$account_data['access_lic_no']='1CEA1F8720EA71B6';
					$account_data['username']='janpaul.sanchez';
					$account_data['password']='Welcome1!';
					$entregado = $obj_upstraking->upstrack($trackingno=''.$row['tracking'].'',$account_data);
					
					if($entregado['TRACKRESPONSE']['RESPONSE']['RESPONSESTATUSDESCRIPTION']=='Success'){
						$shipment=$entregado['TRACKRESPONSE']['SHIPMENT'];
						$address_arr=$shipment['PACKAGE']['ACTIVITY']['ACTIVITYLOCATION']['ADDRESS'];
						$address=$address_arr['CITY'].', '.$address_arr['STATEPROVINCECODE'].', '.$address_arr['COUNTRYCODE'];
						
						if($shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] == 'DELIVERED'){
						echo "<td align='center'><button type='button' class='btn btn-success' style=\"color:#000;\" href=\"#demo".$cant."_tip\" onmouseover=\"tooltip.pop(this, '#demo".$cant."_tip', {sticky:true})\"><strong>".$row['tracking']."</strong></button>";
						}else{
								echo "<td align='center'><button type='button' class='btn btn-success' style=\"color:#000;\" href=\"#demo".$cant."_tip\" onmouseover=\"tooltip.pop(this, '#demo".$cant."_tip', {sticky:true})\"><strong>".$row['tracking']."</strong></a></button>";
							}
						
                        ?>
                        <div style="display:none;">
                            <?php echo "<div id=\"demo".$cant."_tip\">"; ?>
                                <!--<img src="src/tooltips.gif" style="float:right;margin-left:12px;" alt="" -->
                                <?php
								//Si es delivered muestro toda la info
								if($shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] == 'DELIVERED'){
									//Organizar la fecha y la hora para mostrarla en un buen formato
									$fecha = $shipment['PACKAGE']['ACTIVITY']['DATE'];
									$anno  = substr($fecha,0,4);
									$mes   = substr($fecha,4,2);
									$dia   = substr($fecha,6,2);
									
									$fecha = $dia."-".$mes."-".$anno;
									
									$hora  = $shipment['PACKAGE']['ACTIVITY']['TIME'];
									$hr    = substr($hora,0,2);
									$min   = substr($hora,2,2);
									$seg   = substr($hora,4,2);
									
									$hora  = $hr.":".$min.":".$seg;
									$hora  = date("g:i a",strtotime($hora));
									
									echo '<strong>'.$shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'].'</strong><br /><br />';
									echo '<b> Delivered On: '.$fecha.' at '.$hora.'</b></br>';
									echo '<b> To: '.$address.'</b></br>';
									echo '<b> <a href="'.$tracking_url.'" target = "_new">See more details</a></b>';
								}else{
									if($shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] == 'BILLING INFORMATION RECEIVED'){
										//Organizar la fecha y la hora para mostrarla en un buen formato
										$fecha = $shipment['PACKAGE']['ACTIVITY']['DATE'];
										$anno  = substr($fecha,0,4);
										$mes   = substr($fecha,4,2);
										$dia   = substr($fecha,6,2);
										
										$fecha = $dia."-".$mes."-".$anno;
										
										$hora  = $shipment['PACKAGE']['ACTIVITY']['TIME'];
										$hr    = substr($hora,0,2);
										$min   = substr($hora,2,2);
										$seg   = substr($hora,4,2);
										
										$hora  = $hr.":".$min.":".$seg;
										$hora  = date("g:i a",strtotime($hora));
										
										echo '<strong>'.$shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'].'</strong><br /><br />';
										echo '<b> Order Processed: Ready for UPS</b></br>';
										echo '<b> Label Created On: '.$fecha.' at '.$hora.'</b></br>';
										echo 'A UPS shipping label has been created. Once the shipment arrives at our facility, the tracking status--including the scheduled delivery date--will be updated.</br>';
										echo '<b> <a href="'.$tracking_url.'" target = "_new">See more details</a></b>';
									}else{
											if($shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] == 'OUT FOR DELIVERY'){
												//Organizar la fecha y la hora para mostrarla en un buen formato
												$fecha = $shipment['PACKAGE']['ACTIVITY']['DATE'];
												$anno  = substr($fecha,0,4);
												$mes   = substr($fecha,4,2);
												$dia   = substr($fecha,6,2);
												
												$fecha = $dia."-".$mes."-".$anno;
												
												$hora  = $shipment['PACKAGE']['ACTIVITY']['TIME'];
												$hr    = substr($hora,0,2);
												$min   = substr($hora,2,2);
												$seg   = substr($hora,4,2);
												
												$hora  = $hr.":".$min.":".$seg;
												$hora  = date("g:i a",strtotime($hora));
												echo '<strong>'.$shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'].'</strong><br /><br />'; 
												echo '<b> Scheduled Delivery: '.$fecha.', By End of Day</b></br>';
												echo '<b>Last Location:'.$address.' at '.$hora.'</b>';
												echo '<b> <a href="'.$tracking_url.'" target = "_new">See more details</a></b>';
											}else{
												if($shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] == 'DEPARTURE SCAN'){
													//Organizar la fecha y la hora para mostrarla en un buen formato
													$fecha = $shipment['PACKAGE']['ACTIVITY']['DATE'];
													$anno  = substr($fecha,0,4);
													$mes   = substr($fecha,4,2);
													$dia   = substr($fecha,6,2);
													
													$fecha = $dia."-".$mes."-".$anno;
													
													$hora  = $shipment['PACKAGE']['ACTIVITY']['TIME'];
													$hr    = substr($hora,0,2);
													$min   = substr($hora,2,2);
													$seg   = substr($hora,4,2);
													
													$hora  = $hr.":".$min.":".$seg;
													$hora  = date("g:i a",strtotime($hora));
													echo '<strong>'.$shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'].'</strong><br /><br />'; 
													echo '<b> In Transit: On Time</b></br>';
													echo '<b> Scheduled Delivery: '.$fecha.', By End of Day</b></br>';
													echo '<b> Last Location:'.$address.' at '.$hora.'</b>';
													echo '<b> <a href="'.$tracking_url.'" target = "_new">See more details</a></b>';
												}
											}
										
										}
								}
								?>
                            </div>
                           </div>
                        </td>
                    <?php
					}else{
						echo "<td align='center'><strong><label>".$row['tracking']."</label></strong></td>";							
						}
					
					echo "<td align='center'><strong>".$row['Ponumber']."</strong></td>";
					echo "<td align='center'><strong>".$row['Custnumber']."</strong></td>";
					echo "<td align='center'><strong><label id='item'>".$row['cpitem']."</label></strong></td>";
					echo "<td align='center'>".$row['cpcantidad']."</td>";
					echo "<td align='center'>".$row['unitprice']."</td>";
					echo "<td align='center'>".$row['order_date']."</td>";
					echo "<td align='center'><label  id='deliver'>".$row['delivery_traking']."</label></td>";
					echo "<td align='center'>".$row['shipto1']."</td>";
					echo "<td align='center'>".$row['shipto2']."</td>"; 
					echo "<td align='center'>".$row['direccion']."</td>";
					echo "<td align='center'>".$row['direccion2']."</td>";
					echo "<td align='center'>".$row['cpcuidad_shipto']."</td>";
					echo "<td align='center'>".$row['cpestado_shipto']."</td>";
					echo "<td align='center'>".$row['cpzip_shipto']."</td>";
					echo "<td align='center'>".$row['cptelefono_shipto']."</td>";
					echo "<td align='center'>".$row['soldto1']."</td>";
					echo "<td align='center'>".$row['soldto2']."</td>";
					echo "<td align='center'>".$row['cpstphone_soldto']."</td>";
					echo "<td align='center'>".$row['ShipDT_traking']."</td>";					
					echo "<td align='center'>".$row['cpservicio']."</td>";					
					echo "<td align='center'>".$row['cptipo_pack']."</td>";					
					echo "<td align='center'>".$row['gen_desc']."</td>";
					echo "<td align='center'>".$row['codigo']."</td>";	
					$_SESSION['codigo'] = $row['codigo'];	
					echo "<td align='center'>".$row['farm']."</td>";
					
					//Obtener las guias madre e hija de esa orden
					$SQL   = "SELECT guia_madre, guia_hija FROM tblcoldroom where codigo ='".$row['codigo']."' UNION (SELECT guia_m,guia_h FROM tblcoldrom_fincas where codigo_unico ='".$row['codigo']."' )";
					$QUERY = mysql_query($SQL,$conection) or die ("Error consultando las guias."); 
					$ROW   = mysql_fetch_array($QUERY);
					
					echo "<td align='center'>".$ROW['guia_madre']."</td>";
					echo "<td align='center'>".$ROW['guia_hija']."</td>";
					$total += $row['unitprice'];
					 }
							echo "
								  <tr>
								  <td align='right'><strong>".$cant. "</strong></td>
								  <td>Órden(es) encontradas</td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td align='center'><strong>Total</strong></td>
								  <td align='center'><strong>".$total."</strong></td>
								  </tr>";						
			   }

	  	
			//Preparando los datos para el reporte
		   $_SESSION["titulo"] ="Listado de Ordenes en Customer Services";			   
		      
		   //Verificar si la caja es del cuarto frio o de ls fincas autonomas
		   $SQLfrio   = "SELECT guia_madre, guia_hija FROM tblcoldroom where codigo ='".$_SESSION['codigo']."' UNION (SELECT guia_m,guia_h FROM tblcoldrom_fincas where codigo_unico ='".$_SESSION['codigo']."')";
		  // echo $SQLfrio;
		   $consulta = mysql_query($SQLfrio,$conection) or die ("Error consultando guias en cuarto frio 1");
		   $numFilas = mysql_num_rows($consulta);
		   
		    if($numFilas > 0){ //Esta en el cuarto frio
					$_SESSION["columnas"] = array("Estado","Reenvío","Tracking","Ponumber","CustNbr","Item","Quantity","UnitPrice","Orddate","Deliver","ShipTo","ShipTo2","Address","Address2","City","State","Zip","Phone","SoldTo","SoldTo2","STPhone","SHIPDT","Service","PkgType","GenDesc","Código","Finca","Guia Madre","Guia Hija"); 
				   
				$_SESSION["consulta"] = "select estado_orden,reenvio,tracking,Ponumber,Custnumber,cpitem,cpcantidad,unitprice,order_date,delivery_traking,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,ShipDT_traking,cpservicio,cptipo_pack,gen_desc,tbldetalle_orden.codigo,farm, guia_madre,guia_hija
									FROM
									tblorden
									INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
									INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
									INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
									INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
									INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
									INNER JOIN tblcoldroom ON tblcoldroom.codigo = tbldetalle_orden.codigo
									WHERE Ponumber = '".$ponumber."' 
									UNION
									select estado_orden,reenvio,tracking,Ponumber,Custnumber,cpitem,cpcantidad,unitprice,order_date,delivery_traking,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,ShipDT_traking,cpservicio,cptipo_pack,gen_desc,codigo,farm, guia_m,guia_h
									FROM
									tblorden
									INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
									INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
									INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
									INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
									INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
									INNER JOIN tblcoldrom_fincas ON tblcoldrom_fincas.codigo_unico = tbldetalle_orden.codigo
									WHERE Ponumber = '".$ponumber."'";
									
									unset ($_SESSION['codigo']);
			}else{
				 //No imprimo bada	
				 		   $_SESSION["columnas"] = array("Estado","Reenvío","Tracking","Ponumber","CustNbr","Item","Quantity","UnitPrice","Orddate","Deliver","ShipTo","ShipTo2","Address","Address2","City","State","Zip","Phone","SoldTo","SoldTo2","STPhone","SHIPDT","Service","PkgType","GenDesc","Código","Finca"); 
						   
				 $_SESSION["consulta"] = "select estado_orden,reenvio,tracking,Ponumber,Custnumber,cpitem,cpcantidad,unitprice,order_date,delivery_traking,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,ShipDT_traking,cpservicio,cptipo_pack,gen_desc,codigo,farm
									FROM
									tblorden
									INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
									INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
									INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
									INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
									INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
									WHERE Ponumber = '".$ponumber."'";
									
									unset ($_SESSION['codigo']);			 
				 }
			 // }
			   $_SESSION["nombre_fichero"] = "Listado de Ordenes.xlsx";
		  
	  }else{
		//verifico que las fechas tengan valor  
		if($custnumber != ''){
		  $sql =   "select *
						FROM
						tblorden
						INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
						INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
						INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
						INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
						INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
						WHERE Custnumber='".$custnumber."'";
	      $val = mysql_query($sql,$conection);
		  if(!$val){
		  	echo "<tr><td>".mysql_error()."</td></tr>";
		   }else{
			   $cant = 0;
			   while($row = mysql_fetch_array($val)){
				   $cant ++;
					echo "<tr>";
					echo "<td>";
										if($row['estado_orden']=='Active'){
						echo '<input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/money.png" heigth="30" value="" title = "Crédito al cliente" width="20" onclick = "credito('.$row['id_orden_detalle'].')"/>';
						}else{
							echo '<input disabled="true" type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../images/money.png" heigth="30" value="" width="20"/>';
					         }

							if($row['reenvio']=='No'){
						echo '<input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/forward.png" heigth="30" value="" title = "Reenviar orden" width="20" onclick = "reenviar('.$row['id_orden_detalle'].',\''.$row['cpitem'].'\',\''.$row['delivery_traking'].'\')"/>';
						}else{
					    	echo '<input disabled="true" type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../images/forward.png" heigth="30" value="" width="20"/>';
						}
						echo "</td>";
						
					echo "<td align='center'><strong>".$row['estado_orden']."</strong></td>";
					echo "<td align='center'><strong>".$row['reenvio']."</strong></td>";
					
					//******Verificar si ese tracking ya esta entregado por UPS******/
					//* Si el tracking es delivered ---> color verde*****************/
					//* Si el tracking esta en ups ---> color rojo*****************/
					//* Si el tracking esta vuelo ---> color amarillo*****************/
					
					$tracking_url = 'http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums='.$row['tracking'];
					$obj_upstraking = new upstraking();
					$account_data = array();
					$account_data['access_lic_no']='1CEA1F8720EA71B6';
					$account_data['username']='janpaul.sanchez';
					$account_data['password']='Welcome1!';
					$entregado = $obj_upstraking->upstrack($trackingno=''.$row['tracking'].'',$account_data);
					
					if($entregado['TRACKRESPONSE']['RESPONSE']['RESPONSESTATUSDESCRIPTION']=='Success'){
						$shipment=$entregado['TRACKRESPONSE']['SHIPMENT'];
						$address_arr=$shipment['PACKAGE']['ACTIVITY']['ACTIVITYLOCATION']['ADDRESS'];
						$address=$address_arr['CITY'].', '.$address_arr['STATEPROVINCECODE'].', '.$address_arr['COUNTRYCODE'];
						
						if($shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] == 'DELIVERED'){
						echo "<td align='center'><button type='button' class='btn btn-success' style=\"color:#000;\" href=\"#demo".$cant."_tip\" onmouseover=\"tooltip.pop(this, '#demo".$cant."_tip', {sticky:true})\"><strong>".$row['tracking']."</strong></button>";
						}else{
								echo "<td align='center'><button type='button' class='btn btn-warning' style=\"color:#000;\" href=\"#demo".$cant."_tip\" onmouseover=\"tooltip.pop(this, '#demo".$cant."_tip', {sticky:true})\"><strong>".$row['tracking']."</strong></button>";
							}
						
                        ?>
                        <div style="display:none;">
                            <?php echo "<div id=\"demo".$cant."_tip\">"; ?>
                                <!--<img src="src/tooltips.gif" style="float:right;margin-left:12px;" alt="" -->
                                <?php
								//Si es delivered muestro toda la info
								if($shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] == 'DELIVERED'){
									//Organizar la fecha y la hora para mostrarla en un buen formato
									$fecha = $shipment['PACKAGE']['ACTIVITY']['DATE'];
									$anno  = substr($fecha,0,4);
									$mes   = substr($fecha,4,2);
									$dia   = substr($fecha,6,2);
									
									$fecha = $dia."-".$mes."-".$anno;
									
									$hora  = $shipment['PACKAGE']['ACTIVITY']['TIME'];
									$hr    = substr($hora,0,2);
									$min   = substr($hora,2,2);
									$seg   = substr($hora,4,2);
									
									$hora  = $hr.":".$min.":".$seg;
									$hora  = date("g:i a",strtotime($hora));
									
									echo '<strong>'.$shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'].'</strong><br /><br />';
									echo '<b> Delivered On: '.$fecha.' at '.$hora.'</b></br>';
									echo '<b> To: '.$address.'</b></br>';
									echo '<b> <a href="'.$tracking_url.'" target = "_new">See more details</a></b>';
								}else{
									if($shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] == 'BILLING INFORMATION RECEIVED'){
										//Organizar la fecha y la hora para mostrarla en un buen formato
										$fecha = $shipment['PACKAGE']['ACTIVITY']['DATE'];
										$anno  = substr($fecha,0,4);
										$mes   = substr($fecha,4,2);
										$dia   = substr($fecha,6,2);
										
										$fecha = $dia."-".$mes."-".$anno;
										
										$hora  = $shipment['PACKAGE']['ACTIVITY']['TIME'];
										$hr    = substr($hora,0,2);
										$min   = substr($hora,2,2);
										$seg   = substr($hora,4,2);
										
										$hora  = $hr.":".$min.":".$seg;
										$hora  = date("g:i a",strtotime($hora));
										
										echo '<strong>'.$shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'].'</strong><br /><br />';
										echo '<b> Order Processed: Ready for UPS</b></br>';
										echo '<b> Label Created On: '.$fecha.' at '.$hora.'</b></br>';
										echo 'A UPS shipping label has been created. Once the shipment arrives at our facility, the tracking status--including the scheduled delivery date--will be updated.</br>';
										echo '<b> <a href="'.$tracking_url.'" target = "_new">See more details</a></b>';
									}else{
											if($shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] == 'OUT FOR DELIVERY'){
												//Organizar la fecha y la hora para mostrarla en un buen formato
												$fecha = $shipment['PACKAGE']['ACTIVITY']['DATE'];
												$anno  = substr($fecha,0,4);
												$mes   = substr($fecha,4,2);
												$dia   = substr($fecha,6,2);
												
												$fecha = $dia."-".$mes."-".$anno;
												
												$hora  = $shipment['PACKAGE']['ACTIVITY']['TIME'];
												$hr    = substr($hora,0,2);
												$min   = substr($hora,2,2);
												$seg   = substr($hora,4,2);
												
												$hora  = $hr.":".$min.":".$seg;
												$hora  = date("g:i a",strtotime($hora));
												echo '<strong>'.$shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'].'</strong><br /><br />'; 
												echo '<b> Scheduled Delivery: '.$fecha.', By End of Day</b></br>';
												echo '<b>Last Location:'.$address.' at '.$hora.'</b>';
												echo '<b> <a href="'.$tracking_url.'" target = "_new">See more details</a></b>';
											}else{
												if($shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] == 'DEPARTURE SCAN'){
													//Organizar la fecha y la hora para mostrarla en un buen formato
													$fecha = $shipment['PACKAGE']['ACTIVITY']['DATE'];
													$anno  = substr($fecha,0,4);
													$mes   = substr($fecha,4,2);
													$dia   = substr($fecha,6,2);
													
													$fecha = $dia."-".$mes."-".$anno;
													
													$hora  = $shipment['PACKAGE']['ACTIVITY']['TIME'];
													$hr    = substr($hora,0,2);
													$min   = substr($hora,2,2);
													$seg   = substr($hora,4,2);
													
													$hora  = $hr.":".$min.":".$seg;
													$hora  = date("g:i a",strtotime($hora));
													echo '<strong>'.$shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'].'</strong><br /><br />'; 
													echo '<b> In Transit: On Time</b></br>';
													echo '<b> Scheduled Delivery: '.$fecha.', By End of Day</b></br>';
													echo '<b> Last Location:'.$address.' at '.$hora.'</b>';
													echo '<b> <a href="'.$tracking_url.'" target = "_new">See more details</a></b>';
												}
											}
										
										}
								}
								?>
                            </div>
                           </div>
                        </td>
                    <?php
					}else{
						echo "<td align='center'><strong><label>".$row['tracking']."</label></strong></td>";							
						}
					
					echo "<td align='center'><strong>".$row['Ponumber']."</strong></td>";
					echo "<td align='center'><strong>".$row['Custnumber']."</strong></td>";
					echo "<td align='center'><strong><label id='item'>".$row['cpitem']."</label></strong></td>";
					echo "<td align='center'>".$row['cpcantidad']."</td>";
					echo "<td align='center'>".$row['unitprice']."</td>";
					echo "<td align='center'>".$row['order_date']."</td>";
					echo "<td align='center'><label  id='deliver'>".$row['delivery_traking']."</label></td>";
					echo "<td align='center'>".$row['shipto1']."</td>";
					echo "<td align='center'>".$row['shipto2']."</td>"; 
					echo "<td align='center'>".$row['direccion']."</td>";
					echo "<td align='center'>".$row['direccion2']."</td>";
					echo "<td align='center'>".$row['cpcuidad_shipto']."</td>";
					echo "<td align='center'>".$row['cpestado_shipto']."</td>";
					echo "<td align='center'>".$row['cpzip_shipto']."</td>";
					echo "<td align='center'>".$row['cptelefono_shipto']."</td>";
					echo "<td align='center'>".$row['soldto1']."</td>";
					echo "<td align='center'>".$row['soldto2']."</td>";
					echo "<td align='center'>".$row['cpstphone_soldto']."</td>";
					echo "<td align='center'>".$row['ShipDT_traking']."</td>";					
					echo "<td align='center'>".$row['cpservicio']."</td>";					
					echo "<td align='center'>".$row['cptipo_pack']."</td>";					
					echo "<td align='center'>".$row['gen_desc']."</td>";
					echo "<td align='center'>".$row['codigo']."</td>";
					$_SESSION['codigo'] = $row['codigo'];		
					echo "<td align='center'>".$row['farm']."</td>";
					
					//Obtener las guias madre e hija de esa orden
					$SQL   = "SELECT guia_madre, guia_hija FROM tblcoldroom where codigo ='".$row['codigo']."' UNION (SELECT guia_m,guia_h FROM tblcoldrom_fincas where codigo_unico ='".$row['codigo']."' )";
					$QUERY = mysql_query($SQL,$conection) or die ("Error consultando las guias."); 
					$ROW   = mysql_fetch_array($QUERY);
					
					echo "<td align='center'>".$ROW['guia_madre']."</td>";
					echo "<td align='center'>".$ROW['guia_hija']."</td>";
					echo "</tr>";
					$total += $row['unitprice'];
					 }
							echo "
								  <tr>
								  <td align='right'><strong>".$cant. "</strong></td>
								  <td>Órden(es) encontradas</td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td align='center'><strong>Total</strong></td>
								  <td align='center'><strong>".$total."</strong></td>
								  </tr>";						
		   }
	  	
			//Preparando los datos para el reporte
		   $_SESSION["titulo"] ="Listado de Ordenes en Customer Services";
		   
		   //Verificar si la caja es del cuarto frio o de ls fincas autonomas
		   $SQLfrio   = "SELECT guia_madre, guia_hija FROM tblcoldroom where codigo ='".$_SESSION['codigo']."' UNION (SELECT guia_m,guia_h FROM tblcoldrom_fincas where codigo_unico ='".$row['codigo']."')";
		   $consulta = mysql_query($SQLfrio,$conection) or die ("Error consultando guias en cuarto frio 1");
		   $numFilas = mysql_num_rows($consulta);
		   
		   if($numFilas > 0){ //Esta en el cuarto frio
		   	$_SESSION["columnas"] = array("Estado","Reenvío","Tracking","Ponumber","CustNbr","Item","Quantity","UnitPrice","Orddate","Deliver","ShipTo","ShipTo2","Address","Address2","City","State","Zip","Phone","SoldTo","SoldTo2","STPhone","SHIPDT","Service","PkgType","GenDesc","Código","Finca","Guia Madre","Guia Hija"); 
		   
				$_SESSION["consulta"] = "select estado_orden,reenvio,tracking,Ponumber,Custnumber,cpitem,cpcantidad,unitprice,order_date,delivery_traking,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,ShipDT_traking,cpservicio,cptipo_pack,gen_desc,tbldetalle_orden.codigo,farm, guia_madre,guia_hija
									FROM
									tblorden
									INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
									INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
									INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
									INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
									INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
									INNER JOIN tblcoldroom ON tblcoldroom.codigo = tbldetalle_orden.codigo
									WHERE Custnumber='".$custnumber."'
									UNION
									select estado_orden,reenvio,tracking,Ponumber,Custnumber,cpitem,cpcantidad,unitprice,order_date,delivery_traking,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,ShipDT_traking,cpservicio,cptipo_pack,gen_desc,codigo,farm, guia_m,guia_h
									FROM
									tblorden
									INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
									INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
									INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
									INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
									INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
									INNER JOIN tblcoldrom_fincas ON tblcoldrom_fincas.codigo_unico = tbldetalle_orden.codigo
									WHERE Custnumber='".$custnumber."'";
									
									unset ($_SESSION['codigo']);
		   
		  }
		   /*
		   if($numFilas > 0){ //Esta en el cuarto frio
		   $_SESSION["columnas"] = array("Estado","Reenvío","Tracking","Ponumber","CustNbr","Item","Quantity","UnitPrice","Orddate","Deliver","ShipTo","ShipTo2","Address","Address2","City","State","Zip","Phone","SoldTo","SoldTo2","STPhone","SHIPDT","Service","PkgType","GenDesc","Código","Finca","Guia Madre","Guia Hija"); 
		   
				$_SESSION["consulta"] = "select estado_orden,reenvio,tracking,Ponumber,Custnumber,cpitem,cpcantidad,unitprice,order_date,delivery_traking,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,ShipDT_traking,cpservicio,cptipo_pack,gen_desc,tbldetalle_orden.codigo,farm, guia_madre,guia_hija
									FROM
									tblorden
									INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
									INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
									INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
									INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
									INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
									INNER JOIN tblcoldroom ON tblcoldroom.codigo = tbldetalle_orden.codigo
									WHERE Custnumber='".$custnumber."'";
									
									unset ($_SESSION['codigo']);
		   
		  }else{
			  	$SQLfrioAutonomo = "SELECT guia_m,guia_h FROM tblcoldrom_fincas where codigo_unico ='".$_SESSION['codigo']."'";
			  	$consulta1 = mysql_query($SQLfrioAutonomo,$conection) or die ("Error consultando guias en cuarto frio 2");
		  		$numFilas = mysql_num_rows($consulta1);
				if($numFilas > 0){ //Esta en el cuarto frio
					$_SESSION["columnas"] = array("Estado","Reenvío","Tracking","Ponumber","CustNbr","Item","Quantity","UnitPrice","Orddate","Deliver","ShipTo","ShipTo2","Address","Address2","City","State","Zip","Phone","SoldTo","SoldTo2","STPhone","SHIPDT","Service","PkgType","GenDesc","Código","Finca","Guia Madre","Guia Hija"); 
						   
				   $_SESSION["consulta"] = "select estado_orden,reenvio,tracking,Ponumber,Custnumber,cpitem,cpcantidad,unitprice,order_date,delivery_traking,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,ShipDT_traking,cpservicio,cptipo_pack,gen_desc,codigo,farm, guia_m,guia_h
									FROM
									tblorden
									INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
									INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
									INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
									INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
									INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
									INNER JOIN tblcoldrom_fincas ON tblcoldrom_fincas.codigo_unico = tbldetalle_orden.codigo
									WHERE Custnumber='".$custnumber."'";
									
									unset ($_SESSION['codigo']);
				   
		 		 }
				 */
				 else{
				 //No imprimo bada	
				 $_SESSION["columnas"] = array("Estado","Reenvío","Tracking","Ponumber","CustNbr","Item","Quantity","UnitPrice","Orddate","Deliver","ShipTo","ShipTo2","Address","Address2","City","State","Zip","Phone","SoldTo","SoldTo2","STPhone","SHIPDT","Service","PkgType","GenDesc","Código","Finca"); 
				 
				 $_SESSION["consulta"] = "select estado_orden,reenvio,tracking,Ponumber,Custnumber,cpitem,cpcantidad,unitprice,order_date,delivery_traking,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,ShipDT_traking,cpservicio,cptipo_pack,gen_desc,codigo,farm
									FROM
									tblorden
									INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
									INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
									INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
									INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
									INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
									WHERE Custnumber='".$custnumber."'";
									
									unset ($_SESSION['codigo']);			 
				 }
			//  }
		   $_SESSION["nombre_fichero"] = "Listado de Ordenes.xlsx";	
		   
		}else{
			//verifico que las fechas tengan valor  
				if($billto != ''){
				  $sql =   "select *
								FROM
								tblorden
								INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
								INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
								INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
								INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
								INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
								WHERE soldto1='".$billto."'";
				  $val = mysql_query($sql,$conection);
				  if(!$val){
					echo "<tr><td>".mysql_error()."</td></tr>";
				   }else{
					   $cant = 0;
					   while($row = mysql_fetch_array($val)){
						   $cant ++;
							echo "<tr>";
							echo "<td>";
								
								if($row['estado_orden']=='Active'){
								echo '<input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/money.png" heigth="30" value="" title = "Crédito al cliente" width="20" onclick = "credito('.$row['id_orden_detalle'].')"/>';
								}else{
									echo '<input disabled="true" type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../images/money.png" heigth="30" value="" width="20"/>';
									 }
		
									if($row['reenvio']=='No'){
								echo '<input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/forward.png" heigth="30" value="" title = "Reenviar orden" width="20" onclick = "reenviar('.$row['id_orden_detalle'].',\''.$row['cpitem'].'\',\''.$row['delivery_traking'].'\' )"/>';
								}else{
									echo '<input disabled="true" type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../images/forward.png" heigth="30" value="" width="20"/>';
								}
								echo "</td>";
								
							echo "<td align='center'><strong>".$row['estado_orden']."</strong></td>";
							echo "<td align='center'><strong>".$row['reenvio']."</strong></td>";
							
					//******Verificar si ese tracking ya esta entregado por UPS******/
					//* Si el tracking es delivered ---> color verde*****************/
					//* Si el tracking esta en ups ---> color rojo*****************/
					//* Si el tracking esta vuelo ---> color amarillo*****************/
					
					$tracking_url = 'http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums='.$row['tracking'];
					$obj_upstraking = new upstraking();
					$account_data = array();
					$account_data['access_lic_no']='1CEA1F8720EA71B6';
					$account_data['username']='janpaul.sanchez';
					$account_data['password']='Welcome1!';
					$entregado = $obj_upstraking->upstrack($trackingno=''.$row['tracking'].'',$account_data);
					
					if($entregado['TRACKRESPONSE']['RESPONSE']['RESPONSESTATUSDESCRIPTION']=='Success'){
						$shipment=$entregado['TRACKRESPONSE']['SHIPMENT'];
						$address_arr=$shipment['PACKAGE']['ACTIVITY']['ACTIVITYLOCATION']['ADDRESS'];
						$address=$address_arr['CITY'].', '.$address_arr['STATEPROVINCECODE'].', '.$address_arr['COUNTRYCODE'];
						
						if($shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] == 'DELIVERED'){
						echo "<td align='center'><button type='button' class='btn btn-success' style=\"color:#000;\" href=\"#demo".$cant."_tip\" onmouseover=\"tooltip.pop(this, '#demo".$cant."_tip', {sticky:true})\"><strong>".$row['tracking']."</strong></button>";
						}else{
								echo "<td align='center'><button type='button' class='btn btn-warning' style=\"color:#000;\" href=\"#demo".$cant."_tip\" onmouseover=\"tooltip.pop(this, '#demo".$cant."_tip', {sticky:true})\"><strong>".$row['tracking']."</strong></button>";
							}
						
                        ?>
                        <div style="display:none;">
                            <?php echo "<div id=\"demo".$cant."_tip\">"; ?>
                                <!--<img src="src/tooltips.gif" style="float:right;margin-left:12px;" alt="" -->
                                <?php
								//Si es delivered muestro toda la info
								if($shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] == 'DELIVERED'){
									//Organizar la fecha y la hora para mostrarla en un buen formato
									$fecha = $shipment['PACKAGE']['ACTIVITY']['DATE'];
									$anno  = substr($fecha,0,4);
									$mes   = substr($fecha,4,2);
									$dia   = substr($fecha,6,2);
									
									$fecha = $dia."-".$mes."-".$anno;
									
									$hora  = $shipment['PACKAGE']['ACTIVITY']['TIME'];
									$hr    = substr($hora,0,2);
									$min   = substr($hora,2,2);
									$seg   = substr($hora,4,2);
									
									$hora  = $hr.":".$min.":".$seg;
									$hora  = date("g:i a",strtotime($hora));
									
									echo '<strong>'.$shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'].'</strong><br /><br />';
									echo '<b> Delivered On: '.$fecha.' at '.$hora.'</b></br>';
									echo '<b> To: '.$address.'</b></br>';
									echo '<b> <a href="'.$tracking_url.'" target = "_new">See more details</a></b>';
								}else{
									if($shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] == 'BILLING INFORMATION RECEIVED'){
										//Organizar la fecha y la hora para mostrarla en un buen formato
										$fecha = $shipment['PACKAGE']['ACTIVITY']['DATE'];
										$anno  = substr($fecha,0,4);
										$mes   = substr($fecha,4,2);
										$dia   = substr($fecha,6,2);
										
										$fecha = $dia."-".$mes."-".$anno;
										
										$hora  = $shipment['PACKAGE']['ACTIVITY']['TIME'];
										$hr    = substr($hora,0,2);
										$min   = substr($hora,2,2);
										$seg   = substr($hora,4,2);
										
										$hora  = $hr.":".$min.":".$seg;
										$hora  = date("g:i a",strtotime($hora));
										
										echo '<strong>'.$shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'].'</strong><br /><br />';
										echo '<b> Order Processed: Ready for UPS</b></br>';
										echo '<b> Label Created On: '.$fecha.' at '.$hora.'</b></br>';
										echo 'A UPS shipping label has been created. Once the shipment arrives at our facility, the tracking status--including the scheduled delivery date--will be updated.</br>';
										echo '<b> <a href="'.$tracking_url.'" target = "_new">See more details</a></b>';
									}else{
											if($shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] == 'OUT FOR DELIVERY'){
												//Organizar la fecha y la hora para mostrarla en un buen formato
												$fecha = $shipment['PACKAGE']['ACTIVITY']['DATE'];
												$anno  = substr($fecha,0,4);
												$mes   = substr($fecha,4,2);
												$dia   = substr($fecha,6,2);
												
												$fecha = $dia."-".$mes."-".$anno;
												
												$hora  = $shipment['PACKAGE']['ACTIVITY']['TIME'];
												$hr    = substr($hora,0,2);
												$min   = substr($hora,2,2);
												$seg   = substr($hora,4,2);
												
												$hora  = $hr.":".$min.":".$seg;
												$hora  = date("g:i a",strtotime($hora));
												echo '<strong>'.$shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'].'</strong><br /><br />'; 
												echo '<b> Scheduled Delivery: '.$fecha.', By End of Day</b></br>';
												echo '<b>Last Location:'.$address.' at '.$hora.'</b>';
												echo '<b> <a href="'.$tracking_url.'" target = "_new">See more details</a></b>';
											}else{
												if($shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] == 'DEPARTURE SCAN'){
													//Organizar la fecha y la hora para mostrarla en un buen formato
													$fecha = $shipment['PACKAGE']['ACTIVITY']['DATE'];
													$anno  = substr($fecha,0,4);
													$mes   = substr($fecha,4,2);
													$dia   = substr($fecha,6,2);
													
													$fecha = $dia."-".$mes."-".$anno;
													
													$hora  = $shipment['PACKAGE']['ACTIVITY']['TIME'];
													$hr    = substr($hora,0,2);
													$min   = substr($hora,2,2);
													$seg   = substr($hora,4,2);
													
													$hora  = $hr.":".$min.":".$seg;
													$hora  = date("g:i a",strtotime($hora));
													echo '<strong>'.$shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'].'</strong><br /><br />'; 
													echo '<b> In Transit: On Time</b></br>';
													echo '<b> Scheduled Delivery: '.$fecha.', By End of Day</b></br>';
													echo '<b> Last Location:'.$address.' at '.$hora.'</b>';
													echo '<b> <a href="'.$tracking_url.'" target = "_new">See more details</a></b>';
												}
											}
										
										}
								}
								?>
                            </div>
                           </div>
                        </td>
                    <?php
					}else{
						echo "<td align='center'><strong><label>".$row['tracking']."</label></strong></td>";							
						}
					
							
							echo "<td align='center'><strong>".$row['Ponumber']."</strong></td>";
							echo "<td align='center'><strong>".$row['Custnumber']."</strong></td>";
							echo "<td align='center'><strong><label id='item'>".$row['cpitem']."</label></strong></td>";
							echo "<td align='center'>".$row['cpcantidad']."</td>";
							echo "<td align='center'>".$row['unitprice']."</td>";
							echo "<td align='center'>".$row['order_date']."</td>";
							echo "<td align='center'><label  id='deliver'>".$row['delivery_traking']."</label></td>";
							echo "<td align='center'>".$row['shipto1']."</td>";
							echo "<td align='center'>".$row['shipto2']."</td>"; 
							echo "<td align='center'>".$row['direccion']."</td>";
							echo "<td align='center'>".$row['direccion2']."</td>";
							echo "<td align='center'>".$row['cpcuidad_shipto']."</td>";
							echo "<td align='center'>".$row['cpestado_shipto']."</td>";
							echo "<td align='center'>".$row['cpzip_shipto']."</td>";
							echo "<td align='center'>".$row['cptelefono_shipto']."</td>";
							echo "<td align='center'>".$row['soldto1']."</td>";
							echo "<td align='center'>".$row['soldto2']."</td>";
							echo "<td align='center'>".$row['cpstphone_soldto']."</td>";
							echo "<td align='center'>".$row['ShipDT_traking']."</td>";					
							echo "<td align='center'>".$row['cpservicio']."</td>";					
							echo "<td align='center'>".$row['cptipo_pack']."</td>";					
							echo "<td align='center'>".$row['gen_desc']."</td>";
							echo "<td align='center'>".$row['codigo']."</td>";
							$_SESSION['codigo'] = $row['codigo'];	
							echo "<td align='center'>".$row['farm']."</td>";
							
							//Obtener las guias madre e hija de esa orden
							$SQL   = "SELECT guia_madre, guia_hija FROM tblcoldroom where codigo ='".$row['codigo']."' UNION (SELECT guia_m,guia_h FROM tblcoldrom_fincas where codigo_unico ='".$row['codigo']."' )";
							$QUERY = mysql_query($SQL,$conection) or die ("Error consultando las guias."); 
							$ROW   = mysql_fetch_array($QUERY);
							
							echo "<td align='center'>".$ROW['guia_madre']."</td>";
							echo "<td align='center'>".$ROW['guia_hija']."</td>";
							echo "</tr>";
							$total += $row['unitprice'];
					 }
							echo "<tr>
								  <td align='right'><strong>".$cant. "</strong></td>
								  <td>Órden(es) encontradas</td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td align='center'><strong>Total</strong></td>
								  <td align='center'><strong>".$total."</strong></td>
								  </tr>";						
			    }

	  	
			//Preparando los datos para el reporte
		   $_SESSION["titulo"] ="Listado de Ordenes en Customer Services";
		  							
		   //Verificar si la caja es del cuarto frio o de ls fincas autonomas
		   $SQLfrio   = "SELECT guia_madre, guia_hija FROM tblcoldroom where codigo ='".$_SESSION['codigo']."' UNION (SELECT guia_m,guia_h FROM tblcoldrom_fincas where codigo_unico ='".$row['codigo']."' )";
		   $consulta = mysql_query($SQLfrio,$conection) or die ("Error consultando guias en cuarto frio 1");
		   $numFilas = mysql_num_rows($consulta);
		   
		   if($numFilas > 0){ //Esta en el cuarto frio
		   	
				$_SESSION["columnas"] = array("Estado","Reenvío","Tracking","Ponumber","CustNbr","Item","Quantity","UnitPrice","Orddate","Deliver","ShipTo","ShipTo2","Address","Address2","City","State","Zip","Phone","SoldTo","SoldTo2","STPhone","SHIPDT","Service","PkgType","GenDesc","Código","Finca","Guia Madre","Guia Hija"); 
				   
				$_SESSION["consulta"] = "select estado_orden,reenvio,tracking,Ponumber,Custnumber,cpitem,cpcantidad,unitprice,order_date,delivery_traking,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,ShipDT_traking,cpservicio,cptipo_pack,gen_desc,tbldetalle_orden.codigo,farm, guia_madre,guia_hija
									FROM
									tblorden
									INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
									INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
									INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
									INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
									INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
									INNER JOIN tblcoldroom ON tblcoldroom.codigo = tbldetalle_orden.codigo
									WHERE soldto1='".$billto."' 
									UNION 
									select estado_orden,reenvio,tracking,Ponumber,Custnumber,cpitem,cpcantidad,unitprice,order_date,delivery_traking,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,ShipDT_traking,cpservicio,cptipo_pack,gen_desc,codigo,farm, guia_m,guia_h
									FROM
									tblorden
									INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
									INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
									INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
									INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
									INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
									INNER JOIN tblcoldrom_fincas ON tblcoldrom_fincas.codigo_unico = tbldetalle_orden.codigo
									WHERE soldto1='".$billto."'";
									
									unset ($_SESSION['codigo']);
		   
		  }
		  
		   /*
		   if($numFilas > 0){ //Esta en el cuarto frio
		   		   $_SESSION["columnas"] = array("Estado","Reenvío","Tracking","Ponumber","CustNbr","Item","Quantity","UnitPrice","Orddate","Deliver","ShipTo","ShipTo2","Address","Address2","City","State","Zip","Phone","SoldTo","SoldTo2","STPhone","SHIPDT","Service","PkgType","GenDesc","Código","Finca","Guia Madre","Guia Hija"); 
				   
				$_SESSION["consulta"] = "select estado_orden,reenvio,tracking,Ponumber,Custnumber,cpitem,cpcantidad,unitprice,order_date,delivery_traking,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,ShipDT_traking,cpservicio,cptipo_pack,gen_desc,tbldetalle_orden.codigo,farm, guia_madre,guia_hija
									FROM
									tblorden
									INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
									INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
									INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
									INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
									INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
									INNER JOIN tblcoldroom ON tblcoldroom.codigo = tbldetalle_orden.codigo
									WHERE soldto1='".$billto."'";
									
									unset ($_SESSION['codigo']);
		   
		  }else{
			  	$SQLfrioAutonomo = "SELECT guia_m,guia_h FROM tblcoldrom_fincas where codigo_unico ='".$_SESSION['codigo']."'";
			  	$consulta1 = mysql_query($SQLfrioAutonomo,$conection) or die ("Error consultando guias en cuarto frio 2");
		  		$numFilas = mysql_num_rows($consulta1);
				if($numFilas > 0){ //Esta en el cuarto frio
					$_SESSION["columnas"] = array("Estado","Reenvío","Tracking","Ponumber","CustNbr","Item","Quantity","UnitPrice","Orddate","Deliver","ShipTo","ShipTo2","Address","Address2","City","State","Zip","Phone","SoldTo","SoldTo2","STPhone","SHIPDT","Service","PkgType","GenDesc","Código","Finca","Guia Madre","Guia Hija"); 
							   	   
				   $_SESSION["consulta"] = "select estado_orden,reenvio,tracking,Ponumber,Custnumber,cpitem,cpcantidad,unitprice,order_date,delivery_traking,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,ShipDT_traking,cpservicio,cptipo_pack,gen_desc,codigo,farm, guia_m,guia_h
									FROM
									tblorden
									INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
									INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
									INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
									INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
									INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
									INNER JOIN tblcoldrom_fincas ON tblcoldrom_fincas.codigo_unico = tbldetalle_orden.codigo
									WHERE soldto1='".$billto."'";
									
									unset ($_SESSION['codigo']);
				   
		 		 }
				 */else{
				 //No imprimo bada		
				 		   $_SESSION["columnas"] = array("Estado","Reenvío","Tracking","Ponumber","CustNbr","Item","Quantity","UnitPrice","Orddate","Deliver","ShipTo","ShipTo2","Address","Address2","City","State","Zip","Phone","SoldTo","SoldTo2","STPhone","SHIPDT","Service","PkgType","GenDesc","Código","Finca"); 	
						   
						    $_SESSION["consulta"] = "select estado_orden,reenvio,tracking,Ponumber,Custnumber,cpitem,cpcantidad,unitprice,order_date,delivery_traking,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1,soldto2,cpstphone_soldto,ShipDT_traking,cpservicio,cptipo_pack,gen_desc,codigo,farm
									FROM
									tblorden
									INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
									INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
									INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
									INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
									INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
									WHERE soldto1='".$billto."'";
									
									unset ($_SESSION['codigo']);	 
				 }
			 // }
			  
		   $_SESSION["nombre_fichero"] = "Listado de Ordenes.xlsx";	
				}
			
			}
	  }
  }
  ?>

</table>
</form>
</div> <!-- table responsive-->
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

