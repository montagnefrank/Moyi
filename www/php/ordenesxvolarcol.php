<?php
    session_start();
	include ("conectarSQL.php");
    include ("conexion.php");
	include ("seguridad.php");
	$user     =  $_SESSION["login"];
	
	//hacer conexion
    $conection = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error()); 
	$id = $_GET['id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Orders by Flying</title>
<script type="text/javascript" src="../js/script.js"></script>
 <link rel="icon" type="image/png" href="../images/favicon.ico" />
  <link rel="stylesheet" type="text/css" media="all" href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="../js/jquery-1.2.1.pack.js"></script>
  <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
  <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
      <script type="text/javascript" src="../js/calendar.js"></script>
  <script type="text/javascript" src="../js/calendar-en.js"></script>
<script type="text/javascript" src="../js/calendar-setup.js"></script>
</head>
<body background="../images/fondo.jpg">
<form id="form" name="form" method="post">
<table width="1024" border="0" align="center">
  <tr>
    <td height="133" align="center" width="100%"><img src="../images/logo.png" width="328" height="110" /></td>
  </tr>
        <tr>
  	<td width="436" colspan="2"><input type="image" src="../images/home.png" height="40" width="30" title="Inicio" formaction="../main.php?panel=mainpanel.php">
    </td>
  </tr>
  <tr>
    <td height="34" width="100%" align="center" bgcolor="#3B5998"><ul id="MenuBar1" class="MenuBarHorizontal">
          </ul></td>
  </tr>
  </form>
<tr>
    <td id="inicio" bgcolor="#CCCCCC" height="100"> 
    <table width="1024" border="0" align="center"> 
  <tr>
    <td colspan="7" align="center">
    	<h3><strong>Órdenes por volar, pendientes de tracking hasta hoy</strong></h3>
    </td>
    <td width="244" align="right">
        <input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/print.ico" heigth="40" value="" title = "Imprimir Listado" width="30" onclick = ""/>
    </td>
  </tr>
  <tr bgcolor="#E8F1FD">
     <td width="92" align="center"><strong>Estado</strong></td>
  	<td width="138" align="center"><strong>Ponumber</strong></td>
    <td width="129" align="center"><strong>Custnumber</strong></td>
    <td width="167" align="center"><strong>Tracking</strong></td>
    <td width="91" align="center"><strong>Producto</strong></td>
    <td width="133" align="center"><strong>Fecha Entrega</strong></td>
    <td width="133" align="center"><strong>Fecha Vuelo</strong></td>
    <td width="244" align="center"><strong>Cobrar a</strong></td>
  </tr>
  <form id="form" name="form" method="post">
  <?php
			  $sql =   "SELECT id_orden_detalle,estado_orden,Ponumber, Custnumber, tracking, cpitem, delivery_traking,ShipDT_traking, soldto1
						FROM
						tblorden
						INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
						INNER JOIN (SELECT * FROM tbldetalle_orden INNER JOIN tblproductos ON tbldetalle_orden.cpitem = tblproductos.id_item WHERE origen = 'GYE-ECUADOR') as temp ON temp.id_detalleorden = tblorden.id_orden
						WHERE ShipDt_traking <= '".date('Y-m-d')."' AND tracking = '' AND estado_orden = 'Active' and (origen = 'BOG-COLOMBIA' OR origen = 'MED-COLOMBIA')";
			  $val = mysql_query($sql,$conection);
			  if(!$val){
				echo "<tr><td>".mysql_error()."</td></tr>";
			   }else{
				   $cant = 0;
				   while($row = mysql_fetch_array($val)){
					   $cant ++;
						echo "<tr>";
						echo "<td align='center'><strong>".$row['estado_orden']."</strong></td>";
						echo "<td align='center'><strong>".$row['Ponumber']."</strong></td>";
						echo "<td align='center'>".$row['Custnumber']."</td>";
						echo "<td align='center'>".$row['tracking']."</td>";
						echo "<td align='center'>".$row['cpitem']."</td>";
						echo "<td align='center'>".$row['delivery_traking']."</td>";
						echo "<td align='center'>".$row['ShipDT_traking']."</td>";
						echo "<td>".$row['soldto1']."</td>";
						echo "</tr>";
					 }
							echo "<tr><td></td></tr>
								  <tr>
								  <td align='right'><strong>".$cant. "</strong></td>
								  <td>Órden(es) encontradas</td>
								  </tr>";						
			   }
			   mysql_close($conection);
  ?>
  </form>
  </table>
  </td>
  </tr>
<tr>
    <td height="36" align="center" bgcolor="#3B5998" colspan="5"><strong><font color="#FFFFFF">Bit <img src="../images/r.png" width="15" height="15"/> 2015 versión 3 </font></strong></td>
  </tr>
  </table>
</body>
</html>

