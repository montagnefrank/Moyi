<?php 
session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");
	
$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Registrar nueva Finca</title>
  <link rel="icon" type="image/png" href="../images/favicon.ico" />
  <link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
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
  // When the browser is ready...
  /*$(function() {
      // Setup form validation on the #register-form element
      $("#form1").validate({
      errorClass: "my-error-class",
      validClass: "my-valid-class",
  
      // Specify the validation rules
      rules: {
            nombre: "required",
            ruc: "required",
            direccion: "required",
            ciudad: "required",
            telefono: "required",
            country: "required",
            mail: {
              //required: true,
              email: true
            }
      },
      
      // Specify the validation error messages
      messages: {
            nombre: "Por favor inserte el Nombre",
            ruc: "Por favor inserte el RUC",
            direccion: "Por favor inserte la Dirección",
            ciudad:"Por favor inserte la Ciudad",
            telefono:"Por favor inserte un número de Teléfono",
            country: "Por favor inserte el código del país",
            mail: "Por favor insterte una dirección de correo válida"
      },
     
      submitHandler: function(form) {
          form.submit();
      }

    });
  });    */  
  </script>
</head>
<body>
<form id="form1" name="form1" method="post" novalidate="novalidate" action="" .error { color:red}>
<table width="500" border="0" align="center">
     <tr>
    <td width="500" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Insertar PO Number</font></strong></td>
  </tr>
</table>
<table width="500" border="0" align="center">
 <tr height="20"><td></td></tr>
     <tr>
     	<td>
        <table border="0" align="center">
            <tr>
            	<td><strong>PO Number:</strong></td>
              <td><input type="text" id="ponumber" name="ponumber" value="" autofocus="autofocus"/>
              <strong>*</strong></td>
            </tr>
            <tr>
              <td align="right"><input name="Insertar" type="submit" value="Insertar" title="Insertar PO Number" /></td>
              <td><input name="Cancelar" type="submit" value="Cancelar" title="Cancelar el registro" onclick="self.close()" /></td>
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
 	 /*echo("<script> window.close()</script>");*/
	 $nombre    = $_POST['nombre'];
	 $ruc = $_POST['ruc'];
	/* $codigo    = substr($nombre,0,4);
	 $codigo    = $codigo.$ruc;*/
	 //echo $codigo;
	 $direccion = $_POST['direccion'];
	 $ciudad = $_POST['ciudad'];
	 $telefono= $_POST['telefono'];
	 $contacto= $_POST['contacto'];
	 $mail= $_POST['mail'];
	 $farm= $_POST['farm'];
	 $country= $_POST['country'];
	 
	 $sql="INSERT INTO tblfinca (`codigo`, `nombre`, `ruc`,`direccion`,`prov_ciudad`,`telefono`,`contacto`,`mail`,`farm_code`,`pais_code`) VALUES ('$ruc','$nombre','$ruc','$direccion','$ciudad','$telefono','$contacto','$mail','$farm','$country')";

	 $insertado= mysql_query($sql,$link);
  	 if($insertado){
	 	echo("<script> alert ('Finca registrada correctamente');
		               window.close();					   
					   window.opener.document.location='crearFincas.php';
		     </script>");
	 }else{
		 echo("<script> alert (".mysql_error().");</script>");
		 }
   }
    
   /*if(isset($_POST["Cancelar"])){  
 	 echo("<script> window.close()</script>");
   } */ 
?>
</body>
</html>