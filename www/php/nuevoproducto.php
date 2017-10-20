<?php 
session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");
	
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
  <title>Register new Item</title>
  <link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
  <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
  <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
  <script src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
  <script src="../js/formato.js"></script>
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
              cptipo_pack: "required",
              Pack: {
                required: true,
                number: true
              }
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
              gen_desc:"Inserte una Descripción general",
              finca:"Por favor seleccione una Finca",
              descripcion: {
                required: "Inserte una Descripción",
                alphanumeric2: "Caracter no válido"
              },
              length: "Por favor inserte un Largo",
              heigth: "Por favor inserte un Alto",
              origen:"Por favor seleccione un Origen",
              cptipo_pack: "Por favor inserte el Tipo de Pack",
              Pack: {
                required: "Campo Requerido",
                number: "Solo números"
              }

        },

        submitHandler: function(form) {
            form.submit();
        }

        /*$('#Cancelar').delegate('','click change',function(){
            window.location = "gestionarordenes.php";
        return false;
        });*/
      });

      //$("#item").mask("**********");

    });
  </script>
  
</head>
<body>
<form id="form1" name="form1" method="post" novalidate="novalidate" action="" .error { color:red}>
<table width="700" border="0" align="center">
     <tr>
    <!-- <td width="600" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Registrar item</font></strong></td> -->
    <td align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Registrar item</font></strong></td>
  </tr>
</table>
<table width="700" border="0" align="center">
 <tr height="20"><td></td></tr>
     <tr>
     	<td>
        <table width="700" border="0" align="center">
        	<tr>
            	<td width="113" align="right"><strong>Item:</strong></td>
                <td width="243">
                  <p>&nbsp;</p>
                  <p>
                    <!-- <input type="text" id="item" name="item" value="" size="10" onkeypress="return onlyNumbersDano(event)"/>*<br /> -->
                    <input type="text" id="item" name="item" value="" size="10" placeholder="10 caracteres máximo"/>*<br />
                 
                  </p>
                </td>
                <td width="101" align="right"><strong>Desc. Prod:</strong></td>
                <td width="175"><p>&nbsp;
                    </p>
                  <p>
                  <textarea type="text" id="descripcion" name="descripcion" value="" style="resize:none;"></textarea>*</p></td>
            </tr>
            <tr>            	
                <td align="right"><strong>Box Type:</strong></td>
                <td><input type="text" id="boxtype" name="boxtype" value="" /></td>
                <td align="right"><strong>Pack:</strong></td>
                <td><input type="text" id="pack" name="pack" value="" /></td>
            </tr>
            <tr>
            <td align="right"><strong>Dclvalue:</strong></td>
                <td>
                  <input type="text" id="dclvalue" name="dclvalue" value="" />*
                  </td>
            	<td align="right"><strong>Largo:</strong></td>
                <td><input type="text" id="length" name="length" value="" />*</td>
            </tr>
            <tr>
            	<td align="right"><strong>Ancho:</strong></td>
                <td><input type="text" placeholder="Ancho" id="width" name="width" value="" />*</td>
                <td align="right"><strong>Alto:</strong></td>
                <td><input type="text" id="heigth" name="heigth" value="" />*</td>
            </tr>
            <tr>
            	<td align="right"><strong>Peso (Kg):</strong></td>
                <td><input type="text" id="wheigthKg" name="wheigthKg" value="" />*</td>
                <td align="right"><strong>Origen:</strong></td>
               <!-- <td><select type="text" id="origen" name="origen" value="" />-->
                <td><select name="origen">
                    <!--  <option>GYE-ECUADOR</option>
                      <option>UIO-ECUADOR</option> 
                      <option>BOG-COLOMBIA</option>
                      <option>MED-COLOMBIA</option>     -->
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
                <td><input type="text" id="cpservicio" name="cpservicio" value="" />*</td>
                <td align="right"><strong>Pack Type:</strong></td>
                <td><input type="text" id="cptipo_pack" name="cptipo_pack" value="" />*</td>
            </tr>
             <tr>
            	
            </tr>
            <tr>
            	<td align="right"><strong>Descripción General:</strong></td>
                <td><input type="text" id="gen_desc" name="gen_desc" value="" />*</td>
                <td align="right"><strong>Receta:</strong></td>
                <td><input type="text" id="receta" name="receta" value="" /></td>
            </tr>
            <tr>
            	<td align="right"><strong>Finca:</strong></td>
                <td><select type="text" id="finca" name="finca">
                	<?PHP
					//Leyendo las fincas
					$sqlFinca   = "SELECT nombre FROM tblfinca order by nombre";
					$queryFinca = mysql_query($sqlFinca,$link) or die ("Error seleccionando las fincas");
					while ($filaFinca = mysql_fetch_array($queryFinca)){
						echo '<option value="'.$filaFinca['nombre'].'">'.$filaFinca['nombre'].'</option>';
					}
					?>
                </select>*</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td align="right"><input name="Registrar" type="submit" value="Registrar" /></td>
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
if(isset($_POST["Registrar"])){  
 	 /*echo("<script> window.close()</script>");*/
	 $item         = strtoupper($_POST['item']);
	 $desc         = addslashes($_POST['descripcion']);
	 $length       = $_POST['length'];
	 $width        = $_POST['width'];
	 $heigth       = $_POST['heigth'];
	 $wheigthKg    = $_POST['wheigthKg'];
	 $dclvalue     = $_POST['dclvalue'];
	 $origen       = $_POST['origen'];
	 $cpservicio   = $_POST['cpservicio'];
	 $cptipo_pack  = $_POST['cptipo_pack'];
	 $gen_desc     = addslashes($_POST['gen_desc']);
	 $tipocaja     = $_POST['boxtype'];
	 $pack         = $_POST['pack'];
	 $receta       = $_POST['receta'];
	 $finca        = $_POST['finca'];

	//echo $item,$desc,$length,$width,$heigth,$wheigthKg,$dclvalue,$origen,$cpservicio,$cptipo_pack, $gen_desc;
	 
	 //verificar que los datos esten llenos
		if($item == '' || $desc == '' || $length == '' || $width == '' || $heigth  == '' || $wheigthKg == '' || $dclvalue == '' || $origen == '' || $cpservicio == '' || $cptipo_pack == '' || $gen_desc  == ''){
			  echo "<script> alert ('Faltan datos para insertar...');</script>";
		}else{

		 $sql="INSERT INTO tblproductos (`id_item`, `prod_descripcion`, `length`, `width`,`heigth`, `wheigthKg`, `dclvalue`, `origen`, `cpservicio`, `cptipo_pack`, `gen_desc`,`receta`,`boxtype`,`pack`,`finca`) VALUES ('$item','$desc','$length','$width','$heigth','$wheigthKg','$dclvalue','$origen','$cpservicio','$cptipo_pack','$gen_desc','$receta','$tipocaja','$pack','$finca')";		 
		 $insertado= mysql_query($sql,$link);
		 if($insertado){
			echo("<script> alert ('Producto insertado correctamente');
						   window.close();					   
						   window.opener.document.location='crearProductos.php';
				 </script>");
		 }else{
			 echo("<script> alert (".mysql_error().");</script>");
			 }
		}
}
    
   /*if(isset($_POST["Cancelar"])){  
 	 echo("<script> window.close()</script>");
   } */ 
?>
</body>
</html>