<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL); 
//ini_set('display_errors', 1);


session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");
require('fpdf/fpdf.php');
require_once('barcode.inc.php');

session_start();
$user = $_SESSION["login"];
$rol = $_SESSION["rol"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

$madre = $_SESSION["madre"];
$hija = $_SESSION["hija"];
$finca = $_SESSION["finca"];
$todo = $_SESSION["todo"];
$codigo = $_SESSION["codigo"];

class PDF extends FPDF {

    //Pie de página
    function Footer() {
        //Posición: a 1,5 cm del final
        $this->SetY(-15);
        //Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        //Número de página
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    //Tabla titulos de la tabla1
    function Tabla1() {
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(10, 5, "PIECES", 1, 0, 'C');
        $this->CellFitSpace(12, 5, "BOX TYPE", 1, 0, 'C');
        $this->CellFitSpace(25, 5, "GEN.DESCR", 1, 0, 'C');
        $this->CellFitSpace(40, 5, "Product/name", 1, 0, 'C');
        $this->CellFitSpace(8, 5, "SPI", 1, 0, 'C');
        $this->CellFitSpace(20, 5, "USHTS", 1, 0, 'C');
        $this->CellFitSpace(20, 5, "NANDINA", 1, 0, 'C');
        $this->CellFitSpace(14, 5, "BUCH/BOX", 1, 0, 'C');
        $this->CellFitSpace(14, 5, "STM/BU", 1, 0, 'C');
        $this->CellFitSpace(15, 5, "TOT STEMS", 1, 0, 'C');
        $this->CellFitSpace(14, 5, "UNIT PRICE", 1, 0, 'C');
        $this->CellFitSpace(14, 5, "TOT PRICE ", 1, 0, 'C');
        $this->Ln();
    }

    //Tabla titulos de la tabla1
    function Tabla2() {
        $this->Cell(25, 5, "FULL 1/1", 1, 0, 'C');
        $this->Cell(25, 5, "HALF 1/2", 1, 0, 'C');
        $this->Cell(25, 5, "QTRS 1/4", 1, 0, 'C');
        $this->Cell(20, 5, "EIGHT 1/8", 1, 0, 'C');
        $this->Cell(25, 5, "SIXTEENTH 1/16", 1, 0, 'C');
        $this->Cell(20, 5, "HUMPERS", 1, 0, 'C');
        $this->Cell(20, 5, "PROCONA", 1, 0, 'C');
        $this->Cell(25, 5, "TOTAL FULL BOX", 1, 0, 'C');
        $this->Ln();
    }

    //***** Aquí comienza código para ajustar texto *************
    //***********************************************************
    function CellFit($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '', $scale = false, $force = true) {
        //Get string width
        $str_width = $this->GetStringWidth($txt);

        //Calculate ratio to fit cell
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $ratio = ($w - $this->cMargin * 2) / $str_width;

        $fit = ($ratio < 1 || ($ratio > 1 && $force));
        if ($fit) {
            if ($scale) {
                //Calculate horizontal scaling
                $horiz_scale = $ratio * 100.0;
                //Set horizontal scaling
                $this->_out(sprintf('BT %.2F Tz ET', $horiz_scale));
            } else {
                //Calculate character spacing in points
                $char_space = ($w - $this->cMargin * 2 - $str_width) / max($this->MBGetStringLength($txt) - 1, 1) * $this->k;
                //Set character spacing
                $this->_out(sprintf('BT %.2F Tc ET', $char_space));
            }
            //Override user alignment (since text will fill up cell)
            $align = '';
        }

        //Pass on to Cell method
        $this->Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);

        //Reset character spacing/horizontal scaling
        if ($fit)
            $this->_out('BT ' . ($scale ? '100 Tz' : '0 Tc') . ' ET');
    }

    function CellFitSpace($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '') {
        $this->CellFit($w, $h, $txt, $border, $ln, $align, $fill, $link, false, false);
    }

    //Patch to also work with CJK double-byte text
    function MBGetStringLength($s) {
        if ($this->CurrentFont['type'] == 'Type0') {
            $len = 0;
            $nbbytes = strlen($s);
            for ($i = 0; $i < $nbbytes; $i++) {
                if (ord($s[$i]) < 128)
                    $len++;
                else {
                    $len++;
                    $i++;
                }
            }
            return $len;
        } else
            return strlen($s);
    }

//************** Fin del código para ajustar texto *****************
//******************************************************************
}

//Creacion del objeto pdf		
$pdf = new PDF();
$pdf->SetLeftMargin(3);
$cont = 0;
$total = 0;
$TOTAL = 0;

/* * ***** si el reporte es filtrado por guia madre ******** */
/////////OJOOOOO ESTE CODIGO 1 NUNCA SE ESTA MANDANDO POR ESO ES QUE SIEMPRE ENTRA AL ELSE DE LA CONDICION.
if ($codigo == 1) { //Si el filtro guia madre tiene valor
    $sql = "SELECT DISTINCT guia_hija, fecha_vuelo, fecha_entrega, servicio, airline FROM tblcoldroom WHERE salida='Si' AND guia_madre = '" . $madre . "' AND finca='" . $finca . "'  UNION (SELECT guia_h,vuelo,entrega,servicio,aerolinea FROM tblcoldrom_fincas WHERE guia_m = '" . $madre . "' AND finca='" . $finca . "') ORDER BY fecha_vuelo, fecha_entrega, servicio";
    $query = mysqli_query($link, $sql);
    //echo $sql;
    while ($row = mysqli_fetch_array($query)) { //Por cada finca diferente creo una pagina
        //Creando la pagina del pdf
        $pdf->AddPage();
        $pdf->AliasNbPages();
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->Cell(180, 7, "COMMERCIAL INVOICE", 0, 0, 'C');
        $pdf->Ln();

        //Mostrando los datos generales de la guia madre
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(118, 7, "SHIPPER NAME AND ADDRESS", 0, 0, 'L');
        $pdf->Cell(35, 7, "FLIGHT DATE", 0, 0, 'C');

        $pdf->Ln();
        $pdf->Cell(115, 7, "NAME: " . utf8_decode($finca), 1, 0, 'L');
        $pdf->Cell(5, 7, "", 0, 0, 'C');

        //Seleccionamos los datos de la finca en cuaetion
        $a = "SELECT * FROM tblfinca WHERE nombre = '" . $finca . "'";
        $b = mysqli_query($link, $a) or die("Error consultando los datos de la finca");
        $c = mysqli_fetch_array($b);
        $pdf->Cell(65, 7, $row['fecha_vuelo'], 1, 0, 'C');


        $pdf->Ln();
        $pdf->Cell(115, 7, "ADDRESS: " . utf8_decode($c['direccion']), 1, 0, 'L');
        $pdf->Cell(35, 7, "FARM CODE", 0, 0, 'C');
        $pdf->Ln();

        $pdf->Cell(115, 7, "PHONE: " . utf8_decode($c['telefono']), 1, 0, 'L');
        $pdf->Cell(5, 7, "", 0, 0, 'C');
        $pdf->Cell(65, 7, utf8_decode($c['farm_code']), 1, 0, 'C');
        $pdf->Ln();

        $pdf->Cell(115, 7, "RUC: " . utf8_decode($c['ruc']), 1, 0, 'L');
        $pdf->Cell(35, 7, "COUNTRY CODE", 0, 0, 'C');
        $pdf->Cell(35, 7, "INVOICE NO.", 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(120, 7, "MARKETING NAME", 0, 0, 'L');
        $pdf->Cell(30, 7, $c['pais_code'], 1, 0, 'C');
        $pdf->Cell(5, 7, "", 0, 0, 'C');

        //Buscar el destino de esta guia madre
        $guia = "SELECT codigo FROM tblcoldroom WHERE guia_madre = '" . $madre . "' UNION (SELECT codigo_unico FROM tblcoldrom_fincas WHERE guia_m = '" . $madre . "') limit 1";
        $buscarguia = mysqli_query($link, $guia) or die("Error consultando el origen");
        $filaguia = mysqli_fetch_array($buscarguia);

        $destinoSql = "SELECT destino FROM tbletiquetasxfinca WHERE codigo = '" . $filaguia['codigo'] . "'";
        $buscardestino = mysqli_query($link, $destinoSql) or die("Error consultando el origen");
        $filadestino = mysqli_fetch_array($buscardestino);
        $destino = $filadestino['destino'];

        //bUSCAR EL DAE DE ESTA FINCA para este destino
        $senteciaDAE = "SELECT dae,finicio,ffin FROM tbldae WHERE nombre_finca = '" . $finca . "' AND pais_dae = '" . $destino . "'";
        $e = mysqli_query($link, $senteciaDAE) or die("Error consultando datos de la finca");
        $dae = "";
        while ($row1 = mysqli_fetch_array($e)) {
            if ($row1['finicio'] <= $row['fecha_vuelo'] && $row1['ffin'] >= $row['fecha_vuelo']) {
                $dae = $row1['dae'];
            }
        }

        //Generando o consultando el nro. de invoice
        $verSql = "SELECT invoice FROM tblinvoice WHERE id_guia_madre='" . $madre . "' AND id_guia_hija='" . $row['guia_hija'] . "' AND fecha_vuelo='" . $row['fecha_vuelo'] . "' AND invoice_dae='" . $dae . "'";
        //echo $verSql;
        $verQuery = mysqli_query($link, $verSql) or die("Error consultando el invoice");
        $cantidad = mysqli_num_rows($verQuery);

        if ($cantidad == 0) {//Si la cantidad es cero genero el invoice de saa factura y lo inserto en la tabla de invoice
            //Obteniendo el ultimo invoice generado y sumandole 1
            $verSql1 = "SELECT invoice FROM tblinvoice order by invoice DESC LIMIT 1";
            $verQuery1 = mysqli_query($link, $verSql1) or die("Error consultando el invoice");
            $verrow1 = mysqli_fetch_array($verQuery1);
            $invoice = $verrow1['invoice'] + 1;

            //Insertando el nuevvo invoice generado
            $insertSql = "INSERT INTO tblinvoice (id_guia_madre,id_guia_hija,invoice,fecha_vuelo,invoice_dae) VALUES ('" . $madre . "','" . $row['guia_hija'] . "','" . $invoice . "','" . $row['fecha_vuelo'] . "','" . $dae . "')";
            $ejecutarSql = mysqli_query($link, $insertSql) or die("Error insertando el invoice");
        } else { //Si la cantidad es mayor que cero o sea 1, solo obtengo el invoice para mostrar
            //Obteniendo el ultimo invoice generado y guardado
            $verrow = mysqli_fetch_array($verQuery);
            $invoice = $verrow['invoice'];
        }

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(30, 7, str_pad($invoice, 10, "0", STR_PAD_LEFT), 1, 0, 'C');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Ln();
        $pdf->Cell(100, 7, "e-Blooms Direct, Inc.", 1, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(120, 7, "FOREIGN PURCHASER", 0, 0, 'L');
        $pdf->Cell(70, 7, "AWB #", 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(100, 7, "NAME: e-Blooms Direct, Inc.", 1, 0, 'L');
        $pdf->Cell(20, 7, "", 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(65, 7, $madre, 1, 0, 'L');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(100, 7, "ADDRESS: 9440 NW 12 St, Suite 202 Miami, Fl 33172 USA", 1, 0, 'L');
        $pdf->Cell(20, 7, "", 0, 0, 'C');
        $pdf->Cell(20, 7, "HAWB #", 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(100, 7, "PHONE: 786-542-9626, 786-542-9627", 1, 0, 'L');
        $pdf->Cell(20, 7, "", 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(65, 7, $row['guia_hija'], 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Ln();
        $pdf->Cell(100, 7, "FAX: # 1-305-675-0328", 1, 0, 'L');
        $pdf->Cell(20, 7, "", 0, 0, 'C');
        $pdf->Cell(70, 7, "AIR LINE & FLIGHT", 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(120, 7, "", 0, 0, 'C');
        $pdf->Cell(65, 7, $row['airline'], 1, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(70, 7, "COUNTRY OF ORIGEN", 0, 0, 'L');
        $pdf->Cell(42, 7, "DAE NO.", 0, 0, 'C');
        $pdf->Cell(40, 7, "ADD CSE #	", 0, 0, 'C');
        $pdf->Ln();

        //obtener el origen de los items de la factura comercial
        $SQL = "SELECT item FROM tblcoldroom  WHERE salida='Si' AND guia_madre = '" . $madre . "' AND fecha_vuelo='" . $row['fecha_vuelo'] . "' AND fecha_entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $finca . "' AND guia_hija='" . $row['guia_hija'] . "' UNION (SELECT item FROM tblcoldrom_fincas WHERE guia_m = '" . $madre . "' AND vuelo='" . $row['fecha_vuelo'] . "' AND entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $finca . "' AND guia_h='" . $row['guia_hija'] . "')  ORDER BY item limit 1";
        $QUERY = mysqli_query($link, $SQL) or die("Error selecionando el item");
        $ROW = mysqli_fetch_array($QUERY);

        $SQL1 = "SELECT origen FROM tblproductos  WHERE id_item='" . $ROW['item'] . "'";
        $QUERY1 = mysqli_query($link, $SQL1) or die("Error selecionando el origen");
        $ROW1 = mysqli_fetch_array($QUERY1);
        $pdf->Cell(50, 7, $ROW1['origen'], 1, 0, 'C');

        $pdf->Cell(20, 7, "", 0, 0, 'C');

        $pdf->Cell(40, 7, $dae, 1, 0, 'C');

        $pdf->Cell(10, 7, "", 0, 0, 'C');
        $pdf->Cell(40, 7, "", 1, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(60, 7, "FIXED PRICE/VENTA DIRECTA [ ]", 0, 0, 'L');
        //$pdf->Cell(120,7,"THE FLOWERS AND PLANTS ON THIS INVOICE WERE WHOLLY GROWN IN ECUADOR",1,0,'L');

        $pdf->Ln();

        $pdf->Tabla1();

        //POR CADA RECETA UTILIZADA EN EL PEDIDO
        //seleccionar diferentes items
        $sql2 = "SELECT DISTINCT item FROM tblcoldroom  WHERE salida='Si' AND guia_madre = '" . $madre . "' AND fecha_vuelo='" . $row['fecha_vuelo'] . "' AND fecha_entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $finca . "' AND guia_hija='" . $row['guia_hija'] . "' UNION (SELECT item FROM tblcoldrom_fincas WHERE guia_m = '" . $madre . "' AND vuelo='" . $row['fecha_vuelo'] . "' AND entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $finca . "' AND guia_h='" . $row['guia_hija'] . "') ORDER BY item";
        $query2 = mysqli_query($link, $sql2) or die("Error selecionando las fincas");

        $total = 0;
        $box_full = 0;
        $box_medio = 0;
        $box_cuarto = 0;
        $box_octavo = 0;
        $box_dieciseis = 0;
        $box_hamp = 0;
        $box_procona = 0;

        //Recorra cada item diferente para escoger sus datos en cada caso
        while ($row2 = mysqli_fetch_array($query2)) {
            $pdf->SetFont('Arial', 'B', 8);
            //Cantidad de item con ese codigo
            $g = "SELECT COUNT(*) as cantidad FROM tblcoldroom  WHERE salida='Si' AND guia_madre = '" . $madre . "' AND fecha_vuelo='" . $row['fecha_vuelo'] . "' AND fecha_entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $finca . "' AND guia_hija='" . $row['guia_hija'] . "' AND item='" . $row2['item'] . "' UNION (SELECT COUNT(*) as cantidad FROM tblcoldrom_fincas WHERE guia_m = '" . $madre . "' AND vuelo='" . $row['fecha_vuelo'] . "' AND entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $finca . "' AND guia_h='" . $row['guia_hija'] . "' AND item='" . $row2['item'] . "')";

            $h = mysqli_query($link, $g) or die("Error selecionando la cantidad");
            $fila11 = mysqli_fetch_array($h);
            //imprimiendo cantidad                                                        
            $TOTALPIECES += $fila11[0];
            $pdf->Cell(10, 5, $fila11[0], 1, 0, 'C');

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $g1 = "SELECT tblproductos.*,tblboxtype.tipo_Box FROM tblboxtype INNER JOIN tblproductos ON tblboxtype.id_Box = tblproductos.boxtype where tblproductos.id_item='" . $row2['item'] . "'";
            $h1 = mysqli_query($link, $g1) or die("Error selecionando las fincas");
            $fila2 = mysqli_fetch_array($h1);
            //agregando boxtype
            $pdf->Cell(12, 5, $fila2['tipo_Box'], 1, 0, 'C');

            //voy viendo el Box y voy sumando la cantidad por cada uno
            if ($fila2['tipo_Box'] == 'FULL')
                $box_full += $fila11[0];
            if ($fila2['tipo_Box'] == 'HLF')
                $box_medio += $fila11[0];
            if ($fila2['tipo_Box'] == 'QBX')
                $box_cuarto += $fila11[0];
            if ($fila2['tipo_Box'] == '1/8')
                $box_octavo += $fila11[0];
            if ($fila2['tipo_Box'] == '1/16')
                $box_dieciseis += $fila11[0];
            if ($fila2['tipo_Box'] == 'HAMP')
                $box_hamp += $fila11[0];
            if ($fila2['tipo_Box'] == 'PRCN')
                $box_procona += $fila11[0];


            //agregando gen descripction
            $pdf->Cell(184, 5, $fila2['gen_desc'], 1, 0);

            //consultando receta
            $kk = "SELECT tbldetallereceta.* FROM tbldetallereceta INNER JOIN tblproductos ON tbldetallereceta.id_receta = tblproductos.id_receta WHERE tblproductos.id_item='" . $row2['item'] . "'";
            $ll = mysqli_query($link, $kk) or die("Error consultando el hts del item");

            //recorro todos los detalles de la receta del item
            //agregando gen descripction
            $packanterior = ""; //esta variable tendra el pack anterior del item de receta que si tiene pack

            while ($rowdetallereceta = mysqli_fetch_array($ll)) {
                $pdf->Ln();
                $pdf->Cell(10, 5, "", 1, 0, 'C');
                $pdf->Cell(12, 5, "", 1, 0, 'C');
                $pdf->Cell(25, 5, "", 1, 0, 'C');
                $pdf->CellFitSpace(40, 5, $rowdetallereceta['producto'], 1, 0, 'C');
                //agregando SPI
                $pdf->Cell(8, 5, 'A', 1, 0, 'C');
                //agregando SPI
                $pdf->CellFitSpace(20, 5, $rowdetallereceta['hts'], 1, 0, 'C');
                //agregando nandina
                $pdf->CellFitSpace(20, 5, $rowdetallereceta['nandina'], 1, 0, 'C');

                //agregando pack
                if ($rowdetallereceta['pack'] != "") {
                    $packanterior = $rowdetallereceta['pack'];
                }
                $pdf->Cell(14, 5, $packanterior, 1, 0, 'C');

                //agregando cantidad
                $pdf->CellFitSpace(14, 5, $rowdetallereceta['cantidad'], 1, 0, 'C');

                //calculo el producto de pieces*STEMS/BUNCH	*cantidad
                $totalstem = $fila11[0] * $rowdetallereceta['cantidad'] * $packanterior;
                $pdf->Cell(15, 5, $totalstem, 1, 0, 'C');

                $pdf->Cell(14, 5, $rowdetallereceta['stem'], 1, 0, 'C');

                //calculo total price
                $totalPrice = $totalstem * $rowdetallereceta['stem'];
                $total += $totalPrice;
                $pdf->Cell(14, 5, $totalPrice, 1, 0, 'C');
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            }
            $pdf->Ln();
        }
        //$pdf->Ln();
        $pdf->Cell(163, 5, "", 0, 0, 'C');
        $pdf->Cell(29, 7, "Total USD: ", 1, 0, 'C');
        $pdf->Cell(14, 7, number_format($total, 2), 1, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(190, 7, "PIECES DESCRIPTION:", 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(30, 7, "TOTAL PIECES:", 0, 0, 'L');
        $pdf->Cell(15, 7, $TOTALPIECES, 0, 0, 'L');
        $pdf->Ln();
        $pdf->Tabla2();
        $valor_full = $box_full;
        $pdf->Cell(25, 5, $box_full, 1, 0, 'C'); //Full 1/1

        $valor_medio = $box_medio * 0.5;
        $pdf->Cell(25, 5, $box_medio, 1, 0, 'C'); //Half 1/2

        $valor_cuarto = $box_cuarto * 0.25;
        $pdf->Cell(25, 5, $box_cuarto, 1, 0, 'C'); //Qtrs  1/4

        $valor_octavo = $box_octavo * 0.125;
        $pdf->Cell(20, 5, $box_octavo, 1, 0, 'C'); //Sixth 1/6

        $valor_dieciseis = $box_dieciseis * 0.0625;
        $pdf->Cell(25, 5, $box_dieciseis, 1, 0, 'C'); //Eight  1/8

        $pdf->Cell(20, 5, $box_hamp, 1, 0, 'C'); //Humpers

        $pdf->Cell(20, 5, $box_procona, 1, 0, 'C'); //Wet pack

        $total_box = $valor_full + $valor_medio + $valor_cuarto + $valor_octavo + $valor_dieciseis + $box_hamp + $box_procona;
        $pdf->Cell(25, 5, $total_box, 1, 0, 'C'); //Total full Box



        $pdf->Ln();
        $pdf->Ln();

        $pdf->SetFontSize(14);
        $pdf->SetTextColor(255, 0, 0);
        $pdf->Cell(200, 7, "\"Goods qualify under Generalized system of preferences GSP\"", 0, 0, 'C');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->Cell(135, 7, "NAME AND TITLE OF PERSON PREPARING INVOICE:", 0, 0, 'L');
        $pdf->Cell(50, 7, "FREIGHT FORWARDER:", 0, 0, 'L');
        $pdf->Ln();
        //Bscar el contactpo de esa finca
        $sentencia = "SELECT contacto FROM tblfinca WHERE nombre='" . utf8_decode($finca) . "'";
        $consulta = mysqli_query($link, $sentencia)or die("Error consultando contacto");
        $resultado = mysqli_fetch_array($consulta);
        $pdf->Cell(135, 7, $resultado['contacto'], 1, 0, 'C');

        //buscar un codigo de la guia madre para localizar con que agencia viajo
        $AAA = "SELECT codigo FROM tblcoldroom WHERE guia_madre='" . $madre . "' UNION (SELECT codigo_unico FROM tblcoldrom_fincas WHERE guia_m = '" . $madre . "') limit 1";
        $bbb = mysqli_query($link, $AAA)or die("Error consultando agencia");
        $result = mysqli_fetch_array($bbb);
        //BUSCAR LA AGENCIA DE ESTE VUELO
        $AA = "SELECT DISTINCT agencia FROM tbletiquetasxfinca WHERE codigo='" . $result['codigo'] . "'";
        $bb = mysqli_query($link, $AA)or die("Error consultando agencia");
        $cc = mysqli_fetch_array($bb);
        $pdf->Cell(50, 7, $cc['agencia'], 1, 0, 'C');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Cell(135, 20, "", 1, 0, 'L');
        $pdf->Cell(50, 20, "", 1, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(135, 7, "Customs Use Only", 0, 0, 'C');
        $pdf->Cell(50, 7, "USA, APHIS P.P.Q. Use Only", 0, 0, 'C');

        $TOTAL = 0;
        $TOTALPIECES = 0;
    }

    //generando el pdf
    $pdf->Output("Guias Asignadas.pdf", "I");
} else {
    //*********************Facturacion por eblooms ********************************/
    $sql = " SELECT DISTINCT finca,guia_hija, fecha_vuelo, fecha_entrega, servicio, airline FROM tblcoldroom WHERE salida='Si' AND guia_madre = '" . $madre . "' UNION (SELECT finca,guia_h, vuelo, entrega, servicio,aerolinea FROM tblcoldrom_fincas WHERE guia_m = '" . $madre . "') ORDER BY fecha_vuelo, fecha_entrega, servicio";
    //echo $sql;
    $query = mysqli_query($link, $sql);

    while ($row = mysqli_fetch_array($query)) { //Por cada finca diferente creo una pagina
        //Creando la pagina del pdf
        $pdf->AddPage();
        $pdf->AliasNbPages();
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->Cell(180, 7, "COMMERCIAL INVOICE", 0, 0, 'C');
        $pdf->Ln();

        //Mostrando los datos generales de la guia madre
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(118, 7, "SHIPPER NAME AND ADDRESS", 0, 0, 'L');
        $pdf->Cell(35, 7, "FLIGHT DATE", 0, 0, 'C');

        $pdf->Ln();
        $pdf->Cell(115, 7, "NAME: " . utf8_decode($row['finca']), 1, 0, 'L');
        $pdf->Cell(5, 7, "", 0, 0, 'C');

        //Seleccionamos los datos de la finca en cuaetion
        $a = "SELECT * FROM tblfinca WHERE nombre = '" . $row['finca'] . "'";
        $b = mysqli_query($link, $a) or die("Error consultando los datos de la finca");
        $c = mysqli_fetch_array($b);
        $pdf->Cell(65, 7, $row['fecha_vuelo'], 1, 0, 'C');


        $pdf->Ln();
        $pdf->Cell(115, 7, "ADDRESS: " . utf8_decode($c['direccion']), 1, 0, 'L');
        $pdf->Cell(35, 7, "FARM CODE", 0, 0, 'C');
        $pdf->Ln();

        $pdf->Cell(115, 7, "PHONE: " . utf8_decode($c['telefono']), 1, 0, 'L');
        $pdf->Cell(5, 7, "", 0, 0, 'C');
        $pdf->Cell(65, 7, utf8_decode($c['farm_code']), 1, 0, 'C');
        $pdf->Ln();

        $pdf->Cell(115, 7, "RUC: " . utf8_decode($c['ruc']), 1, 0, 'L');
        $pdf->Cell(35, 7, "COUNTRY CODE", 0, 0, 'C');
        $pdf->Cell(35, 7, "INVOICE NO.", 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(120, 7, "MARKETING NAME", 0, 0, 'L');
        $pdf->Cell(30, 7, $c['pais_code'], 1, 0, 'C');
        $pdf->Cell(5, 7, "", 0, 0, 'C');

        //Buscar el destino de esta guia madre
        $guia = "SELECT codigo FROM tblcoldroom WHERE guia_madre = '" . $madre . "' UNION (SELECT codigo_unico FROM tblcoldrom_fincas WHERE guia_m = '" . $madre . "') limit 1";
        $buscarguia = mysqli_query($link, $guia) or die("Error consultando el origen");
        $filaguia = mysqli_fetch_array($buscarguia);

        //Seleccionar destino 
        //Si es finca autonoma se busca en la tabla detalle orden sino en la tabla de pedidos
        $guiaSql = "SELECT * FROM tblcoldrom_fincas WHERE guia_m = '" . $madre . "'";
        //echo $guiaSql;
        $guiaQuery = mysqli_query($link, $guiaSql) or die("Error buscando la guia madre de la finca autonoma");
        $guiaCant = mysqli_num_rows($guiaQuery);

        if ($guiaCant > 0) {
            $destinoSql = "SELECT cppais_envio  FROM tbldetalle_orden WHERE codigo = '" . $filaguia['codigo'] . "'";
            $buscardestino = mysqli_query($link, $destinoSql) or die("Error consultando el origen");
            $filadestino = mysqli_fetch_array($buscardestino);
            $destino = $filadestino['cppais_envio'];
        } else {
            $destinoSql = "SELECT destino  FROM tbletiquetasxfinca WHERE codigo = '" . $filaguia['codigo'] . "'";
            $buscardestino = mysqli_query($link, $destinoSql) or die("Error consultando el origen");
            $filadestino = mysqli_fetch_array($buscardestino);
            $destino = $filadestino['destino'];
        }

        //bUSCAR EL DAE DE ESTA FINCA para este destino
        $d = "SELECT dae,finicio,ffin FROM tbldae WHERE nombre_finca = '" . $row['finca'] . "' AND pais_dae = '" . $destino . "'";

        $e = mysqli_query($link, $d) or die("Error consultando datos de la finca");
        $dae = "";
        while ($row1 = mysqli_fetch_array($e)) {
            if ($row1['finicio'] <= $row['fecha_vuelo'] && $row1['ffin'] >= $row['fecha_vuelo']) {
                $dae = $row1['dae'];
            }
        }

        //Generando o consultando el nro. de invoice
        $verSql = "SELECT invoice FROM tblinvoice WHERE id_guia_madre='" . $madre . "' AND id_guia_hija='" . $row['guia_hija'] . "' AND fecha_vuelo='" . $row['fecha_vuelo'] . "' AND invoice_dae='" . $dae . "'";
        //echo $verSql;
        $verQuery = mysqli_query($link, $verSql) or die("Error consultando el invoice");
        $cantidad = mysqli_num_rows($verQuery);

        if ($cantidad == 0) {//Si la cantidad es cero genero el invoice de saa factura y lo inserto en la tabla de invoice
            //Obteniendo el ultimo invoice generado y sumandole 1
            $verSql1 = "SELECT invoice FROM tblinvoice order by invoice DESC LIMIT 1";
            $verQuery1 = mysqli_query($link, $verSql1) or die("Error consultando el invoice");
            $verrow1 = mysqli_fetch_array($verQuery1);
            $invoice = $verrow1['invoice'] + 1;

            //Insertando el nuevvo invoice generado
            $insertSql = "INSERT INTO tblinvoice (id_guia_madre,id_guia_hija,invoice,fecha_vuelo,invoice_dae) VALUES ('" . $madre . "','" . $row['guia_hija'] . "','" . $invoice . "','" . $row['fecha_vuelo'] . "','" . $dae . "')";
            $ejecutarSql = mysqli_query($link, $insertSql) or die("Error insertando el invoice");
        } else { //Si la cantidad es mayor que cero o sea 1, solo obtengo el invoice para mostrar
            //Obteniendo el ultimo invoice generado y guardado
            $verrow = mysqli_fetch_array($verQuery);
            $invoice = $verrow['invoice'];
        }

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(30, 7, str_pad($invoice, 10, "0", STR_PAD_LEFT), 1, 0, 'C');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Ln();
        $pdf->Cell(100, 7, "e-Blooms Direct, Inc.", 1, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(120, 7, "FOREIGN PURCHASER", 0, 0, 'L');
        $pdf->Cell(70, 7, "AWB #", 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(100, 7, "NAME: e-Blooms Direct, Inc.", 1, 0, 'L');
        $pdf->Cell(20, 7, "", 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(65, 7, $madre, 1, 0, 'L');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(100, 7, "ADDRESS: 9440 NW 12 St, Suite 202 Miami, Fl 33172 USA", 1, 0, 'L');
        $pdf->Cell(20, 7, "", 0, 0, 'C');
        $pdf->Cell(20, 7, "HAWB #", 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(100, 7, "PHONE: 786-542-9626, 786-542-9627", 1, 0, 'L');
        $pdf->Cell(20, 7, "", 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(65, 7, $row['guia_hija'], 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Ln();
        $pdf->Cell(100, 7, "FAX: # 1-305-675-0328", 1, 0, 'L');
        $pdf->Cell(20, 7, "", 0, 0, 'C');
        $pdf->Cell(70, 7, "AIR LINE & FLIGHT", 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(120, 7, "", 0, 0, 'C');
        $pdf->Cell(65, 7, $row['airline'], 1, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(70, 7, "COUNTRY OF ORIGEN", 0, 0, 'L');
        $pdf->Cell(42, 7, "DAE NO.", 0, 0, 'C');
        $pdf->Cell(40, 7, "ADD CSE #	", 0, 0, 'C');
        $pdf->Ln();

        //obtener  los items de la factura comercial
        $SQL = "SELECT item FROM tblcoldroom  WHERE salida='Si' AND guia_madre = '" . $madre . "' AND fecha_vuelo='" . $row['fecha_vuelo'] . "' AND fecha_entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $row['finca'] . "' AND guia_hija='" . $row['guia_hija'] . "' UNION  (SELECT item FROM tblcoldrom_fincas  WHERE guia_m = '" . $madre . "' AND vuelo='" . $row['fecha_vuelo'] . "' AND entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $row['finca'] . "' AND guia_h='" . $row['guia_hija'] . "' ) ORDER BY item limit 1";

        $QUERY = mysqli_query($link, $SQL) or die("Error selecionando el item");
        $ROW = mysqli_fetch_array($QUERY);

        $SQL1 = "SELECT origen FROM tblproductos  WHERE id_item='" . $ROW['item'] . "'";
        $QUERY1 = mysqli_query($link, $SQL1) or die("Error selecionando el origen");
        $ROW1 = mysqli_fetch_array($QUERY1);
        $pdf->Cell(50, 7, $ROW1['origen'], 1, 0, 'C');

        $pdf->Cell(20, 7, "", 0, 0, 'C');

        $pdf->Cell(40, 7, $dae, 1, 0, 'C');

        $pdf->Cell(10, 7, "", 0, 0, 'C');
        $pdf->Cell(40, 7, "", 1, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(60, 7, "FIXED PRICE/VENTA DIRECTA [ ]", 0, 0, 'L');
        //$pdf->Cell(120,7,"THE FLOWERS AND PLANTS ON THIS INVOICE WERE WHOLLY GROWN IN ECUADOR",1,0,'L');

        $pdf->Ln();

        $pdf->Tabla1();

        //POR CADA RECETA UTILIZADA EN EL PEDIDO
        //seleccionar diferentes items
        $sql2 = "SELECT DISTINCT item FROM tblcoldroom  WHERE salida='Si' AND guia_madre = '" . $madre . "' AND fecha_vuelo='" . $row['fecha_vuelo'] . "' AND fecha_entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $row['finca'] . "' AND guia_hija='" . $row['guia_hija'] . "'  UNION (SELECT DISTINCT item FROM tblcoldrom_fincas WHERE guia_m = '" . $madre . "' AND vuelo='" . $row['fecha_vuelo'] . "' AND entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca ='" . $row['finca'] . "' AND guia_h='" . $row['guia_hija'] . "') ORDER BY item";
        $query2 = mysqli_query($link, $sql2) or die("Error selecionando las fincas");

        $total = 0;
        $box_full = 0;
        $box_medio = 0;
        $box_cuarto = 0;
        $box_octavo = 0;
        $box_dieciseis = 0;
        $box_hamp = 0;
        $box_procona = 0;

        //Recorra cada item diferente para escoger sus datos en cada caso
        while ($row2 = mysqli_fetch_array($query2)) {
            $pdf->SetFont('Arial', 'B', 8);
            //Cantidad de item con ese codigo
            $g = "SELECT COUNT(*) as cantidad FROM tblcoldroom  WHERE salida='Si' AND guia_madre = '" . $madre . "' AND fecha_vuelo='" . $row['fecha_vuelo'] . "' AND fecha_entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $row['finca'] . "' AND guia_hija='" . $row['guia_hija'] . "' AND item='" . $row2['item'] . "'";
            $h = mysqli_query($link, $g) or die("Error selecionando la cantidad");
            $fila11 = mysqli_fetch_array($h);
            //imprimiendo cantidad                                                        
            $TOTALPIECES += $fila11[0];
            $pdf->Cell(10, 5, $fila11[0], 1, 0, 'C');

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $g1 = "SELECT tblproductos.*,tblboxtype.tipo_Box FROM tblboxtype INNER JOIN tblproductos ON tblboxtype.id_Box = tblproductos.boxtype where tblproductos.id_item='" . $row2['item'] . "'";
            $h1 = mysqli_query($link, $g1) or die("Error selecionando las fincas");
            $fila2 = mysqli_fetch_array($h1);
            //agregando boxtype
            $pdf->Cell(12, 5, $fila2['tipo_Box'], 1, 0, 'C');

            //voy viendo el Box y voy sumando la cantidad por cada uno
            if ($fila2['tipo_Box'] == 'FULL')
                $box_full += $fila11[0];
            if ($fila2['tipo_Box'] == 'HLF')
                $box_medio += $fila11[0];
            if ($fila2['tipo_Box'] == 'QBX')
                $box_cuarto += $fila11[0];
            if ($fila2['tipo_Box'] == '1/8')
                $box_octavo += $fila11[0];
            if ($fila2['tipo_Box'] == '1/16')
                $box_dieciseis += $fila11[0];
            if ($fila2['tipo_Box'] == 'HAMP')
                $box_hamp += $fila11[0];
            if ($fila2['tipo_Box'] == 'PRCN')
                $box_procona += $fila11[0];

            //agregando gen descripction
            $pdf->Cell(184, 5, $fila2['gen_desc'], 1, 0);

            //consultando receta
            $kk = "SELECT tbldetallereceta.* FROM tbldetallereceta INNER JOIN tblproductos ON tbldetallereceta.id_receta = tblproductos.id_receta WHERE tblproductos.id_item='" . $row2['item'] . "'";
            $ll = mysqli_query($link, $kk) or die("Error consultando el hts del item");

            //recorro todos los detalles de la receta del item
            //agregando gen descripction
            $packanterior = ""; //esta variable tendra el pack anterior del item de receta que si tiene pack

            while ($rowdetallereceta = mysqli_fetch_array($ll)) {
                $pdf->Ln();
                $pdf->Cell(10, 5, "", 1, 0, 'C');
                $pdf->Cell(12, 5, "", 1, 0, 'C');
                $pdf->Cell(25, 5, "", 1, 0, 'C');
                $pdf->CellFitSpace(40, 5, $rowdetallereceta['producto'], 1, 0, 'C');
                //agregando SPI
                $pdf->Cell(8, 5, 'A', 1, 0, 'C');
                //agregando SPI
                $pdf->CellFitSpace(20, 5, $rowdetallereceta['hts'], 1, 0, 'C');
                //agregando nandina
                $pdf->CellFitSpace(20, 5, $rowdetallereceta['nandina'], 1, 0, 'C');

                //agregando pack
                if ($rowdetallereceta['pack'] != "") {
                    $packanterior = $rowdetallereceta['pack'];
                }
                $pdf->Cell(14, 5, $packanterior, 1, 0, 'C');

                //agregando cantidad
                $pdf->CellFitSpace(14, 5, $rowdetallereceta['cantidad'], 1, 0, 'C');

                //calculo el producto de pieces*STEMS/BUNCH	*cantidad
                $totalstem = $fila11[0] * $rowdetallereceta['cantidad'] * $packanterior;
                $pdf->Cell(15, 5, $totalstem, 1, 0, 'C');

                $pdf->Cell(14, 5, $rowdetallereceta['stem'], 1, 0, 'C');

                //calculo total price
                $totalPrice = $totalstem * $rowdetallereceta['stem'];
                $total += $totalPrice;
                $pdf->Cell(14, 5, $totalPrice, 1, 0, 'C');
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            }
            $pdf->Ln();
        }
        //$pdf->Ln();
        $pdf->Cell(163, 5, "", 0, 0, 'C');
        $pdf->Cell(29, 7, "Total USD: ", 1, 0, 'C');
        $pdf->Cell(14, 7, number_format($total, 2), 1, 0, 'C');
        $pdf->Ln();
        $pdf->Cell(190, 7, "PIECES DESCRIPTION:", 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(30, 7, "TOTAL PIECES:", 0, 0, 'L');
        $pdf->Cell(15, 7, $TOTALPIECES, 0, 0, 'L');
        $pdf->Ln();

        $pdf->Tabla2();
        $valor_full = $box_full;
        $pdf->Cell(25, 5, $box_full, 1, 0, 'C'); //Full 1/1

        $valor_medio = $box_medio * 0.5;
        $pdf->Cell(25, 5, $box_medio, 1, 0, 'C'); //Half 1/2

        $valor_cuarto = $box_cuarto * 0.25;
        $pdf->Cell(25, 5, $box_cuarto, 1, 0, 'C'); //Qtrs  1/4

        $valor_octavo = $box_octavo * 0.125;
        $pdf->Cell(20, 5, $box_octavo, 1, 0, 'C'); //Sixth 1/6

        $valor_dieciseis = $box_dieciseis * 0.0625;
        $pdf->Cell(25, 5, $box_dieciseis, 1, 0, 'C'); //Eight  1/8

        $pdf->Cell(20, 5, $box_hamp, 1, 0, 'C'); //Humpers

        $pdf->Cell(20, 5, $box_procona, 1, 0, 'C'); //Wet pack
        $total_box = $valor_full + $valor_medio + $valor_cuarto + $valor_octavo + $valor_dieciseis + $box_hamp + $box_procona;
        $pdf->Cell(25, 5, $total_box, 1, 0, 'C'); //Total full Box

        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFontSize(14);
        $pdf->SetTextColor(255, 0, 0);
        $pdf->Cell(200, 7, "\"Goods qualify under Generalized system of preferences GSP\"", 0, 0, 'C');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(135, 7, "NAME AND TITLE OF PERSON PREPARING INVOICE:", 0, 0, 'L');
        $pdf->Cell(50, 7, "FREIGHT FORWARDER:", 0, 0, 'L');
        $pdf->Ln();
        //Bscar el contactpo de esa finca
        $sentencia = "SELECT contacto FROM tblfinca WHERE nombre='" . utf8_decode($row['finca']) . "'";
        $consulta = mysqli_query($link, $sentencia)or die("Error consultando contacto");
        $resultado = mysqli_fetch_array($consulta);
        $pdf->Cell(135, 7, $resultado['contacto'], 1, 0, 'C');

        //buscar un codigo de la guia madre para localizar con que agencia viajo
        $AAA = "SELECT codigo FROM tblcoldroom WHERE guia_madre='" . $madre . "' UNION (SELECT codigo_unico FROM tblcoldrom_fincas WHERE guia_m='" . $madre . "') limit 1";
        $bbb = mysqli_query($link, $AAA)or die("Error consultando el codigo");
        $result = mysqli_fetch_array($bbb);
        //BUSCAR LA AGENCIA DE ESTE VUELO
        $AA = "SELECT DISTINCT agencia FROM tbletiquetasxfinca WHERE codigo='" . $result['codigo'] . "'";
        $bb = mysqli_query($link, $AA)or die("Error consultando agencia");
        $cc = mysqli_fetch_array($bb);
        $pdf->Cell(50, 7, $cc['agencia'], 1, 0, 'C');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Cell(135, 20, "", 1, 0, 'L');
        $pdf->Cell(50, 20, "", 1, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(135, 7, "Customs Use Only", 0, 0, 'C');
        $pdf->Cell(50, 7, "USA, APHIS P.P.Q. Use Only", 0, 0, 'C');

        $TOTAL = 0;
        $TOTALPIECES = 0;
    }

    //generando el pdf
    $pdf->Output("Guias Asignadas.pdf", "I");
}
?>


