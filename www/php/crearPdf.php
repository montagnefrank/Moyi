<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");
require('fpdf/fpdf.php');
require_once('barcode.inc.php');

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

$sql = $_SESSION["sql"];
$madre = $_SESSION["madre"];
$hija = $_SESSION["hija"];
$finca = $_SESSION["finca"];
$todo = $_SESSION["todo"];


class PDF extends FPDF {

    //Cabecera de página
    function Header() {
        //Logo
        $this->Image('../images/logo.png', 60, 0, 80);
        //Arial bold 15
        $this->SetFont('Arial', 'B', 8);
        $this->Ln(20);
    }

    //Pie de página
    function Footer() {
        //Posición: a 1,5 cm del final
        $this->SetY(-15);
        //Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        //Número de página
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
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
$cont = 0;
$total = 0;

//Si algn filtro tiene valor verificamos cual es
if ($todo == 1) { //Si el filtro todo tiene valor
    $sql = "SELECT DISTINCT finca,guia_madre,fecha_vuelo, fecha_entrega, servicio FROM tblcoldroom WHERE salida='Si' AND (guia_madre != '0' OR guia_hija != '0') 
                      UNION 
                    (SELECT DISTINCT finca,guia_m,vuelo, entrega, servicio FROM tblcoldrom_fincas WHERE guia_m != '0' OR guia_h != '0')
                    ORDER BY finca";
    $query = mysqli_query($link, $sql) or die("Error seleccionado datos generales del psi");

    //Por cada guia madre diferente voy creando paginas en pdf
    while ($row = mysqli_fetch_array($query)) {

        //Creando la pagina del pdf
        $pdf->AddPage();
        $pdf->AliasNbPages();
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Cell(180, 7, "REPORTE GENERAL DE GUIAS ASIGNADAS", 0, 0, 'C');
        $pdf->Ln();
        $pdf->Ln();

        //Mostrando los datos generales de la guia madre
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(45, 7, "", 0, 0, 'C');
        $pdf->Cell(45, 7, "AWB: ", 0, 0, 'L');
        $pdf->Cell(45, 7, $row['guia_madre'], 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(45, 7, "", 0, 0, 'C');
        $pdf->Cell(45, 7, "FLIGHT DATE:", 0, 0, 'L');
        $pdf->Cell(45, 7, $row['fecha_vuelo'], 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(45, 7, "", 0, 0, 'C');
        $pdf->Cell(45, 7, "DELIVERY DATE: ", 0, 0, 'C');
        $pdf->Cell(45, 7, $row['fecha_entrega'], 0, 0, 'L');
        $pdf->Ln();
        $pdf->Cell(45, 7, "", 0, 0, 'C');
        $pdf->Cell(45, 7, "SERVICE: ", 0, 0, 'L');
        $pdf->Cell(180, 7, $row['servicio'], 0, 0, 'L');
        $pdf->Ln();
        $pdf->Ln();

        //Seleccionando las fincas y guias hijas diferentes
        $sql1 = "SELECT DISTINCT finca, guia_hija FROM tblcoldroom  WHERE finca = '" . $row['finca'] . "' AND salida='Si' AND guia_madre = '" . $row['guia_madre'] . "' AND fecha_vuelo='" . $row['fecha_vuelo'] . "' AND fecha_entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' 
                           UNION
                                   SELECT DISTINCT finca, guia_h FROM tblcoldrom_fincas  WHERE finca = '" . $row['finca'] . "' AND guia_m = '" . $row['guia_madre'] . "' AND vuelo='" . $row['fecha_vuelo'] . "' AND entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "'
                                   ORDER BY finca, guia_hija";
        $query1 = mysqli_query($link, $sql1) or die("Error selecionando las fincas y guias hijas");


        //Por cada finca y guia hija diferente muestro sus datos
        while ($row1 = mysqli_fetch_array($query1)) {
            $pdf->Cell(140, 7, "FARM: " . utf8_decode($row1['finca']), 0, 0, 'L');
            $pdf->Cell(40, 7, "HAWB#: " . $row1['guia_hija'], 0, 0, 'C');
            $pdf->Ln();

            //Encabezado de la tabla
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(10, 7, "", 1, 0, 'C');
            $pdf->Cell(70, 7, "", 1, 0, 'C');
            $pdf->Cell(10, 7, "", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "Stems", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "Stems", 1, 0, 'C');
            $pdf->CellFitSpace(12, 7, "Weight", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "1/16B", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "1/8EB", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "QBX", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "HLF", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "FULL", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "TOTAL", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "TOTAL", 1, 0, 'C');
            $pdf->Ln();
            $pdf->Cell(10, 7, "ITEM", 1, 0, 'C');
            $pdf->Cell(70, 7, "PRODUCT NAME", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "PACK", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "Bunch", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "Box", 1, 0, 'C');
            $pdf->CellFitSpace(12, 7, "Kg", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "QTY", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "QTY", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "QTY", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "QTY", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "QTY", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "STEMS", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "Kg", 1, 0, 'C');
            $pdf->Ln();

            //seleccionar diferentes items
            $sql2 = "SELECT DISTINCT item FROM tblcoldroom  WHERE salida='Si' AND guia_madre = '" . $row['guia_madre'] . "' AND fecha_vuelo='" . $row['fecha_vuelo'] . "' AND fecha_entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $row1['finca'] . "' AND guia_hija='" . $row1['guia_hija'] . "' 
                                   ORDER BY item";
//                        $sql2 =   "SELECT DISTINCT item FROM tblcoldroom  WHERE salida='Si' AND guia_madre = '".$row['guia_madre']."' AND fecha_vuelo='".$row['fecha_vuelo']."' AND fecha_entrega='".$row['fecha_entrega']."' AND servicio='".$row['servicio']."' AND finca = '".$row1['finca']."' AND guia_hija='".$row1['guia_hija']."' AND item !='10000' 
//                                   ORDER BY item";
            $query2 = mysqli_query($link, $sql2) or die("Error selecionando los items");

            //Recorra cada item diferente para escoger sus datos en cada caso
            while ($row2 = mysqli_fetch_array($query2)) {
                //inicializando las variables
                $box_full = 0;
                $box_medio = 0;
                $box_cuarto = 0;
                $box_octavo = 0;
                $box_dieciseis = 0;


                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(10, 7, $row2['item'], 1, 0, 'C');
                ////////////////////////////////////////////////////////////////////////////////////////
                //Cantidad de item con ese codigo
                $g = "SELECT COUNT(*) as cantidad FROM tblcoldroom  WHERE salida='Si' AND guia_madre = '" . $madre . "' AND fecha_vuelo='" . $row['fecha_vuelo'] . "' AND fecha_entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $row1['finca'] . "' AND guia_hija='" . $row1['guia_hija'] . "' AND item='" . $row2['item'] . "'";
                $h = mysqli_query($link, $g) or die("Error selecionando la cantidad");
                $fila11 = mysqli_fetch_array($h);

                $g1 = "SELECT tblproductos.*,tblboxtype.tipo_Box FROM tblboxtype INNER JOIN tblproductos ON tblboxtype.id_Box = tblproductos.boxtype where tblproductos.id_item='" . $row2['item'] . "'";
                $h1 = mysqli_query($link, $g1) or die("Error selecionando las fincas");
                $fila2 = mysqli_fetch_array($h1);

                //voy viendo el Box y voy sumando la cantidad por cada uno
                if ($fila2['tipo_Box'] == 'FULL')
                    $box_full = $fila11[0];
                if ($fila2['tipo_Box'] == 'HLF')
                    $box_medio = $fila11[0];
                if ($fila2['tipo_Box'] == 'QBX')
                    $box_cuarto = $fila11[0];
                if ($fila2['tipo_Box'] == '1/8')
                    $box_octavo = $fila11[0];
                if ($fila2['tipo_Box'] == '1/16')
                    $box_dieciseis = $fila11[0];

                //Para cada item escoger la receta
                $pdf->CellFitSpace(70, 7, $fila2['prod_descripcion'], 1, 0, 'L');
                $pdf->CellFitSpace(10, 7, $fila2['pack'], 1, 0, 'C');

                //recorro los detallesd receta para sumar las qcatidades=bunch
                //consultando receta
                if ($row2['item'] == '10000') {
                    $kk = "SELECT sum(cantidad) as cant FROM tbldetallereceta INNER JOIN tblproductos ON tbldetallereceta.id_receta = tblproductos.id_receta WHERE tblproductos.id_item='" . $row2['item'] . "'";
                } else {
                    $kk = "SELECT sum(cantidad) as cant FROM tbldetallereceta INNER JOIN tblproductos ON tbldetallereceta.id_receta = tblproductos.id_receta WHERE tblproductos.id_item='" . $row2['item'] . "' and tbldetallereceta.producto!='CERAMIC VASE'";
                }
                $ll = mysqli_query($link, $kk) or die("Error consultando el hts del item");
                $cant = mysqli_fetch_row($ll);
                $pdf->CellFitSpace(10, 7, $cant[0], 1, 0, 'C');

                //box
                $box = $fila2['pack'] * $cant[0];
                $pdf->CellFitSpace(10, 7, $box, 1, 0, 'C');

                //peso
                $pdf->CellFitSpace(12, 7, $fila2['wheigthKg'], 1, 0, 'C');

                //$valor_dieciseis=$box_dieciseis*0.0625;
                $valor_dieciseis = $box_dieciseis;
                $cont1 += $valor_dieciseis;
                $pdf->CellFitSpace(10, 7, $valor_dieciseis, 1, 0, 'C');

//                            $valor_octavo=$box_octavo*0.125;
                $valor_octavo = $box_octavo;
                $cont2 += $valor_octavo;
                $pdf->CellFitSpace(10, 7, $valor_octavo, 1, 0, 'C');

                //$valor_cuarto=$box_cuarto*0.25;
                $valor_cuarto = $box_cuarto;
                $cont3 += $valor_cuarto;
                $pdf->CellFitSpace(10, 7, $valor_cuarto, 1, 0, 'C'); //Qtrs  1/4
//                            $valor_medio=$box_medio*0.5;
                $valor_medio = $box_medio;
                $cont4 += $valor_medio;
                $pdf->CellFitSpace(10, 7, $valor_medio, 1, 0, 'C'); //Half 1/2

                $valor_full = $box_full;
                $cont5 += $valor_full;
                $pdf->CellFitSpace(10, 7, $valor_full, 1, 0, 'C'); //Full 1/1
                //total stems
                $totalstems = $fila11[0] * $cant[0] * $fila2['pack'];
                $cont6 += $totalstems;
                $pdf->CellFitSpace(10, 7, $totalstems, 1, 0, 'C');

                //total kg
                $totalkg = $fila11[0] * $fila2['wheigthKg'];
                $cont7 += $totalkg;
                $pdf->CellFitSpace(10, 7, $totalkg, 1, 0, 'C');
                $pdf->Ln();
            }

            //Agregar Subtotal por item
            $pdf->Cell(110, 7, "SUBTOTAL:", 1, 0, 'R');
            $pdf->CellFitSpace(12, 7, "", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, $cont1, 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, $cont2, 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, $cont3, 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, $cont4, 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, $cont5, 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, $cont6, 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, $cont7, 1, 0, 'C');
            $pdf->Ln();

            //Actualizar contadores totales
            $totalcont_1 += $cont1;
            $totalcont_2 += $cont2;
            $totalcont_3 += $cont3;
            $totalcont_4 += $cont4;
            $totalcont_5 += $cont5;
            $totalcont_6 += $cont6;
            $totalcont_7 += $cont7;

            //limpio los subtotales
            $cont1 = 0;
            $cont2 = 0;
            $cont3 = 0;
            $cont4 = 0;
            $cont5 = 0;
            $cont6 = 0;
            $cont7 = 0;


            //Aumentar el tamaño de fuente de la proxima finca 
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Ln();
            $pdf->Ln();
        }

        //darle color al total
        $pdf->SetFillColor(255, 0, 0);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFont('Arial', 'B', 12);
        //Agregar Total general
        $pdf->CellFitSpace(85, 7, "", 1, 0, 'R', true);
        $pdf->CellFitSpace(15, 7, "1/16B", 1, 0, 'C', true);
        $pdf->CellFitSpace(15, 7, "1/8EB", 1, 0, 'C', true);
        $pdf->CellFitSpace(15, 7, "QBX", 1, 0, 'C', true);
        $pdf->CellFitSpace(15, 7, "HLF", 1, 0, 'C', true);
        $pdf->CellFitSpace(15, 7, "FULL", 1, 0, 'C', true);
        $pdf->CellFitSpace(20, 7, "T.Stems", 1, 0, 'C', true);
        $pdf->CellFitSpace(15, 7, "T.Kg", 1, 0, 'C', true);
        $pdf->Ln();
        $pdf->CellFitSpace(85, 7, "TOTAL:", 1, 0, 'R', true);
        $pdf->CellFitSpace(15, 7, $totalcont_1, 1, 0, 'C', true);
        $pdf->CellFitSpace(15, 7, $totalcont_2, 1, 0, 'C', true);
        $pdf->CellFitSpace(15, 7, $totalcont_3, 1, 0, 'C', true);
        $pdf->CellFitSpace(15, 7, $totalcont_4, 1, 0, 'C', true);
        $pdf->CellFitSpace(15, 7, $totalcont_5, 1, 0, 'C', true);
        $pdf->CellFitSpace(20, 7, $totalcont_6, 1, 0, 'C', true);
        $pdf->CellFitSpace(15, 7, $totalcont_7, 1, 0, 'C', true);
        $pdf->Ln();
        $pdf->CellFitSpace(85, 7, "TOTAL PIECES:", 1, 0, 'R', true);
        $pdf->CellFitSpace(75, 7, $totalcont_1 + $totalcont_2 + $totalcont_3 + $totalcont_4 + $totalcont_5, 1, 0, 'C', true);
        $pdf->Ln();
        //generando el pdf
        $pdf->Output("Guias Asignadas.pdf", "I");
    }
} else {

    /*     * ***** si el reporte es filtrado por guia madre ******** */
    if ($madre != '') { //Si el filtro guia madre tiene valor
        $sql = "SELECT DISTINCT fecha_vuelo, fecha_entrega, servicio FROM tblcoldroom WHERE salida='Si' AND guia_madre = '" . $madre . "' 
                                  UNION
                                  (SELECT DISTINCT vuelo, entrega, servicio FROM tblcoldrom_fincas WHERE guia_m = '" . $madre . "')
                                  ORDER BY fecha_vuelo, fecha_entrega, servicio";
        $query = mysqli_query($link, $sql) or die("Error seleccionando datos generales");
        while ($row = mysqli_fetch_array($query)) {

            //Creando la pagina del pdf
            $pdf->AddPage();
            $pdf->AliasNbPages();
            $pdf->SetFont('Arial', 'B', 20);
            $pdf->Cell(180, 7, "REPORTE POR GUIA MADRE", 0, 0, 'C');
            $pdf->Ln();
            $pdf->Ln();

            //Mostrando los datos generales de la guia madre
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(45, 7, "", 0, 0, 'C');
            $pdf->Cell(45, 7, "AWB: ", 0, 0, 'L');
            $pdf->Cell(45, 7, $madre, 0, 0, 'L');
            $pdf->Ln();
            $pdf->Cell(45, 7, "", 0, 0, 'C');
            $pdf->Cell(45, 7, "FLIGHT DATE:", 0, 0, 'L');
            $pdf->Cell(45, 7, $row['fecha_vuelo'], 0, 0, 'L');
            $pdf->Ln();
            $pdf->Cell(45, 7, "", 0, 0, 'C');
            $pdf->Cell(45, 7, "DELIVERY DATE: ", 0, 0, 'C');
            $pdf->Cell(45, 7, $row['fecha_entrega'], 0, 0, 'L');
            $pdf->Ln();
            $pdf->Cell(45, 7, "", 0, 0, 'C');
            $pdf->Cell(45, 7, "SERVICE: ", 0, 0, 'L');
            $pdf->Cell(180, 7, $row['servicio'], 0, 0, 'L');
            $pdf->Ln();
            $pdf->Ln();

            //Seleccionando las fincas y guias hijas diferentes
            $sql1 = "SELECT DISTINCT finca, guia_hija FROM tblcoldroom  WHERE salida='Si' AND guia_madre = '" . $madre . "' AND fecha_vuelo='" . $row['fecha_vuelo'] . "' AND fecha_entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' 
                           UNION
                                   SELECT DISTINCT finca, guia_h FROM tblcoldrom_fincas  WHERE guia_m = '" . $madre . "' AND vuelo='" . $row['fecha_vuelo'] . "' AND entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' 
                                   ORDER BY finca, guia_hija";

            $query1 = mysqli_query($link, $sql1) or die("Error selecionando las fincas");

            //Por cada finca y guia hija diferente muestro sus datos
            while ($row1 = mysqli_fetch_array($query1)) {
                $pdf->Cell(140, 7, "FARM: " . utf8_decode($row1['finca']), 0, 0, 'L');
                $pdf->Cell(40, 7, "HAWB#: " . $row1['guia_hija'], 0, 0, 'C');
                $pdf->Ln();

                //Encabezado de la tabla
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(10, 7, "", 1, 0, 'C');
                $pdf->Cell(70, 7, "", 1, 0, 'C');
                $pdf->Cell(10, 7, "", 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, "Stems", 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, "Stems", 1, 0, 'C');
                $pdf->CellFitSpace(12, 7, "Weight", 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, "1/16B", 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, "1/8EB", 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, "QBX", 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, "HLF", 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, "FULL", 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, "TOTAL", 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, "TOTAL", 1, 0, 'C');
                $pdf->Ln();
                $pdf->Cell(10, 7, "ITEM", 1, 0, 'C');
                $pdf->Cell(70, 7, "PRODUCT NAME", 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, "PACK", 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, "Bunch", 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, "Box", 1, 0, 'C');
                $pdf->CellFitSpace(12, 7, "Kg", 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, "QTY", 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, "QTY", 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, "QTY", 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, "QTY", 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, "QTY", 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, "STEMS", 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, "Kg.", 1, 0, 'C');
                $pdf->Ln();

                //seleccionar diferentes items
                $sql2 = "SELECT DISTINCT item FROM tblcoldroom  WHERE salida='Si' AND guia_madre = '" . $madre . "' AND fecha_vuelo='" . $row['fecha_vuelo'] . "' AND fecha_entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $row1['finca'] . "' AND guia_hija='" . $row1['guia_hija'] . "' UNION ALL SELECT DISTINCT item FROM tblcoldrom_fincas WHERE guia_m = '" . $madre . "' AND vuelo='" . $row['fecha_vuelo'] . "' AND entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $row1['finca'] . "' AND guia_h='" . $row1['guia_hija'] . "' ";
//                        $sql2 =   "SELECT DISTINCT item FROM tblcoldroom  WHERE salida='Si' AND guia_madre = '".$madre."' AND fecha_vuelo='".$row['fecha_vuelo']."' AND fecha_entrega='".$row['fecha_entrega']."' AND servicio='".$row['servicio']."' AND finca = '".$row1['finca']."' AND guia_hija='".$row1['guia_hija']."' AND item !='10000' 
//                                   ORDER BY item";
                $query2 = mysqli_query($link, $sql2) or die("Error selecionando los items");

                //Recorra cada item diferente para escoger sus datos en cada caso
                while ($row2 = mysqli_fetch_array($query2)) {
                    //inicializando las variables
                    $box_full = 0;
                    $box_medio = 0;
                    $box_cuarto = 0;
                    $box_octavo = 0;
                    $box_dieciseis = 0;


                    $pdf->SetFont('Arial', 'B', 8);
                    $pdf->CellFitSpace(10, 7, $row2['item'], 1, 0, 'C');
                    ////////////////////////////////////////////////////////////////////////////////////////
                    //Cantidad de item con ese codigo
                    $g = "SELECT COUNT(*) as cantidad FROM tblcoldroom  WHERE salida='Si' AND guia_madre = '" . $madre . "' AND fecha_vuelo='" . $row['fecha_vuelo'] . "' AND fecha_entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $row1['finca'] . "' AND guia_hija='" . $row1['guia_hija'] . "' AND item='" . $row2['item'] . "' UNION ALL SELECT COUNT(*) FROM tblcoldrom_fincas WHERE guia_m = '" . $madre . "' AND guia_h='" . $row1['guia_hija'] . "' AND item='" . $row2['item'] . "' AND vuelo='" . $row['fecha_vuelo'] . "' AND entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $row1['finca'] . "' ORDER BY cantidad DESC";
                    $h = mysqli_query($link, $g) or die("Error selecionando la cantidad");
                    $fila11 = mysqli_fetch_array($h);

                    $g1 = "SELECT tblproductos.*,tblboxtype.tipo_Box FROM tblboxtype INNER JOIN tblproductos ON tblboxtype.id_Box = tblproductos.boxtype where tblproductos.id_item='" . $row2['item'] . "'";
                    $h1 = mysqli_query($link, $g1) or die("Error selecionando las fincas");
                    $fila2 = mysqli_fetch_array($h1);

                    //voy viendo el Box y voy sumando la cantidad por cada uno
                    if ($fila2['tipo_Box'] == 'FULL')
                        $box_full = $fila11[0];
                    if ($fila2['tipo_Box'] == 'HLF')
                        $box_medio = $fila11[0];
                    if ($fila2['tipo_Box'] == 'QBX')
                        $box_cuarto = $fila11[0];
                    if ($fila2['tipo_Box'] == '1/8')
                        $box_octavo = $fila11[0];
                    if ($fila2['tipo_Box'] == '1/16')
                        $box_dieciseis = $fila11[0];

                    //Para cada item escoger la receta
                    $pdf->CellFitSpace(70, 7, $fila2['prod_descripcion'], 1, 0, 'L');
                    $pdf->CellFitSpace(10, 7, $fila2['pack'], 1, 0, 'C');

                    //recorro los detallesd receta para sumar las qcatidades=bunch
                    //consultando receta
                    if ($row2['item'] == '10000') {
                        $kk = "SELECT sum(cantidad) as cant FROM tbldetallereceta INNER JOIN tblproductos ON tbldetallereceta.id_receta = tblproductos.id_receta WHERE tblproductos.id_item='" . $row2['item'] . "'";
                    } else {
                        $kk = "SELECT sum(cantidad) as cant FROM tbldetallereceta INNER JOIN tblproductos ON tbldetallereceta.id_receta = tblproductos.id_receta WHERE tblproductos.id_item='" . $row2['item'] . "' and tbldetallereceta.producto!='CERAMIC VASE'";
                    }
//$kk = "SELECT sum(cantidad) as cant FROM tbldetallereceta INNER JOIN tblproductos ON tbldetallereceta.id_receta = tblproductos.id_receta WHERE tblproductos.id_item='".$row2['item']."' AND tbldetallereceta.producto!='CERAMIC VASE'";
                    $ll = mysqli_query($link, $kk) or die("Error consultando el hts del item");
                    $cant = mysqli_fetch_row($ll);
                    $pdf->CellFitSpace(10, 7, $cant[0], 1, 0, 'C');

                    //box
                    $box = $fila2['pack'] * $cant[0];
                    $pdf->CellFitSpace(10, 7, $box, 1, 0, 'C');

                    //peso
                    $pdf->CellFitSpace(12, 7, $fila2['wheigthKg'], 1, 0, 'C');

                    //$valor_dieciseis=$box_dieciseis*0.0625;
                    $valor_dieciseis = $box_dieciseis;
                    $cont1 += $valor_dieciseis;
                    $pdf->CellFitSpace(10, 7, $valor_dieciseis, 1, 0, 'C');

                    //                            $valor_octavo=$box_octavo*0.125;
                    $valor_octavo = $box_octavo;
                    $cont2 += $valor_octavo;
                    $pdf->CellFitSpace(10, 7, $valor_octavo, 1, 0, 'C');

                    //$valor_cuarto=$box_cuarto*0.25;
                    $valor_cuarto = $box_cuarto;
                    $cont3 += $valor_cuarto;
                    $pdf->CellFitSpace(10, 7, $valor_cuarto, 1, 0, 'C'); //Qtrs  1/4
                    //                            $valor_medio=$box_medio*0.5;
                    $valor_medio = $box_medio;
                    $cont4 += $valor_medio;
                    $pdf->CellFitSpace(10, 7, $valor_medio, 1, 0, 'C'); //Half 1/2

                    $valor_full = $box_full;
                    $cont5 += $valor_full;
                    $pdf->CellFitSpace(10, 7, $valor_full, 1, 0, 'C'); //Full 1/1
                    //total stems
                    $totalstems = $fila11[0] * $cant[0] * $fila2['pack'];
                    $cont6 += $totalstems;
                    $pdf->CellFitSpace(10, 7, $totalstems, 1, 0, 'C');

                    //total kg
                    $totalkg = $fila11[0] * $fila2['wheigthKg'];
                    $cont7 += $totalkg;
                    $pdf->CellFitSpace(10, 7, $totalkg, 1, 0, 'C');
                    $pdf->Ln();
                }

                //Agregar Subtotal por item
                $pdf->CellFitSpace(110, 7, "SUBTOTAL:", 1, 0, 'R');
                $pdf->CellFitSpace(12, 7, "", 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, $cont1, 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, $cont2, 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, $cont3, 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, $cont4, 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, $cont5, 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, $cont6, 1, 0, 'C');
                $pdf->CellFitSpace(10, 7, $cont7, 1, 0, 'C');
                $pdf->Ln();

                //Actualizar contadores totales
                $totalcont_1 += $cont1;
                $totalcont_2 += $cont2;
                $totalcont_3 += $cont3;
                $totalcont_4 += $cont4;
                $totalcont_5 += $cont5;
                $totalcont_6 += $cont6;
                $totalcont_7 += $cont7;

                //limpio los subtotales
                $cont1 = 0;
                $cont2 = 0;
                $cont3 = 0;
                $cont4 = 0;
                $cont5 = 0;
                $cont6 = 0;
                $cont7 = 0;


                //Aumentar el tamaño de fuente de la proxima finca 
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Ln();
                $pdf->Ln();
            }
        }
        //darle color al total
        $pdf->SetFillColor(255, 0, 0);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 12);
        //Agregar Total general
        $pdf->CellFitSpace(85, 7, "", 1, 0, 'R', true);
        $pdf->CellFitSpace(15, 7, "1/16B", 1, 0, 'C', true);
        $pdf->CellFitSpace(15, 7, "1/8EB", 1, 0, 'C', true);
        $pdf->CellFitSpace(15, 7, "QBX", 1, 0, 'C', true);
        $pdf->CellFitSpace(15, 7, "HLF", 1, 0, 'C', true);
        $pdf->CellFitSpace(15, 7, "FULL", 1, 0, 'C', true);
        $pdf->CellFitSpace(20, 7, "T.Stems", 1, 0, 'C', true);
        $pdf->CellFitSpace(15, 7, "T.Kg", 1, 0, 'C', true);
        $pdf->Ln();
        $pdf->CellFitSpace(85, 7, "TOTAL:", 1, 0, 'R', true);
        $pdf->CellFitSpace(15, 7, $totalcont_1, 1, 0, 'C', true);
        $pdf->CellFitSpace(15, 7, $totalcont_2, 1, 0, 'C', true);
        $pdf->CellFitSpace(15, 7, $totalcont_3, 1, 0, 'C', true);
        $pdf->CellFitSpace(15, 7, $totalcont_4, 1, 0, 'C', true);
        $pdf->CellFitSpace(15, 7, $totalcont_5, 1, 0, 'C', true);
        $pdf->CellFitSpace(20, 7, $totalcont_6, 1, 0, 'C', true);
        $pdf->CellFitSpace(15, 7, $totalcont_7, 1, 0, 'C', true);
        $pdf->Ln();
        $pdf->CellFitSpace(85, 7, "TOTAL PIECES:", 1, 0, 'R', true);
        $pdf->CellFitSpace(75, 7, $totalcont_1 + $totalcont_2 + $totalcont_3 + $totalcont_4 + $totalcont_5, 1, 0, 'C', true);
        $pdf->Ln();
        $pdf->Output("Guias Asignadas.pdf", "I");
    } else {
        if ($hija != '') {
            $sql = "SELECT DISTINCT finca,guia_madre,fecha_vuelo, fecha_entrega, servicio FROM tblcoldroom WHERE salida='Si' AND guia_hija = '" . $hija . "' 
                            UNION
                            SELECT DISTINCT finca,guia_m,vuelo, entrega, servicio FROM tblcoldrom_fincas WHERE guia_h = '" . $hija . "' 
                            ORDER BY fecha_vuelo, fecha_entrega, servicio";
            $query = mysqli_query($link, $sql) or die("Error seleccionando datos generales");
            $row = mysqli_fetch_array($query);

            //Creando la pagina del pdf
            $pdf->AddPage();
            $pdf->AliasNbPages();
            $pdf->SetFont('Arial', 'B', 20);
            $pdf->Cell(180, 7, "REPORTE POR GUIA HIJA", 0, 0, 'C');
            $pdf->Ln();
            $pdf->Ln();

            //Mostrando los datos generales de la guia madre
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(45, 7, "", 0, 0, 'C');
            $pdf->Cell(45, 7, "AWB: ", 0, 0, 'L');
            $pdf->Cell(45, 7, $row['guia_madre'], 0, 0, 'L');
            $pdf->Ln();
            $pdf->Cell(45, 7, "", 0, 0, 'C');
            $pdf->Cell(45, 7, "FLIGHT DATE:", 0, 0, 'L');
            $pdf->Cell(45, 7, $row['fecha_vuelo'], 0, 0, 'L');
            $pdf->Ln();
            $pdf->Cell(45, 7, "", 0, 0, 'C');
            $pdf->Cell(45, 7, "DELIVERY DATE: ", 0, 0, 'C');
            $pdf->Cell(45, 7, $row['fecha_entrega'], 0, 0, 'L');
            $pdf->Ln();
            $pdf->Cell(45, 7, "", 0, 0, 'C');
            $pdf->Cell(45, 7, "SERVICE: ", 0, 0, 'L');
            $pdf->Cell(180, 7, $row['servicio'], 0, 0, 'L');
            $pdf->Ln();
            $pdf->Ln();
            //Creando datos y tabla de encabezamiento
            $pdf->Cell(140, 7, "FARM: " . utf8_decode($row['finca']), 0, 0, 'L');
            $pdf->Cell(40, 7, "HAWB#: " . $hija, 0, 0, 'C');
            $pdf->Ln();

            //Encabezado de la tabla
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(10, 7, "", 1, 0, 'C');
            $pdf->Cell(70, 7, "", 1, 0, 'C');
            $pdf->Cell(10, 7, "", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "Stems", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "Stems", 1, 0, 'C');
            $pdf->CellFitSpace(12, 7, "Weight", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "1/16B", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "1/8EB", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "QBX", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "HLF", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "FULL", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "TOTAL", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "TOTAL", 1, 0, 'C');
            $pdf->Ln();
            $pdf->Cell(10, 7, "ITEM", 1, 0, 'C');
            $pdf->Cell(70, 7, "PRODUCT NAME", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "PACK", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "Bunch", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "Box", 1, 0, 'C');
            $pdf->CellFitSpace(12, 7, "Kg", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "QTY", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "QTY", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "QTY", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "QTY", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "QTY", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "STEMS", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, "Kg", 1, 0, 'C');
            $pdf->Ln();

            //seleccionar diferentes items
            $sql2 = "SELECT DISTINCT item FROM tblcoldroom  WHERE salida='Si' AND guia_hija = '" . $hija . "' AND fecha_vuelo='" . $row['fecha_vuelo'] . "' AND fecha_entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $row['finca'] . "'   
                            UNION
                            SELECT DISTINCT item FROM tblcoldrom_fincas  WHERE guia_h= '" . $hija . "' AND vuelo='" . $row['fecha_vuelo'] . "' AND entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $row['finca'] . "'   
                            ORDER BY item";
//                    $sql2 = "SELECT DISTINCT item FROM tblcoldroom  WHERE salida='Si' AND guia_hija = '".$hija."' AND fecha_vuelo='".$row['fecha_vuelo']."' AND fecha_entrega='".$row['fecha_entrega']."' AND servicio='".$row['servicio']."' AND finca = '".$row['finca']."' AND item !='10000'  
//                            UNION
//                            SELECT DISTINCT item FROM tblcoldrom_fincas  WHERE guia_h= '".$hija."' AND vuelo='".$row['fecha_vuelo']."' AND entrega='".$row['fecha_entrega']."' AND servicio='".$row['servicio']."' AND finca = '".$row['finca']."' AND item !='10000'   
//                            ORDER BY item";
            $query2 = mysqli_query($link, $sql2) or die("Error selecionando los items");
            //Recorra cada item diferente para escoger sus datos en cada caso
            while ($row2 = mysqli_fetch_array($query2)) {

                //inicializando las variables
                $box_full = 0;
                $box_medio = 0;
                $box_cuarto = 0;
                $box_octavo = 0;
                $box_dieciseis = 0;


                $pdf->SetFont('Arial', 'B', 8);
                $pdf->CellFitSpace(10, 7, $row2['item'], 1, 0, 'C');
                ////////////////////////////////////////////////////////////////////////////////////////
                //Cantidad de item con ese codigo
                $g = "SELECT COUNT(*) as cantidad FROM tblcoldroom  WHERE salida='Si' AND guia_madre = '" . $row['guia_madre'] . "' AND fecha_vuelo='" . $row['fecha_vuelo'] . "' AND fecha_entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $row['finca'] . "' AND guia_hija='" . $hija . "' AND item='" . $row2['item'] . "'";
                $h = mysqli_query($link, $g) or die("Error selecionando la cantidad");
                $fila11 = mysqli_fetch_array($h);

                $g1 = "SELECT tblproductos.*,tblboxtype.tipo_Box FROM tblboxtype INNER JOIN tblproductos ON tblboxtype.id_Box = tblproductos.boxtype where tblproductos.id_item='" . $row2['item'] . "'";
                $h1 = mysqli_query($link, $g1) or die("Error selecionando las fincas");
                $fila2 = mysqli_fetch_array($h1);

                //voy viendo el Box y voy sumando la cantidad por cada uno
                if ($fila2['tipo_Box'] == 'FULL')
                    $box_full = $fila11[0];
                if ($fila2['tipo_Box'] == 'HLF')
                    $box_medio = $fila11[0];
                if ($fila2['tipo_Box'] == 'QBX')
                    $box_cuarto = $fila11[0];
                if ($fila2['tipo_Box'] == '1/8')
                    $box_octavo = $fila11[0];
                if ($fila2['tipo_Box'] == '1/16')
                    $box_dieciseis = $fila11[0];

                //Para cada item escoger la receta
                $pdf->CellFitSpace(70, 7, $fila2['prod_descripcion'], 1, 0, 'L');
                $pdf->CellFitSpace(10, 7, $fila2['pack'], 1, 0, 'C');

                //recorro los detallesd receta para sumar las qcatidades=bunch
                //consultando receta
                if ($row2['item'] == '10000') {
                    $kk = "SELECT sum(cantidad) as cant FROM tbldetallereceta INNER JOIN tblproductos ON tbldetallereceta.id_receta = tblproductos.id_receta WHERE tblproductos.id_item='" . $row2['item'] . "'";
                } else {
                    $kk = "SELECT sum(cantidad) as cant FROM tbldetallereceta INNER JOIN tblproductos ON tbldetallereceta.id_receta = tblproductos.id_receta WHERE tblproductos.id_item='" . $row2['item'] . "' and tbldetallereceta.producto!='CERAMIC VASE'";
                }
                $ll = mysqli_query($link, $kk) or die("Error consultando el hts del item");
                $cant = mysqli_fetch_row($ll);
                $pdf->CellFitSpace(10, 7, $cant[0], 1, 0, 'C');

                //box
                $box = $fila2['pack'] * $cant[0];
                $pdf->CellFitSpace(10, 7, $box, 1, 0, 'C');

                //peso
                $pdf->CellFitSpace(12, 7, $fila2['wheigthKg'], 1, 0, 'C');

                //$valor_dieciseis=$box_dieciseis*0.0625;
                $valor_dieciseis = $box_dieciseis;
                $cont1 += $valor_dieciseis;
                $pdf->CellFitSpace(10, 7, $valor_dieciseis, 1, 0, 'C');

                //                            $valor_octavo=$box_octavo*0.125;
                $valor_octavo = $box_octavo;
                $cont2 += $valor_octavo;
                $pdf->CellFitSpace(10, 7, $valor_octavo, 1, 0, 'C');

                //$valor_cuarto=$box_cuarto*0.25;
                $valor_cuarto = $box_cuarto;
                $cont3 += $valor_cuarto;
                $pdf->CellFitSpace(10, 7, $valor_cuarto, 1, 0, 'C'); //Qtrs  1/4
                //                            $valor_medio=$box_medio*0.5;
                $valor_medio = $box_medio;
                $cont4 += $valor_medio;
                $pdf->CellFitSpace(10, 7, $valor_medio, 1, 0, 'C'); //Half 1/2

                $valor_full = $box_full;
                $cont5 += $valor_full;
                $pdf->CellFitSpace(10, 7, $valor_full, 1, 0, 'C'); //Full 1/1
                //total stems
                $totalstems = $fila11[0] * $cant[0] * $fila2['pack'];
                $cont6 += $totalstems;
                $pdf->CellFitSpace(10, 7, $totalstems, 1, 0, 'C');

                //total kg
                $totalkg = $fila11[0] * $fila2['wheigthKg'];
                $cont7 += $totalkg;
                $pdf->CellFitSpace(10, 7, $totalkg, 1, 0, 'C');
                $pdf->Ln();
            }
            //Agregar Subtotal por item
            $pdf->CellFitSpace(110, 7, "SUBTOTAL:", 1, 0, 'R');
            $pdf->CellFitSpace(12, 7, "", 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, $cont1, 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, $cont2, 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, $cont3, 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, $cont4, 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, $cont5, 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, $cont6, 1, 0, 'C');
            $pdf->CellFitSpace(10, 7, $cont7, 1, 0, 'C');
            $pdf->Ln();

            //Actualizar contadores totales
            $totalcont_1 += $cont1;
            $totalcont_2 += $cont2;
            $totalcont_3 += $cont3;
            $totalcont_4 += $cont4;
            $totalcont_5 += $cont5;
            $totalcont_6 += $cont6;
            $totalcont_7 += $cont7;

            //limpio los subtotales
            $cont1 = 0;
            $cont2 = 0;
            $cont3 = 0;
            $cont4 = 0;
            $cont5 = 0;
            $cont6 = 0;
            $cont7 = 0;

            $pdf->Ln();
            $pdf->Ln();


            //darle color al total
            $pdf->SetFillColor(255, 0, 0);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', 'B', 12);
            //Agregar Total general
            $pdf->CellFitSpace(85, 7, "", 1, 0, 'R', true);
            $pdf->CellFitSpace(15, 7, "1/16B", 1, 0, 'C', true);
            $pdf->CellFitSpace(15, 7, "1/8EB", 1, 0, 'C', true);
            $pdf->CellFitSpace(15, 7, "QBX", 1, 0, 'C', true);
            $pdf->CellFitSpace(15, 7, "HLF", 1, 0, 'C', true);
            $pdf->CellFitSpace(15, 7, "FULL", 1, 0, 'C', true);
            $pdf->CellFitSpace(20, 7, "T.Stems", 1, 0, 'C', true);
            $pdf->CellFitSpace(15, 7, "T.Kg", 1, 0, 'C', true);
            $pdf->Ln();
            $pdf->CellFitSpace(85, 7, "TOTAL:", 1, 0, 'R', true);
            $pdf->CellFitSpace(15, 7, $totalcont_1, 1, 0, 'C', true);
            $pdf->CellFitSpace(15, 7, $totalcont_2, 1, 0, 'C', true);
            $pdf->CellFitSpace(15, 7, $totalcont_3, 1, 0, 'C', true);
            $pdf->CellFitSpace(15, 7, $totalcont_4, 1, 0, 'C', true);
            $pdf->CellFitSpace(15, 7, $totalcont_5, 1, 0, 'C', true);
            $pdf->CellFitSpace(20, 7, $totalcont_6, 1, 0, 'C', true);
            $pdf->CellFitSpace(15, 7, $totalcont_7, 1, 0, 'C', true);
            $pdf->Ln();
            $pdf->CellFitSpace(85, 7, "TOTAL PIECES:", 1, 0, 'R', true);
            $pdf->CellFitSpace(75, 7, $totalcont_1 + $totalcont_2 + $totalcont_3 + $totalcont_4 + $totalcont_5, 1, 0, 'C', true);
            $pdf->Ln();

            $pdf->Output("Guias Asignadas.pdf", "I");
        } else {
            //Si el filtro finca tiene valor
            $sql = "SELECT DISTINCT guia_madre,fecha_vuelo, fecha_entrega, servicio FROM tblcoldroom WHERE salida='Si' AND finca = '" . $finca . "' 
                          UNION
                                  SELECT DISTINCT guia_m,vuelo, entrega, servicio FROM tblcoldrom_fincas WHERE finca = '" . $finca . "'
                          ORDER BY fecha_vuelo, fecha_entrega, servicio";
            $query = mysqli_query($link, $sql) or die("Error consultando datos generales");

            //Por cada guia madre que tengfa esa finca voy creando paginas en pdf
            while ($row = mysqli_fetch_array($query)) {
                if ($row['guia_madre'] != 0) {
                    //Creando la pagina del pdf
                    $pdf->AddPage();
                    $pdf->AliasNbPages();
                    $pdf->SetFont('Arial', 'B', 20);
                    $pdf->Cell(180, 7, "REPORTE POR FINCA", 0, 0, 'C');
                    $pdf->Ln();
                    $pdf->Ln();

                    //Mostrando los datos generales de la guia madre
                    $pdf->SetFont('Arial', 'B', 14);
                    $pdf->Cell(45, 7, "", 0, 0, 'C');
                    $pdf->Cell(45, 7, "AWB: ", 0, 0, 'L');
                    $pdf->Cell(45, 7, $row['guia_madre'], 0, 0, 'L');
                    $pdf->Ln();
                    $pdf->Cell(45, 7, "", 0, 0, 'C');
                    $pdf->Cell(45, 7, "FLIGHT DATE:", 0, 0, 'L');
                    $pdf->Cell(45, 7, $row['fecha_vuelo'], 0, 0, 'L');
                    $pdf->Ln();
                    $pdf->Cell(45, 7, "", 0, 0, 'C');
                    $pdf->Cell(45, 7, "DELIVERY DATE: ", 0, 0, 'C');
                    $pdf->Cell(45, 7, $row['fecha_entrega'], 0, 0, 'L');
                    $pdf->Ln();
                    $pdf->Cell(45, 7, "", 0, 0, 'C');
                    $pdf->Cell(45, 7, "SERVICE: ", 0, 0, 'L');
                    $pdf->Cell(180, 7, $row['servicio'], 0, 0, 'L');
                    $pdf->Ln();
                    $pdf->Ln();

                    //Seleccionando las fincas y guias hijas diferentes
                    $sql1 = "SELECT DISTINCT finca, guia_hija FROM tblcoldroom  WHERE finca = '" . $finca . "' AND salida='Si' AND guia_madre = '" . $row['guia_madre'] . "' AND fecha_vuelo='" . $row['fecha_vuelo'] . "' AND fecha_entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' 
                                       UNION
                                       SELECT DISTINCT finca, guia_h FROM tblcoldrom_fincas  WHERE finca = '" . $finca . "' AND guia_m = '" . $row['guia_madre'] . "' AND vuelo='" . $row['fecha_vuelo'] . "' AND entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' 
                                       ORDER BY finca, guia_hija";
                    $query1 = mysqli_query($link, $sql1) or die("Error selecionando las fincas");
                    //Por cada finca y guia hija diferente muestro sus datos
                    while ($row1 = mysqli_fetch_array($query1)) {
                        $pdf->Cell(140, 7, "FARM: " . utf8_decode($row1['finca']), 0, 0, 'L');
                        $pdf->Cell(40, 7, "HAWB#: " . $row1['guia_hija'], 0, 0, 'C');
                        $pdf->Ln();
                        //Encabezado de la tabla
                        $pdf->SetFont('Arial', 'B', 8);
                        $pdf->Cell(10, 7, "", 1, 0, 'C');
                        $pdf->Cell(70, 7, "", 1, 0, 'C');
                        $pdf->Cell(10, 7, "", 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, "Stems", 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, "Stems", 1, 0, 'C');
                        $pdf->CellFitSpace(12, 7, "Weight", 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, "1/16B", 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, "1/8EB", 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, "QBX", 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, "HLF", 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, "FULL", 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, "TOTAL", 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, "TOTAL", 1, 0, 'C');
                        $pdf->Ln();
                        $pdf->Cell(10, 7, "ITEM", 1, 0, 'C');
                        $pdf->Cell(70, 7, "PRODUCT NAME", 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, "PACK", 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, "Bunch", 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, "Box", 1, 0, 'C');
                        $pdf->CellFitSpace(12, 7, "Kg", 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, "QTY", 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, "QTY", 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, "QTY", 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, "QTY", 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, "QTY", 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, "STEMS", 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, "Kg", 1, 0, 'C');
                        $pdf->Ln();



                        //seleccionar diferentes items
                        $sql2 = "SELECT DISTINCT item FROM tblcoldroom  WHERE salida='Si' AND guia_madre = '" . $row['guia_madre'] . "' AND fecha_vuelo='" . $row['fecha_vuelo'] . "' AND fecha_entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $row1['finca'] . "' AND guia_hija='" . $row1['guia_hija'] . "' ORDER BY item";
//                        $sql2 =   "SELECT DISTINCT item FROM tblcoldroom  WHERE salida='Si' AND guia_madre = '".$row['guia_madre']."' AND fecha_vuelo='".$row['fecha_vuelo']."' AND fecha_entrega='".$row['fecha_entrega']."' AND servicio='".$row['servicio']."' AND finca = '".$row1['finca']."' AND guia_hija='".$row1['guia_hija']."' AND item !='10000' 
//                                   ORDER BY item";
                        $query2 = mysqli_query($link, $sql2) or die("Error selecionando los items");

                        //Recorra cada item diferente para escoger sus datos en cada caso
                        while ($row2 = mysqli_fetch_array($query2)) {
                            //inicializando las variables
                            $box_full = 0;
                            $box_medio = 0;
                            $box_cuarto = 0;
                            $box_octavo = 0;
                            $box_dieciseis = 0;


                            $pdf->SetFont('Arial', 'B', 8);
                            $pdf->CellFitSpace(10, 7, $row2['item'], 1, 0, 'C');
                            ////////////////////////////////////////////////////////////////////////////////////////
                            //Cantidad de item con ese codigo
                            $g = "SELECT COUNT(*) as cantidad FROM tblcoldroom  WHERE salida='Si' AND guia_madre = '" . $row['guia_madre'] . "' AND fecha_vuelo='" . $row['fecha_vuelo'] . "' AND fecha_entrega='" . $row['fecha_entrega'] . "' AND servicio='" . $row['servicio'] . "' AND finca = '" . $row1['finca'] . "' AND guia_hija='" . $row1['guia_hija'] . "' AND item='" . $row2['item'] . "'";
                            $h = mysqli_query($link, $g) or die("Error selecionando la cantidad");
                            $fila11 = mysqli_fetch_array($h);

                            $g1 = "SELECT tblproductos.*,tblboxtype.tipo_Box FROM tblboxtype INNER JOIN tblproductos ON tblboxtype.id_Box = tblproductos.boxtype where tblproductos.id_item='" . $row2['item'] . "'";
                            $h1 = mysqli_query($link, $g1) or die("Error selecionando las fincas");
                            $fila2 = mysqli_fetch_array($h1);

                            //voy viendo el Box y voy sumando la cantidad por cada uno
                            if ($fila2['tipo_Box'] == 'FULL')
                                $box_full = $fila11[0];
                            if ($fila2['tipo_Box'] == 'HLF')
                                $box_medio = $fila11[0];
                            if ($fila2['tipo_Box'] == 'QBX')
                                $box_cuarto = $fila11[0];
                            if ($fila2['tipo_Box'] == '1/8')
                                $box_octavo = $fila11[0];
                            if ($fila2['tipo_Box'] == '1/16')
                                $box_dieciseis = $fila11[0];

                            //Para cada item escoger la receta
                            $pdf->CellFitSpace(70, 7, $fila2['prod_descripcion'], 1, 0, 'L');
                            $pdf->CellFitSpace(10, 7, $fila2['pack'], 1, 0, 'C');

                            //recorro los detallesd receta para sumar las qcatidades=bunch
                            //consultando receta
                            if ($row2['item'] == '10000') {
                                $kk = "SELECT sum(cantidad) as cant FROM tbldetallereceta INNER JOIN tblproductos ON tbldetallereceta.id_receta = tblproductos.id_receta WHERE tblproductos.id_item='" . $row2['item'] . "'";
                            } else {
                                $kk = "SELECT sum(cantidad) as cant FROM tbldetallereceta INNER JOIN tblproductos ON tbldetallereceta.id_receta = tblproductos.id_receta WHERE tblproductos.id_item='" . $row2['item'] . "' and tbldetallereceta.producto!='CERAMIC VASE'";
                            }

                            $ll = mysqli_query($link, $kk) or die("Error consultando el hts del item");
                            $cant = mysqli_fetch_row($ll);
                            $pdf->CellFitSpace(10, 7, $cant[0], 1, 0, 'C');

                            //box
                            $box = $fila2['pack'] * $cant[0];
                            $pdf->CellFitSpace(10, 7, $box, 1, 0, 'C');

                            //peso
                            $pdf->CellFitSpace(12, 7, $fila2['wheigthKg'], 1, 0, 'C');

                            //$valor_dieciseis=$box_dieciseis*0.0625;
                            $valor_dieciseis = $box_dieciseis;
                            $cont1 += $valor_dieciseis;
                            $pdf->CellFitSpace(10, 7, $valor_dieciseis, 1, 0, 'C');

                            //                            $valor_octavo=$box_octavo*0.125;
                            $valor_octavo = $box_octavo;
                            $cont2 += $valor_octavo;
                            $pdf->CellFitSpace(10, 7, $valor_octavo, 1, 0, 'C');

                            //$valor_cuarto=$box_cuarto*0.25;
                            $valor_cuarto = $box_cuarto;
                            $cont3 += $valor_cuarto;
                            $pdf->CellFitSpace(10, 7, $valor_cuarto, 1, 0, 'C'); //Qtrs  1/4
                            //                            $valor_medio=$box_medio*0.5;
                            $valor_medio = $box_medio;
                            $cont4 += $valor_medio;
                            $pdf->CellFitSpace(10, 7, $valor_medio, 1, 0, 'C'); //Half 1/2

                            $valor_full = $box_full;
                            $cont5 += $valor_full;
                            $pdf->CellFitSpace(10, 7, $valor_full, 1, 0, 'C'); //Full 1/1
                            //total stems
                            $totalstems = $fila11[0] * $cant[0] * $fila2['pack'];
                            $cont6 += $totalstems;
                            $pdf->CellFitSpace(10, 7, $totalstems, 1, 0, 'C');

                            //total kg
                            $totalkg = $fila11[0] * $fila2['wheigthKg'];
                            $cont7 += $totalkg;
                            $pdf->CellFitSpace(10, 7, $totalkg, 1, 0, 'C');
                            $pdf->Ln();
                        }

                        //Agregar Subtotal por item
                        $pdf->CellFitSpace(110, 7, "SUBTOTAL:", 1, 0, 'R');
                        $pdf->CellFitSpace(12, 7, "", 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, $cont1, 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, $cont2, 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, $cont3, 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, $cont4, 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, $cont5, 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, $cont6, 1, 0, 'C');
                        $pdf->CellFitSpace(10, 7, $cont7, 1, 0, 'C');
                        $pdf->Ln();

                        //Actualizar contadores totales
                        $totalcont_1 += $cont1;
                        $totalcont_2 += $cont2;
                        $totalcont_3 += $cont3;
                        $totalcont_4 += $cont4;
                        $totalcont_5 += $cont5;
                        $totalcont_6 += $cont6;
                        $totalcont_7 += $cont7;

                        //limpio los subtotales
                        $cont1 = 0;
                        $cont2 = 0;
                        $cont3 = 0;
                        $cont4 = 0;
                        $cont5 = 0;
                        $cont6 = 0;
                        $cont7 = 0;


                        //Aumentar el tamaño de fuente de la proxima finca 
                        $pdf->SetFont('Arial', 'B', 14);
                        $pdf->Ln();
                        $pdf->Ln();


                        //darle color al total
                        $pdf->SetFillColor(255, 0, 0);
                        $pdf->SetTextColor(0, 0, 0);
                    } //separar hijas y finca										
                }// si la guia madre existe
            }// cada guia madre voy creando paginasss
            //darle color al total
            $pdf->SetFillColor(255, 0, 0);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', 'B', 12);
            //Agregar Total general
            $pdf->CellFitSpace(85, 7, "", 1, 0, 'R', true);
            $pdf->CellFitSpace(15, 7, "1/16B", 1, 0, 'C', true);
            $pdf->CellFitSpace(15, 7, "1/8EB", 1, 0, 'C', true);
            $pdf->CellFitSpace(15, 7, "QBX", 1, 0, 'C', true);
            $pdf->CellFitSpace(15, 7, "HLF", 1, 0, 'C', true);
            $pdf->CellFitSpace(15, 7, "FULL", 1, 0, 'C', true);
            $pdf->CellFitSpace(20, 7, "T.Stems", 1, 0, 'C', true);
            $pdf->CellFitSpace(15, 7, "T.Kg", 1, 0, 'C', true);
            $pdf->Ln();
            $pdf->CellFitSpace(85, 7, "TOTAL:", 1, 0, 'R', true);
            $pdf->CellFitSpace(15, 7, $totalcont_1, 1, 0, 'C', true);
            $pdf->CellFitSpace(15, 7, $totalcont_2, 1, 0, 'C', true);
            $pdf->CellFitSpace(15, 7, $totalcont_3, 1, 0, 'C', true);
            $pdf->CellFitSpace(15, 7, $totalcont_4, 1, 0, 'C', true);
            $pdf->CellFitSpace(15, 7, $totalcont_5, 1, 0, 'C', true);
            $pdf->CellFitSpace(20, 7, $totalcont_6, 1, 0, 'C', true);
            $pdf->CellFitSpace(15, 7, $totalcont_7, 1, 0, 'C', true);
            $pdf->Ln();
            $pdf->CellFitSpace(85, 7, "TOTAL PIECES:", 1, 0, 'R', true);
            $pdf->CellFitSpace(75, 7, $totalcont_1 + $totalcont_2 + $totalcont_3 + $totalcont_4 + $totalcont_5, 1, 0, 'C', true);
            $pdf->Ln();
            //generando el pdf
            $pdf->Output("Guias Asignadas.pdf", "I");
        } //fin del else de si finca, y de sino lo demas.
    }
}
            