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
$directorio = opendir("C:\Program Files\RSSBus\RSSBus Server\data\as2connector\profiles\COMMERCEHUB\Incoming"); //ruta de archivos XML
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
        $archivo = "C:\Program Files\RSSBus\RSSBus Server\data\as2connector\profiles\COMMERCEHUB\Incoming" . "\\" . $archivo;
        //Obtener los campos a llenar

        $xmlpo = new xmlHandler($archivo); //llamada a la clase que está en xmlHandler.php
        //---------------------------------------------------//
        //Tabla ORDEN tblorden
        //Nombre de compañía
        //$nombre_compania = $xmlpo->getXML()->partnerID;
        $nombre_compania = 'eblooms';

        for ($i = 0; $i < $xmlpo->MessageCount(); $i++) {       //recorrer las hubOrders
            $hubOrder = $xmlpo->getHubOrder($i);

            //Tabla ORDEN tblorden
            //giftMessage
            $cpmensaje = $xmlpo->getXML()->hubOrder[$i]->poHdrData->giftMessage;

            //orderDate
            $order_date = $xmlpo->getXML()->hubOrder[$i]->orderDate;

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

            //-----------------------------------//
            //Tabla DIRECTOR tbldirector
            //$id_director = 	;				//autogenerado
            //-----------------------------------//
            //DETALLES DE ORDEN tbldetalle_orden
            $Custnumber = $xmlpo->getXML()->hubOrder[$i]->custOrderNumber;


            $Ponumber = (string) $xmlpo->getXML()->hubOrder[$i]->poNumber;
            //Quitar los 2 ceros del inicio del poNumber
            $Ponumber = trim(substr($Ponumber, 2, 12));
            $iii = 0;

            $farm = '';
            $satdel = '';
            $cppais_envio = $shipcountry;
            $cpmoneda = 'USD';


            //$cporigen = 'EC';


            $cpUOM = 'BOX';
            $tracking = '';
            $estado_orden = 'Active';
            $descargada = 'not downloaded';
            $user = '';
            $eBing = 0;
            $coldroom = 'No';
            $status = 'New';
            $reenvio = 'No';
            $ups = $xmlpo->getXML()->hubOrder[$i]->shippingCode;
            $codigo = 0;

            $vendor = $xmlpo->getXML()->hubOrder[$i]->participatingParty->attributes()->name;

            //---RECORRER TODOS LOS lineItems de la orden /// Aqui se hacen las inserciones a la base de datos

            for ($j = 0; $j < $xmlpo->LineItemCount($i); $j++) {  //recorrer los lineItems, se pasa como parametro la hubOrder donde estoy
                $lineItem = $xmlpo->getLineItem($i, $j);   //obtener el lineItem, parametros indice de hubOrder e indice de lineItem 
                //---Datos tomados de cada linea de la orden---//

                $cpcantidad = $lineItem->qtyOrdered;
                $qtyorder = $lineItem->qtyOrdered;
                $cpitem = $lineItem->vendorSKU;

                $merchantSKU = $lineItem->merchantSKU;

                $merchantLineNumber = $lineItem->merchantLineNumber;

                //echo $merchantLineNumber;



                $shippingWeight = $lineItem->poLineData->unitShippingWeight;
                $weightUnit = $lineItem->poLineData->unitShippingWeight->attributes()->weightUnit;

                $delivery_traking = $lineItem->requestedArrivalDate;
                $poline = $lineItem->orderLineNumber;
                $unitprice = (string) $lineItem->unitCost;
                $unitprice = number_format($unitprice, 2, '.', '');    //dos digitos decimales
                //echo $merchantSKU;
                //echo "<br>";
                //echo "esto es shippingWeight: ".$shippingWeight;
                //echo "esto es weightUnit: ".$weightUnit;
                /////--------$ShipDT_traking------//////

                $fecha = date('l', strtotime($delivery_traking));

                //Obteniendo el origen para obtener el pais de origen (codigo_ciudad-pais)
                $sqlorg = "SELECT origen FROM tblproductos WHERE tblproductos.id_item = '$cpitem'";

                $query5 = mysqli_query($link, $sqlorg);
                $row = mysqli_fetch_array($query5);

                $cporigen = $row["origen"];

                $cporigen_city = explode("-", $cporigen);

                $cporigen = $cporigen_city[0];

                //Obteniendo el codigo del pais
                $sqlorg = "SELECT codpais_origen FROM tblciudad_origen WHERE tblciudad_origen.codciudad = '$cporigen'";
                //echo $query;
                $query5 = mysqli_query($link, $sqlorg);
                $row = mysqli_fetch_array($query5);

                $cporigen = $row["codpais_origen"];

                if ($cporigen == "EC") {
                    // Si es Martes, Jueves o Viernes le resto 3 dias
                    if (strcmp($fecha, "Tuesday") == 0 || strcmp($fecha, "Thursday") == 0 || strcmp($fecha, "Friday") == 0) {
                        $ShipDT_traking = strtotime('-3 day', strtotime($delivery_traking));
                        $ShipDT_traking = date('Y-m-j', $ShipDT_traking); //TBLDETALLE_ORDEN
                    } else {
                        //Si es otro dia de envio o sea Miercoles
                        $ShipDT_traking = strtotime('-4 day', strtotime($delivery_traking));
                        $ShipDT_traking = date('Y-m-j', $ShipDT_traking);  //TBLDETALLE_ORDEN
                    }
                } else {
                    $ShipDT_traking = strtotime('-5 day', strtotime($delivery_traking));
                    $ShipDT_traking = date('Y-m-j', $ShipDT_traking);  //TBLDETALLE_ORDEN
                }



                ///--COMPROBAR SI SE PUEDE INSERTAR--///
                //Verifico que la orden no este registrada en la bd 
                $sql = "SELECT
                        tbldetalle_orden.id_orden_detalle
                        FROM
                        tbldetalle_orden
                        WHERE
                        tbldetalle_orden.Custnumber = '$Custnumber' AND
                        tbldetalle_orden.Ponumber = '$Ponumber' AND
                        tbldetalle_orden.cpitem = '$cpitem'";
                //echo $query;
                $query = mysqli_query($link, $sql);
                $row = mysqli_fetch_array($query);
                //echo $row[0]."<br>";
                //verifico si hay datos 
                $ray = mysqli_num_rows($query);
                $orden = $i + 1;
                $linediff = $qtyorder - $ray;
//                echo "<br /><br /><br />" . $sql ." <br />". $linediff . " - " . $iii . "<br />"; ///////////////////////////////////////////////PARA VER LO QUE NO SE ESTA INSERTANDO
                if ($ray > 0) {
                    if ($iii >= $linediff) { //Si el item esta registrado uso sus detalles
                        echo "<font color='red'>La orden " . $orden . " con Ponumber: " . $Ponumber . " y Custnumber: " . $Custnumber . " ya fue insertada." . "</font><br>";
                        $archivo_alertado = 1;
                        //no pasarlo a la bd, solo enviar por mail o log local
                        continue;
                    }
                }
                $iii++;

                //Verifico que el item del producto este registrado en la bd 
                $query = "select * from tblproductos where id_item= '$cpitem'";
                //echo $query;
                $sql = mysqli_query($link, $query) or die(mysqli_error()); //selecciona los registros iguales aItem
                $ray = mysqli_num_rows($sql);
                if ($ray == 0) { //Si el item no esta registrado uso su detalles
                    $errormsj = 'El producto asociado al item ' . $cpitem . ' no está registrado, por favor regístrelo antes de continuar';

                    echo "<font color='red'>El producto asociado al item " . $cpitem . " no está registrado, por favor regístrelo antes de continuar.</font>";
                    echo "<br>";
                    goto insertar_tabla_error; //goto
                }

                //Verifico si falta algun dato en la orden

                if ($Ponumber == '' | $Custnumber == '' | $cpitem == '' | $shipcountry == '' | $cptelefono_shipto == '' | $delivery_traking == '' | $order_date == '') {
                    $orden = $i + 1;

                    if ($Ponumber == '')
                        $errormsj = 'A la orden ' . $orden . ' le faltan datos en el archivo, por favor revise el poNumber';
                    //echo $Custnumber;
                    if ($Custnumber == '')
                        $errormsj = 'A la orden ' . $orden . ' le faltan datos en el archivo, por favor revise el Custnumber';
                    //echo $cpitem;
                    if ($cpitem == '')
                        $errormsj = 'A la orden ' . $orden . ' le faltan datos en el archivo, por favor revise el item';
                    //echo $shipcountry;
                    if ($shipcountry == '')
                        $errormsj = 'A la orden ' . $orden . ' le faltan datos en el archivo, por favor revise el Shipcountry';
                    //echo $cptelefono_shipto;	
                    if ($cptelefono_shipto == '')
                        $errormsj = 'A la orden ' . $orden . ' le faltan datos en el archivo, por favor revise el Teléfono';

                    if ($delivery_traking == '') {
                        $delivery_traking = '1900-01-01';
                        $errormsj = 'A la orden ' . $orden . ' le faltan datos en el archivo, por favor revise el Delivery Tracking';
                    }

                    if ($order_date == '') {
                        $order_date = '1900-01-01';
                        $errormsj = 'A la orden ' . $orden . ' le faltan datos en el archivo, por favor revise el Order Date';
                    }

                    echo '<font color="red"> A la orden ' . $orden . ' le faltan datos en el archivo, por favor revise el poNumber, Custnumber, Item, País, Teléfono, Delivery Tracking, Order Date...</font>';
                    echo "<br>";

                    insertar_tabla_error:      //Etiqueta del goto

                    $tblorden_codigo++; /////////////////////////////////////////////////////////////////////////////PASAMOS AL SIGUIENTE ID 
                    $id_order = $tblorden_codigo;
                    //echo "este es el id order ".$id_order;
                    //Si no tiene alguno de estos datos agregar a la tabla de errores
                    //$cpcantidad se inserta y da la cantidad completa que luego debe ser usada para reproducir la orden n veces
                    //verifico el mensaje
                    if ($cpmensaje == '') {
                        $cpmensaje = "To-Blank Info   ::From- Blank Info   ::Blank .Info";
                    } else {
                        $cpmensaje = addslashes($cpmensaje);
                    }

                    //Inserto en la tabla de errores
                    $sql_error = "Insert INTO `tblerror`(`id_detalleorden`,`Custnumber`,`Ponumber`,`cpitem`,`cpcantidad`,`farm`,`satdel`,`cppais_envio`,`cpmoneda`,`cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`tracking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`reenvio`,`poline`,`unitprice`,`ups`,`codigo`,`vendor`,`merchantSKU`,`shippingWeight`,`weightUnit`,`merchantLineNumber`,`shipto1`,`shipto2`,`direccion`,`direccion2`,`cpestado_shipto`,`cpcuidad_shipto`,`cptelefono_shipto`,`cpzip_shipto`,`mail`,`shipcountry`,`soldto1`,`soldto2`,`cpstphone_soldto`,`address1`,`address2`,`city`,`state`,`postalcode`,`billcountry`,`billmail`,nombre_compania,cpmensaje,order_date,errormsj) VALUES ('$id_order','$Custnumber','$Ponumber','$cpitem','$cpcantidad','$farm','$satdel','$cppais_envio','$cpmoneda','$cporigen','$cpUOM','$delivery_traking','$ShipDT_traking','$tracking','$estado_orden','$descargada','$user','$eBing','$coldroom','$status','$reenvio','$poline','$unitprice','$ups','$codigo','$vendor','$merchantSKU','$shippingWeight','$weightUnit','$merchantLineNumber','$shipto1','$shipto2','$direccion','$direccion2','$cpestado_shipto','$cpcuidad_shipto','$cptelefono_shipto','$cpzip_shipto','$mail','$shipcountry','$soldto1','$soldto2','$cpstphone_soldto','$address1','$address2','$city','$state','$postalcode','$billcountry','$billmail','$nombre_compania','$cpmensaje','$order_date','$errormsj')";
                    mysqli_query($link, $sql_error)or die(mysqli_error());
//                    $insert_tblerror_values = $insert_tblerror_values . "('" . $id_order . "','" . $Custnumber . "','" . $Ponumber . "','" . $cpitem . "','" . $cpcantidad . "','" . $farm . "','" . $satdel . "','" . $cppais_envio . "','" . $cpmoneda . "','" . $cporigen . "','" . $cpUOM . "','" . $delivery_traking . "','" . $ShipDT_traking . "','" . $tracking . "','" . $estado_orden . "','" . $descargada . "','" . $user . "','" . $eBing . "','" . $coldroom . "','" . $status . "','" . $reenvio . "','" . $poline . "','" . $unitprice . "','" . $ups . "','" . $codigo . "','" . $vendor . "','" . $merchantSKU . "','" . $shippingWeight . "','" . $weightUnit . "','" . $merchantLineNumber . "','" . $shipto1 . "','" . $shipto2 . "','" . $direccion . "','" . $direccion2 . "','" . $cpestado_shipto . "','" . $cpcuidad_shipto . "','" . $cptelefono_shipto . "','" . $cpzip_shipto . "','" . $mail . "','" . $shipcountry . "','" . $soldto1 . "','" . $soldto2 . "','" . $cpstphone_soldto . "','" . $address1 . "','" . $address2 . "','" . $city . "','" . $state . "','" . $postalcode . "','" . $billcountry . "','" . $billmail . "','" . $nombre_compania . "','" . $cpmensaje . "','" . $order_date . "','" . $errormsj . "'),";
                    $insert_incoming_values = $insert_incoming_values . "('" . $archivoNombre . "','" . $Ponumber . "','" . $Custnumber . "','" . $cpitem . "','" . date('Y-m-d') . "','" . $delivery_traking . "','" . $billmail . "','" . $cpstphone_soldto . "','" . $soldto1 . "'),";
                    continue; //Como faltaban datos se pasa a la siguiente orden
                }

                ///---------INSERCION A LA BASE DE DATOS DEL lineItem------////

                for ($l = 0; $l < $linediff; $l++) {

                    //Insertar los datos de tblorden
                    if ($cpmensaje == '') {
                        $cpmensaje = "To-Blank Info   ::From- Blank Info   ::Blank .Info";
                    } else {
                        $cpmensaje = addslashes($cpmensaje);
                    }

                    if ($l == 0) {// es la primera orden
//                        $sql = "Insert INTO tblorden(nombre_compania,cpmensaje,order_date)VALUES ('$nombre_compania','$cpmensaje','$order_date')";
//                        mysqli_query($link, $sql, $conection) or die(mysqli_error()); //OK
                        $insert_tblorden_values = $insert_tblorden_values . "('" . $nombre_compania . "','" . $cpmensaje . "','" . $order_date . "'),";

                        $tblorden_codigo++; /////////////////////////////////////////////////////////////////////////////PASAMOS AL SIGUIENTE ID 
                        $id_order = $tblorden_codigo;
//                        echo "este es el id order " . $id_order;
//                        
//                        Insertar los datos de Shipto
//                        $sql1 = "Insert INTO `tblshipto`(`id_shipto`,`shipto1`,`shipto2`,`direccion`,`direccion2`,`cpestado_shipto`,`cpcuidad_shipto`,`cptelefono_shipto`,`cpzip_shipto`,`mail`,`shipcountry`)VALUES ('$id_order','$shipto1','$shipto2','$direccion','$direccion2','$cpestado_shipto','$cpcuidad_shipto','$cptelefono_shipto','$cpzip_shipto','$mail','$shipcountry')";
//                        mysqli_query($link, $sql1) or die(mysqli_error()); //OK
                        $insert_tblshipto_values = $insert_tblshipto_values . "('" . $id_order . "','" . $shipto1 . "','" . $shipto2 . "','" . $direccion . "','" . $direccion2 . "','" . $cpestado_shipto . "','" . $cpcuidad_shipto . "','" . $cptelefono_shipto . "','" . $cpzip_shipto . "','" . $mail . "','" . $shipcountry . "'),";
//                        
//                        Insertar los datos de BillTo (Soldto)
//                        $sql2 = "Insert INTO `tblsoldto`(`id_soldto`,`soldto1`,`soldto2`,`cpstphone_soldto`,`address1`,`address2`,`city`,`state`,`postalcode`,`billcountry`,`billmail`)VALUES ('$id_order','$soldto1','$soldto2','$cpstphone_soldto','$address1','$address2','$city','$state','$postalcode','$billcountry','$billmail')";
//                        mysqli_query($link, $sql2, $conection) or die(mysqli_error()); //ok
                        $insert_tblsoldto_values = $insert_tblsoldto_values . "('" . $id_order . "','" . $soldto1 . "','" . $soldto2 . "','" . $cpstphone_soldto . "','" . $address1 . "','" . $address2 . "','" . $city . "','" . $state . "','" . $postalcode . "','" . $billcountry . "','" . $billmail . "'),";
//                        
//                        Insertar los datos de tbldirector
//                        $sql5 = "Insert INTO `tbldirector`(`id_director`)VALUES ('$id_order')";
//                        mysqli_query($link, $sql5) or die(mysqli_error()); //ok
                        $insert_tbldirector_values = $insert_tbldirector_values . "('" . $id_order . "'),";
//                        
//                        Inserto los detalles del primer producto de la orden
//                        $cpcantidadsingle = 1;
//                        $sql3 = "Insert INTO `tbldetalle_orden`(`id_detalleorden`,`Custnumber`,`Ponumber`,`cpitem`,`cpcantidad`,`farm`,`satdel`,`cppais_envio`,`cpmoneda`,`cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`tracking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`reenvio`,`poline`,`unitprice`,`ups`,`codigo`,`vendor`,`merchantSKU`,`shippingWeight`,`weightUnit`,`merchantLineNumber`) VALUES ('$id_order','$Custnumber','$Ponumber','$cpitem','$cpcantidadsingle','$farm','$satdel','$cppais_envio','$cpmoneda','$cporigen','$cpUOM','$delivery_traking','$ShipDT_traking','$tracking','$estado_orden','$descargada','$user','$eBing','$coldroom','$status','$reenvio','$poline','$unitprice','$ups','$codigo','$vendor','$merchantSKU','$shippingWeight','$weightUnit','$merchantLineNumber')";
//                        mysqli_query($link, $sql3, $conection)or die(mysqli_error());
                        $cpcantidadsingle = 1;
                        $insert_ord_index1_values = $insert_ord_index1_values . "('" . $id_order . "','" . $Custnumber . "','" . $Ponumber . "','" . $cpitem . "','" . $cpcantidadsingle . "','" . $farm . "','" . $satdel . "','" . $cppais_envio . "','" . $cpmoneda . "','" . $cporigen . "','" . $cpUOM . "','" . $delivery_traking . "','" . $ShipDT_traking . "','" . $tracking . "','" . $estado_orden . "','" . $descargada . "','" . $user . "','" . $eBing . "','" . $coldroom . "','" . $status . "','" . $reenvio . "','" . $poline . "','" . $unitprice . "','" . $ups . "','" . $codigo . "','" . $vendor . "','" . $merchantSKU . "','" . $shippingWeight . "','" . $weightUnit . "','" . $merchantLineNumber . "'),";

                        $insert_incoming_values = $insert_incoming_values . "('" . $archivoNombre . "','" . $Ponumber . "','" . $Custnumber . "','" . $cpitem . "','" . date('Y-m-d') . "','" . $delivery_traking . "','" . $billmail . "','" . $cpstphone_soldto . "','" . $soldto1 . "'),";
                        echo "<font color='green'>Se ha insertado el PO: " . $Ponumber . " con Custnumber: " . $Custnumber . " y Item: " . $cpitem . " A la base de datos desde el archivo " . $archivoNombre . ".</font><br>";
                    } else {
                        //Inserto los detalles de los restantes productos de la orden
                        $cpcantidadsingle = 1;
//			$sql3 = "Insert INTO `tbldetalle_orden`(`id_detalleorden`,`Custnumber`,`Ponumber`,`cpitem`,`cpcantidad`,`farm`,`satdel`,`cppais_envio`,`cpmoneda`,`cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`tracking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`reenvio`,`poline`,`unitprice`,`ups`,`codigo`,`vendor`,`merchantSKU`,`shippingWeight`,`weightUnit`,`merchantLineNumber`)VALUES ('$id_order','$Custnumber','$Ponumber','$cpitem','$cpcantidadsingle','$farm','$satdel','$cppais_envio','$cpmoneda','$cporigen','$cpUOM','$delivery_traking','$ShipDT_traking','$tracking','$estado_orden','$descargada','$user','$eBing','$coldroom','$status','$reenvio','$poline','$unitprice','$ups','$codigo','$vendor','$merchantSKU','$shippingWeight','$weightUnit','$merchantLineNumber')";
//			mysqli_query($link,$sql3)or die (mysqli_error());
                        $insert_tbldetalleord_values = $insert_tbldetalleord_values . "('" . $id_order . "','" . $Custnumber . "','" . $Ponumber . "','" . $cpitem . "','" . $cpcantidadsingle . "','" . $farm . "','" . $satdel . "','" . $cppais_envio . "','" . $cpmoneda . "','" . $cporigen . "','" . $cpUOM . "','" . $delivery_traking . "','" . $ShipDT_traking . "','" . $tracking . "','" . $estado_orden . "','" . $descargada . "','" . $user . "','" . $eBing . "','" . $coldroom . "','" . $status . "','" . $reenvio . "','" . $poline . "','" . $unitprice . "','" . $ups . "','" . $codigo . "','" . $vendor . "','" . $merchantSKU . "','" . $shippingWeight . "','" . $weightUnit . "','" . $merchantLineNumber . "'),";
                        echo "<font color='green'>Se ha insertado el PO: " . $Ponumber . " con Custnumber: " . $Custnumber . " y Item: " . $cpitem . " A la base de datos desde el archivo " . $archivoNombre . ".</font><br>";
                    }
                }
            }
        }
        //Mover el archivo XML a la carpeta de RSSBus: PROCESADOS
        if ($archivo_alertado == 1) {
            if ($xmlpo->moveXMLAlertFile($archivo, $archivoNombre)) {
                echo $archivo . " Procesado y movido a los archivos irregulares";
                echo "<br>";
            } else {
                echo "Error moviendo el archivo: " . $archivo;
                echo "<br>";
            }
        } else {
            if ($xmlpo->moveXMLFile($archivo, $archivoNombre)) {
                echo $archivo . " Procesado y movido correctamente";
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
//
////////////////////////////////////////////////////////////////////////////////////////////////////////////INSERTAMOS A DB TODAS LAS ORDENES PROCESADAS
//$result_tblincoming_values = "Insert INTO `tblincoming`(`filename`,`ponumber`,`custnumber`,`item_id`,`fecha_ingreso`,`deliver_date`,`cust_mail`,`cust_phone`,`cust_name`) VALUES " . $insert_incoming_values;
////echo "<br /><br /><br />" . $insert_incoming_values;die;
//mysqli_query($link, $result_tblincoming_values)or die(' INCOMING Error: ' . mysqli_error($link));

$result_tblorden_values = "Insert INTO tblorden(nombre_compania,cpmensaje,order_date)VALUES " . $insert_tblorden_values;
echo "<br /><br /><br />" . $result_tblorden_values;die;
mysqli_query($link, $result_tblorden_values) or die(' TBLORDEN Error: ' . mysqli_error($link));

$result_tblshipto_values = "Insert INTO `tblshipto`(`id_shipto`,`shipto1`,`shipto2`,`direccion`,`direccion2`,`cpestado_shipto`,`cpcuidad_shipto`,`cptelefono_shipto`,`cpzip_shipto`,`mail`,`shipcountry`)VALUES " . $insert_tblshipto_values;
//echo "<br /><br /><br />" . $result_tblshipto_values;
mysqli_query($link, $result_tblshipto_values) or die(' TBLSHIPTO Error: ' . mysqli_error($link));

$result_tblsoldto_values = "Insert INTO `tblsoldto`(`id_soldto`,`soldto1`,`soldto2`,`cpstphone_soldto`,`address1`,`address2`,`city`,`state`,`postalcode`,`billcountry`,`billmail`)VALUES " . $insert_tblsoldto_values;
//echo "<br /><br /><br />" . $result_tblsoldto_values;
mysqli_query($link, $result_tblsoldto_values) or die(' TBLSOLDTO Error: ' . mysqli_error($link));

$result_tbldirector_values = "Insert INTO `tbldirector`(`id_director`)VALUES " . $insert_tbldirector_values;
//echo "<br /><br /><br />" . $result_tbldirector_values;
mysqli_query($link, $result_tbldirector_values) or die(' TBLDIRECTOR Error: ' . mysqli_error($link));

$result_ord_index1_values = "Insert INTO `tbldetalle_orden`(`id_detalleorden`,`Custnumber`,`Ponumber`,`cpitem`,`cpcantidad`,`farm`,`satdel`,`cppais_envio`,`cpmoneda`,`cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`tracking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`reenvio`,`poline`,`unitprice`,`ups`,`codigo`,`vendor`,`merchantSKU`,`shippingWeight`,`weightUnit`,`merchantLineNumber`) VALUES " . $insert_ord_index1_values;
//echo "<br /><br /><br />" . $result_ord_index1_values;
mysqli_query($link, $result_ord_index1_values) or die(' DETALLEORDINDEX1 Error: ' . mysqli_error($link));

$result_tbldetalleord_values = "Insert INTO `tbldetalle_orden`(`id_detalleorden`,`Custnumber`,`Ponumber`,`cpitem`,`cpcantidad`,`farm`,`satdel`,`cppais_envio`,`cpmoneda`,`cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`tracking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`reenvio`,`poline`,`unitprice`,`ups`,`codigo`,`vendor`,`merchantSKU`,`shippingWeight`,`weightUnit`,`merchantLineNumber`)VALUES " . $insert_tbldetalleord_values;
//echo "<br /><br /><br />" . $result_tbldetalleord_values;
mysqli_query($link, $result_tbldetalleord_values)or die(' DETALLEORD Error: ' . mysqli_error($link));
