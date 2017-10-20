<?php 
session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");

$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());

//recojo el id de la orden a modificar
$id = $_GET['codigo'];

//Obtener la descripcion del item
$sql1 = "SELECT id_item,prod_descripcion FROM tblproductos WHERE item =".$id.";";
$query1 = mysql_query($sql1,$link);
$row1   = mysql_fetch_array($query1);

//Obtener los datos de la receta
$sql = "SELECT * FROM tblreceta WHERE item =".$row1['id_item'].";";
$query = mysql_query($sql,$link);
$row   = mysql_fetch_array($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Register new recipe</title>
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
					item: "required",
					tarifa: "required",
					cant: "required",
					porciento: "required"
	          	},
	          
				// Specify the validation error messages
				messages: {
					item: "Por favor inserte el Item",
					tarifa: "Por favor inserte el Código de la Tarifa",
					cant: "Por favor inserte la Cantidad",
					porciento: "Por favor inserte el Porciento"
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
    <td width="300" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Crear receta</font></strong></td>
  </tr>
</table>
<table width="350" border="0" align="center">
 <tr height="20"><td></td></tr>
     <tr>
     	<td>
        <table width="329" border="0" align="center">
        	<tr>
            	<td width="92" align="right"><strong>Item:</strong></td>
                <td width="213"><p>
                  <input type="text" id="item" name="item" value="<?php echo $row1['id_item']."-".$row1["prod_descripcion"]; ?>" disabled="disabled"/>
                  *</p></td>
            </tr>
            <tr>
            	<td align="right"><strong>Tariff Code:</strong></td>
                <td><input type="text" id="tarifa" name="tarifa" value="<?php echo $row["tarif_code"]; ?>" />
                *</td>
                
            </tr>
            <tr>
            	<td align="right"><strong>Cantidad Tallos:</strong></td>
                <td><input type="text" id="cant" name="cant" value="<?php echo $row["cantflores"]; ?>" />*</td
            ></tr>
            <tr><td align="right"><strong>%:</strong></td>
                <td><input type="text" id="porciento" name="porciento" value="<?php echo $row["porciento"]; ?>" />
                *</td></tr>
            <tr>
            <td align="right"><input name="Registrar" type="submit" value="Registrar" /></td>
            <td align="left"><input name="Cancelar" type="submit" value="Cancelar" onClick="self.close();"/></td>
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
	$tarifa    = $_POST['tarifa'];
	$porciento = $_POST['porciento'];
	$cant      = $_POST['cant'];
 
 //verificar que los datos esten llenos
	if($tarifa  == '' || $cant  == '' || $porciento  == ''){
		echo "<script> alert ('Faltan campos por completar...');</script>";
	}
	else{
		
		//verificar si ya existe receta para ese producto
		$sentencia = "SELECT COUNT(*) as cantidad FROM tblreceta WHERE item='".$row1['id_item']."'";
		$consulta  = mysql_query($sentencia,$link) or die ("Error verificando la existencia de la receta");
		$fila      = mysql_fetch_array($consulta);
		 
		if($fila['cantidad'] == 0){
		 	$sql="INSERT INTO tblreceta (`item`,`tarif_code`,`porciento`,`cantflores`) VALUES ('".$row1['id_item']."','$tarifa','$porciento','$cant')";		 
			$insertado= mysql_query($sql,$link);
			if($insertado){
				echo("<script> alert ('Receta creada correctamente');
			   					window.close();					   
								window.opener.document.location='crearProductos.php';
				</script>");
				}
				else{
					echo("<script> alert (".mysql_error().");</script>");
				}	 
		}
	 	else{
			$sql="UPDATE tblreceta SET tarif_code = '".$tarifa."',porciento = '".$porciento."', cantflores= '".$cant."' WHERE item='".$row1['id_item']."'";		 
			$insertado= mysql_query($sql,$link);
			if($insertado){
				echo("<script> alert ('Receta modificada correctamente');
							   window.close();					   
							   window.opener.document.location='crearProductos.php';
					 </script>");
			}
			else{
				echo("<script> alert (".mysql_error().");</script>");
			}		 		 
		}
	}
}

/*if(isset($_POST["Cancelar"])){  
	 echo("<script>		   window.close();					   
					   window.opener.document.location='crearProductos.php';
			 </script>");
} */ 
?>
</body>
</html>