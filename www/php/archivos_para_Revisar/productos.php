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
<title>Productos</title>
 <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
 <link rel="icon" type="image/png" href="../images/favicon.ico" />
  <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
  <script type="text/javascript" src="../js/script.js"></script>
  <script>
  function home(id){
			window.location='contabilidad.php';
  }
  </script>
  <script language="javascript">          
function abrir(direccion, pantallacompleta, herramientas, direcciones, estado, barramenu, barrascroll, cambiatamano, ancho, alto, sustituir){
    var izquierda = (screen.availWidth - ancho) / 2;
    var arriba = (screen.availHeight - alto) / 2;
    var opciones = "fullscreen=" + pantallacompleta +
                 ",toolbar=" + herramientas +
                 ",location=" + direcciones +
                 ",status=" + estado +
                 ",menubar=" + barramenu +
                 ",scrollbars=" + barrascroll +
                 ",resizable=" + cambiatamano +
                 ",width=" + ancho +
                 ",height=" + alto +
                 ",left=" + izquierda +
                 ",top=" + arriba;

    var ventana = window.open(direccion,"ventana",opciones,sustituir);
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

  </tr>
  <tr>  
    <td height="34" align="center" bgcolor="#3B5998" colspan="5"></td>
  </tr>
  <tr>
  <td>
  
  <form id="form1" name="form1" method="post" action="">
  <table width="1016" border="0">
    <tr>
      <td height="32"  valign="top" colspan="2">&nbsp;</td>
      
      <td height="32" align="center" valign="top" colspan="4"><strong>LISTA DE PRODUCTOS</strong></td>
     
      
      <td height="32" align="center" ><img src="../images/new.png" alt="" width="21" height="24" border="0" style="cursor:pointer" title="Actualizar" onClick="abrir('frm_producto.php',0,0,0,0,0,0,0,740,700,0);" /> </td>
    </tr>
    <tr>
      <td width="47"><strong>#</strong></td>
      <td width="323"><strong>Nombre producto</strong></td>
      <td width="352"><strong>Descripcion</strong></td>
      <td width="68"><strong>Precio</strong></td>
      <td width="59"><strong>IVA</strong></td>
      <td width="70"><strong>Stock</strong></td>
      <td width="67">&nbsp;</td>
    </tr>
    <?php 
	$contador=1;
	$sql_consulta="SELECT id_producto, cpnombre_producto, cpdescripcion_producto,  cpprecioproducto_contado, cpstock_producto,  idiva_producto FROM tblproductos_ecu where cpestado_producto='ACTIVO';";
	$ejecutar_conculta=mysql_query($sql_consulta) or die(mysql_error());
	while($row=mysql_fetch_array($ejecutar_conculta)){
		$idproducto=$row["id_producto"];
	$nombre=$row["cpnombre_producto"];
	$descripcion=$row["cpdescripcion_producto"];
	$precio=$row["cpprecioproducto_contado"];
	$stock=$row["cpstock_producto"];
	$iva=$row["idiva_producto"];
	if($iva==0)
	$iva1="NO";
	
	else
	$iva1="SI";
	
	echo "<tr>";
	 echo "<td>$contador</td>";
	 echo "<td>$nombre</td>";
	 echo "<td>$descripcion</td>";
	 echo "<td>$precio</td>";
	 echo "<td>$iva1</td>";
	 echo "<td>$stock</td>";
	 echo "<td>"?><img src="../images/edit.png" alt="" width="24" height="24" border="0" style="cursor:pointer" title="Actualizar" onClick="abrir('frm_actualizarproducto.php?codigo=<?php echo $idproducto?>',0,0,0,0,0,0,0,750,700,0);" /> <img src="../images/delete.png" alt="" width="24" height="24" border="0" style="cursor:pointer" title="Actualizar" onClick="abrir('frm_eliminarproducto.php?codigo=<?php echo $idproducto?>',0,0,0,0,0,0,0,350,300,0);"  /> <?php "</td>";
	echo "</tr>";
	$contador++;
	}
	?>
    <tr>
      <td colspan="7">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
      
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
  
  
  </td>
  </tr>
  <tr>
  
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

