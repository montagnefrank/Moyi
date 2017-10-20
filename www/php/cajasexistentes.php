<?php
session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");
$user     =  $_SESSION["login"];
$rol      =  $_SESSION["rol"];

  $conection = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cajas Existentes</title>

<link rel="icon" type="image/png" href="../images/favicon.ico" />
<link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
<link href="../bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" media="all"  />

<script src="../bootstrap/js/jquery.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="../bootstrap/js/moment.js"></script>
<script src="../bootstrap/js/bootstrap-datetimepicker.min.js"></script>
<script src="../bootstrap/js/bootstrap-submenu.js"></script>

<script type="text/javascript">
    
$(document).ready(function(){
    //tol-tip-text
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    });
                
    $(".botonExcel").click(function (event) {
                    $("#datos_a_enviar").val($("<div>").append($('.divtabla').clone()).html());
                    $("#FormularioExportacion").submit();
                });
    
    $("#btn_reset").click(function(){
        window.location.href="cajasexistentes.php";
    });
});
</script>

<style>
   li a{
      	cursor:pointer;/*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
    }
  .contenedor{
    width: 95%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
  }
  .table-responsive{
      overflow: auto;
  }
  tbody td{
      white-space: pre-wrap;
     
/*      word-break: break-all;
      width: 20%;*/
  }
  .table thead > tr > th{
      text-align: center;
      vertical-align: middle;
  }
/*  .pintado{
      background-color: green;
  }*/

</style>
</head>
<body background="../images/fondo.jpg">
<body background="../images/fondo.jpg" class="dt">
<div class="contenedor">
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
          if($rol <=3){
  		echo '<a class="navbar-brand" href="asig_etiquetas.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>';
  }else{
	  echo '<a class="navbar-brand" href=".mainroom.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>';
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
                   <li class="dropdown">
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
              <li class="dropdown">
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
				  <li>
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
			     	<li   class="active">
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
                    <li class="dropdown">
                         <a tabindex="0" data-toggle="dropdown">
                             <strong>Contabilidad</strong><span class="caret"></span>
                         </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="administration.php"><strong>Contabilidad</strong></a></li>                      
                            <li class="divider"></li>         
                            <li><a href="contabilidad.php"><strong>Contabilidad ECU</strong></a></li>
                       </ul>
                    </li>	
                 <?php
				 }
				 ?>
                 
                 <?php if($rol == 1){  ?>
			     	<li><a href="usuarios.php"><strong>Usuarios</strong></a></li>
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
  <form action="" method="post">
	<div class="panel panel-default">
        <div class="panel-body">
	<div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="fecha">Fecha Salida Finca:</label>
                    <input name="fecha" type="text" class="form-control" id="fecha" value="<?php echo $_POST['fecha'];?>" size="20">
                </div>
                <script type="text/javascript">
                    $(function () {
                        $('#fecha').datetimepicker({
                            format: 'YYYY-MM-DD',
                            showTodayButton:true
                        });
                    });
                </script>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="fecha_vuelo">Fecha Vuelo:</label>
                    <input name="fecha_vuelo" type="text" class="form-control" id="fecha_vuelo" value="<?php echo $_POST['fecha_vuelo'];?>" size="20">
                </div>
                <script type="text/javascript">
                    $(function () {
                        $('#fecha_vuelo').datetimepicker({
                            format: 'YYYY-MM-DD',
                            showTodayButton:true
                        });
                    });
                </script>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="agencia">Agencia:</label>
                    <select type="text" name="agencia" class="form-control" id="agencia" title="Seleccione una agencia">
                    <?php 
                        //Consulto la bd para obtener las agencias
                        $sql   = "SELECT * FROM tblagencia";
                        $query = mysql_query($sql,$conection);
                        //Recorrer los iteme para mostrar
                        echo '<option value=""></option>'; 
                        while($row1 = mysql_fetch_array($query)){
                         ?>  
                              <option value="<?php echo $row1['nombre_agencia']?>" <?php echo ($row1['nombre_agencia']==$_POST['agencia'])? "selected=\"selected\"":""?>><?php echo $row1['nombre_agencia']?></option>
                    
                            
                       <?php }
                        ?>                       
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="finca">Finca:</label>
                    <select type="text" name="finca" class="form-control" id="finca" title="Seleccione una finca">
                    <?php 
                        //Consulto la bd para obtener las agencias
                        $sql   = "SELECT nombre FROM tblfinca";
                        $query = mysql_query($sql,$conection);
                        //Recorrer los iteme para mostrar
                        echo '<option value=""></option>'; 
                        while($row1 = mysql_fetch_array($query)){ ?>
                           <option value="<?php echo $row1['nombre']?>" <?php echo ($row1['nombre']==$_POST['finca'])? "selected=\"selected\"":""?>><?php echo $row1['nombre']?></option>
                        <?php }
                        ?>                       
                    </select>
                </div>
            </div>
            
            <div class="col-md-2"  style="margin-top: 25px;">
                <div  style="float: left;">
                    <input name="btn_filtro" id="btn_filtro" type="submit" class="btn btn-primary" value="Filtrar" />
                </div>
            
                <div >
                    <input name="btn_reset" id="btn_reset" type="button" class="btn btn-warning" value="Resetear" />
                </div>
            </div>
	</div>
        </div>
        </div>
  </form>
    <div class="row">
        
        <div class="col-md-11"> 
           <label><h3><strong>Listado de pedidos a las Fincas</strong></h3></label>
        </div>
        <div class="col-md-1" > 
        <form action="Fichero_Excel.php" method="post" target="_blank" id="FormularioExportacion">
            <button type="button" class="btn btn-primary botonExcel" data-toggle="tooltip" aria-label="Exportar Excell" title = "Exportar Excell">
                <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>
            </button>
            
        <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
        </form> 
        </div>
    </div>
    
  <div class="divtabla">
  <?php 
    //recorro por los destinos primero
    $sql="SELECT DISTINCT tbletiquetasxfinca.destino FROM tbletiquetasxfinca";
    $res=mysql_query($sql,$conection);
    while($row=mysql_fetch_array($res)){
        //recorro por las agencias
        $sql1="SELECT DISTINCT tbletiquetasxfinca.agencia FROM tbletiquetasxfinca WHERE tbletiquetasxfinca.archivada!='Si' AND (tbletiquetasxfinca.estado!='5')";
        
        //si esta el filtro de agencia
        if(isset($_POST['agencia']) && $_POST['agencia']!="")
        {
           $sql1.=" AND agencia='".$_POST['agencia']."'"; 
        }
        
        $res1=mysql_query($sql1,$conection);
        
        while($row1=mysql_fetch_array($res1))
        {
  
            $sql3="SELECT COUNT(tbletiquetasxfinca.item) as cant,
                    tbletiquetasxfinca.fecha,
                    tbletiquetasxfinca.fecha_tentativa,
                    tbletiquetasxfinca.destino,
                    tbletiquetasxfinca.agencia,
                    tbletiquetasxfinca.finca,
                    tbletiquetasxfinca.item,
                    tblproductos.prod_descripcion,
                    tblproductos.item as id_item,
                    tblproductos.pack,
                    tblproductos.receta,
                    tbletiquetasxfinca.precio,
                    tblboxtype.tipo_Box
                    FROM
                    tbletiquetasxfinca
                    INNER JOIN tblproductos ON tblproductos.id_item = tbletiquetasxfinca.item
                    INNER JOIN tblboxtype ON tblproductos.boxtype = tblboxtype.id_Box
                    WHERE tbletiquetasxfinca.agencia='".$row1['agencia']."'
                    AND tbletiquetasxfinca.destino='".$row['destino']."'
                    AND tbletiquetasxfinca.archivada!='Si'
                    AND (tbletiquetasxfinca.estado!='5')";
                    
                    //aplicando los filtros de fecha de salida, vuelo y finca
                    if(isset($_POST['fecha']) && $_POST['fecha']!="") $sql3.=" AND tbletiquetasxfinca.fecha='".$_POST['fecha']."'";
                    if(isset($_POST['fecha_vuelo']) && $_POST['fecha_vuelo']!="") $sql3.=" AND tbletiquetasxfinca.fecha_tentativa='".$_POST['fecha_vuelo']."'";
                    if(isset($_POST['finca']) && $_POST['finca']!="") $sql3.=" AND tbletiquetasxfinca.finca='".$_POST['finca']."'";
                    
                    $sql3.=" GROUP BY tbletiquetasxfinca.fecha,tbletiquetasxfinca.fecha_tentativa,tbletiquetasxfinca.item,tbletiquetasxfinca.finca ORDER BY tbletiquetasxfinca.finca ASC";
                    
                    $res3=mysql_query($sql3,$conection);
                    $num=  mysql_num_rows($res3);
                    $finca="0";
                    $contador=1;
                    $i=0;
                    $tot_cajasOrdenadas=0;
                    $tot_precio=0;
                    $tot_fullbox=0;
                    
                    while($row3=mysql_fetch_array($res3))
                    {
                       if($i==0)
                       { ?>
                            
                            <div class="row">
                                <label><h4><?php echo $row['destino']?></h4></label>
                            </div>
                            <div class="row">
                                <label><h4><?php echo $row1['agencia']?></h4></label>
                            </div>
                            <div class="table-responsive">
                                <table id="tabla" class="table table-condensed" >
                                <thead>
                                    <tr style="background-color: #428bca;">
                                        <th>Salida de Finca</th>
                                        <th>Fecha Vuelo</th>
                                        <th>Destino</th>
                                        <th>Agencia</th>
                                        <th>Finca</th>
                                        <th>Producto</th>
                                        <th>Desc.Prod.</th>
                                        <th>Pack</th>
                                        <th>Receta</th>
                                        <th>Imagen</th>
                                        <th>Cajas Ordenadas</th>
                                        <th>Precio/Caja</th>
                                        <th>Precio Total</th>
                                        <th>Tipo Caja</th>
                                        <th>Full Box</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                        <?php 
                           $i=1;
                        }
                                         
                       
                       if($finca!=$row3['finca']){
                         if($finca!="0")
                         {
                             echo '<tr style="background-color:#D3D3D3;font-weight: bold"><td></td><td></td><td></td><td></td><td><strong>TOTAL</strong></td><td></td><td></td><td></td><td></td><td></td><td>'.$tot_cajasOrdenadas.'</td><td></td>'
                        . '<td>'.$tot_precio.'</td><td></td><td>'.$tot_fullbox.'</td></tr>';
                             $finca=$row3['finca'];
                             $tot_cajasOrdenadas=0;
                            $tot_precio=0;
                            $tot_fullbox=0;
                             
                         }else
                         {
                           $finca=$row3['finca'];  
                         }
                       }
                       
                        
                 ?>
                    <tr>
                        <td><?php echo $row3['fecha']; ?></td>
                        <td><?php echo $row3['fecha_tentativa']; ?></td>
                        <td><?php echo $row3['destino']; ?></td>
                        <td><?php echo $row3['agencia']; ?></td>
                        <td><?php echo $row3['finca']; ?></td>
                        <td><?php echo $row3['item']; ?></td>
                        <td><?php echo $row3['prod_descripcion']; ?></td>
                        <td><?php echo $row3['pack']; ?></td>
                        <td><?php echo substr($row3['receta'],0,50)." ".substr($row3['receta'],50); ?></td>
                        <td><?php 
                            if(file_exists('../images/productos/'.$row3['id_item'].'.jpg'))                            
                              echo '<img class="imag" src="../images/productos/'.$row3['id_item'].'.jpg" alt="Imagen" width="50px" height="50px"/>';?>
                        </td>
                        <td style="background-color: #F1F1F1;"><?php
                            $tot_cajasOrdenadas+=$row3['cant'];
                            echo $row3['cant']; ?>
                        </td>
                        <td><?php echo $row3['precio']; ?></td>
                        <td style="background-color: #F1F1F1;"><?php 
                            $tot_precio+=floatval($row3['precio']*$row3['cant']);
                            echo floatval($row3['precio']*$row3['cant']); 
                        ?>
                        </td>
                        <td style="mso-number-format:'@'"><?php echo $row3['tipo_Box']; ?></td>
                        <td style="background-color: #F1F1F1;"><?php 
                        
                        if($row3['tipo_Box']=='QBX'){
                            $tot_fullbox+=floatval($row3['cant']*0.25);
                            echo floatval($row3['cant']*0.25);
                        }
                        if($row3['tipo_Box']=='HLF'){
                            $tot_fullbox+=floatval($row3['cant']*0.5);
                            echo floatval($row3['cant']*0.5);
                        }
                        if($row3['tipo_Box']=='1/8'){
                            $tot_fullbox+=floatval($row3['cant']*0.125);
                            echo floatval($row3['cant']*0.125);
                        }
                        
                        ?></td>
                    </tr>   
                
                
                
                
                
                <?php
                    //si estoy en la ultima fila
                    if($contador==$num)
                    {
                        echo '<tr style="background-color:#D3D3D3;font-weight: bold"><td></td><td></td><td></td><td></td><td>TOTAL</td><td></td><td></td><td></td><td></td><td></td><td>'.$tot_cajasOrdenadas.'</td><td></td>'
                        . '<td>'.$tot_precio.'</td><td></td><td>'.$tot_fullbox.'</td></tr>';
                        $tot_cajasOrdenadas=0;
                        $tot_precio=0;
                        $tot_fullbox=0;
                     ?>   
                            </tbody>
                        </table>
                    </div>
                              
                    <?php }
                    $contador++;
                
                } ?> 
                 
 <?php }
    }
 ?>   
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
</html>

