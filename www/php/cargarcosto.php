<?php
//incluir otros archivos php
include('conectarSQL.php');
include('conexion.php');
include('date.php');
include('convertHex-Dec.php');
include('consecutivo.php');
include('PHPExcel/IOFactory.php');
include('convertir_Excel.php');
include ("seguridad.php");

$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Carga de Costos</title>
<script type="text/javascript" src="../js/script.js"></script>
</head>
<body>
<?php
$orden= 0;
$fila = 1;
$array = '';
$dir = "./Archivos subidos/";

$orden=0;
$id_order = 0;
//contar archivos
$total_excel = count(glob("$dir/{*.csv}",GLOB_BRACE));  //("$dir/{*.xlsx,*.xls,*.csv}",GLOB_BRACE));
if($total_excel ==0 ){
	echo "<font color='red'>No hay ficheros para cargar...</font><br>";
}else{
	echo "Total de ficheros cargados: ".$total_excel."<br>";
	
	//renombrarlos para cargarlos
	$a= 1;
	$excels = (glob("$dir/{*.csv}",GLOB_BRACE));
	 foreach ($excels as $cvs){
			$expr = explode("/",$cvs);
			$nombre=array_pop($expr);
			rename("$dir/$nombre","$dir/$a.csv");		
			$a++;
	  }
}	
	//Convertir csv a excel
	 try 
		{ 		
			CSVToExcelConverter::convert("$dir/1.csv", "$dir/1.xlsx"); 
		} catch(Exception $e) { 
			echo $e->getMessage(); 
		}
		
	//Se crea la tabla para mostrar en el navegador los datos de las ordenes cargadas
	echo "<a href='administration.php'>Volver Atrás</a>";
	echo '<table width="100%" border="1" style="border-collapse:collapse;" cellspacing="0" cellpadding="0">';
	echo '<CAPTION ALIGN=top>Tabla de Costos</CAPTION>';
		
	//Aqui leemos cada uno de los excel cargados y se guardan sus datos a la BD
	for($i=1; $i <= $total_excel ; $i++){
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objReader->setReadDataOnly(true);
		//cargamos el archivo que deseamos leer
		$direccion = "$dir/$i.xlsx";
		$objPHPExcel = $objReader->load($direccion);
		$objHoja=$objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		echo '<tr><td align="center"><strong>Invoice#</strong></td><td align="center"><strong>Invoice Date</strong></td><td align="center"><strong>Total Amount</strong></td><td align="center"><strong>Discount Amount</strong></td><td align="center"><strong>Net Amount</strong></td><td align="center"><strong>Due Date</strong></td><td align="center"><strong>PO#</strong></td><td align="center"><strong>Cost</strong></td><td align="center"><strong>Diference</strong></td></tr>';
		$cont = 0;
		$costoTotal = 0;
		$company = '';
		$vendor  = '';
		$check   = 0;
		$totalCredito = 0;
		$credito = 0;
		$costo_dif = 0;
		
	    foreach ($objHoja as $iIndice=>$objCelda) {
			
				if ($iIndice == 2){ //leyendo los datos generales del archivo
					$company =  $objCelda['A'];
					$vendor  =  $objCelda['B'];
					$check   =  $objCelda['C'];
				}else{	
				
						$invoice  = $objCelda['A'];
						$invoiceDate  = $objCelda['B'];
						list($anno,$mes,$dia) = explode('/',$invoiceDate);
					    $invoiceDate    = $anno."-".$mes."-".$dia; 
						$totalAmount  = $objCelda['C'];
						$discountAmount  = $objCelda['D'];
						$netAmount  = $objCelda['E'];
						$dueDate  = $objCelda['F'];
						list($anno,$mes,$dia) = explode('/',$dueDate);
					    $dueDate    = $anno."-".$mes."-".$dia;
						$poNbr  = $objCelda['G'];
			
						if($poNbr == ''){
								$newPo = $invoice;
						}else{
						 //Transformar el po para que el sistema lo entenda
						/** ejemplo de po 00847-2266-305*****************/
						$newPo  = str_replace("-","",$poNbr); //quitando los - 008472266305
						$newPo = substr($newPo,2,10); //quitando los ceros del principio 8472266305
						$newPo = substr_replace($newPo,'00',3,0);
						}
				  					
						if($iIndice > 4 && $totalAmount < 0){	 //Es pq es credito	
							
							//verifico si  el credito existe en la tabla de pagos
							$SQL   = "SELECT `invoice#` FROM tblpagos WHERE `po#` = '".$newPo."' AND neto < 0";
							$QUERY = mysql_query($SQL,$link) or die ("Error en la conexion 1");
							$cantf = mysql_num_rows($QUERY);
							if($cantf > 0)	{
									echo "<tr bgcolor='#FF0000'><td colspan='9'>Ya el credito con Invoice#: ".$invoice." fue cargado al sistema</td></tr>";								
								}else{	
								
								//inserto el credito en la tabla de pagos
								$SENTENCIA = "INSERT INTO tblpagos (`company`,`check`,`vendor`,`invoice#`,`invoiceDate`,`total`,`descuento`,`neto`,`dueDate`,`po#`) VALUES ('$company','$check','$vendor','$invoice','$invoiceDate','$totalAmount','$discountAmount','$netAmount','$dueDate','$newPo')";
								$CONSULTA  = mysql_query($SENTENCIA,$link) or die ("Error en la consulta 1");
								
								$credito_dif = $totalAmount - $netAmount;
								
								echo '<tr><td align="center">'.$invoice.'</td><td align="center">'.$invoiceDate.'</td><td align="center">'.$totalAmount.'</td><td align="center">'.$discountAmount.'</td><td align="center">'.$netAmount.'</td><td align="center">'.$dueDate.'</td><td align="center">'.$newPo.'</td><td align="center">0</td><td align="center">'.$credito_dif.'</td></tr>';
								 
								$totalCredito += $totalAmount;
								$costoTotal   += $totalAmount;
							}
						}else{
							  
							  if($iIndice > 4  && $totalAmount > 0){ //es costo pagado		
									  
								  //verifico si  el costo existe en la tabla de pagos
								  //$SQL   = "SELECT `invoice#` FROM tblpagos WHERE `invoice#` = '".$invoice."'";
								  $SQL   = "SELECT `invoice#` FROM tblpagos WHERE `po#` = '".$newPo."' AND neto > 0";
								  $QUERY = mysql_query($SQL,$link) or die ("Error en la conexion 2");
								  $cantf = mysql_num_rows($QUERY);
								 
								  if($cantf > 0){
										echo "<tr bgcolor='#FF0000'><td colspan='9'>Ya el costo con Invoice#: ".$invoice." y PO#: ".$newPo." fue cargado al sistema</td></tr>";		
								  }else{	
											

								  			//$invoiceDate = date_format($invoiceDate, 'Y-m-d');

								  			//$invoiceDate = date('Y-m-d', $invoiceDate);

								  			//$dueDate = date_format($dueDate, 'Y-m-d');


											//$date = new DateTime($invoiceDate);
											//$invoiceDate = $date->format('Y-m-d'); 
											//echo $invoiceDate;


											//inserto el credito en la tabla de pagos
											$SENTENCIA = "INSERT INTO tblpagos (`company`,`check`,`vendor`,`invoice#`,`invoiceDate`,`total`,`descuento`,`neto`,`dueDate`,`po#`) VALUES ('$company','$check','$vendor','$invoice','$invoiceDate','$totalAmount','$discountAmount','$netAmount','$dueDate','$newPo')";
											

											/*
											echo "<br>";
											echo $company;
											echo "<br>";
											echo $check;
											echo "<br>";
											echo $vendor;
											echo "<br>";
											echo $invoice;
											echo "<br>";
											echo $invoiceDate;
											echo "<br>";
											echo $totalAmount;
											echo "<br>";
											echo $discountAmount;
											echo "<br>";
											echo $netAmount;
											echo "<br>";
											echo $dueDate;
											echo "<br>";
											echo $newPo;
											echo "<br>";

											echo $SENTENCIA;
											echo "<br>";

											*/

											$CONSULTA  = mysql_query($SENTENCIA,$link) or die ("Error en la consulta");
										
										  
										  //Comparar los costos de la BD con lo que viene de Costco
										  //$sql   = "SELECT costo FROM tblcosto WHERE ebinv = '".$invoice."'";
										  $sql   = "SELECT costo FROM tblcosto WHERE po = '".$newPo."'";
										  $query = mysql_query($sql,$link) or die ("Error seleccionando el costo de la orden con ebinv: ". $invoice);
										  $row   = mysql_fetch_array($query);
										  $cant  = mysql_num_rows($query);
									
										  $costoTotal   += $totalAmount;
										  
										  //Verifico que si esta ese ebinv en la BD
										  if($cant >0){
												//Si esta obtengo su costo
												$costo = $row['costo'];
												// Le resto el costo total de nuestra bd a lo que envia costco
												$costo_dif = $costo - $netAmount;
												
												//Actualizo la tabla de costos con la diferencia de cosotos
												$sentencia = "UPDATE tblcosto SET credito = '".$costo_dif."',pago = '".$netAmount."', pagado = 'Si'  WHERE `po` = '".$newPo."'";
												$consulta = mysql_query($sentencia ,$link) or die ("Error actualizando el credito de la orden con ebinv: ". $invoice);											
												
												//Contar las filas 
												  $cont++;
												  echo '<tr bgcolor="#66FF00"><td align="center">'.$invoice.'</td><td align="center">'.$invoiceDate.'</td><td align="center">'.$totalAmount.'</td><td align="center">'.$discountAmount.'</td><td align="center">'.$netAmount.'</td><td align="center">'.$dueDate.'</td><td align="center">'.$newPo.'</td><td align="center">'.$row['costo'].'</td><td align="center">'.$costo_dif.'</td></tr>';	
										  
										  }else{
											  //Contar las filas 
											  $cont++;
											  echo '<tr bgcolor="#FF0000"><td align="center">'.$invoice.'</td><td align="center">'.$invoiceDate.'</td><td align="center">'.$totalAmount.'</td><td align="center">'.$discountAmount.'</td><td align="center">'.$netAmount.'</td><td align="center">'.$dueDate.'</td><td align="center">'.$newPo.'</td><td align="center">No tiene costo</td><td align="center">No esta en el sistema</td></tr>';										  
											  }
								       }
								  }
							  }
					}
			}
			//Resultado final
			echo '<tr><td></td></tr>';
			echo '<tr><td></td></tr>';							

			echo '<tr><td align="center"><strong>Company#</strong></td><td align="center"><strong>Vendor#</strong></td><td align="center"><strong>Check#</strong></td><td align="center"><strong>Total Balance</strong></td><td align="center"><strong>Total Credits</strong></td></tr>';
			echo '<tr><td align="center">'.$company.'</td><td align="center">'.$vendor.'</td><td align="center">'.$check.'</td><td align="center">'.$costoTotal.'</td><td align="center">'.$totalCredito.'</td></tr>';
			$handle = opendir($dir); 
			while ($file = readdir($handle))  {  
				 if (is_file($dir.$file)) { 
					unlink($dir.$file); 
				}
			}
     }
	 echo '</table>';

			//CErrando la conexion a mysql
			mysql_close($conection);

		echo "<a href='administration.php'>Volver Atrás</a>";
?>
</body>
</html>
