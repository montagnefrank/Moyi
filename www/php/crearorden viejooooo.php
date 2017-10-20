<?php
//ini_set('display_errors', 'On');
//ini_set('display_errors', 1);
session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");

$user     =  $_SESSION["login"];
$passwd   =  $_SESSION["passwd"];
$rol      =  $_SESSION["rol"];

$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());

$id = $_GET['id'];

if($_GET['id'] == 1 ) {
  echo '<script>window.open("asig_destino.php","Cantidad","width=400,height=300,top=100,left=400")</script>';
  //header('Location: crearorden.php');
}

//Recogiendo el id del usuario
$sqluser = "SELECT id_usuario FROM tblusuario WHERE cpuser = '".$user."'";
$queryuser = mysql_query($sqluser,$link);
$rowuser = mysql_fetch_array($queryuser);

$usuario = $rowuser['id_usuario'];

$cajas[0]=0;

$id_order = 0;
$error = $_GET['error'];

$idcliente = $_GET['codigo'];  

$ponumberManual = $_GET['ponumberManual'];

if ($_GET['codigo']!="") {
  $_SESSION['idcliente'] = $idcliente;
}
else {
  $idcliente = $_SESSION['idcliente'];
}

//recogiendo el valor de ponumber manual si existe

if ($_GET['ponumberManual']!="") {
  $_SESSION['ponumberManual'] = $ponumberManual;
}
else {
  $ponumberManual = $_SESSION['ponumberManual'];
}

if($idcliente) {

  $sql = "select * from tblcliente where codigo = '".$idcliente."';"; 

  $query = mysql_query($sql,$link);
  $row9   = mysql_fetch_array($query);

//Utilizando el último PO autogenerado en la tabla tbltransaccion
//tener en cuenta que hay que revisar que ese po no exista ya en tbldetalleorden
//hacer esto mismo a la hora de insertar definitivamente para garantizar insertar con el ultimo po

  $sqlpotran = "SELECT * FROM tbltransaccion WHERE Ponumber = (SELECT MAX(Ponumber) FROM tbltransaccion)";
  $querypotran = mysql_query($sqlpotran, $link);
  $rowpotran = mysql_fetch_array($querypotran);

  if($_SESSION['ponumberManual']!=""){
    $ponumbershow = $_SESSION['ponumberManual'];
  }
  else{
    $ponumbershow = $rowpotran['Ponumber']+1;
  }

}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Punto de Venta</title>
  <link href="../images/favicon.ico"  rel="icon" type="image/png" />
  <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" media="all"  />
  <link href="../bootstrap/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all"  />
  <link href="../bootstrap/css/prettify-1.0.css" rel="stylesheet" type="text/css" media="all"  />
  <link href="../bootstrap/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/bootstrap-submenu.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/octicons.css" rel="stylesheet" type="text/css">
  <link href="../bootstrap/css/zenburn.css" rel="stylesheet" type="text/css">
  
    <script src="../bootstrap/js/jquery.min.js"></script>
  <script src="../bootstrap/js/bootstrap.min.js"></script>
  <script src="../bootstrap/js/bootstrap-submenu.js"></script>
  <script src="../js/moment-with-locales.js"></script>
  <script src="../bootstrap/js/bootstrap-datetimepicker.js"></script>
  <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js" defer></script>
  <script src="../bootstrap/js/docs.js" defer></script>
  <style type="text/css">
      .my-error-class {
          color:red;
      }
      /*.my-valid-class {
          color:green;
      }*/
      li a{
      	cursor:pointer;/*permite que se despliegue el dropdown en ipad, que sin esto no se muestra*/
      }
  </style>
  <script type="text/javascript">
    function marcar() {
      for (var i = 0; i < document.form.elements.length; i++) {
        if(document.form.elements[i].type == 'checkbox'){
          document.form.elements[i].checked = !(document.form.elements[i].checked);
        }
      }
    }
  </script>
  <script> 
    function modificar(valor){
    var v=valor;
    window.open("modificaritem_venta.php?codigo="+v,"Cantidad","width=550,height=400,top=25,left=350");
    return false;
  }
  </script>
  <script> 
  function eliminar(valor){
    var v=valor;
    window.open("eliminaritem_venta.php?codigo="+v,"Cantidad","width=300,height=150,top=300,left=400");
    return false;
  }
  </script>
  <script> 
    function nueva(){
      //var v1=valor1;
      var v1 = "<?php echo $row9['codigo']; ?>" ;
      var v2 = document.getElementById('destino1').value;
      //alert(v2);
      window.open("guardardestino.php?codcliente="+v1+"&destino="+v2,"Cantidad","width=450,height=380,top=200,left=400");
      return false;
    }
    function InsertarCliente(){
      window.open("nuevocliente.php","Cantidad","width=500,height=630,top=10,left=350");
      return false;
    }
    function InsertarPO(){
      //var v1=valor1;
      //var v1 = "<?php echo $row9['codigo']; ?>" ;
      //var v2 = document.getElementById('destino1').value;
      //alert(v2);
      //window.open("insertarPO.php?codcliente="+v1+"&destino="+v2,"Cantidad","width=370,height=350,top=200,left=400");
      if(document.getElementById('insertar_ponumber').value==''){
        alert('Debe insertar un Ponumber');
      }
      else{
        var v1 = "<?php echo $_SESSION['idcliente']; ?>" ;
        if(v1==''){
          alert('Debe seleccionar un Cliente');
        }
        else{
          document.getElementById('ponumber').value = document.getElementById('insertar_ponumber').value;
          document.getElementById('insertar_ponumber_hidden').value = document.getElementById('insertar_ponumber').value;
          var v2 = document.getElementById('insertar_ponumber').value;
          location.href = "crearorden.php?ponumberManual="+v2;
        }
      }
      return false;
    }

    function recogerCheck(){
      var v1 = "<?php echo $_SESSION['idcliente']; ?>" ;
      if(v1==''){
        alert('Debe seleccionar un Cliente');
      }
      else{
        window.open("recogercheck_item.php?id=1","Cantidad","width=370,height=350,top=200,left=400");
        //formaction="recogercheck_item.php?id=1"
      }
      return false;
    }

    function nuevoDestino(){
      var v2 = "<?php echo $_SESSION['idcliente']; ?>" ;
      if(v2==''){
        alert('Debe seleccionar un Cliente');
      } 
      else{
        var v1 = "<?php echo $row9['codigo']; ?>" ;
        window.open("nuevodestino.php?codcliente="+v1,"Cantidad","width=450,height=650,top=200,left=400");
      }

      return false;
    }
    function nuevoItem_Venta(){
      var v2 = "<?php echo $_SESSION['idcliente']; ?>" ;
      if(v2==''){
        alert('Debe seleccionar un Cliente');
      } 
      else{
        var v1 = "<?php echo $row9['codigo']; ?>" ;
        window.open("nuevoitem_venta.php?codcliente="+v1,"Cantidad","width=450,height=400,top=200,left=400");
      }
      return false;
    }
    function gestionarDestinos(){
      window.open("gestionardestinos.php");
      return false;
    }

    function nuevoItem(){
      //var v1=valor1;
      var v1 = "<?php echo $row9['codigo']; ?>" ;
      var v2 = document.getElementById('destino1').value;
      //alert(v2);
      window.open("guardardestino.php?codcliente="+v1+"&destino="+v2,"Cantidad","width=370,height=350,top=200,left=400");
      return false;
    }
   /* function eliminar(valor){
    var v=valor;
    window.open("eliminarusuario.php?codigo="+v,"Cantidad","width=300,height=150,top=300,left=400");
    return false;
  }*/
  </script>

  <!-- Funcion de Jquery para buscador -->
  <!-- Referencias nuevas a JQUERY -->
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
  <link href="../css/jqueryui.css" type="text/css" rel="stylesheet"/>
  <script>
  jQuery.validator.addMethod("noSpace", function (value, element) { 
    return value.indexOf(" ") < 0 && value != ""; 
  }, "No space please and don't leave it empty");

    // When the browser is ready...
    $(function() {
        $("#item1").autocomplete({
              source: "buscar_item.php",
              minLength: 2
        });
    });
  </script>


  <script type="text/javascript">
   $(document).ready(function(){
  		//tol-tip-text
  		$(function () {
  		  $('[data-toggle="tooltip"]').tooltip()
  		});
      });
  </script>

  <script>
    $(document).ready(function() {
        $("#resultadoBusqueda").html('<p></p>');
    });

    function buscar() {
        var textoBusqueda = $("input#busqueda").val();
     
         if (textoBusqueda != "") {
            $.post("buscarCliente.php", {valorBusqueda: textoBusqueda}, function(mensaje) {
                $("#resultadoBusqueda").html(mensaje);
             }); 
         } else { 
            $("#resultadoBusqueda").html('<p></p>');
            };
    };
  </script>

  <!-- Fin de funcion jquery para buscador -->

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
              <a class="navbar-brand" href="../main.php?panel=mainpanel.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
      </div>
 
  <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
 <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
               <?php
			  if($rol == 3 || $rol == 8){?>
			  	<li><a href="./php/reenvioordenes.php" ><strong>Reenviar Órdenes</strong></a></li>
			  <?php }?> 
 
			  <?php 
              if($rol<= 2){ ?>
                   <li  class="active" >
                        <a tabindex="0" data-toggle="dropdown">
                          <strong>Venta</strong><span class="caret"></span>
                        </a>
                         <ul class="dropdown-menu" role="menu">
                              <li><a href="crearorden.php"><strong>Punto de Venta</strong></a></li>
                            <li class="divider"></li>
                            <li class="dropdown-submenu">
                                <a tabindex="0" data-toggle="dropdown"><strong>Cargar</strong></a>            
                                <ul class="dropdown-menu">
                                  <li><a href="gestionarordenes.php" ><strong>Modificar Órdenes</strong></a></li> 
                                  <li class="divider"></li>
                                  <li><a href="reenvioordenes.php" ><strong>Reenviar Órdenes</strong></a></li>
                                </ul>
                            </li>
                      </ul>
                  </li>
                      <?php
				}
                       ?>
              <li>
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
                    <li class="dropdown">
            
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
                       </ul> -->
                    </li>	
                 <?php
				 }
				 ?>
                 
                 <?php if($rol == 1){  ?>
			     	<li ><a href="usuarios.php"><strong>Usuarios</strong></a></li>
				<?php }else{
					$sql   = "SELECT id_usuario from tblusuario where cpuser='".$user."'";
					$query = mysql_query($sql,$link);
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
<?php
  if($error == 2){
      	echo '<div class="alert alert-danger" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Error:</span>
            ¡Error!, al crear la orden, por favor revise los datos.
            </div>';
  }
  else{
  	if($error == 1)
  	echo '<div class="alert alert-success" role="alert">
          <span class="glyphicon glyphicon-su" aria-hidden="true"></span>
          <span class="sr-only">Éxito:</span>
          ¡Orden creada satisfactoriamente!</div>';
  }
?>

  <form id="form" name="form" method="post" novalidate action="" .error { color:red}>

  <!-- <form id="frmCliente" name="frmCliente" method="post" novalidate action="" .error { color:red}> -->
    <h3><strong>Punto de Venta</strong></h3>
    <div class="table-responsive">
      <table width="50%" border="0" align="center" class="table table-striped">
        <tr align="right">
          <td width="600"></td>
          
          <td><input type="button" class="btn btn-primary" id="btn_insertarCliente" name="btn_insertarCliente" value="Añadir Cliente" onclick="InsertarCliente()"/></td>
          <td><strong>Buscar Cliente </strong><input type="text" name="busqueda" autofocus id="busqueda" value="" placeholder="" maxlength="30" autocomplete="off" onKeyUp="buscar();"/></td>
          <!-- <td><input type="text" id="buscaCliente" size="30" autofocus tabindex="0"/></td>
          <td><button class="btn btn-primary" data-placement="right" type="submit" value="Buscar cliente" title="Buscar cliente">Buscar Cliente</button></td> -->
        </tr>
      </table>
    </div>
  <!-- </form>   -->
  <div id="resultadoBusqueda"></div>

<!-- Mostrar los datos del cliente -->
  <div class="table-responsive">
    <table width="50%" border="0" align="center" class="table table-striped" >
            <tr>               
                <td><strong>Ponumber:</strong></td>
                <td><input type="text" id="ponumber" name="ponumber" value="<?php echo $ponumbershow;?>" size="30" readonly tabindex="0"/><font color="#CC0000"></font></td>
                <td><input type="button" class="btn btn-primary" id="btn_insertarPO" name="btn_insertarPO" value="Utilizar PO" onclick="InsertarPO()"/></td>
                <td>
                  <input type="text" id="insertar_ponumber" name="insertar_ponumber" value="<?php if($_SESSION['ponumberManual']!='') echo $_SESSION['ponumberManual']; ?>" size="15" tabindex="0"/><font color="#CC0000"></font>
                  <input type="hidden" id="insertar_ponumber_hidden" name="insertar_ponumber_hidden" value="" />
                </td>
            </tr>
            <tr>
              <td></td>
            </tr>
            <tr>
                <td><strong>Custnumber:</strong></td>
                <td>
                <?php
                  //$nro = rand (1,1000000000);
                  //echo '<input type="text" id="custnumber" name="custnumber" value="'.$nro.'" readonly="readonly" size="20"/></td>';
                  echo '<input type="text" id="custnumber" name="custnumber" value="'.$idcliente.'" readonly="readonly" size="20"/></td>';
                ?>
                <td><strong>Cliente:</strong></td>
                <td><input type="text" id="showCliente" readonly name="showCliente" value="<?php echo $row9['empresa'];?>"/></td>   
            </tr>
            <tr>                               
              <td><strong>Cobrar a Cod. Postal:</strong></td>
              <td><input type="text" id="billzip" name="billzip" readonly value="<?php echo $row9['zip'];?>" tabindex="9" /></td>   
              <td><strong>Cobrar a:</strong></td>
              <td><input type="text"  id="soldto" name="soldto" readonly value="<?php echo $row9['empresa'];?>" tabindex="8" /><font color="#FF0000"></font></td>
              <td><strong>Cobrar a2:</strong></td>
              <td><input type="text" id="soldto2" name="soldto2" readonly value="<?php echo $row9['empresa2'];?>" tabindex="9" /></td>
              <!-- <td><input type="button" class="btn btn-primary" id="showDestino" name="showDestino" value="Añadir destino" onclick="mostrar()"/></td> -->
            </tr> 
            <tr>
           <!--   <td>
                <input type="text" id="destino1" name="destino1" value="" style='display:none;'/>
                <?php //echo '<button type="button" class="btn btn-primary" data-toggle="tooltip" id="btn_add_Destino1" data-placement="right" title = "Añadir destino" onclick = "nueva()" style="display:none;">'; ?>
                <input type="image" style='display:none;' name='add_Destino1' id='add_Destino1' src='../bootstrap/fonts/glyphicons/glyphicons/glyphicons-7-user-add.png'/>
              </td>
            </tr>   -->
                        <!-- Ocultar y mostrar destinos  -->
            <script type="text/javascript">
              function mostrar(){
                document.getElementById('destino1').style.display = 'inline-block';
                document.getElementById('btn_add_Destino1').style.display = 'inline-block';
                document.getElementById('destino1').focus();
                document.getElementById('add_Destino1').style.display = 'inline-block';

                //document.write('<td colspan="4" align="right"><td><input type="button" class="btn btn-primary" id="showDestino1" name="showDestino1" value="Añadir destino" onclick="mostrar()"/></td></td>');

                  //if(document.getElementById('destino1').value!=''){
                  //}
              }
            </script>
            <tr>
              <td><strong>Cobrar a Dirección:</strong></td>
                <td><input type="text" id="billaddress" name="billaddress" readonly value="<?php echo $row9['direccion'];?>" tabindex="9" size="30"/><font color="#FF0000"></font></td>
                <td><strong>Cobrar a Dirección2:</strong></td>
                <td><input type="text" id="billaddress2" name="billaddress2" readonly value="<?php echo $row9['direccion2'];?>" tabindex="9" size="30"/></td>
                <td><strong>Cobrar a Contacto:</strong></td>
                <td><input type="text" id="billphone" name="billphone" readonly value="<?php echo $row9['telefono'];?>" tabindex="11"/></td>
            </tr>
            <tr>
              <td><strong>Cobrar a Ciudad:</strong></td>
                <td><input type="text" id="billcity" name="billcity" readonly value="<?php echo $row9['ciudad'];?>" tabindex="9" size="30"/></td>
                <td colspan="1"><strong>Cobrar a Estado:</strong></td>
                <td><input type="text" id="billstate" name="billstate" readonly value="<?php echo $row9['estado'];?>" tabindex="9" size="30"/></td>
                <td><strong>Cobrar a País:</strong></td>
                <td><input type="text" id="billcountry" name="billcountry" readonly value="<?php echo $row9['pais'];?>" tabindex="9" size="18"/><font color="#FF0000"></font></td>  
            </tr>
    </table> 
  </div> <!-- Table responsive -->
      <!--Fin de la tabla de datos del cliente -->

<!-- BUSCADOR DE ITEMS   -->
<!-- Mostrar los datos del cliente -->
  <!-- <h3><strong>Añadir item</strong></h3>  -->
  
  <!-- <form id="frmCompraItem" name="frmCompraItem" method="post"> -->
   <div class="table-responsive">
    <table width="50%" border="0" align="center" class="table table-striped" >
      <tr align="center">               
        <td>
          <input type="button" class="btn btn-primary" id="nuevoitem_venta" name="nuevoitem_venta" value="Añadir item" onclick="nuevoItem_Venta()"/>
          <td><input type="button" class="btn btn-primary" id="showDestino" name="showDestino" value="Añadir destino" onclick="nuevoDestino()"/></td>
          <td><input type="button" class="btn btn-primary" id="showGestionarDestinos" name="showGestionarDestinos" value="Gestionar destinos" onclick="gestionarDestinos()"/></td>

          <!-- <input type="button" class="btn btn-primary" id="showitem" name="showitem" value="Añadir item" onclick="mostraritem()"/> -->
        <!--  <input type="text" id="item1" name="item1" value="" placeholder="Producto" size="40" size="50"/>
          <input type="text" id="cantidad1" name="cantidad1" placeholder="Cantidad" value="" size="10"/>
          <input type="text" id="precioUnitario" name="precioUnitario" placeholder="Precio Unitario" value="" size="10"/>
          <textarea type="text" id="mensaje1" name="mensaje1" tabindex="19" placeholder="Mensaje..." size="70" style="width:70;height:150;resize:none;"/></textarea>  -->

          <!-- <input type="text" id="destino_item1" name="destino_item1" placeholder="Destino" value="" style='display:none;'/> -->
          <!-- <select type="text" name="destino_item1" id="destino_item1" tabindex="17" style="width:240px; display:none;"> -->
            <?php /*
              //Consulto la bd para obtener solo los id de item existentes
              $sql   = "SELECT * FROM tbldestinos WHERE codcliente= '".$row9['codigo']."'";
              $query = mysql_query($sql,$link);
                //Recorrer los iteme para mostrar
              while($row1 = mysql_fetch_array($query)){
                    
                //ARREGLAR!!!!

                    //echo "<option value=''".$row1["destino"]."''</option>"; 
                    echo '<option value="'.$row1["iddestino"].'">'.$row1["destino"].'</option>'; 
              }
              */
            ?>                       
          <!-- </select>  -->


       <!--   <button type="submit" class="btn btn-primary" data-toggle="tooltip" id="btn_add_Item" name="btn_add_Item" data-placement="right" title = "Añadir Item">
          <input type="image" name='add_Item1' id='add_Item1' src='../bootstrap/fonts/glyphicons/glyphicons/glyphicons-7-user-add.png'/>  -->
        </td>
      </tr>
                  <!-- Ocultar y mostrar destinos  -->
      <script type="text/javascript">
        function mostraritem(){ /*
          document.getElementById('item1').style.display = 'inline-block';
          //document.getElementById('destino_item1').style.display = 'inline-block';
          document.getElementById('cantidad1').style.display = 'inline-block';
          document.getElementById('btn_add_Item').style.display = 'inline-block';
          document.getElementById('item1').focus();
          document.getElementById('add_Item1').style.display = 'inline-block';
          document.getElementById('precioUnitario').style.display = 'inline-block';
          document.getElementById('mensaje1').style.display = 'inline-block';
          */
          //document.write('<td colspan="4" align="right"><td><input type="button" class="btn btn-primary" id="showDestino1" name="showDestino1" value="Añadir destino" onclick="mostrar()"/></td></td>');

            //if(document.getElementById('destino1').value!=''){
            //}
        }
      </script>
  </table> 
  <!--</form>  --> <!-- Fin del formulario frmCompraItem-->
</div> <!-- Table responsive -->
<!--Fin de la tabla de items -->



    <!-- <h3><strong>Registrar Orden</strong></h3> -->
    <div class="table-responsive">
    <table width="50%" border="0" align="center" class="table table-striped" >
            <tr>
                <td width="90"><strong>Fecha de Orden:</strong></td>
                <td colspan="2">
                    <div class='input-group date' id='datetimepicker1'>
                            <input type='text' class="form-control" name="orddate" id="orddate" value="<?php echo date ('Y-m-d')?>" tabindex="12"  placeholder="Fecha de orden" required />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                    </div>
                    <script type="text/javascript">
                          $(function () {
                              $('#datetimepicker1').datetimepicker({
                                  format: 'YYYY-MM-DD'
                              });
                            });
                      </script>
                </td>
                <td><strong>Fecha de Entrega:</strong></td>
                <td colspan="2">
                    <div class='input-group date' id='datetimepicker2'>
                            <input type='text' class="form-control" id="deliver" name="deliver" value="<?php echo date ('Y-m-d')?>" tabindex="13"  placeholder="Fecha de entrega" required />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                    </div>
                    <script type="text/javascript">
                          $(function () {
                              $('#datetimepicker2').datetimepicker({
                                  format: 'YYYY-MM-DD'
                              });
                            });
                      </script>
                </td> 
              <td><strong>Fecha de vuelo:</strong></td>
                <td colspan="2">
                    <div class='input-group date' id='datetimepicker3'>
                            <input type='text' class="form-control" id="shipdt" name="shipdt" value="<?php 
                  $fecha = date('Y-m-d');
                  $nuevafecha = strtotime ( '-3 day' , strtotime ( $fecha ) ) ;
                  $nuevafecha = date ( 'Y-m-d' , $nuevafecha );       
                  echo $nuevafecha?>" tabindex="14"  placeholder="Fecha de vuelo" required />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                    </div>
                    <script type="text/javascript">
                          $(function () {
                              $('#datetimepicker3').datetimepicker({
                                  format: 'YYYY-MM-DD'
                              });
                            });
                      </script>
                </td> 
              <td><strong>SatDel:</strong></td>
                <td colspan="2">
                  <select  type="text" name="satdel" id="satdel" tabindex="15">
                        <option selected="selected">N</option>
                        <option>Y</option>  
                  </select>
                </td> 
            </tr>
        </table> 



<!--Mostrar los items que se han ido añadiendo para esa compra -->
<!-- <form id="form1" name="form1" method="post">   -->
<div class="table-responsive">
<table width="50%" border="0" align="center" class="table table-striped" > 
  <tr>
    <td colspan="5" align="center">
      <h3><strong>Listado de Items a comprar</strong></h3>
    </td>
    <td align="right">
    <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="rigth" title = "Asignar Destino">
    <input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" value="" title = "Asignar Destino" heigth="30" width="30" src="../images/airplane.png" formaction="recogercheck_item.php?id=1" />
    <!-- onclick="filtrar(1)"/>-->
    </button>
    </td>    
    <td>
    <!-- <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="rigth" title = "Asignar Guía Hija">
      <input type="image" name="btn_cliente1" id="btn_cliente1" value="" src="../images/hija.png" heigth="40" width="30" formaction="recogercheck.php?id=2"/>
    </button>  -->
    </td>
  </tr>
  <tr>
    <td align="center"><strong>Código de Producto</strong></td>
    <td align="center"><strong>Producto</strong></td>
    <td align="center"><strong>Cantidad</strong></td>
    <td align="center"><strong>Precio Unitario</strong></td>
    <td align="center"><strong>Mensaje</strong></td>
    <td align="center"><strong>Destino</strong></td>
    <td align="center"><strong>Editar</strong></td>
    <td align="center"><strong>Eliminar</strong></td>
    <td align="center"><strong><input type="checkbox" value="0" onchange="marcar()" title="Marcar todos"/></strong></td>
  </tr>
  <?php
  
  //if(!isset($_POST['filtrar'])){
     //$sql =   "SELECT * FROM tblcoldroom WHERE fecha_tracking <= '".date('Y-m-d')."' AND salida='Si' AND (guia_madre = 0 OR guia_hija = 0)";
     
     //$sql = "SELECT * FROM tblcarro_venta INNER JOIN tblproductos ON tblcarro_venta.id_item=tblproductos.id_item WHERE tblcarro_venta.codcliente = '".$row9['codigo']."'";
     $sql = "SELECT * FROM tblcarro_venta INNER JOIN tblproductos ON tblcarro_venta.id_item=tblproductos.id_item LEFT JOIN tbldestinos ON tbldestinos.iddestino = tblcarro_venta.iddestino WHERE tblcarro_venta.codcliente = '".$row9['codigo']."' AND id_usuario= '".$usuario."'";

     //echo $sql;


     $val = mysql_query($sql,$link);
        if(!$val){
        echo "<tr><td>".mysql_error()."</td></tr>";
         }else{
           $cant = 0;
           while($row = mysql_fetch_array($val)){
             
             
            $cant ++;
            echo "<tr>";
            echo "<td align='center'><strong>".$row['id_item']."</strong></td>";
            echo "<td align='center'><strong>".$row['prod_descripcion']."</strong></td>";
            echo "<td><strong>".$row['cantidad']."</strong></td>";
            echo "<td align='center'>".$row['preciounitario']."</td>";
            echo "<td align='center'>".$row['mensaje']."</td>";
            echo "<td align='center'>".$row['destino']."</td>";
            echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_modificaritem" id="btn_modificaritem" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-151-edit.png" value="" data-toggle="tooltip" data-placement="left" title = "Modificar Item" onclick = "modificar('.$row['idcompra'].')"/></td>';
            echo '<td align="center"><input type="image" style="cursor:pointer" name="btn_eliminaritem" id="btn_eliminaritem" src="../bootstrap/fonts/glyphicons/glyphicons/glyphicons-17-bin.png" value="" data-toggle="tooltip" data-placement="left" title = "Eliminar Item" onclick = "eliminar('.$row['idcompra'].')"/></td>';
            echo '<td align="center"><input name="cajas[]" type="checkbox" value="'.$row['idcompra'].'" title="Marcar Caja"/></td>';               
            echo "</tr>";
             
         }
        echo "
            <tr>
              <td align='right'><strong>".$cant. "</strong></td>
              <td>Item(s) encontrados</td>
            </tr>";                     
         }
?>
            <tr>
              <td></td>
              <td></td>
              <td align="right"></td>
              <td width="83"><button name="Crear" type="submit" value="Punto de Venta" class="btn btn-primary" tabindex="30" data-toggle="tooltip" data-placement="left" title="Registrar Orden">Registrar</button></td>
              <td width="153"><button name="Cancelar" id="Cancelar" type="submit" value="Cancelar"  class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="Cancelar" tabindex="31">Cancelar</button></td>
            </tr>



 </table>
 </div> <!-- table responsive-->
<!-- </form>   -->
<!--Fin de la tabla de items a comprar -->




  </form>
</div> <!-- /table-responsive --> 
</div> <!-- /panel body --> 
   
           <div class="panel-heading">
              <div class="contenedor" align="center">
                <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2015 versión 3</strong>
                <br>
                <strong><a href="http://www.bit-store.ec/index.php/contactenos/"  style="color:white">Info</a> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></strong>
              </div>
           </div>

<?php

/*if(isset($_POST["btn_add_Item"])){

  $item1= $_POST['item1'];
  $cantidad1= $_POST['cantidad1'];
  $precioUnitario = $_POST['precioUnitario'];
  $mensaje1 = $_POST['mensaje1'];

  //Insertar los datos en la tabla del carro venta

  //Recoger id_item
  $sqlitem = "SELECT * FROM tblproductos WHERE prod_descripcion = '".$item1."'";
  $queryitem = mysql_query($sqlitem,$link);
  $rowitem = mysql_fetch_array($queryitem);

  $item1 = $rowitem['id_item'];

  $sqlcarro = "INSERT INTO tblcarro_venta (codcliente, id_item, cantidad, preciounitario, mensaje) VALUES ('".$idcliente."','".$item1."','".$cantidad1."','".$precioUnitario."','".$mensaje1."')";
  $query_carro = mysql_query($sqlcarro,$link);

  echo "<script> window.location.href='crearorden.php'</script>";

} // Fin de isset btn_add_Item

*/

if(isset($_POST["Crear"])){ 

//Recoger todos los datos del cliente

  $sqlsoldto = "SELECT * from tblcliente WHERE codigo = ".$idcliente." ";
  $querysoldto = mysql_query($sqlsoldto,$link);
  $rowsoldto = mysql_fetch_array($querysoldto);

  //datos de la tabla tblsoldto
  $soldto      = $rowsoldto['empresa'];
  $soldto2     = $rowsoldto['empresa2']; 
  $stphone     = $rowsoldto['telefono'];
  $adddress    = $rowsoldto['direccion'];
  $adddress2   = $rowsoldto['direccion2'];
  $city        = $rowsoldto['ciudad'];
  $state       = $rowsoldto['estado'];
  $billzip     = $rowsoldto['zip'];
  $country     = $rowsoldto['pais'];
  $billmail    = $rowsoldto['mail'];


  //Común para todos
  $orddate     = $_POST['orddate'];
  $deliver     = $_POST['deliver'];
  $satdel      = $_POST['satdel'];
  
  //$ponumber    = $_POST['ponumber']; YA

  $sqlpoins = "SELECT * FROM tbltransaccion WHERE Ponumber = (SELECT MAX(Ponumber) FROM tbltransaccion)";
  $querypoins = mysql_query($sqlpoins, $link);
  $rowpoins = mysql_fetch_array($querypoins);

  
  //$ponumberManual  = $_POST['insertar_ponumber_hidden'];

  if($ponumberManual!=''){

    $ponumber = $ponumberManual;
  }
  else{

  $ponumber = $rowpoins['Ponumber']+1;

  }


  $custnumber  = $idcliente; 

  //Recorrer los registros de tblcarro_venta//

  $sqlins = "SELECT * FROM tblcarro_venta WHERE codcliente = '".$idcliente."' AND id_usuario = '".$usuario."' ";
  $queryins = mysql_query($sqlins,$link);

  //Verifico que la orden no este registrado en la bd (RESTRICION PARA SUBIR ORDENES)   //
  
  $sqlPO   = "SELECT tbldetalle_orden.id_orden_detalle FROM tbldetalle_orden WHERE tbldetalle_orden.Ponumber = '".$ponumber."' ";
  //echo $sqlPO;
  $queryPO = mysql_query($sqlPO,$link);
  $rowPO     =mysql_fetch_array($queryPO);
  //verifico si hay datos 
  $ray = mysql_num_rows($queryPO);
  if($ray > 0 ){ //Si el item esta registrado uso su detalles
    //echo "<font color='red'>La orden ".$orden." con Ponumber: ".$Ponumber." y Custnumber: ".$CUSTnbr." ya fue insertada."."</font><br>";
    echo "<script> alert ('Ese Ponumber ya está siendo utilizado por otra orden');</script>";
    exit();
  }

//Verificar que todos los items en el carro de venta tengan un destino

  $sqldestcheck = "SELECT * FROM tblcarro_venta WHERE codcliente = '".$idcliente."' AND id_usuario = '".$usuario."' ";
  $querydestcheck = mysql_query($sqldestcheck,$link);

  while ($rowdestcheck = mysql_fetch_array($querydestcheck)) {
    if($rowdestcheck['iddestino'] == 0){
      echo "<script> alert ('Existen Items sin un destino asignado');</script>";
      exit();
    }
  }

  //Recorriendo toda la tabla para efectuar inserciones

  while($rowins = mysql_fetch_array($queryins)){

    $cantidad = $rowins['cantidad'];
    $item = $rowins['id_item'];
    $precio = $rowins['preciounitario'];
    $mensaje = $rowins['mensaje'];

    $iddestino = $rowins['iddestino'];

    $sqldest = "SELECT * FROM tblcarro_venta INNER JOIN tblshipto_venta ON tblcarro_venta.iddestino = tblshipto_venta.iddestino WHERE tblcarro_venta.iddestino = '".$iddestino."'";
    $querydest = mysql_query($sqldest,$link);
    $rowdest = mysql_fetch_array($querydest);

   
    $shipto   = $rowdest['shipto1'];
    $shipto2  = $rowdest['shipto2'];
    $direccion   = $rowdest['direccion'];
    $direccion2  = $rowdest['direccion2'];
    $ciudad      = $rowdest['cpcuidad_shipto'];
    $estado      = $rowdest['cpestado_shipto'];
    $zip         = $rowdest['cpzip_shipto'];
    $telefono    = $rowdest['cptelefono_shipto'];
    $mail        = $rowdest['mail'];

    //echo $shipto,$shipto2,$direccion,$direccion2,$ciudad,$estado,$zip,$telefono,$mail;
    //echo "<br>";

  /*    --------    */

  //$custnumber  = $_POST['custnumber'];   YA

  //datos de la tabla tblsoldto
  //$soldto      = $_POST['soldto'];  YA
  //$soldto2     = $_POST['soldto2'];   YA
  //$stphone     = $_POST['billphone'];  YA
  //$adddress    = $_POST['billaddress'];  YA
  //$adddress2   = $_POST['billaddress2'];  YA
  //$city        = $_POST['billcity'];  YA
  //$state       = $_POST['billstate'];  YA
  //$billzip     = $_POST['billzip'];  YA
  //$country     = $_POST['billcountry'];  YA

  //Fin comun para todos

  //datos de la tabla tblorden 
  //$mensaje     = $_POST['mensaje']; YA

  //datos de la tabla tblshipto
  //$shipto      = $_POST['shipto'];  YA
  //$shipto2     = $_POST['shipto2']; YA
  //$direccion   = $_POST['direccion']; YA
  //$direccion2  = $_POST['direccion2']; YA
  //$ciudad      = $_POST['ciudad']; YA
  //$estado      = $_POST['estado']; YA
  //$zip         = $_POST['zip']; YA
  //$telefono    = $_POST['telefono']; YA
  //$mail        = $_POST['mail']; YA

  //datos de la tabla tblsoldto
 /* $soldto      = $_POST['soldto'];
  $soldto2     = $_POST['soldto2']; 
  $stphone     = $_POST['billphone'];
  $adddress    = $_POST['billaddress'];
  $adddress2   = $_POST['billaddress2'];
  $city        = $_POST['billcity'];
  $state       = $_POST['billstate'];
  $billzip     = $_POST['billzip'];
  $country     = $_POST['billcountry'];  */

  //datos de la tabla tbldetalleorden
  //$ponumber    = $_POST['ponumber'];    YA
  //$custnumber  = $_POST['custnumber'];  YA
   	 
  //Calcular el shipdt
  $shipdt      = $_POST['shipdt'];


  //$item        = $_POST['item'];  YA
	 
  //Obteniendo el origen para obtener el pais de origen (codigo_ciudad-pais)
  $sqlorg4   = "SELECT origen FROM tblproductos WHERE tblproductos.id_item = '$item'";
  $query4 = mysql_query($sqlorg4,$link);
  $row4   = mysql_fetch_array($query4);
  $cporigen = $row4["origen"];
  $cporigen_city = explode("-", $cporigen);
  $cporigen = $cporigen_city[0];

  //Obteniendo el codigo del pais
  $sqlorg5   = "SELECT codpais_origen FROM tblciudad_origen WHERE tblciudad_origen.codciudad = '$cporigen'";
  $query5 = mysql_query($sqlorg5,$link);
  $row5   = mysql_fetch_array($query5);
  $origin = $row5["codpais_origen"];

  //Obtener dia de la semana para saber cuanto restar al deliver para asignarle al shipdt
  $fecha = date('l', strtotime($deliver));
  //verifico que dia es para restarle los dias que son 
  /*
    Si el envio es de ECUADOR
  */
  //if($_POST['origen'] == "EC"){ 
  if($origin == "EC"){ 
  		// Si es Martes, Jueves o Viernes le resto 3 dias
  		if(strcmp($fecha,"Tuesday")==0 || strcmp($fecha,"Thursday")==0 || strcmp($fecha,"Friday")==0) {
  			$shipdt = strtotime ( '-3 day' , strtotime ( $deliver ) ) ;
  			$shipdt = date ( 'Y-m-j' , $shipdt );
  		}else{
  			//Si es otro dia de envio o sea Miercoles
  			$shipdt = strtotime ( '-4 day' , strtotime ( $deliver ) ) ;
  			$shipdt = date ( 'Y-m-j' , $shipdt );
  		}					
  }
  else{
        $shipdt = strtotime ( '-5 day' , strtotime ( $deliver ) ) ;
        $shipdt = date ( 'Y-m-j' , $shipdt );  //TBLDETALLE_ORDEN     
  }	//Fin del if 
	 
  //$cantidad    = $_POST['cantidad']; YA

  $farm        = $_POST['farm'];

  //El pais de envio hay que sacarlo tambien del shipto_venta
  //$ctry        = $_POST['ctry']; YA

  $ctry = $rowdest['shipcountry'];

  //$origin      = $_POST['origen'];
  
  //$precio      = $_POST['precio']; YA
  

  /* VENDOR */

  //$cliente     = $idcliente."-".$ctry;
  $cliente     = $idcliente;
  
  //$cliente     = $_POST['cliente']."-".$ctry;  YA
	 	 
 
  //verificando que los campos obligatorios esten marcados

  //if($ponumber == '' && $shipto == '' && $direccion =='' && $soldto =='' && $country == '' && $adddress == ''){
   /*echo("<script> alert ('Por favor introduzca los datos en los campos obligatorios.');</script>");*/
  // echo "<script> window.location.href='crearorden.php?error=2'</script>";		 
  //}
  // else{
   
  $enviaramsg = $rowdest['shipto1'];

  $clientmsg = $row9['empresa'];

  //Verifico si el mensaje esta en blanco, si es asi le pongo un valor por defecto
  if($mensaje == ''){
    $mensaje = "To-Blank Info   ::From- Blank Info   ::Blank .Info"; 
  }
  else{
    $mensaje = "To-".$enviaramsg."::From-".$clientmsg."::".$mensaje;
  }

  if($cantidad == 1){		
    //***************** Insertando en las diferentes tablas para registrar la orden ****************************************//
    //Insertando los datos de la tabla orden
    $sql="INSERT INTO tblorden (`nombre_compania`,`cpmensaje`,`order_date`) VALUES ('eblooms','$mensaje','$orddate')"; 
    $creado_orden= mysql_query($sql,$link);
    $id_order = mysql_insert_id();

    //Insertar los datos de Shipto
    $sql1 = "Insert INTO `tblshipto`(`id_shipto`,`shipto1`,`shipto2`,`direccion`,`cpestado_shipto`,`cpcuidad_shipto`,`cptelefono_shipto`,`cpzip_shipto`,`mail`,`direccion2`,`shipcountry`)VALUES ('$id_order','$shipto','$shipto2','$direccion','$estado','$ciudad','$telefono','$zip','$mail','$direccion2','$ctry')";
    $creado_ship = mysql_query($sql1,$link);

    //Insertar los datos de Soldto
    $sql2 = "Insert INTO `tblsoldto`(`id_soldto`,`soldto1`,`soldto2`,`cpstphone_soldto`,`address1`,`address2`,`city`,`state`,`postalcode`,`billcountry`,`billmail`)VALUES ('$id_order','$soldto','$soldto2','$stphone','$adddress','$adddress2','$city','$state','$billzip','$country','$billmail')";
    $creado_sold = mysql_query($sql2,$link);

    //Insertar los datos de tbldirector
    $sql5 = "Insert INTO `tbldirector`(`id_director`)VALUES ('$id_order')";
    $creado_director= mysql_query($sql5,$link);
    	
    //Inserto los detalles del primer producto de la orden
    $sql3 = "Insert INTO `tbldetalle_orden`(`id_detalleorden`,`cpcantidad`,`Ponumber`,`Custnumber`,`cpitem`,`satdel`,`farm`,`cppais_envio`,`cpmoneda`,`cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`poline`,`unitprice`,`ups`,`tracking`,`vendor`)VALUES ('$id_order','1','$ponumber','$custnumber','$item','$satdel','$farm','$ctry','USD','$origin','BOX','$deliver','$shipdt','Active','not donwloaded','','0','No','New','0','$precio','','','$cliente')";																
    $creado_detalle = mysql_query($sql3,$link); 
  }
  else{
    for($i=1;$i<=$cantidad;$i++){
			if($i == 1){
        //Insertando los datos de la tabla orden
        $sql="INSERT INTO tblorden (`nombre_compania`,`cpmensaje`,`order_date`) VALUES ('eblooms','$mensaje','$orddate')"; 
        $creado_orden= mysql_query($sql,$link);
        $id_order = mysql_insert_id();

        //Insertar los datos de Shipto
        $sql1 = "Insert INTO `tblshipto`(`id_shipto`,`shipto1`,`shipto2`,`direccion`,`cpestado_shipto`,`cpcuidad_shipto`,`cptelefono_shipto`,`cpzip_shipto`,`mail`,`direccion2`,`shipcountry`)VALUES ('$id_order','$shipto','$shipto2','$direccion','$estado','$ciudad','$telefono','$zip','$mail','$direccion2','$ctry')";
        $creado_ship = mysql_query($sql1,$link);

        //Insertar los datos de Soldto
        $sql2 = "Insert INTO `tblsoldto`(`id_soldto`,`soldto1`,`soldto2`,`cpstphone_soldto`,`address1`,`address2`,`city`,`state`,`postalcode`,`billcountry`,`billmail`)VALUES ('$id_order','$soldto','$soldto2','$stphone','$adddress','$adddress2','$city','$state','$billzip','$country','$billmail')";
        $creado_sold = mysql_query($sql2,$link);

        //Insertar los datos de tbldirector
        $sql5 = "Insert INTO `tbldirector`(`id_director`)VALUES ('$id_order')";
        $creado_director= mysql_query($sql5,$link);
        	
        //Inserto los detalles del primer producto de la orden
        $sql3 = "Insert INTO `tbldetalle_orden`(`id_detalleorden`,`cpcantidad`,`Ponumber`,`Custnumber`,`cpitem`,`satdel`,`farm`,`cppais_envio`,`cpmoneda`,`cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`poline`,`unitprice`,`ups`,`tracking`,`vendor`)VALUES ('$id_order','1','$ponumber','$custnumber','$item','$satdel','$farm','$ctry','USD','$origin','BOX','$deliver','$shipdt','Active','not donwloaded','','0','No','New','0','$precio','','','$cliente')";																
        $creado_detalle = mysql_query($sql3,$link);
			}
      else{
				//Inserto los detalles del primer producto de la orden
							$sql3 = "Insert INTO `tbldetalle_orden`(`id_detalleorden`,`cpcantidad`,`Ponumber`,`Custnumber`,`cpitem`,`satdel`,`farm`,`cppais_envio`,`cpmoneda`,`cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`poline`,`unitprice`,`ups`,`tracking`,`vendor`)VALUES ('$id_order','1','$ponumber','$custnumber','$item','$satdel','$farm','$ctry','USD','$origin','BOX','$deliver','$shipdt','Active','not donwloaded','','0','No','New','0','$precio','','','$cliente')";																
							$creado_detalle = mysql_query($sql3,$link);
			}
  	}		
  }

    //Insertar en la tabla de transacciones
    if($ponumberManual==''){
      $sqltrans = "INSERT INTO `tbltransaccion`(`Ponumber`,`codcliente`,`cantidad`,`iddestino`,`id_item`) VALUES ('$ponumber','$idcliente','$cantidad','$iddestino',$item)";
      $querytrans = mysql_query($sqltrans);
    }

 }//FIN DEL WHILE

  if($creado_orden && $creado_ship && $creado_sold && $creado_detalle && $creado_director){

    //Vaciar carro de compra
    $sqlvaciar = "DELETE FROM `tblcarro_venta` WHERE codcliente = '".$idcliente."' AND id_usuario = '".$usuario."'  ";
    $queryvaciar = mysql_query($sqlvaciar,$link);

     //echo $sqlvaciar;
     //exit();

    //Eliminando la informacion del cliente
    $_SESSION['idcliente'] = null;
    $_SESSION['ponumberManual'] = null;

    echo "<script> window.location.href='crearorden.php?error=1'</script>";

  }
  else{
    echo "<script> window.location.href='crearorden.php?error=2'</script>";
  }

} //Fin del isset del boton REGISTRAR

  if(isset($_POST["Cancelar"])){  

    //Vaciar carro de compra
    $sqlvaciar = "DELETE FROM `tblcarro_venta` WHERE codcliente = '".$idcliente."' AND id_usuario = '".$usuario."'  ";
    $queryvaciar = mysql_query($sqlvaciar,$link);

    //Destruir la variable de sesion de cliente de crearorden (Punto de venta)
    $_SESSION['idcliente'] = null;

    //Destruir la variable de sesion de ponumber manual de crearorden (Punto de venta)
    $_SESSION['ponumberManual'] = null;

    echo "<script>window.location.href='../main.php?panel=mainpanel.php';</script>";
  }

?>
</div> <!-- /container -->
</body>
</html>