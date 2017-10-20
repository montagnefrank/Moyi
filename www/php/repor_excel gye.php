<?php
    session_start();
	include ("conectarSQL.php");
    include ("conexion.php");
	include ("seguridad.php");
	$user     =  $_SESSION["login"];
	$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());

	$fecha_inicio0  =$_SESSION["inicio0"];
	$fecha_fin0     =$_SESSION["fin0"];
	
	$fecha_inicio  =$_SESSION["inicio"];
	$fecha_fin     =$_SESSION["fin"];
	
	$fecha_inicio1 =$_SESSION["inicio1"];
	$fecha_fin1    =$_SESSION["fin1"];
	
	$fecha_inicio2 =$_SESSION["inicio2"];
	$fecha_fin2    =$_SESSION["fin2"];
	
	$tracking      =$_SESSION["tracking"];
	
	$ponumber      =$_SESSION["ponumber"];
	
	$custnumber    =$_SESSION["custnumber"];
	
	$item          =$_SESSION["item"];
	
	$farm          =$_SESSION["farm"];
	
	$shipto1       =$_SESSION["shipto1"];
	
	$direccion     =$_SESSION["direccion"];
	
	$soldto1       =$_SESSION["soldto1"];
	
	$cpdireccion_soldto =$_SESSION["cpdireccion_soldto"];
	
	$pais          =$_SESSION["pais"];
	
	$origen        ='GYE-ECUADOR';
	
	$rol          =$_SESSION["rol"];
	
	$total = 0;
	

function restaFechas($dFecIni, $dFecFin)
			{
    $dFecIni = str_replace("-","",$dFecIni);
    $dFecIni = str_replace("/","",$dFecIni);
    $dFecFin = str_replace("-","",$dFecFin);
    $dFecFin = str_replace("/","",$dFecFin);

    ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecIni, $aFecIni);
    ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecFin, $aFecFin);

    $date1 = mktime(0,0,0,$aFecIni[2], $aFecIni[1], $aFecIni[3]);
    $date2 = mktime(0,0,0,$aFecFin[2], $aFecFin[1], $aFecFin[3]);
 return round(($date2 - $date1) / (60 * 60 * 24));
}
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reports Viewer</title>
<script type="text/javascript" src="jquery-1.3.2.min.js"></script>
<script language="javascript">
$(document).ready(function() {
	$(".botonExcel").click(function(event) {
		$("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).html());
		$("#FormularioExportacion").submit();
});
});
</script>
<style type="text/css">
.botonExcel{cursor:pointer;}
</style>
<link href="css/estilo_personalizado.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.table {  border: black 1px solid;
}
</style>
</head>
<body bgcolor="#CCCCCC">
<form id="form1" name="form1" method="post">
<table width="1024" border="0" align="center">
  <tr>
    <td height="133" align="center" width="100%"><img src="../images/logo.png" width="328" height="110" />
    </td>
  </tr>
  <tr>
  	<td width="436"><input type="image" src="../images/atras.png" height="40" width="30" title="Atrás" formaction="filtros gye.php" /></td>
  </tr>
  <tr>
    <td height="34" width="100%" align="left" bgcolor="#3B5998">
    <ul id="MenuBar1" class="MenuBarHorizontal"></ul>
      </td>
    </td>
  </tr>
  <tr align="center">
 	<td width="100%"><strong>Ódenes Registradas</strong></td>
 </tr>
 </table>
   </form>
  <form action="crearexcel.php" method="post" target="_blank">
<input type=image src="../images/excel.png" width="25" height="25" title="Exportar Archivo Excel"/>
</form>
 <table width="1024" align="center" border="1" style="border-collapse:collapse;" cellspacing="0" cellpadding="0" >
 <?php
		 echo '<tr bgcolor="#B7D6EC" width="1024">';
					echo '<td align="center">'."Estado".'</td>';
					echo '<td align="center">'."Estado de Envío".'</td>';
					echo '<td align="center">'."Reenvío".'</td>';
					echo '<td align="center">'."Tracking".'</td>';
					echo '<td align="center">'."Compañia".'</td>';
					echo '<td align="center">'."eBinv".'</td>';
					echo '<td align="center">'."Fecha de Órden".'</td>';
					echo '<td align="center">'."Enviar a".'</td>';
					echo '<td align="center">'."Enviar a2".'</td>';
					echo '<td align="center">'."Dirección".'</td>';
					echo '<td align="center">'."Dirección2".'</td>';
					echo '<td align="center">'."Ciudad".'</td>';
					echo '<td align="center">'."Estado".'</td>';
					echo '<td align="center">'."Cod. Postal".'</td>';
					echo '<td align="center">'."Teléfono".'</td>';
					echo '<td align="center">'."Cobrar a".'</td>';
					echo '<td align="center">'."Cobrar a2".'</td>';
					echo '<td align="center">'."Teléfono".'</td>';
					echo '<td align="center">'."Ponumber".'</td>'; 
					echo '<td align="center">'."Custnumber".'</td>';
					echo '<td align="center">'."Fecha de Vuelo".'</td>';
					echo '<td align="center">'."Fecha de Entrega".'</td>';
					echo '<td align="center">'."SatDel".'</td>';
					echo '<td align="center">'."Cantidad".'</td>';
					echo '<td align="center">'."Producto".'</td>';
					echo '<td align="center">'."Desc. Prod.".'</td>';
					echo '<td align="center">'."Largo".'</td>';
					echo '<td align="center"s>'."Ancho".'</td>';
					echo '<td align="center">'."Alto".'</td>';
					echo '<td align="center">'."Peso Kg".'</td>';
					echo '<td align="center">'."Valor Dcl".'</td>';
					echo '<td align="center">'."Mensaje".'</td>';
					echo '<td align="center">'."Servicio".'</td>'; 
					echo '<td align="center">'."Tipo Pack".'</td>';
					echo '<td align="center">'."GenDesc".'</td>';
					echo '<td align="center">'."País de envío".'</td>'; 
					echo '<td align="center">'."Moneda".'</td>';
					echo '<td align="center">'."Origen".'</td>';
					echo '<td align="center">'."UOM".'</td>'; 
					echo '<td align="center">'."TPComp".'</td>';
					echo '<td align="center">'."TPAttn".'</td>';
					echo '<td align="center">'."TPAdd1".'</td>';
					echo '<td align="center">'."TPCity".'</td>'; 
					echo '<td align="center">'."TPState".'</td>'; 
					echo '<td align="center">'."TPCtrye".'</td>';
					echo '<td align="center"s>'."TPZip".'</td>'; 
					echo '<td align="center">'."TPPhone".'</td>';
					echo '<td align="center">'."TPAcct".'</td>';
					echo '<td align="center"s>'."Finca".'</td>';
	echo "</tr>";
	
	//verificar el filtro para buscar
	if($fecha_inicio0 != null && $fecha_fin0 != null){
		$sql = "select *
FROM
tblorden
INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
WHERE `delivery_traking` BETWEEN '".$fecha_inicio0."' AND '".$fecha_fin0."' AND status= 'New'";

		//echo ("Fecha 1");	
  // Selecionar todos los campos de la base de datops para el excel a exportar
  	if($pais!= null){
			$sql = $sql." AND `cppais_envio`= '".$pais."'";
	}
	
	if($origen!= null){
			$sql = $sql." AND `origen`= '".$origen."'";
	}	

 // echo $sql;
  $query=mysql_query($sql);
  //$total = mysql_num_rows($query);
  while($com=mysql_fetch_array($query))
  {
	  			if(strcmp($com['status'],'New') == 0 ){
					$total++;
					if($com['estado_orden'] == 'Canceled'){
					echo '<tr bgcolor="#CC0000">';
					}else{
					echo "<tr>";	
					}
					echo "<td>".$com['estado_orden']." </td>"; //ya
					echo "<td>".$com['status']." </td>"; //ya
					echo "<td>".$com['reenvio']." </td>"; //ya	
					echo "<td>".$com['tracking']." </td>"; //ya	
					echo "<td>".$com['nombre_compania']." </td>"; //ya
					echo "<td>".$com['eBing']." </td>"; //ya
					echo "<td>".$com['order_date']." </td>"; //ya
					echo "<td>".$com['shipto1']." </td>"; //ya
					echo "<td>".$com['shipto2']." </td>"; //ya
					echo "<td>".$com['direccion']." </td>"; //ya
					echo "<td>".$com['direccion2']." </td>"; //ya
					echo "<td>".$com['cpcuidad_shipto']." </td>";//ya
					echo "<td>".$com['cpestado_shipto']." </td>"; //ya
					echo "<td>".$com['cpzip_shipto']." </td>"; //ya
					echo "<td>".$com['cptelefono_shipto']." </td>"; //ya
					echo "<td>".$com['soldto1']." </td>"; //ya
					echo "<td>".$com['soldto2']." </td>"; //ya
					echo "<td>".$com['cpstphone_soldto']." </td>"; //ya
					echo "<td>".$com['Ponumber']." </td>"; //ya
					echo "<td>".$com['Custnumber']." </td>"; //ya
					echo "<td>".$com['ShipDT_traking']." </td>"; //ya
					echo "<td>".$com['delivery_traking']." </td>"; //ya
					echo "<td>".$com['satdel']." </td>"; //ya
					echo "<td>".$com['cpcantidad']." </td>"; //ya		
					echo "<td>".$com['cpitem']." </td>";//ya
					echo "<td>".$com['prod_descripcion']." </td>"; //ya
					echo "<td>".$com['length']." </td>"; //ya
					echo "<td>".$com['width']." </td>"; //ya
					echo "<td>".$com['heigth']." </td>"; //ya
					echo "<td>".$com['wheigthKg']." </td>"; //ya
					echo "<td>".$com['dclvalue']." </td>"; //ya
					echo "<td>".$com['cpmensaje']." </td>"; //ya
					echo "<td>".$com['cpservicio']." </td>"; //ya
					echo "<td>".$com['cptipo_pack']." </td>"; //ya
					echo "<td>".$com['gen_desc']." </td>"; //ya
					echo "<td>".$com['cppais_envio']." </td>"; //ya
					echo "<td>".$com['cpmoneda']." </td>"; //ya
					echo "<td>".$com['cporigen']." </td>"; //ya		
					echo "<td>".$com['cpUOM']." </td>"; //ya
					echo "<td>".$com['empresa']." </td>"; //ya
					echo "<td>".$com['director']." </td>"; //ya
					echo "<td>".$com['direccion_director']." </td>"; //ya
					echo "<td>".$com['cuidad_director']." </td>"; //ya
					echo "<td>".$com['estado_director']." </td>"; //ya
					echo "<td>".$com['pais_director']." </td>"; //ya
					echo "<td>".$com['tpzip_director']." </td>"; //ya
					echo "<td>".$com['tpphone_director']." </td>"; //ya
					echo "<td>".$com['tpacct_director']." </td>"; //ya
					echo "<td>".$com['farm']." </td>"; //ya
				echo "</tr>";
				}	
		}
  	  session_destroy();
	  session_start();
	  $sql." AND `status`= 'New'";
	  $_SESSION["sql"]=$sql;
	  $_SESSION["login"]=$user;
	  $_SESSION["rol"]=$rol;
	  $_SESSION["pais"]= $pais;
	  
	}
	
	//verificar el filtro para buscar
	if($fecha_inicio != '' && $fecha_fin != ''){
		//echo "Ordate";
		$sql = "select *
		FROM
		tblorden
		INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
		INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
		INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
		INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
		INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `order_date` BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
				//echo ("Fecha 1");	
		  // Selecionar todos los campos de la base de datops para el excel a exportar
			if($pais!= null){
					$sql = $sql." AND `cppais_envio`= '".$pais."'";
			}
		
			$sql = $sql." AND `origen`= '".$origen."'";
		
		
		  //echo $sql;
		  $query=mysql_query($sql);
		  //$total = mysql_num_rows($query);
		
		  while($com=mysql_fetch_array($query))
		  {
				 if(strcmp($com['status'],'Ready to ship') == 0 || strcmp($com['status'],'New') == 0){
					//echo "entro aqui";
					$total++;
					if($com['estado_orden'] == 'Canceled'){
					echo '<tr bgcolor="#CC0000">';
					}else{
					echo "<tr>";	
					}
					echo "<td>".$com['estado_orden']." </td>"; //ya
					echo "<td>".$com['status']." </td>"; //ya					
					echo "<td>".$com['reenvio']." </td>"; //ya	
					echo "<td>".$com['tracking']." </td>"; //ya		
					echo "<td>".$com['nombre_compania']." </td>"; //ya
					echo "<td>".$com['eBing']." </td>"; //ya
					echo "<td>".$com['order_date']." </td>"; //ya
					echo "<td>".$com['shipto1']." </td>"; //ya
					echo "<td>".$com['shipto2']." </td>"; //ya
					echo "<td>".$com['direccion']." </td>"; //ya
					echo "<td>".$com['direccion2']." </td>"; //ya
					echo "<td>".$com['cpcuidad_shipto']." </td>";//ya
					echo "<td>".$com['cpestado_shipto']." </td>"; //ya
					echo "<td>".$com['cpzip_shipto']." </td>"; //ya
					echo "<td>".$com['cptelefono_shipto']." </td>"; //ya
					echo "<td>".$com['soldto1']." </td>"; //ya
					echo "<td>".$com['soldto2']." </td>"; //ya
					echo "<td>".$com['cpstphone_soldto']." </td>"; //ya
					echo "<td>".$com['Ponumber']." </td>"; //ya
					echo "<td>".$com['Custnumber']." </td>"; //ya
					echo "<td>".$com['ShipDT_traking']." </td>"; //ya
					echo "<td>".$com['delivery_traking']." </td>"; //ya
					echo "<td>".$com['satdel']." </td>"; //ya
					echo "<td>".$com['cpcantidad']." </td>"; //ya		
					echo "<td>".$com['cpitem']." </td>";//ya
					echo "<td>".$com['prod_descripcion']." </td>"; //ya
					echo "<td>".$com['length']." </td>"; //ya
					echo "<td>".$com['width']." </td>"; //ya
					echo "<td>".$com['heigth']." </td>"; //ya
					echo "<td>".$com['wheigthKg']." </td>"; //ya
					echo "<td>".$com['dclvalue']." </td>"; //ya
					echo "<td>".$com['cpmensaje']." </td>"; //ya
					echo "<td>".$com['cpservicio']." </td>"; //ya
					echo "<td>".$com['cptipo_pack']." </td>"; //ya
					echo "<td>".$com['gen_desc']." </td>"; //ya
					echo "<td>".$com['cppais_envio']." </td>"; //ya
					echo "<td>".$com['cpmoneda']." </td>"; //ya
					echo "<td>".$com['cporigen']." </td>"; //ya		
					echo "<td>".$com['cpUOM']." </td>"; //ya
					echo "<td>".$com['empresa']." </td>"; //ya
					echo "<td>".$com['director']." </td>"; //ya
					echo "<td>".$com['direccion_director']." </td>"; //ya
					echo "<td>".$com['cuidad_director']." </td>"; //ya
					echo "<td>".$com['estado_director']." </td>"; //ya
					echo "<td>".$com['pais_director']." </td>"; //ya
					echo "<td>".$com['tpzip_director']." </td>"; //ya
					echo "<td>".$com['tpphone_director']." </td>"; //ya
					echo "<td>".$com['tpacct_director']." </td>"; //ya
					echo "<td>".$com['farm']." </td>"; //ya
				echo "</tr>";
				}	
				
		  }
			  session_destroy();
			  session_start();
			  $sql." AND (`status`= 'Ready to ship' OR `status`= 'New')";
			  $_SESSION["sql"]=$sql;
			  $_SESSION["login"]=$user;
			  $_SESSION["rol"]=$rol;
			  $_SESSION["pais"]= $pais;
			  //break;
			  
			}
	
	//verificar el filtro para buscar
	if($fecha_inicio1 != null && $fecha_fin1 != null){
		//echo ("Fecha 2");	
  // Selecionar todos los campos de la base de datops para el excel a exportar
	$sql = "select *
			FROM
			tblorden
			INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
			INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
			INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
			INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
			INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `ShipDT_traking` BETWEEN '".$fecha_inicio1."' AND '".$fecha_fin1."'";
				
				if($pais!= null){
						$sql = $sql." AND `cppais_envio`= '".$pais."'";
				}
			   
			  $sql = $sql." AND `origen`= '".$origen."'";
				
			 $query=mysql_query($sql);
			  //$total = mysql_num_rows($query);
			
			  while($com=mysql_fetch_array($query))
			  {
				 
					 if(strcmp($com['status'],'Ready to ship') == 0 || strcmp($com['status'],'New') == 0){
						$total++;
						if($com['estado_orden'] == 'Canceled'){
						echo '<tr bgcolor="#CC0000">';
						}else{
						echo "<tr>";	
						}
						echo "<td>".$com['estado_orden']." </td>"; //ya
						echo "<td>".$com['status']." </td>"; //ya					
						echo "<td>".$com['reenvio']." </td>"; //ya	
					echo "<td>".$com['tracking']." </td>"; //ya		
						echo "<td>".$com['nombre_compania']." </td>"; //ya
						echo "<td>".$com['eBing']." </td>"; //ya
						echo "<td>".$com['order_date']." </td>"; //ya
						echo "<td>".$com['shipto1']." </td>"; //ya
						echo "<td>".$com['shipto2']." </td>"; //ya
						echo "<td>".$com['direccion']." </td>"; //ya
						echo "<td>".$com['direccion2']." </td>"; //ya
						echo "<td>".$com['cpcuidad_shipto']." </td>";//ya
						echo "<td>".$com['cpestado_shipto']." </td>"; //ya
						echo "<td>".$com['cpzip_shipto']." </td>"; //ya
						echo "<td>".$com['cptelefono_shipto']." </td>"; //ya
						echo "<td>".$com['soldto1']." </td>"; //ya
						echo "<td>".$com['soldto2']." </td>"; //ya
						echo "<td>".$com['cpstphone_soldto']." </td>"; //ya
						echo "<td>".$com['Ponumber']." </td>"; //ya
						echo "<td>".$com['Custnumber']." </td>"; //ya
						echo "<td>".$com['ShipDT_traking']." </td>"; //ya
						echo "<td>".$com['delivery_traking']." </td>"; //ya
						echo "<td>".$com['satdel']." </td>"; //ya
						echo "<td>".$com['cpcantidad']." </td>"; //ya		
						echo "<td>".$com['cpitem']." </td>";//ya
						echo "<td>".$com['prod_descripcion']." </td>"; //ya
						echo "<td>".$com['length']." </td>"; //ya
						echo "<td>".$com['width']." </td>"; //ya
						echo "<td>".$com['heigth']." </td>"; //ya
						echo "<td>".$com['wheigthKg']." </td>"; //ya
						echo "<td>".$com['dclvalue']." </td>"; //ya
						echo "<td>".$com['cpmensaje']." </td>"; //ya
						echo "<td>".$com['cpservicio']." </td>"; //ya
						echo "<td>".$com['cptipo_pack']." </td>"; //ya
						echo "<td>".$com['gen_desc']." </td>"; //ya
						echo "<td>".$com['cppais_envio']." </td>"; //ya
						echo "<td>".$com['cpmoneda']." </td>"; //ya
						echo "<td>".$com['cporigen']." </td>"; //ya		
						echo "<td>".$com['cpUOM']." </td>"; //ya
						echo "<td>".$com['empresa']." </td>"; //ya
						echo "<td>".$com['director']." </td>"; //ya
						echo "<td>".$com['direccion_director']." </td>"; //ya
						echo "<td>".$com['cuidad_director']." </td>"; //ya
						echo "<td>".$com['estado_director']." </td>"; //ya
						echo "<td>".$com['pais_director']." </td>"; //ya
						echo "<td>".$com['tpzip_director']." </td>"; //ya
						echo "<td>".$com['tpphone_director']." </td>"; //ya
						echo "<td>".$com['tpacct_director']." </td>"; //ya
						echo "<td>".$com['farm']." </td>"; //ya
					echo "</tr>";
					}	
					
			  }
				  session_destroy();
				  session_start();
				  $sql." AND (`status`= 'Ready to ship' OR `status`= 'New')";
				  $_SESSION["sql"]=$sql;
				  $_SESSION["login"]=$user;
				  $_SESSION["rol"]=$rol;
				  $_SESSION["pais"]= $pais;
				}
	
	//verificar el filtro para buscar
	if($fecha_inicio2 != null && $fecha_fin2 != null){
	//echo ("Fecha 3");	
  // Selecionar todos los campos de la base de datops para el excel a exportar
	$sql = "select *
FROM
tblorden
INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `delivery_traking` BETWEEN '".$fecha_inicio2."' AND '".$fecha_fin2."'";
	
	if($pais!= null){
			$sql = $sql." AND `cppais_envio`= '".$pais."'";
	}
	
  $sql = $sql." AND `origen`= '".$origen."'";

 $query=mysql_query($sql);
  //$total = mysql_num_rows($query);

  while($com=mysql_fetch_array($query))
  {
  	   
  	     if(strcmp($com['status'],'Ready to ship') == 0 || strcmp($com['status'],'New') == 0 ){
			$total++;
			if($com['estado_orden'] == 'Canceled'){
			echo '<tr bgcolor="#CC0000">';
			}else{
			echo "<tr>";	
			}
			echo "<td>".$com['estado_orden']." </td>"; //ya
			echo "<td>".$com['status']." </td>"; //ya					
			echo "<td>".$com['reenvio']." </td>"; //ya	
					echo "<td>".$com['tracking']." </td>"; //ya		
			echo "<td>".$com['nombre_compania']." </td>"; //ya
			echo "<td>".$com['eBing']." </td>"; //ya
			echo "<td>".$com['order_date']." </td>"; //ya
			echo "<td>".$com['shipto1']." </td>"; //ya
			echo "<td>".$com['shipto2']." </td>"; //ya
			echo "<td>".$com['direccion']." </td>"; //ya
			echo "<td>".$com['direccion2']." </td>"; //ya
			echo "<td>".$com['cpcuidad_shipto']." </td>";//ya
			echo "<td>".$com['cpestado_shipto']." </td>"; //ya
			echo "<td>".$com['cpzip_shipto']." </td>"; //ya
			echo "<td>".$com['cptelefono_shipto']." </td>"; //ya
			echo "<td>".$com['soldto1']." </td>"; //ya
			echo "<td>".$com['soldto2']." </td>"; //ya
			echo "<td>".$com['cpstphone_soldto']." </td>"; //ya
			echo "<td>".$com['Ponumber']." </td>"; //ya
			echo "<td>".$com['Custnumber']." </td>"; //ya
			echo "<td>".$com['ShipDT_traking']." </td>"; //ya
			echo "<td>".$com['delivery_traking']." </td>"; //ya
			echo "<td>".$com['satdel']." </td>"; //ya
			echo "<td>".$com['cpcantidad']." </td>"; //ya		
			echo "<td>".$com['cpitem']." </td>";//ya
			echo "<td>".$com['prod_descripcion']." </td>"; //ya
			echo "<td>".$com['length']." </td>"; //ya
					echo "<td>".$com['width']." </td>"; //ya
					echo "<td>".$com['heigth']." </td>"; //ya
					echo "<td>".$com['wheigthKg']." </td>"; //ya
					echo "<td>".$com['dclvalue']." </td>"; //ya
			echo "<td>".$com['cpmensaje']." </td>"; //ya
			echo "<td>".$com['cpservicio']." </td>"; //ya
			echo "<td>".$com['cptipo_pack']." </td>"; //ya
			echo "<td>".$com['gen_desc']." </td>"; //ya
			echo "<td>".$com['cppais_envio']." </td>"; //ya
			echo "<td>".$com['cpmoneda']." </td>"; //ya
			echo "<td>".$com['cporigen']." </td>"; //ya		
			echo "<td>".$com['cpUOM']." </td>"; //ya
			echo "<td>".$com['empresa']." </td>"; //ya
			echo "<td>".$com['director']." </td>"; //ya
			echo "<td>".$com['direccion_director']." </td>"; //ya
			echo "<td>".$com['cuidad_director']." </td>"; //ya
			echo "<td>".$com['estado_director']." </td>"; //ya
			echo "<td>".$com['pais_director']." </td>"; //ya
			echo "<td>".$com['tpzip_director']." </td>"; //ya
			echo "<td>".$com['tpphone_director']." </td>"; //ya
			echo "<td>".$com['tpacct_director']." </td>"; //ya
			echo "<td>".$com['farm']." </td>"; //ya
		echo "</tr>";
		}	
		
  }
  	 session_destroy();
	 session_start();
	 $sql = $sql." AND (`status`= 'Ready to ship' OR `status`= 'New')";
	 $_SESSION["sql"]=$sql;
	  $_SESSION["login"]=$user;
	 $_SESSION["rol"]=$rol;
	  $_SESSION["pais"]= $pais;
	}
	

	//verificar el filtro para buscar
	if($tracking != null){
		//echo "Tracking";
			$sql = "select *
FROM
tblorden
INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `tracking`= '".$tracking."'";
					
	if($pais!= null){
			$sql = $sql." AND `cppais_envio`= '".$pais."'";
	}
	
	if($origen!= null){
			$sql = $sql." AND `origen`= '".$origen."'";
	}
	
  $query=mysql_query($sql);
  //$total = mysql_num_rows($query);
		  while($com=mysql_fetch_array($query))
		  {
			   //if(strcmp($com['status'],'Ready to ship') == 0 || strcmp($com['status'],'New') == 0 ){
					$total++;
					if($com['estado_orden'] == 'Canceled'){
					echo '<tr bgcolor="#CC0000">';
					}else{
					echo "<tr>";	
					}
					echo "<td>".$com['estado_orden']." </td>"; //ya
					echo "<td>".$com['status']." </td>"; //ya					
					echo "<td>".$com['reenvio']." </td>"; //ya	
					echo "<td>".$com['tracking']." </td>"; //ya
					echo "<td>".$com['nombre_compania']." </td>"; //ya
					echo "<td>".$com['eBing']." </td>"; //ya
					echo "<td>".$com['order_date']." </td>"; //ya
					echo "<td>".$com['shipto1']." </td>"; //ya
					echo "<td>".$com['shipto2']." </td>"; //ya
					echo "<td>".$com['direccion']." </td>"; //ya
					echo "<td>".$com['direccion2']." </td>"; //ya
					echo "<td>".$com['cpcuidad_shipto']." </td>";//ya
					echo "<td>".$com['cpestado_shipto']." </td>"; //ya
					echo "<td>".$com['cpzip_shipto']." </td>"; //ya
					echo "<td>".$com['cptelefono_shipto']." </td>"; //ya
					echo "<td>".$com['soldto1']." </td>"; //ya
					echo "<td>".$com['soldto2']." </td>"; //ya
					echo "<td>".$com['cpstphone_soldto']." </td>"; //ya
					echo "<td>".$com['Ponumber']." </td>"; //ya
					echo "<td>".$com['Custnumber']." </td>"; //ya
					echo "<td>".$com['ShipDT_traking']." </td>"; //ya
					echo "<td>".$com['delivery_traking']." </td>"; //ya
					echo "<td>".$com['satdel']." </td>"; //ya
					echo "<td>".$com['cpcantidad']." </td>"; //ya		
					echo "<td>".$com['cpitem']." </td>";//ya
					echo "<td>".$com['prod_descripcion']." </td>"; //ya
					echo "<td>".$com['length']." </td>"; //ya
					echo "<td>".$com['width']." </td>"; //ya
					echo "<td>".$com['heigth']." </td>"; //ya
					echo "<td>".$com['wheigthKg']." </td>"; //ya
					echo "<td>".$com['dclvalue']." </td>"; //ya
					echo "<td>".$com['cpmensaje']." </td>"; //ya
					echo "<td>".$com['cpservicio']." </td>"; //ya
					echo "<td>".$com['cptipo_pack']." </td>"; //ya
					echo "<td>".$com['gen_desc']." </td>"; //ya
					echo "<td>".$com['cppais_envio']." </td>"; //ya
					echo "<td>".$com['cpmoneda']." </td>"; //ya
					echo "<td>".$com['cporigen']." </td>"; //ya		
					echo "<td>".$com['cpUOM']." </td>"; //ya
					echo "<td>".$com['empresa']." </td>"; //ya
					echo "<td>".$com['director']." </td>"; //ya
					echo "<td>".$com['direccion_director']." </td>"; //ya
					echo "<td>".$com['cuidad_director']." </td>"; //ya
					echo "<td>".$com['estado_director']." </td>"; //ya
					echo "<td>".$com['pais_director']." </td>"; //ya
					echo "<td>".$com['tpzip_director']." </td>"; //ya
					echo "<td>".$com['tpphone_director']." </td>"; //ya
					echo "<td>".$com['tpacct_director']." </td>"; //ya
					echo "<td>".$com['farm']." </td>"; //ya
				echo "</tr>";
				//}			
		  }
  	  session_destroy();
	  session_start();
	  $_SESSION["sql"]=$sql;
	  $_SESSION["login"]=$user;
	  $_SESSION["rol"]=$rol;
	  $_SESSION["pais"]= $pais;	
	  $_SESSION["alltrack"]= $alltrack;	
	  
}


	//verificar el filtro para buscar
	if($ponumber != null ){
		//echo ("Tracking");	
  // Selecionar todos los campos de la base de datops para el excel a exportar
	$sql = "select *
FROM
tblorden
INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `Ponumber`= '".$ponumber."'";
	
	if($pais!= null){
			$sql = $sql." AND `cppais_envio`= '".$pais."'";
	}
	
	$sql = $sql." AND `origen`= '".$origen."'";
	
 $query=mysql_query($sql);
  //$total = mysql_num_rows($query);

  while($com=mysql_fetch_array($query))
  {
  	    
  	    // if(strcmp($com['status'],'Ready to ship') == 0 || strcmp($com['status'],'New') == 0 ){
			$total++;
			if($com['estado_orden'] == 'Canceled'){
			echo '<tr bgcolor="#CC0000">';
			}else{
			echo "<tr>";	
			}
			echo "<td>".$com['estado_orden']." </td>"; //ya
			echo "<td>".$com['status']." </td>"; //ya					
			echo "<td>".$com['reenvio']." </td>"; //ya	
					echo "<td>".$com['tracking']." </td>"; //ya		
			echo "<td>".$com['nombre_compania']." </td>"; //ya
			echo "<td>".$com['eBing']." </td>"; //ya
			echo "<td>".$com['order_date']." </td>"; //ya
			echo "<td>".$com['shipto1']." </td>"; //ya
			echo "<td>".$com['shipto2']." </td>"; //ya
			echo "<td>".$com['direccion']." </td>"; //ya
			echo "<td>".$com['direccion2']." </td>"; //ya
			echo "<td>".$com['cpcuidad_shipto']." </td>";//ya
			echo "<td>".$com['cpestado_shipto']." </td>"; //ya
			echo "<td>".$com['cpzip_shipto']." </td>"; //ya
			echo "<td>".$com['cptelefono_shipto']." </td>"; //ya
			echo "<td>".$com['soldto1']." </td>"; //ya
			echo "<td>".$com['soldto2']." </td>"; //ya
			echo "<td>".$com['cpstphone_soldto']." </td>"; //ya
			echo "<td>".$com['Ponumber']." </td>"; //ya
			echo "<td>".$com['Custnumber']." </td>"; //ya
			echo "<td>".$com['ShipDT_traking']." </td>"; //ya
			echo "<td>".$com['delivery_traking']." </td>"; //ya
			echo "<td>".$com['satdel']." </td>"; //ya
			echo "<td>".$com['cpcantidad']." </td>"; //ya		
			echo "<td>".$com['cpitem']." </td>";//ya
			echo "<td>".$com['prod_descripcion']." </td>"; //ya
			echo "<td>".$com['length']." </td>"; //ya
					echo "<td>".$com['width']." </td>"; //ya
					echo "<td>".$com['heigth']." </td>"; //ya
					echo "<td>".$com['wheigthKg']." </td>"; //ya
					echo "<td>".$com['dclvalue']." </td>"; //ya
			echo "<td>".$com['cpmensaje']." </td>"; //ya
			echo "<td>".$com['cpservicio']." </td>"; //ya
			echo "<td>".$com['cptipo_pack']." </td>"; //ya
			echo "<td>".$com['gen_desc']." </td>"; //ya
			echo "<td>".$com['cppais_envio']." </td>"; //ya
			echo "<td>".$com['cpmoneda']." </td>"; //ya
			echo "<td>".$com['cporigen']." </td>"; //ya		
			echo "<td>".$com['cpUOM']." </td>"; //ya
			echo "<td>".$com['empresa']." </td>"; //ya
			echo "<td>".$com['director']." </td>"; //ya
			echo "<td>".$com['direccion_director']." </td>"; //ya
			echo "<td>".$com['cuidad_director']." </td>"; //ya
			echo "<td>".$com['estado_director']." </td>"; //ya
			echo "<td>".$com['pais_director']." </td>"; //ya
			echo "<td>".$com['tpzip_director']." </td>"; //ya
			echo "<td>".$com['tpphone_director']." </td>"; //ya
			echo "<td>".$com['tpacct_director']." </td>"; //ya
			echo "<td>".$com['farm']." </td>"; //ya
		echo "</tr>";
		//}	
		
  }
  	  session_destroy();
	  session_start();
	  $_SESSION["sql"]=$sql;
	  $_SESSION["login"]=$user;	
	  $_SESSION["rol"]=$rol;
	  $_SESSION["pais"]= $pais;  
	}
	
	//verificar el filtro para buscar
	if($custnumber != null ){
		//echo ("Tracking");	
  // Selecionar todos los campos de la base de datops para el excel a exportar
	$sql = "select *
FROM
tblorden
INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `Custnumber`='".$custnumber."'";
	
	if($pais!= null){
			$sql = $sql." AND `cppais_envio`= '".$pais."'";
	}
	
	$sql = $sql." AND `origen`= '".$origen."'";
	
 $query=mysql_query($sql);
  //$total = mysql_num_rows($query);

  while($com=mysql_fetch_array($query))
  {
  	    
  	    // if(strcmp($com['status'],'Ready to ship') == 0 || strcmp($com['status'],'New') == 0 ){
			$total++;
			if($com['estado_orden'] == 'Canceled'){
			echo '<tr bgcolor="#CC0000">';
			}else{
			echo "<tr>";	
			}
			echo "<td>".$com['estado_orden']." </td>"; //ya
			echo "<td>".$com['status']." </td>"; //ya					
			echo "<td>".$com['reenvio']." </td>"; //ya	
					echo "<td>".$com['tracking']." </td>"; //ya
			echo "<td>".$com['nombre_compania']." </td>"; //ya
			echo "<td>".$com['eBing']." </td>"; //ya
			echo "<td>".$com['order_date']." </td>"; //ya
			echo "<td>".$com['shipto1']." </td>"; //ya
			echo "<td>".$com['shipto2']." </td>"; //ya
			echo "<td>".$com['direccion']." </td>"; //ya
			echo "<td>".$com['direccion2']." </td>"; //ya
			echo "<td>".$com['cpcuidad_shipto']." </td>";//ya
			echo "<td>".$com['cpestado_shipto']." </td>"; //ya
			echo "<td>".$com['cpzip_shipto']." </td>"; //ya
			echo "<td>".$com['cptelefono_shipto']." </td>"; //ya
			echo "<td>".$com['soldto1']." </td>"; //ya
			echo "<td>".$com['soldto2']." </td>"; //ya
			echo "<td>".$com['cpstphone_soldto']." </td>"; //ya
			echo "<td>".$com['Ponumber']." </td>"; //ya
			echo "<td>".$com['Custnumber']." </td>"; //ya
			echo "<td>".$com['ShipDT_traking']." </td>"; //ya
			echo "<td>".$com['delivery_traking']." </td>"; //ya
			echo "<td>".$com['satdel']." </td>"; //ya
			echo "<td>".$com['cpcantidad']." </td>"; //ya		
			echo "<td>".$com['cpitem']." </td>";//ya
			echo "<td>".$com['prod_descripcion']." </td>"; //ya
			echo "<td>".$com['length']." </td>"; //ya
					echo "<td>".$com['width']." </td>"; //ya
					echo "<td>".$com['heigth']." </td>"; //ya
					echo "<td>".$com['wheigthKg']." </td>"; //ya
					echo "<td>".$com['dclvalue']." </td>"; //ya
			echo "<td>".$com['cpmensaje']." </td>"; //ya
			echo "<td>".$com['cpservicio']." </td>"; //ya
			echo "<td>".$com['cptipo_pack']." </td>"; //ya
			echo "<td>".$com['gen_desc']." </td>"; //ya
			echo "<td>".$com['cppais_envio']." </td>"; //ya
			echo "<td>".$com['cpmoneda']." </td>"; //ya
			echo "<td>".$com['cporigen']." </td>"; //ya		
			echo "<td>".$com['cpUOM']." </td>"; //ya
			echo "<td>".$com['empresa']." </td>"; //ya
			echo "<td>".$com['director']." </td>"; //ya
			echo "<td>".$com['direccion_director']." </td>"; //ya
			echo "<td>".$com['cuidad_director']." </td>"; //ya
			echo "<td>".$com['estado_director']." </td>"; //ya
			echo "<td>".$com['pais_director']." </td>"; //ya
			echo "<td>".$com['tpzip_director']." </td>"; //ya
			echo "<td>".$com['tpphone_director']." </td>"; //ya
			echo "<td>".$com['tpacct_director']." </td>"; //ya
			echo "<td>".$com['farm']." </td>"; //ya
		echo "</tr>";
		//}	
		
  }
  	  session_destroy();
	  session_start();
	  $_SESSION["sql"]=$sql;
	  $_SESSION["login"]=$user;
	  $_SESSION["rol"]=$rol;
	  $_SESSION["pais"]= $pais;	  
	}
	
	//verificar el filtro para buscar
	if($item != null ){
		//echo ("Item");	
  // Selecionar todos los campos de la base de datops para el excel a exportar
	$sql = "select *
FROM
tblorden
INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `cpitem`= '".$item."'";
	
	if($pais!= null){
			$sql = $sql." AND `cppais_envio`= '".$pais."'";
	}
	
	$sql = $sql." AND `origen`= '".$origen."'";
	
 $query=mysql_query($sql);
  //$total = mysql_num_rows($query);

  while($com=mysql_fetch_array($query))
  {
  	   
  	     //if(strcmp($com['status'],'Ready to ship') == 0 || strcmp($com['status'],'New') == 0 ){//|| strcmp($com['status'],'New') == 0 ){
			$total++;
			if($com['estado_orden'] == 'Canceled'){
			echo '<tr bgcolor="#CC0000">';
			}else{
			echo "<tr>";	
			}
			echo "<td>".$com['estado_orden']." </td>"; //ya
			echo "<td>".$com['status']." </td>"; //ya					
			echo "<td>".$com['reenvio']." </td>"; //ya	
					echo "<td>".$com['tracking']." </td>"; //ya	
			echo "<td>".$com['nombre_compania']." </td>"; //ya
			echo "<td>".$com['eBing']." </td>"; //ya
			echo "<td>".$com['order_date']." </td>"; //ya
			echo "<td>".$com['shipto1']." </td>"; //ya
			echo "<td>".$com['shipto2']." </td>"; //ya
			echo "<td>".$com['direccion']." </td>"; //ya
			echo "<td>".$com['direccion2']." </td>"; //ya
			echo "<td>".$com['cpcuidad_shipto']." </td>";//ya
			echo "<td>".$com['cpestado_shipto']." </td>"; //ya
			echo "<td>".$com['cpzip_shipto']." </td>"; //ya
			echo "<td>".$com['cptelefono_shipto']." </td>"; //ya
			echo "<td>".$com['soldto1']." </td>"; //ya
			echo "<td>".$com['soldto2']." </td>"; //ya
			echo "<td>".$com['cpstphone_soldto']." </td>"; //ya
			echo "<td>".$com['Ponumber']." </td>"; //ya
			echo "<td>".$com['Custnumber']." </td>"; //ya
			echo "<td>".$com['ShipDT_traking']." </td>"; //ya
			echo "<td>".$com['delivery_traking']." </td>"; //ya
			echo "<td>".$com['satdel']." </td>"; //ya
			echo "<td>".$com['cpcantidad']." </td>"; //ya		
			echo "<td>".$com['cpitem']." </td>";//ya
			echo "<td>".$com['prod_descripcion']." </td>"; //ya
			echo "<td>".$com['length']." </td>"; //ya
					echo "<td>".$com['width']." </td>"; //ya
					echo "<td>".$com['heigth']." </td>"; //ya
					echo "<td>".$com['wheigthKg']." </td>"; //ya
					echo "<td>".$com['dclvalue']." </td>"; //ya
			echo "<td>".$com['cpmensaje']." </td>"; //ya
			echo "<td>".$com['cpservicio']." </td>"; //ya
			echo "<td>".$com['cptipo_pack']." </td>"; //ya
			echo "<td>".$com['gen_desc']." </td>"; //ya
			echo "<td>".$com['cppais_envio']." </td>"; //ya
			echo "<td>".$com['cpmoneda']." </td>"; //ya
			echo "<td>".$com['cporigen']." </td>"; //ya		
			echo "<td>".$com['cpUOM']." </td>"; //ya
			echo "<td>".$com['empresa']." </td>"; //ya
			echo "<td>".$com['director']." </td>"; //ya
			echo "<td>".$com['direccion_director']." </td>"; //ya
			echo "<td>".$com['cuidad_director']." </td>"; //ya
			echo "<td>".$com['estado_director']." </td>"; //ya
			echo "<td>".$com['pais_director']." </td>"; //ya
			echo "<td>".$com['tpzip_director']." </td>"; //ya
			echo "<td>".$com['tpphone_director']." </td>"; //ya
			echo "<td>".$com['tpacct_director']." </td>"; //ya
			echo "<td>".$com['farm']." </td>"; //ya
		echo "</tr>";
		//}	
		
  }
  	  session_destroy();
	  session_start();
	  $_SESSION["sql"]=$sql;
	  $_SESSION["login"]=$user;
	  $_SESSION["rol"]=$rol;
	  $_SESSION["pais"]= $pais;	  
	}
	
	//verificar el filtro para buscar
	if($farm != null ){
		//echo ("Item");	
  // Selecionar todos los campos de la base de datops para el excel a exportar
	$sql = "select *
FROM
tblorden
INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `farm` LIKE '".$farm."%'";
	
	if($pais!= null){
			$sql = $sql." AND `cppais_envio`= '".$pais."'";
	}
	
	$sql = $sql." AND `origen`= '".$origen."'";
	
 $query=mysql_query($sql);
  //$total = mysql_num_rows($query);

  while($com=mysql_fetch_array($query))
  {
  	   
  	   // if(strcmp($com['status'],'Ready to ship') == 0 || strcmp($com['status'],'New') == 0 || strcmp($com['status'],'New') == 0 ){
			$total++;
			if($com['estado_orden'] == 'Canceled'){
			echo '<tr bgcolor="#CC0000">';
			}else{
			echo "<tr>";	
			}
			echo "<td>".$com['estado_orden']." </td>"; //ya
			echo "<td>".$com['status']." </td>"; //ya					
			echo "<td>".$com['reenvio']." </td>"; //ya	
					echo "<td>".$com['tracking']." </td>"; //ya		
			echo "<td>".$com['nombre_compania']." </td>"; //ya
			echo "<td>".$com['eBing']." </td>"; //ya
			echo "<td>".$com['order_date']." </td>"; //ya
			echo "<td>".$com['shipto1']." </td>"; //ya
			echo "<td>".$com['shipto2']." </td>"; //ya
			echo "<td>".$com['direccion']." </td>"; //ya
			echo "<td>".$com['direccion2']." </td>"; //ya
			echo "<td>".$com['cpcuidad_shipto']." </td>";//ya
			echo "<td>".$com['cpestado_shipto']." </td>"; //ya
			echo "<td>".$com['cpzip_shipto']." </td>"; //ya
			echo "<td>".$com['cptelefono_shipto']." </td>"; //ya
			echo "<td>".$com['soldto1']." </td>"; //ya
			echo "<td>".$com['soldto2']." </td>"; //ya
			echo "<td>".$com['cpstphone_soldto']." </td>"; //ya
			echo "<td>".$com['Ponumber']." </td>"; //ya
			echo "<td>".$com['Custnumber']." </td>"; //ya
			echo "<td>".$com['ShipDT_traking']." </td>"; //ya
			echo "<td>".$com['delivery_traking']." </td>"; //ya
			echo "<td>".$com['satdel']." </td>"; //ya
			echo "<td>".$com['cpcantidad']." </td>"; //ya		
			echo "<td>".$com['cpitem']." </td>";//ya
			echo "<td>".$com['prod_descripcion']." </td>"; //ya
			echo "<td>".$com['length']." </td>"; //ya
					echo "<td>".$com['width']." </td>"; //ya
					echo "<td>".$com['heigth']." </td>"; //ya
					echo "<td>".$com['wheigthKg']." </td>"; //ya
					echo "<td>".$com['dclvalue']." </td>"; //ya
			echo "<td>".$com['cpmensaje']." </td>"; //ya
			echo "<td>".$com['cpservicio']." </td>"; //ya
			echo "<td>".$com['cptipo_pack']." </td>"; //ya
			echo "<td>".$com['gen_desc']." </td>"; //ya
			echo "<td>".$com['cppais_envio']." </td>"; //ya
			echo "<td>".$com['cpmoneda']." </td>"; //ya
			echo "<td>".$com['cporigen']." </td>"; //ya		
			echo "<td>".$com['cpUOM']." </td>"; //ya
			echo "<td>".$com['empresa']." </td>"; //ya
			echo "<td>".$com['director']." </td>"; //ya
			echo "<td>".$com['direccion_director']." </td>"; //ya
			echo "<td>".$com['cuidad_director']." </td>"; //ya
			echo "<td>".$com['estado_director']." </td>"; //ya
			echo "<td>".$com['pais_director']." </td>"; //ya
			echo "<td>".$com['tpzip_director']." </td>"; //ya
			echo "<td>".$com['tpphone_director']." </td>"; //ya
			echo "<td>".$com['tpacct_director']." </td>"; //ya
			echo "<td>".$com['farm']." </td>"; //ya
		echo "</tr>";
		//}	
		
  }
  	  session_destroy();
	  session_start();
	  $_SESSION["sql"]=$sql;
	  $_SESSION["login"]=$user;	
	  $_SESSION["rol"]=$rol;
	  $_SESSION["pais"]= $pais;  
	}
	
	
	//verificar el filtro para buscar
	if($shipto1 != null ){
		//echo ("Item");	
  // Selecionar todos los campos de la base de datops para el excel a exportar
	$sql = "select *
FROM
tblorden
INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `shipto1` LIKE '".$shipto1."%'";
	
	if($pais!= null){
			$sql = $sql." AND `cppais_envio`= '".$pais."'";
	}
	
	$sql = $sql." AND `origen`= '".$origen."'";
	
 $query=mysql_query($sql);
  //$total = mysql_num_rows($query);

  while($com=mysql_fetch_array($query))
  {
  	   
  	    //if(strcmp($com['status'],'Ready to ship') == 0 || strcmp($com['status'],'New') == 0 ){//|| strcmp($com['status'],'New') == 0 ){
			$total++;
			if($com['estado_orden'] == 'Canceled'){
			echo '<tr bgcolor="#CC0000">';
			}else{
			echo "<tr>";	
			}
			echo "<td>".$com['estado_orden']." </td>"; //ya
			echo "<td>".$com['status']." </td>";
			echo "<td>".$com['reenvio']." </td>"; //ya	
					echo "<td>".$com['tracking']." </td>"; //ya
			echo "<td>".$com['nombre_compania']." </td>"; //ya
			echo "<td>".$com['eBing']." </td>"; //ya
			echo "<td>".$com['order_date']." </td>"; //ya
			echo "<td>".$com['shipto1']." </td>"; //ya
			echo "<td>".$com['shipto2']." </td>"; //ya
			echo "<td>".$com['direccion']." </td>"; //ya
			echo "<td>".$com['direccion2']." </td>"; //ya
			echo "<td>".$com['cpcuidad_shipto']." </td>";//ya
			echo "<td>".$com['cpestado_shipto']." </td>"; //ya
			echo "<td>".$com['cpzip_shipto']." </td>"; //ya
			echo "<td>".$com['cptelefono_shipto']." </td>"; //ya
			echo "<td>".$com['soldto1']." </td>"; //ya
			echo "<td>".$com['soldto2']." </td>"; //ya
			echo "<td>".$com['cpstphone_soldto']." </td>"; //ya
			echo "<td>".$com['Ponumber']." </td>"; //ya
			echo "<td>".$com['Custnumber']." </td>"; //ya
			echo "<td>".$com['ShipDT_traking']." </td>"; //ya
			echo "<td>".$com['delivery_traking']." </td>"; //ya
			echo "<td>".$com['satdel']." </td>"; //ya
			echo "<td>".$com['cpcantidad']." </td>"; //ya		
			echo "<td>".$com['cpitem']." </td>";//ya
			echo "<td>".$com['prod_descripcion']." </td>"; //ya
			echo "<td>".$com['length']." </td>"; //ya
					echo "<td>".$com['width']." </td>"; //ya
					echo "<td>".$com['heigth']." </td>"; //ya
					echo "<td>".$com['wheigthKg']." </td>"; //ya
					echo "<td>".$com['dclvalue']." </td>"; //ya
			echo "<td>".$com['cpmensaje']." </td>"; //ya
			echo "<td>".$com['cpservicio']." </td>"; //ya
			echo "<td>".$com['cptipo_pack']." </td>"; //ya
			echo "<td>".$com['gen_desc']." </td>"; //ya
			echo "<td>".$com['cppais_envio']." </td>"; //ya
			echo "<td>".$com['cpmoneda']." </td>"; //ya
			echo "<td>".$com['cporigen']." </td>"; //ya		
			echo "<td>".$com['cpUOM']." </td>"; //ya
			echo "<td>".$com['empresa']." </td>"; //ya
			echo "<td>".$com['director']." </td>"; //ya
			echo "<td>".$com['direccion_director']." </td>"; //ya
			echo "<td>".$com['cuidad_director']." </td>"; //ya
			echo "<td>".$com['estado_director']." </td>"; //ya
			echo "<td>".$com['pais_director']." </td>"; //ya
			echo "<td>".$com['tpzip_director']." </td>"; //ya
			echo "<td>".$com['tpphone_director']." </td>"; //ya
			echo "<td>".$com['tpacct_director']." </td>"; //ya
			echo "<td>".$com['farm']." </td>"; //ya
		echo "</tr>";
		//}	
		
  }
  	  session_destroy();
	  session_start();
	  $_SESSION["sql"]=$sql;
	  $_SESSION["login"]=$user;
	  $_SESSION["rol"]=$rol;
	  $_SESSION["pais"]= $pais;	  
	}
	
	//verificar el filtro para buscar
	if($direccion != null ){
		//echo ("Item");	
  // Selecionar todos los campos de la base de datops para el excel a exportar
	$sql = "select *
FROM
tblorden
INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `direccion` LIKE '".$direccion."%'";
	
	if($pais!= null){
			$sql = $sql." AND `cppais_envio`= '".$pais."'";
	}
	
	$sql = $sql." AND `origen`= '".$origen."'";
	
 $query=mysql_query($sql);
  //$total = mysql_num_rows($query);

  while($com=mysql_fetch_array($query))
  {
  	   
  	     //if(strcmp($com['status'],'Ready to ship') == 0 || strcmp($com['status'],'New') == 0 || strcmp($com['status'],'New') == 0 ){
			$total++;
			if($com['estado_orden'] == 'Canceled'){
			echo '<tr bgcolor="#CC0000">';
			}else{
			echo "<tr>";	
			}
			echo "<td>".$com['estado_orden']." </td>"; //ya
			echo "<td>".$com['status']." </td>";
			echo "<td>".$com['reenvio']." </td>"; //ya	
					echo "<td>".$com['tracking']." </td>"; //ya
			echo "<td>".$com['nombre_compania']." </td>"; //ya
			echo "<td>".$com['eBing']." </td>"; //ya
			echo "<td>".$com['order_date']." </td>"; //ya
			echo "<td>".$com['shipto1']." </td>"; //ya
			echo "<td>".$com['shipto2']." </td>"; //ya
			echo "<td>".$com['direccion']." </td>"; //ya
			echo "<td>".$com['direccion2']." </td>"; //ya
			echo "<td>".$com['cpcuidad_shipto']." </td>";//ya
			echo "<td>".$com['cpestado_shipto']." </td>"; //ya
			echo "<td>".$com['cpzip_shipto']." </td>"; //ya
			echo "<td>".$com['cptelefono_shipto']." </td>"; //ya
			echo "<td>".$com['soldto1']." </td>"; //ya
			echo "<td>".$com['soldto2']." </td>"; //ya
			echo "<td>".$com['cpstphone_soldto']." </td>"; //ya
			echo "<td>".$com['Ponumber']." </td>"; //ya
			echo "<td>".$com['Custnumber']." </td>"; //ya
			echo "<td>".$com['ShipDT_traking']." </td>"; //ya
			echo "<td>".$com['delivery_traking']." </td>"; //ya
			echo "<td>".$com['satdel']." </td>"; //ya
			echo "<td>".$com['cpcantidad']." </td>"; //ya		
			echo "<td>".$com['cpitem']." </td>";//ya
			echo "<td>".$com['prod_descripcion']." </td>"; //ya
			echo "<td>".$com['length']." </td>"; //ya
					echo "<td>".$com['width']." </td>"; //ya
					echo "<td>".$com['heigth']." </td>"; //ya
					echo "<td>".$com['wheigthKg']." </td>"; //ya
					echo "<td>".$com['dclvalue']." </td>"; //ya
			echo "<td>".$com['cpmensaje']." </td>"; //ya
			echo "<td>".$com['cpservicio']." </td>"; //ya
			echo "<td>".$com['cptipo_pack']." </td>"; //ya
			echo "<td>".$com['gen_desc']." </td>"; //ya
			echo "<td>".$com['cppais_envio']." </td>"; //ya
			echo "<td>".$com['cpmoneda']." </td>"; //ya
			echo "<td>".$com['cporigen']." </td>"; //ya		
			echo "<td>".$com['cpUOM']." </td>"; //ya
			echo "<td>".$com['empresa']." </td>"; //ya
			echo "<td>".$com['director']." </td>"; //ya
			echo "<td>".$com['direccion_director']." </td>"; //ya
			echo "<td>".$com['cuidad_director']." </td>"; //ya
			echo "<td>".$com['estado_director']." </td>"; //ya
			echo "<td>".$com['pais_director']." </td>"; //ya
			echo "<td>".$com['tpzip_director']." </td>"; //ya
			echo "<td>".$com['tpphone_director']." </td>"; //ya
			echo "<td>".$com['tpacct_director']." </td>"; //ya
			echo "<td>".$com['farm']." </td>"; //ya
		echo "</tr>";
		//}	
		
  }
  	  session_destroy();
	  session_start();
	  $_SESSION["sql"]=$sql;
	  $_SESSION["login"]=$user;
	  $_SESSION["rol"]=$rol;
	  $_SESSION["pais"]= $pais;	  
	}
	
	//verificar el filtro para buscar
	if($soldto1 != null ){
		//echo ("Item");	
  // Selecionar todos los campos de la base de datops para el excel a exportar
	$sql = "select *
FROM
tblorden
INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `soldto1`  LIKE '".$soldto1."%'";
	
	if($pais!= null){
			$sql = $sql." AND `cppais_envio`= '".$pais."'";
	}
	
	$sql = $sql." AND `origen`= '".$origen."'";
	
 $query=mysql_query($sql);
  //$total = mysql_num_rows($query);

  while($com=mysql_fetch_array($query))
  {
  	    
  	    // if(strcmp($com['status'],'Ready to ship') == 0 || strcmp($com['status'],'New') == 0 || strcmp($com['status'],'New') == 0 ){
			$total++;
			if($com['estado_orden'] == 'Canceled'){
			echo '<tr bgcolor="#CC0000">';
			}else{
			echo "<tr>";	
			}
			echo "<td>".$com['estado_orden']." </td>"; //ya
			echo "<td>".$com['status']." </td>";
			echo "<td>".$com['reenvio']." </td>"; //ya	
					echo "<td>".$com['tracking']." </td>"; //ya
			echo "<td>".$com['nombre_compania']." </td>"; //ya
			echo "<td>".$com['eBing']." </td>"; //ya
			echo "<td>".$com['order_date']." </td>"; //ya
			echo "<td>".$com['shipto1']." </td>"; //ya
			echo "<td>".$com['shipto2']." </td>"; //ya
			echo "<td>".$com['direccion']." </td>"; //ya
			echo "<td>".$com['direccion2']." </td>"; //ya
			echo "<td>".$com['cpcuidad_shipto']." </td>";//ya
			echo "<td>".$com['cpestado_shipto']." </td>"; //ya
			echo "<td>".$com['cpzip_shipto']." </td>"; //ya
			echo "<td>".$com['cptelefono_shipto']." </td>"; //ya
			echo "<td>".$com['soldto1']." </td>"; //ya
			echo "<td>".$com['soldto2']." </td>"; //ya
			echo "<td>".$com['cpstphone_soldto']." </td>"; //ya
			echo "<td>".$com['Ponumber']." </td>"; //ya
			echo "<td>".$com['Custnumber']." </td>"; //ya
			echo "<td>".$com['ShipDT_traking']." </td>"; //ya
			echo "<td>".$com['delivery_traking']." </td>"; //ya
			echo "<td>".$com['satdel']." </td>"; //ya
			echo "<td>".$com['cpcantidad']." </td>"; //ya		
			echo "<td>".$com['cpitem']." </td>";//ya
			echo "<td>".$com['prod_descripcion']." </td>"; //ya
			echo "<td>".$com['length']." </td>"; //ya
					echo "<td>".$com['width']." </td>"; //ya
					echo "<td>".$com['heigth']." </td>"; //ya
					echo "<td>".$com['wheigthKg']." </td>"; //ya
					echo "<td>".$com['dclvalue']." </td>"; //ya
			echo "<td>".$com['cpmensaje']." </td>"; //ya
			echo "<td>".$com['cpservicio']." </td>"; //ya
			echo "<td>".$com['cptipo_pack']." </td>"; //ya
			echo "<td>".$com['gen_desc']." </td>"; //ya
			echo "<td>".$com['cppais_envio']." </td>"; //ya
			echo "<td>".$com['cpmoneda']." </td>"; //ya
			echo "<td>".$com['cporigen']." </td>"; //ya		
			echo "<td>".$com['cpUOM']." </td>"; //ya
			echo "<td>".$com['empresa']." </td>"; //ya
			echo "<td>".$com['director']." </td>"; //ya
			echo "<td>".$com['direccion_director']." </td>"; //ya
			echo "<td>".$com['cuidad_director']." </td>"; //ya
			echo "<td>".$com['estado_director']." </td>"; //ya
			echo "<td>".$com['pais_director']." </td>"; //ya
			echo "<td>".$com['tpzip_director']." </td>"; //ya
			echo "<td>".$com['tpphone_director']." </td>"; //ya
			echo "<td>".$com['tpacct_director']." </td>"; //ya
			echo "<td>".$com['farm']." </td>"; //ya
		echo "</tr>";
	//	}	
		
  }
  	  session_destroy();
	  session_start();
	  $_SESSION["sql"]=$sql;
	  $_SESSION["login"]=$user;	
	  $_SESSION["rol"]=$rol;
	  $_SESSION["pais"]= $pais;  
	}
	
	//verificar el filtro para buscar
	if($cpdireccion_soldto != null ){
		//echo ("Item");	
  // Selecionar todos los campos de la base de datops para el excel a exportar
	$sql = "select *
FROM
tblorden
INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE `cpdireccion_soldto` LIKE '".$cpdireccion_soldto."%'";
	
	if($pais!= null){
			$sql = $sql." AND `cppais_envio`= '".$pais."'";
	}
	
	$sql = $sql." AND `origen`= '".$origen."'";
	
 $query=mysql_query($sql);
  //$total = mysql_num_rows($query);

  while($com=mysql_fetch_array($query))
  {
  	   
  	    // if(strcmp($com['status'],'Ready to ship') == 0 || strcmp($com['status'],'New') == 0 || strcmp($com['status'],'New') == 0 ){
			$total++;
			if($com['estado_orden'] == 'Canceled'){
			echo '<tr bgcolor="#CC0000">';
			}else{
			echo "<tr>";	
			}
			echo "<td>".$com['estado_orden']." </td>"; //ya
			echo "<td>".$com['status']." </td>"; 
			echo "<td>".$com['reenvio']." </td>"; //ya	
					echo "<td>".$com['tracking']." </td>"; //ya
			echo "<td>".$com['nombre_compania']." </td>"; //ya
			echo "<td>".$com['eBing']." </td>"; //ya
			echo "<td>".$com['order_date']." </td>"; //ya
			echo "<td>".$com['shipto1']." </td>"; //ya
			echo "<td>".$com['shipto2']." </td>"; //ya
			echo "<td>".$com['direccion']." </td>"; //ya
			echo "<td>".$com['direccion2']." </td>"; //ya
			echo "<td>".$com['cpcuidad_shipto']." </td>";//ya
			echo "<td>".$com['cpestado_shipto']." </td>"; //ya
			echo "<td>".$com['cpzip_shipto']." </td>"; //ya
			echo "<td>".$com['cptelefono_shipto']." </td>"; //ya
			echo "<td>".$com['soldto1']." </td>"; //ya
			echo "<td>".$com['soldto2']." </td>"; //ya
			echo "<td>".$com['cpstphone_soldto']." </td>"; //ya
			echo "<td>".$com['Ponumber']." </td>"; //ya
			echo "<td>".$com['Custnumber']." </td>"; //ya
			echo "<td>".$com['ShipDT_traking']." </td>"; //ya
			echo "<td>".$com['delivery_traking']." </td>"; //ya
			echo "<td>".$com['satdel']." </td>"; //ya
			echo "<td>".$com['cpcantidad']." </td>"; //ya		
			echo "<td>".$com['cpitem']." </td>";//ya
			echo "<td>".$com['prod_descripcion']." </td>"; //ya
			echo "<td>".$com['length']." </td>"; //ya
					echo "<td>".$com['width']." </td>"; //ya
					echo "<td>".$com['heigth']." </td>"; //ya
					echo "<td>".$com['wheigthKg']." </td>"; //ya
					echo "<td>".$com['dclvalue']." </td>"; //ya
			echo "<td>".$com['cpmensaje']." </td>"; //ya
			echo "<td>".$com['cpservicio']." </td>"; //ya
			echo "<td>".$com['cptipo_pack']." </td>"; //ya
			echo "<td>".$com['gen_desc']." </td>"; //ya
			echo "<td>".$com['cppais_envio']." </td>"; //ya
			echo "<td>".$com['cpmoneda']." </td>"; //ya
			echo "<td>".$com['cporigen']." </td>"; //ya		
			echo "<td>".$com['cpUOM']." </td>"; //ya
			echo "<td>".$com['empresa']." </td>"; //ya
			echo "<td>".$com['director']." </td>"; //ya
			echo "<td>".$com['direccion_director']." </td>"; //ya
			echo "<td>".$com['cuidad_director']." </td>"; //ya
			echo "<td>".$com['estado_director']." </td>"; //ya
			echo "<td>".$com['pais_director']." </td>"; //ya
			echo "<td>".$com['tpzip_director']." </td>"; //ya
			echo "<td>".$com['tpphone_director']." </td>"; //ya
			echo "<td>".$com['tpacct_director']." </td>"; //ya
			echo "<td>".$com['farm']." </td>"; //ya
		echo "</tr>";
		//}	
		
  }
  	  session_destroy();
	  session_start();
	  $_SESSION["sql"]=$sql;
	  $_SESSION["login"]=$user;	
	  $_SESSION["rol"]=$rol;
	  $_SESSION["pais"]= $pais;  
	}
	
  ?>
  <tr>
  <td>
  </table>
  <form action="crearexcel.php" method="post" target="_blank">
<input type=image src="../images/excel.png" width="25" height="25" title="Exportar Archivo Excel"/>
</form>
Charged total orders: <?php echo  $total?>
 <table width="1024" border="0" align="center">
   <tr>
    <td height="36" align="center" bgcolor="#3B5998" colspan="5"><strong><font color="#FFFFFF">Bit <img src="../images/r.png" width="15" height="15"/> 2015 versión 3 </font></strong></td>
  </tr>
</table>
</form>
</body>
</html>