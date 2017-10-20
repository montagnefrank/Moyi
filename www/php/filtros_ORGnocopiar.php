<?php
session_start();
$user     =  $_SESSION["login"];
$passwd   =  $_SESSION["passwd"];
$rol      =  $_SESSION["rol"];

include ("conectarSQL.php");
include('conexion.php');
include ("seguridad.php");

	if($_SESSION['rol'] == 3){
		header("Location: filtros_fincas.php");
	}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ver Órdenes</title>

  <link rel="icon" type="image/png" href="../images/favicon.ico" />
  <link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
  <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/octicons.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/zenburn.css" rel="stylesheet" type="text/css">
  <link href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1"rel="stylesheet" type="text/css" media="all"  />
  <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
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
  <script type="text/javascript">
   $(document).ready(function(){
  		//tol-tip-text
  		$(function () {
  		  $('[data-toggle="tooltip"]').tooltip();
  		});
   });
  	
  </script>
    <script language="javascript">
    
    function Compara(frmFec)
  {
      var Anio = (frmFec.txtinicio.value).substr(0,4)
      var Mes = ((frmFec.txtinicio.value).substr(5,2))*1 
      var Dia = (frmFec.txtinicio.value).substr(8,2)
      var Anio1 = (frmFec.txtfin.value).substr(0,4)
      var Mes1 = ((frmFec.txtfin.value).substr(5,2))*1 
      var Dia1 = (frmFec.txtfin.value).substr(8,2)
      var Fecha_Inicio = new Date(Anio,Mes,Dia)
      var Fecha_Fin = new Date(Anio1,Mes1,Dia1)

    if(Anio == '' || Anio1 == ''|| Mes == '' || Mes1 == '' || Dia == '' || Dia1 == '' )
      {
        alert("Las fechas no deben ser vacias; Ingrese correctamente")
  	  return false;
       }

  	 
      if(Fecha_Inicio > Fecha_Fin)
      {
        alert("La fecha de inicio es mayor; Ingrese correctamente")
  	  return false;
       }
      else
      {
        return true;
       }
  }
  function Compara1(frmFec)
  {
      var Anio = (frmFec.txtinicio1.value).substr(0,4)
      var Mes = ((frmFec.txtinicio1.value).substr(5,2))*1
      var Dia = (frmFec.txtinicio1.value).substr(8,2)
      var Anio1 = (frmFec.txtfin1.value).substr(0,4)
      var Mes1 = ((frmFec.txtfin1.value).substr(5,2))*1
      var Dia1 = (frmFec.txtfin1.value).substr(8,2)
      var Fecha_Inicio = new Date(Anio,Mes,Dia)
      var Fecha_Fin = new Date(Anio1,Mes1,Dia1)
   
    if(Anio == '' || Anio == ''|| Mes == '' || Mes1 == '' || Dia == '' || Dia1 == '' )
      {
        alert("Las fechas no deben ser vacias; Ingrese correctamente")
  	  return false;
       }

  	 
      if(Fecha_Inicio > Fecha_Fin)
      {
        alert("La fecha de inicio es mayor; Ingrese correctamente")
  	  return false;
       }
      else
      {
        return true;
       }
  }
  function Compara2(frmFec)
  {
      var Anio = (frmFec.txtinicio2.value).substr(0,4)
      var Mes = ((frmFec.txtinicio2.value).substr(5,2))*1
      var Dia = (frmFec.txtinicio2.value).substr(8,2)
      var Anio1 = (frmFec.txtfin2.value).substr(0,4)
      var Mes1 = ((frmFec.txtfin2.value).substr(5,2))*1
      var Dia1 = (frmFec.txtfin2.value).substr(8,2)
      var Fecha_Inicio = new Date(Anio,Mes,Dia)
      var Fecha_Fin = new Date(Anio1,Mes1,Dia1)
   
    if(Anio == '' || Anio == ''|| Mes == '' || Mes1 == '' || Dia == '' || Dia1 == '' )
      {
        alert("Las fechas no deben ser vacias; Ingrese correctamente")
  	  return false;
       }

  	 
      if(Fecha_Inicio > Fecha_Fin)
      {
        alert("La fecha de inicio es mayor; Ingrese correctamente")
  	  return false;
       }
      else
      {
        return true;
       }
  }

  function Compara3(frmFec)
  {
      var Anio = (frmFec.txtinicio0.value).substr(0,4)
      var Mes = ((frmFec.txtinicio0.value).substr(5,2))*1
      var Dia = (frmFec.txtinicio0.value).substr(8,2)
      var Anio1 = (frmFec.txtfin0.value).substr(0,4)
      var Mes1 = ((frmFec.txtfin0.value).substr(5,2))*1
      var Dia1 = (frmFec.txtfin0.value).substr(8,2)
      var Fecha_Inicio = new Date(Anio,Mes,Dia)
      var Fecha_Fin = new Date(Anio1,Mes1,Dia1)
   
    if(Anio == '' || Anio == ''|| Mes == '' || Mes1 == '' || Dia == '' || Dia1 == '' )
      {
        alert("Las fechas no deben ser vacias; Ingrese correctamente")
  	  return false;
       }

  	 
      if(Fecha_Inicio > Fecha_Fin)
      {
        alert("La fecha de inicio es mayor; Ingrese correctamente")
  	  return false;
       }
      else
      {
        return true;
       }
  }
    </script>  
  <style>
    .contenedor {
         margin:0 auto;
         width:85%;
         text-align:center;
    }
  </style>
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
        <?php
	if($rol!=6){
    echo '<a class="navbar-brand" href="../main.php?panel=mainpanel.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>';
	}else{
		echo '<a class="navbar-brand" href="services.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>';
		}
	?>
              
      </div>
 
  <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
 <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
               <?php
			  if($rol == 3 || $rol == 8){?>
			  	 <li><a href="../php/subirTracking1.php"><strong>Cargar Tracking</strong></a></li>
			  <?php }?> 
 
			  <?php 
              if($rol<= 2){ ?>
                   <li class="dropdown" >
                        <a tabindex="0" data-toggle="dropdown">
                          <strong>Venta</strong><span class="caret"></span>
                        </a>
                         <ul class="dropdown-menu" role="menu">
                              <li><a href="crearorden.php"><strong>Punto de Venta</strong></a></li>
                            <li class="divider"></li>
                            <li class="dropdown-submenu">
                                <a tabindex="0" data-toggle="dropdown"><strong>Cargar</strong></a>            <ul class="dropdown-menu">
                                	<li><a href="../php/subirOrden.php"><strong>Cargar Órdenes</strong></a></li>
                                        <li class="divider"></li>
                                        <li><a href="../php/subirTracking1.php"><strong>Cargar Tracking</strong></a></li>
                                </ul>
                            </li>
                      </ul>
                  </li>
                      <?php
				}
                       ?>
              <li class="active" >
                   <a tabindex="0" data-toggle="dropdown">
                   	<strong>Órdenes</strong><span class="caret"></span>
                   </a>
                 <ul class="dropdown-menu" role="menu">
                     <li><a href="filtros.php"><strong>Ver Órdenes</strong></a>
                     <li class="divider"></li>		
                 <?php
                      if($rol<= 2) { ?>
                    <li class="dropdown-submenu">
                    	<a tabindex="0" data-toggle="dropdown"><strong>Gestionar Órdenes</strong></a>
                        <ul class="dropdown-menu">
                             <li><a href="gestionarordenes.php" ><strong>Modificar Órdenes</strong></a></li> 
                             <li class="divider"></li>
                             <li><a href="reenvioordenes.php" ><strong>Reenviar Órdenes</strong></a></li>
                       </ul>
                    </li>
                    <?php
				    }
                    ?>
                    <li class="divider"></li>
                    <li><a href="filtros.php?accion=buscarPO"><strong>Buscar PO</strong></a>
                </ul>
              </li>
              
              <?php
			  if($rol<= 2 || $rol == 3){?>
				  <li class="dropdown">
                  	<a tabindex="0" data-toggle="dropdown">
                    	<strong>Registro</strong><span class="caret"></span>
                    </a>
				   <ul class="dropdown-menu" role="menu">
					  <li><a href="crearProductos.php"><strong>Registro de Productos</strong></a></li>                      					
                    <?php
                    if($rol <= 2){ ?>
                    <li class="divider"></li>
					  <li><a href="crearClientes.php" ><strong>Registro de Clientes</strong></a></li>
                      <?php 
					}
					  ?>
                      <?php
                      if($rol <= 2){ ?>
                      <li class="divider"></li>					  
					  <li><a href="crearFincas.php" ><strong>Registro de Fincas</strong></a></li>
                       <?php 
					}
					  ?>
                       <?php
                      if($rol <= 2){ ?>
                      <li class="divider"></li>
					  <li><a href="crearagencia.php" ><strong>Registro de Agencias de Vuelo</strong></a></li>
                       <?php 
					}
					  ?>
                      
				  </ul>
                 </li>
			 <?php 
			  }
			  ?>
              <?php
              if($rol<= 2) {?>				 
			     	<li class="dropdown">
                         <a tabindex="0" data-toggle="dropdown">
                             <strong>Pedidos</strong><span class="caret"></span>
                         </a>
                        <ul class="dropdown-menu" role="menu">
                             <li><a href="asig_etiquetas.php" ><strong>Hacer Pedido</strong></a></li>		
                             <li class="divider"></li>			
                             <li><a href="verdae.php" ><strong>Ver DAE</strong></a></li>
                        </ul>
                    </li>				 
				<?php
				  }
				  ?>
                  
                 <?php if($rol<= 2) {?> 
			     	<li><a href="mainroom.php"><strong>Cuarto Frío</strong></a></li>     			   					<li><a href="services.php" ><strong>Clientes</strong></a></li> 
                     
                    <li><a href="administration.php">
                             <strong>EDI</strong>
                         </a>  

                      <!--   <a tabindex="0" data-toggle="dropdown">
                             <strong>Contabilidad</strong><span class="caret"></span>
                         </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="administration.php"><strong>Contabilidad</strong></a></li>                      
                            <li class="divider"></li>         
                            <li><a href="contabilidad.php"><strong>Contabilidad ECU</strong></a></li>
                       </ul>   -->
                    </li>	
                 <?php
				 }
				 ?>
                 
                 <?php if($rol == 1){  ?>
			     	<li ><a href="usuarios.php"><strong>Usuarios</strong></a></li>
				<?php }else{
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

<form id="form1" name="form1" method="post">


<ul class="nav nav-tabs nav-justified">
  <li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
      <strong>Por Fecha</strong> <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
      	<li><a href="#" onclick="mostrar('new')">Nuevas</a></li>
        <li><a href="#" onclick="mostrar('ordate')">Fecha de Orden</a></li>
        <li><a href="#" onclick="mostrar('shipto')">Fecha de Vuelo</a></li>
        <li><a href="#" onclick="mostrar('deliver')">Fecha de Entrega</a></li>
    </ul>
  </li>

  <li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
      <strong>Por Detalles</strong> <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
    	 <li><a href="#" onclick="mostrar('tracking')">Por Tracking</a></li>
         <li><a href="#" onclick="mostrar('ponumber')">Por Ponumber</a></li>
         <li><a href="#" onclick="mostrar('custnumber')">Por CustNumber</a></li>
         <li><a href="#" onclick="mostrar('item')">Por Producto</a></li>
    </ul>
  </li>

  <li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
      <strong>Clientes</strong> <span class="caret"></span>
    </a>
 

    <ul class="dropdown-menu">
    	<li><a href="#" onclick="mostrar('shipto1')">Nombre del Receptor</a></li>
        <li><a href="#" onclick="mostrar('direccion')">Dirección del Receptor</a></li>
        <li><a href="#" onclick="mostrar('soldto1')">Nombre del Comprador</a></li>
        <li><a href="#" onclick="mostrar('cpdireccion_soldto')">Dirección del Comprador</a></li>
    </ul>
  </li>
  <li><a href="#"><strong><input name="pais" type="radio" value="US" checked="checked"/>E.U</strong></a>
  </li>
             <li><a href="#"><strong><input name="pais" type="radio" value="CA" />CA</strong></a>
             </li>
              <li><a href="#"><strong><input name="origen" type="radio" value="UIO-ECUADOR" />UIO</strong></a>
  </li>
             <li><a href="#"><strong><input name="origen" type="radio" value="GYE-ECUADOR" />GYE</strong></a>
             </li>
             <li><a href="#"><strong><input name="origen" type="radio" value="BOG-COLOMBIA" />BOG</strong></a>
  </li>
             <li><a href="#"><strong><input name="origen" type="radio" value="MED-COLOMBIA" />MED</strong></a>
             </li>
</ul>
<?php
		if($error == 2){
        	echo '<div class="alert alert-danger" role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Error:</span>
  ¡Error!, al crear la orden, por favor revise los datos.
</div>';
		}else{
			if($error == 1)
			echo '<div class="alert alert-success" role="alert">
  <span class="glyphicon glyphicon-su" aria-hidden="true"></span>
  <span class="sr-only">Éxito:</span>
  ¡Orden creada satisfactoriamente!</div>';
		}
?>

<div class="table-responsive">
  <table width="50%" border="0" align="center" class="table table-responsive">
      <tr id="new" style='display:none;'>
      <td> 
      
		  <p>&nbsp;</p>
		  <p><strong>Fecha Inicio:</strong></p></td>
		   <td width="603"><p><strong>BUSCAR ÓRDENES NUEVAS</strong></p>
            <input name="txtinicio0" type="text" id="txtinicio0" readonly="readonly" />
          <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtinicio0");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtinicio0",   // id of the input field
        ifFormat       :    "%Y-%m-%d ",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

               </script>
		       <strong>Fecha Fin:</strong>
		       <input name="txtfin0" type="text" id="txtfin0" readonly="readonly" />
          <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtfin0");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtfin0",   // id of the input field
        ifFormat       :    "%Y-%m-%d",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

               </script>
          <input type="submit" name="enviar0" id="enviar0" value="Buscar"  onclick="Compara3(this.form)" class="btn-primary" title="Buscar" />
        </p>
        <p>&nbsp;</p>
        </td>
    </tr>
      <tr id="ordate" style='display:none;'>
        <td> 
		  <p>&nbsp;</p>
		  <p><strong>Fecha Inicio:</strong></p></td>
		   <td><p><strong>BUSCAR ÓRDENES POR FECHA DE ÓRDEN</strong></p>
<p>
          <input name="txtinicio" type="text" id="txtinicio" readonly="readonly" />
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
		       <input name="txtfin" type="text" id="txtfin" readonly="readonly" />
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
        ifFormat       :    "%Y-%m-%d",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

          </script>
          <input type="submit" name="enviar" id="enviar" value="Buscar"  onclick="Compara(this.form)"  class="btn-primary" />
	         </p>
	      </strong></p>              
        </td>
      </tr>
       <tr id="shipto" style='display:none;'   >
        <td> 
	     <p>&nbsp;</p>
	     <p><strong>Fecha Inicio:</strong></p></td>
		   <td> 
          <p><strong>BUSCAR ÓRDENES POR FECHA DE VUELO</strong></p>
<p>
          <input name="txtinicio1" type="text" id="txtinicio1" readonly="readonly" />
          <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtinicio1");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtinicio1",   // id of the input field
        ifFormat       :    "%Y-%m-%d ",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

          </script>
            <strong>Fecha Fin:</strong>
            <input name="txtfin1" type="text" id="txtfin1" readonly="readonly" />
          <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtfin1");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtfin1",   // id of the input field
        ifFormat       :    "%Y-%m-%d",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

        </script>
        <input type="submit" name="enviar1" id="enviar1" value="Buscar"  onclick="Compara1(this.form)"  class="btn-primary" /></p>    <!--change by me org Compara2 -->
        </strong>              
        </td>
      </tr>
      <tr id="deliver" style='display:none;'   >
        <td> 
	    <p>&nbsp;</p>
	    <p><strong>Fecha Inicio:</strong></p></td>
		   <td> 
          <p><strong>BUSCAR ÓRDENES POR FECHA DE ENTREGA</strong></p>
          <p>
            <input name="txtinicio2" type="text" id="txtinicio2" readonly="readonly" />
            <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtinicio2");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtinicio2",   // id of the input field
        ifFormat       :    "%Y-%m-%d ",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

          </script>
            <strong>Fecha Fin:</strong>
            <input name="txtfin2" type="text" id="txtfin2" readonly="readonly" />
            <script type="text/javascript">
    function catcalc(cal) {
        var date = cal.date;
        var time = date.getTime()
        // use the _other_ field
        var field = document.getElementById("f_calcdate");
        if (field == cal.params.inputField) {
            field = document.getElementById("txtfin2");
            time -= Date.WEEK; // substract one week
        } else {
            time += Date.WEEK; // add one week
        }
        var date2 = new Date(time);
        field.value = date2.print("%Y-%m-%d");
    }
    Calendar.setup({
        inputField     :    "txtfin2",   // id of the input field
        ifFormat       :    "%Y-%m-%d",       // format of the input field
        showsTime      :    false,
        timeFormat     :    "24",
        onUpdate       :    catcalc
    });

        </script>
            <input type="submit" name="enviar2" id="enviar2" value="Buscar"  onclick="Compara2(this.form)"  class="btn-primary" />
          </p>
        </strong>              
        </td>
      </tr>
      <tr id="tracking" style='display:none;'   >
        <td> 
	    <p>&nbsp;</p>
	    <p><strong>Tracking:</strong></p></td>
		   <td> 
           <p><strong>BUSCAR ÓRDENES POR  TRACKING</strong></p>
          <p>
          <input name="tracking" type="text" id="idtracking" />
         <!-- <input name="alltrack" id="alltrack" type="checkbox" value="" onchange="deshabilitar()"/>All Trackings-->
          <input type="submit" name="enviar3" id="enviar3" value="Buscar" class="btn-primary"/>
        </strong>              
          <p></td>
      </tr>
      <tr id="ponumber" style='display:none;'   >
        <td> 
	    <p>&nbsp;</p>
	    <p><strong>Ponumber:</strong></p></td>
		   <td>
           <p><strong>BUSCAR ÓRDENES POR PONUMBER</strong></p>
          <p> 
          <input name="ponumber" type="text" id="ponumber"  />
          <input type="submit" name="enviar4" id="enviar4" value="Buscar" class="btn-primary"/>
        </strong>              
          <p></td>
      </tr>
      <tr id="custnumber" style='display:none;'   >
        <td> 
	    <p>&nbsp;</p>
	    <p><strong>Custnumber:</strong></p></td>
		   <td> 
           <p><strong>BUSCAR ÓRDENES POR CUSTNUMBER</strong></p>
          <p>
          <input name="custnumber" type="text" id="custnumber"  />
        <input type="submit" name="enviar6" id="enviar6" value="Buscar" class="btn-primary"/>
        </strong>              
          <p></td>
      </tr>
      <tr id="item" style='display:none;'>
        <td> 
	    <p>&nbsp;</p>
	    <p><strong>Producto:</strong></p></td>
	    <td> 
        <p><strong>BUSCAR ÓRDENES POR PRODUCTO</strong></p>
           <p>
             <input name="item" type="text" id="item" />
             <input type="submit" name="enviar5" id="enviar5" value="Buscar" class="btn-primary"/>
             </strong>              
        </p>
        <p>&nbsp;</p></td>
      </tr>
      <tr id="farm" style='display:none;'   >
       <td> 
		<p><strong>Finca:</strong></td>
	    <td> 
        <p><strong>BUSCAR ÓRDENES POR FINCA</strong></p>
		  <p>
		    <input name="farm" type="text" id="farm" />
		    <input type="submit" name="enviar7" id="enviar7" value="Buscar" class="btn-primary"/>
		    </strong></p>
		  <p>&nbsp;</p>
        </p></td>
      </tr>
       <tr id="shipto1" style='display:none;' >
     	<td>
        <p><strong>Nombre del Receptor:</strong></td>
		   <td> 
           <p><strong>BUSCAR ÓRDENES POR NOMBRE DEL RECEPTOR</strong></p>
           <p>
		     <input name="shipto1" type="text" id="shipto1" />
		     <input type="submit" name="enviar8" id="enviar8" value="Buscar" class="btn-primary"/>
		     </strong></p>
           <p>&nbsp;</p>
		   </p></td>
      </tr>
      <tr id="direccion" style='display:none;'   >
     <td> 
		<p>        
		<p><strong>Dirección  del Receptor:</strong></td>
		   <td> 
           <p><strong>BUSCAR ÓRDENES POR DIRECCIÓN DEL RECEPTOR</strong></p>
		   <p>
		     <input name="direccion" type="text" id="direccion" />
		     <input type="submit" name="enviar9" id="enviar9" value="Buscar" class="btn-primary"/>
		     </strong></p>
		   <p>&nbsp;</p>
		   </p></td>
      </tr>
       <tr id="soldto1" style='display:none;'   >
     <td> 
		<p>        
		<p><strong>Nombre del Comprador:</strong></td>
		   <td> 
           <p><strong>BUSCAR ÓRDENES POR NOMBRE DEL COMPRADOR</strong></p>
		   <p>
		     <input name="soldto1" type="text" id="soldto1" />
		     <input type="submit" name="enviar10" id="enviar10" value="Buscar" class="btn-primary"/>
		     </strong></p>
		   <p>&nbsp;</p>
		   </p></td>
      </tr>
      <tr id="cpdireccion_soldto" style='display:none;'   >
     <td> 
		<p>        
		<p>        
		<p><strong>Dirección del Comprador:</strong></td>
		   <td> 
           <p><strong>BUSCAR ÓRDENES POR DIRECCIÓN DEL COMPRADOR</strong></p>
		   <p>
		     <input name="cpdireccion_soldto" type="text" id="cpdireccion_soldto" />
		     <input type="submit" name="enviar11" id="enviar11" value="Buscar" class="btn-primary"/>
		     </p>
	    </strong></p></td>
  </tr>
  </table>
  </div> <!-- /table-responsive --> 
  </form>
</div> <!-- /table-responsive --> 
           <div class="panel-heading">
              <div class="contenedor" align="center">
                <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2015 versión 3</strong>
                <br>
                <strong><a href="http://www.bit-store.ec/index.php/contactenos/"  style="color:white">Info</a> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></strong>
              </div>
           </div>

  <?php 
  //Si se oprimio el boton buscar de new
   if(isset($_POST['enviar0']))
  {	  
  	 
	 $fecha_inicio0=$_POST["txtinicio0"];
	 $fecha_fin0   =$_POST["txtfin0"];
	 $pais        =$_POST["pais"];
	 $origen      = $_POST["origen"];
	 $_SESSION["origen"]=$origen;
	 $_SESSION["pais"]=$pais;
	 $_SESSION["inicio0"]=$fecha_inicio0;
	 $_SESSION["fin0"]=$fecha_fin0;	
	 $_SESSION["login"] = $user ;
	 $url="repor_excel.php"; 
     echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
  
  //Si se oprimio el boton buscar de orderdate
   if(isset($_POST['enviar']))
  {	  
  	 
	 $fecha_inicio=$_POST["txtinicio"];
	 $fecha_fin   =$_POST["txtfin"];
	 $pais        =$_POST["pais"];
	 $origen      = $_POST["origen"];
	 $_SESSION["origen"]=$origen;
	 $_SESSION["pais"]=$pais;
	 $_SESSION["inicio"]=$fecha_inicio;
	 $_SESSION["fin"]=$fecha_fin;	
	 $_SESSION["login"] = $user ;
	 $url="repor_excel.php"; 
     echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
  
  //Si se oprimio el boton buscar de shipdt
  if(isset($_POST['enviar1']))
  {	  
	  $fecha_inicio1=$_POST["txtinicio1"];
	  $fecha_fin1=$_POST["txtfin1"];
	  $pais        =$_POST["pais"];
	  $origen      = $_POST["origen"];
	  $_SESSION["origen"]=$origen;
	  $_SESSION["pais"]=$pais;
	  $_SESSION["inicio1"]=$fecha_inicio1;
	  $_SESSION["fin1"]=$fecha_fin1;
	  $_SESSION["login"] = $user ;
	  $url="repor_excel.php"; 
       echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
  
  //Si se oprimio el boton buscar de deliver fecha de entrega
   if(isset($_POST['enviar2']))
  {	  
	  $fecha_inicio1=$_POST["txtinicio2"];
	  $fecha_fin1=$_POST["txtfin2"];
	  $pais        =$_POST["pais"];
	  $origen      = $_POST["origen"];
	  $_SESSION["origen"]=$origen;
	  $_SESSION["pais"]=$pais;
	  $_SESSION["inicio2"]=$fecha_inicio1;
	  $_SESSION["fin2"]=$fecha_fin1;
	  $_SESSION["login"] = $user ;
	  $url="repor_excel.php"; 
       echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
  
  //Si se oprimio el boton buscar de tracking
    if(isset($_POST['enviar3']))
  {	  
	  $tracking         =$_POST["tracking"];
	  $alltrack         = $_POST["alltrack"];
	  $pais             =$_POST["pais"];
	  $origen            = $_POST["origen"];
	  $_SESSION["origen"]=$origen;
	  $_SESSION["pais"]  =$pais;
	  $_SESSION["tracking"]=$tracking;
	  $_SESSION["login"] = $user ;
	  $_SESSION["alltrack"] = $alltrack ;
	  $url="repor_excel.php"; 
       echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
  
   //Si se oprimio el boton buscar de ponumber
    if(isset($_POST['enviar4']))
  {	  
	  $ponumber=$_POST["ponumber"];
	  $pais        =$_POST["pais"];
	  $origen      = $_POST["origen"];
	  $_SESSION["origen"]=$origen;
	  $_SESSION["pais"]=$pais;
	  $_SESSION["ponumber"]=$ponumber;
	  $_SESSION["login"] = $user ;
	  $url="repor_excel.php"; 
       echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
  
     //Si se oprimio el boton buscar de ponumber
    if(isset($_POST['enviar5']))
  {	   
	  $item=$_POST["item"];
	  $pais        =$_POST["pais"];
	  $origen      = $_POST["origen"];
	  $_SESSION["origen"]=$origen;
	  $_SESSION["pais"]=$pais;
	  $_SESSION["item"]=$item;
	  $_SESSION["login"] = $user ;
	  $url="repor_excel.php"; 
       echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
   //Si se oprimio el boton buscar de ponumber
    if(isset($_POST['enviar6']))
  {	  
	  $custnumber=$_POST["custnumber"];
	  $pais        =$_POST["pais"];
	  $origen      = $_POST["origen"];
	  $_SESSION["origen"]=$origen;
	  $_SESSION["pais"]=$pais;
	  $_SESSION["custnumber"]=$custnumber;
	  $_SESSION["login"] = $user ;
	  $url="repor_excel.php"; 
       echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
   //Si se oprimio el boton buscar de ponumber
    if(isset($_POST['enviar7']))
  {	  
	  $farm=$_POST["farm"];
	  $pais        =$_POST["pais"];
	  $origen      = $_POST["origen"];
	  $_SESSION["origen"]=$origen;
	  $_SESSION["pais"]=$pais;
	  $_SESSION["farm"]=$farm;
	  $_SESSION["login"] = $user ;
	  $url="repor_excel.php"; 
       echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
   //Si se oprimio el boton buscar de shipto1
    if(isset($_POST['enviar8']))
  {	  
	  $shipto1=$_POST["shipto1"];
	  $pais        =$_POST["pais"];
	  $origen      = $_POST["origen"];
	  $_SESSION["origen"]=$origen;
	  $_SESSION["pais"]=$pais;
	  $_SESSION["shipto1"]=$shipto1;
	  $_SESSION["login"] = $user ;
	  $url="repor_excel.php"; 
       echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
   //Si se oprimio el boton buscar de direccion
    if(isset($_POST['enviar9']))
  {	  
	  $direccion=$_POST["direccion"];
	  $pais        =$_POST["pais"];
	  $origen      = $_POST["origen"];
	  $_SESSION["origen"]=$origen;
	  $_SESSION["pais"]=$pais;
	  $_SESSION["direccion"]=$direccion;
	  $_SESSION["login"] = $user ;
	  $url="repor_excel.php"; 
       echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
   //Si se oprimio el boton buscar de soldto1
    if(isset($_POST['enviar10']))
  {	  
	  $soldto1=$_POST["soldto1"];
	  $pais        =$_POST["pais"];
	  $origen      = $_POST["origen"];
	  $_SESSION["origen"]=$origen;
	  $_SESSION["pais"]=$pais;
	  $_SESSION["soldto1"]=$soldto1;
	  $_SESSION["login"] = $user ;
	  $url="repor_excel.php"; 
       echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
   //Si se oprimio el boton buscar de direccion
    if(isset($_POST['enviar11']))
  {	  
	  $cpdireccion_soldto=$_POST["cpdireccion_soldto"];
	  $pais        =$_POST["pais"];
	  $origen      = $_POST["origen"];
	 $_SESSION["origen"]=$origen;
	 $_SESSION["pais"]=$pais;
	 $_SESSION["cpdireccion_soldto"]=$cpdireccion_soldto;
	 $_SESSION["login"] = $user ;
	  $url="repor_excel.php"; 
       echo "<SCRIPT>window.location='$url';</SCRIPT>";
  }
 
  ?>
  </div> <!-- /container -->
  <script type="text/javascript">
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1
    });
	$('.form_date').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
    });
	$('.form_time').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		forceParse: 0
    });
</script>
</body>
</html>
