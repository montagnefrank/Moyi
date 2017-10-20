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

	  //recogiendo el id de la orden a activar o cancelar
	  $codi = $_GET["codigo"];
	  $dir  = $_GET["dir"];
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Activar/Cancelar Orden</title>
<link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="300" border="0" align="center" class="alert">
    <tr>
      <td height="30" colspan="2" align="center"><strong><span id="result_box" lang="en" xml:lang="en">Esta seguro de eliminar el DAE de esta finca?</span></strong></td>
    </tr>
    <tr>
      <td align="center"><input name="si" type="submit" class="btn-danger" id="si" value="SI" /></td>
      <td align="center"><input name="no" type="submit" class="alert-info" id="no" value="NO" /></td>
    </tr>
  </table>
</form>
<?php 
  if(isset($_POST["si"])){
  	$sql = "UPDATE tbldae SET url='eliminado' WHERE id_dae = '".$codi."'";
	$query = mysqli_query($link, $sql);
	
	if($query){
		
		//Eliminar el archivo de la carpeta 
		unlink($dir.$file); 
		
		 echo("<script>
		           alert ('Dae eliminado correctamente.');
				   window.close();
				   window.opener.document.location='verdae.php';
				   </script>");
		}else{
			echo("<script>
		           alert (Error eliminando el DAE de esta finca');
				   window.close();
				   window.opener.document.location='verdae.php';
				   </script>");			
			}  
  }
  
  if(isset($_POST["no"])){  
     //$_SESSION['PoNbr']= $PoNbr;
 	 echo("<script>window.close();
				   window.opener.document.location='verdae.php';
				   </script>");
   }  
  
  ?>
</body>
</html>