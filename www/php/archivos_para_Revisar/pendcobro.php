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
<title>Pendientes de Cobro</title>
 <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
 <link rel="icon" type="image/png" href="../images/favicon.ico" />
  <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
  <script type="text/javascript" src="../js/script.js"></script>
  <script>
  function home(id){
			window.location='contabilidad.php';
  }
  
  function ver(cedula,nrofact){
	window.open("cambio.php?cedula="+cedula+"&nrofact="+nrofact,"Cantidad","width=650,height=450,top=150,left=350");
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
  	<td>
<input type="image" src="../images/home.png" height="40" width="30" title="Inicio" onclick="home(2)"></td>
</td>
  </tr>
  <tr>  
    <td height="34" align="center" bgcolor="#3B5998" colspan="5">
    </td>
  </tr>
  <tr>
<form id="form1" name="form1" method="post" action="" target="new">
  <table width="1007" border="0" align="center" >
    <tr>
      <td align="center" colspan="7"><strong>Pendientes de Cobro</strong></td>
    </tr>
    <tr align="center">
      <td width="187"><strong>Nro. Factura</strong></td>
      <td width="267"><strong>Cliente</strong></td>
      <td width="227"><strong>Direcci&oacute;n</strong></td>
      <td width="119"><strong>Teléfono</strong></td>
      <td width="72"><strong>Valor</strong></td>
      <td width="46"><strong>Ver</strong></td>
    </tr>
    <?php
    $sql_pendientes="Select id_venta, cpcedula_cliente, cprazonsocial_cliente, cpdireccion_cliente, cptelefono_cliente, cpcelular_cliente, cpnum1_venta, cpnum2_venta, cpnumero_venta, cptotal_ventas, cpretencion_factura  from tblfactura_venta, tblcliente_ecu where  idcliente_venta=id_cliente and  cpestado_factura='PENDIENTE';";
	$va=0;
	$suma=0;
	$ejecutar=mysql_query($sql_pendientes,$conection) or die(mysql_error());
	while($fila=mysql_fetch_array($ejecutar))
	{
		if($va%2==0)
		 $color="#CCCCCC";
		 
		 else
		$color="#FFFFFF";
		
		$cedula= $fila["cpcedula_cliente"]; 
		$nombre= $fila["cprazonsocial_cliente"];
		$direccion= $fila["cpdireccion_cliente"];
		$telefono = $fila["cptelefono_cliente"];
		$ventas = $fila["cptotal_ventas"];
		$num_1 = $fila["cpnum1_venta"];
		$num_2 = $fila["cpnum2_venta"];
		$idventa=$fila["id_venta"];
		$numero_fac = $fila["cpnumero_venta"];
		$retencion = $fila["cpretencion_factura"];
		if($retencion !="SIN RETENCION"){
		
		$consultar="SELECT d.id_retencion,  base_retencion, Impuesto_retencion, porcentaje_retencion, cpvalor_retencion, idretencion_detalle FROM  tbldetalle_retencion d, tblretencion r, tblfactura_venta v where r.id_retencion=d.idretencion_detalle and idfactura_retencion =id_venta and id_venta = '$idventa';";
		$exx=mysql_query($consultar, $conection) or die(mysql_error());
		while($filas_1=mysql_fetch_array($exx)){
			$iva=$filas_1["cpvalor_retencion"];
			$renta=$filas_1["cpvalor_retencion"];
		}
		$ventas=$ventas-($iva+$renta);
		}
		
		$fac_n = $fila["id_venta"];
		$suma=$suma+$ventas;
		
echo "<tr bgcolor=".$color.">";
echo "<td align='center'>".$num_1."-".$num_2."-".$numero_fac."</td>";
echo "<td>".$nombre."</td>";
echo "<td>".$direccion."</td>";
echo "<td align='center'>".$telefono."</td>";
echo "<td align='center'>"."$. ".$ventas."</td>";
echo "<td align='center'>";?>
<img src="../images/ver.png" onclick="ver('<?php echo $cedula ?>','<?php echo $fac_n?>');" width="18" height="24" border="0" title="Ver los detalles" style="cursor:pointer" />
<?php
echo"</td>";
echo "</tr>";
		$va++;
	}
	
	 ?>
     <tr>
     <td colspan="3"></td>
     <td bgcolor="#FF0000" align='center'><strong>Total</strong></td>
     <td bgcolor="#FF0000"  align='center'><?php echo "$. ".$suma?></td>
    </tr>
  <tr><td colspan="7">&nbsp;</td></tr>
  <tr>
    <td height="36" align="center" bgcolor="#3B5998" colspan="7"><strong><font color="#FFFFFF">Bit <img src="../images/r.png" width="15" height="15"/> 2015 version 0.2 Beta </font></strong></td>
  </tr>
</table>
</body>
</div>
</html>

