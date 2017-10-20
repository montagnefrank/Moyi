<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");
include ("consecutivo.php");

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

$id = $_GET['id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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

</head>
<body background="../images/fondo.jpg">
<form id="form" name="form" method="post">
<table width="1024" border="0" align="center">
  <tr>
    <td height="133" align="center" width="100%"><img src="../images/logo.png" width="328" height="110" /></td>
  </tr>
        <tr>
  	<td width="436" colspan="2"><input type="image" src="../images/home.png" height="40" width="30" title="Inicio" formaction="administration.php">
    </td>
  </tr>
  <tr>
    <td height="34" width="100%" align="center" bgcolor="#3B5998"><ul id="MenuBar1" class="MenuBarHorizontal">
          </ul></td>
  </tr>
  </form>
  <tr>
  	<td>
    	<form method="post" id="frm1" name="frm1" target="_parent" >
            <table width="1024" border="0" align="center">
        
              <tr>
                    <td  colspan="5" align="center"> <strong>GENERAR EL CONFIRM MANIFEST FULL</strong></td>
              </tr>
              <tr>
                    <td width="285" align="right"> 
              <strong>Fecha Inicio:</strong>
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
                  </td>
                  <td width="274">
                <strong>Fecha Fin:</strong>
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
                  <td width="391">
                  <strong>
                  <input name="radio" type="radio" value="deliver" title="Filtrar por deliver"/>
                  Fecha Entrega</strong><strong>
                  <input name="radio" type="radio" value="ship" title="Filtrar por Ship Date"/>
                  Fecha Vuelo </strong><strong>
                  <input name="radio" type="radio" value="order" title="Filtrar por Order Date"/>
                  Fecha de órden:</strong></td>
                  <td width="56">
                <input type="submit" name="buscar" id="buscar" value="Buscar" onclick="return Compara(this.form)"/>
                </td>
                </td>
              </tr>
              </table>
          </form>
    </td>
  </tr>
<tr>
    <td id="inicio" bgcolor="#CCCCCC" height="100"> 
    <table width="1024" border="0" align="center"> 
  <tr>
    <td colspan="8" align="center">
    	<h3><strong>Lista de Órdenes</strong></h3>
    </td>
    <form action="crearcsv2.php" method="post" target="_blank">
    <td width="70" align="right">
        <input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/excel.png" heigth="40" value="" title = "Exportar Listado" width="30" onclick = ""/>
    </td>
    </form>
  </tr>
  <tr bgcolor="#E8F1FD">
    <td width="53" align="center"><strong>Cliente</strong></td>
    <td width="59" align="center"><strong>Producto</strong></td>
    <td width="276" align="center"><strong>Prod. Desc</strong></td>
    <td width="85" align="center"><strong>Ponumber</strong></td>
    <td width="152" align="center"><strong>Tracking</strong></td>
  	<td width="109" align="center"><strong>Fecha Entrega</strong></td>
    <td width="98" align="center"><strong>Fecha Vuelo</strong></td>
    <td width="84" align="center"><strong>Valor Orden</strong></td>
    <td align="center"><strong>eBinv</strong></td>
  </tr>
  <form id="form" name="form" method="post">
  <?php
  if(isset($_POST['buscar'])){
        $fecha1 = $_POST['txtinicio'];
		$fecha2 = $_POST['txtfin'];
		$radio  = $_POST['radio']; // Va atener el filtro marcado deliver-shipto-orderdate


		//verifico que las fechas y el filtro tengan valortengan valor  
		if($fecha1 != '' && $fecha2 != '' && $radio != ''){
			
		  //Verifivcar que filtr de fecha es y armar la consulta, segun sea el caso
		  if($radio == 'deliver'){
		  	$sql =   "SELECT DISTINCT Ponumber, eBing FROM tbldetalle_orden WHERE delivery_traking BETWEEN '".$fecha1."' AND '".$fecha2."' AND tracking!='' AND status='Shipped' AND estado_orden='Active';";
		  }else{
				  if($radio == 'ship'){
					$sql =   "SELECT DISTINCT Ponumber, eBing FROM tbldetalle_orden WHERE ShipDT_traking BETWEEN '".$fecha1."' AND '".$fecha2."' AND tracking!='' AND status='Shipped' AND estado_orden='Active';";
				  }else{
				 		 $sql =   "SELECT DISTINCT Ponumber, eBing FROM tbldetalle_orden INNER JOIN tblorden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden WHERE order_date BETWEEN '".$fecha1."' AND '".$fecha2."' AND tracking!='' AND status='Shipped' AND estado_orden='Active';";
					   }
			  }
		  
		  $_SESSION['sql1'] = $sql;
	      $val = mysqli_query($link,$sql);
		  if(!$val){
		  	echo "<tr><td>".mysqli_error()."</td></tr>";
		   }else{
			   //recorro cada Ponumber y si no tine eBinv lo genero
			   
			   while($row = mysqli_fetch_array($val)){
				   if($row ['eBing'] == 0){
					   //verifico si ya ese ponumber tine ebing asignado
					   $SQL = "select consecutivo from tblconsecutivo where Ponbr='".$row['Ponumber']."'";
					   $QUERY = mysqli_query($link,$SQL) or die (mysqli_error());
					   $FILA= mysqli_fetch_array($QUERY);
					   $CONT = mysqli_num_rows($QUERY);
					   
					   if($CONT > 0){
						  $eBing = $FILA['consecutivo']; 
					   }else{
						   //Generar eBinv
						   $eBing = generarConsecutivo();
						   //Insertar los datos del consecutivo 
						   $sql1 = "Insert INTO tblconsecutivo(consecutivo,Ponbr)VALUES(".$eBing.",'".$row ['Ponumber']."')";
						   mysqli_query($link,$sql1)or die ("Error creando eBinv");
						   $sql2 = "SELECT consecutivo FROM tblconsecutivo ORDER BY consecutivo desc LIMIT 1";
						   $query2 = mysqli_query($link,$sql2)or die ("Error seleccionando eBinv");
						   $row2= mysqli_fetch_row($query2);
						   $eBing= $row2[0];
					   }
					   
					   $sentencia = "UPDATE tbldetalle_orden set eBing =".$eBing." WHERE Ponumber='".$row ['Ponumber']."'";
					   $consulta  = mysqli_query($link,$sentencia) or die ("Error generando el eBinv del Ponumber ".$row ['Ponumber']);	
					   
					   //Insertando o modificando la tabla de costos
					   //Ahora se verifica si el Ponumber existe ya en la tabla de costo
						$a = "SELECT * FROM tblcosto WHERE po = '".$row ['Ponumber']."'";
						$b = mysqli_query($link,$a) or die ("Error consultando el POnumber: ".$row ['Ponumber']." en la tabla de costos");
						$c = mysqli_num_rows($b);
						
						//Obtengo la suma total de los costos asosciados al ponumber en cuestion
						$d = "SELECT SUM(unitprice),delivery_traking FROM tbldetalle_orden WHERE Ponumber = '".$row ['Ponumber']."' AND estado_orden='Active' AND tracking!='' AND status='Shipped'";
						$e = mysqli_query($link,$d) or die ("Error sumando los costos del POnumber: ".$row ['Ponumber']);
							
						//Obtengo el costo total del ponumber
						$f = mysqli_fetch_array($e);
						$costo = $f[0];
						$fecha_facturacion = $f[1];
						
						//Verifico si no hay fila con ese ponumber
						if($c == 0){						
							//Verifico si el costo es diferente de vacio
							if($costo >= 0){
								// Si no tiene fila creo una fila con el costo total de ese ponumber			
								//Se crea una nueva entrada en la tabla de costos
								$x = "INSERT INTO tblcosto (`po`,`ebinv`,`costo`,`credito`,`pagado`, `fecha_facturacion`) VALUES ('".$row ['Ponumber']."','".$eBing."','".$costo."','0','No','$fecha_facturacion')";
								$y = mysqli_query($link,$x) or die ("Error insertando el costo total del Ponumber: ".$row ['Ponumber']);	
							}else{
								  //elimino el costo de esa orden en la tabla de costos
								  $sql1="DELETE FROM tblcosto WHERE po='".$row ['Ponumber']."'";
								  $eliminado1= mysqli_query($link,$sql1) or die ("Erro eliminando un costo negativo");
								
								}
						}else{ //Si ya existe una fila con ese ponumber verifico que el costo se mantenga sin modificacion
								//verifico si el costo ha variado
								if($c['costo'] <> $costo){
									//Se modifca una nueva entrada en la tabla de costos
									$xx = "UPDATE tblcosto SET costo='".$costo."' WHERE po='".$row ['Ponumber']."'";
									$yy = mysqli_query($link,$xx) or die ("Error modificando el costo total del Ponumber: ".$row ['Ponumber']);
								}
							
							}						   			    
				   }
				   
				  }
				   
				   //Leyendo los datos de las ordenes a exportar
				    if($radio == 'deliver'){
				  		 $sql =   "SELECT vendor,cpitem,Ponumber,tracking,delivery_traking,ShipDT_traking,unitprice,eBing FROM tbldetalle_orden WHERE delivery_traking BETWEEN '".$fecha1."' AND '".$fecha2."' AND eBing != '0' AND tracking!='' AND status='Shipped' AND estado_orden='Active' order by eBing";
					}else{
							if($radio == 'ship'){
								 $sql =   "SELECT vendor,cpitem,Ponumber,tracking,delivery_traking,ShipDT_traking,unitprice,eBing FROM tbldetalle_orden WHERE ShipDT_traking BETWEEN '".$fecha1."' AND '".$fecha2."' AND eBing != '0' AND tracking!='' AND status='Shipped' AND estado_orden='Active' order by eBing";
							}else{
									 $sql =   "SELECT vendor,cpitem,Ponumber,tracking,delivery_traking,ShipDT_traking,unitprice,eBing FROM tbldetalle_orden INNER JOIN tblorden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden WHERE order_date BETWEEN '".$fecha1."' AND '".$fecha2."' AND eBing != '0' AND tracking!='' AND status='Shipped' AND estado_orden='Active' order by eBing";
							}						
						}
				   $val = mysqli_query($link,$sql);
				   $cant = 0;
				   while($row = mysqli_fetch_array($val)){
					   $cant ++;
						echo "<tr>";
							echo "<td align='center'>".$row['vendor']."</td>";
							echo "<td align='center'><strong>".$row['cpitem']."</strong></td>";
							
							//Seleccionar la descripcion del item
							$a = "SELECT prod_descripcion FROM tblproductos WHERE id_item = '".$row['cpitem']."'";
							$b = mysqli_query($link,$a) or die ("Error consultando la descripcion del item");
							$fila = mysqli_fetch_array($b);							
							echo "<td>".$fila['prod_descripcion']."</td>";
														
							echo "<td align='center'><strong>".$row['Ponumber']."</trong></td>";
							echo "<td align='center'>".$row['tracking']."</td>";
							echo "<td align='center'>".$row['delivery_traking']."</td>";
							echo "<td align='center'>".$row['ShipDT_traking']."</td>";							
							echo "<td align='center'>".$row['unitprice']."</td>";
							echo "<td align='center'><strong>".$row['eBing']."</strong></td>";
							echo "</tr>";						
			   }
			   echo "<tr><td align ='center'><strong>".$cant."</strong></td><td><strong>Órdenes generadas</strong></td></tr>";
			   mysqli_close($conection);
			   $_SESSION["sql2"] = $sql;	
			}
	  }
  }
  ?>
  </form>
  </table>
  </td>
  </tr>
  <tr>
    <td height="36" align="center" bgcolor="#3B5998" colspan="5"><strong><font color="#FFFFFF">Bit <img src="../images/r.png" width="15" height="15"/> 2015 versión 3 </font></strong></td>
  </tr>
  </table>
</body>
</html>

