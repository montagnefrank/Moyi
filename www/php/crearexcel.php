<?php
	ini_set('memory_limit', '-1');
        include('conectarSQL.php');
	require_once ('PHPExcel.php');
	include ("conexion.php");
	include ("seguridad.php");
	session_start();
	$sql    =  $_SESSION["sql"];
	$user   =  $_SESSION["login"];
	$rol    =  $_SESSION["rol"];
	$pais   =  $_SESSION["pais"];
	$ip     =  $_SERVER['REMOTE_ADDR'];


	$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());
	$query=mysql_query($sql,$link);
	$cantfilas = mysql_num_rows($query);

	
	//Aqui se crea el objeto de tipo excel 2007
	
	$objPHPExcel = new PHPExcel();	
	//Se le crean las propiedades
	$objProps= $objPHPExcel->getProperties();
	$objProps->setCreator("Bit-Store.ec");

	
	$objPHPExcel->setActiveSheetIndex(0); 
        $objActSheet = $objPHPExcel->getActiveSheet(); 
	
	//Datos para canada
	$NRITaxid   = '816170971RM0001';
	$NRIAccount = 'A173A5';
	$NRIPhone   = '305-905-0153';
	$NRIZip     = 'N8N2M1';
	$NRIState   = 'ON';
	$NRICity    = 'WINDSOR RR2';
	$NRIAdd3    = 'MIAMI FL 33155';
	$NRIAdd2    = '';
	$NRIAdd1    = '2231 S.W. 82 PLACE';
	$NRIAtt     = 'ALINA ALZUGARAY';
	$NRIComp    = 'E-Blooms Direct Inc.';
	
	if($pais == 'US'){
		//echo "USA";/*
		//Dandole tama�os a las filas
		$objActSheet->getColumnDimension('A')->setAutoSize(true); 
		$objActSheet->getColumnDimension('B')->setAutoSize(true);
		$objActSheet->getColumnDimension('C')->setAutoSize(true); 
		$objActSheet->getColumnDimension('D')->setAutoSize(true);
		$objActSheet->getColumnDimension('E')->setAutoSize(true); 
		$objActSheet->getColumnDimension('F')->setAutoSize(true);
		$objActSheet->getColumnDimension('G')->setAutoSize(true); 
		$objActSheet->getColumnDimension('H')->setAutoSize(true);
		$objActSheet->getColumnDimension('I')->setAutoSize(true); 
		$objActSheet->getColumnDimension('J')->setAutoSize(true);
		$objActSheet->getColumnDimension('K')->setAutoSize(true); 
		$objActSheet->getColumnDimension('L')->setAutoSize(true);
		$objActSheet->getColumnDimension('M')->setAutoSize(true); 
		$objActSheet->getColumnDimension('N')->setAutoSize(true);
		$objActSheet->getColumnDimension('O')->setAutoSize(true); 
		$objActSheet->getColumnDimension('P')->setAutoSize(true);
		$objActSheet->getColumnDimension('Q')->setAutoSize(true); 
		$objActSheet->getColumnDimension('R')->setAutoSize(true);
		$objActSheet->getColumnDimension('S')->setAutoSize(true); 
		$objActSheet->getColumnDimension('T')->setAutoSize(true);
		$objActSheet->getColumnDimension('U')->setAutoSize(true); 
		$objActSheet->getColumnDimension('V')->setAutoSize(true);
		$objActSheet->getColumnDimension('W')->setAutoSize(true); 
		$objActSheet->getColumnDimension('Y')->setAutoSize(true);
		$objActSheet->getColumnDimension('Z')->setAutoSize(true);
		
		$objActSheet->getColumnDimension('AA')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AB')->setAutoSize(true);
		$objActSheet->getColumnDimension('AC')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AD')->setAutoSize(true);
		$objActSheet->getColumnDimension('AE')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AF')->setAutoSize(true);
		$objActSheet->getColumnDimension('AG')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AH')->setAutoSize(true);
		$objActSheet->getColumnDimension('AI')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AJ')->setAutoSize(true);
		$objActSheet->getColumnDimension('AK')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AL')->setAutoSize(true);
		$objActSheet->getColumnDimension('AM')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AN')->setAutoSize(true);
		$objActSheet->getColumnDimension('AO')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AP')->setAutoSize(true);
		$objActSheet->getColumnDimension('AQ')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AR')->setAutoSize(true);
		$objActSheet->getColumnDimension('AS')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AT')->setAutoSize(true);
                $objActSheet->getColumnDimension('AU')->setAutoSize(true);
		
			
		//Se crean el encabezado del documento
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Tracking')
				->setCellValue('B1', 'Company')
				->setCellValue('C1', 'eBinv')
				->setCellValue('D1', 'Orddate')
				->setCellValue('E1', 'Shipto')
				->setCellValue('F1', 'shipto2')
				->setCellValue('G1', 'address')
				->setCellValue('H1', 'address2')
				->setCellValue('I1', 'city')
				->setCellValue('J1', 'state')
				->setCellValue('K1', 'zip')
				->setCellValue('L1', 'phone')
				->setCellValue('M1', 'soldto')
				->setCellValue('N1', 'soldto2')
				->setCellValue('O1', 'STPhone')
				->setCellValue('P1', 'Ponumber')
				->setCellValue('Q1', 'CUSTnbr')
				->setCellValue('R1', 'SHIPDT')
				->setCellValue('S1', 'deliver')
				->setCellValue('T1', 'SatDel')
				->setCellValue('U1', 'Quantity')
				->setCellValue('V1', 'Item')
				->setCellValue('W1', 'ProdDesc')
				->setCellValue('X1', 'Length')
				->setCellValue('Y1', 'width')
				->setCellValue('Z1', 'height')
				->setCellValue('AA1', 'WeightKg')
				->setCellValue('AB1', 'DclValue')
				->setCellValue('AC1', 'Message')
				->setCellValue('AD1', 'Service')
				->setCellValue('AE1', 'PkgType')
				->setCellValue('AF1', 'GenDesc')
				->setCellValue('AG1', 'ShipCtry')
				->setCellValue('AH1', 'Currency')
				->setCellValue('AI1', 'Origin')
				->setCellValue('AJ1', 'UOM')
				->setCellValue('AK1', 'TPComp')
				->setCellValue('AL1', 'TPAttn')
				->setCellValue('AM1', 'TPAdd1')
				->setCellValue('AN1', 'TPCity')
				->setCellValue('AO1', 'TPState')
				->setCellValue('AP1', 'TPCtry')
				->setCellValue('AQ1', 'TPZip')
				->setCellValue('AR1', 'TPPhone')
				->setCellValue('AS1', 'TPAcct')
				->setCellValue('AT1', 'Farm');
               if($_SESSION["login"]=='MFA' || $_SESSION["login"]=='MON')
                 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AU1', 'MSG');

	}else{
		//echo "CA";
		    //Dandole tama�os a las filas
		$objActSheet->getColumnDimension('A')->setAutoSize(true); 
		$objActSheet->getColumnDimension('B')->setAutoSize(true);
		$objActSheet->getColumnDimension('C')->setAutoSize(true); 
		$objActSheet->getColumnDimension('D')->setAutoSize(true);
		$objActSheet->getColumnDimension('E')->setAutoSize(true); 
		$objActSheet->getColumnDimension('F')->setAutoSize(true);
		$objActSheet->getColumnDimension('G')->setAutoSize(true); 
		$objActSheet->getColumnDimension('H')->setAutoSize(true);
		$objActSheet->getColumnDimension('I')->setAutoSize(true); 
		$objActSheet->getColumnDimension('J')->setAutoSize(true);
		$objActSheet->getColumnDimension('K')->setAutoSize(true); 
		$objActSheet->getColumnDimension('L')->setAutoSize(true);
		$objActSheet->getColumnDimension('M')->setAutoSize(true); 
		$objActSheet->getColumnDimension('N')->setAutoSize(true);
		$objActSheet->getColumnDimension('O')->setAutoSize(true); 
		$objActSheet->getColumnDimension('P')->setAutoSize(true);
		$objActSheet->getColumnDimension('Q')->setAutoSize(true); 
		$objActSheet->getColumnDimension('R')->setAutoSize(true);
		$objActSheet->getColumnDimension('S')->setAutoSize(true); 
		$objActSheet->getColumnDimension('T')->setAutoSize(true);
		$objActSheet->getColumnDimension('U')->setAutoSize(true); 
		$objActSheet->getColumnDimension('V')->setAutoSize(true);
		$objActSheet->getColumnDimension('W')->setAutoSize(true); 
		$objActSheet->getColumnDimension('Y')->setAutoSize(true);
		$objActSheet->getColumnDimension('Z')->setAutoSize(true);
		
		$objActSheet->getColumnDimension('AA')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AB')->setAutoSize(true);
		$objActSheet->getColumnDimension('AC')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AD')->setAutoSize(true);
		$objActSheet->getColumnDimension('AE')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AF')->setAutoSize(true);
		$objActSheet->getColumnDimension('AG')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AH')->setAutoSize(true);
		$objActSheet->getColumnDimension('AI')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AJ')->setAutoSize(true);
		$objActSheet->getColumnDimension('AK')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AL')->setAutoSize(true);
		$objActSheet->getColumnDimension('AM')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AN')->setAutoSize(true);
		$objActSheet->getColumnDimension('AO')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AP')->setAutoSize(true);
		$objActSheet->getColumnDimension('AQ')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AR')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AS')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AT')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AU')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AV')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AW')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AX')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AY')->setAutoSize(true); 
		$objActSheet->getColumnDimension('AZ')->setAutoSize(true); 
		$objActSheet->getColumnDimension('BA')->setAutoSize(true); 
		$objActSheet->getColumnDimension('BB')->setAutoSize(true);
		$objActSheet->getColumnDimension('BC')->setAutoSize(true); 
		$objActSheet->getColumnDimension('BD')->setAutoSize(true);
		 
		
			
		//Se crean el encabezado del docuemneto
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Tracking')
				->setCellValue('B1', 'Company')
				->setCellValue('C1', 'eBinv')
				->setCellValue('D1', 'Orddate')
				->setCellValue('E1', 'Shipto')
				->setCellValue('F1', 'shipto2')
				->setCellValue('G1', 'address')
				->setCellValue('H1', 'address2')
				->setCellValue('I1', 'city')
				->setCellValue('J1', 'state')
				->setCellValue('K1', 'zip')
				->setCellValue('L1', 'phone')
				->setCellValue('M1', 'soldto')
				->setCellValue('N1', 'soldto2')
				->setCellValue('O1', 'STPhone')
				->setCellValue('P1', 'Ponumber')
				->setCellValue('Q1', 'CUSTnbr')
				->setCellValue('R1', 'SHIPDT')
				->setCellValue('S1', 'deliver')
				->setCellValue('T1', 'Quantity')
				->setCellValue('U1', 'Item')
				->setCellValue('V1', 'ProdDesc')
				->setCellValue('W1', 'Length')
				->setCellValue('X1', 'width')
				->setCellValue('Y1', 'height')
				->setCellValue('Z1', 'WeightKg')
				->setCellValue('AA1', 'DclValue')
				->setCellValue('AB1', 'Message')
				->setCellValue('AC1', 'Service')
				->setCellValue('AD1', 'PkgType')
				->setCellValue('AE1', 'GenDesc')
				->setCellValue('AF1', 'ShipCtry')
				->setCellValue('AG1', 'Currency')
				->setCellValue('AH1', 'Origin')
				->setCellValue('AI1', 'UOM')
				->setCellValue('AJ1', 'TPComp')
				->setCellValue('AK1', 'TPAttn')
				->setCellValue('AL1', 'TPAdd1')
				->setCellValue('AM1', 'TPCity')
				->setCellValue('AN1', 'TPState')
				->setCellValue('AO1', 'TPCtry')
				->setCellValue('AP1', 'TPZip')
				->setCellValue('AQ1', 'TPPhone')
				->setCellValue('AR1', 'TPAcct')
				->setCellValue('AS1', 'NRIComp')
				->setCellValue('AT1', 'NRIAtt')
				->setCellValue('AU1', 'NRIAdd1')
				->setCellValue('AV1', 'NRIAdd2')
				->setCellValue('AW1', 'NRIAdd3')
				->setCellValue('AX1', 'NRICity')
				->setCellValue('AY1', 'NRIState')
				->setCellValue('AZ1', 'NRIZip')
				->setCellValue('BA1', 'NRIPhone')
				->setCellValue('BB1', 'NRIAccount')
				->setCellValue('BC1', 'NRITaxid')
				->setCellValue('BD1', 'Farm');
                if($_SESSION["login"]=='MFA' || $_SESSION["login"]=='MON')
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('BE1', 'MSG');
		
	}

	$j=2;
	

	//recorro todas las filas y en cada una escribo el valor de cada celda
	if($pais == 'US'){

			while($row=mysql_fetch_array($query)){
				
				if($rol == 1){
					//if($row["estado_orden"] =='Active' && $row["tracking"]!=''){
								$objPHPExcel->setActiveSheetIndex(0)
								->setCellValueExplicit('A'.$j, $row["tracking"],PHPExcel_Cell_DataType::TYPE_STRING)
								->setCellValue('B'.$j, $row["nombre_compania"])
								->setCellValueExplicit('C'.$j, $row["eBing"],PHPExcel_Cell_DataType::TYPE_STRING) //setCellValue('C'.$j, $row["eBing"])
								->setCellValue('D'.$j, $row["order_date"])
								->setCellValue('E'.$j, $row["shipto1"])  
								->setCellValue('F'.$j, $row["shipto2"])
								->setCellValue('G'.$j, $row["direccion"])
								->setCellValue('H'.$j, $row["direccion2"])
								->setCellValue('I'.$j, $row["cpcuidad_shipto"])
								->setCellValue('J'.$j, $row["cpestado_shipto"])
								->setCellValue('K'.$j, $row["cpzip_shipto"])
								->setCellValue('L'.$j, $row["cptelefono_shipto"])
								->setCellValue('M'.$j, $row["soldto1"])
								->setCellValue('N'.$j, $row["soldto2"])
								->setCellValue('O'.$j, $row ["cpstphone_soldto"])
								->setCellValueExplicit('P'.$j, $row ["Ponumber"],PHPExcel_Cell_DataType::TYPE_STRING)
								->setCellValueExplicit('Q'.$j, $row ["Custnumber"],PHPExcel_Cell_DataType::TYPE_STRING)
								->setCellValue('R'.$j, $row ["ShipDT_traking"])
								->setCellValue('S'.$j, $row ["delivery_traking"])
								->setCellValue('T'.$j, $row ["satdel"])
								->setCellValue('U'.$j, $row ["cpcantidad"])
								->setCellValue('V'.$j, $row ["cpitem"])
								->setCellValue('W'.$j, $row ["prod_descripcion"])
								->setCellValue('x'.$j, $row ["length"])
								->setCellValue('Y'.$j, $row ["width"])
								->setCellValue('Z'.$j, $row ["heigth"])
								->setCellValue('AA'.$j, $row ["wheigthKg"])
								->setCellValue('AB'.$j, $row ["dclvalue"])
								->setCellValue('AC'.$j, $row ["cpmensaje"])
								->setCellValue('AD'.$j, $row ["cpservicio"])
								->setCellValue('AE'.$j, $row ["cptipo_pack"])
								->setCellValue('AF'.$j, $row ["gen_desc"])
								->setCellValue('AG'.$j, $row ["cppais_envio"])
								->setCellValue('AH'.$j, $row ["cpmoneda"])
								->setCellValue('AI'.$j, $row ["cporigen"])
								->setCellValue('AJ'.$j, $row ["cpUOM"])
								->setCellValue('AK'.$j, $row ["empresa"])
								->setCellValue('AL'.$j, $row ["director"])
								->setCellValue('AM'.$j, $row ["direccion_director"])
								->setCellValue('AN'.$j, $row ["cuidad_director"])
								->setCellValue('AO'.$j, $row ["estado_director"])
								->setCellValue('AP'.$j, $row ["pais_director"])
								->setCellValue('AQ'.$j, $row ["tpzip_director"])
								->setCellValue('AR'.$j, $row ["tpphone_director"])
								->setCellValue('AS'.$j, $row ["tpacct_director"])
								->setCellValue('AT'.$j, $row ["farm"]);
                                                 if($_SESSION["login"]=='MFA' || $_SESSION["login"]=='MON')               
                                                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AU'.$j, $row ["cpmensaje"]=='To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N': 'Y');
                                                 $j++;
						
					//}
					
				}else{
					if((strcmp($row["estado_orden"],'Active') == 0 && $row["tracking"]=='') || (strcmp($row["estado_orden"],'Active') == 0 && $row["tracking"]!='' && $row["consolidado"]=='Y')){
								$objPHPExcel->setActiveSheetIndex(0)
								->setCellValueExplicit('A'.$j, $row["tracking"],PHPExcel_Cell_DataType::TYPE_STRING)
								->setCellValue('B'.$j, $row["nombre_compania"])
								->setCellValueExplicit('C'.$j, $row["eBing"],PHPExcel_Cell_DataType::TYPE_STRING) //setCellValue('C'.$j, $row["eBing"])
								->setCellValue('D'.$j, $row["order_date"])
								->setCellValue('E'.$j, $row["shipto1"])  
								->setCellValue('F'.$j, $row["shipto2"])
								->setCellValue('G'.$j, $row["direccion"])
								->setCellValue('H'.$j, $row["direccion2"])
								->setCellValue('I'.$j, $row["cpcuidad_shipto"])
								->setCellValue('J'.$j, $row["cpestado_shipto"])
								->setCellValue('K'.$j, $row["cpzip_shipto"])
								->setCellValue('L'.$j, $row["cptelefono_shipto"])
								->setCellValue('M'.$j, $row["soldto1"])
								->setCellValue('N'.$j, $row["soldto2"])
								->setCellValue('O'.$j, $row ["cpstphone_soldto"])
								->setCellValueExplicit('P'.$j, $row ["Ponumber"],PHPExcel_Cell_DataType::TYPE_STRING)
								//->setCellValue('O'.$j, $row ["Ponumber"])
								->setCellValueExplicit('Q'.$j, $row ["Custnumber"],PHPExcel_Cell_DataType::TYPE_STRING)
								->setCellValue('R'.$j, $row ["ShipDT_traking"])
								->setCellValue('S'.$j, $row ["delivery_traking"])
								->setCellValue('T'.$j, $row ["satdel"])
								->setCellValue('U'.$j, $row ["cpcantidad"])
								->setCellValue('V'.$j, $row ["cpitem"])
								->setCellValue('W'.$j, $row ["prod_descripcion"])
								->setCellValue('X'.$j, $row ["length"])
								->setCellValue('Y'.$j, $row ["width"])
								->setCellValue('Z'.$j, $row ["heigth"])
								->setCellValue('AA'.$j, $row ["wheigthKg"])
								->setCellValue('AB'.$j, $row ["dclvalue"])
								->setCellValue('AC'.$j, $row ["cpmensaje"])
								->setCellValue('AD'.$j, $row ["cpservicio"])
								->setCellValue('AE'.$j, $row ["cptipo_pack"])
								->setCellValue('AF'.$j, $row ["gen_desc"])
								->setCellValue('AG'.$j, $row ["cppais_envio"])
								->setCellValue('AH'.$j, $row ["cpmoneda"])
								->setCellValue('AI'.$j, $row ["cporigen"])
								->setCellValue('AJ'.$j, $row ["cpUOM"])
								->setCellValue('AK'.$j, $row ["empresa"])
								->setCellValue('AL'.$j, $row ["director"])
								->setCellValue('AM'.$j, $row ["direccion_director"])
								->setCellValue('AN'.$j, $row ["cuidad_director"])
								->setCellValue('AO'.$j, $row ["estado_director"])
								->setCellValue('AP'.$j, $row ["pais_director"])
								->setCellValue('AQ'.$j, $row ["tpzip_director"])
								->setCellValue('AR'.$j, $row ["tpphone_director"])
								->setCellValue('AS'.$j, $row ["tpacct_director"])
								->setCellValue('AT'.$j, $row ["farm"]);
                                                                 if($_SESSION["login"]=='MFA' || $_SESSION["login"]=='MON')
                                                                     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AU'.$j, $row ["cpmensaje"]=='To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N': 'Y');
									//contador de ordenes
							$j++;
							//echo  $j."<br>";
							//Marco que la orden fue descargado y por quien
							if( $rol >= 2){
								$codi = $row["id_detalleorden"];
								$sql="UPDATE tbldetalle_orden SET descargada='Downloaded', user='".$user."', status='Ready to ship' where id_detalleorden=".$codi.";";
								mysql_query($sql)or die ("Error updating...");
								
								//Guardar datos de la operacion de subida de ordenes
										/******* Subir Orden ********************/
										/******* Descargar Orden ****************/
										/******* Subir tracking  ****************/
										
										$fecha = date('Y-m-d H:i:s');
										$SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`) 
														                   VALUES ('$user','Descargar Orden','$fecha','$ip')";
										$consultaHist = mysql_query($SqlHistorico,$link) or die ("Error actualizando la bitacora de usuarios");	
							}
							
					}
				}
		
		}
	}else{
		//Si es canada
		//echo "CA 1";
       // echo mysql_num_rows($query);
	   $i=0;
			while($row=mysql_fetch_array($query)){
				$i++;
				//echo $i."<br>";/*
				if($rol == 1){
					//if(strcmp($row["estado_orden"],'Active') == 0 && $row["tracking"]!=''){
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValueExplicit('A'.$j, $row["tracking"],PHPExcel_Cell_DataType::TYPE_STRING)
							->setCellValue('B'.$j, $row["nombre_compania"])
							->setCellValueExplicit('C'.$j, $row["eBing"],PHPExcel_Cell_DataType::TYPE_STRING) //setCellValue('C'.$j, $row["eBing"])
							->setCellValue('D'.$j, $row["order_date"])
							->setCellValue('E'.$j, $row["shipto1"])  
							->setCellValue('F'.$j, $row["shipto2"])
							->setCellValue('G'.$j, $row["direccion"])
							->setCellValue('H'.$j, $row["direccion2"])
							->setCellValue('I'.$j, $row["cpcuidad_shipto"])
							->setCellValue('J'.$j, $row["cpestado_shipto"])
							->setCellValue('K'.$j, $row["cpzip_shipto"])
							->setCellValue('L'.$j, $row["cptelefono_shipto"])
							->setCellValue('M'.$j, $row["soldto1"])
							->setCellValue('N'.$j, $row["soldto2"])
							->setCellValue('O'.$j, $row ["cpstphone_soldto"])
							->setCellValueExplicit('P'.$j, $row ["Ponumber"],PHPExcel_Cell_DataType::TYPE_STRING)
							->setCellValueExplicit('Q'.$j, $row ["Custnumber"],PHPExcel_Cell_DataType::TYPE_STRING)
							->setCellValue('R'.$j, $row ["ShipDT_traking"])
							->setCellValue('S'.$j, $row ["delivery_traking"])
							->setCellValue('T'.$j, $row ["cpcantidad"])
							->setCellValue('U'.$j, $row ["cpitem"])
							->setCellValue('V'.$j, $row ["prod_descripcion"])
							->setCellValue('W'.$j, $row ["length"])
                                                        ->setCellValue('X'.$j, $row ["width"])
                                                        ->setCellValue('Y'.$j, $row ["heigth"])
                                                        ->setCellValue('Z'.$j, $row ["wheigthKg"])
                                                        ->setCellValue('AA'.$j, $row ["dclvalue"])
							->setCellValue('AB'.$j, $row ["cpmensaje"])
							->setCellValue('AC'.$j, $row ["cpservicio"])
							->setCellValue('AD'.$j, $row ["cptipo_pack"])
							->setCellValue('AE'.$j, $row ["gen_desc"])
							->setCellValue('AF'.$j, $row ["cppais_envio"])
							->setCellValue('AG'.$j, $row ["cpmoneda"])
							->setCellValue('AH'.$j, $row ["cporigen"])
							->setCellValue('AI'.$j, $row ["cpUOM"])
							->setCellValue('AJ'.$j, $row ["empresa"])
							->setCellValue('AK'.$j, $row ["director"])
							->setCellValue('AL'.$j, $row ["direccion_director"])
							->setCellValue('AM'.$j, $row ["cuidad_director"])
							->setCellValue('AN'.$j, $row ["estado_director"])
							->setCellValue('AO'.$j, $row ["pais_director"])
							->setCellValue('AP'.$j, $row ["tpzip_director"])
							->setCellValue('AQ'.$j, $row ["tpphone_director"])
							->setCellValue('AR'.$j, $row ["tpacct_director"])
							->setCellValue('AS'.$j, $NRIComp)
							->setCellValue('AT'.$j, $NRIAtt)
							->setCellValue('AU'.$j, $NRIAdd1)
							->setCellValue('AV'.$j, $NRIAdd2)
							->setCellValue('AW'.$j, $NRIAdd3)
							->setCellValue('AX'.$j, $NRICity)
							->setCellValue('AY'.$j, $NRIState)
							->setCellValue('AZ'.$j,$NRIZip)
							->setCellValue('BA'.$j,$NRIPhone)
							->setCellValue('BB'.$j,$NRIAccount)
							->setCellValue('BC'.$j,$NRITaxid)
							->setCellValue('BD'.$j, $row ["farm"]);
                                                        if($_SESSION["login"]=='MFA' || $_SESSION["login"]=='MON')
                                                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('BE'.$j, $row ["cpmensaje"]=='To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N': 'Y');
							$j++;
				}else{
					if((strcmp($row["estado_orden"],'Active') == 0 && $row["tracking"]=='') || (strcmp($row["estado_orden"],'Active') == 0 && $row["tracking"]!='' && $row["consolidado"]=='Y')){
								$objPHPExcel->setActiveSheetIndex(0)
								->setCellValueExplicit('A'.$j, $row["tracking"],PHPExcel_Cell_DataType::TYPE_STRING)
								->setCellValue('B'.$j, $row["nombre_compania"])
								->setCellValueExplicit('C'.$j, $row["eBing"],PHPExcel_Cell_DataType::TYPE_STRING) //setCellValue('C'.$j, $row["eBing"])
								->setCellValue('D'.$j, $row["order_date"])
								->setCellValue('E'.$j, $row["shipto1"])  
								->setCellValue('F'.$j, $row["shipto2"])
								->setCellValue('G'.$j, $row["direccion"])
								->setCellValue('H'.$j, $row["direccion2"])
								->setCellValue('I'.$j, $row["cpcuidad_shipto"])
								->setCellValue('J'.$j, $row["cpestado_shipto"])
								->setCellValue('K'.$j, $row["cpzip_shipto"])
								->setCellValue('L'.$j, $row["cptelefono_shipto"])
								->setCellValue('M'.$j, $row["soldto1"])
								->setCellValue('N'.$j, $row["soldto2"])
								->setCellValue('O'.$j, $row ["cpstphone_soldto"])
								->setCellValueExplicit('P'.$j, $row ["Ponumber"],PHPExcel_Cell_DataType::TYPE_STRING)
								->setCellValueExplicit('Q'.$j, $row ["Custnumber"],PHPExcel_Cell_DataType::TYPE_STRING)
								->setCellValue('R'.$j, $row ["ShipDT_traking"])
								->setCellValue('S'.$j, $row ["delivery_traking"])
								->setCellValue('T'.$j, $row ["cpcantidad"])
								->setCellValue('U'.$j, $row ["cpitem"])
								->setCellValue('V'.$j, $row ["prod_descripcion"])
								->setCellValue('W'.$j, $row ["length"])
								->setCellValue('X'.$j, $row ["width"])
								->setCellValue('Y'.$j, $row ["heigth"])
								->setCellValue('Z'.$j, $row ["wheigthKg"])
								->setCellValue('AA'.$j, $row ["dclvalue"])
								->setCellValue('AB'.$j, $row ["cpmensaje"])
								->setCellValue('AC'.$j, $row ["cpservicio"])
								->setCellValue('AD'.$j, $row ["cptipo_pack"])
								->setCellValue('AE'.$j, $row ["gen_desc"])
								->setCellValue('AF'.$j, $row ["cppais_envio"])
								->setCellValue('AG'.$j, $row ["cpmoneda"])
								->setCellValue('AH'.$j, $row ["cporigen"])
								->setCellValue('AI'.$j, $row ["cpUOM"])
								->setCellValue('AJ'.$j, $row ["empresa"])
								->setCellValue('AK'.$j, $row ["director"])
								->setCellValue('AL'.$j, $row ["direccion_director"])
								->setCellValue('AM'.$j, $row ["cuidad_director"])
								->setCellValue('AN'.$j, $row ["estado_director"])
								->setCellValue('AO'.$j, $row ["pais_director"])
								->setCellValue('AP'.$j, $row ["tpzip_director"])
								->setCellValue('AQ'.$j, $row ["tpphone_director"])
								->setCellValue('AR'.$j, $row ["tpacct_director"])
								->setCellValue('AS'.$j, $NRIComp)
								->setCellValue('AT'.$j, $NRIAtt)
								->setCellValue('AU'.$j, $NRIAdd1)
								->setCellValue('AV'.$j, $NRIAdd2)
								->setCellValue('AW'.$j, $NRIAdd3)
								->setCellValue('AX'.$j, $NRICity)
								->setCellValue('AY'.$j, $NRIState)
								->setCellValue('AZ'.$j,$NRIZip)
								->setCellValue('BA'.$j,$NRIPhone)
								->setCellValue('BB'.$j,$NRIAccount)
								->setCellValue('BC'.$j,$NRITaxid)
								->setCellValue('BD'.$j, $row ["farm"]);
                                                                
                                                                if($_SESSION["login"]=='MFA' || $_SESSION["login"]=='MON')
                                                                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('BE'.$j, $row ["cpmensaje"]=='To-Blank Info   ::From- Blank Info   ::Blank .Info' ? 'N': 'Y');
									//contador de ordenes
							$j++;
							//echo  $j."<br>";
							//Marco que la orden fue descargado y por quien
							if( $rol >= 2){
								$codi = $row["id_detalleorden"];
								$sql="UPDATE tbldetalle_orden SET descargada='Downloaded', user='".$user."', status='Ready to ship' where id_detalleorden=".$codi.";";
								mysql_query($sql)or die ("Error actualizando...");
								
								//Guardar datos de la operacion de subida de ordenes
										/******* Subir Orden ********************/
										/******* Descargar Orden ****************/
										/******* Subir tracking  ****************/
										
										$fecha = date('Y-m-d H:i:s');
										$SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`) 
														                   VALUES ('$user','Descargar Orden','$fecha','$ip')";
										$consultaHist = mysql_query($SqlHistorico,$link) or die ("Error actualizando la bitacora de usuarios");	
							}
							
					}
			
		      }
	     }

	}

	//Aqui se crea el docuemnto completo con todas las filas insertadas
	$objPHPExcel->getActiveSheet()->setTitle('Hoja1');
	$objPHPExcel->setActiveSheetIndex(0);	
	
	//Worksheet estilo predeterminado (de forma predeterminada diferentes necesidades y Preferencias Configurar individualmente)
	$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);

   // Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="Order.xlsx"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');