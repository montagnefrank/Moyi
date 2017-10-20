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
<link href="../bootstrap/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css">
    
    
<script src="../js/tooltip.js" type="text/javascript"></script>
<script language="javascript" src="../js/imprimir.js"></script>
<script type="text/javascript" src="../js/script.js"></script>
<script src="../bootstrap/js/jquery.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="../bootstrap/js/moment.js"></script>
<script src="../bootstrap/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
<script src="../bootstrap/js/bootstrap-submenu.js"></script>
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
        
        //boton exportar
        $(".botonExcel").click(function(event) {
            $("#datos_a_enviar").val( $("<div>").append( $("#listado").clone()).html());
            $("#FormularioExportacion").submit();
        });
    });
    
    $(function () {
        $('#txtinicio').datetimepicker(
          {format: "YYYY-MM-DD",
            showTodayButton:true}
        );
        $('#txtfin').datetimepicker({
            useCurrent: false, //Important! See issue #1075
            format: "YYYY-MM-DD",
            showTodayButton:true
        });
        $("#txtinicio").on("dp.change", function (e) {
            $('#txtfin').data("DateTimePicker").minDate(e.date);
        });
        $("#txtfin").on("dp.change", function (e) {
            $('#txtinicio').data("DateTimePicker").maxDate(e.date);
        });
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
    	<li><a href="cust_services.php"><strong>Atención al Cliente</strong></a></li>     	
        <li><a href="report_cust.php"><strong>Reportes</strong></a></li>
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
<div class="panel-body" align="center">
<div class="table-responsive">
<form method="post" id="frm1" name="frm1" target="_parent" >
 <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <h3><strong>BUSCAR ÓRDENES CON CRÉDITOS, QUEJAS O REENVIOS</strong></h3>
        </div>
</div>
    
<div class="panel panel-default">
  <div class="panel-body">
    
       <div class="row">
        <div class="col-md-2">
            <label>Fecha inicio:</label>
            <div class="form-group">
                <div class='input-group date' id='txtinicio'>
                    <input type='text' class="form-control" name='txtinicioo'/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <label>Fecha fin:</label>
            <div class="form-group">
                <div class='input-group date' id='txtfin'>
                    <input type='text' class="form-control" name='txtfinn'/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <label>Razon:</label>
            <select name="razon" class="form-control"  id="razon" value="" >
              <?php
                $sql = "SELECT * FROM tblcausas";
                $query = mysqli_query($link, $sql);
                echo '<option value=""></option>';   
                while($row= mysqli_fetch_array($query))
                {
                  echo '<option value="'.$row['id'].'">'.$row['causa'].'</option>'; 
                }
               ?> 
            </select>
        </div>
        <div class="col-md-1" style="padding-top: 20px;">
            <input type="submit" class="btn btn-primary" name="buscar" id="buscar" value="Buscar" onClick="return Compara(this.form)"/>
        </div>
    </div>
    
  </div>
</div>
  
</form>

<div style="float: right;">
        <form action="Fichero_Excel.php" method="post" target="_blank" id="FormularioExportacion">
                <button type="button" class="btn btn-default botonExcel" data-toggle="tooltip" aria-label="Exportar Excell" title = "Exportar Excell">
                    <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>
                </button>

            <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
        </form> 
</div>
<div class="table-responsive" id="listado">
    <div class="col-md-11">
        <h3><strong>Listado de Órdenes</strong></h3>
    </div>
    
    <table width="50%" border="0" align="center" class="table">
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
      $fecha1 = $_POST['txtinicioo'];
      $fecha2 = $_POST['txtfinn'];
      
      if($fecha1=="" && $fecha2=="" && $_POST['razon']=="")
      {
         $sql="SELECT
              tblcustom_services.ponumber,
              tblcustom_services.custnumber,
              tblcustom_services.credito,
              tblcustom_services.id_orden,
              tblcausas.causa,
              tblquejas.fecha,
              tblquejas.queja,
              tblcustom_services.reenvio
              FROM
              tblcustom_services
              INNER JOIN tblcausas_devolucion ON tblcausas_devolucion.id_orden = tblcustom_services.id_custom
              INNER JOIN tblcausas ON tblcausas_devolucion.id_causa = tblcausas.id
              INNER JOIN tblquejas ON tblquejas.id_customer = tblcustom_services.id_custom
              INNER JOIN tbldetalle_orden ON tblcustom_services.id_orden = tbldetalle_orden.id_orden_detalle";   
      }
      else
      {
         $sql="SELECT
              tblcustom_services.ponumber,
              tblcustom_services.custnumber,
              tblcustom_services.credito,
              tblcustom_services.id_orden,
              tblcausas.causa,
              tblquejas.fecha,
              tblquejas.queja,
              tblcustom_services.reenvio
              FROM
              tblcustom_services
              INNER JOIN tblcausas_devolucion ON tblcausas_devolucion.id_orden = tblcustom_services.id_custom
              INNER JOIN tblcausas ON tblcausas_devolucion.id_causa = tblcausas.id
              INNER JOIN tblquejas ON tblquejas.id_customer = tblcustom_services.id_custom
              INNER JOIN tbldetalle_orden ON tblcustom_services.id_orden = tbldetalle_orden.id_orden_detalle WHERE";
      }
      $band=0;
      if(isset($_POST['txtinicioo']) && $_POST['txtinicioo']!='' && isset($_POST['txtfinn']) && $_POST['txtfinn']!='')
      {
         $sql.=" delivery_traking BETWEEN '".$fecha1."' AND '".$fecha2."'"; 
         $band=1;
      }
      else if(isset($_POST['txtinicioo']) && $_POST['txtinicioo']!='')
      {
         $sql.=" delivery_traking>='".$fecha1."'";  
         $band=1;
      }
      else if(isset($_POST['txtfinn']) && $_POST['txtfinn']!='')
      {
         $sql.=" delivery_traking<='".$fecha2."'"; 
         $band=1;
      }
      if(isset($_POST['razon']) && $_POST['razon']!="" && $band==1)
      {
         $sql.=" AND tblcausas.id='".$_POST['razon']."'"; 
      }
      else if(isset($_POST['razon']) && $_POST['razon']!="" && $band==0)
      {
         $sql.=" tblcausas.id='".$_POST['razon']."'"; 
      }
      
      $sql.=" ORDER BY tblquejas.fecha DESC";
      
      $val = mysqli_query($link, $sql);
      if(!$val){
            echo "<tr><td>".mysqli_error()."</td></tr>";
      }else{
              $cant = 0;
              while($row = mysqli_fetch_array($val)){
                  $cant ++;
                  echo "<tr>";
                  echo "<td align='center'>".$row['fecha']."</td>";
                  echo "<td align='center' style='mso-number-format:\"@\"'><strong>".$row['ponumber']."</strong></td>";
                  echo "<td align='center'><strong>".$row['custnumber']."</strong></td>";
                  echo "<td>".$row['queja']."</td>";
                  echo "<td align='center'><strong>".$row['credito']."</strong></td>";
                  echo "<td align='center'>".$row['reenvio']."</td>";
                  echo "<td align='center'>".$row['causa']."</td>";
                  echo "</tr>";
              }
              echo "<tr>
                      <td align='right'><strong>".$cant."</strong></td>
                      <td>Órden(es) encontradas</td>
                  </tr>";						
      }
      mysqli_close($conection);
    }
    ?>
    </table>

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
</span>
</div>
</body>
</html>

