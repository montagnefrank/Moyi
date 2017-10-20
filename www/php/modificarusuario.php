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
	//recojo el id de la orden a modificar
	$id = $_GET['codigo'];	

	$sql = "select * FROM tblusuario WHERE id_usuario = '".$id."';";
	$query = mysqli_query($link, $sql);
	$row   = mysqli_fetch_array($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Modificar usuario</title>
<link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" media="all" href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
  <script type="text/javascript" src="../js/calendar.js"></script>
  <script type="text/javascript" src="../js/calendar-en.js"></script>
<script type="text/javascript" src="../js/calendar-setup.js"></script>
</head>
<body>
<form id="form1" name="form1" method="post">
<table width="350" border="0" align="center">
     <tr>
    <td width="350" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Modificar datos del  usuario </font><font color="#FF0000"><?php echo $row['cpuser'];?></font></strong></td>
  </tr>
</table>
<table width="350" border="0" align="center">
 <tr height="20"><td></td></tr>
     <tr>
     	<td>
        <table width="259" border="0" align="center">
            <tr>
            	<td width="74"><strong>Usuario:</strong></td>
                <td width="175"><input type="text" id="usuario" name="usuario" value="<?php echo $row['cpuser'];?>" autofocus="autofocus"/></td>
            </tr>
            <tr>
            	<td><strong>Nombre:</strong></td>
                <td><input type="text" id="nombre" name="nombre" value="<?php echo $row['cpnombre'];?>" /></td>
            </tr>
            <tr>
            	<td><strong>Rol:</strong></td>
                <td><select type="text" name="rol" id="rol">
                      <?php 
					   //Consulto la bd para obtener solo los id de item existentes
					  $sql   = "SELECT cpdescripcion FROM tblrol where id_rol='".$row['idrol_user']."'";
					  $query = mysqli_query($link, $sql);
					  $row1 = mysqli_fetch_array($query);
					  
					  echo '<option selected="selected" value="'.$row["idrol_user"].'">'.$row1['cpdescripcion'].'
												</option>';
												
					  //Consulto la bd para obtener solo los id de item existentes
					  $sql1   = "SELECT id_rol, cpdescripcion FROM tblrol";
					  $query1 = mysqli_query($link, $sql1);
             
			   		  
					  	//Recorrer los iteme para mostrar
						while($row2 = mysqli_fetch_array($query1)){
									if($row2['id_rol'] <> $row['idrol_user']){
										echo '<option value="'.$row2["id_rol"].'">'.$row2["cpdescripcion"].'</option>'; 
									}
								}
					  ?>                       
                    </select></td>
            </tr>
            <tr>
            	<td width="82"><strong>Nombre de Finca:</strong></td>
                <td width="237"><select type="text" name="finca" id="finca">
                      <?php 
					  //Consulto la bd para obtener solo los id de item existentes
					  $sql   = "SELECT nombre FROM tblfinca order by nombre";
					  $query = mysqli_query($link, $sql);
					  	//Recorrer los iteme para mostrar
						echo '<option value="'.$row['finca'].'" selected="selected">'.$row['finca'].'</option>'; 
						while($row1 = mysqli_fetch_array($query)){
									if($row['finca'] != $row1['finca'])
									echo '<option value="'.$row1["nombre"].'">'.$row1["nombre"].'</option>'; 
								}
					  echo '<option value="TODAS">TODAS</option>'; 
					  ?>                       
                    </select></td>
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
	 $user        = $_POST['usuario'];
	 $nombre      = $_POST['nombre'];
	 $rol         = $_POST['rol'];
	 $finca         = $_POST['finca'];  
	 
         //verifico que no existe un usuario igual 
         $sql="SELECT * FROM tblusuario WHERE cpuser='".$user."' and id_usuario!='".$id."'";
         $res= mysqli_query($link, $sql);
         if(mysqli_num_rows($res)==0)
         {
         
            $sql="update tblusuario set cpuser='".$user."', cpnombre='".$nombre."', idrol_user='".$rol."', finca='".$finca."' WHERE id_usuario='".$id."'";
            $modificado= mysqli_query($link, $sql);
            if($modificado){
                   echo("<script> alert ('Usuario modificado correctamente');
                                  window.close();					   
                                              window.opener.document.location='usuarios.php';
                        </script>");
            }else{
                echo("<script> alert (".mysqli_error().");</script>");
            }
         }
         else
        {
          echo("<script> alert('Ya existe un usuario registrado con ese nombre');</script>");  
        }
   }
    
   if(isset($_POST["Cancelar"])){  
 	 echo("<script> window.close()</script>");
   }  
  ?>
</body>
</html>