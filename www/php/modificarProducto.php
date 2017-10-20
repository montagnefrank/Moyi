<?php 
session_start();
include ("conectarSQL.php");
include ("conexion.php");
	
$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());

//recojo el id de la orden a modificar
$id = $_GET['codigo'];	

$sql = "SELECT * FROM tblproductos WHERE item =".$id.";";
$query = mysql_query($sql,$link);
$row   = mysql_fetch_array($query);
$id = $row['item'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Modificar producto</title>
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

    jQuery.validator.addMethod("noSpace", function (value, element) { 
      return value.indexOf(" ") < 0 && value != ""; 
    }, "No space please and don't leave it empty");

    jQuery.validator.addMethod("alphanumeric", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
    }, "Enter a valid item.");

    jQuery.validator.addMethod("alphanumeric2", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\-\s\.\,\_\:\+\*\/]+$/.test(value);
    }, "Enter a valid item.");

    jQuery.validator.addMethod("barcode", function (value, element) {
      return this.optional(element) || /[a-z0-9 -()+]+$/.test(value);
    }, "Enter a valid bar code.");
  
      // When the browser is ready...
      $(function() {
          // Setup form validation on the #register-form element
          $("#form1").validate({
              errorClass: "my-error-class",
              validClass: "my-valid-class",
          
              // Specify the validation rules
              rules: {
                  item: {
                    required: true,
                    noSpace: true,
                    maxlength: 10,
                    alphanumeric: true
                  },
                  dclvalue: "required",
                  width: "required",
                  wheigthKg: "required",
                  cpservicio: "required",
                  gen_desc: "required",
                  finca: "required",
                  descripcion: {
                    required: true,
                    alphanumeric2: true
                  },
                  length: "required",
                  heigth: "required",
                  origen: "required",
                  cptipo_pack: "required"
              },
              
              // Specify the validation error messages
              messages: {
                  item: {
                    required: "Por favor inserte el Producto",
                    maxlength: "10 caracteres como máximo",
                    noSpace: "No puede dejar espacios en blanco",
                    alphanumeric: "Solamente letras y números"
                  },
                  dclvalue: "Por favor inserte el ValorDCL",
                  width: "Por favor inserte el Ancho",
                  wheigthKg:"Por favor inserte el Peso",
                  cpservicio:"Por favor inserte el Servicio",
                  gen_desc:"Por favor inserte una descripción general",
                  finca:"Por favor seleccione una Finca",
                  descripcion: {
                    required: "Inserte una Descripción",
                    alphanumeric2: "Caracter no válido"
                  },
                  length: "Por favor inserte un Largo",
                  heigth: "Por favor inserte un Alto",
                  origen:"Por favor seleccione un Origen",
                  cptipo_pack: "Por favor inserte el Tipo de Pack"
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
<table width="700" border="0" align="center">
     <tr>
    <td width="700" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Modificar Datos del producto</font> <font color="#FF0000"><?php echo $row['id_item']?></font></strong></td>
  </tr>
</table>
<table width="700" border="0" align="center">
 <tr height="20"><td></td></tr>
     <tr>
     	<td>
        <table border="0" align="center">
            <tr>
            <td align="right"><strong>Producto:</strong></td>
                <td><p>&nbsp;
                  </p>
                  <p>
                    <input type="text" id="item" name="item" value="<?php echo $row['id_item'];?>" />*<br />
                </p></td>
                <td align="right"><strong>Desc. Prod:</strong></td>
                <td><p>&nbsp;
                    </p>
                  <p>
                  <textarea type="text" id="descripcion" name="descripcion" value="" style="resize:none;"><?php echo $row['prod_descripcion'];?></textarea>*</p></td>
            </tr>
            <tr>            	
                <td align="right"><strong>Tipo de Caja:</strong></td>
                <td><input type="text" id="boxtype" name="boxtype" value="<?php echo $row['boxtype'];?>" /></td>
                <td align="right"><strong>Cant:</strong></td>
                <td><input type="text" id="pack" name="pack" value="<?php echo $row['pack'];?>" /></td>
            </tr>
            <tr>
            <td align="right"><strong>Valor Dcl:</strong></td>
                <td><input type="text" id="dclvalue" name="dclvalue" value="<?php echo $row['dclvalue'];?>" />*</td>
            	<td align="right"><strong>Largo:</strong></td>
                <td><input type="text" id="length" name="length" value="<?php echo $row['length'];?>" />*</td>
            </tr>
            <tr>
            	<td align="right"><strong>Ancho:</strong></td>
                <td><input type="text" id="width" name="width" value="<?php echo $row['width'];?>" />*</td>
                <td align="right"><strong>Alto:</strong></td>
                <td><input type="text" id="heigth" name="heigth" value="<?php echo $row['heigth'];?>" />*</td>
            </tr>
            <tr>
            	<td align="right"><strong>Peso Kg:</strong></td>
                <td><input type="text" id="wheigthKg" name="wheigthKg" value="<?php echo $row['wheigthKg'];?>" />*</td>
                <td align="right"><strong>Origen:</strong></td>
               <!-- <td><select type="text" id="origen" name="origen" value="" />-->



                <td><select name="origen" id="origen" >
                      <option selected="selected"><?php echo $row['origen']; ?></option>
                    <?php 
                      //Consulto la bd para obtener las ciudades y paises de origen
                      $sql1   = "SELECT * FROM tblciudad_origen";
                      $query1 = mysql_query($sql1,$link);
                        //Recorrer las ciudades para mostrar
                      echo '<option value=""></option>'; 
                      while($row2 = mysql_fetch_array($query1)){
                            echo '<option value="'.$row2["codciudad"].'-'.$row2["pais_origen"].'">'.$row2["codciudad"].'-'.$row2["pais_origen"].'</option>'; 
                      }
                    ?>  
                    </select>*</td>

            </tr>
            <tr>
            	<td align="right"><strong>Servicio:</strong></td>
                <td><input type="text" id="cpservicio" name="cpservicio" value="<?php echo $row['cpservicio'];?>" />*</td>
                <td align="right"><strong>Tipo Pack:</strong></td>
                <td><input type="text" id="cptipo_pack" name="cptipo_pack" value="<?php echo $row['cptipo_pack'];?>" />*</td>
            </tr>
            <tr>
            	<td align="right"><strong>Desc. Gen. :</strong></td>
                <td><input type="text" id="gen_desc" name="gen_desc" value="<?php echo $row['gen_desc'];?>" />*</td>
                <td align="right"><strong>Receta:</strong></td>
                <td><input type="text" id="receta" name="receta" value="<?php echo $row['receta'];?>" /></td>
            </tr>
            <tr>
            	<td align="right"><strong>Finca:</strong></td>
                <td><select type="text" id="finca" name="finca">
                  <?PHP
        					//Leyendo las fincas
        					$sqlFinca   = "SELECT nombre FROM tblfinca order by nombre";
        					$queryFinca = mysql_query($sqlFinca,$link) or die ("Error seleccionando las fincas");
        					echo '<option value="'.$row['finca'].'" selected="selected">'.$row['finca'].'</option>';
        					while ($filaFinca = mysql_fetch_array($queryFinca)){
        						if($row['finca'] != $filaFinca['nombre'])
        						echo '<option value="'.$filaFinca['nombre'].'">'.$filaFinca['nombre'].'</option>';
        					}
        					?>
                </select>*</td>
            </tr>
             <tr>
            <td>&nbsp;</td>
            <td align="right"><input name="Modificar" type="submit" value="Modificar" /></td>
            <td><input name="Cancelar" type="submit" value="Cancelar" onClick="self.close();"/></td>
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
	 $item       = $_POST['item'];
	 $desc       = addslashes($_POST['descripcion']);
	 $length     = $_POST['length'];
	 $width      = $_POST['width'];
	 $heigth     = $_POST['heigth'];
	 $wheigthKg  = $_POST['wheigthKg'];
	 $dclvalue  = $_POST['dclvalue'];
	 $origen  = $_POST['origen'];
	 $cpservicio  = $_POST['cpservicio'];
	 $cptipo_pack  = $_POST['cptipo_pack'];
	 $gen_desc  = addslashes($_POST['gen_desc']);
	 $tipocaja  = $_POST['boxtype'];
	 $pack  = $_POST['pack'];
	 $receta  = $_POST['receta'];
	 $finca  = $_POST['finca'];

	 $sql="UPDATE tblproductos set id_item='".$item."' , prod_descripcion='".$desc."' , length='".$length."', width='".$width."',heigth='".$heigth."', wheigthKg='".$wheigthKg."', dclvalue='".$dclvalue."', origen='".$origen."', cpservicio='".$cpservicio."', cptipo_pack='".$cptipo_pack."', gen_desc='".$gen_desc."',receta = '".$receta."', boxtype='".$tipocaja."', pack='".$pack."', finca = '".$finca."'  where item='".$id."'";
	 $modificado= mysql_query($sql,$link);
  	 if($modificado){
	 	echo("<script> alert ('Producto modificado correctamente');
		               window.close();					   
					   window.opener.document.location='crearProductos.php';
		     </script>");
	 }else{
		 echo("<script> alert (".mysql_error().");</script>");
		 }
   }
    
   if(isset($_POST["Cancelar"])){  
 	 echo("<script> window.close()</script>");
   }  
  ?>
</body>
</html>