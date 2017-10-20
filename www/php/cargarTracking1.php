<?php
///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");
include('date.php');
include('convertHex-Dec.php');
include('consecutivo.php');
include ('PHPExcel/IOFactory.php');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////CONEXION A DB
$user = $_SESSION["login"];
$rol = $_SESSION["rol"];
$ip = $_SERVER['REMOTE_ADDR'];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Carga de ordenes</title>
        <script type="text/javascript" src="../js/script.js"></script>
    </head>
    <body>
        <?php
        $orden = 0;
        $fila = 1;
        $array = array(); 
        $dir = "./Archivos subidos/";

//contar archivos
        $total_excel = count(glob("$dir/{*.csv}", GLOB_BRACE));  //("$dir/{*.xlsx,*.xls,*.csv}",GLOB_BRACE));
        if ($total_excel == 0) {
            echo "<font color='red'>No hay archivo para leer...</font><br>";
        } else {
            echo "Total de archivos cargados: " . $total_excel . "<br>";

            //renombrarlos para cargarlos
            $a = 1;
            $excels = (glob("$dir/{*.csv}", GLOB_BRACE));
            foreach ($excels as $cvs) {
                $expr = explode("/", $cvs);
                $nombre = array_pop($expr);
                rename("$dir/$nombre", "$dir/$a.csv");
                $a++;
            }
        }

//Aqui leemos cada uno de los excel cargados y se guardan sus datos a la BD
        for ($i = 1; $i <= $total_excel; $i++) {
            $orden ++;
            if (($gestor = fopen("$dir/$i.csv", "r")) !== FALSE) {
                while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                    $numero = count($datos);
                    for ($c = 0; $c < $numero; $c++) {
                        $array[$fila][$c] = $datos[$c];
                    }
                    $fila++;
                }
                //cierro el handle de directorio
                fclose($gestor);
                //elimino el excel leido del servidor
                unlink("$dir/$i.csv");
            }
        }

        $j = 2; //el numero de filas empieza por dos porque en el archivo las dos primeras filas no nos sirven
        $contador = 1;
        $fila = $fila - 1;
        echo "Cantidad de filas del archivo leído: " . $fila . "<br>";

//Aqui recorro cada una de las filas leida de las ordenes
        for ($j = 2; $j <= $fila; $j++) {
            $Tracking = $array [$j]['5'];
            $Ponumber = trim($array [$j]['2']);
            $CustNumber = $array [$j]['3'];
            $item = $array [$j]['4'];

            //Consultar la BD para identificar que id tiene la orden con el ponumber y custnumber leido
            //selecciona los registros asociados a Ponumber and Custnumber 
            if ($Ponumber == '' || $CustNumber == '') {
                echo "<font color='red'>La orden tiene valores vacios de Ponumber , custnumber </font><br>";
                continue;
            } else {

                //verificamos en detalle orden si existe el tracking
                $query = "select * from tbldetalle_orden where tracking = '$Tracking' LIMIT 1";
                $result = mysqli_query($link, $query) or die("Error verificando si el tracking existe");
                //existe el tracking en detalle orden
                while ($row = mysqli_fetch_array($result)) {
                    if ($row['tracking'] != "") {
                        echo "Fila:" . $j;
                        echo "<font color='red'> El tracking:" . $Tracking . " ya existe en el sistema con el Ponumber " . $row['Ponumber'] . " , custnumber " . $row['Custnumber'] . " e item " . $row['cpitem'] . "</font><br>";
                        //pasamos a la siguiente fila

                        continue 2; //esto saltara al bucle que envuelve este while que sera el for
                    }
                }

                //aqui sigue si no existe el tracking
                //Verifico si la orden es reshipped
                $query1 = "select * from  tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item' and reenvio= 'Forwarded' AND estado_orden!='Canceled' and tracking='' LIMIT 1";

                $row1 = mysqli_query($link, $query1) or die("Error verificando si la orden es un reenvio");
                $row11 = mysqli_fetch_array($row1);
                $ray = mysqli_num_rows($row1);
                //Si existen valores
                if ($ray != 0) {
                    $query2 = "Update tbldetalle_orden set tracking='$Tracking', status = 'Shipped' where id_orden_detalle = '" . $row11['id_orden_detalle'] . "'";
                    $tracking_insertado = mysqli_query($link, $query2);
                    echo "Fila:" . $j;
                    echo " El tracking: " . $Tracking . " y el Ponumber: " . $Ponumber . " fueron insertados correctamente.<br>";

                    $fecha = date('Y-m-d H:i:s');
                    $SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`) 
                            VALUES ('$usuario','Subir Tracking','$fecha','$ip')";
                    $consultaHist = mysqli_query($link, $SqlHistorico) or die("Error actualizando la bitacora de usuarios");

                    continue;
                }

                //no existe la orden con tracking vacio y forwared

                $query3 = "select id_orden_detalle,tracking,Ponumber from  tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item' AND estado_orden!='Canceled' and tracking='' LIMIT 1";
                $row3 = mysqli_query($link, $query3) or die("Error verificando si la orden es un reenvio");
                $row33 = mysqli_fetch_array($row3);
                $ray3 = mysqli_num_rows($row3);
                if ($ray3 != 0) {
                    $query3 = "Update tbldetalle_orden set tracking='$Tracking', status = 'Shipped' where id_orden_detalle = '" . $row33['id_orden_detalle'] . "'";
                    $tracking_insertado = mysqli_query($link, $query3);
                    echo "Fila:" . $j;
                    echo " El tracking: " . $Tracking . " y el Ponumber: " . $Ponumber . " fueron insertados correctamente.<br>";

                    $fecha = date('Y-m-d H:i:s');
                    $SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`) 
                            VALUES ('$usuario','Subir Tracking','$fecha','$ip')";
                    $consultaHist = mysqli_query($link, $SqlHistorico) or die("Error actualizando la bitacora de usuarios");
                } else {
                    echo " El ponumber" . $row33['Ponumber'] . " ya tiene asignado un tracking:" . $row33['tracking'] ." o La orden se encuentra  en estado Cancelada<br>";
                }
            }
        }


        $handle = opendir($dir);
        while ($file = readdir($handle)) {
            if (is_file($dir . $file)) {
                unlink($dir . $file);
            }
        }
        echo "<a href='javascript:history.back(1)'>Volver Atras</a>";
        ?>
    </body>
</html>
