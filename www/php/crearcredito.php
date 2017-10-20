<?php 
    session_start();
	include ("conectarSQL.php");
    include ("conexion.php");
	include ("seguridad.php");
	
	$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE) or die('No pudo conectarse : '. mysql_error());

	  //recogiendo el id de la orden a pagar
	  $codi=$_GET["codigo"];
	  $sql= "SELECT * from tblcosto WHERE id_costo='".$codi."'"; 
	  $query= mysql_query($sql,$link) or die ("Error seleccionando los datos de la orden");
	  $row = mysql_fetch_array($query);
	  $costo = $row['costo'];
	  $Po    = $row['po'];
	  
	  //recogiendo el custom y el id de la orden
	  $sql= "SELECT id_orden_detalle, Custnumber from tbldetalle_orden WHERE Ponumber='".$Po."'"; 
	  $query= mysql_query($sql,$link) or die ("Error seleccionando los datos de la orden");
	  $row = mysql_fetch_array($query);
	  $cust = $row['Custnumber'];
	  $id_orden = $row['id_orden_detalle'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Crear Crédito</title>
<link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="300" border="0" align="center" class="alert">
    <tr>
      <td height="30" colspan="2" align="center"><strong><span id="result_box" lang="en" xml:lang="en">Está seguro de pagar esta órden?</span></strong></td>
    </tr>
    <tr>
      <td align="center"><input name="si" type="submit" class="btn-danger" id="si" value="SI" /></td>
      <td align="center"><input name="no" type="submit" class="alert-info" id="no" value="NO" /></td>
    </tr>
  </table>
</form>
<?php 
  if(isset($_POST["si"])){
	  		$fecha = date('Y-m-d');
	        //poner la orden como pagada en la tabla de costo
			$sql1 = "INSERT INTO tblcustom_services (`ponumber`,`custnumber`,`credito`,`reenvio`,`fecha`,`id_orden`,`razones`) VALUES ('$Po','$cust','$costo','No','$fecha','$id_orden','CONT')";
			$query1 = mysql_query($sql1,$link) or die ("Error ejecutando el pago");
			
			//Poner las ordenes como credito
			$a= "SELECT id_orden_detalle,unitprice from tbldetalle_orden WHERE Ponumber='".$Po."'"; 
		    $b= mysql_query($a,$link) or die ("Error seleccionando los datos de la orden");
			
		    while($c = mysql_fetch_array($b)){
	  			$sql2 = 'UPDATE tbldetalle_orden SET estado_orden = "Canceled", status = "Not Shipped", unitprice= '.-$c['unitprice'].' WHERE id_orden_detalle = '.$c['id_orden_detalle'].'';
				$query2 = mysql_query($sql2,$link) or die ("Error en la modificacion de la orden");
			}
			//Borrarla de los costos
			$sql3   = "DELETE FROM tblcosto WHERE po = '".$Po."'";
			$query3 = mysql_query($sql3,$link) or die ("Error en la elminacion del costo");
			
			echo("<script>
			       alert('El pago se realizo satisfactoriamente');
				   window.close();
				   window.opener.document.location='pagosycreditos.php';
				   </script>");

  }
  
  if(isset($_POST["no"])){  
 	 echo("<script>window.close();
				   window.opener.document.location='pagosycreditos.php';
				   </script>");
   }  
  
  ?>
</body>
</html>