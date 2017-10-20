<?php 

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");
require_once('barcode.inc.php');

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

$sql = "SELECT * FROM tblcliente WHERE id_vendedor =".$id.";";
$query = mysqli_query($link, $sql);
$row   = mysqli_fetch_array($query);
$id = $row['id_vendedor'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Register Client</title>
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

  </script>

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
              codigo: "required",
              empresa: "required",
              direccion: "required",
              ciudad: "required",
              estado: "required",
              zip: "required",
              pais: "required",
              telefono: "required",
              vendedor: "required",
              mail: {
                //required: true,
                email: true
              }
        },
        
        // Specify the validation error messages
        messages: {
              codigo: "Por favor inserte el Código",
              empresa: "Por favor inserte el Nombre",
              direccion: "Por favor inserte la Dirección",
              ciudad:"Por favor inserte la Ciudad",
              estado: "Por favor inserte el Estado",
              zip:"Por favor inserte el Código Postal",
              pais:"Por favor inserte un País",
              telefono:"Por favor inserte un número de Teléfono",
              vendedor:"Por favor inserte un Vendedor"
              //mail: "Por favor inserte una dirección de correo válida"
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
    
    $(document).ready(function ($){
       
      $('#pais').on('change',function(){
       $('#estado').html(''); 
       $.ajax({
                data: "pais="+$('#pais').val(),
                url:  "modelocliente.php",
                type: "post",
                dataType: 'json',
                success:  function (response) {
                    var dato=""; 
                    for(var i=0;i<response.length;i++)
                    {
                      dato+='<option value="'+response[i][1]+'">'+response[i][0]+'</option>';
                    }
                    $('#estado').append(dato);
               },
               error:function(response) {}
          });
        }); 
      });
    </script>
</head>
<body>
<form id="form1" name="form1" method="post" novalidate="novalidate" action="" .error{color:red}>
<table width="500" border="0" align="center">
     <tr>
    <td width="500" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Registrar cliente</font> <font color="#FF0000"><?php echo $row['empresa']?></font></strong></td>
  </tr>
</table>
<table width="500" border="0" align="center">
 <tr height="20"><td></td></tr>
     <tr>
     	<td>
        <table border="0" align="center">
        <!--	<tr>
            	<td><strong>Código</strong></td>
                <td><input type="text" id="codigo" name="codigo" value=""<?php echo $row['codigo'];?>"" readonly />
                *</td>
            </tr>   -->
            <tr>
            	<td><strong>Nombre</strong></td>
                <td><input type="text" id="empresa" name="empresa" value="<?php echo $empresa;?>"/>
                *</td>
            </tr>
            <tr>
              <td><strong>Nombre 2</strong></td>
                <td><input type="text" id="empresa2" name="empresa2" value="" />
                </td>
            </tr>
            <tr>
            	<td><strong>Dirección</strong></td>
                <td><input type="text" id="direccion" name="direccion" value="" />
                *</td>
            </tr>
            <tr>
            	<td><strong>Dirección 2</strong></td>
                <td><input type="text" id="direccion2" name="direccion2" value="" /></td>
            </tr>
            <tr>
            	<td><strong>Ciudad</strong></td>
                <td><input type="text" id="ciudad" name="ciudad" value="" />
                *</td>
            </tr>
            <tr>
            	<td><strong>Estado</strong></td>
                <td><select type="text" id="estado" name="estado">
                    
                    </select>
                *</td>
            </tr>
            <tr>
            	<td><strong>Zip</strong></td>
                <td><input type="text" id="zip" name="zip" value="" />
                *</td>
            </tr>
             <tr>
            	<td><strong>País</strong></td>
                <td>
                  <select type="text" name="pais" id="pais" tabindex="20">
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
            </tr>
            <tr>
            	<td><strong>Teléfono</strong></td>
                <td><input type="text" id="telefono" name="telefono" value="" />
                *</td>
            </tr>
             <tr>
            	<td><strong>Vendedor</strong></td>
                <td>
                    <select type="text" name="vendedor" id="vendedor">
                    <?php
                  $consulta = "select id_usuario,cpnombre FROM tblusuario";
                  $result = mysqli_query($link, $consulta);
                  
                  while($fila = mysqli_fetch_array($result)){
                       echo "<option  value='".$fila['id_usuario']."'>".$fila['cpnombre']."</option>";
                    }
	          ?>
                    </select>
                *</td>
            </tr>
            <tr>
            	<td><strong>E-Mail</strong></td>
                <td><input type="text" id="mail" name="mail" value=""/>
                </td>
            </tr>
            <tr>
            <td><input name="Registrar" type="submit" value="Registrar" /></td>
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
   
    $empresa      = addslashes($_POST['empresa']);
    $empresa2      = addslashes($_POST['empresa2']);
    $direccion     = addslashes($_POST['direccion']);
    $direccion2     = addslashes($_POST['direccion2']);
    $ciudad     = addslashes($_POST['ciudad']);
    $estado  = addslashes($_POST['estado']);
    $zip  = addslashes($_POST['zip']);
    $pais  = addslashes($_POST['pais']);
    $telefono  = $_POST['telefono'];
    $vendedor  = addslashes($_POST['vendedor']);
    $mail  = $_POST['mail'];

    $sqlcod = "SELECT MAX(codigo) AS codigo FROM tblcliente";
    $querycod = mysqli_query($link, $sqlcod);
    $rowcod = mysqli_fetch_array($querycod);
    $codigo = $rowcod['codigo']+1;

    //Recoger id_item
     $sql="INSERT INTO tblcliente (`codigo`, `empresa`, `direccion`, `direccion2`,`ciudad`, `estado`, `zip`, `pais`, `telefono`, `vendedor`, `mail`, `empresa2`) VALUES ('$codigo','$empresa','$direccion','$direccion2','$ciudad','$estado','$zip','$pais','$telefono','$vendedor','$mail','$empresa2')";
     $insertado= mysqli_query($link, $sql);
      if($insertado){
      	echo("<script> alert ('Cliente insertado correctamente');
      				   window.close();	
      				   window.opener.document.location='crearClientes.php';
      		 </script>");
      }else{
      	echo("<script> alert (".mysqli_error().");</script>");
      }
    
}
?>
</body>
</html>