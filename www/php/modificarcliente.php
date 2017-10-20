<?php 
session_start();
include ("conectarSQL.php");
include ("conexion.php");
	
$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());

//recojo el id de la orden a modificar
$id = $_GET['codigo'];	

$sql = "SELECT * FROM tblcliente INNER JOIN tblusuario ON tblusuario.id_usuario = tblcliente.vendedor WHERE id_vendedor =".$id.";";
$query = mysql_query($sql,$link);
$row   = mysql_fetch_array($query);
$id = $row['id_vendedor'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Modificar cliente</title>
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
            empresa: "required",
            direccion: "required",
            ciudad: "required",
            zip: "required",
            pais: "required",
            telefono: "required",
            vendedor: "required",
            mail: {
              required: true,
              email: true
            }
      },
      
      // Specify the validation error messages
      messages: {
            codigo: "Por favor inserte el Código",
            empresa: "Por favor inserte el Nombre",
            direccion: "Por favor inserte la Dirección",
            ciudad:"Por favor inserte la Ciudad",
            zip:"Por favor inserte el Código Postal",
            pais:"Por favor seleccione un País",
            telefono:"Por favor inserte un número de Teléfono",
            vendedor:"Por favor inserte un Vendedor",
            mail: "Por favor inserte una dirección de correo válida"
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
<form id="form1" name="form1" method="post" novalidate>
<table width="500" border="0" align="center">
     <tr>
    <td width="500" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Modificar Datos del cliente</font> <font color="#FF0000"><?php echo $row['empresa']?></font></strong></td>
  </tr>
</table>
<table width="500" border="0" align="center">
 <tr height="20"><td></td></tr>
     <tr>
     	<td>
        <table border="0" align="center">
        	<tr>
            	<td><strong>Código</strong></td>
                <td><input type="text" id="codigo" name="codigo" value="<?php echo $row['codigo'];?>" readonly/>
                *</td>
            </tr>
            <tr>
            	<td><strong>Nombre</strong></td>
                <td><input type="text" id="empresa" name="empresa" value="<?php echo $row['empresa'];?>" />
                *</td>
            </tr>
            <tr>
              <td><strong>Nombre2</strong></td>
                <td><input type="text" id="empresa2" name="empresa2" value="<?php echo $row['empresa2'];?>" />
                </td>
            </tr>
            <tr>
            	<td><strong>Dirección</strong></td>
                <td><input type="text" id="direccion" name="direccion" value="<?php echo $row['direccion'];?>" />
                *</td>
            </tr>
            <tr>
            	<td><strong>Dirección2</strong></td>
                <td><input type="text" id="direccion2" name="direccion2" value="<?php echo $row['direccion2'];?>" /></td>
            </tr>
            <tr>
            	<td><strong>Ciudad</strong></td>
                <td><input type="text" id="ciudad" name="ciudad" value="<?php echo $row['ciudad'];?>" />
                *</td>
            </tr>
            <tr>
            	<td><strong>Estado</strong></td>
                <td><input type="text" id="estado" name="estado" value="<?php echo $row['estado'];?>" /></td>
            </tr>
            <tr>
            	<td><strong>Zip</strong></td>
                <td><input type="text"  id="zip" name="zip" value="<?php echo $row['zip'];?>" />
                *</td>
            </tr>
             <tr>
            	<td><strong>País</strong></td>
                <td><select type="text" name="pais" id="pais" tabindex="20">
                    <?php 
                      //Consulto la bd para obtener solo los id de item existentes
                      $sql   = "SELECT * FROM tblpaises_destino";
                      $query = mysql_query($sql,$link);
                        //Recorrer los iteme para mostrar
                      echo '<option value=""></option>'; 
                      while($row1 = mysql_fetch_array($query)){
                          if($row['pais']==$row1["codpais"]){
                            echo '<option value="'.$row1["codpais"].'" selected="selected">'.$row1["pais_dest"].'</option>';
                          } else {
                            echo '<option value="'.$row1["codpais"].'">'.$row1["pais_dest"].'</option>';
                          }
                      }
                    ?>    
                </select>*</td>
            </tr>
            <tr>
            	<td><strong>Teléfono</strong></td>
                <td><input type="text" id="telefono" name="telefono" value="<?php echo $row['telefono'];?>" />
                *</td>
            </tr>
             <tr>
            	<td><strong>Vendedor</strong></td>
                <td>
                    <select type="text" name="vendedor" id="vendedor">
                    <?php
                  $consulta = "select id_usuario,cpnombre FROM tblusuario";
                  $result = mysql_query($consulta,$link);
                  
                  while($fila = mysql_fetch_array($result)){
                      if($fila['id_usuario']==$row['vendedor']){
                        echo "<option  value='".$fila['id_usuario']."' selected='selected'>".$fila['cpnombre']."</option>";
                      }else {
                         echo "<option  value='".$fila['id_usuario']."'>".$fila['cpnombre']."</option>";
                      }
                    }
	          ?>
                    </select>
                *</td>
            </tr>
            <tr>
            	<td><strong>e-mail</strong></td>
                <td><input type="text" id="mail" name="mail" value="<?php echo $row['mail'];?>" />
                *</td>
            </tr>
            <tr>
            <td><input name="Modificar" type="submit" value="Modificar" /></td>
            <td><input name="Cancelar" type="submit" value="Cancelar" onclick="self.close()" /></td>
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
 	 /*echo("<script> window.close()</script>");*/
	 $codigo       = $_POST['codigo'];
	 $empresa      = $_POST['empresa'];
         $empresa2      = $_POST['empresa2'];
	 $direccion     = $_POST['direccion'];
	 $direccion2     = $_POST['direccion2'];
	 $ciudad     = $_POST['ciudad'];
	 $estado  = $_POST['estado'];
	 $zip  = $_POST['zip'];
	 $pais  = $_POST['pais'];
	 $telefono  = $_POST['telefono'];
	 $vendedor  = $_POST['vendedor'];
	 $mail  = $_POST['mail'];

	 $sql="UPDATE tblcliente set codigo ='".$codigo ."' , empresa ='".$empresa ."' , direccion='".$direccion."', direccion2='".$direccion2."',ciudad='".$ciudad."', estado='".$estado."', zip='".$zip."', pais ='".$pais ."', telefono='".$telefono."', vendedor='".$vendedor."', mail='".$mail."', empresa2='".$empresa2."' where id_vendedor='".$id."'";
	 
	 
	 $modificado= mysql_query($sql,$link);
  	 if($modificado){
	 	echo("<script> alert ('Cliente modificado correctamente');
		               window.close();
                               window.opener.document.location='crearClientes.php';
		     </script>");
	 }else{
		 echo("<script> alert (".mysql_error().");</script>");
		 }
   }
    
   /*if(isset($_POST["Cancelar"])){  
 	 echo("<script> window.close()</script>");
   }*/  
  ?>
</body>
</html>