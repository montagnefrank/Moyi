<?php
ini_set('display_errors', 'On');
ini_set('display_errors', 1);

    session_start();
	include ("conectarSQL.php");
    include ("conexion.php");
	include ("seguridad.php");
	
	$user     =  $_SESSION["login"];

  echo $user;

	$passwd   =  $_SESSION["passwd"];
	$rol      =  $_SESSION["rol"];
	$id       =  $_GET['id'];

    $link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Asignar DAE</title>

<link rel="icon" type="image/png" href="../images/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
<link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/octicons.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/zenburn.css" rel="stylesheet" type="text/css">
<link href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1"rel="stylesheet" type="text/css" media="all"  />
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">

<script language="javascript" src="../js/imprimir.js"></script>
<script type="text/javascript" src="../js/script.js"></script>
<script src="../bootstrap/js/jquery.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
<script src="../bootstrap/js/bootstrap-submenu.js"></script>
<script src="../bootstrap/js/docs.js" defer></script>
<script type="text/javascript" src="../js/calendar.js"></script>
<script type="text/javascript" src="../js/calendar-en.js"></script>
<script type="text/javascript" src="../js/calendar-setup.js"></script><style>
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
$(document).ready(function(){
		//tol-tip-text
		$(function () {
		  $('[data-toggle="tooltip"]').tooltip()
		});
		
		// Only enable if the document has a long scroll bar
		// Note the window height + offset
		if ( ($(window).height() + 100) < $(document).height() ) {
			$('#top-link-block').removeClass('hidden').affix({
				// how far to scroll down before link "slides" into view
				offset: {top:100}
			});
		}
    });
	
	    function cambiar(valor){
	var v=valor;
	window.open("cambiarcontrasenna.php?codigo="+v,"Cantidad","width=400,height=300,top=150,left=400");
	return false;
}
</script>
</head>
<body background="../images/fondo.jpg" class="dt">
<div class="container">
<div align="center" width="100%">
    	<img src="../images/logo.png" class="img-responsive"/>
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
<a class="navbar-brand" href="imp_etiquetas.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
</div>
 
  <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
 <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
    	<li class="active"><a href="dae.php"><strong>Ingresar DAE</strong></a></li>     	<li><a href="fact_com.php?finca='.$finca.'"><strong>Facturas Comerciales</strong></a></li>
		<?php
                     if($rol == 1){  
			     	 //no muestra nada
					 }else{
					$sql   = "SELECT id_usuario from tblusuario where cpuser='".$user."'";
					$query = mysql_query($sql,$conection);
					$row = mysql_fetch_array($query);
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
<div class="panel-body" align="center">

<form action="subirdae.php?finca=".'$user'."" method="post" enctype="multipart/form-data" name="Upload" id="Upload">
<div class="table-responsive">
<table width="50%" border="0" align="center" class="table table-striped" >
     <tr>
        <td colspan="4" align="center"><h3><strong>ASIGNAR DAE</strong></h3></td>
     </tr>
     <tr>               
     	<td  align="right"><strong>DAE:</strong></td>
        <td><input type="text" id="dae" name="dae" value="" size="30" autofocus="autofocus" tabindex="0"/><font color="#CC0000">*</font>
        </td>
     </tr>
     <tr>               
        <td align="right"><strong>País:</strong></td>
        <td><select name="pais">
                   <option value="US">US</option>
                   <option value="CA">CA</option>
                   </select><font color="#CC0000">*</font>
        </td>
    </tr>
    <tr>               
        <td align="right"><strong>Fecha de inicio:</strong></td>
        <td><input name="finicio" type="text" id="finicio" readonly="readonly" size="20" value="<?php echo date ('Y-m-d')?>" tabindex="12"/><font color="#CC0000">*</font></td> 
    </tr>
    <tr>
         <td align="right"><strong>Fecha de caducidad:</strong></td>
          <td><input name="ffin" type="text" id="ffin" readonly="readonly" size="20" value="<?php 
				$date = date ('Y-m-d');
				$fecha = new DateTime($date);
				$fecha->modify('last day of this month');
				echo $fecha->format('Y-m-d');				
				?>" tabindex="12"/>
                <font color="#CC0000">*</font>
           </td>
     </tr>
     <tr>
           <td align="right"><strong>Cargar pdf DAE:</strong></td>
           <td>                
                <input type="file" name="archivo[]" class="btn btn-success"/>
                <?php
                if($_GET['id'] == 1)
                    echo '<script> alert ("DAE cargado correctamente");</script>';
                    
                if($_GET['id'] == 2)
                    echo '<script> alert ("Falta el campo DAE por completar.");</script>';	
                    
                 if($_GET['id'] == 3)
                    echo '<script> alert ("Ya tiene un DAE asignado para ese mes.");</script>';
                ?>
      </td>                
    </tr>
     <tr>
            <td align="right">&nbsp;</td>
            <td><input name="Crear" type="submit" value="Asignar" tabindex="30" class="btn btn-primary" data-toggle="tooltip" data-placement="rigth" title="Cargar y asignar Dae"/>
    </tr>
 </table>
 </div> <!-- table responsive-->
 </form>
</div> <!-- /panel body --> 

   <div class="panel-heading">
      <div class="contenedor" align="center">
        <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2015 versión 3</strong>
        <br>
        <strong><a href="http://www.bit-store.ec/index.php/contactenos/"  style="color:white">Info</a> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></strong>
      </div>
   </div>
       
    <span id="top-link-block" class="hidden">
    <a href="#top" class="well well-sm"  onclick="$('html,body').animate({scrollTop:0},'slow');return false;">
        <i class="glyphicon glyphicon-chevron-up"></i> Ir arriba
    </a>
</span><!-- /top-link-block --> 
</div> <!-- /container -->

<!---------------Scripts ----------------------->
<script type="text/javascript">
        function catcalc(cal) {
            var date = cal.date;
            var time = date.getTime()
            // use the _other_ field
            var field = document.getElementById("f_calcdate");
            if (field == cal.params.inputField) {
                field = document.getElementById("finicio");
                time -= Date.WEEK; // substract one week
            } else {
                time += Date.WEEK; // add one week
            }
            var date2 = new Date(time);
            field.value = date2.print("%Y-%m-%d");
        }
        Calendar.setup({
            inputField     :    "finicio",   // id of the input field
            ifFormat       :    "%Y-%m-%d ",       // format of the input field
            showsTime      :    false,
            timeFormat     :    "24",
            onUpdate       :    catcalc
        });
    

        function catcalc(cal) {
            var date = cal.date;
            var time = date.getTime()
            // use the _other_ field
            var field = document.getElementById("f_calcdate");
            if (field == cal.params.inputField) {
                field = document.getElementById("ffin");
                time -= Date.WEEK; // substract one week
            } else {
                time += Date.WEEK; // add one week
            }
            var date2 = new Date(time);
            field.value = date2.print("%Y-%m-%d");
        }
        Calendar.setup({
            inputField     :    "ffin",   // id of the input field
            ifFormat       :    "%Y-%m-%d ",       // format of the input field
            showsTime      :    false,
            timeFormat     :    "24",
            onUpdate       :    catcalc
        });
    
</script>
</body>
</html>