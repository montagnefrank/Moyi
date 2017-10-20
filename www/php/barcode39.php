<?php
include ("seguridad.php");
include('./barcodegen/class/BCGFont.php');
include('./barcodegen/class/BCGColor.php');
include('./barcodegen/class/BCGDrawing.php');
include('./barcodegen/class/BCGcode128.barcode.php');

function Barcode39($codigo){


$color_black = new BCGColor(0, 0, 0);
$color_white = new BCGColor(255, 255, 255);

$code = new BCGcode128();
$code->setThickness(100);
$code->setScale(10);
$code->setFont($font);
$code->setForegroundColor($color_black);
//$code->setBackgroundColor($color_white);
//$code->parse('(415)9679998005853(8020)123581339002705676 (3900)649946');
$code->parse($codigo);

$drawing = new BCGDrawing('', $color_white);
$drawing->setBarcode($code);
$drawing->setDPI(200);
$drawing->setFilename("./barscode/Barcode_".$codigo.".png");
$drawing->draw();
header('Content-Type: image/png');
$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
}
?>