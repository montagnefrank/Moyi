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
<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contabilidad</title>
  
  <link rel="icon" type="image/png" href="../images/favicon.ico" />
  <link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
  <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/octicons.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/zenburn.css" rel="stylesheet" type="text/css">
  
  <script language="javascript" src="../js/imprimir.js"></script>
  <script type="text/javascript" src="../js/script.js"></script>
  <script src="../bootstrap/js/jquery.js"></script>
  <script src="../bootstrap/js/bootstrap.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
  <script src="../bootstrap/js/bootstrap-submenu.js"></script>
  <script src="../bootstrap/js/docs.js" defer></script>

  <!-- <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script> -->
  <script type="text/javascript" src="../js/script.js"></script>
  <script>
    function salir(){
  	window.location='../index.php';
    }
    function home(id){
  	  if(id == 2){
  		window.location='administration.php';
  	  }else{
  		window.location='../main.php?panel=mainpanel.php';
        }
    }
    function cambiar(valor){
  	var v=valor;
  	window.open("cambiarcontrasenna.php?codigo="+v,"Cantidad","width=400,height=300,top=150,left=400");
  	return false;
  }
  </script>


  <style>
    .contenedor {
         margin-left: 10px;
         width:100%;
         text-align:center;
    }

    .navbar-fixed-top + .content-container {
      margin-top: 70px;
    }
    .content-container {
      margin: 0 130px;
    }

    #top-link-block.affix-top {
        position: absolute; /* allows it to "slide" up into view */
        bottom: -82px; /* negative of the offset - height of link element */
        left: 10px; /* padding from the left side of the window */
    }
    #top-link-block.affix {
        position: fixed; /* keeps it on the bottom once in view */
        bottom: 18px; /* height of link element */
        left: 10px; /* padding from the left side of the window */
    }
    li a{
      	cursor:pointer;/*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
      } 
  </style>

  <script>
    function cambiar(valor){
      var v=valor;
      window.open("cambiarcontrasenna.php?codigo="+v,"Cantidad","width=400,height=300,top=150,left=400");
      return false;
    }

    $(document).ready(function(){
      //tol-tip-text
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      });
    }); 
  </script>

</head>

<body background="../images/fondo.jpg">
<div class="container">
<div align="center" width="100%">
      <img src="../images/logo.png"  class="img-responsive"/>
</div>

<div class="panel panel-primary">
  <div class="panel-heading">         
  
  <nav class="navbar navbar-default" role="navigation">
<!-- El logotipo y el icono que despliega el menú se agrupan
     para mostrarlos mejor en los dispositivos móviles -->

    <div class="container-fluid">
     <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Desplegar navegación</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        <?php
          if($rol==4){
           echo '<a class="navbar-brand" href="administration.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>';
          }else{
            echo '<a class="navbar-brand" href="../main.php?panel=mainpanel.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>';   
            }
        ?>
      </div>



  <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
 <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
        <li class="dropdown">
          <a tabindex="0" data-toggle="dropdown">
            <strong>Pagos</strong><span class="caret"></span>
          </a>
          <ul class="dropdown-menu" role="menu">
              <li><a href="subircostco.php"><strong>Subir Archivo de Costo</strong></a></li>
              <li class="divider"></li>
              <li><a href="pagosycreditos.php"><strong>Pagos y Créditos Manuales</strong></a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a tabindex="0" data-toggle="dropdown">
            <strong>Reportes</strong><span class="caret"></span>     
          </a>
          <ul class="dropdown-menu" role="menu">
            <li class="dropdown-submenu">
              <a tabindex="0" data-toggle="dropdown"><strong>Reportes Manifest Costco</strong></a>            
              <ul class="dropdown-menu">                               
                  <li><a href="manifest.php">Reporte Manifest Costco</a></li>
                  <li class="divider"></li>
                  <li><a href="manifestfull.php">Reporte Manifest Costco Completo</a></li>
              </ul>
            </li>
            <li class="divider"></li>
            <li class="dropdown-submenu">
              <a tabindex="0" data-toggle="dropdown"><strong>Ventas</strong></a>            
              <ul class="dropdown-menu">                               
                  <li><a href="venta.php?id=1">Total Vendidos</a></li>
                  <li class="divider"></li>
                  <li><a href="venta.php?id=2">Créditos</a></li>
                  <li class="divider"></li>
                  <li><a href="venta.php?id=3">Neto Vendidos</a></li>
              </ul>
            </li>
            <li class="divider"></li>
            <li class="dropdown-submenu">
              <a tabindex="0" data-toggle="dropdown"><strong>Pagos</strong></a>            
              <ul class="dropdown-menu">                               
                  <li><a href="pagos.php">Pagos por Costco</a></li>
                  <li class="divider"></li>
                  <li><a href="cuadre.php">Cuadre de pagos</a></li>
              </ul>
            </li>
          </ul>
        </li>

       
    <?php
          if($rol == 4){  
            $sql   = "SELECT id_usuario from tblusuario where cpuser='".$user."'";
            $query = mysqli_query($link, $sql);
            $row = mysqli_fetch_array($query);
            echo '<li><a href="" onclick="cambiar(\''.$row[0].'\')"><strong>Contraseña</strong></a>'; 
           }
    ?> 
    </ul>  <!--Fin del navbar -->

      <ul class="nav navbar-nav navbar-right">
        <li><a class="navbar-brand" href="" data-toggle="tooltip" data-placement="bottom" title="Usuario conectado"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo $user?></a></li>
        <li><a class="navbar-brand" href="../index.php" data-toggle="tooltip" data-placement="bottom" title="Salir del sistema" ><span class="glyphicon glyphicon-off" aria-hidden="true"></span></a></li>
      </ul>
  </div>
</nav>
  </div>


<!--
<table width="1024" border="0" align="center">
  <tr>
  	<td>
    <?php
	if($rol == 5){
    echo '<input type="image" src="../images/home.png" height="40" width="30" title="Inicio" onclick="home(2)"></td>';
	}else{
		echo '<input type="image" src="../images/home.png" height="40" width="30" title="Inicio" onclick="home(1)"></td>';
		}
	?>
    <td colspan="4" align="right">
    <?php
	if($rol == 5)
    echo '<input type="image" src="../images/logout.png" height="40" width="30" title="Salir" onclick="salir()">';
	?>
</td>
  </tr>
  <tr>  
    <td height="34" align="center" bgcolor="#3B5998" colspan="5">
    <ul id="MenuBar1" class="MenuBarHorizontal">
              <li><a href=""><strong>Pagos</strong></a>
              <ul>
              <li><a href="#" onclick="mostrar('costo')"><strong>Subir Archivo de Costo</strong></a></li>
              <li><a href="pagosycreditos.php"><strong>Pagos y Créditos Manuales</strong></a></li>
              </ul>
              </li>
          			 
			<li><a href=""><strong>Reportes</strong></a>
               <ul>
                   <li><a href=""><strong>Reportes Manifest Costco</strong></a>
                   <ul>
                        <li><a href="manifest.php"><strong>Reporte Manifest Costco</strong></a></li>
                  <li><a href="manifestfull.php"><strong>Reporte Manifest Costco Completo</strong></a></li>
                  </ul>
                  </li>
                    <li><a href=""><strong>Ventas</strong></a>
              		 <ul>
                        <li><a href="venta.php?id=1">Total Vendido</a></li>
                        <li><a href="venta.php?id=2">Creditos</a></li>
                        <li><a href="venta.php?id=3">Neto Vendido</a></li>
                    </ul>
                    </li>
                    <li><a href=""><strong>Pagos</strong></a>
              		 <ul>
                        <li><a href="pagos.php">Pagos por Costco</a></li>
                        <li><a href="cuadre.php">Cuadre de Pagos</a></li>
                    </ul>
                    </li>
               </ul>
               </li>
               <?php	
				 if($rol == 5){ 
					$sql   = "SELECT id_usuario from tblusuario where cpuser='".$user."'";
					$query = mysqli_query($link, $sql);
					$row = mysqli_fetch_array($query);
					echo '<li><a href="" onclick="cambiar(\''.$row[0].'\')"><strong>Change Password</strong></a>'; 
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
  <tr>
    <td height="36" align="center" bgcolor="#3B5998" colspan="5"><strong><font color="#FFFFFF">Bit <img src="../images/r.png" width="15" height="15"/> 2015 versión 3 </font></strong></td>
  </tr>
</table>




<br />
<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"php/SpryAssets/SpryMenuBarDownHover.gif", imgRight:"php/SpryAssets/SpryMenuBarRightHover.gif"});
</script>

-->

<div class="panel-body" align="center">
<div id="alertas">
    <div class="col-md-2"> 
                  <div class="panel panel-primary">
                      <div class="panel-heading">
                        <h3 class="panel-title">Órdenes Diarias</h3>
                      </div>
                      <div class="panel-body">
      <?php 

      $today = date("Y-m-d"); 

      if($rol == 3){
        $sql = "select count(*)
                FROM
                tblorden
                INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                WHERE `order_date`='".$today."'";

          $query=mysqli_query($link, $sql);
          $query = mysqli_query($link, $sql)or die("Error Searching....");
          $row = mysqli_fetch_array($query);
          if($row[0] <> 0){
            echo "<strong>".$row[0]."</strong>";
            }else{
            echo "<strong>0</strong>";
              }
        }else{
            $sql = "select count(*)
                    FROM
                    tblorden
                    INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                    WHERE `order_date`='".$today."'";

            //$sql = "select count(*) FROM tbldetalle_orden WHERE status = 'New'";
            $query=mysqli_query($link, $sql);
            $query = mysqli_query($link, $sql)or die("Error Searching....");
            $row = mysqli_fetch_array($query);
            if($row[0] <> 0){
              echo "<center><strong>".$row[0]."</strong></center>";
              }else{
              echo "<center><strong>0</strong></center>";
                }
            }
      ?>
                      </div>
                  </div>
              </div>

    <div class="col-md-2"> 
                  <div class="panel panel-primary">
                      <div class="panel-heading">
                        <h3 class="panel-title">Por Despachar</h3>
                      </div>
                      <div class="panel-body">
      <?php 
            $sql = 'select count(*) FROM tbldetalle_orden WHERE estado_orden="Active" AND (status = "New" OR status="Ready to ship")';
            $query = mysqli_query($link, $sql)or die("Error Searching....");
            $row = mysqli_fetch_array($query);
            if($row[0] <> 0){
              echo "<center><strong>".$row[0]."</strong></center>";
              }else{
              echo "<center><strong>0</strong></center>";
                }

      ?>
                      </div>
                  </div>
              </div>

    <div class="col-md-2"> 
    
   <?php 
        $sql = 'SELECT count(Ponumber),Ponumber, cpitem FROM `tbldetalle_orden` 
                inner join tblorden on id_detalleorden =id_orden where status="New" OR status="Ready to ship"
                group by Ponumber,cpitem HAVING count(Ponumber)>=3
                order by Ponumber desc';
        $query = mysqli_query($link, $sql)or die("Error Searching....");
        $row = mysqli_num_rows($query);
        
        if($row>0)
        {
          echo '<div class="panel panel-danger">';
        }else{ 
        
         echo '<div class="panel panel-primary">';
        } 
        ?>
     <div class="panel-heading">
         <h3 class="panel-title">Auditar Ordenes</h3>
     </div>
    <div class="panel-body">
  <?php
        if($row>0)
         echo "<a href= './ordenes_ponumber_revisar.php' data-toggle='tooltip' data-placement= 'bottom' title='Ver órdenes por arreglar'><center><strong>".$row."</strong></center></a>";
        else
            echo '<center><strong>'.$row.'</center></strong>'
  ?>
    </div>
</div>
</div>
</div>
</div> <!-- panel panel-primary -->

    
<div class="panel-heading">
  <div class="contenedor" align="center">
    <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2015 versión 3</strong>
    <br>
    <strong><a href="http://www.bit-store.ec/index.php/contactenos/"  style="color:white">Info</a> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></strong>
  </div>
</div>
</div> <!-- /container -->
</body>
</html>
