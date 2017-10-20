<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");
require_once ('PHPExcel.php');

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


if (isset($_SESSION["sql2"])) {
    $sql = $_SESSION["sql2"];
}
echo "VALORES ".$_SESSION['cancelarordenes'];
if (isset($_SESSION['cancelarordenes'])) {
    $sql = $_SESSION["sql2"];
    $update_ebing = "UPDATE tbldetalle_orden 
                            SET estado_orden='Canceled', 
                            status='Not Shipped', 
                            unitprice= (tbldetalle_orden.unitprice*-1)
                            WHERE Ponumber IN (" . $_SESSION['cancelarordenes'] . ") ";
    echo $update_ebing;
    $result_update_ebing = mysqli_query($link, $update_ebing) or die("NO SE PUDO ACTUALIZAR ESTATUS CANCELADA");
} else {
    echo "<a onclick='javascript:window.close();' href='#'>Volver Atrás</a>";
    echo "<br>";
    echo "<br>";
    exit("Por favor realice una búsqueda. No se creará el archivo XML");
}

$query = mysqli_query($link, $sql);

if (mysqli_num_rows($query) != 0) {

    $partnerID = 'ebloomdirect';
    $partnerTrxDate = date("Ymd");
    $action = 'v_cancel';


    $writer = new XMLWriter();

    //especificar que se salve en outgoing
    $archivo = 'C:\Program Files\RSSBus\RSSBus Server\data\as2connector\profiles\COMMERCEHUB\Outgoing2\Confirm ' . date("Y-m-d_h_i_s") . '.confirm';


    $writer->openURI($archivo);

    $writer->startDocument('1.0', 'UTF-8');
    $writer->setIndent(true);
    //ConfirmMessageBatch
    $writer->startElement('ConfirmMessageBatch');
    $writer->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
    //$writer->writeAttribute('xsi:noNamespaceSchemaLocation', 'S:\VENDOR~1\XSDs\Costco\Costco_confirm.xsd');  
    //partnerID
    $writer->startElement('partnerID');
    $writer->writeAttribute('name', 'E-blooms Direct Inc');
    $writer->writeAttribute('roleType', 'vendor');
    $writer->text($partnerID);
    $writer->endElement();

    $i = 0;
    while ($row = mysqli_fetch_array($query)) {
        $partnerTrxID = $row['partnerTrxID'];
        $poNumber = '00' . $row['Ponumber'];
        $trxVendorSKU = $row['cpitem'];
        $packageDetailID = $row['packageDetailID'];
        $trackingNumber = $row['tracking'];
        $serviceLevel1 = $row['ups'];
        $trxMerchantSKU = $row['merchantSKU'];
        $shippingWeight = $row['shippingWeight'];
        $weightUnit = $row['weightUnit'];
        $merchantLineNumber = $row['merchantLineNumber'];
        $eBing = $row['eBing'];
        //hubConfirm   
        $writer->startElement('hubConfirm');
        //participatingParty
        $writer->startElement('participatingParty');
        $writer->writeAttribute('name', 'Costco');
        $writer->writeAttribute('participationCode', 'To:');
        $writer->writeAttribute('roleType', 'merchant');
        $writer->text('costco');
        $writer->endElement();
        //partnerTrxID
        $writer->startElement('partnerTrxID');
        $writer->text($partnerTrxID);
        $writer->endElement();
        //partnerTrxDate
        $writer->startElement('partnerTrxDate');
        $writer->text($partnerTrxDate);
        $writer->endElement();
        //poNumber
        $writer->startElement('poNumber');
        $writer->text($poNumber);
        $writer->endElement();
        $writer->startElement('trxData');
        $writer->startElement('vendorsInvoiceNumber');
        $writer->text($eBing);
        $writer->endElement();
        $writer->endElement();
        //hubAction
        $writer->startElement('hubAction');
        $writer->startElement('action');
        $writer->text('v_cancel');
        $writer->endElement();
        $writer->startElement('actionCode');
        $writer->text('out_of_stock');
        $writer->endElement();
        $writer->startElement('merchantLineNumber');
        $writer->text($merchantLineNumber);
        $writer->endElement();
        $writer->startElement('trxVendorSKU');
        $writer->text($trxVendorSKU);
        $writer->endElement();
        /* $writer->startElement('trxMerchantSKU');
          $writer->text($trxMerchantSKU);
          $writer->endElement(); */
        $writer->startElement('trxQty');
        $writer->text('1');
        $writer->endElement();
        $writer->startElement('packageDetailLink');
        $writer->writeAttribute('packageDetailID', $packageDetailID);
        $writer->endElement();
        $writer->endElement(); //hubAction
        //packageDetail
        $writer->startElement('packageDetail');
        $writer->writeAttribute('packageDetailID', $packageDetailID);
        $writer->startElement('shipDate');
        $writer->text('20150518');
        $writer->endElement();
        $writer->startElement('serviceLevel1');    //el shippingcode que es UPS
        $writer->text($serviceLevel1);
        $writer->endElement();
        $writer->startElement('trackingNumber');
        $writer->text($trackingNumber);
        $writer->endElement();
        $writer->startElement('shippingWeight');
        $writer->writeAttribute('weightUnit', $weightUnit);
        $writer->text($shippingWeight);
        $writer->endElement();
        $writer->endElement(); //packageDetail
        $writer->endElement();  //hubConfirm 
        $i++; //contador para el messageCount
    }
    $messageCount = $i;
    $writer->startElement('messageCount');
    $writer->text($messageCount);
    $writer->endElement();

    $writer->endElement();  //ConfirmMessageBatch
    $writer->endDocument();
    $writer->flush();

    // LOG //
    //Usuario, ip, fecha y hora, operación


    function getRealIP() {

        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            return $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
            return $_SERVER["HTTP_X_FORWARDED"];
        } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_FORWARDED"])) {
            return $_SERVER["HTTP_FORWARDED"];
        } else {
            return $_SERVER["REMOTE_ADDR"];
        }
    }

    $ip = getRealIP();
    $fechaAccion = date("Y-m-d H:i:s");

    //Escribir archivo de log
    $file = fopen("..\logs\crearXML.log", "a");
    fwrite($file, $fechaAccion . "  " . $ip . "  " . $user . "  Archivo: " . $archivo . "  Creado correctamente" . PHP_EOL);
    fclose($file);

    echo "<a onclick='javascript:window.close();' href='#'>Volver Atrás</a>";
    echo "<br>";
    echo "<br>";
    echo "Archivo XML creado y listo para su envío";
} else {
    echo "<a onclick='javascript:window.close();' href='#'>Volver Atrás</a>";
    echo "<br>";
    echo "<br>";
    exit("No hubo resultados para su búsqueda, no se creará el archivo XML");
}
?>