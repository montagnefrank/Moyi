<?php

ini_set('memory_limit', '-1');
include('conectarSQL.php');
require_once ('PHPExcel.php');
include ("conexion.php");
include ("seguridad.php");

session_start();

$sql = $_SESSION["sql"];
$sqlrep = $_SESSION["sqlrep"];
$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : ' . mysqli_error());
$user = $_SESSION["login"];
$rol = $_SESSION["rol"];
$pais = $_SESSION["pais"];
$ip = $_SERVER['REMOTE_ADDR'];
$nombre = 'order';
$query = mysqli_query($sqlrep);
$query2 = mysqli_query($sql);
$col = mysqli_num_fields($query);

//Identificamos el PAIS
if ($pais == 'US') {
    //GENERAMOS LOS CABECEROS DEL CSV
    $csv = "Tracking,Company,eBinv,Orddate,Shipto,Shipto2,Address,Address2,City,State,Zip,Phone,Soldto,Soldto2,STPhone,Ponumber,CUSTnbr,SHIPDT,Deliver,SatDel,Quantity,Item,ProdDesc,Length,Width,Heigth,WeightKg,DclValue,Message,Service,PkgTypex,GenDesc,ShipCtry,Currency,Origin,UOM,TPComp,TPAttn,TPAdd1,TPCity,TPState,TPCtry,TPZip,TPPhone,TPAcct,Farm,MSG" . "\n";
    //RELLENAMOS EL CONTENIDO DEL CSV
    while ($rowr = mysqli_fetch_array($query)) {
        if (ltrim(rtrim($rowr[46])) == 'To-Blank Info   ::From- Blank Info   ::Blank .Info') {
            $rowr[46] = "N";
        } else {
            $rowr[46] = "Y";
        }
        for ($j = 0; $j < $col; $j++) {
            //DEPURAMOS QUE LOS ELEMENTOS NO CONTENGAN COMAS NI SALTO DE LINEA
            $row2 = str_replace(array(',', "\n", "\r"), '.', $rowr);
            $csv .= $row2[$j] . ", ";
        }
        $csv .= "\n";
    }
} else {
    //GENERAMOS LOS CABECEROS DEL CSV
    $csv = "Tracking,Company,eBinv,Orddate,Shipto,Shipto2,Address,Address2,City,State,Zip,Phone,Soldto,Soldto2,STPhone,Ponumber,CUSTnbr,SHIPDT,Deliver,SatDel,Quantity,Item,ProdDesc,Length,Width,Heigth,WeightKg,DclValue,Message,Service,PkgTypex,GenDesc,ShipCtry,Currency,Origin,UOM,TPComp,TPAttn,TPAdd1,TPCity,TPState,TPCtry,TPZip,TPPhone,TPAcct,Farm, NRIComp, NRIAtt, NRIAdd1, NRIAdd2, NRIAdd3, NRICity, NRIState, NRIZip, NRIPhone, NRIAccount, NRITaxid" . "\n";
    //RELLENAMOS EL CONTENIDO DEL CSV
    for ($j = 0; $j < $col; $j++) {
        //DEPURAMOS QUE LOS ELEMENTOS NO CONTENGAN COMAS NI SALTO DE LINEA
        $row2 = str_replace(array(',', "\n", "\r"), '.', $rowr);
        $csv .= $row2[$j] . "   , ";
    }
    $csv .= "E-Blooms Direct Inc., ALINA ALZUGARAY, 2231 S.W. 82 PLACE, , MIAMI FL 33155, WINDSOR RR2, ON, N8N2M1, 305-905-0153, A173A5, 816170971RM0001";
    $csv .= "\n";
}


//VALIDAMOS SI EL USUARIO MODIFICA ESTATUS DE DESCARGA
while ($rowr = mysqli_fetch_array($query2)) {
    if ((strcmp($rowr["estado_orden"], 'Active') == 0 && $rowr["tracking"] == '') || (strcmp($rowr["estado_orden"], 'Active') == 0 && $rowr["tracking"] != '' && $rowr["consolidado"] == 'Y')) {
        if ($rol >= 2) {
            $sqlup .= $rowr["id_detalleorden"] . ",";
        }
    }
}
//REMOVEMOS LA ULTIMA COMA PARA NO GENERAR ERROR DE SINTAXIS
$sqlup = substr(trim($sqlup), 0, -1);
//ACTUALIZAMOS ESTATUS A DESCARGADO
$sqlupdate = "UPDATE tbldetalle_orden SET descargada='Downloaded', user='" . $user . "', status='Ready to ship' where id_detalleorden in (" . $sqlup . ");";
mysqli_query($sqlupdate)or die("Error updating...");

// ALIMENTAMOS LA BITACORA
$fecha = date('Y-m-d H:i:s');
$SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`) VALUES ('$user','Descargar Orden','$fecha','$ip')";
$consultaHist = mysqli_query($SqlHistorico) or die("Error actualizando la bitacora de usuarios");

//GENERAMOS EL ARCHIVO CSV CON SU CONTENIDO
header("Content-Type: text/csv; charset=utf-8");
header("Content-disposition: filename=" . $nombre . ".csv");
print $csv;

// **SOPORTE** VERIFICAMOS LAS SALIDAS DE NUESTRO SCRIPT
//print_r($sqlrep);
//print_r($sql);
//print_r($pais);
//print_r($sqlup);
//print_r($sqlupdate);
//print_r($SqlHistorico);
//die;

exit;
?>