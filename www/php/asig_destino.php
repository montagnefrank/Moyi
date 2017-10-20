<?php 
session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");

$conection = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());

$user     =  $_SESSION["login"];
$passwd   =  $_SESSION["passwd"];
$bd       =  $_SESSION["bd"];
$rol      =  $_SESSION["rol"];
$caja     =  $_SESSION['cajas'];	

if($_SESSION['idcliente']==''){
	 //echo("<script> alert ('Item no encontrado'); </script>");
	echo ("<script> alert('Debe seleccionar un cliente'); 
	window.close();
		</script>");

}

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Asignar destino</title>
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
	/*
	jQuery.validator.addMethod("noSpace", function (value, element) { 
		return value.indexOf("_") < 0 && value != ""; 
	}, "No space please and don't leave it empty");

	// When the browser is ready...
	$(function() {

	*/
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
	/*
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
	
	*/
	</script>
</head>
<body>
<form id="form1" name="form1" method="post">
<table width="350" border="0" align="center">
     <tr>
    <td width="350" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Asignar destino</font></strong></td>
  </tr>
</table>
<table width="350" border="0" align="center">
 <tr height="20"><td></td></tr>
     <tr>
     	<td>
        <table border="0" align="center" width="350">
            <tr>
            	<td><strong>Destino:</strong></td>
                <td>
                  <select type="text" name="itemDestino" id="itemDestino" tabindex="20">
                    <?php 
                      //Consulto la bd para obtener solo los id de item existentes
                      $sql   = "SELECT * FROM tbldestinos WHERE codcliente = '".$_SESSION['idcliente']."'  ";
                      $query = mysql_query($sql,$conection);
                        //Recorrer los iteme para mostrar
                      echo '<option value=""></option>'; 
                      while($row10 = mysql_fetch_array($query)){
                            echo '<option value="'.$row10["iddestino"].'">'.$row10["destino"].'</option>'; 
                          }
                    ?>    
                </select>
                </td>
            </tr>
            <tr>
            <td align="right"><input name="Registrar" type="submit" value="Asignar" /></td>
            <td><input name="Cancelar" type="submit" value="Cancelar"/></td>
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
	 /*$guia    = $_POST['guia'];
	 $guia1   = $_POST['guia1'];
	 $guia    = $guia."-".$guia1;
	 $fecha   = $_POST['fecha'];
	 $service   = $_POST['servicio'];
	 $vuelo   = $_POST['vuelo'];
	 */
	 $check   = $_SESSION['cajas'];


	 $itemDestino = $_POST['itemDestino'];

	 //print_r($check);
	 //exit();


	  for ($i=0; $i < count($check);$i++){		 
		 //$sql   = "SELECT guia_madre FROM tblcoldroom WHERE codigo='".$check[$i]."'";
		 //$sql   = "SELECT * FROM tblcarro_venta WHERE idcompra='".$check[$i]."'";
		 //$query = mysql_query($sql,$link);
		 //$row   = mysql_fetch_array($query);
   		 //if($row[0]== 0){  //Si no tiene guia le asigno
			 //$sql="Update tblcoldroom set guia_madre='".$guia ."', fecha_entrega='".$fecha."', servicio = '".$service."', fecha_vuelo = '".$vuelo."', airline='".$airline."' WHERE codigo='".$check[$i]."'";

		$sql="update tblcarro_venta set iddestino='".$itemDestino ."' WHERE idcompra='".$check[$i]."'";

		//echo $sql;

		$modificado= mysql_query($sql,$conection) or die("Error");
			 
		 //}else{
			 /*echo("<script> alert ('Ya existen guias asignadas en la selección de guias madres, revise por favor!');</script>");*/
			// }
        }
		if($modificado){
			echo("<script>
			       alert('Destinos asignados correctamente');
				   window.close();
				   window.opener.document.location='crearorden.php';
				   </script>");	
			}
			   
   }

    
   if(isset($_POST["Cancelar"])){  
 	 echo("<script>window.close();
				   window.opener.document.location='crearorden.php';
				   </script>");
   } 
?>
</body>
</html>