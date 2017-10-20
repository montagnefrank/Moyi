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
  <title>Nuevo Usuario</title>
  <link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" media="all" href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
  <script type="text/javascript" src="../js/calendar.js"></script>
  <script type="text/javascript" src="../js/calendar-en.js"></script>
  <script type="text/javascript" src="../js/calendar-setup.js"></script>
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
/*
$(function() {

$("#finca").on('change', function() {
         var finca = $(this).find(":selected").text();
         var rol = $('#rol').find(":selected").text();
         var esFinca = rol.search("Finca");
         var envio1 = 'true';
         
         if(esFinca!=-1&&finca!="TODAS"){
          $('#nombre').val(finca);
         }
         else{
          $('#nombre').val("");
         }
});

$("#rol").on('change', function() {
        var finca2 = $('#finca').find(":selected").text();
        var rol2 = $('#rol').find(":selected").text();
        var esFinca2 = rol2.search("Finca");
        var envio = 'true';

         if(esFinca2!=-1&&finca2!="TODAS"){
          $('#nombre').val(finca2);

         }
         else{
          $('#nombre').val("");
         }
});

    $("#form1").submit(function(){
      if((envio1==='true')&&(envio==='true')){
      alert("a");
         return true;
      }
      else if($('#nombre').val()==""){
        return false;
      }

        });

 });

*/

</script>

<script>

  jQuery.validator.addMethod("noSpace", function (value, element) { 
    return value.indexOf(" ") < 0 && value != ""; 
  }, "");

  jQuery.validator.addMethod("alphanumeric", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
  }, "");

  jQuery.validator.addMethod("barcode", function (value, element) {
    return this.optional(element) || /[a-z0-9 -()+]+$/.test(value);
  }, "");

  jQuery.validator.addMethod("checkpass", function (value, element) {
    return this.optional(element) || /(([\W]+[\d]+[A-Za-z]+)|([\W]+[A-Za-z]+[\d]+$)|([A-Za-z]+[\d]+[\W]+$)|([A-Za-z]+[\W]+[\d]+$)|([\d]+[\W]+[A-Za-z]+$)|([\d]+[A-Za-z]+[\W]+$))/.test(value);
  }, "");

// When the browser is ready...
$(function() {
      // Setup form validation on the #register-form element
      $("#form1").validate({
        debug: true,
        errorClass: "my-error-class",
        validClass: "my-valid-class",
        //onsubmit: false,
    
        // Specify the validation rules
        rules: {
              usuario: {
                required: true,
                noSpace: true,
                minlength: 3,
                maxlength: 10,
                alphanumeric: true
              },
              nombre: {
                required: true,
                //noSpace: true,
                maxlength: 50,
              },
              contrasenna: {
                required: true,
                checkpass: true,
                minlength: 4,
                maxlength: 20,
              },
        },    
        // Specify the validation error messages
        messages: {
              usuario: {
                required: "Campo requerido",
                minlength: "3 caracteres como mínimo",
                maxlength: "10 caracteres como máximo",
                noSpace: "No puede dejar espacios en blanco",
                alphanumeric: "Solamente letras y números"
              },
              nombre: {
                required: "Campo requerido",
                //noSpace: "No puede dejar espacios en blanco",
                maxlength: "20 caracteres como máximo",
              },
              contrasenna: {
                required: "Campo requerido",
                checkpass: "Al menos una letra, un número y caracter especial",
                maxlength: "20 caracteres como máximo",
                minlength: "Al menos 4 caracteres"
              },
        },

        submitHandler: function(form) {
            form.submit();
        }

      });

      /*$("#Registrar").click(function(){
        alert("Has pulsado el enlace...");
          $("#form1").[0].submit();
          return true;
      });*/
     
   });

  </script>



</head>
<body>
<form id="form1" name="form1" method="post" novalidate="novalidate" action="" .error { color:red}>
<table width="350" border="0" align="center">
     <tr>
    <td width="300" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Registrar nuevo usuario</font></strong></td>
  </tr>
</table>
<table width="350" border="0" align="center">
 <tr height="20"><td></td></tr>
     <tr>
     	<td>
        <table border="0" align="center">
        	
            <tr>
            	<td><strong>Usuario:</strong></td>
                <td><input type="text" id="usuario" name="usuario" value="" autofocus="autofocus" autocomplete="off"/>
                <strong>*</strong></td>
            </tr>
            <tr>
            	<td><strong>Contraseña:</strong></td>
                <td><input type="password" id="contrasenna" name="contrasenna" value="" autocomplete="off"/>
                <strong>*</strong></td>
            </tr>

            <tr>
            	<td><strong>Rol:</strong></td>
                <td><select type="text" name="rol" id="rol">
                      <?php 
					  //Consulto la bd para obtener solo los id de item existentes
					  $sql   = "SELECT id_rol, cpdescripcion FROM tblrol";
					  $query = mysqli_query($link,$sql);
					  	//Recorrer los iteme para mostrar
						while($row1 = mysqli_fetch_array($query)){
									echo '<option value="'.$row1["id_rol"].'">'.$row1["cpdescripcion"].'</option>'; 
								}
					  ?>                       
                    </select>
                <strong>*</strong></td>
            </tr>
                    <tr>
            	<td width="82"><strong>Nombre de Finca:</strong></td>
                <td width="237"><select type="text" name="finca" id="finca">
                      <?php 
					  //Consulto la bd para obtener solo los id de item existentes
					  $sql   = "SELECT nombre FROM tblfinca order by nombre";
					  $query = mysqli_query($link,$sql);
					  	//Recorrer los iteme para mostrar
						echo '<option value="TODAS" selected="selected">TODAS</option>'; 
						while($row1 = mysqli_fetch_array($query)){
									echo '<option value="'.$row1["nombre"].'">'.$row1["nombre"].'</option>'; 
								}
					  ?>                       
                    </select>
                <strong>*</strong></td>
            </tr>
            <tr>
              <td><strong>Nombre:</strong></td>
                <td><input type="text" id="nombre" name="nombre" value="" autocomplete="off"/>
                </td>
            </tr>
            <tr>
            <td align="right"><input id="Registrar" name="Registrar" type="submit" value="Registrar" /></td>
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
 	 $user    = $_POST['usuario'];
	 $nombre  = $_POST['nombre'];
	 $cont    = $_POST['contrasenna'];
	 $rol     = $_POST['rol'];	 
	 $finca   = $_POST['finca'];	
            
         //verifico que no existe un usuario igual 
         $sql="SELECT * FROM tblusuario WHERE cpuser='".$user."'";
         $res= mysqli_query($link,$sql);
         if(mysqli_num_rows($res)==0)
         {
             $sql="INSERT INTO tblusuario (`cpuser`, `cpnombre`, `cppassword`,`idrol_user`,`finca`,`theme`,`mainpanel`,`wg_ord`,`wg_pvo`,`wg_par`,`wg_ecf`,`wg_reh`,`wg_lpb`) VALUES ('$user','$nombre','$cont','$rol','$finca','blue','mainpanel.php','0','0','0','0','0','0')";
             echo "HOLAMUNDO INSERTADO ".$sql;
            $insertado= mysqli_query($link,$sql);
            if($insertado){
                   echo("<script> alert ('Usuario registrado correctamente');
                                  window.close();					   
                                              window.opener.document.location='usuarios.php';
                        </script>");
            }else{
		 echo("<script> alert (".mysqli_error($link).");</script>");
            }
        }
        else
        {
          echo("<script> alert ('Ya existe un usuario registrado con ese nombre');</script>");  
        }
         
	 
   }
    
   /*if(isset($_POST["Cancelar"])){  
 	 echo("<script> window.close()</script>");
   } */ 
  ?>
</body>
</html>