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

$_SESSION["mas"]=$_SESSION["act"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Diario</title>
 <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
 <link rel="icon" type="image/png" href="../images/favicon.ico" />
  <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
  <script type="text/javascript" src="../js/script.js"></script>
  <script>
  function home(id){
			window.location='diario.php';
  }
  </script>
  <script>
  function activar(){
	var activar = document.getElementById("txtcc").value;
	if(activar == "" || activar == "0"){
		document.form1.button2.disabled=true; 
		document.form1.button3.disabled=false; 
	
	}
	else if(activar == "1"){
		document.form1.button3.disabled=true; 
		document.form1.button2.disabled=false; 
		
	}
	  
  }
  </script>
</head>
<div id="contenedor">
<body background="../images/fondo.jpg" onload="activar()">
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
  <td align="center"><strong>ASIENTOS  </strong></td>
  </tr>
  <tr>
  <td> 
  
  <form id="form1" name="form1" method="post" action="" >
  <table width="700" border="0" align="center" class="punteado">
    <tr>
      <td height="34" colspan="4" align="center" bgcolor="#B7E2F7" class="text"><strong>Asientos Contables</strong></td>
    </tr>
    <tr>
      <td colspan="4"><strong>Nombre de transaccion:</strong></td>
    </tr>
    <tr>
      <td colspan="4"><input name="txtnombretransaccion" type="text"  class="text" id="txtnombretransaccion" value="<?php echo  $_SESSION["detalleasiento"] ?>" size="110" /></td>
    </tr>
    <tr>
      <td width="78"><strong>Cuenta</strong></td>
      <td width="390"><select name="cbx3" id="cbx3">
      <option value="0">Seleccione la cuenta.</option>
        <?php 
		$roles_user="select * from tblplancuentas order by cpcodigo_plancuentas asc;";
		$exe=mysql_query($roles_user) or die(mysql_error());
		while($col=mysql_fetch_array($exe)){
			$id_plan=$col["id_plancuentas"];
			$plan=$col["cpdescripcion_plancuentas"];
			echo "<option value='$id_plan'>$plan</option>";
		
		}
		
function asiento() {
	$sql_consultar="select * from tblasientos;";
	$contados_filas=mysql_query($sql_consultar) or die(mysql_error());
	$count=mysql_num_rows($contados_filas);
	if($count==0 || $count==""){
		$aseintoid=$count;
	 	return $aseintoid;
	}
	else{
	
		$sql="select cpnumero_asiento from tblasientos where cpnumero_asiento=(select max(cpnumero_asiento) from tblasientos); ";
		$result=mysql_query($sql);	
		while ($row = mysql_fetch_row($result)){
			$aseintoid=$row[0];
			}
			return $aseintoid;
	}
}

echo "Numero".asiento();
		?>
      </select></td>
      <td width="96"><strong>Debe/haber</strong></td>
      <td width="116"><select name="cbx4" id="cbx4">
        <option value="debe">Debe</option>
        <option value="haber">Haber</option>
      </select></td>
    </tr>
    <tr>
      <td ><strong>Valor</strong></td>
      <td ><input name="txtvalortransaccion" type="text" class="text1" id="txtvalortransaccion" size="15" /></td>
      <td ><label for="txtcc"></label>
        <input name="txtcc" type="hidden" id="txtcc" value="<?php echo $_SESSION["mas"] ?>" /></td>
      <td >&nbsp;</td>
    </tr>
    <tr>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
    </tr>
    <tr>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td ><strong>Fecha:</strong></td>
      <td ><?php echo date("d/m/Y"); ?></td>
    </tr>
  </table>
  <br />
  <table width="700" border="0" align="center" >
    <tr>
      <td width="571" align="center"><input type="submit" name="button2" id="button2" value="Ingresar " onclick="return validar_campos(this.form)"/></td>
      <td width="119" align="center"><input type="submit" name="button3" id="button3" value="Nuevo Asiento" /></td>
    </tr>
  </table>
  <?php 
  if(isset($_POST["button2"])){
	  
  $detalleasiento=$_POST["txtnombretransaccion"]; 
  $_SESSION["detalleasiento"]=$detalleasiento; 
  $cuenta=$_POST["cbx3"];
  $movimiento=$_POST["cbx4"];
  $valortransaccion=$_POST["txtvalortransaccion"];
  $fecha=date("d/m/Y");
  
  $sql_insertar="INSERT INTO tblaux_asiento (cpfecha_asiento, cpnumero_asiento, cpdetalle_asiento, cpmovimientoasiento, cpcuenta_asiento, cpvalor_asiento) VALUE ( '$fecha', '".$_SESSION["nuevoasiento"]."', '$detalleasiento', '$movimiento', '$cuenta', '$valortransaccion');";
  if(mysql_query($sql_insertar)){
 
  
  $seg = $seconds * 10000;
	$url="diario.php";
$comando = "<script>window.setTimeout('window.location =".chr(34).$url.chr(34).";',".$seg.");</script>";
	echo ($comando  );
	
	 echo "<script> alert('Dato registrado') </script>";


  }
  }
  
  if(isset($_POST["button3"])){
	  
 unset($_SESSION["detalleasiento"]);
  $sumarmas=asiento()+1;
  $_SESSION["nuevoasiento"]=$sumarmas;
   $seg = $seconds * 10000;
   $_SESSION["act"]= 1;
  

	$url="diario.php";
$comando = "<script>window.setTimeout('window.location =".chr(34).$url.chr(34).";',".$seg.");</script>";
	echo ($comando  );
  
  }
  ?>
<br />    
 <table width="973" border="0" align="center" cellpadding="0" cellspacing="0" class="punteado">
    <tr>
      <td colspan="7" align="center" bgcolor="#B7E2F7"><strong>Asientos Contables</strong></td>
    <tr>
      <td width="17"  align="center" bgcolor="#E4EDFA"><strong>#</strong></td>
      <td width="107"  align="center" bgcolor="#E4EDFA"><strong>Fecha</strong></td>
      <td width="434"  align="center" bgcolor="#E4EDFA"><strong>Detalle</strong></td>
      <td width="205"  align="center" bgcolor="#E4EDFA"><strong>Referencia</strong></td>
      <td width="117"  align="center" bgcolor="#E4EDFA"><strong>Debe</strong></td>
      <td width="93" align="center" bgcolor="#E4EDFA"><strong>Haber</strong></td>
    </tr>
    <?php
	$contador=1;
	$contador2=1;
	$suma_debe=0;
	$suma_haber=0;
	$sqlconusltaraux=" SELECT cpfecha_asiento, cpnumero_asiento, cpdetalle_asiento, cpmovimientoasiento, cpcuenta_asiento, cpvalor_asiento FROM tblaux_asiento group by cpnumero_asiento;";
	$exx=mysql_query($sqlconusltaraux) or die(mysql_error());
	echo "<tr>";
	while($files=mysql_fetch_array($exx)){
		$numero_consulta=$files["cpnumero_asiento"];
		$fecha_consulta=$files["cpfecha_asiento"];
		$detalle_consulta=$files["cpdetalle_asiento"];
		echo "<td align='center' >$numero_consulta</td>";
		echo "<td align='center' >$fecha_consulta</td>";
		echo "<td align='center' >$detalle_consulta</td>";
		
		$sqlconusltaraux1=" SELECT cpfecha_asiento, cpnumero_asiento, cpdetalle_asiento, cpmovimientoasiento, cpcodigo_plancuentas, cpdescripcion_plancuentas, cpvalor_asiento FROM tblaux_asiento, tblplancuentas where id_plancuentas = cpcuenta_asiento  and cpnumero_asiento = '$numero_consulta';";
		$exxxx=mysql_query($sqlconusltaraux1) or die(mysql_error());
		while($files1=mysql_fetch_array($exxxx)){
		$movimiento_consulta1=$files1["cpmovimientoasiento"];
		
		$cuentaconsulta_consulta1=$files1["cpcodigo_plancuentas"];
		$valor_consulta1=$files1["cpvalor_asiento"];
		$detalles_plancuenta=$files1["cpdescripcion_plancuentas"];
		
	
	if($movimiento_consulta1=="debe"){
		$suma_debe=$suma_debe+$valor_consulta1;
	
	if($contador==1){
	echo "<td align=''>$cuentaconsulta_consulta1 - $detalles_plancuenta</td>";
	echo "<td align='center'>$valor_consulta1</td>";
	echo "</tr>";
	$contador++;
	}
	else if($contador>1){
		echo "<tr>";
	echo "<td align='center'></td>";
	echo "<td align='center'></td>";
	echo "<td align='center'></td>";
	echo "<td align=''>$cuentaconsulta_consulta1 - $detalles_plancuenta </td>";
	echo "<td align='center'>$valor_consulta1</td>";
	echo "<td align='center'></td>";
	echo "<td align='center'></td>";
	echo "</tr>";
	$contador++;
	}
	}else if($movimiento_consulta1=="haber"){
		$suma_haber=$suma_haber+$valor_consulta1;
		
		if($contador==1) {
			echo "<td align=''>$cuentaconsulta_consulta1 - $detalles_plancuenta </td>";
			echo "<td align='center'></td>";
	echo "<td align='center'>$valor_consulta1</td>";
	echo "</tr>";
	$contador++;
	}
	else if($contador>1){
	echo "<tr>";
	echo "<td align='center'></td>";
	echo "<td align='center'></td>";
	echo "<td align='center'></td>";
	echo "<td align=''>$cuentaconsulta_consulta1 - $detalles_plancuenta</td>";
	echo "<td align='center'></td>";
	echo "<td align='center'>$valor_consulta1</td>";
	echo "</tr>";
	$contador++;
	}
	
			
	}
	

		}
		echo "<tr>";
	}
	
	
	 ?>
      
      <tr>
        <td colspan="7" align="center">&nbsp;</td>
      <tr>
      <td colspan="4" rowspan="20" align="center"><strong>SUMA TOTAL</strong></td>
      <td rowspan="20" align="center"><hr /> <?php echo $suma_debe ?></td>
      <td rowspan="20" align="center"><hr /><?php  echo $suma_haber ?></td>
     
      </table>
 <br />
  <table width="771" border="0" align="center" >
    <tr>
      <td width="765" align="center"><input type="submit" name="button" id="button" value="Registrar Transaccion" /></td>
    
    </tr>
  </table>
  <?php 
  if(isset($_POST["button"])){
	  
	$sql_consulta_sql="SELECT cpfecha_asiento, cpnumero_asiento, cpdetalle_asiento, cpmovimientoasiento, cpcuenta_asiento, cpvalor_asiento FROM tblaux_asiento;";
	$ejecutar=mysql_query($sql_consulta_sql) or die(mysql_error());
	while($f=mysql_fetch_array($ejecutar)){
	  
  $cpfecha_asiento=$f["cpfecha_asiento"];
  $cpnumero_asiento=$f["cpnumero_asiento"];
  $cpdetalle_asiento=$f["cpdetalle_asiento"];
  $cpmovimientoasiento=$f["cpmovimientoasiento"];
  $cpcuenta_asiento=$f["cpcuenta_asiento"];
  $cpvalor_asiento=$f["cpvalor_asiento"];
  
  $sqlinsertar ="INSERT INTO tblasientos(cpnumero_asiento, cpfecha_asiento, cpnombre_asiento, cpmovimiento_asiento, idcuenta_asiento, cpvalor_asiento) VALUE ('$cpnumero_asiento', '$cpfecha_asiento', '$cpdetalle_asiento', '$cpmovimientoasiento', '$cpcuenta_asiento', '$cpvalor_asiento');";
  $insertar_sql=mysql_query($sqlinsertar) or die(mysql_error());
  if($insertar_sql){
    echo "<script> alert('datos registrados correctamente.')</script>";
	unset($_SESSION["detalleasiento"]);
	
	unset($_SESSION["act"]);
  $sumarmas=asiento()+1;
  $_SESSION["nuevoasiento"]=$sumarmas;
   $seg = $seconds * 10000;
   echo $sumarmas;
   echo $_SESSION["detalleasiento"];
	$url="diario.php";
$comando = "<script>window.setTimeout('window.location =".chr(34).$url.chr(34).";',".$seg.");</script>";
	echo ($comando  );
	
  }
	}
  
  $sql_limpiar="TRUNCATE TABLE tblaux_asiento; ";
  mysql_query($sql_limpiar);
  }
 
  
  ?>
  
  <p>&nbsp;</p>
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

