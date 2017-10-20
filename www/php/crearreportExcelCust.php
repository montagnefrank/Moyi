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


//Se incia la sess
$sql = $_SESSION["consulta"];
//$sql   = "SELECT id_orden,nombre_compania,cpmensaje,order_date FROM tblorden LIMIT 10";
$titulo = $_SESSION["titulo"];
//$titulo = "REPORTE DE PRUEBA";
$columnas = $_SESSION["columnas"];
$nombre_fichero = $_SESSION["nombre_fichero"];
//$nombre_fichero = "Reporte prueba.xlsx";
//Verificar si hay datos en las variables de session
if ($sql == '' && $titulo == '' && $columnas == '' && $nombre_fichero == '') {
    header("Location:" . $_SERVER['HTTP_REFERER']);
}


//Aqui se crea el objeto de tipo excel 2007
$objPHPExcel = new PHPExcel();

//Se le crean las propiedades
$objProps = $objPHPExcel->getProperties();
$objProps->setCreator("Bit-Store.ec");

//Aqui se activa la primera hoja del excel
$objPHPExcel->setActiveSheetIndex(0);
$objActSheet = $objPHPExcel->getActiveSheet();

//Escribir los titulos de las columnas
//Convirtendo la cadena de titulos en un array
//$TIT_COLUM = array("COLUMNA 1","COLUMNA 2", "COLUMNA 3","COLUMNA 4");
$TIT_COLUM = $columnas;

// Se combinan las celdas A1 hasta D1, para colocar ah� el titulo del reporte
$objPHPExcel->setActiveSheetIndex(0)
        ->mergeCells('A1:M1');

//Se crea la primera columna fija y las demas on dinamicas en dependencia de la cantidad de titulos que tengamos
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', $titulo)
        ->setCellValue('A2', $TIT_COLUM['0']);


$fila = 2;
$letra = 'A';
for ($i = 1; $i < count($TIT_COLUM); $i++) {
    $letra ++;
    $lastColumn = $letra . $fila;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . $fila, $TIT_COLUM[$i]);
}
//Se ejecuta la consulta y se van crenado las columnas segun los campos de la 
$query = mysqli_query($link, $sql) or die("Error al ejecutar la consulta");
$cant = mysqli_num_fields($query);
$fila = 3;
$letra = 'A';
$cant = $cant + 2;

while ($row = mysqli_fetch_array($query)) { //recorro por cada fila 
    $gm = '';
    $gh = '';
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . $fila, $row[0]);
    //Devuelve la ultima columna utilizada en este caso la D
    $lastColumn = $letra . $fila;

    for ($i = 1; $i < $cant; $i++) { //recorro por cada columna de esa fila
        $letra ++;
        $lastColumn = $letra . $fila; //va iterando desde la E,F,G...n en la fila 1 ($head)

        if ($letra == 'Z') {
            $cod = $row[$i];
            $SQL = "SELECT guia_madre as gm, guia_hija as gh FROM tblcoldroom where codigo LIKE '%" . $cod . "%' UNION (SELECT guia_m as gm,guia_h as gh FROM tblcoldrom_fincas where codigo_unico LIKE '%" . $cod . "%')";
            $res1 = mysqli_query($link, $SQL) or die("Error al ejecutar la consulta");
            $roww = mysqli_fetch_array($res1);
            $gm = $roww['gm'];
            $gh = $roww['gh'];
        }
        if ($letra == 'AB') {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn, $gm);
        } else if ($letra == 'AC') {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn, $gh);
        } else {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn, $row[$i]);
        }
    }
    $letra = 'A';
    $fila++;
}

//Dando algunos estilos
$estiloTituloReporte = array(
    'font' => array(
        'name' => 'Verdana',
        'bold' => true,
        'italic' => false,
        'strike' => false,
        'size' => 16,
        'color' => array(
            'rgb' => 'FF220835'
        )
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
            'argb' => 'FFFFFF')
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_NONE
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation' => 0,
        'wrap' => TRUE
    )
);

//Limpiando las variables de session
unset($_SESSION["consulta"]);
unset($_SESSION["titulo"]);
unset($_SESSION["columnas"]);
unset($_SESSION["nombre_fichero"]);


//Aplicando el estilo 
$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray($estiloTituloReporte);

//Aqui se crea el docuemnto completo con todas las filas insertadas
$objPHPExcel->getActiveSheet()->setTitle('Hoja1');
$objPHPExcel->setActiveSheetIndex(0);

//Worksheet estilo predeterminado (de forma predeterminada diferentes necesidades y Preferencias Configurar individualmente)
$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $nombre_fichero . '"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>