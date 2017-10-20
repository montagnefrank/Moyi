<?php

/////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require (__DIR__ . "/../scripts/conn.php");
require ('filehandler.php');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////CONEXION A DB
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

//Procesar los archivos XML de la carpeta RSSBUS: Incoming
$directorio = opendir("C:\Program Files\RSSBus\RSSBus Server\data\as2connector\profiles\COMMERCEHUB\Incoming"); //ruta de archivos XML

while ($archivo = readdir($directorio)) { //obtenemos el primer archivo y luego otro sucesivamente
    if (!is_dir($archivo)) { //verificamos si es o no un directorio
        $archivoNombre = $archivo;
        $archivo = "C:\Program Files\RSSBus\RSSBus Server\data\as2connector\profiles\COMMERCEHUB\Incoming" . "\\" . $archivo;
        //Obtener los campos a llenar
        $xmlpo = new xmlHandler($archivo); //llamada a la clase que está en xmlHandler.php
        for ($i = 0; $i < $xmlpo->MessageCount(); $i++) {       //recorrer las hubOrders
            $hubOrder = $xmlpo->getHubOrder($i);
            $cpmensaje = $xmlpo->getXML()->hubOrder[$i]->poHdrData->giftMessage;
            $order_date = $xmlpo->getXML()->hubOrder[$i]->orderDate;
            $order_id = $xmlpo->getXML()->hubOrder[$i]->orderId;

            //-----------------------------------//
            //Tablas SHIPTO y SOLDTO
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
            $billmail = addslashes($personPlaceBill->email);

            //-----------------------------------//
            //Tabla DIRECTOR tbldirector
            //$id_director = 	;				//autogenerado
            //-----------------------------------//
            //DETALLES DE ORDEN tbldetalle_orden
            $Custnumber = $xmlpo->getXML()->hubOrder[$i]->custOrderNumber;


            $Ponumber = (string) $xmlpo->getXML()->hubOrder[$i]->poNumber;
            //Quitar los 2 ceros del inicio del poNumber
            $Ponumber = trim(substr($Ponumber, 2, 12));

            $ups = $xmlpo->getXML()->hubOrder[$i]->shippingCode;
            $vendor = $xmlpo->getXML()->hubOrder[$i]->participatingParty->attributes()->name;

            //---RECORRER TODOS LOS lineItems de la orden 
            for ($j = 0; $j < $xmlpo->LineItemCount($i); $j++) {  //recorrer los lineItems, se pasa como parametro la hubOrder donde estoy
                $lineItem = $xmlpo->getLineItem($i, $j);   //obtener el lineItem, parametros indice de hubOrder e indice de lineItem 
                //---Datos tomados de cada linea de la orden---//
                $qtyorder = $lineItem->qtyOrdered;
                $cpitem = $lineItem->vendorSKU;
                $descr = $lineItem->description;
                $merchantSKU = $lineItem->merchantSKU;
                $merchantLineNumber = $lineItem->merchantLineNumber;
                $shippingWeight = $lineItem->poLineData->unitShippingWeight;
                $weightUnit = $lineItem->poLineData->unitShippingWeight->attributes()->weightUnit;
                $delivery_traking = $lineItem->requestedArrivalDate;
                $poline = $lineItem->orderLineNumber;
                $unitprice = (string) $lineItem->unitCost;
                $unitprice = number_format($unitprice, 2, '.', '');    //dos digitos decimales
                ////////////////////DAMOS FORMATO A LA FECHA
                $fecha = date('l', strtotime($delivery_traking));

                ///---------INSERCION A LA BASE DE DATOS -----////
                for ($l = 0; $l < $qtyorder; $l++) {

                    //Insertar los datos de tblorden
                    if ($cpmensaje == '') {
                        $cpmensaje = "To-Blank Info   ::From- Blank Info   ::Blank .Info";
                    } else {
                        $cpmensaje = addslashes($cpmensaje);
                    }
                    $cpmensaje = str_replace('"', "", $cpmensaje);
                    $shipto1 = str_replace('"', "", $shipto1);
                    $direccion = str_replace('"', "", $direccion);
                    $direccion2 = str_replace('"', "", $direccion2);
                    $soldto1 = str_replace('"', "", $soldto1);
                    $address1 = str_replace('"', "", $address1);
                    $descr = str_replace('"', "", $descr);
                    
                    $cpmensaje = str_replace("'", "", $cpmensaje);
                    $shipto1 = str_replace("'", "", $shipto1);
                    $direccion = str_replace("'", "", $direccion);
                    $direccion2 = str_replace("'", "", $direccion2);
                    $soldto1 = str_replace("'", "", $soldto1);
                    $address1 = str_replace("'", "", $address1);
                    $descr = str_replace("'", "", $descr);
                    
                    $cpmensaje = str_replace(",", "", $cpmensaje);
                    $shipto1 = str_replace(",", "", $shipto1);
                    $direccion = str_replace(",", "", $direccion);
                    $direccion2 = str_replace(",", "", $direccion2);
                    $soldto1 = str_replace(",", "", $soldto1);
                    $address1 = str_replace(",", "", $address1); 
                    $descr = str_replace(",", "", $descr);
                    
                    if ($l == 0) {
                        $insert_xmlcostco_values = $insert_xmlcostco_values . "('" . $archivoNombre . "','" . date('Y-m-d h:i:sa') . "','" . $order_id . "','" . $Ponumber . "','" . $Custnumber . "','" . $order_date . "','" . $shipto1 . "','" . $direccion . "','" . $direccion2 . "','" . $cpcuidad_shipto . "','" . $cpestado_shipto . "','" . $cptelefono_shipto . "','" . $mail . "','" . $cpzip_shipto . "','" . $soldto1 . "','" . $address1 . "','" . $city . "','" . $state . "','" . $billcountry . "','" . $cpstphone_soldto . "','" . $billmail . "','" . $postalcode . "','" . $ups . "','" . $vendor . "','" . $poline . "','" . $cpitem . "','" . $unitprice . "','" . $delivery_traking . "','" . $descr . "','" . $cpmensaje . "','" . $qtyorder . "','true','" . $shippingWeight . "','" . $weightUnit . "','" . $merchantSKU . "','0','Llegada de Costco'),";
                    } else {
                        $insert_xmlcostco_values = $insert_xmlcostco_values . "('" . $archivoNombre . "','" . date('Y-m-d h:i:sa') . "','" . $order_id . "','" . $Ponumber . "','" . $Custnumber . "','" . $order_date . "','" . $shipto1 . "','" . $direccion . "','" . $direccion2 . "','" . $cpcuidad_shipto . "','" . $cpestado_shipto . "','" . $cptelefono_shipto . "','" . $mail . "','" . $cpzip_shipto . "','" . $soldto1 . "','" . $address1 . "','" . $city . "','" . $state . "','" . $billcountry . "','" . $cpstphone_soldto . "','" . $billmail . "','" . $postalcode . "','" . $ups . "','" . $vendor . "','" . $poline . "','" . $cpitem . "','" . $unitprice . "','" . $delivery_traking . "','" . $descr . "','" . $cpmensaje . "','" . $qtyorder . "','false','" . $shippingWeight . "','" . $weightUnit . "','" . $merchantSKU . "','0','Llegada de Costco'),";
                    }
                }
            }
        }
        /////////////////////////////Mover el archivo XML a la carpeta de RSSBus: PROCESADOS /////////////////****************** SE DESHABILITO POR PROBLEMAS DE LECTURA
//        if ($xmlpo->moveXMLFile($archivo, $archivoNombre)) {
//            echo $archivo . " Procesado y movido correctamente";
//            echo "<br>";
//        } else {
//            echo "Error moviendo el archivo: " . $archivo;
//            echo "<br>";
//        }
        $namestomove = $namestomove . "," . $archivoNombre;
    }
}

$namestomove = explode(",", $namestomove);

foreach ($namestomove as $element) {
    $fullpath = "C:\Program Files\RSSBus\RSSBus Server\data\as2connector\profiles\COMMERCEHUB\Incoming" . "\\" . $element;
    if ($xmlpo->moveXMLFile($fullpath, $element)) {
        echo $element . " Procesado y movido correctamente";
        echo "<br>";
    } else {
        echo "Error moviendo el archivo: " . $element;
        echo "<br>";
    }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////REMOVEMOS LA ULTIMA COMA PARA NO GENERAR ERROR DE SINTAXIS
$insert_xmlcostco_values = substr(trim($insert_xmlcostco_values), 0, -1);

////////////////////////////////////////////////////////////////////////////////////////////////////////////INSERTAMOS A DB TODAS LAS ORDENES
if (!empty($insert_xmlcostco_values)) {
    $insert_xmlcostco = "Insert INTO `tblcostco`(filename,insert_date,order_id,ponumber,custnumber,orderdate,
                            shipto,shipto_address,shipto_address2,shipto_city,shipto_state,shipto_phone,shipto_mail,shipto_zip,
                            soldto,soldto_address,soldto_city,soldto_state,soldto_country,soldto_phone,soldto_mail,soldto_zip,
                            shippingcode,vendor,linenumber,item,uni_cost,RAD,item_desc,message,qty_ordered,insertid,shipping_weight,
                            shipping_weight_unit,merchantsku,status,status_desc) VALUES " . $insert_xmlcostco_values;
    $result_xmlcostco = mysqli_query($link, $insert_xmlcostco)or die(' INSERT Error: ' . mysqli_error($link));
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////********************************************************************************************//////////////////////////////////
///////////////////////////**POSTERIOR A LA INSERCION EN LA BASE DE DATOS, PROCESAMOS LOS RECIEN INGRESADOS, STATUS 0**//////////////////////////////////
///////////////////////////********************************************************************************************//////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$ingresados = array();
$select_ingresados = "SELECT * FROM tblcostco WHERE status = '0'";
$result_ingresados = mysqli_query($link, $select_ingresados);
$whereidin_status1 = '';
$whereidin_status2 = '';
$whereidin_status3 = '';
$whereidin_status4 = '';
$whereidin_status5 = '';

///////////////////////////////////////////////////////////////////////////////////////////////////////////LLAMAMOS AL ULTIMO CONSECUTIVO DE LA TABLA DE ORDENES
$select_tblorden_codigo = "SELECT id_orden FROM tblorden ORDER BY id_orden DESC LIMIT 1";
$result_tblorden_codigo = mysqli_query($link, $select_tblorden_codigo);
$row_tblorden_codigo = mysqli_fetch_array($result_tblorden_codigo, MYSQLI_BOTH);
$tblorden_codigo = $row_tblorden_codigo[0];

while ($row_ingresados = mysqli_fetch_array($result_ingresados)) {
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////VALIDAMOS QUE EL ARCHIVO NO HAYA SIDO SUBIDO ANTERIORMENTE
    $select_filename_validate = "SELECT * FROM tblcostco WHERE filename ='" . $row_ingresados['filename'] . "' AND status != '0' ";
    $result_filename_validate = mysqli_query($link, $select_filename_validate);
    $count_filename_validate = mysqli_num_rows($result_filename_validate);
    if ($count_filename_validate > 0) { //////////////SI EL ARCHIVO YA HA SIDO SUBIDO, CAMBIAMOS A STATUS 1
        $whereidin_status1 .= "'" . $row_ingresados['id'] . "',";
        continue; /////////PARA QUE YA NO SIGA HACEINDO VALIDACIONES
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////VALIDAMOS QUE NO EXISTAN CAMPOS VACIOS EN LA ORDEN
    if ($row_ingresados['order_id'] == '' || $row_ingresados['ponumber'] == '' ||
            $row_ingresados['custnumber'] == '' || $row_ingresados['orderdate'] == '' ||
            $row_ingresados['shipto'] == '' || $row_ingresados['shipto_address'] == '' ||
            $row_ingresados['shipto_city'] == '' || $row_ingresados['shipto_state'] == '' ||
            $row_ingresados['shipto_phone'] == '' || $row_ingresados['shipping_weight_unit'] == '' ||
            $row_ingresados['shipto_zip'] == '' || $row_ingresados['soldto'] == '' ||
            $row_ingresados['soldto_address'] == '' || $row_ingresados['soldto_city'] == '' ||
            $row_ingresados['soldto_state'] == '' || $row_ingresados['soldto_country'] == '' ||
            $row_ingresados['soldto_phone'] == '' || $row_ingresados['shipping_weight'] == '' ||
            $row_ingresados['soldto_zip'] == '' || $row_ingresados['shippingcode'] == '' ||
            $row_ingresados['vendor'] == '' || $row_ingresados['linenumber'] == '' ||
            $row_ingresados['item'] == '' || $row_ingresados['uni_cost'] == '' ||
            $row_ingresados['RAD'] == '' || $row_ingresados['item_desc'] == '' ||
            $row_ingresados['qty_ordered'] == '' || $row_ingresados['merchantsku'] == '') {
        $whereidin_status2 .= "'" . $row_ingresados['id'] . "',";
        continue; ////////////PARA QUE YA NO SIGA HACEINDO VALIDACIONES
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////VALIDAMOS QUE EL PO NO SE HAYA SUBIDO ANTES
    $select_ponumber_validate = "SELECT * FROM tblcostco WHERE ponumber ='" . $row_ingresados['ponumber'] . "' AND status != '0' ";
    $result_ponumber_validate = mysqli_query($link, $select_ponumber_validate);
    $count_ponumber_validate = mysqli_num_rows($result_ponumber_validate);
    if ($count_ponumber_validate > 0) {
        $whereidin_status3 .= "'" . $row_ingresados['id'] . "',";
        continue; //////PARA QUE YA NO SIGA HACEINDO VALIDACIONES
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////VALIDAMOS QUE EL PO NO EXISTA EN DETALLE ORDEN
    $select_detord_validate = "SELECT ponumber FROM tbldetalle_orden WHERE ponumber ='" . $row_ingresados['ponumber'] . "' AND custnumber = '" . $row_ingresados['custnumber'] . "' AND cpitem = '" . $row_ingresados['item'] . "'";
    $result_detord_validate = mysqli_query($link, $select_detord_validate);
    $count_detord_validate = mysqli_num_rows($result_detord_validate);
    if ($count_detord_validate > 0) {
        $whereidin_status4 .= "'" . $row_ingresados['id'] . "',";
        continue; ////////PARA QUE YA NO SIGA HACEINDO VALIDACIONES
    }
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////EL RESTO LAS ENVIAMOS A DETALLE ORDEN
    $whereidin_status5 .= "'" . $row_ingresados['id'] . "',";
    $nombre_compania = "Burtontech";
    $fecha = date('l', strtotime($row_ingresados['RAD']));
    if (strcmp($fecha, "Tuesday") == 0 || strcmp($fecha, "Thursday") == 0 || strcmp($fecha, "Friday") == 0) {
        $ShipDT_traking = strtotime('-3 day', strtotime($row_ingresados['RAD']));
        $ShipDT_traking = date('Y-m-j', $ShipDT_traking); //TBLDETALLE_ORDEN
    } else {
        //Si es otro dia de envio o sea Miercoles
        $ShipDT_traking = strtotime('-4 day', strtotime($row_ingresados['RAD']));
        $ShipDT_traking = date('Y-m-j', $ShipDT_traking);  //TBLDETALLE_ORDEN
    }
    if ($row_ingresados['insertid'] == 'true') {
        $tblorden_codigo++;
        $id_order = $tblorden_codigo;
        $insert_tblorden_values = $insert_tblorden_values . "('" . $id_order . "','" . $nombre_compania . "','" . $row_ingresados['message'] . "','" . $row_ingresados['orderdate'] . "'),";
        $insert_tblshipto_values = $insert_tblshipto_values . "('" . $id_order . "','" . $row_ingresados['shipto'] . "','" . $row_ingresados['shipto'] . "','" . $row_ingresados['shipto_address'] . "','" . $row_ingresados['shipto_adrress2'] . "','" . $row_ingresados['shipto_state'] . "','" . $row_ingresados['shipto_city'] . "','" . $row_ingresados['shipto_phone'] . "','" . $row_ingresados['shipto_zip'] . "','" . $row_ingresados['shipto_mail'] . "','US'),";
        $insert_tblsoldto_values = $insert_tblsoldto_values . "('" . $id_order . "','" . $row_ingresados['soldto'] . "','" . $row_ingresados['soldto'] . "','" . $row_ingresados['soldto_phone'] . "','" . $row_ingresados['soldto_address'] . "','" . $row_ingresados['soldto_address'] . "','" . $row_ingresados['soldto_city'] . "','" . $row_ingresados['soldto_state'] . "','" . $row_ingresados['soldto_zip'] . "','" . $row_ingresados['soldto_country'] . "','" . $row_ingresados['soldto_mail'] . "'),";
        $insert_tbldirector_values = $insert_tbldirector_values . "('" . $id_order . "'),";
        $cpcantidadsingle = 1;
        $insert_ord_index1_values = $insert_ord_index1_values . "('" . $id_order . "','" . $row_ingresados['custnumber'] . "','" . $row_ingresados['ponumber'] . "','" . $row_ingresados['item'] . "','" . $cpcantidadsingle . "','" . $farm . "','" . $satdel . "','US','USD','EC','BOX','" . $row_ingresados['RAD'] . "','" . $ShipDT_traking . "','','Active','not downloaded',' ','0','No','New','No','" . $row_ingresados['linenumber'] . "','" . $row_ingresados['uni_cost'] . "','" . $row_ingresados['shippingcode'] . "','0','" . $row_ingresados['vendor'] . "','" . $row_ingresados['merchantsku'] . "','" . $row_ingresados['shipping_weight'] . "','" . $row_ingresados['shipping_weight_unit'] . "','0'),";
    } else {
        $insert_ord_index1_values = $insert_ord_index1_values . "('" . $id_order . "','" . $row_ingresados['custnumber'] . "','" . $row_ingresados['ponumber'] . "','" . $row_ingresados['item'] . "','" . $cpcantidadsingle . "','" . $farm . "','" . $satdel . "','US','USD','EC','BOX','" . $row_ingresados['RAD'] . "','" . $ShipDT_traking . "','','Active','not downloaded',' ','0','No','New','No','" . $row_ingresados['linenumber'] . "','" . $row_ingresados['uni_cost'] . "','" . $row_ingresados['shippingcode'] . "','0','" . $row_ingresados['vendor'] . "','" . $row_ingresados['merchantsku'] . "','" . $row_ingresados['shipping_weight'] . "','" . $row_ingresados['shipping_weight_unit'] . "','0'),";
    }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////REMOVEMOS LA ULTIMA COMA PARA NO GENERAR ERROR DE SINTAXIS
$whereidin_status1 = substr(trim($whereidin_status1), 0, -1);
$whereidin_status2 = substr(trim($whereidin_status2), 0, -1);
$whereidin_status3 = substr(trim($whereidin_status3), 0, -1);
$whereidin_status4 = substr(trim($whereidin_status4), 0, -1);
$whereidin_status5 = substr(trim($whereidin_status5), 0, -1);


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////UPDATE STATUS 1
if (!empty($whereidin_status1)) {
    $update_duplicado_alerta = "UPDATE tblcostco SET status ='1',status_desc='El archivo ha sido cargado anteriormente' WHERE id IN (" . $whereidin_status1 . ")";
    $result_update_duplicado_alerta = mysqli_query($link, $update_duplicado_alerta);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////UPDATE STATUS 2
if (!empty($whereidin_status2)) {
    $update_campovacio = "UPDATE tblcostco SET status ='2',status_desc='La orden contiene campos vacios' WHERE id IN (" . $whereidin_status2 . ")";
    $result_campovacio = mysqli_query($link, $update_campovacio);
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////INSERTAMOS EN LA TABLA DE ERROR
    $select_tablaerror = "SELECT * FROM tblcostco WHERE id IN (" . $whereidin_status2 . ")";
    $result_tablaerror = mysqli_query($link, $select_tablaerror);
    while ($row_tablaerror = mysqli_fetch_array($result_tablaerror)) {
        $fecha = date('l', strtotime($row_tablaerror['RAD']));
        if (strcmp($fecha, "Tuesday") == 0 || strcmp($fecha, "Thursday") == 0 || strcmp($fecha, "Friday") == 0) {
            $ShipDT_traking = strtotime('-3 day', strtotime($row_tablaerror['RAD']));
            $ShipDT_traking = date('Y-m-j', $ShipDT_traking); //TBLDETALLE_ORDEN
        } else {
            //Si es otro dia de envio o sea Miercoles
            $ShipDT_traking = strtotime('-4 day', strtotime($row_tablaerror['RAD']));
            $ShipDT_traking = date('Y-m-j', $ShipDT_traking);  //TBLDETALLE_ORDEN
        }
        $tblerror_insert_values = $tblerror_insert_values . "('" . $tblorden_codigo . "','" . $row_tablaerror['custnumber'] .
                "','" . $row_tablaerror['ponumber'] . "','" . $row_tablaerror['item'] . "','" . $row_tablaerror['qty_ordered'] .
                "','" . $farmquenoexiste . "','" . $satdelquenoexiste . "','US','USD','EC','BOX','1969-12-27','" . $ShipDT_traking . "','" . $trackingquenoexiste . "','Active','not downloaded','" . $userquenoexiste .
                "','0','No','New','No','" . $row_tablaerror['linenumber'] . "','" . $row_tablaerror['uni_cost'] . "','" . $row_tablaerror['shippingcode'] .
                "','0','" . $row_tablaerror['vendor'] . "','" . $row_tablaerror['merchantsku'] . "','" . $row_tablaerror['shipping_weight'] .
                "','" . $row_tablaerror['shipping_weight_unit'] . "','1','" . $row_tablaerror['shipto'] . "','" . $noexiste . "','" . $row_tablaerror['shipto_address'] .
                "','" . $row_tablaerror['shipto_address2'] . "','" . $row_tablaerror['shipto_state'] . "','" . $row_tablaerror['shipto_city'] .
                "','" . $row_tablaerror['shipto_phone'] . "','" . $row_tablaerror['shipto_zip'] . "','" . $row_tablaerror['shipto_mail'] .
                "','US','" . $row_tablaerror['soldto'] . "','" . $noexiste . "','" . $row_tablaerror['soldto_phone'] .
                "','" . $row_tablaerror['soldto_address'] . "','" . $row_tablaerror['soldto_address'] . "','" . $row_tablaerror['soldto_city'] .
                "','" . $row_tablaerror['soldto_state'] . "','" . $row_tablaerror['soldto_zip'] . "','" . $row_tablaerror['soldto_country'] .
                "','" . $row_tablaerror['soldto_mail'] . "','Burtontech','" . $row_tablaerror['message'] . "','" . $row_tablaerror['orderdate'] .
                "','Error campos vacios'),";
    }
    ////////////////////REMOVEMOS LA ULTIMA COMA PARA NO GENERAR ERROR DE SINTAXIS
    $tblerror_insert_values = substr(trim($tblerror_insert_values), 0, -1);
    $tblerror_insert = "Insert INTO `tblerror`(`id_detalleorden`,`Custnumber`,`Ponumber`,`cpitem`,`cpcantidad`,`farm`,`satdel`,`cppais_envio`,`cpmoneda`,
                `cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`tracking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`reenvio`,
                `poline`,`unitprice`,`ups`,`codigo`,`vendor`,`merchantSKU`,`shippingWeight`,`weightUnit`,`merchantLineNumber`,`shipto1`,`shipto2`,`direccion`,
                `direccion2`,`cpestado_shipto`,`cpcuidad_shipto`,`cptelefono_shipto`,`cpzip_shipto`,`mail`,`shipcountry`,`soldto1`,`soldto2`,`cpstphone_soldto`,
                `address1`,`address2`,`city`,`state`,`postalcode`,`billcountry`,`billmail`,nombre_compania,cpmensaje,order_date,errormsj) 
                VALUES " . $tblerror_insert_values;
    $result_tblerror_insert = mysqli_query($link, $tblerror_insert)or die(' INSERT TBLERROR Error: ' . mysqli_error($link));
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////UPDATE STATUS 3
if (!empty($whereidin_status3)) {
    $update_posubido = "UPDATE tblcostco SET status ='3',status_desc='Este PONUMBER ya ha sido cargado anteriormente ' WHERE id IN (" . $whereidin_status3 . ")";
    $result_posubido = mysqli_query($link, $update_posubido);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////UPDATE STATUS 4
if (!empty($whereidin_status4)) {
    $update_poendetord = "UPDATE tblcostco SET status ='4',status_desc='Este PONUMBER ya existe en la tabla de ordenes ' WHERE id IN (" . $whereidin_status4 . ")";
    $result_poendetord = mysqli_query($link, $update_poendetord);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////UPDATE STATUS 5 Y ENVIAMOS AL RESTO DE LAS TABLAS
if (!empty($whereidin_status5)) {
    $update_sendtodetord = "UPDATE tblcostco SET status ='5',status_desc='Orden cargada correctamente' WHERE id IN (" . $whereidin_status5 . ")";
    $result_sendtodetord = mysqli_query($link, $update_sendtodetord);
}

/////////////////////////////////////////////////////////////////////////////////////////////////AQUI VAN TODOS LOS INSERT A LAS TABLAS DE LAS ORDENES VALIDAS A INGRESAR
//////////////////////////////////////////////////////////////////////////////////////////////////////////REMOVEMOS LA ULTIMA COMA PARA NO GENERAR ERROR DE SINTAXIS
$insert_tblorden_values = substr(trim($insert_tblorden_values), 0, -1);
$insert_tblshipto_values = substr(trim($insert_tblshipto_values), 0, -1);
$insert_tblsoldto_values = substr(trim($insert_tblsoldto_values), 0, -1);
$insert_tbldirector_values = substr(trim($insert_tbldirector_values), 0, -1);
$insert_ord_index1_values = substr(trim($insert_ord_index1_values), 0, -1);
$insert_tbldetalleord_values = substr(trim($insert_tbldetalleord_values), 0, -1);

if (!empty($insert_tblorden_values) && !empty($insert_tblshipto_values) && !empty($insert_tblsoldto_values) && !empty($insert_tbldirector_values) && !empty($insert_ord_index1_values)) {
    $result_tblorden_values = "Insert INTO tblorden(id_orden,nombre_compania,cpmensaje,order_date)VALUES " . $insert_tblorden_values;
//    echo "<br /><br /><br />" . $insert_tblorden_values;
    mysqli_query($link, $result_tblorden_values) or die(' TBLORDEN Error: ' . mysqli_error($link));

    $result_tblshipto_values = "Insert INTO `tblshipto`(`id_shipto`,`shipto1`,`shipto2`,`direccion`,`direccion2`,`cpestado_shipto`,`cpcuidad_shipto`,`cptelefono_shipto`,`cpzip_shipto`,`mail`,`shipcountry`)VALUES " . $insert_tblshipto_values;
//    echo "<br /><br /><br />" . $insert_tblshipto_values;
    mysqli_query($link, $result_tblshipto_values) or die(' TBLSHIPTO Error: ' . mysqli_error($link));

    $result_tblsoldto_values = "Insert INTO `tblsoldto`(`id_soldto`,`soldto1`,`soldto2`,`cpstphone_soldto`,`address1`,`address2`,`city`,`state`,`postalcode`,`billcountry`,`billmail`)VALUES " . $insert_tblsoldto_values;
//    echo "<br /><br /><br />" . $insert_tblsoldto_values;
    mysqli_query($link, $result_tblsoldto_values) or die(' TBLSOLDTO Error: ' . mysqli_error($link));

    $result_tbldirector_values = "Insert INTO `tbldirector`(`id_director`)VALUES " . $insert_tbldirector_values;
//    echo "<br /><br /><br />" . $insert_tbldirector_values;
    mysqli_query($link, $result_tbldirector_values) or die(' TBLDIRECTOR Error: ' . mysqli_error($link));

    $result_ord_index1_values = "Insert INTO `tbldetalle_orden`(`id_detalleorden`,`Custnumber`,`Ponumber`,`cpitem`,`cpcantidad`,`farm`,`satdel`,`cppais_envio`,`cpmoneda`,`cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`tracking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`reenvio`,`poline`,`unitprice`,`ups`,`codigo`,`vendor`,`merchantSKU`,`shippingWeight`,`weightUnit`,`merchantLineNumber`) VALUES " . $insert_ord_index1_values;
//    echo "<br /><br /><br />" . $insert_ord_index1_values;
    mysqli_query($link, $result_ord_index1_values) or die(' DETALLEORDINDEX1 Error: ' . mysqli_error($link));
}
if (!empty($insert_tbldetalleord_values)) {
    $result_tbldetalleord_values = "Insert INTO `tbldetalle_orden`(`id_detalleorden`,`Custnumber`,`Ponumber`,`cpitem`,`cpcantidad`,`farm`,`satdel`,`cppais_envio`,`cpmoneda`,`cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`tracking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`reenvio`,`poline`,`unitprice`,`ups`,`codigo`,`vendor`,`merchantSKU`,`shippingWeight`,`weightUnit`,`merchantLineNumber`)VALUES " . $insert_tbldetalleord_values;
//    echo "<br /><br /><br />" . $insert_tbldetalleord_values;
    mysqli_query($link, $result_tbldetalleord_values)or die(' DETALLEORD Error: ' . mysqli_error($link));
}
echo "FIN DEL SCRIPT";
