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
  <title>Hacer Pedido</title>

  <script type="text/javascript" src="../js/script.js"></script>
  <script language="javascript" src="../js/imprimir.js"></script>
  <link rel="icon" type="image/png" href="../images/favicon.ico" />
  <link type="text/css" rel="stylesheet" href="../css/imprimir.css" media="print">
  <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="../css/validationEngine.jquery.css" type="text/css"/>
  
  <script language="javascript" src="../js/imprimir.js"></script>
  <script type="text/javascript" src="../js/script.js"></script>
  <script src="../bootstrap/js/jquery.js"></script>
  <script src="../bootstrap/js/bootstrap.js"></script>
  <script src="../bootstrap/js/moment.js"></script>
  <script src="../bootstrap/js/bootstrap-datetimepicker.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
  <script src="../bootstrap/js/bootstrap-submenu.js"></script>
  <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
  <script src="../bootstrap/js/docs.js" defer></script>
  <style>
    
    li a{
      	cursor:pointer;/*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
    }
   
    .seleccionado{
          background: #0066cc;
          color: #FFF;
    }
    .modal-header{
          background-color : #3B5998;
          color: white;
          border-radius: 5px 5px 0 0;
    }
    th {
      text-align: center; 
    }
    #lista_items thead{
        background: cornflowerblue;
    }
    .my-error-class {
        color: red;
        font-style: italic;
        font-size: 12px;
    }
  </style>
  <script type="text/javascript">
    
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
//    //boton exportar
//    $(".botonExcel").click(function(event) {
//        $("#datos_a_enviar").val( $("<div>").append( $("#form").clone()).html());
//        $("#FormularioExportacion").submit();
//    });
        
    //para el boton de  filtrar
     $("#btn_filtro").click(function(event) {
        window.location.href="cajasexistentes.php";
        return;
        
    });
    
    
    //boton seleccionar todas las filas
    $('#btn_seleccionar').on('click',function(){
        $('.seleccionable').addClass('seleccionado');
    });
    //boton deseleccionar todas las filas
    $('#btn_deseleccionar').on('click',function(){
        $('.seleccionable').removeClass('seleccionado');
    });
    
    var cajas = []; //tenddra los codigos de las cajas que se van a archivar   
    //al dar click en una fila   
    $('.seleccionable').on('click',function(){
       if($(this).hasClass('seleccionado'))
           $(this).removeClass('seleccionado'); 
       else
           $(this).addClass('seleccionado'); 
    });
       
    //al dar click en el boton de editar pedido
    $('#btn_editar').on('click',function(){
       var band=-1;
       $("#listado tbody tr.seleccionado").each(function (index) 
        {
           band=index;
        });
        
       if(band!=0)
       {
           $('#mensaje').html("<strong>Para editar un pedido debe seleccionar una sola fila de la tabla</strong>");
           $('#myModal').modal('show'); 
           return;
       }
       else{
        //veo si solicitado>0, entregado,rechazado,cierr=0
        var solicitado=$("#listado tbody tr.seleccionado td:eq(6)").html();
        var rechazado=$("#listado tbody tr.seleccionado td:eq(10)").html();
        var cierre=$("#listado tbody tr.seleccionado td:eq(11)").html();
        
        var porentregar=$("#listado tbody tr.seleccionado td:eq(13) strong").html();
        var entregadas=parseInt(solicitado-porentregar);
        if(solicitado>0 && entregadas==0 && rechazado==0 && cierre==0)
        { 
            var codigo=$("#listado tbody tr.seleccionado").attr('id');
            window.open("modificaretiqueta.php?codigo="+codigo,"Cantidad","width=502,height=510,top=100,left=400");
        }
        else
        {
           $('#mensaje').html("<strong>Para editar un pedido tiene que tener valores de entregadas, rechazadas y cierre de dia iguales a cero.</strong>");
           $('#myModal').modal('show'); 
           return;
        }
       }
    });
    
    var btn_seleccionado=''; //variable para saber que boton es ekl que fue seleccionado
    //para eliminar un pedido
    $('#btn_eliminar').on('click',function(){
       btn_seleccionado='eliminar';
       var band=-1;
       $("#listado tbody tr.seleccionado").each(function (index) 
        {
           band=index;
        });
        
       if(band!=0)
       {   //muestro ventana de error
           $('#mensaje').html("<strong>Para cancelar un pedido debe seleccionar una sola fila de la tabla</strong>");
           $('#myModal').modal('show'); 
           return;
       }
       else{
        //veo si solicitado>0, entregado,rechazado,cierr=0
        var solicitado=$("#listado tbody tr.seleccionado td:eq(6)").html();
        var rechazado=$("#listado tbody tr.seleccionado td:eq(10)").html();
        var cierre=$("#listado tbody tr.seleccionado td:eq(11)").html();
        
        var porentregar=$("#listado tbody tr.seleccionado td:eq(13) strong").html();
        var entregadas=parseInt(solicitado-porentregar);
        if(solicitado>0 && entregadas==0 && rechazado==0 && cierre==0)
        { 
           //mnuestro ventana de confirmaciom 
           $('#myModal1').find('#myModalLabel').html('Cancelar Pedido');
           $('#myModal1').find('#mensaje1').html('Est&aacute; seguro que desea cancelar este pedido?');
           $('#myModal1').modal('show'); 
           return;
        }
        else
        {
           $('#mensaje').html("<strong>Para cancelar un pedido tiene que tener valores de entregadas, rechazadas y cierre de dia iguales a cero.</strong>");
           $('#myModal').modal('show'); 
           return;
        }
       } 
    });
    
    //para archivar un pedido
    var cajas_archivar = [];// contendra los pedidos  que van a ser archivadas
    $('#btn_archivar').on('click',function(){
       btn_seleccionado='archivar';
       var band=-1;
       $("#listado tbody tr.seleccionado").each(function (index) 
        {
           band=index;
        });
        
       if(band==-1)
       {
           $('#mensaje').html("<strong>Para archivar un pedido debe seleccionar al menos una fila de la tabla</strong>");
           $('#myModal').modal('show'); 
           return;
       }
       else{
        cajas_archivar = [];
        var i=0;
        //de las filas seleccionadas solo se archivaran las que tengan en el boton de por entregar el valor 0
        $("#listado tbody tr.seleccionado").each(function(index){
            if($("td:eq('13') strong", this).html()==0){
              cajas_archivar[i]=$(this).attr('id'); 
              i++;
            }
        });
        $('#myModal1').find('#myModalLabel').html('Archivar Pedido');
        $('#myModal1').find('#mensaje1').html('Solo se archivar&aacute;n los pedidos con cantidades "Por entregar" igual a cero.</br>Est&aacute; seguro que desea archivar este pedido?');
   	$('#myModal1').modal('show');  
         
       } 
    });
    
    //para el boton de si
    $('#myModal1').find('#btn_si').on('click',function(){
        if(btn_seleccionado=='archivar'){
         $.ajax({
            data: "accion=archivar_pedido&cajas="+JSON.stringify(cajas_archivar),
            url:  "nuevoitem_pedido.php",
            type: "post",
            dataType: "json",
            success:  function (response) {

            },
            complete:function(){
                $('#myModal1').modal('hide');
                location.reload();
            }
         });
       }
       else if(btn_seleccionado=='eliminar'){
         var num_pedido=$("#listado tbody tr.seleccionado").attr('id');
         $.ajax({
            data: "accion=cancelar_pedido&nropedido="+num_pedido,
            url:   'nuevoitem_pedido.php',
            type:  'post',
            dataType: 'json',
            success:  function (response) {

            },
            complete:function(){
                $('#myModal1').modal('hide');
                location.reload();
            }
         });
       }
           
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
                                <li><a href="administration.php">
                                  <strong>EDI</strong>
                               </a>  
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
    <div class="row" style="margin-left:20px;margin-right:20px;">
    <div style="float: left;">
        <form action="pedidos_fincasExcel.php" method="post" target="_blank" id="FormularioExportacion">
                <button type="submit" class="btn btn-default" data-toggle="tooltip" aria-label="Exportar Excell" title = "Exportar Excell">
                    <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>
                </button>

            <input type="hidden" id="finca" name="finca" value="<?php echo $_GET['finca'] ?>" />
        </form> 
    </div>
    
    <div class="" style="float: left;">
        <button type="image" class="btn btn-primary" id="btn_filtro" data-toggle="tooltip" aria-label="Filtros" title = "Filtros">
           <span class="glyphicon glyphicon-filter" aria-hidden="true"></span>
        </button>
    </div>
    <div class="" style="float: left;">
        <button type="button" class="btn btn-primary" name="btn_nuevo" id="btn_nuevo" data-toggle="tooltip" aria-label="Nuevo pedido" title = "Nuevo pedido">
            <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
        </button>
    </div>    
    <div class="" style="float: right;">
        <button type="button" class="btn btn-primary" name="btn_archivar" id="btn_archivar" data-toggle="tooltip" aria-label="Archivar pedido" title = "Archivar pedido">
            <span class="glyphicon glyphicon-save" aria-hidden="true"></span>
        </button>
    </div>
    <div class="" style="float: right;">
        <button type="button" class="btn btn-primary" name="btn_eliminar" id="btn_eliminar" data-toggle="tooltip" aria-label="Eliminar pedido" title = "Eliminar pedido">
            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
        </button>
    </div>
    <div class="" style="float: right;">
        <button type="button" class="btn btn-primary" name="btn_editar" id="btn_editar" data-toggle="tooltip" aria-label="Editar pedido" title = "Editar pedido">
            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
        </button>
    </div>
</div>
 
<form id="form" name="form" method="post">
  <div class="row">
    	<h3><strong>LISTADO DE PEDIDOS DE LA FINCA <?php echo $_GET['finca'] ?></strong></h3>
  </div>
  <div class="row">
    <div class="col-md-2" style="float: right;">
        <button type="button" class="btn btn-primary" name="btn_deseleccionar" id="btn_deseleccionar" data-toggle="tooltip" aria-label="Deseleccionar todas las filas" title = "Deseleccionar todas las filas">
            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Deseleccionar
        </button>
    </div>
    <div class="col-md-2" style="float: right;">
        <button type="button" class="btn btn-primary" name="btn_seleccionar" id="btn_seleccionar" data-toggle="tooltip" aria-label="Seleccionar todas las filas" title = "Seleccionar todas las filas">
            <span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span> Seleccionar
        </button>
    </div>
    	
  </div>
    <div class="table-responsive" id="tabla_pedido">
  
    <table id="listado" border="0" align="center" class="table table-condensed table-responsive" >  
    <thead>
      <?php
      //Agrupar el reporte por destino
      $a = "SELECT distinct destino FROM tbletiquetasxfinca where archivada = 'No' AND estado!='5' AND finca='".$_GET['finca']."' order by destino DESC";
      $b = mysql_query($a,$conection) or die('Error seleccionando el origen');

      $TOTALSOL = 0;
      $TOTALENTSTRACK = 0;
      $TOTALENTTRACK = 0;
      $TOTALENT = 0;
      $TOTALRECH= 0;
      $TOTALDIF = 0;
      $TOTALCIERRE = 0;
      $TOTALREUT = 0;
      $TOTALPRECIO = 0;
      $cont = 0;

      while($fila = mysql_fetch_array($b)){
        echo '
            <th align="center"><strong>Salida de Finca</strong></th>
            <th align="center"><strong>Producto</strong></th>
            <th align="center"><strong>Desc. Prod. </strong></th>
            <th align="center"><strong>Fecha Vuelo</strong></th>
            <th align="center"><strong>Destino</strong></th>
            <th align="center"><strong>Precio Compra</strong></th>
            <th align="center"><strong>Ordenadas</strong></th>
            <th align="center"><strong>Sin Traquear</strong></th>
            <th align="center"><strong>Traqueadas</strong></th>
            <th align="center"><strong>Total Cajas Recibidas</strong></th>
            <th align="center"><strong>Rechazadas</strong></th>
            <th align="center"><strong>Cierre de Día</strong></th>
            <th align="center"><strong>Reutilizadas</strong></th>
            <th align="center"><strong>Por entregar</strong></th>

           </thead>
       <tbody>';
       $cont++;

        //Leer las fechas de los pedidios
        $sql = "SELECT distinct nropedido,fecha,fecha_tentativa FROM tbletiquetasxfinca where archivada = 'No' AND estado!='5' AND finca='".$_GET['finca']."' AND destino = '".$fila['destino']."' order by fecha";

        $val = mysql_query($sql,$conection);
         if(!$val){
                echo "<tr><td>".mysql_error()."</td></tr>";
         }else{
            $totalsol = 0;
            $totalentstrack = 0;
            $totalenttrack = 0;
            $totalent = 0;
            $totalrech= 0;
            $totaldif = 0;
            $totalcierre = 0;
            $totalreut = 0;
            while($row = mysql_fetch_array($val)){

                //recorro por cada fecha de entrega que exista en los pedidos
                $sql1 = "SELECT distinct finca,item, precio, destino FROM tbletiquetasxfinca WHERE nropedido = '".$row['nropedido']."' AND fecha = '".$row['fecha']."' AND fecha_tentativa = '".$row['fecha_tentativa']."' AND estado!='5' AND archivada = 'No' AND finca='".$_GET['finca']."' order by item";

                $val1 = mysql_query($sql1,$conection);
                    if(!$val1){
                        echo "<tr><td>".mysql_error()."</td></tr>";
                    }else{
                        while($row1 = mysql_fetch_array($val1)){

                            //Se cuenta cuantas solicitudes y entregas hay por cada finca e item
                            $sql2 = "SELECT SUM(solicitado) as solicitado, SUM(entregado) as entregado FROM tbletiquetasxfinca where finca ='".$row1['finca']."' AND item='".$row1['item']."' AND precio='".$row1['precio']."' AND destino='".$row1['destino']."' AND nropedido = '".$row['nropedido']."' AND archivada = 'No' AND estado!='5'";
                            $val2 = mysql_query($sql2,$conection) or die ("Error sumando las cantidades de solicitudes y entregas de las fincas");
                            $row2 = mysql_fetch_array($val2);

                            //Se cuenta cuantas solicitudes rechazadas hay por cada finca e item
                            $sql3 = "SELECT COUNT(*) as rechazado FROM tbletiquetasxfinca where finca ='".$row1['finca']."' AND item='".$row1['item']."' AND precio='".$row1['precio']."' AND destino='".$row1['destino']."' AND estado='2' AND nropedido = '".$row['nropedido']."' AND archivada = 'No'";
                            $val3 = mysql_query($sql3,$conection) or die ("Error sumando las cantidades de solicitudes y entregas de las fincas");
                            $row3 = mysql_fetch_array($val3);

                            //Se cuenta cuantas solicitudes con cierre de dia por cada finca e item
                            $sql4 = "SELECT COUNT(*) as cierre FROM tbletiquetasxfinca where finca ='".$row1['finca']."' AND item='".$row1['item']."' AND precio='".$row1['precio']."' AND destino='".$row1['destino']."' AND estado= '3' AND nropedido = '".$row['nropedido']."' AND archivada = 'No'";
                            $val4 = mysql_query($sql4,$conection) or die ("Error sumando las cantidades de solicitudes y entregas de las fincas");
                            $row4 = mysql_fetch_array($val4);

                            //Se cuenta cuantas solicitudes con cierre de dia por cada finca e item
                            $sql41 = "SELECT COUNT(*) as reutilizadas FROM tbletiquetasxfinca where finca ='".$row1['finca']."' AND item='".$row1['item']."' AND precio='".$row1['precio']."' AND destino='".$row1['destino']."' AND estado= '4' AND nropedido = '".$row['nropedido']."' AND archivada = 'No'";
                            $val41 = mysql_query($sql41,$conection) or die ("Error sumando las cantidades de solicitudes y entregas de las fincas");
                            $row41 = mysql_fetch_array($val41);

                            echo "<tr style='cursor:pointer;' id='".$row['nropedido']."' class='seleccionable'>";
                            echo "<td><strong>".$row['fecha']."</strong></td>";
                            echo "<td align='center'><strong>".$row1['item']."</strong></td>";

                            //Seleccionar la descripcion del item
                            $sql5 = "SELECT prod_descripcion FROM tblproductos where id_item ='".$row1['item']."'";
                            $val5 = mysql_query($sql5,$conection) or die ("Error seleccionando la descripcion del item");
                            $row5 = mysql_fetch_array($val5);

                            //Contar cantidad de cajas entregadas sin traquear
                            $sentencia = "SELECT * FROM tblcoldroom INNER JOIN tbletiquetasxfinca ON tblcoldroom.codigo = tbletiquetasxfinca.codigo where tblcoldroom.item = '".$row1["item"]."' AND entrada= 'Si' AND salida ='No' AND tblcoldroom.finca='".$row1['finca']."' AND tbletiquetasxfinca.archivada='No' AND tbletiquetasxfinca.estado='1' AND tbletiquetasxfinca.nropedido = '".$row['nropedido']."'";
                            $consulta = mysql_query($sentencia,$conection);
                            $Cantfila = mysql_num_rows($consulta);

                            //Contar cantidad de cajas entregadas traqueadas
                            $sentencia1 = "SELECT * FROM tblcoldroom INNER JOIN tbletiquetasxfinca ON tblcoldroom.codigo = tbletiquetasxfinca.codigo where tblcoldroom.item = '".$row1["item"]."' AND tblcoldroom.finca='".$row1['finca']."' AND  salida ='Si' AND tblcoldroom.tracking_asig !='' AND tbletiquetasxfinca.archivada='No' AND tbletiquetasxfinca.estado='1' AND tbletiquetasxfinca.nropedido = '".$row['nropedido']."'";
                            $consulta1 = mysql_query($sentencia1,$conection);
                            $Cantfila1 = mysql_num_rows($consulta1);							 


                            echo "<td>".$row5['prod_descripcion']."</td>";
                            echo "<td><strong>".$row['fecha_tentativa']."</strong></td>";
                            echo "<td align='center'>".$row1['destino']."</td>";
                            echo "<td align='center'>".$row1['precio']."</td>";
                            echo "<td align='center'>".$row2['solicitado']."</td>";
                            echo "<td align='center'>".$Cantfila."</td>";
                            echo "<td align='center'>".$Cantfila1."</td>";
                            $totalsol+= $row2['solicitado'];
                            echo "<td align='center'>".$row2['entregado']."</td>";
                            echo "<td align='center'>".$row3['rechazado']."</td>";
                            echo "<td align='center'>".$row4['cierre']."</td>";
                            echo "<td align='center'>".$row41['reutilizadas']."</td>";

                            //se restan las solicitudes - entregado - rechazado	
                            $totalrech+= $row3['rechazado'];
                            $totalentstrack += $Cantfila;
                            $totalenttrack += $Cantfila1;
                            $totalent+= $row2['entregado'];
                            $dif      = $row2['solicitado'] - $row2['entregado']- $row3['rechazado'] -$row4['cierre'] - $row41['reutilizadas'] ;
                            $totalcierre += $row4['cierre'];
                            $totalreut += $row41['reutilizadas'];
                            $totaldif+=$dif;
                            $totalprecio += $row1['precio']; 

                            if($dif == 0){
                                echo "<td align='center'><button type='button' class='btn btn-success' data-toggle='tooltip' data-placement='left' title = 'No hay  cajas pendientes'><strong>".$dif."</strong></button></td>";
                            }else{							
                                echo "<td align='center'><button type='button' class='btn btn-danger' data-toggle='tooltip' data-placement='rigth' title = 'Ver cajas por entregar'><a href= 'etiqxentregar.php?id=".$row['nropedido']."' title='Ver cajas pendientes'><strong>".$dif."</strong></a></button></td>";	
                            }

//                            //Para modificar un pedido tiene que tener solicitada mayor que cero y los demas estados en cero
//                            if($row2['solicitado'] > 0 & $row2['entregado']== 0 & $row3['rechazado'] == 0 & $row4['cierre']==0){						 
//                                echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-151-edit.png"  value="" data-toggle="tooltip" data-placement="left" title = "Modificar solicitud"  onclick = "modificar(\''.$row['nropedido'].'\')"/></td>';
//                            }else{
//                                echo '<td align="center"><input type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-151-edit.png" value="" data-toggle="tooltip" data-placement="left" title = "Para poder modificar la solictud, no puede haber sido rechazada ni entregada"  disabled="true"/></td>';
//                            }
//
//                            //Para cancelar un pedido tiene que tener solicitada mayor que cero y los demas estados en cero
//                            if($row2['solicitado'] > 0 & $row2['entregado']== 0 & $row3['rechazado'] == 0 & $row4['cierre']==0){
//                                echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-17-bin.png" value="" data-toggle="tooltip" data-placement="left" title = "Cancelar solicitud" " onclick = "cancelar(\''.$row['nropedido'].'\')"/></td>';
//                            }else{
//                                echo '<td align="center"><input type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-17-bin.png" value="" data-toggle="tooltip" data-placement="left" title = "Para poder cancelar la solictud, no puede haber sido rechazada ni entregada"  disabled="true"/></td>';
//                            }
//
//                                    //Para archivar un pedido tiene que tener diferencia 0
//                            if($dif == 0){
//                                echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-447-floppy-save.png"  value="" data-toggle="tooltip" data-placement="left" title = "Archivar solicitud" onclick = "archivar(\''.$row['nropedido'].'\')"/>';
//                                echo '<input name="cajas[]" type="checkbox" value="'.$row['nropedido'].'" data-toggle="tooltip" data-placement="left" title="Seleccionar pedido"/></td>';
//                            }else{
//                                echo '<td align="center"><input type="image" style="cursor:not-allowed" name="btn_cliente" id="btn_cliente" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-447-floppy-save.png"  value="" data-toggle="tooltip" data-placement="left" title = "Para poder archivar la solictud, debe tener 0 en Por entregar"  disabled="true"/>';
//                                echo '<input name="cajas[]" style="cursor:not-allowed" type="checkbox" data-toggle="tooltip" data-placement="left" title="Para poder marcar para archivar la solictud, debe tener 0 en Por entregar" disabled="true"/></td>';
//                            }
                            echo "</tr>";
                        }//FIN 3ER WHILE
                       }//FIN ELSE
    }//FIN 2DO WHILE
            echo "
                      <tr>
                      <td align='right'></td>
                      <td align='right'></td>
                      <td align='right'></td>
                      <td align='center'><strong>Total por país:</strong></td>
                      <td align='right'></td>
                      <td align='center'><strong>".$totalprecio."</strong></td>
                      <td align='center'><strong>".$totalsol."</strong></td>
                      <td align='center'><strong>".$totalentstrack."</strong></td>
                      <td align='center'><strong>".$totalenttrack."</strong></td>
                      <td align='center'><strong>".$totalent."</strong></td>
                      <td align='center'><strong>".$totalrech."</strong></td>
                      <td align='center'><strong>".$totalcierre."</strong></td>
                      <td align='center'><strong>".$totalreut."</strong></td>";

                      if($totaldif == 0){
                                    echo "<td align='center'><button type='button' class='btn btn-success btn-lg' data-toggle='tooltip' data-placement='right' title = 'Cajas faltan por entregar'><strong>0</strong></button></td>";
                            }else{							
                                    echo "<td align='center'><button type='button' class='btn btn-danger btn-lg' data-toggle='tooltip' data-placement='right' title = 'Cajas faltan por entregar'><strong>".$totaldif."</strong></button></td>";
                                    }
                      echo "</tr>";	

                      //Sumar alos totales
                      $TOTALPRECIO += $totalprecio;
                      $TOTALSOL    += $totalsol;
                      $TOTALENTSTRACK    += $totalentstrack;
                      $TOTALENTTRACK   += $totalenttrack;
                      $TOTALENT    += $totalent;
                      $TOTALRECH   += $totalrech;
                      $TOTALCIERRE += $totalcierre;
                      $TOTALREUT += $totalreut;
                      $TOTALDIF    += $totaldif;

                      //Resetear los subtotales
                      $totalprecio = 0;
                      $totalsol    = 0;
                      $totalentstrack   = 0;	
                      $totalenttrack    = 0;	
                      $totalent    = 0;	
                      $totalrech   = 0;
                      $totalcierre = 0;
                      $totalreut = 0;
                      $totaldif    = 0;


                       }//FIN ELSE		   
      }//FIN 1ER WHILE
      echo "
                <tr>
                <td align='right'></td>
                <td align='right'></td>
                <td align='right'></td>				  
                <td align='center'><strong>Total General:</strong></td>
                <td align='right'></td>
                <td align='center'><strong>".$TOTALPRECIO."</strong></td>
                <td align='center'><strong>".$TOTALSOL."</strong></td>
                <td align='center'><strong>".$TOTALENTSTRACK."</strong></td>
                <td align='center'><strong>".$TOTALENTTRACK."</strong></td>
                <td align='center'><strong>".$TOTALENT."</strong></td>
                <td align='center'><strong>".$TOTALRECH."</strong></td>
                <td align='center'><strong>".$TOTALCIERRE."</strong></td>
                <td align='center'><strong>".$TOTALREUT."</strong></td>";

                if($TOTALDIF == 0){
                              echo "<td align='center'><button type='button' class='btn btn-success btn-lg' data-toggle='tooltip' data-placement='rigth' title = 'Cajas faltan por entregar'><strong>0</strong></button></td>";
                      }else{							
                              echo "<td align='center'><button type='button' class='btn btn-danger btn-lg' data-toggle='tooltip' data-placement='rigth' title = 'Cajas faltan por entregar'><strong>".$TOTALDIF."</strong></button></td>";
                              }
                echo "</tr>";	
       mysql_close($conection);
      ?>
      </tbody>   
    </table>
  </div> <!-- /table-responsive -->
</form> 
</div> 
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
   
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Error</h4>
      </div>
      <div class="modal-body">
        <p id="mensaje"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        
      </div>
    </div>
  </div>
</div>
   
    <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Archivando Pedidos</h4>
      </div>
      <div class="modal-body">
          <p id="mensaje1">Está seguro que desea archivar estos pedidos</p>
      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-default" id="btn_si">Si</button>
       <button type="button" class="btn btn-default" data-dismiss="modal" id="btn_no">No</button>
      </div>
    </div>
  </div>
</div> 
   
<?php include 'nuevaetiqueta.php';   ?>
</div>     
 </div>
 </body>
</html>

