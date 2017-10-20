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
  </style>
  <script>
  // When the browser is ready...
  $(function() {
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

      /*$('#Cancelar').delegate('','click change',function(){
          window.location = "gestionarordenes.php";
      return false;
      });*/
    });
  });
  </script>
</head>
<body>
<form id="form1" name="form1" method="post" novalidate="novalidate" action="" .error { color:red}>
<table width="500" border="0" align="center">
     <tr>
    <td width="500" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Registrar nueva finca</font></strong></td>
  </tr>
</table>
<table width="500" border="0" align="center">
 <tr height="20"><td></td></tr>
     <tr>
     	<td>
        <table border="0" align="center">
            <tr>
            	<td><strong>Razón Social:</strong></td>
                <td><input type="text" id="nombre" name="nombre" value="" autofocus="autofocus"/>
                <strong>*</strong></td>
            </tr>
            <tr>
            	<td><strong>Dirección:</strong></td>
                <td><input type="text" id="direccion" name="direccion" value="" />
                <strong>*</strong></td>
            </tr>
            <tr>
            	<td><strong>RUC:</strong></td>
                <td><input type="text" id="ruc" name="ruc" value="" />
                <strong>*</strong></td>
            </tr>
            <tr>
            	<td><strong>Provincia-Ciudad</strong>:</td>
                <td><input type="text" id="ciudad" name="ciudad" value="" />
                <strong>*</strong></td>
            </tr>
            <tr>
            	<td><strong>Teléfono:</strong></td>
                <td><input type="text" id="telefono" name="telefono" value="" />
                <strong>*</strong></td>
            </tr>
            <tr>
            	<td><strong>Contacto:</strong></td>
                <td><input type="text" id="contacto" name="contacto" value="" /></td>
            </tr>
            <tr>
            	<td><strong>Mail:</strong></td>
                <td><input type="text" id="mail" name="mail" value="" /></td>
            </tr>
            <tr>
            	<td><strong>Código de Finca:</strong></td>
                <td><input type="text" id="farm" name="farm" value="" /></td>
            </tr>
            <tr>
            	<td><strong>Código de País:</strong></td>
                <td>
                  <select type="text" name="country" id="country">
                    <?php 
                      //Consulto la bd para obtener solo los id de item existentes
                      $sql   = "SELECT * FROM tblpaises_destino";
                      $query = mysqli_query($link, $sql);
                        //Recorrer los iteme para mostrar
                      echo '<option value=""></option>'; 
                      while($row1 = mysqli_fetch_array($query)){
                            echo '<option value="'.$row1["codpais"].'">'.$row1["pais_dest"].'</option>'; 
                          }
                    ?>    
                </select>
                *</td>
                <strong>*</strong></td>
            </tr>
            <tr>
            <td align="right"><input name="Registrar" type="submit" value="Registrar" title="Registrar la nueva finca" /></td>
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
 	
	 $nombre= $_POST['nombre'];
	 $ruc = $_POST['ruc'];
	 $direccion = $_POST['direccion'];
	 $ciudad = $_POST['ciudad'];
	 $telefono= $_POST['telefono'];
	 $contacto= $_POST['contacto'];
	 $mail= $_POST['mail'];
	 $farm= $_POST['farm'];
	 $country= $_POST['country'];
	 
	 $sql="INSERT INTO tblfinca (`codigo`, `nombre`,`direccion`, `ruc`,`prov_ciudad`,`telefono`,`contacto`,`mail`,`farm_code`,`pais_code`) VALUES ('$ruc','$nombre','$direccion','$ruc','$ciudad','$telefono','$contacto','$mail','$farm','$country')";

	 $insertado= mysqli_query($link, $sql);
  	 if($insertado){
	 	echo("<script> alert ('Finca registrada correctamente');
		               window.close();					   
                                window.opener.document.location='crearFincas.php';
		     </script>");
	 }else{
		 echo("<script> alert (".mysqli_error().");</script>");
		 }
   }
    ?>
</body>
</html>