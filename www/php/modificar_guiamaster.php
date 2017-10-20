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

$user = $_SESSION["login"];
$caja = $_SESSION['cajas'];


//$sql="Update tblcoldroom set guia_hija='".$guia ."' WHERE codigo='".$check[$i]."'";
//      $modificado= mysqli_query($link, $sql) or die("Error");



$sql_init = "select * FROM tblcoldroom WHERE codigo='" . $caja[0] . "'";
$modif = mysqli_query($link, $sql_init) or die("Error");
$row = mysqli_fetch_array($modif);

$guia_master = $row['guia_master'];
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Guia Master</title>
        <link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
        <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" media="all" href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
        <script type="text/javascript" src="../js/calendar.js"></script>
        <script type="text/javascript" src="../js/calendar-en.js"></script>
        <script type="text/javascript" src="../js/calendar-setup.js"></script>
        <style type="text/css">
            .my-error-class {
                color:red;
            }
            /*.my-valid-class {
                color:green;
            }*/
        </style>
        <script src="//code.jquery.com/jquery-1.9.1.js"></script>
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
        <script src="../js/formato.js"></script>
        <script>

            jQuery.validator.addMethod("noSpace", function (value, element) {
                return value.indexOf("_") < 0 && value != "";
            }, "No space please and don't leave it empty");

            // When the browser is ready...
            $(function () {


                /*
                 $("#codigo").keydown(function (e) {
                 // Allow: delete(46), "-" (109), backspace(8), tab(9), escape(27), enter(13) and 
                 if ($.inArray(e.keyCode, [46, 109, 8, 9, 27, 13]) !== -1 ||
                 // Allow: home, end, left, right, down, up
                 (e.keyCode >= 35 && e.keyCode <= 35) || e.keyCode >= 96 && e.keyCode <= 105)  {
                 // let it happen, don't do anything
                 return;
                 }
                 // Ensure that it is a number and stop the keypress
                 if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57))) {
                 e.preventDefault();
                 }
                 });
                 */

                // Setup form validation on the #register-form element
                $("#form1").validate({
                    errorClass: "my-error-class",
                    validClass: "my-valid-class",

                    // Specify the validation rules
                    rules: {
                        guia: {
                            required: true,
                            noSpace: true
                        }
                    },

                    // Specify the validation error messages
                    messages: {
                        guia: {
                            required: "Por favor inserte un código válido",
                            noSpace: "No puede dejar espacios en blanco"
                        }
                    },

                    submitHandler: function (form) {
                        form.submit();
                    }
                });

                $("#guia").mask("****");

                //$('[data-toggle="tooltip"]').tooltip();

            });

        </script>
    </head>
    <body>
        <form id="form1" name="form1" method="post" novalidate="novalidate" action="" .error { color:red}>
              <table width="300" border="0" align="center">
                <tr>
                    <td width="300" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Modificar guía master</font></strong></td>
                </tr>
            </table>
            <table width="300" border="0" align="center">
                <tr height="20"><td></td></tr>
                <tr>
                    <td>
                        <table border="0" align="center" width="300">
                            <tr>
                                <td><strong>GUIA MASTER:</strong></td>
                                <td><input type="text" id="guia" name="guia" value="<?php echo $guia_master; ?>" autofocus="autofocus"/>*</td>
                            </tr>
                            <tr>
                                <td align="right"><input name="Registrar" id="Registrar" type="submit" value="Modificar" /></td>
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
if (isset($_POST["Registrar"])) {
    $guia = $_POST['guia'];
    $check = $_SESSION['cajas'];

    for ($i = 0; $i < count($check); $i++) {
//		 $sql   = "SELECT guia_hija FROM tblcoldroom WHERE codigo='".$check[$i]."'";
//                 echo $sql;
//		 $query = mysqli_query($link, $sql);
//		 $row   = mysqli_fetch_array($query);
//                 
//   		 if($row[0]== '0'){  
        $sql = "Update tblcoldroom set guia_master='" . $guia . "' WHERE codigo='" . $check[$i] . "'";
        $modificado = mysqli_query($link, $sql) or die("Error");
//		 }else{
//			// echo("<script> alert ('Ya existen guias asignadas en la selección de guias hijas, revise por favor!');</script>");
//			 }
    }
    if ($modificado) {
        echo("<script>
			       alert('Guias asignadas correctamente');
  				   window.close();
  				   window.opener.document.location='modificar_guia.php';
				   </script>");
    }
}


/* if(isset($_POST["Cancelar"])){  
  echo("<script>window.close();
  window.opener.document.location='asig_guia.php';
  </script>");
  } */
?>
    </body>
</html>