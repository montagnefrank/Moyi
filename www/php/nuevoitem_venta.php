<?php 

//ini_set('display_errors', 'On');
//ini_set('display_errors', 1);

session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");

$user     =  $_SESSION["login"];
$passwd   =  $_SESSION["passwd"];
$rol      =  $_SESSION["rol"];

$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());

$sqluser = "SELECT id_usuario FROM tblusuario WHERE cpuser = '".$user."'";
$queryuser = mysql_query($sqluser,$link);
$rowuser = mysql_fetch_array($queryuser);

$user = $rowuser['id_usuario'];

$id_order = 0;
$error = $_GET['error'];

$codcliente = $_GET['codcliente'];  

if($codcliente) {

  $sql = "select * from tblcliente where codigo = '".$codcliente."';"; 

  $query = mysql_query($sql,$link);
  $row9   = mysql_fetch_array($query);

}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Añadir Item</title>
    <link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
    <script src="//code.jquery.com/jquery-1.9.1.js"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>


  <!--Buscador en linea -->
   <!-- Referencias nuevas a JQUERY -->
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
  <link href="../css/jqueryui.css" type="text/css" rel="stylesheet"/>

  <script>
  jQuery.validator.addMethod("noSpace", function (value, element) { 
    return value.indexOf(" ") < 0 && value != ""; 
  }, "No space please and don't leave it empty");

    // When the browser is ready...
    $(function() {
        $("#item1").autocomplete({
              source: "buscar_item.php",
              minLength: 2
        });
    });
  </script>
  <!--Fin del buscador en linea -->
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
    }, "No space please and don't leave it empty");
    // When the browser is ready...
    $(function() {
        // Setup form validation on the #register-form element
        $("#form1").validate({
        errorClass: "my-error-class",
        validClass: "my-valid-class",
    
        // Specify the validation rules
        rules: {
        	    item1: "required",
              cantidad1: {
                required: true,
                noSpace: true,
                number: true
            },
              precioUnitario: {
                required: true,
                noSpace: true,
                number: true
            }
        },
        
        // Specify the validation error messages
        messages: {
              item1: "Inserte un item",
              cantidad1: {
                required: "Inserte una cantidad",
                noSpace: "No puede dejar espacios en blanco",
                number: "Solo números y un punto (.)"
              },
              precioUnitario: {
                required: "Inserte un precio",
                noSpace: "No puede dejar espacios en blanco",
                number: "Solo números y un punto (.)"
              }
        },
       
        submitHandler: function(form) {
            form.submit();
        }
      });
    });
  </script>
</head>
<body>
<form id="form1" name="form1" method="post" novalidate="novalidate" action="" .error { color:red}>
<table width="500" border="0" align="center">
     <tr>
    <td width="500" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Añadir Item</font> <font color="#FF0000"><?php echo $row['empresa']?></font></strong></td>
  </tr>
</table>
<table width="500" border="0" align="center">
 <tr height="20"><td></td></tr>
  <tr>
 	<td>
    <table border="0" align="center">
      <tr>               
        <td>
          <!-- <input type="button" class="btn btn-primary" id="showitem" name="showitem" value="Añadir item" onclick="mostraritem()"/> -->
          <tr><td><input type="text" id="item1" name="item1" value="" placeholder="Producto" size="40" size="50"/></td></tr>
          <tr><td><input type="text" id="cantidad1" name="cantidad1" placeholder="Cantidad" value="" size="10"/></td></tr>
          <tr><td><input type="text" id="precioUnitario" name="precioUnitario" placeholder="Precio Unitario" value="" size="10"/></td></tr>
          <tr><td><textarea type="text" id="mensaje1" name="mensaje1" tabindex="19" placeholder="Mensaje..." size="70" style="width:70;height:150;resize:none;"/></textarea></td></tr>
        </td>
      </tr>   
        <tr>
          <td>
            <input name="Registrar" type="submit" value="Registrar" />
            <input name="Cancelar" type="submit" value="Cancelar" onclick="self.close();" />
          </td>
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

  //$item1 = mysql_real_escape_string($_POST['item1']);

  $item1 = addslashes($_POST['item1']);


  $cantidad1= $_POST['cantidad1'];
  $precioUnitario = $_POST['precioUnitario'];
  $mensaje1 = addslashes($_POST['mensaje1']);

  
  //$enviara = "Ana";
  //$client = $row9['empresa'];


  /*if($mensaje1 == ''){
    $mensaje1 = "To-Blank Info   ::From- Blank Info   ::Blank .Info"; 
  }
  else{
    $mensaje1 = "To-".$enviara."::From-".$client."::".$mensaje1;
  }*/

  //Insertar los datos en la tabla del carro venta

  //Recoger id_item
  $sqlitem = 'SELECT * FROM tblproductos WHERE id_item = "'.$item1.'"   ';
  $queryitem = mysql_query($sqlitem,$link);
  $rowitem = mysql_fetch_array($queryitem);

  $item1 = $rowitem['id_item'];

  if($item1 == ''){
        echo("<script> alert ('Item no encontrado'); </script>");
  }
  else{

    $sqlcarro = "INSERT INTO tblcarro_venta (codcliente, id_item, cantidad, preciounitario, mensaje, id_usuario) VALUES ('".$codcliente."','".$item1."','".$cantidad1."','".$precioUnitario."','".$mensaje1."','".$user."')";
    $query_carro = mysql_query($sqlcarro,$link);
    
    //echo "<script> window.location.href='crearorden.php'</script>";

    //echo $sqlcarro;
    //exit();

      echo("<script> alert ('Item insertado correctamente');
         window.close();	
         window.opener.document.location='crearorden.php';
            </script>");
  }
} // Fin if de isset

    /*if(isset($_POST["Cancelar"])){  
     echo("<script> window.close()</script>");
    } */ 
?>
</body>
</html>