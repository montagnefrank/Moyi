<?php 
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

$codigo = $_GET['codigo'];  

if(isset($_GET['fecha'])) {
  $_SESSION['fecha'] = $_GET['fecha'];
}
if(isset($_GET['ftentativa'])) {
  $_SESSION['ftentativa'] = $_GET['ftentativa'];
}
if(isset($_GET['destino'])) {
  $_SESSION['destino'] = $_GET['destino'];
}
if(isset($_GET['agencia'])) {
  $_SESSION['agencia'] = $_GET['agencia'];
}


if($codigo) {
  $sqldatos = "SELECT * FROM tblcarro_pedido INNER JOIN tblproductos ON tblcarro_pedido.id_item=tblproductos.id_item WHERE idcompra_pedido = '".$codigo."' and id_usuario = ".$user." ";  
  $querydatos = mysql_query($sqldatos,$link);
  $rowdatos = mysql_fetch_array($querydatos);
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Modificar Item</title>
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
  <!--Fin del buscador en linea -->
    <style type="text/css">
      .my-error-class {
          color:red;
      }
     
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
<form id="form1" name="form1" method="post" novalidate="novalidate" action="">
<table width="500" border="0" align="center">
     <tr>
    <td width="500" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Modificar Item</font> <font color="#FF0000"><?php echo $row['empresa']?></font></strong></td>
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
          <tr>
            <td><strong>Producto:</strong></td>
            <td>
                <select id="item1" name="item1" placeholder="Producto">
                <?php
                $sql1   = "SELECT * FROM tblproductos";
               $query1 = mysql_query($sql1,$link) or die ("Error leyendo los procductos");
               while($row2 = mysql_fetch_array($query1)){
                   if($rowdatos['prod_descripcion']==$row2['prod_descripcion'])
                   {
                      echo '<option value="'.$row2['id_item'].'" selected="selected">'.$row2['id_item'].'-'.$row2['prod_descripcion'].'</option>';
                   }
                   else
                   {
                     echo '<option value="'.$row2['id_item'].'">'.$row2['id_item'].'-'.$row2['prod_descripcion'].'</option>';  
                   }
               }
            ?>
                </select>
            
          </tr>
          <tr>
            <td><strong>Cantidad:</strong></td>
            <td><input type="text" id="cantidad1" name="cantidad1" placeholder="Cantidad" value="<?php echo $rowdatos['cantidad'];?>" size="10"/></td>
          </tr>
          <tr>
          <td><strong>Precio:</strong></td>
            <td><input type="text" id="precioUnitario" name="precioUnitario" placeholder="Precio Unitario" value="<?php echo $rowdatos['preciounitario'];?>" size="10"/></td>
          </tr>
          <!-- <tr><td><textarea type="text" id="mensaje1" name="mensaje1" tabindex="19" placeholder="Mensaje..." size="70" style="width:70;height:150;resize:none;"/></textarea></td></tr>  -->
        </td>
      </tr>   
        <tr>
          <td>
            <input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo;?>" />  
            <input name="Registrar" type="submit" value="Registrar" />
            <input formnovalidate name="Cancelar" type="submit" value="Cancelar" class="cancel"/>
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

  $item1 = addslashes($_POST['item1']);
  $cantidad1= $_POST['cantidad1'];
  $precioUnitario = $_POST['precioUnitario'];
  $idcompra_pedido = $_POST['codigo'];

  //Insertar los datos en la tabla del carro pedido
  //Recoger id_item
  $sqlitem = 'SELECT * FROM tblproductos WHERE id_item = "'.$item1.'"   ';
  $queryitem = mysql_query($sqlitem,$link);
  $rowitem = mysql_fetch_array($queryitem);
  $item1 = $rowitem['id_item'];

  if($item1 == ''){
        echo("<script> alert ('Item no encontrado'); </script>");
  }
  else{
    $sqlcarro = "UPDATE tblcarro_pedido SET id_item = '".$item1."', cantidad = '".$cantidad1."', preciounitario = '".$precioUnitario."' WHERE idcompra_pedido='".$idcompra_pedido."'";
    $query_carro = mysql_query($sqlcarro,$link);

    if($query_carro){
      echo("<script> alert ('Item modificado correctamente');
         window.close();	
         window.opener.document.location='nuevaetiqueta.php?$codfinca';
            </script>");
    }
    else{
      echo("<script> alert ('Error modificando el item'); </script>");
    }
  }
} // Fin if de isset

    if(isset($_POST["Cancelar"])){  
     echo "<script>window.close();window.opener.document.location='nuevaetiqueta.php?$codfinca';</script>";
    } 
?>
</body>
</html>