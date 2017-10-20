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
<title>Atención al Cliente</title>

<link rel="icon" type="image/png" href="../images/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
<link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/octicons.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/zenburn.css" rel="stylesheet" type="text/css">
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">

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
	if($rol==6){
            echo '<a class="navbar-brand" href="services.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>';
	}else{
            echo '<a class="navbar-brand" href="../main.php?panel=mainpanel.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>';		
        }
	?>
</div>
 
  <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
 <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
    	<li><a href="cust_services.php"><strong>Atención al Cliente</strong></a></li>
        <li><a href="report_cust.php"><strong>Reportes</strong></a></li>
        <li><a href="report_cust_viejo.php"><strong>Reportes2</strong></a></li>
        <li><a href="gestionarcausas.php"><strong>Gestionar Causas</strong></a></li>
		<?php
		if($rol == 6)
                     echo '<li><a href="filtros.php"><strong>Ver Órdenes</strong></a></li>';
                if($rol == 1){  
			     	 //no muestra nada
                }else{
                    $sql   = "SELECT id_usuario from tblusuario where cpuser='".$user."'";
                    $query = mysqli_query($link, $sql);
                    $row = mysqli_fetch_array($query);
                    echo '<li><a href="" onclick="cambiar(\''.$row[0].'\')"><strong>Contraseña</strong></a></li>';

                }
		?>	
    </ul>
        <ul class="nav navbar-nav navbar-right">
        <li><a class="navbar-brand" href="" data-toggle="tooltip" data-placement="bottom" title="Usuario conectado"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo $user?></a></li>
          <li><a class="navbar-brand" href="../index.php" data-toggle="tooltip" data-placement="bottom" title="Salir del sistema" ><span class="glyphicon glyphicon-off" aria-hidden="true"></span></a></li>
        </ul>
  </div>
</nav>
</div>
<div id="inicio" style="display:block" align="center">	
<div class="panel-body">
<div class="row">
    	<div class="col-md-1"></div>
  		<div class="col-md-3"> 
              <div class="panel panel-danger">
                  <div class="panel-heading">
                    <h3 class="panel-title">	Créditos hasta hoy</h3>
                  </div>
                  <div class="panel-body">
      <?php
  $sql = "SELECT COUNT(*) as cantidad FROM tblcustom_services where credito != '0' AND fecha<='".date('Y-m-d')."'";
  $query = mysqli_query($link, $sql);
  $row = mysqli_fetch_array($query);
  if($row[0] <> 0){
  	echo "<strong>".$row[0]."</strong>";
  }else{
	echo "<strong>0</strong>";
	  }
  ?>
                  </div>
              </div>
          </div>
          
       <div class="col-md-3"> 
              <div class="panel panel-danger">
                  <div class="panel-heading">
                    <h3 class="panel-title">Quejas hasta hoy</h3>
                  </div>
                  <div class="panel-body">   
      <?php
  $sql = "SELECT COUNT(*) as cantidad FROM tblcustom_services where quejas != '' AND fecha<='".date('Y-m-d')."'";
  $query = mysqli_query($link, $sql);
  $row = mysqli_fetch_array($query);
  if($row[0] <> 0){
  	echo "<strong>".$row[0]."</strong>";
  }else{
	echo "<strong>0</strong>";
	  }
  ?>
    			</div>
    		</div>
          </div>
          
     <div class="col-md-3"> 
              <div class="panel panel-danger">
                  <div class="panel-heading">
                    <h3 class="panel-title">Reenvios hasta hoy</h3>
                  </div>
                  <div class="panel-body">     
      <?php
  $sql = "SELECT COUNT(*) as cantidad FROM tblcustom_services where reenvio != 'No' AND fecha<='".date('Y-m-d')."'";
  $query = mysqli_query($link, $sql);
  $row = mysqli_fetch_array($query);
  if($row[0] <> 0){
  	echo "<strong>".$row[0]."</strong>";
  }else{
	echo "<strong>0</strong>";
	  }
  ?>
  				</div>
    		</div>
          </div>
          <div class="col-md-1"></div>
</div>
</div>
</div>
   <div class="panel-heading">
      <div class="contenedor" align="center">
        <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2015 versión 3</strong>
        <br>
        <strong><a href="http://www.bit-store.ec/index.php/contactenos/"  style="color:white">Info</a> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></strong>
      </div>
   </div>   
</div> <!-- /container -->
</body>
</div>
</html>

