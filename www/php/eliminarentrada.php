<?php 
    session_start();
	include ("conectarSQL.php");
    include ("conexion.php");
	include ("seguridad.php");
	
	$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());
    $codi=$_GET["codigo"];
	echo "codigo".$codi;
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mensaje</title>
<link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="300" border="0" align="center" class="alert">
    <tr>
      <td height="30" colspan="2" align="center"><strong><span id="result_box" lang="en" xml:lang="en">Esta seguro de eliminar los datos de la entrada</span>?</strong></td>
    </tr>
    <tr>
      <td width="255" align="center"><input name="si" type="submit" class="btn-danger" id="si" value="SI" /></td>
      <td width="314" align="center"><input name="no" type="submit" class="alert-info" id="no" value="NO" /></td>
    </tr>
  </table>
</form>
 <?php 
  if(isset($_POST["si"])){

  $sql="DELETE FROM tblcoldroom WHERE codigo='".$codi."'";
  $eliminado= mysql_query($sql,$link);
  	if($eliminado){
		echo("<script> alert ('Entrada eliminada correctamente');
		               window.close();					   
					   window.opener.document.location='reg_entrada.php';
		     </script>");
	 }else{
		 echo("<script> alert (".mysql_error().");</script>");
		 } 
  }
  
   if(isset($_POST["no"])){  
  echo("<script> window.close()</script>");
  }
  
  ?>
</body>
</html>