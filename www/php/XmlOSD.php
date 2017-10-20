<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

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

header("Content-Type: text/csv; charset=utf-8");
header("Content-disposition: filename=xmlosd.csv");

//Procesar los archivos XML de la carpeta RSSBUS: Incoming
$directorio = opendir("C:\Program Files\RSSBus\RSSBus Server\data\as2connector\profiles\COMMERCEHUB\Auditados"); //ruta de archivos XML
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
        $archivo = "C:\Program Files\RSSBus\RSSBus Server\data\as2connector\profiles\COMMERCEHUB\Auditados" . "\\" . $archivo;
        //Obtener los campos a llenar

        $xmlpo = new xmlHandler($archivo); //llamada a la clase que está en xmlHandler.php
        //---------------------------------------------------//
        //Tabla ORDEN tblorden
        //Nombre de compañía
        //$nombre_compania = $xmlpo->getXML()->partnerID;
        $nombre_compania = 'BurtonTech';
        $archivo_alertado = 0;
        ////////////////////////////////////////////////////////////////////////////////////////////////////LEEMOS TODO EL XML
        for ($i = 0; $i < $xmlpo->MessageCount(); $i++) {

            $hubOrder = $xmlpo->getHubOrder($i);
            $Custnumber = $xmlpo->getXML()->hubOrder[$i]->custOrderNumber;

            $personPlaceIDshipTo = $xmlpo->getXML()->hubOrder[$i]->shipTo->attributes()->personPlaceID;
            $personPlaceIDbillTo = $xmlpo->getXML()->hubOrder[$i]->billTo->attributes()->personPlaceID;

            //Recorrer los dos personPlace de la hubOrder y recoger shipto y billto
            for ($k = 0; $k < 2; $k++) {

                $personPlaceshipTo = $xmlpo->getXML()->hubOrder[$i]->personPlace[$k]->attributes()->personPlaceID;

                $personPlacebillTo = $xmlpo->getXML()->hubOrder[$i]->personPlace[$k]->attributes()->personPlaceID;

                if ((string) $personPlaceshipTo == (string) $personPlaceIDshipTo) {
                    //$personPlaceshipToFinal = $personPlaceIDshipTo;
                    $personPlaceShip = $xmlpo->getXML()->hubOrder[$i]->personPlace[$k];
                }
                if ((string) $personPlacebillTo == (string) $personPlaceIDbillTo) {
                    //$personPlacebillToFinal = $personPlaceIDbillTo;
                    $personPlaceBill = $xmlpo->getXML()->hubOrder[$i]->personPlace[$k];
                }
            }

            //-----------------------------------//
            //Tabla SHIPTO tblshipto

            $shipto1 = addslashes($personPlaceShip->name1);
            $shipto2 = '';
            $direccion = addslashes($personPlaceShip->address1);
            $direccion2 = addslashes($personPlaceShip->address2);
            $cpestado_shipto = addslashes($personPlaceShip->state);
            $cpcuidad_shipto = addslashes($personPlaceShip->city);
            $cptelefono_shipto = addslashes($personPlaceShip->dayPhone);
            $cpzip_shipto = addslashes($personPlaceShip->postalCode);
            //$shipcountry = $personPlaceShip->country;			
            $mail = addslashes($personPlaceShip->email);

            //-----------------------------------//
            //Tabla SOLDTO tblsoldto

            $soldto1 = addslashes($personPlaceBill->name1);
            $soldto2 = '';
            $cpstphone_soldto = addslashes($personPlaceBill->dayPhone);
            $address1 = addslashes($personPlaceBill->address1);
            $address2 = addslashes($personPlaceBill->address2);
            $city = addslashes($personPlaceBill->city);
            $state = addslashes($personPlaceBill->state);
            $postalcode = addslashes($personPlaceBill->postalCode);
            $billcountry = addslashes($personPlaceBill->country);
            //$shipcountry = $personPlaceBill->country;		
            $shipcountry = 'US';        //Esta hardcoded
            $billmail = addslashes($personPlaceBill->email);

            $Ponumber = (string) $xmlpo->getXML()->hubOrder[$i]->poNumber;
            $order_date = $xmlpo->getXML()->hubOrder[$i]->orderDate;
            //////////////////////////////////////////////////////////////////////////////////////////////////QUITAMOS LOS CEROS DEL INICIO
            $Ponumber = trim(substr($Ponumber, 2, 12));
            $array_item = array();
            for ($j = 0; $j < $xmlpo->LineItemCount($i); $j++) {
                $lineItem = $xmlpo->getLineItem($i, $j);
                $cpitem = "" . $lineItem->vendorSKU . "";
                $cantidad = "" . $lineItem->qtyOrdered . "";
                $array_item[] = $cpitem;
                echo " Archivo: " . $archivoNombre . ", Ponumber : " . $Ponumber . ", Custnumber: " . $Custnumber . ", Item: " . $cpitem . ", Cantidad : " . $cantidad . ", Fecha de orden : " . $order_date . ", BillToName : " . $soldto1 . ", BillToEmail : " . $billmail . ", BillToPhone : " . $cpstphone_soldto . ", ShipToName : " . $shipto1 . ", ShipToEmail : " . $mail . ", ShipToPhone : " . $cptelefono_shipto . " \n";
            }
        }
        //Mover el archivo XML a la carpeta de RSSBus: PROCESADOS
        if ($xmlpo->moveXMLOSDFile($archivo, $archivoNombre)) {
//            echo $archivo . " Procesado y movido a los archivos auditados";
//            echo "<br>";
        } else {
            echo "Error moviendo el archivo: " . $archivo;
            echo "<br>";
        }
    }
}