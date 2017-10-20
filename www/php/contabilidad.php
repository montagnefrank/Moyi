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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Contabilidad ECU</title>
 <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
 <link rel="icon" type="image/png" href="../images/favicon.ico" />
  <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
  <script type="text/javascript" src="../js/script.js"></script>
  <script>
  function salir(){
	window.location='../index.php';
  }
  function home(id){
	  if(id == 2){
			window.location='contabilidad.php';
	  }else{
		    window.location='../main.php';
		  }
  }
  function cambiar(valor){
	var v=valor;
	window.open("cambiarcontrasenna.php?codigo="+v,"Cantidad","width=400,height=300,top=150,left=400");
	return false;
}

function mostrar(id){
	document.getElementById(id).style.display = 'block';
}
  </script>
</head>
<div id="contenedor">
<body background="../images/fondo.jpg">
<table width="1024" border="0" align="center">
  <tr>
    <td height="133" align="center" colspan="4"><img src="../images/logo.png" width="328" height="110" /></td>
  </tr>
  <tr>
  	<td>
    <?php
	if($rol == 9){
    echo '<input type="image" src="../images/home.png" height="40" width="30" title="Inicio" onclick="home(2)"></td>';
	}else{
		echo '<input type="image" src="../images/home.png" height="40" width="30" title="Inicio" onclick="home(1)"></td>';
		}
	?>
    <td colspan="4" align="right">
    <?php
	if($rol == 9)
    echo '<input type="image" src="../images/logout.png" height="40" width="30" title="Salir" onclick="salir()">';
	?>
</td>
  </tr>
  <tr>  
    <td height="34" align="center" bgcolor="#3B5998" colspan="5">
    <ul id="MenuBar1" class="MenuBarHorizontal">
              <!--<li><a href="filtros.php"><strong>Show Orders</strong></a></li>-->
              <li><a href=""><strong>Compras</strong></a>
                  <ul>
                  <li><a href="proveedor.php">Proveedor</strong></a></li>
                  <li><a href="retxcobrar.php">Retenciones x Cobrar</a></li>
                  <li><a href="pendcobro.php">Pendiente de Cobro</a></li>
                  <li><a href="factcompra.php">Facturas Compras</a></li>
                  </ul>
              </li>
          			 
			<li><a href=""><strong>Ventas</strong></a>
              		<ul>
                        <li><a href="cliente.php">Clientes</a></li>
                        <li><a href="factventa.php">Facturas Ventas</a></li>
                        <li><a href="productos.php">Productos</a></li>
                        <li><a href="retxpagar.php">Retenciones por Pagar</a></li>
                    </ul>
               </li>
               
               <li><a href=""><strong>Contabilidad</strong></a>
              		<ul>
                        <li><a href="plancuentas.php">Plan de Cuentas</a></li>
                        <li><a href="diario.php">Diario</a></li>
                        <li><a href="ingreso.php">Ingreso</a></li>
                        <li><a href="egreso.php">Egreso</a></li>
                        <li><a href="libro.php">Libro Mayor</a></li>
	                    <li><a href="balance.php">Balance de Comprobaci�n</a></li>
	                    <li><a href="resultado.php">Estado de Resultado</a></li>
                    </ul>
                    
               </li>
               <?php	
				 if($rol == 9){ 
					$sql   = "SELECT id_usuario from tblusuario where cpuser='".$user."'";
					$query = mysqli_query($link, $sql);
					$row = mysqli_fetch_array($query);
					echo '<li><a href="" onclick="cambiar(\''.$row[0].'\')"><strong>Contrase�a</strong></a>'; 
					 }				 
			 ?>
             
          </ul></td>
  </tr>
  <tr>
  <tr height="200">
    <td id="costo" style='display:none;'>
      <h3>Seleccione el archivo de costo de Costco</h3>
      <form action="subirarchivo2.php" method="post" enctype="multipart/form-data" name="Upload" id="Upload">
        <input type="file" name="archivo[]"/>
        <input type="submit"  class="buttons" value="Cargar archivo"  />
      </form>
      </td>  
  </tr>
  <!--<tr>  
   <td  height="20" align="left"  style="border:1px solid black">�rdenes nuevas:
      <?php 
	/*$sql = "select count(*)
							FROM
							tbldetalle_orden WHERE status = 'New'";
	$query=mysqli_query($link, $sql);
	$query = mysqli_query($link, $sql)or die("Error Searching....");
	$row = mysqli_fetch_array($query);
    if($row[0] <> 0){
  	echo "<strong>".$row[0]."</strong>";
  }else{
	echo "<strong>0</strong>";
	  }
  ?>
    </td>  
      <?php
  $sql = "SELECT SUM(cpcantidad) FROM tbldetalle_orden where ShipDT_traking ='".date('Y-m-d')."' AND tracking = ''";
  $query = mysqli_query($link, $sql);
  $row = mysqli_fetch_array($query);
  if($row[0] <> 0){
	  echo '<td  height="20" align="left"  style="border:1px solid black"  bgcolor="#FF0000">
�rdenes por volar:';
  	echo "<a href= './php/ordenesxvolar.php' title='Ver �rdenes'><strong>".$row[0]."</strong></a>";
  }else{
	   echo '<td  height="20" align="left"  style="border:1px solid black">�rdenes por volar:';
	echo "<strong>0</strong>";
	  }
  ?>
    </td>     
    <td height="15" align="left"  style="border:1px solid black">Cajas en cuarto fr�o:
      <?php
  $sql = "SELECT COUNT(*) FROM tblcoldroom where fecha<='".date('Y-m-d')."' AND entrada = 'Si'";
  $query = mysqli_query($link, $sql);
  $row = mysqli_fetch_array($query);
   if($row[0] <> 0){
  	echo "<strong>".$row[0]."</strong>";
  }else{
	echo "<strong>0</strong>";
	  }
  ?>
      </td>
      <td height="15" align="left" style="border:1px solid black">Cajas recibidas hoy:
            <?php
  $sql = "SELECT COUNT(*) FROM tblcoldroom where fecha ='".date('Y-m-d')."' AND entrada = 'Si'";
  $query = mysqli_query($link, $sql);
  $row = mysqli_fetch_array($query);
  if($row[0] <> 0){
  	echo "<strong>".$row[0]."</strong>";
  }else{
	echo "<strong>0</strong>";
	  }
  ?>
  </td>
      
<td width="82" height="15" align="left" style="border:1px solid black">
	 <?php
        echo '<img src="../images/user.png" height="15" width="15" title="Usuario Conectado"><strong>'.$user.'</strong>';
		
		*/?>
        </td>
  </tr>-->
  <tr>
    <td height="36" align="center" bgcolor="#3B5998" colspan="5"><strong><font color="#FFFFFF">Bit <img src="../images/r.png" width="15" height="15"/> 2015 version 0.2 Beta </font></strong></td>
  </tr>
</table>
<br />
<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"php/SpryAssets/SpryMenuBarDownHover.gif", imgRight:"php/SpryAssets/SpryMenuBarRightHover.gif"});
</script>
</body>
</div>
</html>

