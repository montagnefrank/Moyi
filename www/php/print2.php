<?php
require('fpdf/fpdf.php');
require_once('barcode.inc.php');
require_once('barcode39.php');
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");

$finca = $_GET['finca'];
$fecha = $_GET['fecha'];
$item = $_GET['item'];

$conection = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : ' . mysql_error());

//Obtener el codigo de la finca
$sql1 = "SELECT codigo FROM tblfinca where nombre = '" . $finca . "'";
$query1 = mysql_query($sql1, $conection);
$row1 = mysql_fetch_array($query1);
$codigo = $row1[0];

if (!isset($_GET['item'])) {
    //Obtengo todos los codigo de las etiquetas para la finca seleccionada
    $sql = "SELECT codigo, item FROM tbletiquetasxfinca where finca='" . $finca . "' AND fecha='" . $fecha . "'";
} else {
    //Obtengo todos los codigo de las etiquetas para la finca seleccionada y el item
    $sql = "SELECT codigo, item FROM tbletiquetasxfinca where finca='" . $finca . "' AND fecha='" . $fecha . "' AND item='" . $item . "'";
}
$query = mysql_query($sql, $conection);


//Creacion del objeto pdf		
$pdf = new FPDF('P', 'mm', array(90, 140));

//Recorro todos las solicitudes para esa finca con ese item y genero el codigo de barras del codigo unico
while ($row = mysql_fetch_array($query)) {
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
    $consulta = mysql_query($sentencia, $conection);
    $fila = mysql_fetch_array($consulta);
    $pdf->SetFont('Arial', 'B', 40);
    $pdf->Cell(0, 35, 'ITEM ' . utf8_decode($fila['id_item']) . '', 0, 0, 'C');
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(0, 43, '' . utf8_decode($fila['prod_descripcion']) . '', 0, 0, 'C');
    //$pdf->Image('./barscode/Barcode_'.$item.'.png',20 ,60, 50 , 30);
    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Cell(0, 50, '' . 'UNITE CODE ' . utf8_decode($row['codigo']) . '', 0, 0, 'C');
    //new barCodeGenrator(''.$codigo.'-'.$item.'-'.$row['codigo'].'',1,'./barscode/Barcode_'.$codigo.'-'.$item.'-'.$row['codigo'].'.png',180,140, true);
    $codebar = $codigo . '-' . $row['item'] . '-' . $row['codigo'];
    Barcode39($codebar);
    $pdf->Image('./barscode/Barcode_' . $codigo . '-' . $row['item'] . '-' . $row['codigo'] . '.png', 5, 65, 80, 30);
}

$pdf->Output("Etiquetas.pdf", "I");
?>
</body>
</html>