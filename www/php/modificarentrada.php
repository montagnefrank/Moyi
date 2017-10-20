<?php 
    session_start();
	include ("conectarSQL.php");
    include ("conexion.php");
	
	$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());
	
	//recojo el id de la orden a modificar
	$id = $_GET['codigo'];	
	echo "El codigo es ".$codigo;
	
	$sql = "SELECT * FROM tblcoldroom WHERE codigo =".$id."";
	$query = mysql_query($sql,$link);
	$row   = mysql_fetch_array($query);
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
<table width="1024" border="0" align="center">
     <tr>
    <td width="747" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Modificar Datos de la caja <?php echo $row['codigo']?></font></strong></td>
  </tr>
</table>
<table width="1024" border="0" align="center">
 <tr height="20"><td></td></tr>
     <tr>
     	<td>
        <table border="0" align="center">
            <tr>
            	<td><strong>Finca:</strong></td>
                <td><input type="text" id="finca" name="finca" value="<?php echo $row['finca'];?>" autofocus="autofocus"/></td>
            </tr>
            <tr>
            	<td><strong>Producto:</strong></td>
                <td><input type="text" id="item" name="item" value="<?php echo $row['item'];?>" /></td>
            </tr>
            <tr>
            	<td><strong>Código:</strong></td>
                <td><input type="text" id="codigo" name="codigo" value="<?php echo $row['codigo'];?>"/><script type="text/javascript">
        function catcalc(cal) {
            var date = cal.date;
            var time = date.getTime()
            // use the _other_ field
            var field = document.getElementById("f_calcdate");
            if (field == cal.params.inputField) {
                field = document.getElementById("fecha");
                time -= Date.WEEK; // substract one week
            } else {
                time += Date.WEEK; // add one week
            }
            var date2 = new Date(time);
            field.value = date2.print("%Y-%m-%d");
        }
        Calendar.setup({
            inputField     :    "fecha",   // id of the input field
            ifFormat       :    "%Y-%m-%d ",       // format of the input field
            showsTime      :    false,
            timeFormat     :    "24",
            onUpdate       :    catcalc
        });
    
                  </script>
                </td>
            </tr>
            <tr>
            <td><input name="Modificar" type="submit" value="Modificar" /></td>
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
   if(isset($_POST["Modificar"])){  
	 $cargamento    = $_POST['cargamento'];
	 $cantidad      = $_POST['cantidad'];
	 $item          = $_POST['item'];
	 $finca			= $_POST['finca'];
	 $fecha			= $_POST['fecha'];
	 
	 $sql="UPDATE tblcoldroom set cargamento ='".$cargamento ."' , cantidad='".$cantidad."' , item ='".$item ."', finca	='".$finca."', fecha='".$fecha."' where id_cold_room='".$id."'";

	 $modificado= mysql_query($sql,$link);
  	 if($modificado){
	 	echo("<script> alert ('Entrada modificada correctamente');
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