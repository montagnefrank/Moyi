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
$error = $_GET['error'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cerrar Día</title>

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

  <style>
  .contenedor {
       margin:0 auto;
       width:85%;
       text-align:center;
  }
  li a{
      	cursor:pointer;/*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
      } 
  </style>

  <script type="text/javascript">
   $(document).ready(function(){
  		//tol-tip-text
  		$(function () {
  		  $('[data-toggle="tooltip"]').tooltip()
  		});
      });
  </script>
  <script>
  function marcar(){
  	 
  	if(document.form1.todas.checked){
  	 document.form1.todas.value =1;
  }else{
  	document.form1.todas.value =0;
  }

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
         <li class="dropdown">
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
                     <li class="divider"></li>
                     <li><a href="reporte_palets.php"><strong>Guias Master trackeadas</strong></a></li>
                     </ul> 
           </li>
                    <li><a href="modificar_guia.php" ><strong>Editar Guías</strong></a></li>   
                    <li class="active"><a href="closeday.php" ><strong>Cerrar Día</strong></a></li>
        
		<?php
               if($rol == 4){  
					$sql   = "SELECT id_usuario from tblusuario where cpuser='".$user."'";
					$query = mysqli_query($link, $sql);
					$row = mysqli_fetch_array($query);
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
				  <strong>¡No hay pedidos para entregar hoy!</strong>
				</div>';
		}else{
			if($error == 3)
			echo '<div class="alert alert-success" role="alert">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				    <strong>¡Cierre realizado correctamente!</strong>
				 </div>';
		}
?>

<form id="form1" name="form1" method="post" action="">
<div class="table-responsive">
<div class="row">
     <div class="col-md-12">
    	<h3><strong>Hacer cierre de día</strong></h3>
    </div>
</div>
<table width="50%" border="0" align="center" class="table" >
    <tr>
      <td align="right"><strong>Fecha de cierre:</strong></td>
      <td style="width: 70%">
          
       <div class="input-group date" id="datetimepicker2" style="width: 50%" >
            <input type='text' class="form-control" name="cierre" id="cierre" value="<?php echo date ('Y-m-d')?>"/>
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
      </td>
    </tr>
    <tr>
      <td align="right"><strong>Finca *:</strong></td>
      <td style="width: 70%">
          <select type="text" name="finca" id="finca" class="form-control" style="width: 50%">
            <?php 
              //Consulto la bd para obtener solo los id de item existentes
              $sql   = "SELECT nombre FROM tblfinca";
              $query = mysqli_query($link, $sql);
                    //Recorrer los iteme para mostrar
                    echo '<option value="0" selected="selected"></option>'; 
                    while($row1 = mysqli_fetch_array($query)){
                        echo '<option value="'.$row1["nombre"].'">'.$row1["nombre"].'</option>'; 
                    }
              ?>                       
      </select>
      
      </tr>
      <tr>
      <td align="right"><strong>Todas las fincas:</strong></td>
      <td><input name="todas" id="todas" type="checkbox" value="0" onchange="marcar()"/></td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="left"><input name="si" type="submit" class="btn btn-danger" id="si" value="Cierre de Día" data-toggle="tooltip" data-placement="rigth" title="Hacer Cierre de Día"/></td>
    </tr>
  </table>
 </div> <!-- table responsive-->
</form>
</div> <!-- /panel body --> 
<div class="panel-heading">
      <div class="contenedor">
        <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2015 versión 3 </strong>
      </div>
    </div>    
    <span id="top-link-block" class="hidden">
    <a href="#top" class="well well-sm"  onclick="$('html,body').animate({scrollTop:0},'slow');return false;">
        <i class="glyphicon glyphicon-chevron-up"></i> Ir arriba
    </a>
</span><!-- /top-link-block --> 
</div> <!-- /container -->
<?php 
  if(isset($_POST["si"]) && $error == 0){
	  $check = $_POST['todas'];
	  if($check == 1){ // Si el check esta marcado le hago cierre de dia atodas las fincas
		    
			$cierre = $_POST['cierre'];
	  		//Seleccionar los pedidos que tienen fecha de enetrada hoy y que son de la finca seleccionada
			$sentencia = "SELECT * FROM tbletiquetasxfinca WHERE fecha='".$cierre."' AND estado=0";
			$consulta  = mysqli_query($link, $sentencia);
			$cant      = mysqli_num_rows($consulta);
			
			//Verifico que haya pedidos para entrar hoy
			if($cant == 0){
				 echo "<script> window.location.href='closeday.php?error=2'</script>";
			}else{
			
			//Se recorre cad uno de lo spediso para el dia y que no entraron
			while ($row = mysqli_fetch_array($consulta)){
				
				//Verifico  que este en estado pendiente si es asi lo modifico por rechazado
				if($row['estado'] == 0){
					//Modificando cada pedido a rechazado codigo 3 que es rechazad por cierre manual de dia
					$sql="UPDATE tbletiquetasxfinca set estado='3', comentario='Cierre de dia' where codigo='".$row['codigo']."'";
					$eliminado= mysqli_query($link, $sql)or die ("Error haciendo cierre de día, vuelva a intentar.");					
				}
			}
			
			 echo "<script> window.location.href='closeday.php?error=3'</script>";
			}
			mysqli_close($link);
			
		  }else{
			  $cierre = $_POST['cierre'];
			  $finca = $_POST['finca'];
					//Seleccionar los pedidos que tienen fecha de enetrada hoy y que son de la finca seleccionada
					$sentencia = "SELECT * FROM tbletiquetasxfinca WHERE fecha='".$cierre."' AND finca='".$finca."' AND estado=0";
					$consulta  = mysqli_query($link, $sentencia);
					$cant      = mysqli_num_rows($consulta);
					
					//Verifico que haya pedidos para entrar hoy
					if($cant == 0){
						 echo "<script> 		 		 
						 	window.location.href='closeday.php?error=2'</script>";
					}else{
					
					//Se recorre cad uno de lo spediso para el dia y que no entraron
					while ($row = mysqli_fetch_array($consulta)){
						
						//Verifico  que este en estado pendiente si es asi lo modifico por rechazado
						if($row['estado'] == 0){
							//Modificando cada pedido a rechazado codigo 3 que es rechazad por cierre manual de dia
							$sql="UPDATE tbletiquetasxfinca set estado='3', comentario='Cierre de dia' where codigo='".$row['codigo']."'";
							$eliminado= mysqli_query($link, $sql)or die ("Error haciendo cierre de día, vuelva a intentar.");					
						}
					}
					
					 echo "<script> window.location.href='closeday.php?error=3'</script>";
					}
					mysqli_close($link);
		  }
  }
  ?>
</body>
</html>