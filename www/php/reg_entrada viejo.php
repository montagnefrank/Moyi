<?php
session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");
require_once('barcode.inc.php');

$user     =  $_SESSION["login"];
$passwd   =  $_SESSION["passwd"];
$bd       =  $_SESSION["bd"];
$rol      =  $_SESSION["rol"];

$error = $_GET['error'];

?>

<?php
  function limpia_espacios($cadena){
    $cadena = str_replace(' ', '', $cadena);
    return $cadena;
  }
?>



<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registro de Cajas</title>

  <link rel="icon" type="image/png" href="../images/favicon.ico" />
  <link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
  <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/octicons.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/zenburn.css" rel="stylesheet" type="text/css">
  <link href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1"rel="stylesheet" type="text/css" media="all"  />
  <!--<link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css"> -->

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

  <script src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
  <script src="../js/formato.js"></script>
  <style type="text/css">
    .my-error-class {
        color:red;
    }
    /*.my-valid-class {
        color:green;
    }*/

    li a{
        cursor:pointer; /*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
      }
  </style>
  
  <script>

  jQuery.validator.addMethod("barcode", function (value, element) {
    return this.optional(element) || /[a-z0-9 -()+]+$/.test(value);
  }, "Enter a valid bar code.");

  jQuery.validator.addMethod("noSpace", function (value, element) { 
    return value.indexOf(" ") < 0 && value != ""; 
  }, "No space please and don't leave it empty");

  // When the browser is ready...
  $(function() {

     /*$("#codigo").keydown(function (e) {
        // Allow: delete(46), "-" (109), backspace(8), tab(9), escape(27), enter(13), "-" (189) and "-" (android)
        if ($.inArray(e.keyCode, [46, 109, 8, 9, 27, 13, 189, 45]) !== -1 ||
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 35) || e.keyCode >= 96 && e.keyCode <= 105 || e.keyCode >= 65 && e.keyCode <= 90 )  {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57))) {
            e.preventDefault();
        }
    });

      // Setup form validation on the #register-form element
     $("#frmreport").validate({
      errorClass: "my-error-class",
      validClass: "my-valid-class",
  
      // Specify the validation rules
      rules: {
            codigo: {
              required: true,
              minlength: 30,
              maxlength: 30,
              noSpace: true,
              barcode: true
            }
      },
      
      // Specify the validation error messages
      messages: {
            codigo: {
              required: "Por favor inserte un código válido",
              minlength: "30 dígitos como mínimo",
              maxlength: "30 dígitos como máximo",
              noSpace: "No puede dejar espacios en blanco",
             // barcode: "Inserte un código de barras válido"
            }
      },
    
      submitHandler: function(form) {
          form.submit();
      }
    });*/
     
   // $("#codigo").mask("*************-*****-**********"); 
  });
  </script>

  <style>
  .contenedor {
       margin:0 auto;
       width:85%;
       text-align:center;
  }
  </style>

  <script type="text/javascript">

  function cambiar(valor){
	var v=valor;
	window.open("cambiarcontrasenna.php?codigo="+v,"Cantidad","width=400,height=300,top=150,left=400");
	return false;
	}

  function modificar(valor){
  	var v=valor;
  	window.open("modificarentrada.php?codigo="+v,"Cantidad","width=500,height=400,top=150,left=350");
  	return false;
  }
  function nueva(){
  	window.open("nuevaentrada.php","Cantidad","width=500,height=400,top=150,left=350");
  	return false;
  }
  function eliminar(valor){
  	var v=valor;
  	window.open("eliminarentrada.php?codigo="+v,"Cantidad","width=250,height=150,top=300,left=400");
  	return false;
  }

  function KeyAscii(e) {
    return (document.all) ? e.keyCode : e.which;
   }
   
   function TabKey(e, nextobject) {
    nextobject = document.getElementById(nextobject);
    if (nextobject) {
     if (KeyAscii(e) == 13) 
     	nextobject.focus();
    }
   }

  $(document).ready(function() {
  	$('#codigo').keydown(function(e) {
  		if (e.keyCode == 13) {
  			$('#form').submit();
  		}
  	});
  	
  	//tol-tip-text
  		  $('[data-toggle="tooltip"]').tooltip();
  });

  </script>
  <script type="text/javascript">
  	function info(mensaje,codigo){ 
  		window.open("info1.php?msg="+mensaje+"&id="+codigo,"Info","width=380,height=200,top=350,left=400");
  	return false;
  	}
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
		<a class="navbar-brand" href="mainroom.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>';
	
</div>
 
  <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
 <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
         <li class="active">
                        <a tabindex="0" data-toggle="dropdown">
                          <strong>Movimientos</strong><span class="caret"></span>
                        </a>
                         <ul class="dropdown-menu" role="menu">
                              <li><a href="reg_entrada.php"><strong>Registro Cajas</strong></a></li>
                            <li class="divider"></li>
                            <li><a href="verificartrack.php" ><strong>Chequear Tracking</strong></a></li>
                      </ul>
        </li>

    	<li><a href="asig_guia.php" ><strong>Asignar Guía</strong></a></li>     	
        <li><a href="reutilizar.php" ><strong>Reutilizar Cajas</strong></a></li>
        <li class="dropdown">
                        <a tabindex="0" data-toggle="dropdown">
                          <strong>Etiquetas</strong><span class="caret"></span>
                        </a>
                         <ul class="dropdown-menu" role="menu">
                              <li><a href="etiqxfincas.php">Imprimir etiquetas por fincas</a></li>
                            <li class="divider"></li>
                            <li><a href="etiquetasexistentes.php">Etiquetas existentes</a></li>
                      </ul>
        </li>
        

        <li class="dropdown">
                        <a tabindex="0" data-toggle="dropdown">
                          <strong>Reportes</strong><span class="caret"></span>
                        </a>
                         <ul class="dropdown-menu" role="menu">
                            <li class="dropdown-submenu">
                                <a tabindex="0" data-toggle="dropdown"><strong>Cajas Recibidas</strong></a>            
                                <ul class="dropdown-menu">                               
                                    <li><a href="reportecold.php?id=1">Por Productos Sin Traquear</a></li>
                                    <li class="divider"></li>
                                    <li><a href="reportecold.php?id=2">Por Fincas Sin Traquear</a></li>
                                    <li class="divider"></li>
                                    <li><a href="reportecold.php?id=3">Por Código Sin Traquear</a></li>
                                    <li class="divider"></li>
                                    <li><a href="reportecold.php?id=4">Total</a></li>
                                </ul>
                            </li>
                      
                     
                          <li class="divider"></li>

                          <li class="dropdown-submenu">
                                        <a tabindex="0" data-toggle="dropdown"><strong>Cajas Traqueadas</strong></a>            
                                        <ul class="dropdown-menu">                               
                                            <li><a href="reportecold1.php?id=1">Por Producto</a></li>
                                            <li class="divider"></li>
                                <li><a href="reportecold1.php?id=2">Por Fincas</a></li>
                                <li class="divider"></li>
                                <li><a href="reportecold1.php?id=3">Por Código</a></li>
                                <li class="divider"></li>
                                <li><a href="reportecold1.php?id=4">Total</a></li>
                                        </ul>
                          </li>
                                
                          <li class="divider"></li>
                          
                          <li class="dropdown-submenu">
                            <a tabindex="0" data-toggle="dropdown"><strong>Cajas Rechazadas</strong></a>            
                            <ul class="dropdown-menu">                               
                                <li><a href="reportecold2.php?id=1">Por Producto</a></li>
                                <li class="divider"></li>
                                <li><a href="reportecold2.php?id=2">Por Fincas</a></li>
                                <li class="divider"></li>
                                <li><a href="reportecold2.php?id=3">Por Código</a></li>
                
             	              </ul>
                         </li>
                         
                        <li class="divider"></li>
                        <li><a href="voladasxfinca.php"><strong>Cajas voladas</strong></a></li>
                        <li class="divider"></li>
                        <li><a href="novoladasxfinca.php"><strong>Cajas no voladas</strong></a></li>
                        <li class="divider"></li>                     
                        <li><a href="guiasasig.php"><strong>Guías asignadas</strong></a></li>
                     
                    </ul> 
        </li>
                    <li><a href="modificar_guia.php" ><strong>Editar Guías</strong></a></li>
                    <li><a href="closeday.php" ><strong>Cerrar Día</strong></a></li>

        
		<?php
               if($rol == 4){  
					$sql   = "SELECT id_usuario from tblusuario where cpuser='".$user."'";
					$query = mysql_query($sql,$conection);
					$row = mysql_fetch_array($query);
					echo '<li><a href="" onclick="cambiar(\''.$row[0].'\')"><strong>Contraseña</strong></a>'; 
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
<?php
		if($error == 2){
			
        	echo '<div class="alert alert-danger" role="alert">
				  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				  <span class="sr-only">Error:</span>
				  <strong>¡No ha insertado ningun código de barras!</strong>
				</div>';
		}else{
			if($error == 3){
				echo '<div class="alert alert-danger" role="alert">
					  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					  <span class="sr-only">Error:</span>
					  <strong>¡Esa caja no ha sido solicitada, no se puede registrar su entrada!</strong>
					</div>';
			}else{
				if($error == 4){
					echo '<div class="alert alert-danger" role="alert">
						  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						  <span class="sr-only">Error:</span>
						  <strong>¡Error!</strong>, No se pudo actualizar el estado del pedido
						</div>';
				}else{
					if($error == 5)
					echo '<div class="alert alert-success" role="alert">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<strong>¡Caja registrada correctamente!</strong>
						 </div>';
				}
			}
		}
?>
<form method="post" id="frmreport" name="frmreport" novalidate="novalidate" action="" .error { color:red}>
<div class="table-responsive">
<table width="50%" border="0" align="center" class="table table-striped" >
<tr>
    <td colspan="6" align="center"><h3><strong>REGISTRAR ENTRADA </strong></h3></td>
</tr>
<tr>
    <td align="right"><strong>Código de Barras:</strong></td>
    <td><input type="text" id="codigo" name="codigo" value="" size="50" autofocus="autofocus"/></td>
</tr>
</form> 
<form method="post" id="frm1" name="frm1">             
    <tr>
    <td colspan="6" align="center">
    	<h3><strong>Entradas del día </strong><font color="#FF0000"><?php echo '<time datetime="'.date('c').'">'.date('d - m - Y').'</time>';?></font></h3>
    </td>
    <td align="right">
        <input type="image" style="cursor:pointer" id="imprimir"  name="imprimir"class= "imprimir" src="../images/excel.png" heigth="40" value="" data-toggle="tooltip" data-placement="left" title = "Exportar Reporte Excel" width="30" formaction = "crearReportExcel.php"/>
    </td>
  </tr>
</table>
</div>

<div class="table-responsive">
<table width="50%" border="0" align="center" class="table table-striped" >
   <tr>
        <td align="center"><strong>Código de Caja</strong></td>
        <td align="center"><strong>Producto</strong></td>
        <td align="center"><strong>Finca</strong></td>
        <td align="center"><strong>Fecha Entrada</strong></td>
        <td align="center"><strong>Entrada</strong></td>
        <td align="center"><strong>Salida</strong></td>
      </tr>
  <?php
  $hoy = date('Y-m-d');
  $conection = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());
  //Leer todas las fincas existentes para modificarlas o crear nuevas
  $sql = "SELECT * FROM tblcoldroom where fecha like '".$hoy."%' AND entrada ='Si' AND salida = 'No'";
  //echo $sql;
  $val = mysql_query($sql,$conection);
   if(!$val){
      echo "<tr><td>".mysql_error()."</td></tr>";
   }else{
	   $cant = 0;
   while($row = mysql_fetch_array($val)){
			echo "<tr>";
			echo "<td align='center'>".$row['codigo']."</td>";
			echo "<td align='center'><strong>".$row['item']."<strong></td>";
			echo "<td><strong>".$row['finca']."</strong></td>";
			echo "<td align='center'>".$row['fecha']."</td>";
			echo "<td align='center'>".$row['entrada']."</td>";
			echo "<td align='center'>".$row['salida']."</td>";
			echo "</tr>";
			$cant ++;
	}
	        echo "<tr>";
			echo "<td align='right' colspan='2'><strong>".$cant."</strong></td>";
			echo "<td align='left'><strong>Cajas registradas</strong></td>";
			echo "</tr>";
							
   }
    mysql_close($conection);
	  	
			//Preparando los datos para el reporte
		   $_SESSION["titulo"] ="Listado de Entradas ".$hoy;
		   $_SESSION["columnas"] = array("Código de Caja","Item","Finca","Fecha","Entrada","Salida"); 
		   $_SESSION["consulta"] =   "SELECT codigo,item,finca,fecha,entrada,salida FROM tblcoldroom where fecha like '".$hoy."%' AND entrada ='Si' AND salida = 'No'";
		   $_SESSION["nombre_fichero"] = "Listado de Entradas ".$hoy.".xlsx";

  ?>
 </table>
 </div> <!-- table responsive-->
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

 <?php
   if($_POST){ 
   
     $conection = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());

  	 $codebar      = strtoupper($_POST['codigo']);

     $codebar = limpia_espacios($codebar);

  	 list($codfinca,$item,$codigo) = explode("-", $codebar);
  	 $fecha         = date('Y-m-d');
  	 
  	 //verifico que los campos no esten vacios
  	 if($codfinca == '' && $item == '' && $codigo == ''){
  	   echo "<script> window.location.href='reg_entrada.php?error=2'</script>";	 
  	 }else{
  		     //Se busca segun el codigo de la finca el nombre para insertarlo
  			 $sentencia = "SELECT nombre FROM tblfinca WHERE codigo='".$codfinca."'";
  			 $consulta  = mysql_query($sentencia,$conection) or die(mysql_error());
  			 $fila      = mysql_fetch_array($consulta);
  			 $finca     = $fila['nombre'];
  			 
  	         //Verificar si esa entrada esta solicitada
  			 $cadena   = "SELECT * FROM tbletiquetasxfinca where codigo = '".$codigo."' AND finca = '".$finca."' AND item = '".$item."'";
  			 $ejecutar = mysql_query($cadena, $conection);
  			 $result   = mysql_num_rows($ejecutar);
  			 if($result == 0){
  						
  						 echo "<script> window.location.href='reg_entrada.php?error=3'</script>";				 
  			 }else{

  				 $sql="INSERT INTO tblcoldroom (`codigo`, `entrada`, `item`,`finca`,`fecha`, `salida`) VALUES ('$codigo','Si','$item','$finca','$fecha','No')";				 			 
  				 $insertado= mysql_query($sql,$conection);
  				 
  				 if($insertado){
  					 //Actualizando el estado de los pedidos en el cuarto frio
  					 $sql="UPDATE tbletiquetasxfinca set estado='1', entregado = '1' where codigo='".$codigo."'";
  					 $modificado= mysql_query($sql,$conection);
  					 if($modificado){
  						echo "<script> window.location.href='reg_entrada.php?error=5'</script>";
  					 }else{
  						  echo "<script> window.location.href='reg_entrada.php?error=4'</script>";
  						 }
  				 }else{
  					 
  					 echo '<script type="text/javascript">
  						info("Ya esa caja fue registrada.",1);
  						 </script>';
  					 }
  			 }
  		}  
  			  mysql_close($conection);
   }
  ?>
</body>
</html>