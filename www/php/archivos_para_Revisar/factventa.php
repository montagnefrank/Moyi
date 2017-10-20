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

if($_POST["btn_cliente"]){
		$_SESSION["cedula_cli"]=$_POST["btn_cliente"];
	}
	$_SESSION["rr"]=$_SESSION["cedula_cli"];

//
$sql1="SELECT id_venta, cpnum1_venta, cpnum2_venta, cpnumero_venta FROM tblfactura_venta ORDER BY id_venta DESC limit 1;";
$result1=mysql_query($sql1);	
while ($row1 = mysql_fetch_array($result1)) { 
	$id_num=$row1["id_venta"];
	$_SESSION["fac_num"]=$id_num;
	$num1=$row1["cpnum1_venta"];
	$num2=$row1["cpnum2_venta"];
	$numfac=$row1["cpnumero_venta"];
	
	}
	
	$num11=(int)$num1;
	$num22=(int)$num2;
	$num33=(int)$numfac+1;
	if($num1<1);
	$num11=1;
	if($num2<1)
	$num22=1;
	
	if($num11>=0 && $num11<10)
		$num111="00".$num11;
	if($num11>=10 && $num11<100)
		$num111="0".$num11;
	if($num11>=100 && $num11<1000)
		$num111=$num11;
		
	if($num22>=0 && $num22<10)
		$num222="00".$num22;
	if($num22>=10 && $num22<100)
		$num222="0".$num22;
	if($num22>=100 && $num22<1000)
		$num222=$num22;
		
	if($num33>0 && $num33<10)
		$num333="000000".$num33;
	if($num33>=10 && $num33<100)
		$num333="00000".$num33;
	if($num33>=100 && $num33<1000)
		$num333="0000".$num33;
	if($num33>=1000 && $num33<10000)
		$num333="000".$num33;
	if($num33>=10000 && $num33<100000)
		$num333="00".$num33;
	if($num33>=100000 && $num33<1000000)
		$num333="0".$num33;
	if($num33>=1000000 && $num33<10000000)
		$num333="".$num33;
		
		
	function id_fac(){
$sql_u="select id_venta from tblfactura_venta where id_venta=(select max(id_venta) from tblfactura_venta) ";
$result_v=mysql_query($sql_u);	
while ($ven = mysql_fetch_row($result_v))
{$ult=$ven[0];}
return $ult;
}

function id_asiento(){
$sql_asiento="select id_asiento from tblasientos where id_asiento=(select max(id_asiento) from tblasientos) ";
$result_asiento=mysql_query($sql_asiento);	
while ($asiento = mysql_fetch_row($result_asiento))
{$idasiento=$asiento[0];}
return $idasiento;
}

function asiento_numero($numero_asiento){
$sql_consultar_asiento="SELECT cpnumero_asiento FROM tblasientos where id_asiento='".$numero_asiento."';";
$ejecutar_asiento=mysql_query($sql_consultar_asiento) or die(mysql_error());
while($row=mysql_fetch_array($ejecutar_asiento)){
$numero=$row["cpnumero_asiento"];
}
 return $numero;
}
	
	if($_SESSION["fecha_venta"]=="" || $_SESSION["fecha_venta"]==" "){
		$_SESSION["fecha_venta"]=date("d/m/Y");
	}
	
	if($_SESSION["factura_numero"]=="" || $_SESSION["factura_numero"]==" "){
	$_SESSION["factura_numero"]=$num333;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Facturas Ventas</title>
 <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
 <link rel="icon" type="image/png" href="../images/favicon.ico" />
  <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
  <script type="text/javascript" src="../js/script.js"></script>
  
  <script src="../js/jquery-1.3.2.js" type="text/javascript"></script>
<script src="../js/jquery-1.3.1.min.js" type="text/javascript"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
<script language="javascript" src="../js/calendar.js"></script>
<script language="javascript" src="../js/calendar-en.js"></script>
<script language="javascript" src="../js/calendar-setup.js"></script>
<link href="../css/calendar-win2k-cold-1.css" rel="stylesheet" type="text/css" />
<link href="../css/jqueryui.css" type="text/css" rel="stylesheet"/>
<style type="text/css">   		
	.botoncontacto 
	{background-image: url(../images/agregar.png); width:40px; height: 42px; border-width: 0;  font-size:0px; }
	
	.quitar 
	{background-image: url(../images/delete.png); width:25px; height: 24px; border-width: 0;  font-size:0px; }
	
	.table {
	  border: black 1px solid;
	}
</style>
  <script type="text/javascript">
		
		function home(){
		window.location='contabilidad.php';
	  }
	  
		function buscar(){
			window.open("buscar_proveedor.php","Cantidad","width=600,height=300,top=150,left=350");
			return false;
		}
		
			$('document').ready(function(){
				$('#btninsertar').click(function(){
					if($('#txtcantidad').val()==""){
					alert("Introduce la cantidad del producto");
					return false;
						 }
					else{
					var cantidad = $('#txtcantidad').val();
						}
						
					if($('#txtdetalle').val()==""){
					alert("Producto no encontrado, seleccione de lo desglozado");
					return false;
						}
					else{
					var detalle= $('#txtdetalle').val();
						}
						
					if($('#txtunitario').val()==""){
					alert("Introduce el valor unitario del producto");
								return false;
							}
							else{
						var unitario = $('#txtunitario').val();
							}
						});
			});

function validar(){
	if(document.form1.txtnumfactura.value==''){
		alert('El Numero de Factura esta vacia');
		document.form1.txtnumfactura.focus();
		return false;
	}
	
	if(document.form1.txtfechaemision.value==''){
		alert('La fecha de emision esta vacia');
		document.form1.txtfechaemision.focus();
		return false;
	}
}

function redondeo2decimales(numero)
{
	var original=parseFloat(numero);
	var result=Math.round(original*100)/100 ;
	return result;
}

function ivacero(){
var subtotal=document.getElementById("txtsubtotal").value;
var ext=0;
var iv=0.12;
var iva;
var totales;
var cero=document.getElementById("txtivacero").value;
if(cero!=null)
{
	ext=subtotal-cero;
	iva=redondeo2decimales(ext*iv);
	document.getElementById("txtivadoce").value=iva;
	totales=redondeo2decimales(parseFloat(subtotal)+parseFloat(iva));
	document.getElementById("txttotales").value=totales;
	
	
}
}
	$(document).ready(function(){ 	
				$( "#txtdetalle2" ).autocomplete({
      				source: "buscarproducto.php",
      				minLength: 1
    			});
    			
    			$("#tblver").mouseover(function(){
    				$.ajax({
    					url:'buscarproducto1.php',
    					type:'POST',
    					dataType:'json',
    					data:{ matricula1:$('#txtdetalle2').val()}
    				}).done(function(respuesta){
    					$("#txtdetalle").val(respuesta.codigo);    					
    				});
    			}); 
				
				   			    		
			});
			
        </script>
</head>
<div id="contenedor">
<body background="../images/fondo.jpg">

<?php 
	$sqlconsulta="SELECT id_cliente, cpcedula_cliente, cprazonsocial_cliente, cpdireccion_cliente , cptelefono_cliente FROM tblcliente_ecu WHERE cpcedula_cliente='".$_SESSION["rr"]."' ;";
	$ejec=mysql_query($sqlconsulta);
	while ($row = mysql_fetch_array($ejec)) {
			$id_cliente=$row["id_cliente"];
		   $cedula = $row["cpcedula_cliente"];
		   $nombre = $row["cprazonsocial_cliente"];
		   $direccion = $row["cpdireccion_cliente"];
		   $telefono = $row["cptelefono_cliente"];
		   $_SESSION["cedula_imprimir"]=$cedula ;
		}
		
?>
<?php 
function redondeo($valor) { 
   $float_redondeado=round($valor * 100) / 100; 
   return $float_redondeado; 
}
$fec_pago=date("d/m/Y", mktime(0, 0, 0, date("m"), date("d")+5, date("Y") ));
?>
<table width="1024" border="0" align="center">
  <tr> 
    <td height="133" align="center" colspan="4"><img src="../images/logo.png" width="328" height="110" /></td>
  </tr>
  <tr>
      <tr>
        <td width="283">
            <input type="image" src="../images/home.png" height="40" width="30" title="Inicio" onclick="home()">
    </td>
  </tr>	
</td>
  </tr>
  <tr>  
    <td height="34" align="center" bgcolor="#3B5998" colspan="5">
    </td>
  </tr>
  <tr>
  <td>
  <table width="702" border="0" align="center"  >
   <form id="form1" name="form1" method="post" action="">
    <tr>
      <td height="29" colspan="6" align="center"  ><strong>DATOS PERSONALES DE CLIENTE</strong></td>
    </tr>
    <tr>
      <td width="138"><strong>C.I. / RUC.:</strong></td>
      <td width="133"><?php echo $cedula ?></td>
      <td width="30"><a href="buscar_cliente.php"><img src="../images/buscar.png" width="23" height="22" title="Buscar proveedor" style="cursor:pointer" /></a></td>
      <td width="30"><a href="#"  onClick="abrir('frmcliente_2.php', '', '', '', '', '', 'auto', '', '650', '480', '')"><img src="../images/new.png" width="18" height="22" title="Nuevo proveedor" style="cursor:pointer" /></a></td>
      <td width="109"><strong>Num. Factura:</strong></td>
      <td width="236"><?php echo ($num111."-".$num222."-")?><input name="txtnumero" type="text" value="<?php echo $_SESSION["factura_numero"]?>" size="10" /></td>
    </tr>
    <tr>
      <td><strong>Sr/(a).</strong></td>
      <td colspan="5"><?php echo $nombre." ".$apellido ?></td>
    </tr>
    <tr>
      <td height="22"><strong>Dirección:</strong></td>
      <td colspan="5"><?php echo $direccion ?></td>
    </tr>
    <tr>
      <td><strong>Teléfono:</strong></td>
      <td colspan="3"><?php echo $telefono ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      
    </tr>
    <tr>
      <td><strong>Forma pago:</strong></td>
      <td colspan="3"><select name="cbxpago" class="text">
      <option value="EFECTIVO">Efectivo</option>
        <option value="CHEQUE">Cheque</option>
        <option value="TRANSACCION">Transaccion</option>
      </select></td>
      <td><strong>Vendedor:</strong></td>
      <td><?php echo "Janpaul Sanchez";?></td>
    </tr>
    <tr>
      <td><strong>Fecha emisión:</strong></td>
      <td colspan="3"><input name="txtdate" type="text" id="txtdate" value="<?php echo $_SESSION["fecha_venta"];?>" readonly="readonly" />
       <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtdate");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%d/%m/%Y");
    }
    Calendar.setup({
        inputField     :    "txtdate",   // id of the input field
        ifFormat       :    "%d/%m/%Y ",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

</script></td>
      <td><strong>Fecha Pago:</strong></td>
      <td><input name="txtfechapago" type="text" class="text" id="txtfechapago" readonly="readonly" value="<?php echo $fec_pago ?>" /></td>
    </tr>
  </table>
  <br />
  <table width="706"  border="0" align="center" id="tblver" onmousemove="detalle()">
  
    <tr >
      <td width="89" ><strong>Cantidad</strong></td>
      <td colspan="3" ><label>
        <input name="txtcantidad" type="text" id="txtcantidad" size="10" class="table"/>
      </label></td>
     
    </tr>
    <tr >
      <td height="26" ><strong>Detalle:</strong></td>
      <td width="407" ><label for="textfield">
        <input name="txtdetalle2" type="text" id="txtdetalle2" size="40" class="table" />
    </label></td>
      <td width="35" >&nbsp;</td>
      <td align=""><input name="txtdetalle" type="hidden" class="table" id="txtdetalle" value=""  /></td>
    </tr>
    
    <tr >
      <td height="26"  valign="top"><strong>P. Unitario</strong></td>
      <td colspan="2" align="" valign="top"  ><input name="txtunitario" type="text" id="txtunitario" size="10" class="table" /></td>
      <td width="92" align=""  > <span id="res"></span></td>
      <td width="61"  align="" rowspan="4" ><input name="btninsertar" type="submit" id="btninsertar" value="Insertar" style="cursor:pointer" title="Insertar los datos"  /></td>
    </tr>
  </table>
  <br />
   
  <table width="707" border="0" align="center" id="myTable"  >
    <tr bgcolor="#00CCFF"  >
      <td width="89" class="table"><strong>Cantidad</strong></td>
      <td width="393" align="center" class="table" ><strong>Descripcion</strong></td>
      <td width="82"  class="table"><strong>P. Unit</strong></td>
      <td width="91" class="table" colspan="2" ><strong>P. Total</strong></td>
      
    </tr>
    <?php
	if(isset($_POST["btninsertar"])) {
		$cantidad=$_POST["txtcantidad"];
		$detalle=$_POST["txtdetalle2"];
		$unitario=$_POST["txtunitario"];
		$cuenta_de=$_POST["txtdetalle"];	
		$sql_consultar_descripcion="select cpdescripcion_producto from tblproductos_ecu where cpcodigo_producto='$cuenta_de';";
		$ejecutar_consulta=mysql_query($sql_consultar_descripcion) or die(mysql_error());
		while($fila=mysql_fetch_array($ejecutar_consulta)){
		$descripcion_producto=$fila["cpdescripcion_producto"];
		}
		
		$total=redondeo($cantidad*$unitario);
$sqlinsertfac="INSERT INTO  tbaux_ven(  cpcantidad_ax, cpdet_aux,  cppu_aux, cppt_aux, cpdescripcion_cuenta)VALUE ('$cantidad',  '$descripcion_producto',  '$unitario', '$total' , '$cuenta_de' );";
	$sql_1=mysql_query($sqlinsertfac);
	
	$fecha_venta=$_POST["txtdate"];
	$numero_facturero=$_POST["txtnumero"];
	
	$_SESSION["fecha_venta"]= $fecha_venta;
	$_SESSION["factura_numero"]=$numero_facturero;
	$ss = $seconds * 10000;
				$url="factventa.php";
$comando = "<script>window.setTimeout('window.location=".chr(34).$url.chr(34).";',".$ss.");</script>";
				echo ($comando);
	
			}
			if(isset($_POST["id"])){
			$val=$_POST["id"];
			$delet="DELETE FROM tbaux_ven WHERE id_auxc = $val;";
			mysql_query($delet);
			
	$fecha_venta=$_POST["txtdate"];
	$numero_facturero=$_POST["txtnumero"];
	
	$_SESSION["fecha_venta"]= $fecha_venta;
	$_SESSION["factura_numero"]=$numero_facturero;
	$ss = $seconds * 10000;
				$url="factventa.php";
$comando = "<script>window.setTimeout('window.location=".chr(34).$url.chr(34).";',".$ss.");</script>";
				echo ($comando);
			
			}
	?>
    <?php include('consul_fac_ventas.php');?>
			
    <tr >
      <td colspan="2">&nbsp;</td>
      <td  bgcolor="#CCCCCC" >Subtotal:</td>
      <td bgcolor="#CCCCCC"><?php echo $subtotal ?>
        <input type="hidden" name="txtsubtotal" value="<?php echo $subtotal ?>" id="txtsubtotal" /></td>
      <td bgcolor="#FFFFFF"><input name="txtsd" type="text" id="txtsd" value="0" size="5" readonly="readonly" style="border:none" /></td>
    </tr>
    <tr >
      <td colspan="2">&nbsp;</td>
      <td  >IVA 12%:</td>
      <?php $iva=redondeo($subtotal*0.12);?>
      <td><?php echo $iva?></td>
      <td bgcolor="#FFFFFF"><input name="txtid" type="text" id="txtid" value="0" size="5" readonly="readonly" style="border:none" /></td>
    </tr>
    <tr >
      <td colspan="2">&nbsp;</td>
      <td bgcolor="#CCCCCC" >Descuento:</td>
      <td bgcolor="#CCCCCC" ><input name="txtdescuento" type="text" id="txtdescuento"   onkeyup="descuento()" value="0" size="3" class="table"/></td>
      <td bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <tr >
      <td colspan="2">&nbsp;</td>
      <td width="82"  ><strong>Total:</strong></td>
      <?php $total=$subtotal+$iva; ?>
      <td width="91" ><hr />
        <?php echo $total?></td>
      <td width="30" bgcolor="#FFFFFF"><input name="txttd" type="text" id="txttd" value="0" size="5" readonly="readonly" style="border:none" /></td>
    </tr>
  </table>
  <table width="704" border="0" align="center">
    <tr>
      <td width="389" align="center"><label>
        <input type="submit" name="button" id="button" value="Registrar e Imprimir factura" />
      </label></td>
      <td width="305" align="center"><label>
        <input type="submit" name="button2" id="button2" value="Cancelar" />
      </label></td>
      
    </tr>
  </table>
  <p><br />
</td>
  </tr>
  <tr>
    <td height="36" align="center" bgcolor="#3B5998" colspan="5"><strong><font color="#FFFFFF">Bit <img src="../images/r.png" width="15" height="15"/> 2015 version 0.2 Beta </font></strong></td>
  </tr>
</table>
<?php 
 echo $numero_asiento=asiento_numero(id_asiento());
 if(isset($_POST["button"])) {
#llamada a los campos para hacer el respectivo ingreso de factura
	 $factura=$id_num+1;
	 $iva=redondeo($iva);
	 $total=redondeo($total);
	 $_SESSION["fac_num"]=$factura;
	 $facven_id=(int)id_fac()+1;
	 $fecha_actual=$_POST["txtdate"];
	 $vendedor="Janpaul Sanchez";
	 $descuento="0";
	 $debe="debe";
	 $haber="haber";
	 $estado="PENDIENTE";
	 $pago=$_POST["cbxpago"];
	 $numfacturero=$_POST["txtnumero"]; 
	 
	 
	 #Ingresar en la tabla factura
	$sql_fac="INSERT INTO tblfactura_venta(id_venta, cpnum1_venta, cpnum2_venta, cpnumero_venta, cpfecha_venta, cpfechapag_venta, cpvendedor_venta, cpformpag_venta, cpsubtotal_ventas, cpiva_ventas, cpdescuento_ventas, cptotal_ventas, idcliente_venta, cpestado_factura) VALUE ('$facven_id', '$num111', '$num222', '$numfacturero', '$fecha_actual', '$fec_pago', '$vendedor', '$pago', '$subtotal', '$iva', '$descuento', '$total', '$id_cliente', '$estado');";
	$eje_correc=mysql_query($sql_fac) or die(mysql_error());
if($eje_correc){ 
	 $tblfac="select * from tbaux_ven";
	$res=mysql_query($tblfac);
while($rr=mysql_fetch_array($res)) {
				  $cant=$rr['cpcantidad_ax'];
				  $deta=$rr['cpdet_aux'];
				  $prec=$rr['cppu_aux'];
				  $descipcuenta=$rr['cpdescripcion_cuenta'];
				  $tota=$rr['cppt_aux'];
$sql_insert="INSERT INTO tbldetalleventas( cpcantidad, cpdetalle, cpprecio_u,cppreciototal,idventa_detalles, cpplancuentas) VALUE ( '$cant', '$deta', '$prec', '$tota', ' $facven_id', '$descipcuenta');";
	 mysql_query($sql_insert) or die(mysql_error());
#consultar producto
$sql_consultar_producto="SELECT cpstock_producto FROM tblproductos_ecu where cpcodigo_producto='$descipcuenta';";
$ejecutar_producto=mysql_query($sql_consultar_producto) or die(mysql_error());
while($columna=mysql_fetch_array($ejecutar_producto)){
$disponible=$columna["cpstock_producto"];
}

$residuo=$disponible-$cant;
#actualizar datos de productos
$sql_actualizar="UPDATE tblproductos_ecu SET cpstock_producto = '$residuo' WHERE cpcodigo_producto = '$descipcuenta';";
mysql_query($sql_actualizar) or die(mysql_error());
	 
			}
			
			
$deletable="truncate table tbaux_ven; ";
$res1=mysql_query($deletable) or die(mysql_error());
			if($res1){
			echo "<script> alert('Datos registrados') </script>";
			$_SESSION["ced"]=" ";
			$_SESSION["fecha_venta"]= " ";
			$_SESSION["factura_numero"]=" ";
			unset($_SESSION["factura_numero"]);
			$comando11 = "<script language='JavaScript'>window.open('imprimir.php','','width=800, height=800, scrollbars=yes','', '');</script>";
				echo ($comando11);
				$url="factventa.php";
$comando = "<script>window.setTimeout('window.location=".chr(34).$url.chr(34).";',".($seconds * 10000).");</script>";
			    echo ($comando);
			}
 }
 }
 
 if(isset($_POST["button2"]))
{
	$deletable=" truncate table tbaux_ven; ";
			$res1=mysql_query($deletable) or die(mysql_error());
			$_SESSION["ced"]=" ";
			$ss = $seconds * 10000;
				$url="frmfactura_venta.php";
$comando = "<script>window.setTimeout('window.location=".chr(34).$url.chr(34).";',".$ss.");</script>";
				echo ($comando);
			
}
 ?>
</form>
<br />
<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"php/SpryAssets/SpryMenuBarDownHover.gif", imgRight:"php/SpryAssets/SpryMenuBarRightHover.gif"});
</script>
</body>
</div>
</html>

