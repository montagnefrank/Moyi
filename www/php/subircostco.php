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
        
          <a class="navbar-brand" href="administration.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio">
              <span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
      </div> 
  <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
 <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
        <li class="dropdown active">
          <a tabindex="0" data-toggle="dropdown">
            <strong>Pagos</strong><span class="caret"></span>
          </a>
          <ul class="dropdown-menu" role="menu">
              <li class="active"><a href="subircostco.php"><strong>Subir Archivo de Costo</strong></a></li>
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
 </div>
</nav>
  </div>
<div class="panel-body">

<table>
  <tr height="200">
    <td >
      <h3>Seleccione el archivo de Costco</h3>
      <form action="subirarchivo2.php" method="post" enctype="multipart/form-data" name="Upload" id="Upload">
        <input type="file" name="archivo[]"/>
        <input type="submit"  class="buttons" value="Cargar archivo"  />
      </form>
      </td>  
  </tr>
</table>
</div>

    
<div class="panel-heading">
  <div class="contenedor" align="center">
    <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2015 versión 3</strong>
    <br>
    <strong><a href="http://www.bit-store.ec/index.php/contactenos/"  style="color:white">Info</a> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></strong>
  </div>
</div>
</div>
</body>
</html>