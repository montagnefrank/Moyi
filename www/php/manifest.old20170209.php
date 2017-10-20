<?php
//ini_set('display_errors', 'On');
//ini_set('display_errors', 1);
ini_set('memory_limit', '-1');


/**** Este archivo es el que genera el costo de los pagos *****/
/**************************************************************/
session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("consecutivo.php");
include ("seguridad.php");

$user     =  $_SESSION["login"];

//hacer conexion
$conection = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error()); 
//$id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Confirm manifest</title>
  <script type="text/javascript" src="../js/script.js"></script>
  <link rel="icon" type="image/png" href="../images/favicon.ico" />
  <link rel="stylesheet" type="text/css" media="all" href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
  <script type="text/javascript" src="../js/jquery-1.2.1.pack.js"></script>
  <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
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


  <script type="text/javascript">
    function modificar(valor){
    	var v=valor;
    	window.open("modificarorden.php?codigo="+v,"Cantidad","width=900,height=880,top=50,left=300");
    	return false;
    }
    function cancelar(valor){
    	//alert ('Hola');
    	var v=valor;
    	window.open("eliminar.php?codigo="+v,"Cantidad","width=300,height=150,top=350,left=400");
    	return false;
    }

    function eliminar(valor){
    	//alert ('Hola');
    	var v=valor;
    	window.open("eliminarorden.php?codigo="+v,"Cantidad","width=300,height=150,top=350,left=400");
    	return false;
    }
  </script>
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
  <script>
    function validar_texto(e){
        tecla = (document.all) ? e.keyCode : e.which;

        //Tecla de retroceso para borrar, siempre la permite
        if (tecla==8){
    		//alert('No puede ingresar letras');
            return true;
        }
            
        // Patron de entrada, en este caso solo acepta numeros
        patron =/[0-9]/;
        
        tecla_final = String.fromCharCode(tecla);
        return patron.test(tecla_final);
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
            $query = mysql_query($sql,$conection);
            $row = mysql_fetch_array($query);
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

<form id="form" name="form" method="post">
<div class="table-responsive">
<table width="50%" border="0" align="center" class="table table-responsive">
  </form>
  <tr>
  	<td>
    	<form method="post" id="frm1" name="frm1" target="_parent" >
           <div class="table-responsive">
            <table width="50%" border="0" align="center" class="table table-responsive">
        
              <tr>
                    <td  colspan="5" align="center"> <strong>GENERAR EL CONFIRM MANIFEST</strong></td>
              </tr>
              <tr>
                   <td width="120px">
                      <h5 style="margin-top: 6px;"><strong>Fecha de Envio</strong></h5>
                  </td>
                    <td width="200"> 
              <strong>Desde:</strong>
               <input name="txtinicio" type="text" id="txtinicio" readonly="readonly" size="20"/>
                <script type="text/javascript">
                  function catcalc(cal) {
                    var date = cal.date;
                    var time = date.getTime()
                    // use the _other_ field
                    var field = document.getElementById("f_calcdate");
                    if (field == cal.params.inputField) {
                        field = document.getElementById("txtinicio");
                        time -= Date.WEEK; // substract one week
                    } 
                    else 
                    {
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
                  </td>
                  <td width="200">
                <strong>Hasta:</strong>
                <input name="txtfin" type="text" id="txtfin" readonly="readonly" size="20"/>
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
                  </td>
                  <td width="298">
                <input type="submit" name="buscar" id="buscar" value="Buscar" onclick="return Compara(this.form)" class="btn-primary"/>
                </td>
                </td>
              </tr>
              </table>
              </div>  <!-- table responsive -->
          </form>
    </td>
  </tr>
<tr>
    <td id="inicio" bgcolor="" height="100"> 
   <div class="table-responsive">
    <table width="50%" border="0" align="center" class="table table-responsive"> 
     <tr>
       <td colspan="7" align="center">
    	   <h3><strong>Lista de Órdenes</strong></h3>
       </td>
    <form action="crearcsv.php" method="post" target="_blank">
      <td width="71" align="right">
          <input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/excel.png" heigth="40" value="" title = "Exportar Listado" width="30" onclick = ""/>
      </td>
    </form>
    <td>
      <form action="crearxml.php" method="post" target="_blank">
      <td width="71" align="right">
          <input type="image" style="cursor:pointer" name="btn_xml" id="btn_xml" src="../images/xml.png" heigth="40" value="" title = "Exportar listado a XML" width="30" onclick = ""/>
      </td>
      </form>
    </td>
  </tr>
 
  <tr bgcolor="#E8F1FD">
     <td width="132" align="center"><strong>Cliente</strong></td>
       <td width="138" align="center"><strong>Ponumber</strong></td>
  	<td width="89" align="center"><strong>Po line #</strong></td>
    <td width="98" align="center"><strong>Cantidad</strong></td>
    <td width="130" align="center"><strong>Código Carrier</strong></td>
    <td width="216" align="center"><strong>Tracking</strong></td>
    <td width="116" align="center"><strong>Fecha Vuelo</strong></td>
    <td width="71" align="center"><strong>eBinv</strong></td>
  </tr>
  <form id="form" name="form" method="post">


  <?php
  if(isset($_POST['buscar'])){
    $fecha1 = $_POST['txtinicio'];
		$fecha2 = $_POST['txtfin'];

		//verifico que las fechas tengan valor  
		if($fecha1 != '' && $fecha2 != ''){
		  $sql =   "SELECT DISTINCT Ponumber, eBing, tracking FROM tbldetalle_orden WHERE ShipDT_traking BETWEEN '".$fecha1."' AND '".$fecha2."' AND tracking!='' AND status='Shipped' AND estado_orden='Active';";
		  
		 // $_SESSION['sql1'] = $sql;
	      $val = mysql_query($sql,$conection);
		  if(!$val){
		  	echo "<tr><td>".mysql_error()."</td></tr>";
		   }else{
			   //recorro cada Ponumber y si no tiene eBinv lo genero
			   while($row = mysql_fetch_array($val)){
				   if($row ['eBing'] == 0){
					   //verifico si ya ese ponumber tine ebing asignado
					   $SQL = "select consecutivo,partnerTrxID from tblconsecutivo where Ponbr='".$row['Ponumber']."'";
					   $QUERY = mysql_query($SQL,$conection) or die (mysql_error());
					   $FILA= mysql_fetch_array($QUERY);
					   $CONT = mysql_num_rows($QUERY);
					   
					   if($CONT > 0){
						  $eBing = $FILA['consecutivo'];
              $partnerTrxID = $FILA['partnerTrxID'];
              //$packageDetailId= $partnerTrxID.'-PDID';
              //echo "AQUI ESTOY asignando el partnerTrxID";
					   }else{
					   
						   //Generar eBinv
						   $eBing = generarConsecutivo();
               $partnerTrxID = generarPartnerTrxID();
               //$packageDetailId= $partnerTrxID.'-PDID';
						   //Insertar los datos del consecutivo 
						   $sql1 = "Insert INTO tblconsecutivo(consecutivo,Ponbr,partnerTrxID)VALUES(".$eBing.",'".$row ['Ponumber']."',".$partnerTrxID.")";
						   mysql_query($sql1,$conection)or die ("Error creando eBinv");
						   $sql2 = "SELECT consecutivo,partnerTrxID FROM tblconsecutivo ORDER BY consecutivo desc LIMIT 1";
						   $query2 = mysql_query($sql2,$conection)or die ("Error seleccionando eBinv");
						   $row2= mysql_fetch_row($query2);
						   $eBing= $row2[0];
               $partnerTrxID = $row2[1];
               //$packageDetailId= $partnerTrxID.'-PDID';
               //echo "AQUIIIII".$partnerTrxID;
						}   
						   //echo "aqui updateando";
               //$sentencia = "UPDATE tbldetalle_orden set eBing =".$eBing.", partnerTrxID=".$partnerTrxID.", packageDetailId='".$packageDetailId."' WHERE Ponumber='".$row ['Ponumber']."'";
               $sentencia = "UPDATE tbldetalle_orden set eBing =".$eBing.", partnerTrxID=".$partnerTrxID." WHERE Ponumber='".$row ['Ponumber']."'";
               //echo $sentencia; 
               $consulta  = mysql_query($sentencia,$conection) or die ("Error generando el eBinv del Ponumber ".$row ['Ponumber']);	

					   //Insertando o modificando la tabla de costos
					   //Ahora se verifica si el Ponumber existe ya en la tabla de costo
						$a = "SELECT * FROM tblcosto WHERE po = '".$row ['Ponumber']."'";
						$b = mysql_query($a,$conection) or die ("Error consultando el POnumber: ".$row ['Ponumber']." en la tabla de costos");
						$c = mysql_num_rows($b);
						
						//Obtengo la suma total de los costos asosciados al ponumber en cuestion
						$d = "SELECT SUM(unitprice),delivery_traking FROM tbldetalle_orden WHERE Ponumber = '".$row ['Ponumber']."' AND estado_orden='Active' AND tracking!='' AND status='Shipped'";
						$e = mysql_query($d,$conection) or die ("Error sumando los costos del POnumber: ".$row ['Ponumber']);
							
						//Obtengo el costo total del ponumber
						$f = mysql_fetch_array($e);
						$costo = $f[0];
						$fecha_facturacion = $f[1];
						
						//Verifico si no hay fila con ese ponumber
						if($c == 0){						
							//Verifico si el costo es diferente de vacio
							if($costo >= 0){
								// Si no tiene fila creo una fila con el costo total de ese ponumber			
								//Se crea una nueva entrada en la tabla de costos
								$x = "INSERT INTO tblcosto (`po`,`ebinv`,`costo`,`credito`,`pagado`, `fecha_facturacion`) VALUES ('".$row ['Ponumber']."','".$eBing."','".$costo."','0','No','$fecha_facturacion')";
								$y = mysql_query($x, $conection) or die ("Error insertando el costo total del Ponumber: ".$row ['Ponumber']);	
							}else{
								  //elimino el costo de esa orden en la tabla de costos
								  $sql1="DELETE FROM tblcosto WHERE po='".$row ['Ponumber']."'";
								  $eliminado1= mysql_query($sql1,$conection) or die ("Erro eliminando un costo negativo");
								
								}
						}else{ //Si ya existe una fila con ese ponumber verifico que el costo se mantenga sin modificacion
								//verifico si el costo ha variado
								if($c['costo'] <> $costo){
									//Se modifca una nueva entrada en la tabla de costos
									$xx = "UPDATE tblcosto SET costo='".$costo."' WHERE po='".$row ['Ponumber']."'";
									$yy = mysql_query($xx, $conection) or die ("Error modificando el costo total del Ponumber: ".$row ['Ponumber']);
								}
							
							}		
					   			    
				   }
				   
				  }
          // Generando el packageDetailId
          $sqlm = "SELECT tracking FROM tbldetalle_orden WHERE ShipDT_traking BETWEEN '".$fecha1."' AND '".$fecha2."' AND tracking!='' AND status='Shipped' AND estado_orden='Active';";
          
          $valm = mysql_query($sqlm,$conection);

          while($myrow = mysql_fetch_array($valm)){
              
            $packageDetailId = 'PDID-'.$myrow ['tracking'];
            $sent = "UPDATE tbldetalle_orden set packageDetailId='".$packageDetailId."' WHERE tracking='".$myrow ['tracking']."' ";
            
            $cons  = mysql_query($sent,$conection) or die ("Error generando el eBinv del Ponumber ".$row ['Ponumber']); 
           
          }

				   
            //Leyendo los datos de las ordenes a exportar
            $sql =   "SELECT vendor,Ponumber, poline, cpcantidad, ups, tracking, ShipDT_traking, eBing, cpitem, merchantSKU, shippingWeight, weightUnit, partnerTrxID, packageDetailID, merchantLineNumber FROM tbldetalle_orden WHERE ShipDT_traking BETWEEN '".$fecha1."' AND '".$fecha2."' AND eBing != '0' AND tracking!='' AND status='Shipped' AND estado_orden='Active' order by eBing";
            
           //Para XML
           //$sqlXML =   "SELECT vendor,Ponumber, poline, cpcantidad, ups, tracking, ShipDT_traking, eBing, cpitem, merchantSKU, shippingWeight FROM tbldetalle_orden WHERE ShipDT_traking BETWEEN '".$fecha1."' AND '".$fecha2."' AND eBing != '0' AND tracking!='' AND status='Shipped' AND estado_orden='Active' order by eBing";
           //Fin para XML

           $val = mysql_query($sql,$conection);
				   $cant = 0;
				   while($row = mysql_fetch_array($val)){
					   $cant ++;
						echo "<tr>";
							echo "<td align='center'>".$row['vendor']."</td>";
							echo "<td align='center'>".$row['Ponumber']."</td>";
							echo "<td align='center'><strong>".$row['poline']."</strong></td>";
							echo "<td align='center'><strong>".$row['cpcantidad']."</strong></td>";
							echo "<td align='center'><strong>".$row['ups']."</strong></td>";
							echo "<td align='center'>".$row['tracking']."</td>";
							echo "<td align='center'>".$row['ShipDT_traking']."</td>";
							echo "<td align='center'>".$row['eBing']."</td>";
							echo "</tr>";						
			   }
			   echo "<tr><td align ='center'><strong>".$cant."</strong></td><td><strong>Órdenes generadas</strong></td></tr>";
			   mysql_close($conection);
			   $_SESSION["sql2"] = $sql;
         
			}
	  }
  }
  ?>
  </form>
  </table>
  </div>   <!-- table responsive -->
  </div>    <!-- table responsive -->
  </td>
  </tr>
  </table>

</div> <!-- panel panel-primary -->

</div> <!-- panel heading -->

<div class="panel-heading">
  <div class="contenedor" align="center">
    <strong>Bit <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span> 2015 versión 3</strong>
    <br>
    <strong><a href="http://www.bit-store.ec/index.php/contactenos/"  style="color:white">Info</a> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></strong>
  </div>
</div>



</div>   <!-- /container -->

</body>
</html>

