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
	<title>Pagos y Créditos Manuales</title>
	<script type="text/javascript" src="../js/script.js"></script>
	<script language="javascript" src="../js/imprimir.js"></script>
	<link rel="icon" type="image/png" href="../images/favicon.ico" />
	<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" media="all" href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
	<link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
	<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
	<script type="text/javascript" src="../js/calendar.js"></script>
	<script type="text/javascript" src="../js/calendar-en.js"></script>
	<script type="text/javascript" src="../js/calendar-setup.js"></script>

<link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
<link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/octicons.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/zenburn.css" rel="stylesheet" type="text/css">
<!-- <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">  -->

<script language="javascript" src="../js/imprimir.js"></script>
<script type="text/javascript" src="../js/script.js"></script>
<script src="../bootstrap/js/jquery.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
<script src="../bootstrap/js/bootstrap-submenu.js"></script>
<script src="../bootstrap/js/docs.js" defer></script>

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
		
		  if(fecha1 =='' && fecha2 =='')
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

	function pagar(valor){
		var v=valor;
		window.open("pagar.php?codigo="+v,"Cantidad","width=300,height=150,top=300,left=400");
		return false;
	}
	function credito(valor){
		var v=valor;
		window.open("crearcredito.php?codigo="+v,"Cantidad","width=300,height=150,top=300,left=400");
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
        <li class="dropdown active">
          <a tabindex="0" data-toggle="dropdown">
            <strong>Pagos</strong><span class="caret"></span>
          </a>
          <ul class="dropdown-menu" role="menu">
              <li><a href="subircostco.php"><strong>Subir Archivo de Costo</strong></a></li>
              <li class="divider"></li>
              <li class="active"><a href="pagosycreditos.php"><strong>Pagos y Créditos Manuales</strong></a></li>
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

<div class="panel-body" align="center">
  <div class="table-responsive">
   <table width="50%" border="0" align="center" class="table table-responsive">
	   	<tr>
	  		<td>
	    		<form method="post" id="frm1" name="frm1" target="_parent" >
	              <div class="table-responsive">
	            	<table width="50%" border="0" align="center" class="table table-responsive">
	  					<tr>
					        <td  colspan="5" align="center"><span id="result_box" lang="en" xml:lang="en"><strong>BUSCAR ÓRDENES PAGADAS Y SIN PAGO DE COSTCO</strong></span></td>
	    				</tr>
	  					<tr>
			  				<td width="314" align="right"> 
			              		<strong>Fecha Inicio:</strong>
			               		<input name="txtinicio" type="text" id="txtinicio" readonly="readonly" size="20"/>
			                </td>
			                <td width="290">
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
			              		<strong>Fecha Fin:</strong>
			               		<input name="txtfin" type="text" id="txtfin" readonly="readonly" size="20"/>
			                </td>
			                <td width="242">
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
							  	<input name="tipo" type="radio" value="1" checked="checked" />Con pago
			                 	<input name="tipo" type="radio" value="0" />Sin pago
			                </td>
			                <td width="160">
			                	<input type="submit" name="buscar" id="buscar" value="Buscar" onclick="return Compara(this.form)" class="btn-primary"/>
			                </td>
			              </tr>
			        </table>
			        </div>    <!-- table responsive -->
			    </form>
			</td>
	  	</tr>
	    <tr>
		    <td id="inicio" bgcolor="" height="100"> 
			  <div class="table-responsive">
			    <table width="50%" border="0" align="center" class="table table-responsive"> 
					<?php 
					   if(isset ($_POST['buscar'])){
					   //Si se oprimio el boton de buscar
							  $fecha1 = $_POST['txtinicio'];
							  $fecha2 = $_POST['txtfin'];
							  $tipo   = $_POST['tipo'];
							  
							  //Verifico que opcion se selecciono
							  if($tipo ==1){
								  //echo "Con pago";
										//Consultar las pagadas en la fecha dada
										$sql   = "SELECT DISTINCT vendor,po,costo,credito,pago FROM tblcosto INNER JOIN tbldetalle_orden ON tblcosto.po = tbldetalle_orden.Ponumber where fecha_facturacion BETWEEN '".$fecha1."' AND '".$fecha2."' AND pagado='Si' order by vendor";
										$query = mysqli_query($link, $sql) or die ("Error al consultar las canceladas");
										echo '<tr>
											<td colspan="12" align="center">
												<h3><strong>Listado de Órdnes pagadas</strong></h3>
											</td>
										</tr>';
										echo "<tr bgcolor='#E8F1FD'>";
											echo '<td align="center">#</td>';
											echo '<td align="center">Cliente</td>';
											echo '<td align="center">Ponumber</td>';
											echo '<td align="center">Costo</td>';
											echo '<td align="center">Diferencia</td>';
											echo '<td align="center">Pago</td>';
				 						echo "</tr>";
										$cont  = 0;
										$total = 0;
										while($row = mysqli_fetch_array($query)){		
											$cont++;	
											echo "<tr>";
												echo '<td align="center">'.$cont.'</td>';
												echo '<td align="center">'.$row['vendor'].'</td>';
												echo '<td align="center">'.$row['po'].'</td>';
												echo '<td align="center">'.number_format($row['costo'],2).'</td>';		
												echo '<td align="center">'.number_format($row['credito'],2).'</td>';	
												echo '<td align="center">'.number_format($row['pago'],2).'</td>';		
												$total += $row['costo'];
												$credito += $row['credito'];
												$pago += $row['pago'];
										}
										echo "<tr>";
												echo '<td colspan="3" align="right"><strong>Total:</strong></td>';
												echo '<td align="center"><strong>'.number_format($total,2).'</strong></td>';	
												echo '<td align="center"><strong>'.number_format($credito,2).'</strong></td>';
												echo '<td align="center"><strong>'.number_format($pago,2).'</strong></td>';	
											echo "</tr>";
										mysqli_close($conection);		  
								  
							  }else{
								  //echo "Sin pago";
								  //Consultar las pagadas en la fecha dada
										$sql   = "SELECT DISTINCT vendor,id_costo,po,costo,credito,pago FROM tblcosto INNER JOIN tbldetalle_orden ON tblcosto.po = tbldetalle_orden.Ponumber where fecha_facturacion BETWEEN '".$fecha1."' AND '".$fecha2."' AND pagado='No' order by vendor";
										$query = mysqli_query($link, $sql) or die ("Error al consultar las canceladas");
										echo '<tr>
											<td colspan="12" align="center">
												<h3><strong>Listado de Órdenes sin pago</strong></h3>
											</td>
										</tr>';
										echo "<tr bgcolor='#E8F1FD'>";
											echo '<td align="center">#</td>';
											echo '<td align="center">Cliente</td>';
											echo '<td align="center">Ponumber</td>';
											echo '<td align="center">Costo</td>';
											echo '<td align="center">Diferencia</td>';
											echo '<td align="center">Pago</td>';
											echo '<td align="center">Crédito</td>';
											echo '<td align="center">Pagar</td>';
				 						echo "</tr>";
										$cont  = 0;
										$total = 0;
										while($row = mysqli_fetch_array($query)){		
											$cont++;	
											echo "<tr>";
												echo '<td align="center">'.$cont.'</td>';
												echo '<td align="center">'.$row['vendor'].'</td>';
												echo '<td align="center">'.$row['po'].'</td>';
												echo '<td align="center">'.number_format($row['costo'],2).'</td>';		
												echo '<td align="center">'.number_format($row['credito'],2).'</td>';	
												echo '<td align="center">'.number_format($row['pago'],2).'</td>';
												echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/credito.png" heigth="30" value="" title = "Crear Crédito" width="20" onclick = "credito('.$row['id_costo'].')"/></td>';
							
												echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/pago.png" heigth="30" value="" title = "Pagar orden" width="20" onclick = "pagar('.$row['id_costo'].')"/></td>';		
												$total += $row['costo'];
												$credito += $row['credito'];
												$pago += $row['pago'];
										}
										echo "<tr>";
												echo '<td colspan="3" align="right"><strong>Total:</strong></td>';
												echo '<td align="center"><strong>'.number_format($total,2).'</strong></td>';	
												echo '<td align="center"><strong>'.number_format($credito,2).'</strong></td>';
												echo '<td align="center"><strong>'.number_format($pago,2).'</strong></td>';	
											echo "</tr>";
								  }
				      }
				           ?>
			    </table> 
			  </div>  <!-- table responsive -->

		  </td>
	  	</tr>
	</table>
  </div> 	<!-- table responsive --> 
  
</div> <!-- panel panel-primary -->
<div class="panel-heading">
  <div class="contenedor" align="center">
    <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2015 versión 3</strong>
    <br>
    <strong><a href="http://www.bit-store.ec/index.php/contactenos/"  style="color:white">Info</a> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></strong>
  </div>
</div>
</div> <!-- panel heading -->



</div>   <!-- /container -->

</body>
</html>

