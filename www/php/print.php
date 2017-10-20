<?php
///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");
require('fpdf/fpdf.php');
require_once('barcode.inc.php');
require_once('barcode39.php');
require_once ('phpStringShortener.php');

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


$finca = $_GET['finca'];
$fecha = $_GET['fecha'];
$item = $_GET['item'];
$vuelo = $_GET['vuelo'];
$agencia = $_GET['agencia'];


//Obtener el codigo de la finca
$sql1 = "SELECT codigo FROM tblfinca where nombre = '" . $finca . "'";
$query1 = mysqli_query($link, $sql1);
$row1 = mysqli_fetch_array($query1);
$codigo = $row1[0];

if ($_GET['status'] == 'true') {
    if (!isset($_GET['item'])) {
        //Obtengo todos los codigo de las etiquetas para la finca seleccionada
        $sql = "SELECT codigo, item FROM tbletiquetasxfinca where finca='" . $finca . "' AND fecha='" . $fecha . "' AND fecha_tentativa = '" . $vuelo . "' AND archivada = 'No'";
    } else {
        //Obtengo todos los codigo de las etiquetas para la finca seleccionada y el item
        $sql = "SELECT codigo, item FROM tbletiquetasxfinca where finca='" . $finca . "' AND fecha='" . $fecha . "' AND item='" . $item . "' AND fecha_tentativa = '" . $vuelo . "' AND archivada = 'No'";
    }
} else {
    if (!isset($_GET['item'])) {
        //Obtengo todos los codigo de las etiquetas para la finca seleccionada
        $sql = "SELECT codigo, item FROM tbletiquetasxfinca where finca='" . $finca . "' AND fecha='" . $fecha . "' AND estado = '0' AND fecha_tentativa = '" . $vuelo . "' AND agencia  LIKE '%" . $agencia . "%' AND archivada = 'No'";
    } else {
        //Obtengo todos los codigo de las etiquetas para la finca seleccionada y el item
        $sql = "SELECT codigo, item FROM tbletiquetasxfinca where finca='" . $finca . "' AND fecha='" . $fecha . "' AND item='" . $item . "'AND estado = '0' AND fecha_tentativa = '" . $vuelo . "' AND agencia  LIKE '%" . $agencia . "%' AND archivada = 'No'";
    }
}
//echo $sql;die;
$query = mysqli_query($link, $sql);


//Creacion del objeto pdf		
$pdf = new FPDF('P', 'mm', array(90, 140));

//Recorro todos las solicitudes para esa finca con ese item y genero el codigo de barras del codigo unico
while ($row = mysqli_fetch_array($query)) {
    //new barCodeGenrator(''.$row['codigo'].'',1,'./barscode/Barcode_'.$row['codigo'].'.gif',180,140, true);
    //Barcode39 ($row['codigo']);		
    //por cada codigo distinto imprimo una pagina
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Image('../images/logo.png', 5, 0, 80);
    $pdf->Ln(7);
    $pdf->Cell(0, 27, '' . utf8_decode($_GET['finca']) . '', 0, 0, 'C');
    // Salto de línea
    $pdf->Ln(6);
    //$pdf->Image('./barscode/Barcode_'.$codigo.'.png',10 ,26, 70 , 27);
    //Buscar la descripcion del item
    $sentencia = "SELECT prod_descripcion, id_item FROM tblproductos WHERE id_item='" . $row['item'] . "'";
    $consulta = mysqli_query($link, $sentencia);
    $fila = mysqli_fetch_array($consulta);
    $pdf->SetFont('Arial', 'B', 30);
    $pdf->Cell(0, 35, 'ITEM ' . utf8_decode($fila['id_item']) . '', 0, 0, 'C');
    //$pdf->Image('./barscode/Barcode_'.$item.'.png',20 ,60, 50 , 30);
    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Cell(0, 50, '' . 'UNIT CODE ' . utf8_decode($row['codigo']) . '', 0, 0, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->drawTextBox('' . utf8_decode($fila['prod_descripcion']) . '', 0, 55, 'C', 'M', false);
    //new barCodeGenrator(''.$codigo.'-'.$item.'-'.$row['codigo'].'',1,'./barscode/Barcode_'.$codigo.'-'.$item.'-'.$row['codigo'].'.png',180,140, true);
    $codebar = $codigo . '-' . $row['item'] . '-' . $row['codigo'];
    $string = $codebar;
    $phpSS = new PhpStringShortener();
    $hash = $phpSS->addHashByString($string);
    Barcode39($hash);
    $pdf->Image('./barscode/Barcode_' . $hash . '.png', 5, 74, 80, 20);
    //Barcode39 ($codebar);
    //$pdf->Image('./barscode/Barcode_'.$codigo.'-'.$row['item'].'-'.$row['codigo'].'.png',5 ,74, 80 , 20);
    $pdf->SetY(74);    // set the cursor at Y position 5
    $pdf->SetFont('Arial', 'B', 13);
    $pdf->Cell(0, 45, '' . $codigo . '-' . $row['item'] . '-' . $row['codigo'] . '', 0, 2, 'C');
}

$pdf->Output("Etiquetas.pdf", "I");
?>
</body>
</html>