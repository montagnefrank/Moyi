<?php 
	require('fpdf/fpdf.php');
	require_once('barcode.inc.php');
	include ("seguridad.php");
	$codigo = $_GET['codigo'];


	// se crea el codigo de barras para el codigo	
	new barCodeGenrator(''.$codigo.'',1,'./barscode/Barcode_'.$codigo.'.gif', 190, 130, true);
	$pdf = new FPDF();
    $pdf->AddPage();
	$pdf->Image('./barscode/Barcode_'.$codigo.'.gif',40,18,33);
	$pdf->Output("doc.pdf","I");
  ?>
</body>
</html>