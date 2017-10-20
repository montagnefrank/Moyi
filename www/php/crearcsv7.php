<?php

ini_set('memory_limit', '-1');
include('conectarSQL.php');
require_once ('PHPExcel.php');
include ('PHPExcel/IOFactory.php');
include ("conexion.php");
include ("seguridad.php");

session_start();


$nombre = 'report';
$query = mysqli_query($sqlrep);
$col = mysqli_num_fields($query);

$fp = fopen('report.csv', 'w');


//CREAMOS LOS ENCABEZADOS
//SI ES ROL 3 TIENE ENCABEZADO SIN MENSAJE AL FINAL
fputcsv($fp, array('Tracking', 'Company', 'eBinv', 'Orddate', 'Shipto', 'Shipto2', 'Address', 'Address2', 'City', 'State', 'Zip', 'Phone', 'Soldto', 'Soldto2', 'STPhone', 'Ponumber', 'CUSTnbr', 'SHIPDT', 'Deliver', 'SatDel', 'Quantity', 'Item', 'ProdDesc', 'Length', 'Width', 'Height', 'WeightKg', 'DclValue', 'Message', 'Service', 'PkgType', 'GenDesc', 'ShipCtry', 'Currency', 'Origin', 'UOM', 'TPComp', 'TPAttn', 'TPAdd1', 'TPCity', 'TPState', 'TPCtry', 'TPZip', 'TPPhone', 'TPAcct', 'Farm'));

//GENERAMOS EL CSV
while ($rowr = mysqli_fetch_assoc($query)) {

    fputcsv($fp, $rowr);
}

fclose($fp);

//LECTOR DE CSV PARA PREPARARLO A XLS
$objReader = PHPExcel_IOFactory::createReader('CSV');
//CARGAMOS EL CSV DENTRO DEL XLS
$objPHPExcel = $objReader->load('report.csv');

//CONVERTIMOS EL VALOR DE PONUMBER A EXPLICITO PARA EVITAR QUE EXCEL CONVIERTA EL VALOR
$porowcount = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
$j = "2";
for ($j = 2; $j <= $porowcount; $j++) {
    $pvalor = $objPHPExcel->getActiveSheet()->getCell('P' . $j)->getValue();
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('P' . $j, $pvalor, PHPExcel_Cell_DataType::TYPE_STRING);
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Report.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

// **SOPORTE** VERIFICAMOS LAS SALIDAS DE NUESTRO SCRIPT
// 
// //header("Content-Type: text/csv; charset=utf-8");
//header("Content-disposition: filename=" . $nombre . ".csv");
//print $csv;
//print_r($sqlrep);
//print_r($sql);
//print_r($pais);
//print_r($sqlup);
//print_r($sqlupdate);
//print_r($SqlHistorico);
//die;
exit;
?>