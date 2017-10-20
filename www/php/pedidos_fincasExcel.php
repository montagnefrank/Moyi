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


//Aqui se crea el objeto de tipo excel 2007
$objPHPExcel = new PHPExcel();

//Se le crean las propiedades
$objProps= $objPHPExcel->getProperties();
$objProps->setCreator("Bit-Store.ec");

//Aqui se activa la primera hoja del excel
$objPHPExcel->setActiveSheetIndex(0); 
$objActSheet = $objPHPExcel->getActiveSheet();
//Escribir los titulos de las columnas
//Convirtendo la cadena de titulos en un array
$TIT_COLUM = array('Fecha Salida Finca','Fecha de Vuelo','Agencia Carga','Item','Prod. Desc.','Receta','Pack','Solicitadas','Enviadas','Rechazadas','Cierre de Dia','Por enviar','Valor Unitario','Valor A Pagar','Finca','Imagen');

// Se combinan las celdas A1 hasta D1, para colocar ah� el titulo del reporte
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:P1');

//Se crea la primera columna fija y las demas on dinamicas en dependencia de la cantidad de titulos que tengamos
//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','Pedidos de la Finca');

				
$filaa = 1;
$letra = 'A';
for ($i=0; $i < count($TIT_COLUM); $i++) {
    
    $lastColumn = $letra.$filaa;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra.$filaa, $TIT_COLUM[$i]);
    $objActSheet->getColumnDimension($letra)->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getStyle($letra.$filaa)->getFont()->setBold(true);
    $letra ++;
}


//Agrupar el reporte por destino
$a = "SELECT distinct destino FROM tbletiquetasxfinca where archivada = 'No' AND estado!='5' AND finca='".$_POST['finca']."' order by destino DESC";
$b = mysqli_query($link,$a) or die('Error seleccionando el origen');
 
$TOTALSOL = 0;
$TOTALENTSTRACK = 0;
$TOTALENTTRACK = 0;
$TOTALENT = 0;
$TOTALRECH= 0;
$TOTALDIF = 0;
$TOTALCIERRE = 0;
$TOTALREUT = 0;
$TOTALPRECIO = 0;
$TOTALPRECIO_COMPLETO=0;
$cont = 0;
$filaa++;
//recorriendo los destinos
while($fila = mysqli_fetch_array($b))
{
        $cont++;

        //Leer las fechas de los pedidios
        $sql = "SELECT distinct nropedido,fecha,fecha_tentativa FROM tbletiquetasxfinca where archivada = 'No' AND estado!='5' AND finca='".$_POST['finca']."' AND destino = '".$fila['destino']."' order by tbletiquetasxfinca.agencia,fecha";
        $val = mysqli_query($link,$sql);
        if(mysqli_num_rows($val)>0)
        {
           $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$filaa, $fila['destino']);
           $objPHPExcel->getActiveSheet()->getStyle('A'.$filaa)->getFont()->setBold(true);
        }
        $totalsol = 0;
        $totalentstrack = 0;
        $totalenttrack = 0;
        $totalent = 0;
        $totalrech= 0;
        $totaldif = 0;
        $totalcierre = 0;
        $totalreut = 0;
        $totalprecio_completo=0;
        //recorriendo cada uno de los pedidos
        while($row = mysqli_fetch_array($val)){

            //recorro por cada fecha de entrega que exista en los pedidos
            $sql1 = "SELECT DISTINCT
                    tbletiquetasxfinca.finca,
                    tbletiquetasxfinca.item,
                    tbletiquetasxfinca.precio,
                    tbletiquetasxfinca.destino,
                    tblproductos.prod_descripcion,
                    tblproductos.gen_desc,
                    tblproductos.receta,
                    tblproductos.pack,
                    tblproductos.item as id,
                    tbletiquetasxfinca.agencia
                    FROM
                    tbletiquetasxfinca
                    INNER JOIN tblproductos ON tblproductos.id_item = tbletiquetasxfinca.item 
                    WHERE nropedido = '".$row['nropedido']."' AND fecha = '".$row['fecha']."' AND fecha_tentativa = '".$row['fecha_tentativa']."' AND estado!='5' AND archivada = 'No' AND tbletiquetasxfinca.finca='".$_POST['finca']."' order by item";
           
            $val1 = mysqli_query($link,$sql1);
            
            while($row1 = mysqli_fetch_array($val1)){

                //Se cuenta cuantas solicitudes y entregas hay por cada finca e item
                $sql2 = "SELECT SUM(solicitado) as solicitado, SUM(entregado) as entregado FROM tbletiquetasxfinca where finca ='".$row1['finca']."' AND item='".$row1['item']."' AND precio='".$row1['precio']."' AND destino='".$row1['destino']."' AND nropedido = '".$row['nropedido']."' AND archivada = 'No' AND estado!='5'";
                $val2 = mysqli_query($link,$sql2) or die ("Error sumando las cantidades de solicitudes y entregas de las fincas");
                $row2 = mysqli_fetch_array($val2);

                //Se cuenta cuantas solicitudes rechazadas hay por cada finca e item
                $sql3 = "SELECT COUNT(*) as rechazado FROM tbletiquetasxfinca where finca ='".$row1['finca']."' AND item='".$row1['item']."' AND precio='".$row1['precio']."' AND destino='".$row1['destino']."' AND estado='2' AND nropedido = '".$row['nropedido']."' AND archivada = 'No'";
                $val3 = mysqli_query($link,$sql3) or die ("Error sumando las cantidades de solicitudes y entregas de las fincas");
                $row3 = mysqli_fetch_array($val3);

                //Se cuenta cuantas solicitudes con cierre de dia por cada finca e item
                $sql4 = "SELECT COUNT(*) as cierre FROM tbletiquetasxfinca where finca ='".$row1['finca']."' AND item='".$row1['item']."' AND precio='".$row1['precio']."' AND destino='".$row1['destino']."' AND estado= '3' AND nropedido = '".$row['nropedido']."' AND archivada = 'No'";
                $val4 = mysqli_query($link,$sql4) or die ("Error sumando las cantidades de solicitudes y entregas de las fincas");
                $row4 = mysqli_fetch_array($val4);

                //Se cuenta cuantas solicitudes con cierre de dia por cada finca e item
                $sql41 = "SELECT COUNT(*) as reutilizadas FROM tbletiquetasxfinca where finca ='".$row1['finca']."' AND item='".$row1['item']."' AND precio='".$row1['precio']."' AND destino='".$row1['destino']."' AND estado= '4' AND nropedido = '".$row['nropedido']."' AND archivada = 'No'";
                $val41 = mysqli_query($link,$sql41) or die ("Error sumando las cantidades de solicitudes y entregas de las fincas");
                $row41 = mysqli_fetch_array($val41);

                //Seleccionar la descripcion del item
                $sql5 = "SELECT prod_descripcion FROM tblproductos where id_item ='".$row1['item']."'";
                $val5 = mysqli_query($link,$sql5) or die ("Error seleccionando la descripcion del item");
                $row5 = mysqli_fetch_array($val5);

                //se restan las solicitudes - entregado - rechazado	
                $totalsol+= $row2['solicitado']; //total solicitado
                $totalrech+= $row3['rechazado']; //total rechazado
                $totalent+= $row2['entregado'];  //total entregado
                $totalcierre += $row4['cierre'];
                $totalprecio += $row1['precio']; 
                //total que faltan por entregar
                $dif= $row2['solicitado'] - $row2['entregado']- $row3['rechazado'] -$row4['cierre'] - $row41['reutilizadas'] ;
                $totaldif+=$dif;
               
                $preciocomp=floatval($row1['precio']*$row2['solicitado']);
                $totalprecio_completo += $preciocomp;
                
                $filaa++;
                $objActSheet->getRowDimension($filaa)->setRowHeight(40);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$filaa, $row['fecha']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$filaa, $row['fecha_tentativa']);  
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$filaa, $row1['agencia']); 
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$filaa, $row1['item']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$filaa, $row1['prod_descripcion']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$filaa, $row1['receta']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$filaa, $row1['pack']);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$filaa,$row2['solicitado']);  
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$filaa,$row2['entregado']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$filaa,$row3['rechazado']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$filaa,$row4['cierre']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$filaa,$dif);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$filaa,$row1['precio']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$filaa,$preciocomp );
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$filaa,$_POST['finca']);
                //agregano imagen
                if(file_exists("../images/productos/".$row1['id'].".jpg")){          
                    $objDrawing = new PHPExcel_Worksheet_Drawing();
                    $objDrawing->setName('Logo');
                    $objDrawing->setDescription('Logo');
                    $objDrawing->setPath("../images/productos/".$row1['id'].".jpg");
                    $objDrawing->setHeight(45);
                    $objDrawing->setCoordinates('P'.$filaa);
                    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
                }
                            
            }
        }
        $filaa++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$filaa, "Total General");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$filaa, "");  
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$filaa, ""); 
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$filaa, "");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$filaa, "");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$filaa, "");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$filaa, "");

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$filaa,$totalsol);  
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$filaa,$totalent);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$filaa,$totalrech);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$filaa,$totalcierre);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$filaa,$totaldif);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$filaa,$totalprecio);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$filaa,$totalprecio_completo);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$filaa,'');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$filaa,'');
        
        $letra = 'A';
        for ($i=1; $i <= count($TIT_COLUM); $i++) {

           $objPHPExcel->getActiveSheet()->getStyle($letra.$filaa)->getFont()->setBold(true);
           $letra ++;
        }
        
        //Sumar los totales para el total general
        $TOTALPRECIO += $totalprecio;
        $TOTALSOL    += $totalsol;
        $TOTALENTSTRACK    += $totalentstrack;
        $TOTALENTTRACK   += $totalenttrack;
        $TOTALENT    += $totalent;
        $TOTALRECH   += $totalrech;
        $TOTALCIERRE += $totalcierre;
        $TOTALREUT += $totalreut;
        $TOTALDIF    += $totaldif;
        $TOTALPRECIO_COMPLETO+=$totalprecio_completo;
        //Resetear los subtotales
        $totalprecio = 0;
        $totalsol    = 0;
        $totalentstrack   = 0;	
        $totalenttrack    = 0;	
        $totalent    = 0;	
        $totalrech   = 0;
        $totalcierre = 0;
        $totalreut = 0;
        $totaldif    = 0;
        $totalprecio_completo=0;
        
        $filaa++;
    }

//Aqui se crea el docuemnto completo con todas las filas insertadas
$objPHPExcel->getActiveSheet()->setTitle('Hoja1');

$objPHPExcel->setActiveSheetIndex(0);	

//Worksheet estilo predeterminado (de forma predeterminada diferentes necesidades y Preferencias Configurar individualmente)
$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Pedido a la Finca.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');