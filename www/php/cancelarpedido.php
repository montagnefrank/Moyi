<?php 
    session_start();
	include ("conectarSQL.php");
    include ("conexion.php");
	include ("seguridad.php");
	
	$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE) or die('No pudo conectarse : '. mysql_error());

	  //recogiendo el id de la orden a activar o cancelar
	  $codi=$_GET["codigo"];
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cancelar pedido</title>
<link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="300" border="0" align="center" class="alert">
    <tr>
      <td height="30" colspan="2" align="center"><strong><span id="result_box" lang="en" xml:lang="en">Esta seguro de cancelar este pedido</span></strong></td>
    </tr>
    <tr>
      <td align="center"><input name="si" type="submit" class="btn-danger" id="si" value="SI" /></td>
      <td align="center"><input name="no" type="submit" class="alert-info" id="no" value="NO" /></td>
    </tr>
  </table>
</form>
<?php 
  if(isset($_POST["si"])){ 
	        /**** Estado 5 es cuando el pedidio es cancelado por error y con comentario por error***/
			$sql="UPDATE tbletiquetasxfinca set estado='5', comentario='Error en la creacion' where nropedido='".$codi."'";
			$eliminado= mysql_query($sql,$link);
			if($eliminado){
				echo("<script> alert ('Pedido cancelado correctamente');
							   window.close();
							   window.opener.document.location='asig_etiquetas.php';
					 </script>");	
			}else{
				 echo("<script> alert (".mysql_error().");</script>");
				 }
  }
  if(isset($_POST["no"])){  
     //$_SESSION['PoNbr']= $PoNbr;
 	 echo("<script>window.close();
				   window.opener.document.location='asig_etiquetas.php';
				   </script>");
   }  
  
  ?>
</body>
</html>