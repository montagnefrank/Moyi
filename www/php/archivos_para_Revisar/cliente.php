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
<title>Clientes</title>
 <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
 <link rel="icon" type="image/png" href="../images/favicon.ico" />
  <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
  <script type="text/javascript" src="../js/script.js"></script>
  <script>
  function home(id){
			window.location='contabilidad.php';
  }
  
function nuevo(){
	window.open("frm_nuevocliente.php?id=1","Cantidad","width=500,height=580,top=150,left=350");
	return false;
}
function modificar(valor){
	var v=valor;
	window.open("frm_actualizarcliente.php?codigo="+v,"Cantidad","width=500,height=520,top=150,left=350");
	return false;
}
function eliminar(valor){
	var v=valor;
	window.open("frm_eliminarcliente.php?codigo="+v,"Cantidad","width=300,height=150,top=300,left=400");
	return false;
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
    <td width="48">
    <input type="image" src="../images/home.png" height="40" width="30" title="Inicio" onclick="home(2)">
    </td>
  </tr>
  <tr>  
    <td height="43" align="center" bgcolor="#3B5998" colspan="7"></td>
  </tr>
<tr>  
      <td align="center" colspan="6"><strong>CLIENTES</strong></td>

      <td width="62" align="center"><img src="../images/new.png" alt="" width="24" height="24" border="0" style="cursor:pointer" title="Nuevo Cliente" onClick="nuevo()" /></td>
  </tr>
  <tr bgcolor="#E8F1FD">
      <td align="center"><strong>#</strong></td>
      <td width="171" align="center"><strong>Cédula / Ruc</strong></td>
      <td width="252" align="center"><strong>Cliente</strong></td>
      <td width="311" align="center"><strong>Direcci&oacute;n</strong></td>
      <td width="133" align="center"><strong>Tel&eacute;fono</strong></td>
    <td width="17" align="center"><strong>Editar</strong></td>
    <td align="center"><strong>Eliminar</strong></td>
    </tr>
    <?php 
	$contador_fila=1;
	echo "<tr>";
	$consulta_cliente="Select * from tblcliente_ecu where cpestado_cliente='ACTIVO';";
	$ejecutar_consulta=mysql_query($consulta_cliente,$conection) or die(mysql_error());
	while($fila=mysql_fetch_array($ejecutar_consulta)){
		$idcliente=$fila["id_cliente"];
		$cedula=$fila["cpcedula_cliente"];
		$nombre=$fila["cprazonsocial_cliente"];
		$direccion=$fila["cpdireccion_cliente"];
		$telefono=$fila["cptelefono_cliente"];
	
	echo "<td align='center'>".$contador_fila."</td>";
	echo "<td align='center'>".$cedula."</td>";
	echo "<td>".$nombre." ".$apellido."</td>";
	echo "<td>".$direccion."</td>";
	echo "<td align='center'>".$telefono."</td>";
	echo "<td align='center'> "?> <img src="../images/edit.png" alt="" width="24" height="24" border="0" style="cursor:pointer" title="Editar Cliente" onClick="modificar('<?php echo $idcliente?>')" />
    <?php "</td>";
	echo "<td align='center'> "?> <img src="../images/delete.png" alt="" width="24" height="24" border="0" style="cursor:pointer" title="Eliminar Cliente" onClick="eliminar('<?php echo $idcliente?>');" />
    <?php "</td>";
	echo "</tr>";
	
	$contador_fila++;
	}
	
	?>
  <tr>
    <td height="36" align="center" bgcolor="#3B5998" colspan="7"><strong><font color="#FFFFFF">Bit <img src="../images/r.png" width="15" height="15"/> 2015 version 0.2 Beta </font></strong></td>
  </tr>
</table>
<br />
</body>
</div>
</html>

