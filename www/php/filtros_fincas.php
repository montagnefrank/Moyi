<?php
include ("conectarSQL.php");
include('conexion.php');
include ("seguridad.php");
session_start();
$user     =  $_SESSION["login"];
$passwd   =  $_SESSION["passwd"];
$bd       =  $_SESSION["bd"];
$rol      =  $_SESSION["rol"];

$conection = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());

//OBTENIENDO LA FINCA DEL USUARIO
$sql   = "SELECT finca FROM tblusuario WHERE cpuser = '".$user."'";
$query = mysql_query($sql, $conection) or die ("Error seleccionando la finca de este usuario");
$row   = mysql_fetch_array($query);
$finca = $row['finca'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <link rel="stylesheet" type="text/css" media="all" href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
  <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
  <title>Ver Órdenes</title>
    
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" media="all"  />
  
 <script language="javascript" src="../js/imprimir.js"></script>
 <script type="text/javascript" src="../js/script.js"></script>
 <script src="../bootstrap/js/jquery.min.js"></script>
 <script src="../bootstrap/js/moment.js"></script>
 <script src="../bootstrap/js/bootstrap.min.js"></script>
 <script src="../bootstrap/js/bootstrap-datetimepicker.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
 <script src="../bootstrap/js/bootstrap-submenu.js"></script>
 <script src="../bootstrap/js/bootstrap-modal.js"></script>
 <script src="../bootstrap/js/docs.js" defer></script>
 <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
  
   <style type="text/css">
      
      li a{
      	cursor:pointer;/*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
      }
      .my-error-class {
        color:red;
        font-style: italic;
        font-size: 12px;
       }
  </style>
  
  <script type="text/javascript">
   $(document).ready(function(){
    //tol-tip-text
    $(function () {
      $('[data-toggle="tooltip"]').tooltip();
    });
   
    var po= '<?php echo $_GET['accion']?>';
    if(po=='buscarPO')
    { 
        $('div#po').css('display','block');
    }
    
    $("#form1 .dropdown-menu a").click(function(event) {
        $("#form1 .panel").css('display','none');
        var link=event.target.id;
        
        $('#form1 div#'+link).css('display','block');
    });
    
    jQuery.validator.addMethod("noSpace", function (value, element) { 
      return value.indexOf(" ") < 0 && value != ""; 
    }, "No space please and don't leave it empty");

    
    $("#enviar0").click(function(event){
        if($("#pais").val()=="")
        {
            $(".my-error-class").css('display','inline');
            event.preventDefault();
            return false;
        }
        else
        {
           $("#my-error-class").css('display','none'); 
           
        }
        
        $Anio = $("#txtinicio0").val().substring(0,4);
        $Mes = ($("#txtinicio0").val().substring(5,2))*1;
        $Dia = $("#txtinicio0").val().substring(8,2);
        $Anio1 = $("#txtfin0").val().substring(0,4);
        $Mes1 = ($("#txtfin0").val().substring(5,2))*1;
        $Dia1 = $("#txtfin0").val().substring(8,2);
                
        if($Anio == '' || $Anio1 == ''|| $Mes == '' || $Mes1 == '' || $Dia == '' || $Dia1 == '' )
        {
            $("#errorFecha0").html("Las fechas ingresadas no son correctas");
            $("#errorFecha0").css("display","inline");
            return false;
        }
        else
        {
           $("#errorFecha0").css("display","none");
        }
          
        if($("#txtinicio0").val() > $("#txtfin0").val())
        {
           $("#errorFecha0").html("Las fecha de inicio es mayor que la fecha de fin.");
           $("#errorFecha0").css("display","inline");
            return false;
        }
        else
        {
          $("#errorFecha0").css("display","none");
          
        }
        return true;
    });
    
    $("#enviar").click(function(event){
        if($("#pais").val()=="")
        {
            $(".my-error-class").css('display','inline');
            event.preventDefault();
            return false;
        }
        else
        {
           $("#my-error-class").css('display','none'); 
           
        }
        
        $Anio = $("#txtinicio").val().substring(0,4);
        $Mes = ($("#txtinicio").val().substring(5,2))*1;
        $Dia = $("#txtinicio").val().substring(8,2);
        $Anio1 = $("#txtfin").val().substring(0,4);
        $Mes1 = ($("#txtfin").val().substring(5,2))*1;
        $Dia1 = $("#txtfin").val().substring(8,2);
                
        if($Anio == '' || $Anio1 == ''|| $Mes == '' || $Mes1 == '' || $Dia == '' || $Dia1 == '' )
        {
            $("#errorFecha").html("Las fechas ingresadas no son correctas");
            $("#errorFecha").css("display","inline");
            return false;
        }
        else
        {
           $("#errorFecha").css("display","none");
        }
          
        if($("#txtinicio").val() > $("#txtfin").val())
        {
           $("#errorFecha").html("Las fecha de inicio es mayor que la fecha de fin.");
           $("#errorFecha").css("display","inline");
            return false;
        }
        else
        {
          $("#errorFecha").css("display","none");
          
        }
        return true;
    });
    
    $("#enviar1").click(function(event){
        if($("#pais").val()=="")
        {
            $(".my-error-class").css('display','inline');
            event.preventDefault();
            return false;
        }
        else
        {
           $("#my-error-class").css('display','none'); 
           
        }
        
        $Anio = $("#txtinicio1").val().substring(0,4);
        $Mes = ($("#txtinicio1").val().substring(5,2))*1;
        $Dia = $("#txtinicio1").val().substring(8,2);
        $Anio1 = $("#txtfin1").val().substring(0,4);
        $Mes1 = ($("#txtfin1").val().substring(5,2))*1;
        $Dia1 = $("#txtfin1").val().substring(8,2);
                
        if($Anio == '' || $Anio1 == ''|| $Mes == '' || $Mes1 == '' || $Dia == '' || $Dia1 == '' )
        {
            $("#errorFecha1").html("Las fechas ingresadas no son correctas");
            $("#errorFecha1").css("display","inline");
            return false;
        }
        else
        {
           $("#errorFecha1").css("display","none");
        }
          
        if($("#txtinicio1").val() > $("#txtfin1").val())
        {
           $("#errorFecha1").html("Las fecha de inicio es mayor que la fecha de fin.");
           $("#errorFecha1").css("display","inline");
            return false;
        }
        else
        {
          $("#errorFecha1").css("display","none");
          
        }
        return true;
    });
    
    $("#enviar2").click(function(event){
        if($("#pais").val()=="")
        {
            $(".my-error-class").css('display','inline');
            event.preventDefault();
            return false;
        }
        else
        {
           $("#my-error-class").css('display','none'); 
           
        }
        
        $Anio = $("#txtinicio2").val().substring(0,4);
        $Mes = ($("#txtinicio2").val().substring(5,2))*1;
        $Dia = $("#txtinicio2").val().substring(8,2);
        $Anio1 = $("#txtfin2").val().substring(0,4);
        $Mes1 = ($("#txtfin2").val().substring(5,2))*1;
        $Dia1 = $("#txtfin2").val().substring(8,2);
                
        if($Anio == '' || $Anio1 == ''|| $Mes == '' || $Mes1 == '' || $Dia == '' || $Dia1 == '' )
        {
            $("#errorFecha2").html("Las fechas ingresadas no son correctas");
            $("#errorFecha2").css("display","inline");
            return false;
        }
        else
        {
           $("#errorFecha2").css("display","none");
        }
          
        if($("#txtinicio2").val() > $("#txtfin2").val())
        {
           $("#errorFecha2").html("Las fecha de inicio es mayor que la fecha de fin.");
           $("#errorFecha2").css("display","inline");
            return false;
        }
        else
        {
          $("#errorFecha2").css("display","none");
          
        }
        return true;
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
                                <a tabindex="0" data-toggle="dropdown"><strong>Cargar</strong></a>  
                                <ul class="dropdown-menu">
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
                     <li><a href="filtros_fincas.php?accion=buscarPO"><strong>Buscar PO</strong></a>
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
                    </ul>
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
          echo '<li><a href="../main.php?panel=mainpanel.php" onclick="cambiar(\''.$row[0].'\')"><strong>Contraseña</strong></a></li>';
          
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
  <form id="form1" name="form1" method="post" novalidate action="" class="form-inline">  
   <div style="margin-bottom: 10px;">
        <div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Fecha <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a id="nnew">Nuevas</a></li>
    <li><a id="oordate">Fecha de Orden</a></li>
    <li><a id="sshipto">Fecha de Vuelo</a></li>
    <li><a id="ddeliver">Fecha de Entrega</a></li>
  </ul>
 </div> 
 
        <div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Detalles <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
   <li><a id="ttracking">Por Tracking</a></li>
    <li><a id="po">Por Ponumber</a></li>
    <li><a id="ccustnumber">Por CustNumber</a></li>
    <li><a id="iitem" >Por Producto</a></li>
  </ul>
 </div> 
 
        <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Clientes <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
          <li><a id="sshipto1">Nombre del Receptor</a></li>
          <li><a id="ddireccion">Dirección del Receptor</a></li>
          <li><a id="ssoldto1">Nombre del Comprador</a></li>
          <li><a id="ccpdireccion_soldto">Dirección del Comprador</a></li>
        </ul>
        </div>  
           
        <div class="form-group">
            <label><strong>País:</strong> </label>
            <select type="text" name="pais" id="pais" class="form-control" style="width: auto;">
                  <?php 
                    //Consulto la bd para obtener solo los id de item existentes
                    $sql   = "SELECT * FROM tblpaises_destino";
                    $query = mysql_query($sql,$conection);
                      //Recorrer los iteme para mostrar
                    echo '<option value=""></option>'; 
                    while($row1 = mysql_fetch_array($query)){
                          echo '<option value="'.$row1["codpais"].'">'.$row1["pais_dest"].'</option>'; 
                        }
                  ?>    
              </select>
            <label class="my-error-class" style="display: none;">Seleccione el pais</label>
        </div>
        <div class="form-group">
            <label><strong>Consolidado:</strong> </label>
            <select type="text" name="consolidado" id="consolidado" class="form-control" style="width: auto;">
                <option value="N">No</option>
                <option value="Y">Si</option>
                <option value="Todas">Todas</option>
            </select>
        </div>
       
   </div>
  
   
<div class="panel panel-default" id="nnew" style="display: none;">
  <div class="panel-heading" >
      <h3 class="panel-title"><strong>BUSCAR ÓRDENES NUEVAS</strong></h3>
  </div>
  <div class="panel-body">
     <div class="col-md-4">
        <label>Fecha Inicio:</label>
        <div class="input-group date" style="width: auto;" id="datetimepicker1">
            <input type='text' class="form-control" name="txtinicio0" id="txtinicio0" value="<?php echo date ('Y-m-d')?>" placeholder="Fecha inicio"/>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
        <script type="text/javascript">
            $(function () {
                $('#datetimepicker1').datetimepicker({
                    format: 'YYYY-MM-DD',
                    showTodayButton:true
                });
            });
        </script>
      </div>
      <div class="col-md-4">
        <label>Fecha Fin:</label>
        <div class="input-group date" style="width: auto;" id="datetimepicker2">
            <input type='text' class="form-control" name="txtfin0" id="txtfin0" value="<?php echo date ('Y-m-d')?>" placeholder="Fecha fin"/>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
        <script type="text/javascript">
            $(function () {
                $('#datetimepicker2').datetimepicker({
                    format: 'YYYY-MM-DD',
                    showTodayButton:true
                });
            });
        </script>
       </div>
        <div class="col-md-2">
            <br>
            <input type="submit" name="enviar0" id="enviar0" value="Buscar" class="btn btn-primary" title="Buscar"/>
        </div>
      <label class="my-error-class" id='errorFecha0' style="display: none;"></label>
  </div>
</div>  
    
<div class="panel panel-default" id="oordate" style='display:none;'>
  <div class="panel-heading" >
      <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR FECHA DE ORDEN</strong></h3>
  </div>
  <div class="panel-body">
        	
      	<div class="col-md-4">
            <p><strong>Fecha Inicio:</strong></p>
            <div class='input-group date' id='datetimepicker3'>
                <input type='text' class="form-control" name="txtinicio" id="txtinicio" value="<?php echo date ('Y-m-d')?>" placeholder="Fecha inicio"/>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            <script type="text/javascript">
                $(function () {
                    $('#datetimepicker3').datetimepicker({
                        format: 'YYYY-MM-DD',
                        showTodayButton:true
                    });
                });
            </script>
        </div>
        <div class="col-md-4">
            <p><strong>Fecha Fin:</strong></p>
            <div class='input-group date' id='datetimepicker4'>
                <input type='text' class="form-control" name="txtfin" id="txtfin" value="<?php echo date ('Y-m-d')?>" placeholder="Fecha fin"/>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            <script type="text/javascript">
                $(function () {
                    $('#datetimepicker4').datetimepicker({
                        format: 'YYYY-MM-DD',
                        showTodayButton:true
                    });
                });
            </script>
        </div>
        <div class="col-md-2">
            <br>
            <input type="submit" name="enviar" id="enviar" value="Buscar" class="btn btn-primary" title="Buscar" />
        </div>
       <label class="my-error-class" id='errorFecha' style="display: none;"></label>
    </div>
  </div>

<div class="panel panel-default" id="sshipto" style="display:none;">
  <div class="panel-heading" >
      <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR FECHA DE VUELO</strong></h3>
  </div>
  <div class="panel-body">
      <div class="col-md-4">
      	 		<p><strong>Fecha Inicio:</strong></p>
		   		<div class='input-group date' id='datetimepicker5'>
                    <input type='text' class="form-control" name="txtinicio1" id="txtinicio1" value="<?php echo date ('Y-m-d')?>" placeholder="Fecha inicio"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                <script type="text/javascript">
                    $(function () {
                        $('#datetimepicker5').datetimepicker({
                            format: 'YYYY-MM-DD',
                            showTodayButton:true
                        });
                    });
                </script>
            </div>
            <div class="col-md-4">
		       <p><strong>Fecha Fin:</strong></p>
		       <div class='input-group date' id='datetimepicker6'>
                    <input type='text' class="form-control" name="txtfin1" id="txtfin1" value="<?php echo date ('Y-m-d')?>" placeholder="Fecha fin"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                <script type="text/javascript">
                    $(function () {
                        $('#datetimepicker6').datetimepicker({
                            format: 'YYYY-MM-DD',
                            showTodayButton:true
                        });
                    });
                </script>
            </div>
            <div class="col-md-2">
            	<br>
          	<input type="submit" name="enviar1" id="enviar1" value="Buscar" class="btn btn-primary" title="Buscar" />
            </div>
            <label class="my-error-class" id='errorFecha1' style="display: none;"></label>
  </div>
 </div>

<div class="panel panel-default" id="ddeliver" style="display:none;">
  <div class="panel-heading" >
      <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR FECHA DE ENTREGA</strong></h3>
  </div>
  <div class="panel-body">
      <div class="col-md-4">
	      	 		<p><strong>Fecha Inicio:</strong></p>
			   		<div class='input-group date' id='datetimepicker7'>
	                    <input type='text' class="form-control" name="txtinicio2" id="txtinicio2" value="<?php echo date ('Y-m-d')?>" placeholder="Fecha inicio"/>
	                    <span class="input-group-addon">
	                        <span class="glyphicon glyphicon-calendar"></span>
	                    </span>
	                </div>
	                <script type="text/javascript">
	                    $(function () {
	                        $('#datetimepicker7').datetimepicker({
	                            format: 'YYYY-MM-DD',
                                    showTodayButton:true
	                        });
	                    });
	                </script>
	            </div>
	            <div class="col-md-4">
			       <p><strong>Fecha Fin:</strong></p>
			       <div class='input-group date' id='datetimepicker8'>
	                    <input type='text' class="form-control" name="txtfin2" id="txtfin2" value="<?php echo date ('Y-m-d')?>" placeholder="Fecha fin"/>
	                    <span class="input-group-addon">
	                        <span class="glyphicon glyphicon-calendar"></span>
	                    </span>
	                </div>
	                <script type="text/javascript">
	                    $(function () {
	                        $('#datetimepicker8').datetimepicker({
	                            format: 'YYYY-MM-DD',
                                    showTodayButton:true
	                        });
	                    });
	                </script>
	            </div>
	            <div class="col-md-2">
	            	<br>
	          	<input type="submit" name="enviar2" id="enviar2" value="Buscar" class="btn btn-primary" title="Buscar" />
	            </div>
                    <label class="my-error-class" id='errorFecha2' style="display: none;"></label>
  </div>
</div>
    
<div class="panel panel-default" id="ttracking" style="display:none;">
  <div class="panel-heading" >
      <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR TRACKING</strong></h3>
  </div>
  <div class="panel-body">
      <div class="col-md-12">
            <label><strong>Tracking:</strong></label>
            <input name="tracking" type="text" id="idtracking" class="form-control" style="width:200px;"/>
            <input type="submit" name="enviar3" id="enviar3" value="Buscar" class="btn btn-primary"/>
      </div>
  </div>
</div>

<div class="panel panel-default" id="po" style="display:none;">
  <div class="panel-heading" >
      <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR PONUMBER</strong></h3>
  </div>
  <div class="panel-body">
      <div class="col-md-12">
            <label><strong>Ponumber:</strong></label>
            <input name="ponumber" type="text" id="ponumber"  class="form-control" style="width:200px;"/>
            <input type="submit" name="enviar4" id="enviar4" value="Buscar" class="btn btn-primary"/>
      </div>
  </div>
</div>

<div class="panel panel-default" id="ccustnumber" style="display:none;">
  <div class="panel-heading" >
      <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR CUSTNUMBER</strong></h3>
  </div>
  <div class="panel-body">
      <div class="col-md-12">
            <label><strong>Custnumber:</strong></label>
            <input name="custnumber" type="text" id="custnumber"  class="form-control" style="width:200px;"/>
            <input type="submit" name="enviar6" id="enviar6" value="Buscar" class="btn btn-primary"/>
      </div>
  </div>
</div>
    
<div class="panel panel-default" id="iitem" style="display:none;">
  <div class="panel-heading" >
      <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR PRODUCTO</strong></h3>
  </div>
  <div class="panel-body">
      <div class="col-md-12">
            <label><strong>Producto:</strong></label>
            <input name="item" type="text" id="item"  class="form-control" style="width:200px;"/>
            <input type="submit" name="enviar5" id="enviar5" value="Buscar" class="btn btn-primary"/>
      </div>
  </div>
</div>     
     
<div class="panel panel-default" id="ffarm" style="display:none;">
  <div class="panel-heading" >
      <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR FINCA</strong></h3>
  </div>
  <div class="panel-body">
      <div class="col-md-12">
            <label><strong>Finca:</strong></label>
            <input name="farm" type="text" id="farm"  class="form-control" style="width:200px;"/>
            <input type="submit" name="enviar7" id="enviar7" value="Buscar" class="btn btn-primary"/>
      </div>
  </div>
</div>     
     
<div class="panel panel-default" id="sshipto1" style="display:none;">
  <div class="panel-heading" >
      <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR NOMBRE DEL RECEPTOR</strong></h3>
  </div>
  <div class="panel-body">
      <div class="col-md-12">
            <label><strong>Nombre del Receptor:</strong></label>
            <input name="shipto1" type="text" id="shipto1"  class="form-control" style="width:200px;"/>
            <input type="submit" name="enviar8" id="enviar8" value="Buscar" class="btn btn-primary"/>
      </div>
  </div>
</div>

<div class="panel panel-default" id="ddireccion" style="display:none;">
  <div class="panel-heading" >
      <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR DIRECCIÓN DEL RECEPTOR</strong></h3>
  </div>
  <div class="panel-body">
      <div class="col-md-12">
            <label><strong>Dirección  del Receptor:</strong></label>
            <input name="direccion" type="text" id="direccion"  class="form-control" style="width:200px;"/>
            <input type="submit" name="enviar9" id="enviar9" value="Buscar" class="btn btn-primary"/>
      </div>
  </div>
</div>      
    
<div class="panel panel-default" id="ssoldto1" style="display:none;">
  <div class="panel-heading" >
      <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR NOMBRE DEL COMPRADOR</strong></h3>
  </div>
  <div class="panel-body">
      <div class="col-md-12">
            <label><strong>Nombre del Comprador:</strong></label>
            <input name="soldto1" type="text" id="soldto1"  class="form-control" style="width:200px;"/>
            <input type="submit" name="enviar10" id="enviar10" value="Buscar" class="btn btn-primary"/>
      </div>
  </div>
</div> 

<div class="panel panel-default" id="ccpdireccion_soldto" style="display:none;">
  <div class="panel-heading" >
      <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR DIRECCIÓN DEL COMPRADOR</strong></h3>
  </div>
  <div class="panel-body">
      <div class="col-md-12">
            <label><strong>Dirección del Comprador:</strong></label>
            <input name="cpdireccion_soldto" type="text" id="cpdireccion_soldto"  class="form-control" style="width:200px;"/>
            <button type="submit" name="enviar11" id="enviar11" value="Buscar" class="btn btn-primary"/>
      </div>
  </div>
</div> 

</form>
</div> <!-- /table-responsive --> 

<div class="panel-heading">
   <div class="contenedor" align="center">
     <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2016</strong>
     <br>
     <strong><a href="http://www.bit-store.ec/index.php/contactenos/"  style="color:white">Info</a> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></strong>
   </div>
</div>

    <?php 
    //Si se oprimio el boton buscar de new
     if(isset($_POST['enviar0']))
     {	  
    	 $fecha_inicio0=$_POST["txtinicio0"];
  	 $fecha_fin0=$_POST["txtfin0"];
  	 $pais =$_POST["pais"];
         $consolidado= $_POST["consolidado"];
         $_SESSION["consolidado"] = $consolidado;
    	 $_SESSION["finca"]=$finca;
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
  	 $pais =$_POST["pais"];
         $consolidado= $_POST["consolidado"];
         $_SESSION["consolidado"] = $consolidado;
    	 $_SESSION["finca"]=$finca;
  	 $_SESSION["pais"]=$pais;
  	 $_SESSION["inicio"]=$fecha_inicio;
  	 $_SESSION["fin"]=$fecha_fin;	
  	 $url="repor_excel_fincas.php";
         echo "<SCRIPT>window.location='$url';</SCRIPT>";
    }
    
    //Si se oprimio el boton buscar de shipdt
    if(isset($_POST['enviar1']))
    {	  
  	  $fecha_inicio1=$_POST["txtinicio1"];
  	  $fecha_fin1=$_POST["txtfin1"];
  	  $pais=$_POST["pais"];
          $consolidado= $_POST["consolidado"];
          $_SESSION["consolidado"] = $consolidado ;
  	  $_SESSION["finca"]=$finca;
  	  $_SESSION["pais"]=$pais;
  	  $_SESSION["inicio1"]=$fecha_inicio1;
  	  $_SESSION["fin1"]=$fecha_fin1;
  	  $url="repor_excel_fincas.php";  
          echo "<SCRIPT>window.location='$url';</SCRIPT>";
    }
    
    //Si se oprimio el boton buscar de deliver
     if(isset($_POST['enviar2']))
     {	  
  	  $fecha_inicio1=$_POST["txtinicio2"];
  	  $fecha_fin1=$_POST["txtfin2"];
  	  $pais=$_POST["pais"];
          $consolidado= $_POST["consolidado"];
          $_SESSION["consolidado"] = $consolidado ;
  	  $_SESSION["finca"]=$finca;
  	  $_SESSION["pais"]=$pais;
  	  $_SESSION["inicio2"]=$fecha_inicio1;
  	  $_SESSION["fin2"]=$fecha_fin1;
  	  $url="repor_excel_fincas.php"; 
          echo "<SCRIPT>window.location='$url';</SCRIPT>";
     }
    
     //Si se oprimio el boton buscar de tracking
      if(isset($_POST['enviar3']))
    {	  
  	  $tracking=$_POST["tracking"];
  	  $alltrack= $_POST["alltrack "];
  	  $pais =$_POST["pais"];
  	  $origen = $_POST["origen"];
  	  $consolidado= $_POST["consolidado"];
          $_SESSION["consolidado"] = $consolidado ;
  	  $_SESSION["finca"]=$finca;
  	  $_SESSION["pais"]  =$pais;
  	  $_SESSION["tracking"]=$tracking;
  	  $_SESSION["login"] = $user ;
  	  $_SESSION["alltrack"] = $alltrack ;
  	  $url="repor_excel_fincas.php";
          echo "<SCRIPT>window.location='$url';</SCRIPT>";
    }
    
     //Si se oprimio el boton buscar de ponumber
      if(isset($_POST['enviar4']))
    {	  
  	  $ponumber=$_POST["ponumber"];
  	  $pais=$_POST["pais"];
          $consolidado= $_POST["consolidado"];
          $_SESSION["consolidado"] = $consolidado ;
  	  $_SESSION["finca"]=$finca;
  	  $_SESSION["pais"]=$pais;
  	  $_SESSION["ponumber"]=$ponumber;
  	  $url="repor_excel_fincas.php"; 
          echo "<SCRIPT>window.location='$url';</SCRIPT>";
    }
    
       //Si se oprimio el boton buscar de ponumber
      if(isset($_POST['enviar5']))
    {	  
  	  $item=$_POST["item"];
  	  $pais =$_POST["pais"];
          $consolidado= $_POST["consolidado"];
         $_SESSION["consolidado"] = $consolidado ;
  	  $_SESSION["finca"]=$finca;
  	  $_SESSION["pais"]=$pais;
  	  $_SESSION["item"]=$item;
  	  $url="repor_excel_fincas.php"; 
          echo "<SCRIPT>window.location='$url';</SCRIPT>";
    }
     //Si se oprimio el boton buscar de ponumber
      if(isset($_POST['enviar6']))
    {	  
        $custnumber=$_POST["custnumber"];
        $pais=$_POST["pais"];
        $consolidado= $_POST["consolidado"];
         $_SESSION["consolidado"] = $consolidado ;
  	$_SESSION["finca"]=$finca;
        $_SESSION["pais"]=$pais;
        $_SESSION["custnumber"]=$custnumber;
        $url="repor_excel_fincas.php"; 
        echo "<SCRIPT>window.location='$url';</SCRIPT>";
    }
     //Si se oprimio el boton buscar de ponumber
      if(isset($_POST['enviar7']))
      {	  
  	  $farm=$_POST["farm"];
  	  $pais =$_POST["pais"];
          $consolidado= $_POST["consolidado"];
          $_SESSION["consolidado"] = $consolidado ;
  	  $_SESSION["finca"]=$finca;
  	  $_SESSION["pais"]=$pais;
  	  $_SESSION["farm"]=$farm;
  	  $url="repor_excel_fincas.php"; 
          echo "<SCRIPT>window.location='$url';</SCRIPT>";
     }
     //Si se oprimio el boton buscar de shipto1
      if(isset($_POST['enviar8']))
      {	  
  	  $shipto1=$_POST["shipto1"];
  	  $pais=$_POST["pais"];
          $consolidado= $_POST["consolidado"];
          $_SESSION["consolidado"] = $consolidado ;
  	  $_SESSION["finca"]=$finca;
  	  $_SESSION["pais"]=$pais;
  	  $_SESSION["shipto1"]=$shipto1;
  	  $url="repor_excel_fincas.php"; 
          echo "<SCRIPT>window.location='$url';</SCRIPT>";
    }
     //Si se oprimio el boton buscar de direccion
      if(isset($_POST['enviar9']))
      {	  
  	  $direccion=$_POST["direccion"];
  	  $pais =$_POST["pais"];
          $consolidado= $_POST["consolidado"];
          $_SESSION["consolidado"] = $consolidado ;
  	  $_SESSION["finca"]=$finca;
  	  $_SESSION["pais"]=$pais;
  	  $_SESSION["direccion"]=$direccion;
  	  $url="repor_excel_fincas.php"; 
          echo "<SCRIPT>window.location='$url';</SCRIPT>";
      }
     //Si se oprimio el boton buscar de soldto1
      if(isset($_POST['enviar10']))
      {	  
  	  $soldto1=$_POST["soldto1"];
  	  $pais =$_POST["pais"];
          $consolidado= $_POST["consolidado"];
          $_SESSION["consolidado"] = $consolidado ;
  	  $_SESSION["finca"]=$finca;
  	  $_SESSION["pais"]=$pais;
  	  $_SESSION["soldto1"]=$soldto1;
  	  $url="repor_excel_fincas.php"; 
          echo "<SCRIPT>window.location='$url';</SCRIPT>";
     }
     //Si se oprimio el boton buscar de direccion
      if(isset($_POST['enviar11']))
      {	  
  	  $cpdireccion_soldto=$_POST["cpdireccion_soldto"];
  	  $pais=$_POST["pais"];
          $consolidado= $_POST["consolidado"];
          $_SESSION["consolidado"] = $consolidado ;
  	  $_SESSION["finca"]=$finca;
  	  $_SESSION["pais"]=$pais;
  	  $_SESSION["cpdireccion_soldto"]=$cpdireccion_soldto;
  	  $url="repor_excel_fincas.php"; 
          echo "<SCRIPT>window.location='$url';</SCRIPT>";
      }
   
    ?>
 
</div>
</div>
</body>
</html>
