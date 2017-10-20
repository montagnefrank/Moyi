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
$id       =  $_GET ['id'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Ventas</title>
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
  	
  	  if(fecha1 == '' && fecha2 == '' )
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
  </script>
  <style>
    .modificar 
    {background-image: url(../images/edit.jpg);}
    .eliminar 
    {background-image: url(../images/delete.jpg);}
  </style>
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
           <a class="navbar-brand" href="administration.php" data-toggle="tooltip" data-placement="bottom" title="Ir al inicio"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
         
      </div>



  <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
 <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
        <li class="dropdown">
          <a tabindex="0" data-toggle="dropdown">
            <strong>Pagos</strong><span class="caret"></span>
          </a>
          <ul class="dropdown-menu" role="menu">
              <li><a href="subircostco.php"><strong>Subir Archivo de Costo</strong></a></li>
              <li class="divider"></li>
              <li><a href="pagosycreditos.php"><strong>Pagos y Créditos Manuales</strong></a></li>
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
            $query = mysqli_query($link,$sql);
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
                    <td  colspan="5" align="center"><strong>REPORTE DE VENTAS</strong></td>
              </tr>
  <tr>
  <tr style="display:none">
                    <td  colspan="5" align="center"><input name="id" type="text" value ="<?php echo $id; ?>"/></td>
              </tr>
  <tr>
  <td width="397" align="right"> 
              <strong>Fecha Inicio:</strong>
               <input name="txtinicio" type="text" id="txtinicio" readonly="readonly" size="20"/>
                    </td>
                    <td width="271">
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
               <td width="342">
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
                <input type="submit" name="buscar" id="buscar" value="Buscar" onclick="return Compara(this.form)"/>
                </td>
              </tr>
              </table>
              </div>    <!--table responsive -->
          </form>
    </td>
  </tr>
  <tr>
    <td id="inicio" bgcolor="" height="100"> 
    <div class="table-responsive">
    <table width="50%" border="0" align="center" class="table table-responsive">
  <tr>
		<?php 
   if(!isset ($_POST['buscar'])){
	   		
			 if ($_GET['id']==1){
				echo '<td colspan="4" align="center"><h3><strong>Total vendidos</strong><font color="#FF0000">';
				//echo '<time datetime="'.date('c').'">'.date('d - m - Y').'</time>';
				echo '</font> organizados por item</h3>';
			 }else{
				 if ($_GET['id']==2){	
						echo '<td colspan="4" align="center"><h3><strong>Total no vendidos </strong><font color="#FF0000">';				
						//echo '<time datetime="'.date('c').'">'.date('d - m - Y').'</time>';
						echo '</font> organizados por items</h3>';				 
				 }else{
					 echo '<td colspan="7" align="center"><h3><strong>Neto vendido </strong><font color="#FF0000">';
					// echo '<time datetime="'.date('c').'">'.date('d - m - Y').'</time>';
					 echo '</font> organizados por items</h3>';
				 }
			 }
			 
			    echo '
				    </td>
                <td align="right">
                    <input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/print.ico" heigth="40" value="" title = "Imprimir Listado" width="30" onclick = ""/>
                 </td>
              </tr>
              <tr bgcolor="#E8F1FD">
                   <td align="center"><strong>Cantidad</strong></td></strong>
                   <td align="center"><strong>Costo unitario</strong></td></strong>
                   <td align="center"><strong>Costo Total</strong></td></strong>
               </tr>';
			 
   }else{
	   //Si se oprimio l boton de buscar
			  $id = $_POST['id'];
			  $fecha1 = $_POST['txtinicio'];
			  $fecha2 = $_POST['txtfin'];
			  
			  if ($id ==1){
				echo '<td colspan="4" align="center"><h3><strong>Total vendidos desde </strong><font color="#FF0000">';
				//echo '<time datetime="'.date('c').'">'.date('d - m - Y').'</time>';
				echo $fecha1."<font color='#000000'> hasta </font>".$fecha2;
				echo '</font> organizados por item.</h3></td>
				<td align="right" colspan="2">
                    <input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/print.ico" heigth="40" value="" title = "Imprimir Listado" width="30" onclick = ""/>
                 </td>';
			 }else{
				 if ($id ==2){	
						echo '<td colspan="4" align="center"><h3><strong>Total no vendidos desde </strong><font color="#FF0000">';				
						//echo '<time datetime="'.date('c').'">'.date('d - m - Y').'</time>';
						echo $fecha1."<font color='#000000'> hasta </font>".$fecha2;
						echo '</font> organizados por items.</h3></td>
						<td align="right" colspan="2">
                    <input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/print.ico" heigth="40" value="" title = "Imprimir Listado" width="30" onclick = ""/>
                 </td>';				 
				 }else{
					 echo '<td colspan="7" align="center"><h3><strong>Neto vendido desde </strong><font color="#FF0000">';
					// echo '<time datetime="'.date('c').'">'.date('d - m - Y').'</time>';
					echo $fecha1."<font color='#000000'> hasta </font>".$fecha2;
					 echo '</font> organizados por items.</h3></td>
					 <td align="right" colspan="2">
                    <input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/print.ico" heigth="40" value="" title = "Imprimir Listado" width="30" onclick = ""/>
                 </td>';
				 }
			 }
			 
              if ($id == 1){ //los items vendidos
                  $totalitem;
                  $total;
                  //Obtengo todos los items que hay en la base 
                  $sql = "SELECT distinct cpitem FROM tbldetalle_orden where estado_orden = 'Active' AND tracking !='' AND status = 'Shipped' AND delivery_traking BETWEEN '".$fecha1."' AND '".$fecha2."' order by cpitem";
                  $query = mysqli_query($link,$sql);
                  if(!$query){
                    echo '<td><strong>Error en la consulta de items...</strong></td>';
                  }else{
                      $cant = mysqli_num_rows($query);
                      if($cant==0){
                          echo '<td><strong>No hay items a mostrar</strong></td>';		  
                    }else{
                        while($row = mysqli_fetch_array($query)){
                            //Si hay mas de un item entonces se construye el reporte por item\
                              echo '<tr>
                                <td><strong>Producto: <font color="#0000FF">'.$row["cpitem"].'</font></strong></td>
                              </tr>';
                              
                              //Consulto la bd con el item y poder sumar los  
                               $sql1 = "SELECT SUM(unitprice) as preciototal, SUM(cpcantidad) as cantidad, unitprice FROM tbldetalle_orden where estado_orden = 'Active' AND tracking !='' AND status = 'Shipped' AND cpitem =  '".$row["cpitem"]."' AND delivery_traking BETWEEN '".$fecha1."' AND '".$fecha2."'";
                               $query1 = mysqli_query($link,$sql1);
                               if(!$query1){
                                    echo '<td><strong>Error consultando los precios del item'.$row["cpitem"].'</strong></td>';
                              }else{
                                  //Si hay entreadas para ese item en el cuarto frio los imprimo
                                  echo '<tr bgcolor="#E8F1FD">
                                            <td align="center"><strong>Cantidad</strong></td></strong>
                                            <td align="center"><strong>Costo unitario</strong></td></strong>
                                            <td align="center"><strong>Costo Total</strong></td></strong>
                                       </tr>';
                                  
                                      echo "<tr>";
                                         $row1 = mysqli_fetch_array($query1);
                                         echo "<td align='center'>".$row1['cantidad']."</td>";
                                         echo "<td align='center'>".$row1['unitprice']."</td>";	
                                         $preciotot = number_format($row1['preciototal'], 2,',', '.');						
                                         echo "<td align='center'>".$preciotot."</td>";					 
                                         $totalitem += $row1['preciototal'];	
                                    echo "</tr>";
                                                      
                                    echo "<tr>";
                                        $total += $totalitem;
                                        echo "<td align='right'><strong></strong></td>";
                                        echo "<td align='right'><strong>Total por Producto:</strong></td>";
                                        $totalitem = number_format($totalitem, 2, ',', '.');
                                        echo "<td align='center'><strong>".$totalitem."</strong></td>";
                                    echo "</tr>";
                                    
                                    $totalitem = 0;			  
                              }//fin else			  
                           }//fin while
                        }//fin else 
                                   echo "<tr></tr>";
                                   echo "<tr>";
                                    echo "<td align='right'><strong></strong></td>";
                                        echo "<td align='right'><strong>Total General:</strong></td>";
                                        $total = number_format($total, 2, ',', '.');
                                        echo "<td align='center'><strong>".$total."</strong></td>";
                                    echo "</tr>"; 
                  } //fin else 
                     mysqli_close($conection);  
             }else{
                  if ($id ==2){
					  $totalitem;
					  $total;
					  //Obtengo todos los items que hay en la base 
					  $sql = "SELECT distinct cpitem FROM tbldetalle_orden where estado_orden = 'Canceled' AND status = 'Not Shipped' AND delivery_traking BETWEEN '".$fecha1."' AND '".$fecha2."' order by cpitem";
					  $query = mysqli_query($link,$sql);
					  if(!$query){
						echo '<td><strong>Error en la consulta de items...</strong></td>';
					  }else{
						  $cant = mysqli_num_rows($query);
						  if($cant==0){
							  echo '<td><strong>No hay item s a mostrar</strong></td>';		  
						}else{
							while($row = mysqli_fetch_array($query)){
								//Si hay mas de un item entonces se construye el reporte por item\
								  echo '<tr>
									<td><strong>Producto: <font color="#0000FF">'.$row["cpitem"].'</font></strong></td>
								  </tr>';
								  
								  //Consulto la bd con el item y poder sumar los  
								   $sql1 = "SELECT SUM(unitprice) as preciototal, SUM(cpcantidad) as cantidad, unitprice FROM tbldetalle_orden where estado_orden = 'Canceled' AND status = 'Not Shipped' AND cpitem =  '".$row["cpitem"]."' AND delivery_traking BETWEEN '".$fecha1."' AND '".$fecha2."'";
								   $query1 = mysqli_query($link,$sql1);
								   if(!$query1){
										echo '<td><strong>Error consultando los precios del item'.$row["cpitem"].'</strong></td>';
								  }else{
									  //Si hay entreadas para ese item en el cuarto frio los imprimo
									  echo '<tr bgcolor="#E8F1FD">
												<td align="center"><strong>Cantidad</strong></td></strong>
												<td align="center"><strong>Costo unitario</strong></td></strong>
												<td align="center"><strong>Costo Total</strong></td></strong>
										   </tr>';
									  
										  echo "<tr>";
											 $row1 = mysqli_fetch_array($query1);
											 echo "<td align='center'>".$row1['cantidad']."</td>";
											 echo "<td align='center'>".-$row1['unitprice']."</td>";	
											 $preciotot = number_format($row1['preciototal'],2);						
											 echo "<td align='center'>".-$preciotot."</td>";					 
											 $totalitem += $row1['preciototal'];	
										echo "</tr>";
														  
										echo "<tr>";
											$total += -$totalitem;
											echo "<td align='right'><strong></strong></td>";
											echo "<td align='right'><strong>Total por Producto:</strong></td>";
											$totalitem = number_format($totalitem, 2);
											echo "<td align='center'><strong>".-$totalitem."</strong></td>";
										echo "</tr>";
										
										$totalitem = 0;			  
								  }//fin else			  
							   }//fin while
							}//fin else 
									   echo "<tr></tr>";
									   echo "<tr>";
										echo "<td align='right'><strong></strong></td>";
											echo "<td align='right'><strong>Total General:</strong></td>";
											$total = number_format($total, 2, ',', '.');
											echo "<td align='center'><strong>".$total."</strong></td>";
										echo "</tr>"; 
					  } //fin else 
						 mysqli_close($conection);
                 }else{
					  $totalitem;
					  $total;
					  //Obtengo todos los items que hay en la base 
					  $sql = "SELECT distinct cpitem FROM tbldetalle_orden where delivery_traking BETWEEN '".$fecha1."' AND '".$fecha2."' order by cpitem";
					  $query = mysqli_query($link,$sql);
					  if(!$query){
						echo '<td><strong>Error en la consulta de items...</strong></td>';
					  }else{
						  $cant = mysqli_num_rows($query);
						  if($cant==0){
							  echo '<td><strong>No hay item s a mostrar</strong></td>';		  
						}else{
							while($row = mysqli_fetch_array($query)){
								//Si hay mas de un item entonces se construye el reporte por item\
								  echo '<tr>
									<td><strong>Producto: <font color="#0000FF">'.$row["cpitem"].'</font></strong></td>
								  </tr>';
								  
								  //Consulto la bd con el item y poder sumar los  
								   $sql1 = "SELECT SUM(unitprice) as preciototal, SUM(cpcantidad) as cantidad, unitprice FROM tbldetalle_orden where cpitem =  '".$row["cpitem"]."' AND delivery_traking BETWEEN '".$fecha1."' AND '".$fecha2."'";
								   $query1 = mysqli_query($link,$sql1);
								   if(!$query1){
										echo '<td><strong>Error consultando los precios del item'.$row["cpitem"].'</strong></td>';
								  }else{
									  //Si hay entreadas para ese item en el cuarto frio los imprimo
									  echo '<tr bgcolor="#E8F1FD">
												<td align="center"><strong>Cantidad</strong></td></strong>
												<td align="center"><strong>Costo unitario</strong></td></strong>
												<td align="center"><strong>Costo Total</strong></td></strong>
										   </tr>';
									  
										  echo "<tr>";
											 $row1 = mysqli_fetch_array($query1);
											 echo "<td align='center'>".$row1['cantidad']."</td>";
											 echo "<td align='center'>".$row1['unitprice']."</td>";	
											 $preciotot = number_format($row1['preciototal'], 2);						
											 echo "<td align='center'>".$preciotot."</td>";					 
											 $totalitem += $row1['preciototal'];	
										echo "</tr>";
														  
										echo "<tr>";
											$total += $totalitem;
											echo "<td align='right'><strong></strong></td>";
											echo "<td align='right'><strong>Total por Producto:</strong></td>";
											$totalitem = number_format($totalitem, 2, ',', '.');
											echo "<td align='center'><strong>".$totalitem."</strong></td>";
										echo "</tr>";
										
										$totalitem = 0;			  
								  }//fin else			  
							   }//fin while
							}//fin else 
									   echo "<tr></tr>";
									   echo "<tr>";
										echo "<td align='right'><strong></strong></td>";
											echo "<td align='right'><strong>Total General:</strong></td>";
											$total = number_format($total, 2, ',', '.');
											echo "<td align='center'><strong>".$total."</strong></td>";
										echo "</tr>";  
					  } //fin else 
                     mysqli_close($conection);
            }
       }
  }
           ?>
  </table>
  </div>    <!-- table responsive    -->
  </td>
  </table>
  </div>    <!-- table responsive    -->


</div> <!-- panel panel-primary -->
<div class="panel-heading">
  <div class="contenedor" align="center">
    <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2015 versión 3</strong>
    <br>
    <strong><a href="http://www.bit-store.ec/index.php/contactenos/"  style="color:white">Info</a> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></strong>
  </div>
</div>
</div> <!-- panel heading -->

</div>

</body>
</html>

