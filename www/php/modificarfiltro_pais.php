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

//echo $id;


$sql = "SELECT * FROM tblpaises_destino WHERE idpais =".$id.";";
$query = mysqli_query($link, $sql);
$row   = mysqli_fetch_array($query);
$id = $row['idpais'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Modificar datos del filtro</title>
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
            pais: "required",
            ciudad: "required",
            codciudad: "required"

      },
      
      // Specify the validation error messages
      messages: {
            pais: "Inserte el país",
            ciudad: "Inserte la ciudad",
            codciudad: "Inserte el código de la ciudad"
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
    <td width="300" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Modificar Datos del Filtro <?php echo $row['pais_recepcion']?></font></strong></td>
  </tr>
</table>
<table width="350" border="0" align="center">
 <tr height="20"><td></td></tr>
     <tr>
     	<td>
        <table border="0" align="center">
            <tr>
              <td><strong>Código País:</strong></td>
                <td><input type="text" id="codpais" name="codpais" value="<?php echo $row['codpais'];?>" />
                <strong>*</strong></td>
            </tr>
            <tr>
              <td><strong>País:</strong></td>
                <td><input type="text" id="pais" name="pais" value="<?php echo $row['pais_dest'];?>" autofocus="autofocus"/>
                <strong>*</strong></td>
            </tr>


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
	 
   $codpais = $_POST['codpais'];
   $pais    = $_POST['pais'];
	 
	 $sql="UPDATE tblpaises_destino set codpais='".$codpais."' , pais_dest='".$pais."'  where idpais='".$id."' ";

   echo "id: ".$id;
   echo $sql;

	 $modificado= mysqli_query($link, $sql);
  	 if($modificado){
	 	echo("<script> alert ('Filtro modificado correctamente');
		               window.close();					   window.opener.document.location='crearfiltros.php';
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