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

//recojo el id de la orden a modificar
$id = $_GET['codigo'];	

$sql = "SELECT * FROM tblagencia WHERE codigo_agencia =".$id.";";
$query = mysqli_query($link, $sql);
$row   = mysqli_fetch_array($query);
$id = $row['codigo_agencia'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Modificar datos de la agencia</title>
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
  $(function() {
      // Setup form validation on the #register-form element
      $("#form1").validate({
      errorClass: "my-error-class",
      validClass: "my-valid-class",
  
      // Specify the validation rules
      rules: {
            codigo: "required",
            nombre: "required"
      },
      
      // Specify the validation error messages
      messages: {
            nombre: "Por favor inserte el Nombre",
            codigo: "Por favor inserte el Código"
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
<table width="350" border="0" align="center">
     <tr>
    <td width="300" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Modificar Datos de la Agencia <?php echo $row['nombre_agencia']?></font></strong></td>
  </tr>
</table>
<table width="350" border="0" align="center">
 <tr height="20"><td></td></tr>
     <tr>
     	<td>
        <table border="0" align="center">
            <tr>
            	<td><strong>Código:</strong></td>
                <td><input type="text" id="codigo" name="codigo" value="<?php echo $row['codigo_agencia'];?>" /></td>
            </tr>
            <tr>
            	<td><strong>Nombre:</strong></td>
                <td><input type="text" id="nombre" name="nombre" value="<?php echo $row['nombre_agencia'];?>" /></td>
            </tr>
            <tr>
            <td><input name="Modificar" type="submit" value="Modificar" /></td>
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
   if(isset($_POST["Modificar"])){  
	 
	 $nombre    = $_POST['nombre'];
	 $codigo    = $_POST['codigo'];
	 
	 $sql="UPDATE tblagencia set codigo_agencia='".$codigo."' , nombre_agencia='".$nombre."'  where codigo_agencia='".$id."'";

	 $modificado= mysqli_query($link, $sql);
  	 if($modificado){
	 	echo("<script> alert ('Agencia modificada correctamente');
		               window.close();					   window.opener.document.location='crearagencia.php';
		     </script>");
	 }else{
		 echo("<script> alert (".mysqli_error().");</script>");
		 }
   }
    
   /*if(isset($_POST["Cancelar"])){  
 	 echo("<script> window.close()</script>");
   } */ 
  ?>
</body>
</html>