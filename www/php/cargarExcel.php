<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL); 
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");
require_once ('PHPExcel.php');
include('date.php');
include('convertHex-Dec.php');
include('consecutivo.php');
include('convertir_Excel.php');
include ('PHPExcel/IOFactory.php');

session_start();
$user = $_SESSION["login"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Carga de ordenes</title>
<script type="text/javascript" src="../js/script.js"></script>
</head>
<body>
<?php
$fila = 1;
$array = '';
$dir = "./Archivos subidos/";

$orden= 2;
$id_order = 0;
//contar archivos
$total_excel = count(glob("$dir/{*.csv}",GLOB_BRACE));  //("$dir/{*.xlsx,*.xls,*.csv}",GLOB_BRACE));
if($total_excel ==0 ){
	echo "<font color='red'>No hay archivos para leer...</font><br>";
}else{
	echo "Total de archivos leídos: ".$total_excel."<br>";
	
	//renombrarlos para cargarlos
	$a= 1;
	$excels = (glob("$dir/{*.csv}",GLOB_BRACE));
	 foreach ($excels as $cvs){
			$expr = explode("/",$cvs);
			$nombre=array_pop($expr);
			rename("$dir/$nombre","$dir/$a.csv");		
			$a++;
	}
	
	//Convertir csv a excel
			 try 
				{ 		
					CSVToExcelConverter::convert("$dir/1.csv", "$dir/1.xlsx");
					unlink($dir."1.csv");  
				} catch(Exception $e) { 
					echo $e->getMessage(); 
				}
		
	//Se crea la tabla para mostrar en el navegador los datos de las ordenes cargadas
	echo "<a href='javascript:history.back(1)'>Atrás</a></br>";
	echo '<table width="100%" border="1" style="border-collapse:collapse;" cellspacing="0" cellpadding="0">';
	echo '<CAPTION ALIGN=top>Órdenes Cargadas</CAPTION>';
		
	//Aqui leemos cada uno de los excel cargados y se guardan sus datos a la BD
	for($i=1; $i <= $total_excel ; $i++){
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objReader->setReadDataOnly(true);
		//cargamos el archivo que deseamos leer
		$direccion = "$dir/$i.xlsx";
		$objPHPExcel = $objReader->load($direccion);
		$objHoja=$objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	
		foreach ($objHoja as $iIndice=>$objCelda) {
		  //LEEMOS EL ARCHIVO POR ORDEN DE COLUMNAS
		  $Ponumber  = $objCelda['AA'];
		   // **************** Si la fila empieza con tracking empieza un nuevo doc ****************************
		  if($Ponumber == 'Ponumber'){
			   //Imprimo el encabezado de cada archivo
				echo "<tr ALIGN=center BGCOLOR='#CCCCCC'>";
					echo '<td><strong># Fila</strong></td>';
					echo '<td><strong>Cliente</strong></td>';
					echo '<td><strong>Compañia</strong></td>';
					echo '<td><strong>Fecha de Órden</strong></td>';
					echo '<td><strong>Detalle Carrier</strong></td>';
					echo '<td><strong>Enviar a</strong></td>';
					echo '<td><strong>Enviar a2</strong></td>';
					echo '<td><strong>Dirección de Envio</strong></td>';
					echo '<td><strong>Dirección de Envio2</strong></td>';
					echo '<td><strong>Ciudad</strong></td>';
					echo '<td><strong>Estado</strong></td>';
					echo '<td><strong>Cod. Postal</strong></td>';
					echo '<td><strong>Teléfono</strong></td>';
					echo '<td><strong>e-Mail</strong></td>';
					echo '<td><strong>País</strong></td>';
					echo '<td><strong>Cobrar a</strong></td>';
					echo '<td><strong>Cobrar a2</strong></td>';
					echo '<td><strong>Dirección</strong></td>';
					echo '<td><strong>Dirección2</strong></td>';
					echo '<td><strong>Ciudad</strong></td>';
					echo '<td><strong>Estado</strong></td>';
					echo '<td><strong>Cod. Postal</strong></td>';
					echo '<td><strong>Teléfono</strong></td>';
					echo '<td><strong>Ponumber</strong></td>'; 
					echo '<td><strong>Custnumber</strong></td>';
					echo '<td><strong>Fecha de Envio</strong></td>';
					echo '<td><strong>Fecha de Entrega</strong></td>';
					echo '<td><strong>SatDel</strong></td>';
					echo '<td><strong>Elemento en Línea</strong></td>';
					echo '<td><strong>Cantidad</strong></td>';
					echo '<td><strong>Producto</strong></td>';
					echo '<td><strong>Precio de Compra</strong></td>';
					echo '<td><strong>Mensaje</strong></td>';
					echo '<td><strong>País de Envío</strong></td>'; 
					echo '<td><strong>Moneda</strong></td>';
					echo '<td><strong>Origen</strong></td>';
					echo '<td><strong>UOM</strong></td>'; 
                                        echo '<td><strong>Consolidado</strong></td>'; 
				echo "</tr>";
			}else{
					  //GENERAL
					  $vendor    = strtoupper(addslashes($objCelda['A'])); 	//TBLDETALLE_ORDEN
					  //Modificandop el cliente para si es costo-us o costco-ca
					  if($vendor == 'COSTCO-US'){
						  $vendor = '10000-US';
					  }
					  
					  if($vendor == 'COSTCO-CA'){
						  $vendor = '10001-US';
					  }
					  $Tracking  = '';							//TBLDETALLE_ORDEN
					  $Company   = 'eblooms';  //TBLORDEN
					  $eBinv     = 0;							//TBLDETALLE_ORDEN
					  $Orddate    = $objCelda['E'];
					  
					  //Armar la feca de orden
					  list($mes,$dia,$anno) = explode('/',$Orddate);
					 /* $mes  = substr($Orddate,0,2);
					  $dia  = substr($Orddate,3,2);
					  $anno = substr($Orddate,6,4);	*/		  
					  $Orddate    = $anno."-".$mes."-".$dia;	
					  $UPS  = addslashes($objCelda['F']);		//TBLDETALLE_ORDEN
					  
					  //SHIP TO	  
					  $Shipto    = addslashes($objCelda['G']);  //TBLSHIPTO
					  $Shipto    = addcslashes($Shipto,";");    
					  $Shipto2   = addslashes($objCelda['H']);  //TBLSHIPTO
					  $Shipto2    = addcslashes($Shipto2,";");		  
					  $address   = addslashes($objCelda['I']);  //TBLSHIPTO
					  $address2   = addslashes($objCelda['J']); //TBLSHIPTO
					  $city      = addslashes($objCelda['K']); //TBLSHIPTO
					  $state     = addslashes($objCelda['L']); //TBLSHIPTO
					  $zip       = $objCelda['M'];		       //TBLSHIPTO
					  $phone     = $objCelda['N'];             //TBLSHIPTO
					  $mail      = addslashes($objCelda['O']); //TBLSHIPTO
					  
					  //DESTINO DE LA ORDEN (US - CA)
					  $ShipCtry  = $objCelda['A'];					//TBLDETALLE_ORDEN		  
					  //saber si es US o CA
					  //$ShipCtry  = substr($ShipCtry,7,2); //Obtengo las dos ultimas letras
					  $ShipCtry  = explode("-",$ShipCtry);
					  $ShipCtry   =  $ShipCtry[1];
				  
					  //BILL TO
					  $soldto    = addslashes($objCelda['Q']);      //TBLSOLDTO
					  $soldto    = addcslashes( $soldto,";");
					  $soldto2   = addslashes($objCelda['R']);      //TBLSOLDTO
					  $soldto2   = addcslashes( $soldto2,";"); 
					  $solto_address1 =addslashes($objCelda['S']);  //TBLSOLDTO
					  $solto_address2 =addslashes($objCelda['T']);  //TBLSOLDTO
					  $solto_city =addslashes($objCelda['U']);      //TBLSOLDTO
					  $solto_state =addslashes($objCelda['V']);     //TBLSOLDTO
					  $solto_zip =addslashes($objCelda['W']);       //TBLSOLDTO
					  $soldto_phone   = $objCelda['X'];             //TBLSOLDTO
					  $soldto_mail   =$objCelda['Y'];				//TBLSOLDTO
					  $solto_country =$objCelda['Z']; 				//TBLSOLDTO
					  
					  //GENERAL  
					  $Ponumber  = exp_to_dec(trim($objCelda['AA']));			//TBLDETALLE_ORDEN
					  $CUSTnbr   = $objCelda['AB'];			//TBLDETALLE_ORDEN
					  
					  //EN EL CASO ODEL SHIP PPRIMERO HAY QUE LEER EL DELIVER PARA CALCULAR EL SHIPDT
					  $deliver   = $objCelda['AD'];			//TBLDETALLE_ORDEN
					  //Armar la feca de envio
					  list($mes,$dia,$anno) = explode('/',$deliver);
					 /* $dia  = substr($deliver,0,2);
					  $mes  = substr($deliver,3,2);
					  $anno = substr($deliver,6,4);	*/		  
					  $deliver    = $anno."-".$mes."-".$dia;  
					  
					  //ITEM
					  $Item      = $objCelda['AI'];			//TBLDETALLE_ORDEN

					  //$Origin    = "EC";				//TBLDETALLE_ORDEN
					  
					//Obteniendo el origen para obtener el pais de origen (codigo_ciudad-pais)
					$sqlorg   = "SELECT origen FROM tblproductos WHERE tblproductos.id_item = '$Item'";

					//echo $query;
					$query5 = mysqli_query($link,$sqlorg);
					$row   = mysqli_fetch_array($query5);
					$cporigen = $row["origen"];
					$cporigen_city = explode("-", $cporigen);
					$cporigen = $cporigen_city[0];

					//Obteniendo el codigo del pais
					$sqlorg   = "SELECT codpais_origen FROM tblciudad_origen WHERE tblciudad_origen.codciudad = '$cporigen'";
					//echo $query;
					$query5 = mysqli_query($link,$sqlorg);
					$row   = mysqli_fetch_array($query5);

					$Origin = $row["codpais_origen"];

					   //Obtener dia de la semana para saber cuanto restar al deliver para asignarle al shipdt
					  $fecha = date('l', strtotime($deliver));	  
					  //verifico que dia es para restarle los dias que son 
					  /*
						Si el envio es de ECUADOR
					  */
					  if($Origin == "EC"){ 
							// Si es Maertes, Jueves o Viernes le resto 3 dias
							if(strcmp($fecha,"Tuesday")==0 || strcmp($fecha,"Thursday")==0 || strcmp($fecha,"Friday")==0) {
								$SHIPDT = strtotime ( '-3 day' , strtotime ( $deliver ) ) ;
								$SHIPDT = date ( 'Y-m-j' , $SHIPDT );	//TBLDETALLE_ORDEN
							}else{
								//Si es otro dia de envio osea Miercoles
								$SHIPDT = strtotime ( '-4 day' , strtotime ( $deliver ) ) ;
								$SHIPDT = date ( 'Y-m-j' , $SHIPDT );  //TBLDETALLE_ORDEN
								}					
					  }else{
 					        $SHIPDT = strtotime ( '-5 day' , strtotime ( $deliver ) ) ;
                			$SHIPDT = date ( 'Y-m-j' , $shipdt );  //TBLDETALLE_ORDEN    
					  }
					  
					 // $SatDel    = $objCelda['AE'];			//TBLDETALLE_ORDEN
					  $POline    = $objCelda['AF'];			//TBLDETALLE_ORDEN
					  $Quantity  = $objCelda['AG'];			//TBLDETALLE_ORDEN
					  
					  //GENERAL
					  $Message   = addslashes($objCelda['AP']); //TBLORDEN
					 
					 
					  //ShipContry preguntar
					  $Currency  = "USD";				//TBLDETALLE_ORDEN
					  $UOM       = "BOX"; //PREGUNTAR   //TBLDETALLE_ORDEN
					  
					  //GENERAL  
					  $Farm      = '';				//TBLDETALLE_ORDEN
					  $Unitprice = $objCelda['BH']; //TBLDETALLE_ORDEN
					  
					  //ESTADOS DE LA ORDEN
					  $estado    ='Active';			 //TBLDETALLE_ORDEN	  
					  $descargada = 'not downloaded';//TBLDETALLE_ORDEN
					  $user      = '';				//TBLDETALLE_ORDEN
					  $status    = 'New';           //TBLDETALLE_ORDEN
					  $coldroom  = 'No';            //TBLDETALLE_ORDEN
					  $SatDel    = 'N';           //TBLDETALLE_ORDEN
                                          
                                          $consolidado = $objCelda['BK']; //consolidado de la orden
                                          if($consolidado=="")  $consolidado="N";
			  //verifico si la orden tiene custnumber, ponumber, item, pais,
			  //echo $Ponumber." ".$Custnumber." ".$Item." ".$ShipCtry; 
			  if($Ponumber == '' | $CUSTnbr == '' | $Item == '' | ($ShipCtry != 'US' && $ShipCtry != 'CA')){
				  //echo "The order ".$orden." missing data, such as PONumber, custnumber, etc. Please review"."<br>";
				  echo '<font color="red"> A la orden '.$orden.' le faltan datos o hay datos erroneos en el archivo, por favor revise el Ponumber, Custnumber, Item y el País de destino (Cliente-CA 0 Cliente-US)...</font><br>';
				  /*$j++;
				  $orden++;*/
				  break;
			  }else{
				  //Verifico que la orden no este  registrado en la bd (RESTRICION PARA SUBIR ORDENES)
					$sql   = "SELECT
                                                    tbldetalle_orden.id_orden_detalle
                                                    FROM
                                                    tbldetalle_orden
                                                    WHERE
                                                    tbldetalle_orden.Custnumber = '$CUSTnbr' AND
                                                    tbldetalle_orden.Ponumber = '$Ponumber' AND
                                                    tbldetalle_orden.cpitem = '$Item'";
					//echo $query;
					$query = mysqli_query($link,$sql);
					$row     =mysqli_fetch_array($query);
					//echo $row[0]."<br>";
					
					//verifico si hay datos 
					$ray = mysqli_num_rows($query);
					if($ray > 0 ){ //Si el item esta registrado uso su detalles
						echo "<font color='red'>La orden ".$orden." con Ponumber: ".$Ponumber." y Custnumber: ".$CUSTnbr." ya fue insertada."."</font><br>";
						$j++;
						$orden++;
					}else{
					      for($i= 1; $i <= $Quantity ; $i++){
							if($i == 1){
                                                            //inserto una linea

                                                            //Verifico que el item del producto este registrado en la bd 
                                                            $query   = "select * from tblproductos where id_item= '$Item'";
                                                            $sql     = mysqli_query($link,$query) or die (mysqli_error());//selecciona los registros iguales aItem
                                                            $ray	 = mysqli_num_rows($sql);
                                                            if($ray == 0 ){ //Si el item esta registrado uso su detalles
                                                                    echo "<font color='red'>El producto associado al item ". $Item." No esta registrado, por favor registrelo antes de continuar.</font>";
                                                                    break;
                                                            }

                                                            //verificar si el mensaje viene vacio o no.
                                                            if($Message == ''){
                                                                    $Message = "To-Blank Info   ::From- Blank Info   ::Blank .Info"; 
                                                            }else{
                                                                    $Message = addslashes($Message); 
                                                            }

                                                               // Conectarse a la BD y guardar los datos			
                                                               //Insertar los datos de tblorden
                                                                $sql = "Insert INTO tblorden(nombre_compania,cpmensaje,order_date)VALUES ('$Company','$Message','$Orddate')";	
                                                                    mysqli_query($link,$sql)or die (mysqli_error()); //OK

                                                                    $select_last_id = "SELECT id_orden FROM tblorden ORDER BY id_orden DESC LIMIT 1";
                                                                    $result_last_id = mysqli_query($link,$select_last_id);
                                                                    $row_last_id = mysqli_fetch_array($result_last_id);
                                                                    $id_order = $row_last_id[0];

                                                                    //Insertar los datos de Shipto
                                                                    $sql1 = "Insert INTO `tblshipto`(`id_shipto`,`shipto1`,`shipto2`,`direccion`,`direccion2`,`cpestado_shipto`,`cpcuidad_shipto`,`cptelefono_shipto`,`cpzip_shipto`,`mail`)VALUES ('$id_order','$Shipto','$Shipto2','$address','$address2','$state','$city','$phone','$zip','$mail')";
                                                                    mysqli_query($link,$sql1)or die (mysqli_error()); //OK

                                                                    //Insertar los datos de Soldto
                                                                    $sql2 = "Insert INTO `tblsoldto`(`id_soldto`,`soldto1`,`soldto2`,`cpstphone_soldto`,`address1`,`address2`,`city`,`state`,`postalcode`,`billcountry`,`billmail`)VALUES ('$id_order','$soldto','$soldto2','$soldto_phone','$solto_address1','$solto_address2','$solto_city','$solto_state','$solto_zip','$solto_country','$soldto_mail')";
                                                                    mysqli_query($link,$sql2)or die (mysqli_error()); //ok

                                                                    //Insertar los datos de tbldirector
                                                                    $sql5 = "Insert INTO `tbldirector`(`id_director`)VALUES ('$id_order')";
                                                                    mysqli_query($link,$sql5)or die (mysqli_error()); //ok

                                                                    //Inserto los detalles del primer producto de la orden
                                                                    $sql3 = "Insert INTO `tbldetalle_orden`(`id_detalleorden`,`Custnumber`,`Ponumber`,`cpitem`,`cpcantidad`,`farm`,`satdel`,`cppais_envio`,`cpmoneda`,`cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`tracking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`reenvio`,`poline`,`unitprice`,`ups`,`codigo`,`vendor`,`consolidado`)VALUES ('$id_order','$CUSTnbr','$Ponumber','$Item','1','$Farm','$SatDel','$ShipCtry','$Currency','$Origin','$UOM','$deliver','$SHIPDT','$Tracking','$estado','$descargada','$user','$eBinv','No','$status','No','$POlin','$Unitprice','$UPS','0','$vendor','$consolidado')";

                                                                    //echo $sql3;
                                                                    mysqli_query($link,$sql3)or die (mysqli_error());

                                                                    //Guardar datos de la operacion de subida de ordenes
                                                                    /******* Subir Orden ********************/
                                                                    /******* Descargar Orden ****************/
                                                                    /******* Subir tracking  ****************/

                                                                    $fecha = date('Y-m-d H:i:s');
                                                                    $SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`) 
                                                                                                                       VALUES ('$usuario','Subir Orden','$fecha','$ip')";
                                                                    $consultaHist = mysqli_query($link,$SqlHistorico) or die ("Error actualizando la bitacora de usuarios");

                                                                    //Imprimir la orden leida
                                                            echo '<tr ALIGN=center VALIGN=center>';
                                                            echo '<td>'.$orden."</td>";
                                                            echo '<td>'.$vendor."</td>";
                                                            echo '<td>'.$Company."</td>";
                                                            echo '<td>'.$Orddate."</td>";
                                                            echo '<td>'.$UPS."</td>";
                                                            echo '<td>'.$Shipto."</td>";
                                                            echo '<td>'.$Shipto2."</td>";
                                                            echo '<td>'.$address."</td>";
                                                            echo '<td>'.$address2."</td>";
                                                            echo '<td>'.$city."</td>";
                                                            echo '<td>'.$state."</td>";
                                                            echo '<td>'.$zip."</td>";
                                                            echo '<td>'.$phone."</td>";
                                                            echo '<td>'.$mail."</td>";
                                                            echo '<td>'.$ShipCtry."</td>";
                                                            echo '<td>'.$soldto."</td>";
                                                            echo '<td>'.$soldto2."</td>";
                                                            echo '<td>'.$solto_address1."</td>";
                                                            echo '<td>'.$solto_address2."</td>";
                                                            echo '<td>'.$solto_city."</td>";
                                                            echo '<td>'.$solto_state."</td>";
                                                            echo '<td>'.$solto_zip."</td>";
                                                            echo '<td>'.$soldto_phone."</td>";
                                                            echo '<td>'.$Ponumber."</td>";
                                                            echo '<td>'.$CUSTnbr."</td>";
                                                            echo '<td>'.$SHIPDT."</td>";
                                                            echo '<td>'.$deliver."</td>";
                                                            echo '<td>'.$SatDel."</td>";
                                                            echo '<td>'.$POline."</td>";
                                                            echo '<td>1</td>';
                                                            echo '<td>'.$Item."</td>";
                                                            echo '<td>'.$Unitprice."</td>";
                                                            echo '<td>'.$Message."</td>";
                                                            echo '<td>'.$ShipCtry."</td>";
                                                            echo '<td>'.$Currency."</td>";
                                                            echo '<td>'.$Origin."</td>";
                                                            echo '<td>'.$UOM."</td>";	
                                                            echo '<td>'.$consolidado."</td>";	
                                                    echo '<tr>';
                                                     $orden ++;
							
						}else{
																  
                                                        //Inserto los detalles del primer producto de la orden
                                                        $sql3 = "Insert INTO `tbldetalle_orden`(`id_detalleorden`,`Custnumber`,`Ponumber`,`cpitem`,`cpcantidad`,`farm`,`satdel`,`cppais_envio`,`cpmoneda`,`cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`tracking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`reenvio`,`poline`,`unitprice`,`ups`,`codigo`,`vendor`,`consolidado`)VALUES ('$id_order','$CUSTnbr','$Ponumber','$Item','1','$Farm','$SatDel','$ShipCtry','$Currency','$Origin','$UOM','$deliver','$SHIPDT','$Tracking','$estado','$descargada','$user','$eBinv','No','$status','No','$POlin','$Unitprice','$UPS','0','$vendor','$consolidado')";
                                                        //echo $sql3;
                                                        mysqli_query($link,$sql3)or die (mysqli_error());

                                                        //Guardar datos de la operacion de subida de ordenes
                                                        /******* Subir Orden ********************/
                                                        /******* Descargar Orden ****************/
                                                        /******* Subir tracking  ****************/

                                                        $fecha = date('Y-m-d H:i:s');
                                                        $SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`) 
                                                                                                           VALUES ('$usuario','Subir Orden'".$Ponumber.",'$fecha','$ip')";
                                                        $consultaHist = mysqli_query($link,$SqlHistorico) or die ("Error actualizando la bitacora de usuarios");
											
											
										//imprimo la orden leida
											//Imprimir la orden leida
                                                        echo '<tr ALIGN=center VALIGN=center>';
                                                        echo '<td>'.$orden."</td>";
                                                        echo '<td>'.$vendor."</td>";
                                                        echo '<td>'.$Company."</td>";
                                                        echo '<td>'.$Orddate."</td>";
                                                        echo '<td>'.$UPS."</td>";
                                                        echo '<td>'.$Shipto."</td>";
                                                        echo '<td>'.$Shipto2."</td>";
                                                        echo '<td>'.$address."</td>";
                                                        echo '<td>'.$address2."</td>";
                                                        echo '<td>'.$city."</td>";
                                                        echo '<td>'.$state."</td>";
                                                        echo '<td>'.$zip."</td>";
                                                        echo '<td>'.$phone."</td>";
                                                        echo '<td>'.$mail."</td>";
                                                        echo '<td>'.$ShipCtry."</td>";
                                                        echo '<td>'.$soldto."</td>";
                                                        echo '<td>'.$soldto2."</td>";
                                                        echo '<td>'.$solto_address1."</td>";
                                                        echo '<td>'.$solto_address2."</td>";
                                                        echo '<td>'.$solto_city."</td>";
                                                        echo '<td>'.$solto_state."</td>";
                                                        echo '<td>'.$solto_zip."</td>";
                                                        echo '<td>'.$soldto_phone."</td>";
                                                        echo '<td>'.$Ponumber."</td>";
                                                        echo '<td>'.$CUSTnbr."</td>";
                                                        echo '<td>'.$SHIPDT."</td>";
                                                        echo '<td>'.$deliver."</td>";
                                                        echo '<td>'.$SatDel."</td>";
                                                        echo '<td>'.$POline."</td>";
                                                        echo '<td>1</td>';
                                                        echo '<td>'.$Item."</td>";
                                                        echo '<td>'.$Unitprice."</td>";
                                                        echo '<td>'.$Message."</td>";
                                                        echo '<td>'.$ShipCtry."</td>";
                                                        echo '<td>'.$Currency."</td>";
                                                        echo '<td>'.$Origin."</td>";
                                                        echo '<td>'.$UOM."</td>";
                                                        echo '<td>'.$consolidado."</td>";
                                                        echo '<tr>';								   
                                                    $orden++;
									
						  }//for
					      }//else
				        }//else
			  }
	   }// fin foreach	
	}// fin for		
			echo '</table>';
}

			//CErrando la conexion a mysqli_
//			mysqli_close($conection);
			
			$handle = opendir($dir); 
			while ($file = readdir($handle))  {  
				 if (is_file($dir.$file)) { 
					unlink($dir.$file); 
				}
			}
}
		echo "<a href='javascript:history.back(1)'>Volver Atras</a>";
?>
</body>
</html>