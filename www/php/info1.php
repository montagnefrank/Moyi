<?php 
    session_start();
	include ("seguridad.php");
    $mensaje = $_GET['msg'];
	$id = $_GET['id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Information</title>
<link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="372" border="0" align="center" class="alert">
    <tr>
      <td height="30" colspan="2" align="center"><p><strong><span id="result_box" lang="en" xml:lang="en"> 
      <?php
	  if ($id == 1){ // Si es error 
      	echo '<img src="../images/error.jpg" width="74" height="69" alt="d" />';
			echo '<embed src="../sounds/error.wav" autostart="true" loop="true" hidden="true"></embed>';
	  }
      ?>
      </span></strong></p>
      <p><strong><span lang="en" xml:lang="en"><?php echo $mensaje; ?></span></strong></p></td>
    </tr>
    <tr>
      <td width="366" align="center"><input name="si" type="submit" class="btn-danger" id="si" value="OK" autofocus="autofocus"/></td>
    </tr>
  </table>
</form>
  <?php 
  if(isset($_POST["si"]))
  echo("<script>window.close();
                window.opener.document.location='reg_entrada.php';
        </script>");
  ?>
</body>
</html>