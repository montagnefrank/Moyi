<?php 

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////CONEXION A DB
$user = $_SESSION["login"];
$rol = $_SESSION["rol"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

$user     =  $_SESSION["login"];
$passwd   =  $_SESSION["passwd"];
$bd       =  $_SESSION["bd"];
$rol      =  $_SESSION["rol"];
$caja     =  $_SESSION['cajas'];	


$sql_init="select * FROM tblcoldroom WHERE codigo='".$caja[0]."'";
$modif= mysqli_query($link, $sql_init) or die("Error");
$row = mysqli_fetch_array($modif);

$guia_madre = $row['guia_madre'];

list($guide1, $guide2) = explode('-',$guia_madre); 

$fecha_entrega = $row['fecha_entrega'];
$servicio = $row['servicio'];
$fecha_vuelo = $row['fecha_vuelo'];
$airline = $row['airline'];

/*echo $guia_madre;
echo $fecha_entrega;
echo $servicio;
echo $fecha_vuelo;
echo $airline;*/

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Modificar Guía Madre</title>
	<link rel="stylesheet" type="text/css" media="all" href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
	<style type="text/css">
	    .my-error-class {
	        color:red;
	    }
	    /*.my-valid-class {
	        color:green;
	    }*/
  	</style>
	<script type="text/javascript" src="../js/calendar.js"></script>
	<script type="text/javascript" src="../js/calendar-en.js"></script>
	<script type="text/javascript" src="../js/calendar-setup.js"></script>
	<script src="//code.jquery.com/jquery-1.9.1.js"></script>
	<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
	<script src="../js/formato.js"></script>
	<script>

	jQuery.validator.addMethod("noSpace", function (value, element) { 
		return value.indexOf("_") < 0 && value != ""; 
	}, "No space please and don't leave it empty");

	// When the browser is ready...
	$(function() {


	/*
	 $("#codigo").keydown(function (e) {
	    // Allow: delete(46), "-" (109), backspace(8), tab(9), escape(27), enter(13) and 
	    if ($.inArray(e.keyCode, [46, 109, 8, 9, 27, 13]) !== -1 ||
	         // Allow: home, end, left, right, down, up
	        (e.keyCode >= 35 && e.keyCode <= 35) || e.keyCode >= 96 && e.keyCode <= 105)  {
	             // let it happen, don't do anything
	             return;
	    }
	    // Ensure that it is a number and stop the keypress
	    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57))) {
	        e.preventDefault();
	    }
	});
	*/

	  // Setup form validation on the #register-form element
	  $("#form1").validate({
	  errorClass: "my-error-class",
	  validClass: "my-valid-class",

	  // Specify the validation rules
	  rules: {
	        guia: {
	          required: true,
	          minlength: 3,
	          maxlength: 3,
	          noSpace: true
	        },
	        guia1: {
	          required: true,
	          minlength: 8,
	          maxlength: 8,
	          noSpace: true
	        }
	  },
	  
	  // Specify the validation error messages
	  messages: {
	        guia: {
	          required: "Por favor inserte un código válido",
	          minlength: "10 caracteres como mínimo",
	          maxlength: "10 caracteres como máximo",
	          noSpace: "No puede dejar espacios en blanco"
	        },
	        guia1: {
	          required: "Por favor inserte una código válido",
	          minlength: "8 caracteres como mínimo",
	          maxlength: "8 caracteres como máximo",
	          noSpace: "No puede dejar espacios en blanco"
	        }
	  },

	  submitHandler: function(form) {
	      form.submit();
	  }
	});
	 
	$("#guia").mask("***");
	$("#guia1").mask("********"); 

  	});

	</script>
</head>
<body>
<form id="form1" name="form1" method="post">
<table width="350" border="0" align="center">
     <tr>
    <td width="350" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Modificar guía madre</font></strong></td>
  </tr>
</table>
<table width="350" border="0" align="center">
 <tr height="20"><td></td></tr>
     <tr>
     	<td>
        <table border="0" align="center" width="350">
            <tr>
            	<td><strong>Deliver Date: </strong></td>
                <td><input name="fecha" type="text" id="fecha" readonly="readonly" value="<?php echo $fecha_entrega?> "/>*<script type="text/javascript">
					function catcalc(cal) {
						var date = cal.date;
						var time = date.getTime()
						// use the _other_ field
						var field = document.getElementById("f_calcdate");
						if (field == cal.params.inputField) {
							field = document.getElementById("fecha");
							time -= Date.WEEK; // substract one week
						} else {
							time += Date.WEEK; // add one week
						}
						var date2 = new Date(time);
						field.value = date2.print("%Y-%m-%d");
					}
					Calendar.setup({
						inputField     :    "fecha",   // id of the input field
						ifFormat       :    "%Y-%m-%d ",       // format of the input field
						showsTime      :    false,
						timeFormat     :    "24",
						onUpdate       :    catcalc
					});

               </script></td>
            </tr>
            <tr>
            	<td><strong>AWB-GUIA:</strong></td>
                <td><input type="text" id="guia" name="guia" value="<?php echo $guide1;?>" size="3"/>-
                  <input type="text" id="guia1" name="guia1" value="<?php echo $guide2;?>" size="11"/>*</td>
            </tr>
            <tr>
            	<td><strong>Service:</strong></td>
                <td>
                <select type="text" id="servicio" name="servicio">
	             <!--   <option selected="selected" value="<?php echo $servicio; ?>"></option>   -->
	                <option value="48">48 Hrs</option>
	             <!--   <option value="ER">Entrega Regular</option>   -->
	                <option value="ER" selected="selected">Entrega Regular</option>
                </select>*</td>
            </tr>
             <tr>
            	<td><strong>Fly Date:</strong></td>
                <td><input name="vuelo" type="text" id="vuelo" readonly="readonly" value="<?php echo $fecha_vuelo ?>" />*<script type="text/javascript">
					function catcalc(cal) {
						var date = cal.date;
						var time = date.getTime()
						// use the _other_ field
						var field = document.getElementById("f_calcdate");
						if (field == cal.params.inputField) {
							field = document.getElementById("vuelo");
							time -= Date.WEEK; // substract one week
						} else {
							time += Date.WEEK; // add one week
						}
						var date2 = new Date(time);
						field.value = date2.print("%Y-%m-%d");
					}
					Calendar.setup({
						inputField     :    "vuelo",   // id of the input field
						ifFormat       :    "%Y-%m-%d ",       // format of the input field
						showsTime      :    false,
						timeFormat     :    "24",
						onUpdate       :    catcalc
					});

               </script></td>
            </tr>
            <tr>
            <td align="right"><input name="Registrar" type="submit" value="Modificar" /></td>
            <td><input name="Cancelar" type="submit" value="Cancelar" onclick="self.close();" /></td>
            </tr>
        </table>
        </td>
     </tr>
     <tr height="20"><td></td></tr>
    <tr>
    <td height="36" align="center" bgcolor="#3B5998" colspan="5"><strong><font color="#FFFFFF">Bit <img src="../images/r.png" width="15" height="15"/> 2015 versión 3 </font></strong></td>
  </tr>
</table>
</form>
<?php
   if(isset($_POST["Registrar"])){  
	 $guia    = $_POST['guia'];
	 $guia1   = $_POST['guia1'];
	 $guia    = $guia."-".$guia1;
	 $fecha   = $_POST['fecha'];
	 $check   = $_SESSION['cajas'];
	 $service = $_POST['servicio'];
	 $vuelo   = $_POST['vuelo'];

	for ($i=0; $i < count($check);$i++){		 
		 $sql   = "SELECT guia_madre FROM tblcoldroom WHERE codigo='".$check[$i]."'";
		 $query = mysqli_query($link, $sql);
		 $row   = mysqli_fetch_array($query);
   		if($row[0]!= 0){  //Si tiene guia la actualizo 
		     //identificar la aerolinea de esa guia madre
			 if($guia == 406) { //Es UPS
			      $airline = 'UPS';				 
			 }else{
				 	if($guia == 369) { //Es ATLAS
						  $airline = 'ATLAS';				 
					 }else{
						 if($guia == '129') { //ES TAMPA
							  $airline = 'TAMPA';				 
						 }else{
							 if($guia == 145) { //ES LAN CHILE
								  $airline = 'LAN CHILE';				 
							 }else{      //ES KLM
							 		$airline = 'KLM';
							 }
						 }
					 }
				 }
			 $sql="Update tblcoldroom set guia_madre='".$guia ."', fecha_entrega='".$fecha."', servicio = '".$service."', fecha_vuelo = '".$vuelo."', airline='".$airline."' WHERE codigo='".$check[$i]."'";
			 $modificado= mysqli_query($link, $sql) or die("Error");
		}
    }
	if($modificado){
		echo("<script>
		       alert('Guias modificadas correctamente');
			   window.close();
			   window.opener.document.location='modificar_guia.php';
			   </script>");	
		}
   }
 
?>
</body>
</html>