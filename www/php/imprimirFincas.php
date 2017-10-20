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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Imprimir Fincas</title>
<script type="text/javascript" src="../js/script.js"></script>
<script language="javascript" src="../js/imprimir.js"></script>
 <link rel="icon" type="image/png" href="../images/favicon.ico" />
  <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
  <link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
  <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
    <script type="text/javascript">
function print1(valor){
	var v=valor;
	window.open("print1.php?codigo="+v,"Imprimir","width=300,height=200,top=200,left=350");
	return false;
}
</script>
</head>
<body background="../images/fondo.jpg">
<form id="form1" name="form1" method="post">
<table width="1024" border="0" align="center">
  <tr>
    <td height="133" align="center" width="100%"><img src="../images/logo.png" width="328" height="110" /></td>
  </tr>
       <tr>
  	<td colspan="2"><input type="image" src="../images/home.png" height="40" width="30" title="Inicio" formaction="mainroom.php">
    </td>
  </tr>
  </form>
  <tr>
    <td height="34" width="100%" align="center" bgcolor="#3B5998"><ul id="MenuBar1" class="MenuBarHorizontal">
          </ul></td>
  </tr>
  <tr>
    <td id="inicio" bgcolor="#CCCCCC" height="100"> 
    <table width="616" border="0" align="center"> 
  <tr>
    <td colspan="8" align="center">
    	<h3><strong>Listado de Fincas</strong></h3>
    </td>
    <td width="58" align="right">
        <input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/print.ico" heigth="40" value="" title = "Imprimir Listado" width="30" onclick = ""/>
    </td>
  </tr>
  <tr bgcolor="#E8F1FD">
     <td width="59" align="center"><strong>Código</strong></td>
     <td width="94" align="center"><strong>Nombre</strong></td>
	 <td width="34" align="center"><strong>RUC</strong></td>
     <td width="67" align="center"><strong>Dirección</strong></td>
     <td width="65" align="center"><strong>Provincia</strong></td>
     <td width="61" align="center"><strong>Teléfono</strong></td>
     <td width="61" align="center"><strong>Contacto</strong></td>
     <td width="79" align="center"><strong>Código Finca</strong></td>
     <td align="center"><strong>Imprimir</strong></td>
  </tr>
  <form id="form" name="form" method="post">
  <?php
  $conection =  conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error()); 
  //Leer todas las fincas existentes para modificarlas o crear nuevas
  $sql = "SELECT * FROM tblfinca";
  $val = mysql_query($sql,$conection);
   if(!$val){
      echo "<tr><td>".mysql_error()."</td></tr>";
   }else{
	   $cant = 0;
	   while($row = mysql_fetch_array($val)){
		   $cant ++;
			echo "<tr>";
			echo "<td><strong>".$row['codigo']."<strong></td>";		
			echo "<td>".$row['nombre']."</td>";
			echo "<td>".$row['ruc']."</td>";
			echo "<td>".$row['direccion']."</td>";
			echo "<td>".$row['prov_ciudad']."</td>";
			echo "<td>".$row['telefono']."</td>";
			echo "<td>".$row['contacto']."</td>";
			echo "<td>".$row['farm_code']."</td>";
			
			echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/print.ico" heigth="30" value="" title = "Imprimir etiquetas con este Item" width="20" onclick="print1(\''.$row['codigo'].'\')""/></td>';
			echo "</tr>";
	}	
		 			echo "<tr><td></td></tr>
				      <tr>
				      <td><strong>".$cant. "</strong></td>
					  <td>Fincas registradas</td>
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

