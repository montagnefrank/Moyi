<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require (__DIR__ . "/../scripts/conn.php");
require ('xmlHandler.php');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////CONEXION A DB
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

//Procesar los archivos XML de la carpeta RSSBUS: Incoming
$directorio = opendir("C:\Program Files\RSSBus\RSSBus Server\data\as2connector\profiles\COMMERCEHUB\Procesados"); //ruta de archivos XML
/////////////////////////////////////////////////////////////////////////////////////////////////////DECLARAMOS LAS VARIABLES DONDE VAMOS A ALMACENAR LOS VALORES A INSERTAR
$insert_tblorden_values = '';
$insert_tblshipto_values = '';
$insert_tblsoldto_values = '';
$insert_tbldirector_values = '';
$insert_ord_index1_values = '';
$insert_tbldetalleord_values = '';
$insert_tblerror_values = '';
$insert_incoming_values = '';
/////////////////////////////////////////////////////////////////////////////////////////////////////LLAMAMOS EL ULTIMO AUTOINCREMENT DE TBLORDEN Y VAMOS ITERANDO LA SECUENCIA PARA LOS DEMAS INSERT, CON ESTO PODEMOS ASUMIR QUE LOS DATOS INGRESADOS SERAN RESPECTIVOS A CADA UNO DE LOS DEMAS INSERT EN LAS OTRAS TABLAS
$select_tblorden_codigo = "SELECT id_orden FROM tblorden ORDER BY id_orden DESC LIMIT 1";
$result_tblorden_codigo = mysqli_query($link, $select_tblorden_codigo);
$row_tblorden_codigo = mysqli_fetch_array($result_tblorden_codigo, MYSQLI_BOTH);
$tblorden_codigo = $row_tblorden_codigo[0];
while ($archivo = readdir($directorio)) { //obtenemos el primer archivo y luego otro sucesivamente
    $archivo_alertado = 0; /////////////////////////////////////////////////////////////////////////////EN CASO DE EXISTIR ALERTA, ENVIAR EL ARCHIVO A CARPETA DE IRREGULARES
    if (!is_dir($archivo)) { //verificamos si es o no un directorio
        $archivoNombre = $archivo;
        $archivo = "C:\Program Files\RSSBus\RSSBus Server\data\as2connector\profiles\COMMERCEHUB\Procesados" . "\\" . $archivo;
        //Obtener los campos a llenar

        $xmlpo = new xmlHandler($archivo); //llamada a la clase que está en xmlHandler.php
        //---------------------------------------------------//
        //Tabla ORDEN tblorden
        //Nombre de compañía
        //$nombre_compania = $xmlpo->getXML()->partnerID;
        $nombre_compania = 'eblooms';
        $archivo_alertado = 0;
        ////////////////////////////////////////////////////////////////////////////////////////////////////LEEMOS TODO EL XML
        for ($i = 0; $i < $xmlpo->MessageCount(); $i++) {

            $hubOrder = $xmlpo->getHubOrder($i);
            $Custnumber = $xmlpo->getXML()->hubOrder[$i]->custOrderNumber;

            $Ponumber = (string) $xmlpo->getXML()->hubOrder[$i]->poNumber;
            //////////////////////////////////////////////////////////////////////////////////////////////////QUITAMOS LOS CEROS DEL INICIO
            $Ponumber = trim(substr($Ponumber, 2, 12));
            $array_item = array();
            for ($j = 0; $j < $xmlpo->LineItemCount($i); $j++) {
                $lineItem = $xmlpo->getLineItem($i, $j);
                $cpitem = "" . $lineItem->vendorSKU . "";
                $array_item[] = $cpitem;
            }
        }
        if (count(array_unique($array_item)) < count($array_item)) {
            echo "<font color='red'>El archivo " . $archivoNombre . " con Ponumber: " . $Ponumber . ", Custnumber: " . $Custnumber . ", Tiene pedidos repetidos." . "</font><br>";
            $archivo_alertado = 1;
        } else {
            echo "<font color='green'>El archivo " . $archivoNombre . " con Ponumber: " . $Ponumber . ", Custnumber: " . $Custnumber . ", Fue auditado y no present&oacute; errores." . "</font><br>";
        }
        //Mover el archivo XML a la carpeta de RSSBus: PROCESADOS
        if ($archivo_alertado == 1) {
            if ($xmlpo->moveXMLDefectFile($archivo, $archivoNombre)) {
                echo $archivo . " Procesado y movido a los archivos defectuosos";
                echo "<br>";
            } else {
                echo "Error moviendo el archivo: " . $archivo;
                echo "<br>";
            }
        } else {
            if ($xmlpo->moveXMLAuditFile($archivo, $archivoNombre)) {
                echo $archivo . " Procesado y movido a los archivos auditados";
                echo "<br>";
            } else {
                echo "Error moviendo el archivo: " . $archivo;
                echo "<br>";
            }
        }
    }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////REMOVEMOS LA ULTIMA COMA PARA NO GENERAR ERROR DE SINTAXIS
$insert_tblorden_values = substr(trim($insert_tblorden_values), 0, -1);
$insert_tblshipto_values = substr(trim($insert_tblshipto_values), 0, -1);
$insert_tblsoldto_values = substr(trim($insert_tblsoldto_values), 0, -1);
$insert_tbldirector_values = substr(trim($insert_tbldirector_values), 0, -1);
$insert_ord_index1_values = substr(trim($insert_ord_index1_values), 0, -1);
$insert_tbldetalleord_values = substr(trim($insert_tbldetalleord_values), 0, -1);
$insert_incoming_values = substr(trim($insert_incoming_values), 0, -1);
