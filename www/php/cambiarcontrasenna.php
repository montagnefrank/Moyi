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

$id = $_GET['codigo'];
$sql = "select * FROM tblusuario WHERE id_usuario = '".$id."';";
$query = mysqli_query($link, $sql);
$row   = mysqli_fetch_array($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Cambiar contraseña</title>
	<link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" media="all" href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
	<script type="text/javascript" src="../js/calendar.js"></script>
	<script type="text/javascript" src="../js/calendar-en.js"></script>
	<script type="text/javascript" src="../js/calendar-setup.js"></script>

  	<script src="//code.jquery.com/jquery-1.9.1.js"></script>
  	<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

  <style type="text/css">
    .my-error-class {
        color:red;
    }
    /*.my-valid-class {
        color:green;
    }*/
  </style>

<script>

  jQuery.validator.addMethod("noSpace", function (value, element) { 
    return value.indexOf(" ") < 0 && value != ""; 
  }, "");

  jQuery.validator.addMethod("alphanumeric", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
  }, "");

  jQuery.validator.addMethod("barcode", function (value, element) {
    return this.optional(element) || /[a-z0-9 -()+]+$/.test(value);
  }, "");

  jQuery.validator.addMethod("checkpass", function (value, element) {
    return this.optional(element) || /(([\W]+[\d]+[A-Za-z]+)|([\W]+[A-Za-z]+[\d]+$)|([A-Za-z]+[\d]+[\W]+$)|([A-Za-z]+[\W]+[\d]+$)|([\d]+[\W]+[A-Za-z]+$)|([\d]+[A-Za-z]+[\W]+$))/.test(value);
  }, "");

  jQuery.validator.setDefaults({
    debug: true,
    success: "valid"
  });


  // When the browser is ready...
  $(function() {
      // Setup form validation on the #register-form element
      $("#form1").validate({
        errorClass: "my-error-class",
        validClass: "my-valid-class",
    
        // Specify the validation rules
        rules: {
              passwd1: {
                required: true,
                checkpass: true,
                minlength: 4,
                maxlength: 20,
              },
              passwd2: {
                required: true,
                checkpass: true,
                minlength: 4,
                maxlength: 20,
                equalTo: "#passwd1",
              },
        },    
        // Specify the validation error messages
        messages: {
              passwd1: {
                required: "Campo requerido",
                checkpass: "Al menos una letra, un número y caracter especial",
                maxlength: "20 caracteres como máximo",
                minlength: "Al menos 4 caracteres"
              },
              passwd2: {
                required: "Campo requerido",
                checkpass: "Al menos una letra, un número y caracter especial",
                maxlength: "20 caracteres como máximo",
                minlength: "Al menos 4 caracteres",
                equalTo: "Las contraseñas deben coincidir"
              },
        },

        submitHandler: function(form) {
            form.submit();
        }

        /*$('#Cancelar').delegate('','click change',function(){
            window.location = "gestionarordenes.php";
        return false;
        });*/
      });

      //$("#item").mask("**********");

    });
  </script>




</head>
<body>
<form id="form1" name="form1" method="post" novalidate="novalidate" action="" .error { color:red}>
<table width="350" border="0" align="center">
	<tr>
    	<td width="350" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Cambiar contraseña del  usuario </font><font color="#FF0000"><?php echo $row['cpuser'];?></font></strong></td>
  	</tr>
</table>
<table width="350" border="0" align="center">
 <tr height="20"><td></td></tr>
     <tr>
     	<td>
        <table width="331" border="0" align="center">
            <tr>
            	<td width="150"><strong>Actual Contraseña:</strong></td>
                <td width="171"><input type="password" id="passwd" name="passwd" value="" autofocus="autofocus"/></td>
            </tr>
            <tr>
            	<td width="150"><strong>Nueva Contraseña:</strong></td>
                <td width="171"><input type="password" id="passwd1" name="passwd1" value=""/></td>
            </tr>
            <tr>
            	<td width="150"><strong>Repetir Nueva Contraseña:</strong></td>
                <td width="171"><input type="password" id="passwd2" name="passwd2" value="" /></td>
            </tr>
            <tr>
            <td align="right"><input name="Cambiar" type="submit" value="Cambiar"/></td>
            <td><input name="Cancelar" type="submit" value="Cancelar" onclick="self.close();" /></td>
            </tr>
        </table>
        </td>
     </tr>
     <tr height="20"><td></td></tr>
    <tr>
    <td height="36" align="center" bgcolor="#3B5998" colspan="5"><strong><font color="#FFFFFF">Bit <img src="../images/r.png" width="15" height="15"/> 2015 versión 3 </font></strong></td>
  </tr>
  </tr>
</table>
</form>
  <?php
   if(isset($_POST["Cambiar"])){  
	 $cont          = $_POST['passwd'];
	 $cont1         = $_POST['passwd1'];
	 $cont2         = $_POST['passwd2'];
	 
	 //verifico que la contrasenna actual este bien
	 $sql = "SELECT * FROM tblusuario WHERE id_usuario='".$id."' AND cppassword='".$cont."'" ;
	 $query= mysqli_query($link, $sql) or die ("Error al consultar contraseña actual");
	 $cant = mysqli_num_rows($query);
	 if($cant == 1){ //si hay un usuario con esa contrasenna
	 
		 //verifico que la nueva contrasenna coincida con la confirmacion
		 if(strcmp($cont1,$cont2)== 0){// Si son iguales entonces cambio la contrasenna
			 
			 $sql="UPDATE tblusuario set cppassword='".$cont1."' WHERE id_usuario='".$id."'";
			 $modificado= mysqli_query($link, $sql)or die ("Error cambiando la contraseña");
			 
			 if($modificado){
				 if($row['cpuser']=='eblooms'){
					echo("<script> alert ('Contraseña modificada correctamente');
								   window.close();					   
								   window.opener.document.location='usuarios.php';
						 </script>");
				 }else{
					 echo("<script> alert ('Contraseña modificada correctamente');
								   window.close();					   
						 </script>");
						 
						 //verificar que rol es el que esta cabiando la contrasenna
						 if($row['idrol_user'] == 2 ||$row['idrol_user'] == 3 ){
						 	echo("<script> window.opener.document.location='../main.php?panel=mainpanel.php';</script>");
						 }else{
							 if($row['idrol_user'] == 4){
						 		echo("<script> window.opener.document.location='mainroom.php';</script>");
							 }else{
								 if($row['idrol_user'] == 6){
						 			echo("<script> window.opener.document.location='services.php';</script>");
								 }else{
									 echo("<script> window.opener.document.location='imp_etiquetas.php';</script>");
									 }
							 }
						 }
					 }
			 }else{
				 echo("<script> alert (".mysqli_error().");</script>");
				 } 
	 
		 }else{
			 	echo("<script> alert ('La contraseña nueva y la verificación no coinciden.');</script>");
			 }
		  
	 }else{		 
		 echo("<script> alert ('Contraseña actual incorrecta.');</script>");
		 }
   }
    
   /*if(isset($_POST["Cancelar"])){  
 	 echo("<script> window.close()</script>");
   } */ 
  ?>
</body>
</html>