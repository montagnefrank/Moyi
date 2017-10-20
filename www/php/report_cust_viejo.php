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
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Reporte de Créditos</title>

<link href="../css/tooltip.css" rel="stylesheet" type="text/css" />
<link rel="icon" type="image/png" href="../images/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
<link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/octicons.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/zenburn.css" rel="stylesheet" type="text/css">
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1"rel="stylesheet" type="text/css" media="all"  />

<script src="../js/tooltip.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery-1.2.1.pack.js"></script>
<script language="javascript" src="../js/imprimir.js"></script>
<script type="text/javascript" src="../js/script.js"></script>
<script src="../bootstrap/js/jquery.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
<script src="../bootstrap/js/bootstrap-submenu.js"></script>
<script src="../bootstrap/js/docs.js" defer></script>
<script type="text/javascript" src="../js/calendar.js"></script>
<script type="text/javascript" src="../js/calendar-en.js"></script>
<script type="text/javascript" src="../js/calendar-setup.js"></script>
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
<script type="text/javascript">
function modificar(valor){
	var v=valor;
	window.open("modificarorden.php?codigo="+v,"Cantidad","width=900,height=880,top=50,left=300");
	return false;
}
function cancelar(valor){
	//alert ('Hola');
	var v=valor;
	window.open("eliminar.php?codigo="+v,"Cantidad","width=300,height=150,top=350,left=400");
	return false;
}

function eliminar(valor){
	//alert ('Hola');
	var v=valor;
	window.open("eliminarorden.php?codigo="+v,"Cantidad","width=300,height=150,top=350,left=400");
	return false;
}
</script>
<script language="javascript">
  function Compara(frmFec)
{
	var fecha1 = document.getElementById('txtinicio').value;
	var fecha2 = document.getElementById('txtfin').value;
	var Anio = (frmFec.txtinicio.value).substr(0,4)
    var Mes = ((frmFec.txtinicio.value).substr(5,2))*1     
    var Dia = (frmFec.txtinicio.value).substr(8,2)
    var Anio1 = (frmFec.txtfin.value).substr(0,4)
    var Mes1 = ((frmFec.txtfin.value).substr(5,2))*1 
    var Dia1 = (frmFec.txtfin.value).substr(8,2)
    var Fecha_Inicio = new Date(Anio,Mes,Dia)
    var Fecha_Fin = new Date(Anio1,Mes1,Dia1)
	
	//alert('fecha de inicio'+Fecha_Fin);
	

  if( fecha1 == '' && fecha2 == '' )
    {
      alert("Las fechas no pueden ser vacías. Tiene que tener algún valor para buscar");
	  return false;
     }

	 
    if(Fecha_Inicio > Fecha_Fin)
    {
      alert("La fecha de inicio es mayor que la fecha de fin; Introduzca un período válido");
	  return false;
     }
    else
    {
      return true;
     }
}
  </script>
  <script>
function validar_texto(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
		//alert('No puede ingresar letras');
        return true;
    }
        
    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-9]/;
    
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

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
<a class="navbar-brand" href="services.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
</div>
 
  <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
 <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
    	<li><a href="cust_services.php"><strong>Atención al Cliente</strong></a></li>     	<li><a href="report_cust.php"><strong>Reportes</strong></a></li>
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
<div class="panel-body" align="center">
<div class="table-responsive">
<form method="post" id="frm1" name="frm1" target="_parent" >
 <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <h3><strong>BUSCAR ÓRDENES CON CRÉDITOS, QUEJAS O REENVIOS</strong></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1">
            <strong>Fecha inicio:</strong>
        </div>
        <div class="col-md-2">
            <input name="txtinicio" type="text" id="txtinicio" readonly="readonly" size="20"/>
        </div>
        <div class="col-md-1">
            <strong>Fecha fin:</strong>
        </div>
        <div class="col-md-2">
            <input name="txtfin" type="text" id="txtfin" readonly="readonly" size="20"/>
        </div>
        <div class="col-md-1">
            <input type="radio" name="radio" id="deliver" value="1" checked="checked"/>
        </div>
        <div class="col-md-1">
            <label for="deliver">Fecha Entrega</label>
        </div>
        <div class="col-md-1">
          	<input type="radio" name="radio" id="ship" value="2" />
   		 </div>
        <div class="col-md-1">
         	 <label for="ship">Fecha Vuelo</label>
   		</div>
        <div class="col-md-1"><input type="submit" class="btn btn-primary" name="buscar" id="buscar" value="Buscar" onClick="return Compara(this.form)"/></div>
</div>
</form>
  <form method="post" id="frmreport" name="frmreport">
      <div class="row">
         <div class="col-md-11">
            <h3><strong>Listado de Órdenes</strong></h3>
        </div>
        <div class="col-md-1">
             <input type="image" style="cursor:pointer" id="imprimir"  name="imprimir"class= "imprimir" src="../images/excel.png" heigth="40" value="" data-toggle="tooltip" data-placement="left" title = "Exportar Reporte Excel" width="30" formaction = "crearReportExcel.php"/>
       </div>
  <table width="50%" border="0" align="center" class="table" >
 <tr>
    <td align="center"><strong>Fecha</strong></td>
  	<td align="center"><strong>Ponumber</strong></td>
    <td align="center"><strong>Custnumber</strong></td>
    <td align="center"><strong>Quejas</strong></td>
    <td align="center"><strong>Créditos</strong></td>
    <td align="center"><strong>Reenvio</strong></td>
    <td align="center"><strong>Razones</strong></td>
  </tr>
  <?php
 if(isset($_POST['buscar'])){	  
	  //recoger datos de busqueda
	  $fecha1   = $_POST['txtinicio'];
	  $fecha2   = $_POST['txtfin'];
	  
	  if($_POST['radio'] == "1"){
	  	$sql =   "SELECT * FROM tblcustom_services INNER JOIN tbldetalle_orden ON tblcustom_services.id_orden = tbldetalle_orden.id_orden_detalle WHERE delivery_traking BETWEEN  '".$fecha1."' AND '".$fecha2."'";
	  }else{
		  $sql =   "SELECT * FROM tblcustom_services INNER JOIN tbldetalle_orden ON tblcustom_services.id_orden = tbldetalle_orden.id_orden_detalle WHERE ShipDT_traking BETWEEN  '".$fecha1."' AND '".$fecha2."'";
		    }
			
	      $val = mysqli_query($link, $sql);
		  if(!$val){
		  	echo "<tr><td>".mysqli_error()."</td></tr>";
		   }else{
			   $cant = 0;
			   while($row = mysqli_fetch_array($val)){
				   $cant ++;
					echo "<tr>";
					echo "<td align='center'>".$row['fecha']."</td>";
					echo "<td align='center'><strong>".$row['Ponumber']."</strong></td>";
					echo "<td align='center'><strong>".$row['Custnumber']."</strong></td>";
					echo "<td>".$row['quejas']."</td>";
					echo "<td align='center'><strong>".$row['credito']."</strong></td>";
					echo "<td align='center'>".$row[5]."</td>";
					echo "<td align='center'>".$row['razones']."</td>";
					echo "</tr>";
			 }
						echo "<tr>
								  <td align='right'><strong>".$cant. "</strong></td>
								  <td>Órden(es) encontradas</td>
							  </tr>";						
		   }
		   mysqli_close($conection);
		   
		   //Preparando los datos para el reporte
		   $_SESSION["titulo"] ="Listado de órdenes con créditos, quejas y reenvios";
		   $_SESSION["columnas"] = array("Fecha","Ponumber","Custnumber","Quejas","Créditos","Reenvio","Razones"); 
		   
		   if($_POST['radio'] == "1"){
	  	$_SESSION["consulta"] =   "SELECT fecha,tblcustom_services.Ponumber,tblcustom_services.Custnumber,quejas,credito,tblcustom_services.reenvio,razones FROM tblcustom_services INNER JOIN tbldetalle_orden ON tblcustom_services.id_orden = tbldetalle_orden.id_orden_detalle WHERE delivery_traking BETWEEN  '".$fecha1."' AND '".$fecha2."'";
	  }else{
		  $_SESSION["consulta"] =   "SELECT fecha,tblcustom_services.Ponumber,tblcustom_services.Custnumber,quejas,credito,tblcustom_services.reenvio,razones FROM tblcustom_services INNER JOIN tbldetalle_orden ON tblcustom_services.id_orden = tbldetalle_orden.id_orden_detalle WHERE ShipDT_traking BETWEEN  '".$fecha1."' AND '".$fecha2."'";
		    }
			$_SESSION["nombre_fichero"] = "Reporte Customer Service.xlsx";
  }
  ?>
</table>
</form>
</div>
<div class="row">
        <div class="col-md-12">
        	<h3><strong>Leyenda de razones</strong></h3>
        </div>
</div>
<div class="row">
        <div class="col-md-12">
        	<strong>FZ:</strong> FROZEN --
            <strong>BO:</strong> BOTRITYS --
            <strong>CV:</strong> CRACKED VASE --
            <strong>IH:</strong> IMPROPER HYDRATION --            
            <strong>UPS:</strong> UPS ERROR --
            <strong>SHC:</strong> SHIPMENT DELIVERED CORRECTLY 
        </div>
</div>

<div class="row">
        <div class="col-md-12">
        	<strong>SHI:</strong> SHIPMENT DELIVERED INCORRECTLY          
            <strong>GP:</strong> GUARD PETALS --
            <strong>WAB:</strong> WRONG AMOUNT OF BLOOMS --
            <strong>WB:</strong> WRONG BOUQUET --
            <strong>WC:</strong> WRONG COLOR --
            <strong>DWA:</strong> DELIVERED TO WRONG ADDRESS
        </div>
</div>

<div class="row">
        <div class="col-md-12">
        	<strong>D2DL:</strong> DELIVERY 2+ DAYS LATE --
        	<strong>IND:</strong> ITEM NOT DELIVERED -- 
            <strong>NMS:</strong> NO MENSSAGE SENT --
            <strong>IN:</strong> INSECTS --
            <strong>OT:</strong> OTHER
        </div>
</div>

  </div> <!-- table responsive-->
</div> <!-- /panel body --> 
<div class="panel-heading">
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

<!---------------------- Scripts ------------------------------>
<script type="text/javascript">
        function catcalc(cal) {
            var date = cal.date;
            var time = date.getTime()
            // use the _other_ field
            var field = document.getElementById("f_calcdate");
            if (field == cal.params.inputField) {
                field = document.getElementById("txtinicio");
                time -= Date.WEEK; // substract one week
            } else {
                time += Date.WEEK; // add one week
            }
            var date2 = new Date(time);
            field.value = date2.print("%Y-%m-%d");
        }
        Calendar.setup({
            inputField     :    "txtinicio",   // id of the input field
            ifFormat       :    "%Y-%m-%d ",       // format of the input field
            showsTime      :    false,
            timeFormat     :    "24",
            onUpdate       :    catcalc
        });
    
</script>
<script type="text/javascript">
        function catcalc(cal) {
            var date = cal.date;
            var time = date.getTime()
            // use the _other_ field
            var field = document.getElementById("f_calcdate");
            if (field == cal.params.inputField) {
                field = document.getElementById("txtfin");
                time -= Date.WEEK; // substract one week
            } else {
                time += Date.WEEK; // add one week
            }
            var date2 = new Date(time);
            field.value = date2.print("%Y-%m-%d");
        }
        Calendar.setup({
            inputField     :    "txtfin",   // id of the input field
            ifFormat       :    "%Y-%m-%d ",       // format of the input field
            showsTime      :    false,
            timeFormat     :    "24",
            onUpdate       :    catcalc
        });
    
                  </script>
</body>
</html>

