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


function redondeo($valor) { 
   $float_redondeado=round($valor * 100) / 100; 
   return $float_redondeado; 
}
$valor=$_GET["cedula"];
$valor2=$_GET["nrofact"];

$sql_pendientes="Select id_venta, cpcedula_cliente, cprazonsocial_cliente,  cpdireccion_cliente, cptelefono_cliente, cpcelular_cliente, cpnum1_venta, cpnum2_venta, cpnumero_venta, cpfecha_venta from tblfactura_venta, tblcliente_ecu where idcliente_venta=id_cliente and  cpcedula_cliente='$valor' and id_venta='$valor2';";
$sql_select=mysql_query($sql_pendientes,$conection);
while($fila=mysql_fetch_array($sql_select))
{
		$id_faf=$fila["id_venta"];
		$cedula= $fila["cpcedula_cliente"]; 
		$nombre= $fila["cprazonsocial_cliente"];
		$direccion= $fila["cpdireccion_cliente"];
		$telefono = $fila["cptelefono_cliente"];
		$celular = $fila["cpcelular_cliente"];
		$num_1 = $fila["cpnum1_venta"];
		$num_2 = $fila["cpnum2_venta"];
		$numero_fac = $fila["cpnumero_venta"];
		$fecha_venta=$fila["cpfecha_venta"];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/estilo_personalizado.css" rel="stylesheet" type="text/css" />
<title>Detalles Factura</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="639" border="0" align="center">
    <tr>  
    <td height="43" align="center" bgcolor="#3B5998" colspan="7"></td>
  </tr>
    <tr>
      <td colspan="4" align="center"><strong>DETALLES DE LA FACTURA</strong></td>
    </tr>
    <tr>
      <td width="107"><strong>Cédula / RUC:</strong></td>
      <td width="260"><?php echo $cedula ?></td>
      <td width="129"><strong>Num Factura:</strong></td>
      <td width="129"><?php echo $num_1."-".$num_2."-".$numero_fac ?></td>
    </tr>
    <tr>
      <td ><strong>Nombres:</strong></td>
      <td colspan="3"><?php echo $nombre." ".$apellido ?></td>
    </tr>
    <tr>
      <td ><strong>Dirección:</strong></td>
      <td colspan="3"><?php echo $direccion ?></td>
    </tr>
    <tr>
      <td ><strong>Teléfono:</strong></td>
      <td><?php  echo $telefono?></td>
      <td><strong>Fecha:</strong></td>
      <td><?php echo $fecha_venta ?></td>
    </tr>
  
  </table>
  <br />
  <br />
  <table width="637" border="0" align="center">
    <tr>
      <td width="83"><strong>Cantidad</strong></td>
      <td width="357"><strong>Detalle</strong></td>
      <td width="92"><strong>Precio U.</strong></td>
      <td width="87"><strong>Precio T.</strong></td>
    </tr>
    <?php	
	$sql_c_fac="SELECT cpcantidad,  cpdetalle,  cpprecio_u,  cppreciototal,  idcliente_venta FROM tbldetalleventas, tblfactura_venta, tblcliente_ecu where  idventa_detalles = id_venta and idcliente_venta = id_cliente and cpcedula_cliente='$cedula'  and id_venta='$valor2' ;";
	$ejc_c_fac=mysql_query($sql_c_fac);
	echo "<tr>";
	while($file=mysql_fetch_array($ejc_c_fac))
	{
	$cantidad=$file["cpcantidad"];	
	$detalle=$file["cpdetalle"];
	$preciouni=$file["cpprecio_u"];
	$totalt=$file["cppreciototal"];
	echo "<td>$cantidad</td>";
	echo "<td>$detalle</td>";
	echo "<td>$preciouni</td>";
	echo "<td>$totalt</td>";
	echo "</tr>";	
	$subtotal=$subtotal+$totalt;	
	}	
	?>
  </table>
  <br />
  <table width="645" border="0" align="center">
    <tr>
      <td height="22"  align="center">&nbsp;</td>
      <td  align="center">&nbsp;</td>
      <td  align="center"><strong>Subtotal</strong></td>
      <td  align="center"><?php echo $subtotal; ?></td>
    </tr>
    <tr>
      <td height="22"  align="center">&nbsp;</td>
      <td  align="center">&nbsp;</td>
      <td  align="center"><strong>IVA 12%</strong></td>
      <td  align="center"><?php echo $iva=redondeo($subtotal*0.12); ?></td>
    </tr>
    <tr>
      <td width="157" height="22"  align="center">&nbsp;</td>
      <td width="281"  align="center">&nbsp;</td>
      <td width="96"  align="center"><strong>Total</strong></td>
      <td width="93"  align="center"><?php echo $total=($subtotal+$iva); ?></td>
    </tr>
    <tr>
      <td height="39" align="center" colspan="2">&nbsp;</td>
      <td height="39" align="center" colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td height="39" align="center" colspan="2"><input type="submit" name="btnpago" id="btnpago" value="Realizar el Pago"  /></td>
      
      <td height="39" align="center" colspan="2"><input type="submit" name="button" id="button" value="CERRAR" onclick="window.close()" /></td>
     
    </tr>
      <tr>
    <td height="36" align="center" bgcolor="#3B5998" colspan="7"><strong><font color="#FFFFFF">Bit <img src="../images/r.png" width="15" height="15"/> 2015 versión 3 </font></strong></td>
  </tr>
  </table>
  <p>&nbsp;</p>
  <?php
  if(isset($_POST["btnpago"])){
 $sqlac_f="UPDATE tblfactura_venta  SET  cpestado_factura = 'PAGADO' WHERE id_venta = '$id_faf';";
  $res=mysql_query($sqlac_f) or die(mysql_error());
  if($res)
	{  
  		echo "<script> alert('Cobro Realizado') </script>";
		 echo "<script> window.opener.document.location='pendcobro.php'</script>";
	 echo "<script> window.close()</script>";
  
  	}
  }
   ?>
</form>
</body>
</html>