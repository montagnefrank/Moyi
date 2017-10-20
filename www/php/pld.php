<?php

session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("calculosUPS.php");
$conection = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysqli_error()); 

//Numero del libro y pagina
$BookNum="";
$PageNum="";
//cuenta UPS
$cuentaUPS=  substr($_GET['guia'],0,6);

//Peso de paquete
$peso_paq="+0000000000000005";

//abrimos el fichero txt
$file = fopen("./archivopld/pld_".$_GET['guia'].".txt", "w");

////////////////////////////////////////////////////
//////////////Upload Message Header Segment//////////
////////////////////////////////////////////////////
//VersionNumber --4caracteres
$dat1="0200";
//DataSource
$dat2="94";
//MailboxID--24 espacios
$dat3="                        ";
//ClientSoftwareVersion--10 espacios
$dat4="          ";
//PickupDate --fecha del cierre de dia
$dat5=date("Ymd");
//PLDSequenceNumber --15 caracteres(5ceros+7+3)
$con="select book,page from tblguiamaster where guia='".$_GET['guia']."'";
$quer1 = mysqli_query($con,$conection);
while($ro=  mysqli_fetch_array($quer1))
{
  $BookNum= $ro['book'];
  $PageNum= $ro['page'];
}
    
$dat6="00000".$BookNum.$PageNum;
//IncrementalPLDCode --2ceros
$dat7="00";
//SoftwareVendorCode --3 espacios
$dat8="   ";
//NumShipperSegsInFile --9caracteres 
//The number of Shipper/Book/Page Information Segments //es fijo 000000001
$dat9="000000001";


fwrite($file,$dat1.$dat2.$dat3.$dat4.$dat5.$dat6.$dat7.$dat8.$dat9);

////////////////////////////////////////////////////
//////////////SHIPPER/BOOK/PAGE INFORMATION SEGMENT//////////
////////////////////////////////////////////////////
//SegmentIdentifier --3caract
$dat10="*AA";
//SenderShipperNumber --10carateres, rellenar con espacio derecha
$dat11=str_pad($cuentaUPS,10," ",STR_PAD_RIGHT);
//ShipperCountry --2caract
$dat12="EC";
//ShipperEIN  --15
$dat13="               ";
//CalculatedRatesInd  --1espacio
$dat14=" ";
//BookNum   --7
$dat15=$BookNum;
//PageNum  --3 --US, PR, CA, or VI, the value must be between 000 and 099 inclusive
$dat16=$PageNum;
//NumShipmentsInPage --6 --The number of Shipment Information Segments
//este numero corresponde con la cantidad de segmentos *BA.
//es el numero de ordenes enviadas +caja de documentos
$totquery="SELECT
            COUNT(guia) as tot
            FROM
            tblorden
            INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
            INNER JOIN tblguiamaster ON tbldetalle_orden.guiamaster = tblguiamaster.id
            where
            tblguiamaster.guia='".$_GET['guia']."'";
            $totpack = mysqli_query($totquery,$conection);
            $rowpack= mysqli_fetch_row($totpack);
        
$cant=  floatval($rowpack[0]+1);

$dat17=str_pad($cant,6,"0",STR_PAD_LEFT);

fwrite($file,$dat10.$dat11.$dat12.$dat13.$dat14.$dat15.$dat16.$dat17);

$num_segmentos=0;  //variable que te dice la cantidad de etiquetas por cada segmento.

////////////////////////////////////////////////////
//////////////SHIPMENT INFORMATION SEGMENT - *BA//////////
////////////////////////////////////////////////////

//lleno primero el segmento de la caja de documentos.
//SegmentIdentifier --3caract
    $sql="SELECT tblguiamaster.tracking_documentos FROM tblguiamaster WHERE tblguiamaster.guia='".$_GET['guia']."'";
    $query = mysqli_query($sql,$conection);
    $row = mysqli_fetch_array($query);
    $track_documentos=$row['tracking_documentos'];
    $ShipmentNumber= str_replace(" ","",shp($track_documentos));
    
    $cajadoc="*BA" . $track_documentos ."                 "."00001"."+0000000000000005"."0"."+0000000000000000KGS"."07"."COL"."10";
    $cajadoc.="TEST CONTACT                       "."3"."CM"."USD"."000001";
    fwrite($file,$cajadoc);
    $num_segmentos++;
    
    
    //busco el pais de envio al que pertenece esta guia
    $a1="SELECT
        DISTINCT
        tblshipto.shipcountry
        FROM
        tblorden
        INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
        INNER JOIN tblguiamaster ON tbldetalle_orden.guiamaster = tblguiamaster.id
        INNER JOIN tblshipto ON tblshipto.id_shipto = tblorden.id_orden
        WHERE tblguiamaster.guia='".$_GET['guia']."'";
    $q1 = mysqli_query($a1,$conection);
    $r1 = mysqli_fetch_row($q1);
    
    if($r1[0]=='US')
    {
        $cajadoc="*CA"."18"."Alina Alvarez Alzugaray            "."E-Blooms Direct, Inc.              "."2231 SW 82nd Pl                    ";
        $cajadoc.="                                   "."                                   ";
        $cajadoc.="MIAMI                         "."FL   "."33155    "."US"."13059050153    ";
        $cajadoc.=" "."               "."E27A19    "."               ";
        fwrite($file,$cajadoc);
        $num_segmentos++;

        $cajadoc="*CA"."08"."Alina Alvarez Alzugaray            "."E-Blooms Direct, Inc.              "."2231 SW 82nd Pl                    ";
        $cajadoc.="                                   "."                                   ";
        $cajadoc.="MIAMI                         "."FL   "."331551250"."US"."13059050153    ";
        $cajadoc.=" "."               "."E27A19    "."               ";
        fwrite($file,$cajadoc);
        $num_segmentos++;

        $cajadoc="*CA"."05"."Alina Alvarez Alzugaray            "."E-Blooms Direct, Inc.              "."2231 SW 82nd Pl                    ";
        $cajadoc.="                                   "."                                   ";
        $cajadoc.="MIAMI                         "."FL   "."33155    "."US"."13059050153    ";
        $cajadoc.=" "."               "."E27A19    "."               ";
        fwrite($file,$cajadoc);
        $num_segmentos++;

        $cajadoc="*CA"."06"."TEST CONTACT                       "."EBLOOMS / UPS TEST SHIPPER         "."ABRAHAM PEREZ S/N Y AV INTEROCEANIA";
        $cajadoc.="EL REFUGIO                         "."                                   ";
        $cajadoc.="QUITO                         "."     "."170902   "."EC"."59342640086    ";
        $cajadoc.=" "."               "."WY1190    "."               ";
        fwrite($file,$cajadoc);
        $num_segmentos++;
    }
    else if($r1[0]=='CA')
    {
        $cajadoc="*CA"."18"."Alina Alzugaray                    "."E-Blooms Direct, Inc.              "."2231 SW 82nd Pl                    ";
        $cajadoc.="                                   "."MIAMI FL 33155                     ";
        $cajadoc.="WINDSOR RR2                   "."ON   "."N8N2M1   ";
        $cajadoc.="CA"."13059050153                    "."A173A5    "."816170971RM0001";
        fwrite($file,$cajadoc);
        $num_segmentos++;

        $cajadoc="*CA07Alina Alvarez Alzugaray            E-Blooms Direct, Inc.              2231 SW 82nd Pl                                                                                          Miami                         FL   331551250US13059050153                    E27A19                   ";
        fwrite($file,$cajadoc);
        $num_segmentos++;

        $cajadoc="*CA05ALINA ALZUGARAY                    E-Blooms Direct Inc.               2231 S.W. 82 PLACE                                                    MIAMI FL 33155                     WINDSOR RR2                   ON   N8N2M1   CA13059050153                    A173A5    816170971RM0001";
        fwrite($file,$cajadoc);
        $num_segmentos++;

        $cajadoc="*CA06ABRAHAM PEREZ S/N Y AV. INTEROCEANIeBlooms                            EL REFUGIO                                                                                               QUITO                              170902   EC59342640086                    WY1190                   ";
        fwrite($file,$cajadoc);
        $num_segmentos++;
    }
    //FREE FORM TEXT SEGMENT - *NA
    $cajadoc="*NA"."007"."I hereby certify that the information on this invoice is true and correct and the contents and value of this shipment is as stated above.                                                                                                                                                                                                                                                                                                                                                                                                                             ";
    fwrite($file,$cajadoc);
    $num_segmentos++;
    
    $sql="SELECT
        tbldetalle_orden.cpitem,tblproductos.prod_descripcion,
        tblproductos.wheigthKg,
        COUNT(tbldetalle_orden.cpitem) as cant,dclvalue,tbldetalle_orden.Ponumber 
        FROM
        tbldetalle_orden
        INNER JOIN tblproductos ON tbldetalle_orden.cpitem = tblproductos.id_item
        INNER JOIN tblguiamaster ON tbldetalle_orden.guiamaster = tblguiamaster.id
        where guia='".$_GET['guia']."' GROUP BY tbldetalle_orden.cpitem";

    $query = mysqli_query($sql,$conection);
    $band=0;
    $num=0;
    $sumapreciototal=0;
    $Ponumberdoc="";
    while ($row = mysqli_fetch_array($query))
    {
      if($band==0)
      {
       $band=1;
       ////////////////////////////////////////////////
       //STANDARD INTERNATIONAL SHIPMENT SEGMENT - *IA
       ///////////////////////////////////////////////
       $cajadoc="*IA".$ShipmentNumber."1".str_pad(substr($row['prod_descripcion'],0,50),50, " ", STR_PAD_RIGHT).$ShipmentNumber."          ";
       fwrite($file,$cajadoc);
       $num_segmentos++;
       
        ////////////////////////////////////////////////////
        //////////////UPS WORLD EASE INFORMATION SEGMENT - *JA//////////
        ////////////////////////////////////////////////////
        ///SegmentIdentifier --3
        $cajadoc="*JA";
        //GCCN   -----11
        $cajadoc.=$ShipmentNumber;
        
        //ClearancePortCountry --2
        $cajadoc.=$r1[0];
        //ClearancePort --5
        if($r1[0]=='US')
            $cajadoc.="05273";
        
        //este depende del codigo postal del shipto que en caso de canada es N8N2M1
        if($r1[0]=='CA')
            $cajadoc.="01417";
        //CCTotalShipmentNum ---4
        //numero total de paquetes incluyendo la caja de documentos
        $totquery="SELECT
                    COUNT(guia) as tot
                    FROM
                    tblorden
                    INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                    INNER JOIN tblguiamaster ON tbldetalle_orden.guiamaster = tblguiamaster.id
                    where
                    tblguiamaster.guia='".$_GET['guia']."'";
                    $totpack = mysqli_query($totquery,$conection);
                    $rowpack= mysqli_fetch_row($totpack);
        
        $cant=  floatval($rowpack[0]+1);
        $cajadoc.=str_pad($cant, 4, "0", STR_PAD_LEFT); //sumo 1 que es la caja de documentos
        
        //CCTotalPkgNum---5 
        $cajadoc.=str_pad($cant, 5, "0", STR_PAD_LEFT); 
        //CCUOMWeight ----3
        $cajadoc.="KGS"; 
        //CCActualWeight--17 Format: +0000000000000000
        //para calcular el peso total de todos los envios
        $pesotot="SELECT
                    tbldetalle_orden.cpitem,tblproductos.wheigthKg,
                    COUNT(tbldetalle_orden.cpitem) as cant
                    FROM
                    tbldetalle_orden
                    INNER JOIN tblproductos ON tbldetalle_orden.cpitem = tblproductos.id_item
                    INNER JOIN tblguiamaster ON tbldetalle_orden.guiamaster = tblguiamaster.id
                    where guia='".$_GET['guia']."' GROUP BY tbldetalle_orden.cpitem";
        $peso=0;
        $pesotot1 = mysqli_query($pesotot,$conection);
        while ($row1 = mysqli_fetch_array($pesotot1))
        {
           $peso= floatval($peso+$row1['wheigthKg']*$row1['cant']);
        }
        $peso=floatval($peso*10);
        $cajadoc.= "+".str_pad($peso, 16, "0", STR_PAD_LEFT);
        //InvoiceShipToAlternate ---35
        $cajadoc.="Multiple Consignees.               ";
        fwrite($file,$cajadoc);
        $num_segmentos++;
       
        ////ojo este sera el PONUMBER de la caja de documentos igual al del primer producto
        $Ponumberdoc=$row['Ponumber'];
      }
      
      //////////////////////////////
      //COMMODITY INFORMATION SEGMENT - *KA
      //////////////////////////////////
      $cajadoc="*KA"."                                   "."                                   ";
      $cajadoc.=str_pad(++$num, 3, "0", STR_PAD_LEFT);
      $cajadoc.="EC";
      $cajadoc.="USD";
      
       //LineExtendedAmt----19
      //+000000000000020000 is $200 (for USD)
      //Este es el precio total de un solo ítem por la cantidad de ítems que hayan de ese tipo
     $preciototal= floatval($row['dclvalue']*$row['cant']*100);
     $sumapreciototal=floatval($sumapreciototal+$preciototal);
     $cajadoc.="+".str_pad($preciototal,18, "0", STR_PAD_LEFT);
     
    //LineUnitAmtPrice ---19
    //+000000000200000000 is $200 (for USD).
     //este es el precio de una unidad
    $preciounidad= floatval($row['dclvalue']*1000000);
    $cajadoc.="+".str_pad($preciounidad,18, "0", STR_PAD_LEFT);
    
    //LineQty  ----7
    //cantidad de items de un mismo tipo
    $cajadoc.=str_pad($row['cant'], 7, '0', STR_PAD_LEFT);
    
     //LineQtyUOM---3
    //esto dependera del tipo de boxtype
    $cajadoc.="BOX";
    
    //LineLicenseInfo---35
    $cajadoc.="                                   ";
    
    //LineLicenseExpDate---8
    $cajadoc.="        ";
    
    //despues la descripcion general del producto
    //LineMerchDesc1---35 +35
    $cajadoc.=str_pad(substr(trim($row['prod_descripcion']),0,35), 35," ", STR_PAD_RIGHT);
    $cajadoc.=str_pad(trim(substr(trim($row['prod_descripcion']),35,70)), 35," ", STR_PAD_RIGHT);    
    
    //ECCN  ---15
    $cajadoc.="               ";
    //CertOfOriginNo---10
    $cajadoc.="          ";
    //AgreementType----10
    $cajadoc.="          ";
    //UnitOfMeasureScheduleB1---3
    $cajadoc.="   ";
    //QuantityScheduleBUnits1---10
    $cajadoc.="          ";
    //UnitOfMeasureScheduleB2---3
    $cajadoc.="   ";
    //QuantityScheduleBUnits2 ----10
    //ScheduleBCode----10
    $cajadoc.="                    ";
    //CommodityWeight ---7
    //NumberOfPackagesPerCommodity ---3
    $cajadoc.="          ";
    //SEDLineAmt----19
    //COType--------1
    //SEDInd--------1
    $cajadoc.="                     ";
    $cajadoc.="    +000000   +000000000000000000                                                                                                                                                                                 ";
    fwrite($file,$cajadoc);
    $num_segmentos++;
 }
    
    ///SegmentIdentifier--3
    $cajadoc="*LA";
    //InvoiceDate---8
    $cajadoc.=date("Ymd");
    //InvoiceLineTotals--19 The value must match the declared value for the shipment on
    //the commercial/customs invoice. +000000000000020000 is $200 (for USD).
    //suma de todos los precios totales
    $cajadoc.="+".str_pad($sumapreciototal, 18,"0", STR_PAD_LEFT);
    //InvoiceCurrencyCode---3
    $cajadoc.="USD";
    //InvoiceNumber ----35
    $cajadoc.="                                   ";
    //PONumber----35
    //$cajadoc.=str_pad($Ponumberdoc, 35," ", STR_PAD_RIGHT);
    $cajadoc.="                                   ";
   //InvoiceSubTotal---19 ///ver si se pone este dato??????/
   $cajadoc.="+".str_pad($sumapreciototal, 18,"0", STR_PAD_LEFT);
   //TotalInvoiceAmount---19
   $cajadoc.="+".str_pad($sumapreciototal, 18,"0", STR_PAD_LEFT);
   //TermsOfShipment --3
   $cajadoc.="FCA";
   //PaymentTerms--35
   $cajadoc.="                                   ";
   //ReasonForExport--35
   $cajadoc.=str_pad("Sale",35," ", STR_PAD_RIGHT);   
   //FreightCharges---19
   $cajadoc.="+000000000000000000";
   //InsuranceCharges--19
   $cajadoc.="+000000000000000000";
   //DiscountRebate---19
   $cajadoc.="+000000000000000000";
   //OtherCharges----19
   $cajadoc.="+000000000000000000";
   //COCode--1
   $cajadoc.=" ";
   fwrite($file,$cajadoc);
   $num_segmentos++;

   //SegmentIdentifier--3
   $cajadoc="*PA";
   //PackageTrackingNumber--35
   $cajadoc.=str_pad($track_documentos,35," ", STR_PAD_RIGHT); 
   //PackagingType--2
   $cajadoc.="02";
   //PackageActualWeight--8  +0000005
   $cajadoc.="+0000005";
   //DeliverToAttnName--35
   $cajadoc.="Alina Alvarez Alzugaray            ";
   //DeliverToPhoneNumber---35
   $cajadoc.="13059050153    ";
   //MerchandiseDescription--35
   $cajadoc.="                                   ";
  //VoidInd--1
   $cajadoc.=" ";
   //PkgPublishedDimWt---8
   $cajadoc.="+0000000";
   //Length---9
   $cajadoc.="+00000000";
   //Width---9
   $cajadoc.="+00000000";
   //Height---9
   $cajadoc.="+00000000";
   fwrite($file,$cajadoc);
   $num_segmentos++;

  //SegmentIdentifier--3
  $cajadoc="*SA";
  //TotalSegmentsInShipment---6
  //The total number of segments in the shipment from *BA through *SA inclusive.
  $num_segmentos++;
  $cajadoc.=str_pad($num_segmentos,6,"0", STR_PAD_LEFT);
  fwrite($file,$cajadoc);
  
////////////////////////////////////////////////////////////////  
//////////////PARA CADA UNO DE LOS ENVIOSS///////////////////////
////////////////////////////////////////////////////////////////  

$sqlord="SELECT
        tblguiamaster.guia,
        tblshipto.shipto1,
        tblshipto.direccion,
        tblshipto.cpcuidad_shipto,
        tblshipto.cpestado_shipto,
        tblshipto.cptelefono_shipto,
        tblshipto.cpzip_shipto,
        tblshipto.shipcountry,
        tbldetalle_orden.tracking,
        tblproductos.wheigthKg,
        tblproductos.length,
        tblproductos.heigth,
        tblproductos.width,
        tblproductos.dclvalue,
        tblproductos.cpservicio,
        tbldetalle_orden.cpitem,
        tblproductos.prod_descripcion,
        tbldetalle_orden.cpcantidad,
        tblboxtype.tipo_Box,
        tbldetalle_orden.Ponumber,
        tbldetalle_orden.Custnumber
        FROM
        tblorden
        INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
        INNER JOIN tblguiamaster ON tbldetalle_orden.guiamaster = tblguiamaster.id
        INNER JOIN tblshipto ON tblshipto.id_shipto = tblorden.id_orden
        INNER JOIN tblproductos ON tbldetalle_orden.cpitem = tblproductos.id_item
        INNER JOIN tblboxtype ON tblproductos.boxtype = tblboxtype.id_Box
        where
        tblguiamaster.guia='".$_GET['guia']."'";
$queryord = mysqli_query($sqlord,$conection); 
while($roword=  mysqli_fetch_array($queryord))
{
    $num_segmentos=0;
    
    $DW=intval(($roword['length']*$roword['heigth']*$roword['width']/5000)*10);
    
    $serv=array('ES'=>'07','SV'=>'65');
    $servicio=$serv[$roword['cpservicio']];
    
    $ord="*BA" . $roword['tracking'] ."                 "."00001"."+".str_pad(floatval($roword['wheigthKg']*10),16,"0", STR_PAD_LEFT)."0"."+".str_pad($DW,16,"0", STR_PAD_LEFT)."KGS".$servicio."COL"."10";
    $ord.="TEST CONTACT                       "."3"."CM"."USD"."000001";
    fwrite($file,$ord);
    $num_segmentos++;
    
    
    if($r1[0]=='US')
    {
        ////*CA18
        $ord="*CA"."18"."/ ".str_pad(substr($roword['shipto1'],0,33),33," ", STR_PAD_RIGHT).str_pad(substr($roword['shipto1'],0,35),35," ", STR_PAD_RIGHT).str_pad(substr($roword['direccion'],0,35),35," ", STR_PAD_RIGHT);
        $ord.="                                   "."                                   ";
        $codp;
        if($roword['shipcountry']=="US")
        {
           //relleno a la izquierda con ceros 
           $codp=str_pad(str_replace(" ", "", $roword['cpzip_shipto']),9,"0",STR_PAD_LEFT);
        } 
        else if($roword['shipcountry']=="CA")       
        {
          $codp=str_pad(str_replace(" ", "", $roword['cpzip_shipto']),5,"0",STR_PAD_LEFT);  
        }

        $ord.=str_pad(substr($roword['cpcuidad_shipto'],0,30),30," ", STR_PAD_RIGHT);
        $ord.=str_pad(substr($roword['cpestado_shipto'],0,5),5," ", STR_PAD_RIGHT);
        $ord.=str_pad($codp,9," ", STR_PAD_RIGHT);
        $ord.=str_pad(substr($roword['shipcountry'],0,2),2," ", STR_PAD_RIGHT);
        $ord.=str_pad(preg_replace("/[^0-9]/i","",substr("1".$roword['cptelefono_shipto'],0,11)),15," ", STR_PAD_RIGHT);
        $ord.=" "."               "."E27A19    "."               ";
        fwrite($file,$ord);
        $num_segmentos++;

        ////*CA08
        $ord="*CA"."08"."Alina Alvarez Alzugaray            "."E-Blooms Direct, Inc.              "."2231 SW 82nd Pl                    ";
        $ord.="                                   "."                                   ";
        $ord.="MIAMI                         "."FL   "."331551250"."US"."13059050153    ";
        $ord.=" "."               "."E27A19    "."               ";
        fwrite($file,$ord);
        $num_segmentos++;

        ////*CA05
        $ord="*CA"."05"."Alina Alvarez Alzugaray            "."E-Blooms Direct, Inc.              "."2231 SW 82nd Pl                    ";
        $ord.="                                   "."                                   ";
        $ord.="MIAMI                         "."FL   "."33155    "."US"."13059050153    ";
        $ord.=" "."               "."E27A19    "."               ";
        fwrite($file,$ord);
        $num_segmentos++;
        $ord="*CA"."06"."TEST CONTACT                       "."EBLOOMS / UPS TEST SHIPPER         "."ABRAHAM PEREZ S/N Y AV INTEROCEANIA";
        $ord.="EL REFUGIO                         "."                                   ";
        $ord.="QUITO                         "."     "."170902   "."EC"."59342640086    ";
        $ord.=" "."               "."WY1190    "."               ";
        fwrite($file,$ord);
        $num_segmentos++;
    }
    else if($r1[0]=='CA')
    {
        ////*CA18
        $ord="*CA"."18"."/ ".str_pad(substr($roword['shipto1'],0,33),33," ", STR_PAD_RIGHT).str_pad(substr($roword['shipto1'],0,35),35," ", STR_PAD_RIGHT).str_pad(substr($roword['direccion'],0,35),35," ", STR_PAD_RIGHT);
        $ord.="                                   "."                                   ";
        $codp;
        if($roword['shipcountry']=="US")
        {
           //relleno a la izquierda con ceros 
           $codp=str_pad(str_replace(" ", "", $roword['cpzip_shipto']),9,"0",STR_PAD_LEFT);
        } 
        else if($roword['shipcountry']=="CA")       
        {
          $codp=str_pad(str_replace(" ", "", $roword['cpzip_shipto']),5,"0",STR_PAD_LEFT);  
        }

        $ord.=str_pad(substr($roword['cpcuidad_shipto'],0,30),30," ", STR_PAD_RIGHT);
        $ord.=str_pad(substr($roword['cpestado_shipto'],0,5),5," ", STR_PAD_RIGHT);
        $ord.=str_pad($codp,9," ", STR_PAD_RIGHT);
        $ord.=str_pad(substr($roword['shipcountry'],0,2),2," ", STR_PAD_RIGHT);
        $ord.=str_pad(preg_replace("/[^0-9]/i","",substr("1".$roword['cptelefono_shipto'],0,11)),15," ", STR_PAD_RIGHT);
        $ord.=" "."               "."E27A19    "."               ";
        fwrite($file,$ord);
        $num_segmentos++;

        ////*CA08
        $ord="*CA"."07"."Alina Alvarez Alzugaray            "."E-Blooms Direct, Inc.              "."2231 SW 82nd Pl                    ";
        $ord.="                                   "."                                   ";
        $ord.="MIAMI                         "."FL   "."331551250"."US"."13059050153    ";
        $ord.=" "."               "."E27A19    "."               ";
        fwrite($file,$ord);
        $num_segmentos++;

        ////*CA05
        $ord="*CA"."05"."ALINA ALZUGARAY                    E-Blooms Direct Inc.               2231 S.W. 82 PLACE                                                    MIAMI FL 33155                     WINDSOR RR2                   ON   N8N2M1   CA13059050153                    A173A5    816170971RM0001";
        fwrite($file,$ord);
        $num_segmentos++;
        
        $ord="*CA"."06"."TEST CONTACT                       "."EBLOOMS / UPS TEST SHIPPER         "."ABRAHAM PEREZ S/N Y AV INTEROCEANIA";
        $ord.="EL REFUGIO                         "."                                   ";
        $ord.="QUITO                         "."     "."170902   "."EC"."59342640086    ";
        $ord.=" "."               "."WY1190    "."               ";
        fwrite($file,$ord);
        $num_segmentos++;
    }
        
    //SegmentIdentifier--3
    $ord="*FA";
    //Reference/BarcodeQualifier---3
    $ord.="001";
    //ReferenceNumberTypeCode--2
    $ord.="IK";
    //Reference/BarcodeNumber---35
    $ord.=str_pad($roword['Ponumber'],35," ", STR_PAD_RIGHT);
    fwrite($file,$ord);
    $num_segmentos++;

    $ord="*FA"."002"."PO".str_pad($roword['Custnumber'],9," ", STR_PAD_RIGHT)." / Item: ".str_pad($roword['cpitem'],17," ", STR_PAD_RIGHT);
    fwrite($file,$ord);
    $num_segmentos++;
    
    $ord="*NA"."007"."I hereby certify that the information on this invoice is true and correct and the contents and value of this shipment is as stated above.                                                                                                                                                                                                                                                                                                                                                                                                                             ";
    fwrite($file,$ord);
    $num_segmentos++; 
    
    $ShipmentNumber1=str_replace(" ","",shp($roword['tracking']));
    $ord="*IA".$ShipmentNumber1."1".str_pad(substr(trim($roword['prod_descripcion']),"0",50),50, " ", STR_PAD_RIGHT).$ShipmentNumber."          ";
    fwrite($file,$ord);
    $num_segmentos++;
    
    $ord="*KA"."                                   "."                                   ";
    $ord.="001"."EC"."USD";
    $preciototal= floatval($roword['dclvalue']*100);
    $ord.="+".str_pad($preciototal,18, "0", STR_PAD_LEFT);
    $preciounidad= floatval($roword['dclvalue']*1000000);
    $ord.="+".str_pad($preciounidad,18, "0", STR_PAD_LEFT);
    $ord.=str_pad($roword['cpcantidad'], 7, '0', STR_PAD_LEFT);
    $ord.="BOX";
    $ord.="                                   ";
    $ord.="        ";
    $ord.=str_pad(substr(trim($roword['prod_descripcion']),0,35), 35," ", STR_PAD_RIGHT);
    $ord.=str_pad(trim(substr(trim($roword['prod_descripcion']),35,70)), 35," ", STR_PAD_RIGHT);
    $ord.="               ";
    $ord.="          ";
    $ord.="          ";
    $ord.="   ";
    $ord.="          ";
    $ord.="   ";
    $ord.="                    ";
    $ord.="          ";
    $ord.="                     ";
    $ord.="    +000000   +000000000000000000".str_pad("", 177," ", STR_PAD_RIGHT);
    fwrite($file,$ord);
    $num_segmentos++;
    
    
    $ord="*LA";
    $ord.=date("Ymd");
    $ord.="+".str_pad(floatval($roword['dclvalue']*100), 18,"0", STR_PAD_LEFT);
    $ord.="USD";
    
    $ord.="                                   ";
    $ord.="                                   ";
    $ord.="+".str_pad(floatval($roword['dclvalue']*100), 18,"0", STR_PAD_LEFT);
    $ord.="+".str_pad(floatval($roword['dclvalue']*100), 18,"0", STR_PAD_LEFT);
    $ord.="FCA";
    $ord.="                                   ";
    $ord.=str_pad("Sale",35," ", STR_PAD_RIGHT);   
    $ord.="+000000000000000000";
    $ord.="+000000000000000000";
    $ord.="+000000000000000000";
    $ord.="+000000000000000000";
    $ord.=" ";
    fwrite($file,$ord);
    $num_segmentos++;
    
    
    $ord="*PA";
    $ord.=str_pad($roword['tracking'],35," ", STR_PAD_RIGHT); 
    $ord.="02";
    $ord.="+".str_pad(floatval($roword['wheigthKg']*10),7,"0", STR_PAD_LEFT);
    $ord.="/ ".str_pad($roword['shipto1'],33," ", STR_PAD_RIGHT);
    $ord.=str_pad(preg_replace("/[^0-9]/i","",substr("1".$roword['cptelefono_shipto'],0,11)),15," ", STR_PAD_RIGHT);
    $ord.="                                   ";
    $ord.=" ";
    $ord.="+0000000";
    $ord.="+00000000";
    $ord.="+00000000";
    $ord.="+00000000";
    fwrite($file,$ord);
    $num_segmentos++;
    
    
    $ord="*FA"."003"."IK".str_pad($roword['Ponumber'],35," ", STR_PAD_RIGHT);
    fwrite($file,$ord);
    $num_segmentos++;
    
    $ord="*FA"."004"."PO".str_pad($roword['Custnumber'],9," ", STR_PAD_RIGHT)." / Item: ".str_pad($roword['cpitem'],17," ", STR_PAD_RIGHT);
    fwrite($file,$ord);
    $num_segmentos++;
    
    $ord="*FA"."005"."  ".str_pad($roword['cpitem'],35," ", STR_PAD_RIGHT);
    fwrite($file,$ord);
    $num_segmentos++;
    
    $ord="*FA"."006"."  ".str_pad($roword['Custnumber'],35," ", STR_PAD_RIGHT);
    fwrite($file,$ord);
    $num_segmentos++;
    
    $ord="*SA";
    $num_segmentos++;
    $ord.=str_pad($num_segmentos,6,"0", STR_PAD_LEFT);
    fwrite($file,$ord);
}
fclose($file);


include 'enviarPLD.php';