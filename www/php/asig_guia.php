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
$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Asignar Guías</title>

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
 <script src="../js/formato.js"></script>

  <style>
  .contenedor {
       margin:0 auto;
       width:85%;
       text-align:center;
  }
  li a{
      	cursor:pointer;/*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
  } 
  .my-error-class {
        color:red;
        font-style: italic;
        font-size: 12px;
    }
    .modal-header{
          background-color : #3B5998;
          color: white;
          border-radius: 5px 5px 0 0;
      }
  </style>
  
  <script language="javascript">
    
    $(document).ready(function() {
    	//tol-tip-text
    	$('[data-toggle="tooltip"]').tooltip();
        
        //para el boton de guia madre
        $('#AWB').on('click',function(){
            //window.open("guiamadre.php","Cantidad","width=400,height=300,top=100,left=400");
            
            $entre=0;
            //verifico si hay elementos seleccionados
            $("tbody input[type=checkbox]:checked").each(function(){
                $entre=1;
            });
        if($entre==1)   
          $('#myModalGM').modal('show');
        else
        {
            $('#mensaje').html('<strong><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span> Error: Debe seleccionar una orden para asignarle una guia.</strong>');
            $('#mensaje').fadeIn('slow',function (){
                setTimeout(function() {
                    $("#mensaje").fadeOut(1500);
                },3000);
            });
        }
        });
        //para el boton de guia hija
        $('#HAWB').on('click',function(){
            $entre=0;
            //verifico si hay elementos seleccionados
            $("tbody input[type=checkbox]:checked").each(function(){
                $entre=1;
            });
        if($entre==1)   
          $('#myModalGH').modal('show');
        else
        {
            $('#mensaje').html('<span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span> Error: Debe seleccionar una orden para asignarle una guia.</strong>');
            $('#mensaje').fadeIn('slow',function (){
                setTimeout(function() {
                    $("#mensaje").fadeOut(1500);
                },3000);
            });
        }
           
        });
        //al presionar el checkbox de seleccionar todos
        $("input[name=todos]").change(function(){
		$('input[type=checkbox]').each( function() {			
			if($("input[name=todos]:checked").length == 1){
				this.checked = true;
			} else {
				this.checked = false;
			}
		});
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
		<a class="navbar-brand" href="mainroom.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>';
	
</div>
 
<!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
         <li>
            <a tabindex="0" data-toggle="dropdown">
              <strong>Movimientos</strong><span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="reg_entrada.php"><strong>Registro Cajas</strong></a></li>
              <li class="divider"></li>
              <li><a href="verificartrack.php" ><strong>Chequear Tracking</strong></a></li>
            </ul>
        </li>

    	<li  class="active"><a href="asig_guia.php" ><strong>Asignar Guía</strong></a></li>     	
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
                    <li><a href="closeday.php" ><strong>Cerrar Día</strong></a></li>

        
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
	  <strong>¡Error! </strong>Debe ingresar la Guia master para realizar la busqueda
	       </div>';
	}
	
?>
<form id="form1" name="form1" method="post" action="">
<div class="table-responsive">
<table width="50%" border="0" align="center" class="table table-striped">
  <tr align="center"><td><h3><strong>ASIGNAR GUÍAS DE VUELO</strong></h3></td>
  </tr>
  <tr> 
    <td id="guiamadre" style='display:block;' align="center">
        <div class="col-mdd-1"><strong>Guia Master:</strong></div>
        <div class="col-md-2">
            <input type="text" class="form-control" id="guia_master" name="guia_master" value="<?php echo $_POST['guia_master'];?>"/>
        </div>  
        <div class="col-md-1"><strong>Finca:</strong></div>
	<div class="col-md-2">	      
            
        <select type="text" name="finca" id="finca" class="form-control" style="column-count:12" >
        <?php 
            //Consulto la bd para obtener solo los id de item existentes
            $sql   = "SELECT nombre FROM tblfinca";
            $query = mysqli_query($link, $sql);
                  //Recorrer los iteme para mostrar
                  echo '<option value=""></option>'; 
                  while($row1 = mysqli_fetch_array($query)){
                    
                    if(isset($_POST['finca']) && $_POST['finca']==$row1["nombre"])
                        echo '<option value="'.$row1["nombre"].'" selected="selected">'.$row1["nombre"].'</option>'; 
                    else
                        echo '<option value="'.$row1["nombre"].'">'.$row1["nombre"].'</option>';
                    
                    
                  }
          ?>                       
          </select>
        </div>
        <div class="col-md-2">	  
         <input name="filtrar" type="submit" class= "btn btn-primary" value="Buscar" data-toggle="tooltip" data-placement="rigth" title="Filtrar búsqueda" />
        </div>
        </td>
  </tr>
</table>
</div>
</form>
    <div class="alert alert-danger" role="alert" id="mensaje" style="display: none"></div>   
<div class="table-responsive">
    <div class="row">
    <div class="col-md-10">
        <h3><strong>Listado de órdenes</strong></h3>
    </div>
    <div class="col-md-1">
           <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="rigth" title = "Asignar Guía Madre" id="AWB">AWB</button>
           <!--<input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" heigth="40" value="" title = "Asignar Guía Madre" width="30" src="../images/madre.png" formaction="recogercheck.php?id=1"/>-->
    </div>    
    <div class="col-md-1">
       <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="rigth" title = "Asignar Guía Hija" id="HAWB">HAWB</button>
       <!--<input type="image" name="btn_cliente1" id="btn_cliente1" value="" src="../images/hija.png" heigth="40" width="30" formaction="recogercheck.php?id=2"/>-->
    </div>
    </div> 
<table width="50%" border="0" align="center" class="table table-striped" >
  <thead>
  <tr>
    <td align="center"><strong>Código de Caja</strong></td>
    <td align="center"><strong>Producto</strong></td>
    <td align="center"><strong>Finca</strong></td>
    <td align="center"><strong>Fecha Traqueo</strong></td>
    <td align="center"><strong>Guía Madre</strong></td>
    <td align="center"><strong>Guía Hija</strong></td>
    <td align="center"><input type="checkbox" value="0" id="todos" name="todos" title="Marcar todos"/></td>
  </tr>
  </thead>
<tbody>
  <?php
  
  if(isset($_POST['filtrar'])){
	  //verificar filtros de busqueda
	  $guia_master = $_POST['guia_master'];
          $finca = $_POST ['finca'];

	  if($guia_master != "" && $finca != ""){
		  $sql =   "SELECT * FROM tblcoldroom WHERE finca = '".$finca."' and guia_master='".$guia_master."' AND salida='Si' AND (guia_madre = 0 OR guia_hija = 0)";
		  $val = mysqli_query($link, $sql);
				  if(!$val){
					echo "<tr><td>".mysqli_error($link)."</td></tr>";
				   }else{
					   $cant = 0;
					   while($row = mysqli_fetch_array($val)){
						   
						   if ($row['fecha_tracking']!=''){
							$cant ++;
							echo "<tr>";
							echo "<td align='center'><strong>".$row['codigo']."</strong></td>";
							echo "<td align='center'><strong>".$row['item']."</strong></td>";
							echo "<td><strong>".$row['finca']."</strong></td>";
							echo "<td align='center'>".$row['fecha_tracking']."</td>";
							echo "<td align='center'>".$row['guia_madre']."</td>";
							echo "<td align='center'>".$row['guia_hija']."</td>";
							echo '<td align="center"><input name="cajas[]" type="checkbox" value="'.$row['codigo'].'" title="Marcar Caja"/></td>';							 
							echo "</tr>";
						   }
					 }
				  echo "
					   <tr>
						   <td align='right'><strong>".$cant. "</strong></td>
						   <td>Órden(es) encontradas</td>
					   </tr>";
				  $_SESSION ['filtro'] = $sql;	
													
				   }
        }
          else if($guia_master != ""){
            $sql =   "SELECT * FROM tblcoldroom WHERE guia_master='".$guia_master."' AND salida='Si' AND (guia_madre = 0 OR guia_hija = 0)";                    
            $val = mysqli_query($link, $sql);
            if(!$val){
                echo "<tr><td>".mysqli_error($link)."</td></tr>";
            }else{
					   $cant = 0;
					   while($row = mysqli_fetch_array($val)){
						   
						   if ($row['fecha_tracking']!=''){
							$cant ++;
							echo "<tr>";
							echo "<td align='center'><strong>".$row['codigo']."</strong></td>";
							echo "<td align='center'><strong>".$row['item']."</strong></td>";
							echo "<td><strong>".$row['finca']."</strong></td>";
							echo "<td align='center'>".$row['fecha_tracking']."</td>";
							echo "<td align='center'>".$row['guia_madre']."</td>";
							echo "<td align='center'>".$row['guia_hija']."</td>";
							echo '<td align="center"><input name="cajas[]" type="checkbox" value="'.$row['codigo'].'" title="Marcar Caja"/></td>';							 
							echo "</tr>";
						   }
					 }
					 echo "<tr>
							   <td align='right'><strong>".$cant. "</strong></td>
							   <td>Órden(es) encontradas</td>
						   </tr>";
					$_SESSION ['filtro'] = $sql;									
													
				   }
          }
          else if($guia_master == ''){
            echo "<script> window.location.href='asig_guia.php?error=2'</script>";
        }
                   
   }
?>
</tbody>
 </table>
 </div> <!-- table responsive-->
</div> <!-- /panel body --> 


<!--incluimo ventanas modales de guias madre e hija-->    
<?php 
include('guiahija.php');
include('guiamadre.php');
?>
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
</body>
</html>