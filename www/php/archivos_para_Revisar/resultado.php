<?php
session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");

$user     =  $_SESSION["login"];
$passwd   =  $_SESSION["passwd"];
$bd       =  $_SESSION["bd"];
$rol      =  $_SESSION["rol"];

$conection = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Estado de Resultado</title>
 <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
 <link rel="icon" type="image/png" href="../images/favicon.ico" />
  <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
  <script type="text/javascript" src="../js/script.js"></script>
  <script>
  function home(id){
			window.location='contabilidad.php';
  }
  </script>
</head>
<div id="contenedor">
<body background="../images/fondo.jpg">
<table width="1024" border="0" align="center">
  <tr>
    <td height="133" align="center" colspan="4"><img src="../images/logo.png" width="328" height="110" /></td>
  </tr>
  <tr>
  	<td>
<input type="image" src="../images/home.png" height="40" width="30" title="Inicio" onclick="home(2)"></td>
</td>
  </tr>
  <tr>  
    <td height="34" align="center" bgcolor="#3B5998" colspan="5">
    </td>
  </tr>
  <tr>
  <td>
  <form id="form1" name="form1" method="post" action="">
      <?php //todas las ventas
	
	$sql_ventas="SELECT cptotal_ventas FROM tblfactura_venta;";
	$sql_query=mysql_query($sql_ventas) or die(mysql_error());
	$ventas_suma=0;
	while($fila1=mysql_fetch_array($sql_query)){
	$ventas=$fila1["cptotal_ventas"];
	
	$ventas_suma=$ventas_suma+$ventas;
	
	}
	
	
	 ?>
  <table width="631" border="0">
    <tr>
      <td colspan="2" align="center"><strong>ESTADO DE RESULTADO</strong></td>
    </tr>
    <tr>
      <td colspan="2" align="center">&nbsp;</td>
    </tr>
    <?php 
	$suma_ingresos=0;
	
	$consultar_ingresos="SELECT id_plancuentas, cpcodigo_plancuentas, cpdescripcion_plancuentas, cpnivel_plancuentas, cpgrupo_plancuentas, cpvalor_asiento FROM tblplancuentas, tblasientos where id_plancuentas=idcuenta_asiento  and cpgrupo_plancuentas='4';";
	$ejecutar = mysql_query($consultar_ingresos) or die (mysql_error());
	while($filas=mysql_fetch_array($ejecutar)){
		
		$descripcion_cuenta=$filas["cpdescripcion_plancuentas"];
		$valor_costo=$filas["cpvalor_asiento"];
		$suma_ingresos+=$valor_costo;
	  echo "<tr>";
      echo "<td width='239'> $descripcion_cuenta</td>";
      echo "<td width='494' >$valor_costo</td>";
      echo "</tr>";
		
	}
	
	
	?>
    <tr>
      <td ><strong>VENTAS</strong></td>
      <td >&nbsp;</td>
    </tr>
    <tr>
      <td width="233"> <strong> - Costo de Ventas:</strong></td>
      <td width="388" ><?php echo $ventas_suma; ?></td>
    </tr>
    <tr>
      <td><strong>Utilidad Bruta:</strong></td>
      <td >&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td><strong> - Gastos Administrativos:</strong></td>
      <td >&nbsp;</td>
    </tr>
    <?php 
	
	$suma_egresos=0;
	$consultar_ingresos="SELECT id_plancuentas, cpcodigo_plancuentas, cpdescripcion_plancuentas, cpnivel_plancuentas, cpgrupo_plancuentas,cpvalor_asiento FROM tblplancuentas, tblasientos where id_plancuentas=idcuenta_asiento  and cpgrupo_plancuentas='5';";
	$ejecutar = mysql_query($consultar_ingresos) or die (mysql_error());
	while($filas=mysql_fetch_array($ejecutar)){
		
		$descripcion_cuenta=$filas["cpdescripcion_plancuentas"];
		$valor_costo=$filas["cpvalor_asiento"];
		
		$suma_egresos+=$valor_costo;
	  echo "<tr>";
      echo "<td width='239'>$descripcion_cuenta </td>";
      echo "<td width='494' >$valor_costo</td>";
      echo "</tr>";
		
	}
	
	$resultado_neto= $suma_ingresos-$suma_egresos;
	if($resultado_neto<0){
		$color="bgcolor='#FF0000'";
	}
	else if($resultado_neto>0){
		$color="bgcolor='#009966'";
	}
	?>
    

    <tr>
      <td><strong>- Gastos de Venta:</strong></td>
      <td >&nbsp;</td>
    </tr>
    <tr>
      <td> <strong>Utilidad Bruta:</strong></td>
      <td >&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td ><strong> - Utilidad antes de Impuestos:</strong></td>
      <td bgcolor="<?php echo $color ?>">&nbsp;</td>
    </tr>
    <tr>
      <td >&nbsp;</td>
      <td bgcolor="<?php echo $color ?>">&nbsp;</td>
    </tr>
    <tr>
      <td ><strong>- Impuestos a la Renta:</strong></td>
      <td bgcolor="<?php echo $color ?>">&nbsp;</td>
    </tr>
    <tr>
      <td ><strong>Utilidad Neta:</strong></td>
      <td bgcolor="<?php echo $color ?>">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td ><strong>Resultado Neto:</strong></td>
      <td bgcolor="<?php echo $color ?>">&nbsp;</td>
    </tr>
  </table>
</form>
  
  </td>
  </tr>
  <tr>
    <td height="36" align="center" bgcolor="#3B5998" colspan="5"><strong><font color="#FFFFFF">Bit <img src="../images/r.png" width="15" height="15"/> 2015 versión 3 </font></strong></td>
  </tr>
</table>
<br />
<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"php/SpryAssets/SpryMenuBarDownHover.gif", imgRight:"php/SpryAssets/SpryMenuBarRightHover.gif"});
</script>
</body>
</div>
</html>

