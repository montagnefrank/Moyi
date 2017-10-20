<?php 
    session_start();
	include ("conectarSQL.php");
    include ("conexion.php");
	include ("seguridad.php");
	
	$user     =  $_SESSION["login"];
	$passwd   =  $_SESSION["passwd"];
	$bd       =  $_SESSION["bd"];
	$rol      =  $_SESSION["rol"];

	$link =  conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mensaje</title>
<link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" media="all" href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
  <script type="text/javascript" src="../js/calendar.js"></script>
  <script type="text/javascript" src="../js/calendar-en.js"></script>
<script type="text/javascript" src="../js/calendar-setup.js"></script>
</head>
<body>
<form id="form1" name="form1" method="post">
<table width="500" border="0" align="center">
     <tr>
    <td width="500" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Registrar nueva entrada</font></strong></td>
  </tr>
</table>
<table width="500" border="0" align="center">
 <tr height="20"><td></td></tr>
     <tr>
     	<td>
        <table border="0" align="center">
            <tr>
            	<td><strong>Cargamento:</strong></td>
                <td><input type="text" id="cargamento" name="cargamento" value="" autofocus="autofocus"/>
                <strong>*</strong></td>
            </tr>
            <tr>
            	<td><strong>Cantidad:</strong></td>
                <td><input type="text" id="cantidad" name="cantidad" value="" />
                <strong>*</strong></td>
            </tr>
            <tr>
            	<td><strong>Item:</strong></td>
                <td><select type="text" name="item" id="satdel">
                      <?php 
					  //Consulto la bd para obtener solo los id de item existentes
					  $sql   = "SELECT id_item FROM tblproductos";
					  $query = mysql_query($sql,$link);
					  	//Recorrer los iteme para mostrar
						while($row1 = mysql_fetch_array($query)){
									echo '<option>'.$row1["id_item"].'</option>'; 
								}
					  ?>                       
                    </select>
                <strong>*</strong></td>
            </tr>
            <tr>
            	<td><strong>Finca</strong>:</td>
                <td>
                <select type="text" name="finca" id="finca">
                      <?php 
					  //Consulto la bd para obtener solo los id de item existentes
					  $sql   = "SELECT nombre FROM tblfinca";
					  $query = mysql_query($sql,$link);
					  	//Recorrer los iteme para mostrar
						while($row1 = mysql_fetch_array($query)){
							echo '<option>'.$row1["nombre"].'</option>'; 
								}
					  ?>                       
                    </select>
                <strong>*</strong></td>
            </tr>
            <tr>
            	<td><strong>Fecha:</strong></td>
                <td><input type="text" id="fecha" name="fecha" value="<?php echo date('Y-m-d');?>" readonly="readonly" />
                <strong>*</strong>
                </td>
            </tr>
            <tr>
            <td align="right"><input name="Registrar" type="submit" value="Registrar" /></td>
            <td><input name="Cancelar" type="submit" value="Cancelar"/></td>
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
	 $cargamento    = $_POST['cargamento'];
	 $cantidad      = $_POST['cantidad'];
	 $item          = $_POST['item'];
	 $finca			= $_POST['finca'];
	 $fecha			= $_POST['fecha'];
	  
	 $sql="INSERT INTO tblcoldroom (`cargamento`, `cantidad_entrada`, `item`,`finca`,`fecha`, `cantidad_salida`) VALUES ('$cargamento','$cantidad','$item','$finca','$fecha','$cantidad')";

	 $insertado= mysql_query($sql,$link);
  	 if($insertado){
	 	echo("<script> alert ('Entrada registrada correctamente');
		               window.close();					   
					   window.opener.document.location='reg_entrada.php';
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