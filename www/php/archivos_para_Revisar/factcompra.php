<?php
session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");

$user     =  $_SESSION["login"];
$passwd   =  $_SESSION["passwd"];
$bd       =  $_SESSION["bd"];
$rol      =  $_SESSION["rol"];
$_SESSION["r"]=" ";

$conection = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());
	$_SESSION["r"]=" ";
	if($_POST["btn_proveedor"]){
		$_SESSION["ruc"]=$_POST["btn_proveedor"];
	}
	$_SESSION["r"]=$_SESSION["ruc"];

function redondeo($valor) { 
   $float_redondeado=round($valor * 100) / 100; 
   return $float_redondeado; 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Factura Compra</title>
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
	{background-image: url(../images/delete1.png); width:30px; height: 30px; border-width: 0;  font-size:0px; }
	
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
			
function nuevo(){
	window.open("frm_nuevoproveedor.php?id=2","Cantidad","width=500,height=580,top=150,left=350");
	return false;
}
 </script>
</head>
<body background="../images/fondo.jpg">
<?php 
	$sqlconsulta="SELECT id_proveedor, cpcomercial_proveedor, cpruc_proveedor, cpdireccion_proveedor,  cpnombre_proveedor, cptelefono_proveedor FROM tblproveedor WHERE cpruc_proveedor='".$_SESSION["r"]."' ;";
	$ejec=mysql_query($sqlconsulta);
	while ($row = mysql_fetch_array($ejec))
		{
		   $idpro=$row["id_proveedor"];
		   $ruc = $row["cpruc_proveedor"];
		   $comercial = $row["cpcomercial_proveedor"];
		   $direccionp = $row["cpdireccion_proveedor"];
		   $nombrep = $row["cpnombre_proveedor"];
		   $telefono = $row["cptelefono_proveedor"];
		}
	
?>
<table width="1024" border="0" align="center"  >
   <tr>
    <td height="133" align="center" colspan="6"><img src="../images/logo.png" width="328" height="110" /></td>
  </tr>
    <tr>
        <td width="283">
            <input type="image" src="../images/home.png" height="40" width="30" title="Inicio" onclick="home()">
    </td>
  </tr>
  <tr>  
    <td height="34" align="center" bgcolor="#3B5998" colspan="6"></td>
  </tr>
  <form id="form1" name="form1" method="post" action="">
  <tr>
      <td height="29" colspan="6" align="center"><strong>DATOS PERSONALES</strong></td>
  </tr>
    <tr>
      <td align="right"><strong>C.I. / RUC.:</strong></td>
      <td width="168"><?php echo $ruc ?></td>
      <td width="41"><a href="buscar_proveedor.php"><img src="../images/buscar.png" width="29" height="30" title="Buscar Proveedor" style="cursor:pointer"/></a></td>
      <td width="38"><img src="../images/new.png" width="30" height="30" title="Nuevo proveedor" style="cursor:pointer" onclick="nuevo()" /></td>
      <td width="107"><strong>Num. Factura:</strong></td>
      <td width="361"><label>
        <input type="text" name="txtnumfactura" id="txtnumfactura" value="<?php echo $_SESSION["ftra"] ?>" />
      </label></td>
    </tr>
    <tr >
      <td align="right"><strong>Nombre Comercial:</strong></td>
      <td colspan="5"><?php echo $comercial ?></td>
    </tr>
    <tr>
      <td align="right"><strong>Registrante:</strong></td>
      <td colspan="5"><?php echo $nombrep ?></td>
    </tr>
    <tr>
      <td align="right"><strong>Dirección:</strong></td>
      <td colspan="5"><?php echo$direccionp ?></td> 
    </tr>
    <tr>
      <td align="right"><strong>Teléfono:</strong></td>
      <td colspan="3"><?php echo $telefono ?></td>
      <td><strong>Fecha emisión:</strong></td>
      <td><label>
        <input type="text" name="txtfechaemision" id="txtfechaemision" value="<?php echo date('Y-m-d') ?>" />
        <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtfechaemision");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtfechaemision",   // id of the input field
        ifFormat       :    "%Y-%m-%d",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

          </script>
      </label></td>
    </tr>
  </table>
  <br />
  <table width="500" border="0" align="center" id="tblver" >
    <tr ></tr>
    <tr ></tr>
    <tr >
      <td align="right"><strong>Cantidad:</strong></td>
      <td ><label>
        <input name="txtcantidad" type="number" min="1" id="txtcantidad" size="10" value="1"/>
      </label></td>
    </tr>
    
    <tr >
      <td align="right"><strong>Detalle:</strong></td>
      <td align=""><input name="txtdetalle2" type="text" id="txtdetalle2" size="40" /> 				 			<input name="txtdetalle" type="hidden" id="txtdetalle" size="40" /></td>
    </tr>
    <tr>
      <td align="right"><strong>P. Unitario:</strong></td>
      <td align=""><input name="txtunitario" type="text" id="txtunitario" size="10" /></td>
    </tr>
   <tr >
   	<td></td>
      <td align="center">
      <input name="btninsertar" type="image" src="../images/agregar.png" id="btninsertar" value="Insertar" width="30" height="30" title="Agregar Detalle"/></td>
    </tr>
  </table>
  <br />

  <table width="638" border="0" align="center" id="myTable" >
    <tr bgcolor="#00CCFF" class="table" >
      <td width="201" class="table"><strong>Cantidad</strong></td>
      <td width="195" align="center" class="table"><strong>Descripcion</strong></td>
      <td width="106" class="table" ><strong>P. Unit</strong></td>
      <td width="61" class="table" ><strong>P. Total</strong></td>
      <td width="53" background="../images/fondo.jpg">&nbsp;</td>
    </tr>
     <?php
	if(isset($_POST["btninsertar"]))
	{
		$numero_factura=$_POST["txtnumfactura"];
		$fecha_emision=$_POST["txtfechaemision"];
		$cantidad=$_POST["txtcantidad"];
		$detalle=$_POST["txtdetalle2"];
		$unitario=$_POST["txtunitario"];
		$d=$_POST["txtdetalle"];
		$_SESSION["ftra"]=$numero_factura;
		$_SESSION["fch"]=$fecha_emision;
		
		$total=redondeo($cantidad*$unitario);
	$sqlinsertfac="INSERT INTO tblfac_ax( cpcantidad, cpdetalle, cpunitario, cptotal, cpdetalle_cuenta) VALUE ('$cantidad', '$detalle', '$unitario', '$total','$d');";
	mysql_query($sqlinsertfac,$conection) or die ("Error insertando datos temporales");
	
	$url="factcompra.php";
	$seg=8;
$comando = "<script>window.setTimeout('window.location =".chr(34).$url.chr(34).";',".$seg.");</script>";
	echo ($comando  );
	
	}
			if(isset($_POST["id"])){
			$val=$_POST["id"];
			$delet="DELETE FROM tblfac_ax WHERE id_fac = $val;";
			mysql_query($delet);
			
			}
	?>
<?php include('const_compra.php');?>
    <tr >
      <td colspan="2">&nbsp;</td>
      <td  ><strong>Subtotal:</strong></td>
      <td><?php echo number_format($subtotal,2) ?>
        <label>
          <input type="hidden" name="txtsubtotal" value="<?php echo $subtotal ?>" id="txtsubtotal" />
        </label></td>
      <td background="../images/fondo.jpg">&nbsp;</td>
    </tr>
    <tr >
      <td colspan="2">&nbsp;</td>
      <td  ><strong>IVA 0%:</strong></td>
      <?php $iva=redondeo($subtotal*0.12);?>
      <td><input name="txtivacero" type="text" class="text" id="txtivacero" size="5" onkeyup="ivacero()" value="0" /></td>
      <td background="../images/fondo.jpg">&nbsp;</td>
    </tr>
    <tr >
      <td colspan="2">&nbsp;</td>
      <td  ><strong>IVA 12 %</strong></td>
      <td><label>
        
        <input name="txtivadoce" type="text" id="txtivadoce" style="border:none" size="10"readonly="readonly"  value="<?php echo number_format($iva,2)?>"  />
      </label></td>
      <td background="../images/fondo.jpg">&nbsp;</td>
    </tr>
    <tr >
      <td colspan="2">&nbsp;</td>
      <td width="106"  ><strong>Total:</strong></td>
      <?php $total=redondeo($subtotal+$iva); ?>
      <td width="61"><input name="txttotales" type="text" id="txttotales" size="10" readonly="readonly" style="border:none" value="<?php echo number_format($total,2)?>" /></td>
      <td width="53" background="../images/fondo.jpg"></td>
    </tr>
     <tr>
      <td width="201" align="center"><label>
        <input type="submit" name="button" id="button" value="Registrar factura" onclick="return validar()" />
      </label></td>
      <td width="195" align="center"><label>
        <input type="submit" name="button2" id="button2" value="Cancelar" />
      </label></td>
    </tr>   
  </table>
  <p>&nbsp;</p>
  <table width="1024" align="center">
        <tr>
    <td height="36" align="center" bgcolor="#3B5998" colspan="6"><strong><font color="#FFFFFF">Bit <img src="../images/r.png" width="15" height="15"/> 2015 versión 3 </font></strong></td>
  </tr>
  </table>
 <?php 
 if(isset($_POST["button"]))
 {
	 $subt=$_POST["txtsubtotal"];
	 $ivacero=$_POST["txtivacero"];
	 $ivadoce=$_POST["txtivadoce"];
	 $to=$_POST["txttotales"];
	 $numfac=$_POST["txtnumfactura"];
	 $fecha_emi=$_POST["txtfechaemision"];
		unset($_SESSION["fch"]);
		unset($_SESSION["ftra"]);
	 
	  $sql_fac_insert="INSERT INTO tblfactura_compra (cpnumfac_compra,  cpfecha_compra,  idproveedor_compra,  cpsubtotal_compra,  cpiva_compra,  cpdescuento_compra,  cptotal_compra) VALUE ('$numfac', '$fecha_emi',  '$idpro', '$subt', '$ivadoce', '$ivacero',  '$to');";
	   $fac_in=mysql_query($sql_fac_insert,$conection) or die(mysql_error());
	   $ultimo = mysql_insert_id();
	   echo $ultimo;
	   if($fac_in){

$tblfac="select * from tblfac_ax";
$res=mysql_query($tblfac);
	 while($rr=mysql_fetch_array($res))//permite ir de fila en fila de la tabla
			{
				  $cant=$rr['cpcantidad'];
				  $deta=$rr['cpdetalle'];
				  $prec=$rr['cpunitario'];
				  $plancuenta=$rr['cpdetalle_cuenta'];
				  $tota=$rr['cptotal'];
	 
	 $sql_insert="INSERT INTO tbldetallecompras (cpcantidad, cpdetalle, cpprecio_u, cppreciototal, idcompra_detalle, cpplancuentas_compra) VALUE ( '$cant', '$deta', '$prec', '$tota', '$ultimo', '$plancuenta');";
	 mysql_query($sql_insert) or die(mysql_error()) ;
			}
			
			$deletable="truncate table tblfac_ax ";
			$res1=mysql_query($deletable,$conection);
			if($res1){
				echo "<script> alert('Factura creada corectamente') </script>";
				$_SESSION["ruc"]=" ";
				$ss = $seconds * 10000;
					$url="factcompra.php";
	$comando = "<script>window.setTimeout('window.location=".chr(34).$url.chr(34).";',".$ss.");</script>";
					echo ($comando);			
			}
 }
 }
 
 if(isset($_POST["button2"]))
{
	$deletable="truncate table tblfac_ax ";
			$res1=mysql_query($deletable);
			$_SESSION["ruc"]=" ";
			$ss = $seconds * 10000;
				$url="factcompra.php";
$comando = "<script>window.setTimeout('window.location=".chr(34).$url.chr(34).";',".$ss.");</script>";
				echo ($comando);
			
}

 ?>
</form>
</body>
</html>