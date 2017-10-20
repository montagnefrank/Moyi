<?php
///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");
require_once ('PHPExcel.php');
include('date.php');
include('convertHex-Dec.php');
include('consecutivo.php');
include('codigounico.php');
include ('PHPExcel/IOFactory.php');

session_start();
$user = $_SESSION["login"];
$pass = $_SESSION["pass"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

//OBTENIENDO LA FINCA DEL USUARIO
$sql = "SELECT finca FROM tblusuario WHERE cpuser = '" . $user . "'";
$query = mysqli_query($link, $sql) or die("Error seleccionando la finca de este usuario");
$row = mysqli_fetch_array($query);
$finca = $row['finca'];
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
            echo "<font color='red'>No hay archivo para leer o el formato de archivo no es csv...</font><br>";
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
                        $array [$fila][$c] = addslashes($datos[$c]);
                    }
                    $fila++;
                }
                //cierro el handle de directorio
                fclose($gestor);
                //elimino el excel leido del servidor
                unlink("$dir/$i.csv");
            }
        }

        //Se crea la tabla para mostrar en el navegador los datos de las ordenes cargadas
        echo '<table width="100%" border="1" style="border-collapse:collapse;" cellspacing="0" cellpadding="0">';
        $j = 2;
        $contador = 1;
        $fila = $fila - 1;
        echo "Cantidad de filas del archivo leído: " . $fila . "<br>";
        while ($j <= $fila) { //Aqui recorro cada una de las filas leida de las ordenes
            $Tracking = $array [$j]['5'];
            $Ponumber = $array [$j]['2'];
            $CustNumber = $array [$j]['3'];
            $item = $array [$j]['4'];
            $Guia_madre = $array [$j]['13'];
            $Guia_hija = $array [$j]['14'];

            $consolidado = $array [$j]['19'];
            $vuelo = $array [$j]['15'];
            $entrega = $array [$j]['16'];

            //si no es consolidado hay que formatear las fechas al formato que tienen la db
            if ($consolidado != "Y") {
                //Armar la feca de vuelo
//                list($dia, $mes, $anno) = explode('/', $vuelo);
//                $vuelo = $anno . "-" . $mes . "-" . $dia;

                //Armar la feca de entrega
//                list($dia, $mes, $anno) = explode('/', $entrega);
//                $entrega = $anno . "-" . $mes . "-" . $dia;
            }

            $servicio = $array [$j]['17'];
            $aerolinea = $array [$j]['18'];

            //Consultar la BD para identificar que id tiene la orden con el ponumber y custnumber leido
            //selecciona los registros asociados a Ponumber and Custnumber 
            if ($Ponumber == '' || $CustNumber == '' || $Guia_madre == '' || $Guia_hija == '') {
                echo "<font color='red'>La orden con Ponumber " . $Ponumber . " , custnumber " . $CustNumber . " e item " . $item . " le faltan datos, por favor revise.</font><br>";
                $j++;
            } else if (!validar_guia(trim($Guia_madre), 'm') || !validar_guia(trim($Guia_hija), 'h')) {
                echo "<font color='red'>La orden con Ponumber " . $Ponumber . " , custnumber " . $CustNumber . " e item " . $item . " tiene errores en los formatos de las guias madre e hija.</font><br>";
                $j++;
            } else {
                //Verifico si la orden es reshipped
                $query = "select id_orden_detalle,tracking from tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item' and reenvio= 'Forwarded'";
                $row = mysqli_query($link, $query) or die("Error verificando si la orden es un reenvio");
                $ray = mysqli_num_rows($row); //cuento las filas devueltas
                //Si tiene reshiped actualizo las ordenes con reshiped
                if ($ray != 0) {
                    //insertar nuevo tracking
                    $query = "select tracking, Ponumber, Custnumber, cpitem from tbldetalle_orden where tracking = '$Tracking'";
                    $row = mysqli_query($link, $query) or die("Error verificando si el tracking existe");
                    $ray = mysqli_num_rows($row); //cuento las filas devueltas
                    //si existe este tracking ya en el detalleorden
                    if ($ray != 0) {
                        $sql = mysqli_fetch_array($row);
                        $TRACKING = $sql['tracking'];
                        $PONUMBER = $sql['Ponumber'];
                        $CUSTNUMBER = $sql['Ponumber'];
                        $ITEM = $sql['cpitem'];
                        echo "Cantidad de ordenes:" . $ray . "<BR>";
                        echo "<font color='red'>La orden con Ponumber " . $Ponumber . " , Custnumber " . $CustNumber . " ya tiene un tracking asignado que es: " . $TRACKING . " y usted intenta agregar este tracking: " . $Tracking . "<font><br>";
                        $j++;
                    } else {

                        //Pregunto si el custnumber y ponumber e item existen, de ser asi asi lo actualizo
                        $query = "SELECT id_orden_detalle,tracking from tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item' and reenvio= 'Forwarded'";
                        //echo "select id_detalleorden from  tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item'";

                        $row = mysqli_query($link, $query) or die("Error verificando si la orden existe");
                        $ray = mysqli_num_rows($row); //cuento las filas devueltas

                        if ($ray == 0) {
                            //si no obtubo ninguna fila es pq esa orden no ha sido introducida
                            echo "Cantidad de órdenes:" . $ray . "<BR>";
                            echo "<font color='red'>La orden no existe en el sistema. Por favor inserte la orden con Ponumber " . $Ponumber . " , custnumber " . $CustNumber . " e item " . $item . "</font><br>";
                            $j++;
                        } else {
                            echo "Cantidad de ordenes:" . $ray . "<BR>";
                            for ($i = 0; $i < $ray; $i++) {
                                $sql = mysqli_fetch_array($row);
                                $id_order = $sql['id_orden_detalle'];
                                $tracking = $sql['tracking'];

                                $Tracking = $array [$j]['5'];
                                $Ponumber = $array [$j]['2'];
                                $CustNumber = $array [$j]['3'];
                                $item = $array [$j]['4'];
                                //echo "el tracing es: ".$tracking;
                                if ($tracking == '') {
                                    //si las ordenes subidas son consolidadas el mismo tracking subido es el codigo de la orden
                                    if ($consolidado == 'Y') {
                                        $codigo = $Tracking;
                                    } else {
                                        //Se genera el codigo unico de la caja
                                        $codigo = generarCodigoUnico();
                                        //Se inserta en la tabla de codigos
                                        $consulta = "INSERT INTO tblcodigo (`codigo`,`finca`) VALUES ('$codigo','$finca')";
                                        $ejecutar = mysqli_query($link, $consulta) or die("Error insertando el código único");
                                    }
                                    
                                    $fecha = date('Y-m-d');
                                    //Crear una entrada al cuarto frio de las fincas autonomas
                                    $sql = "INSERT INTO tblcoldrom_fincas (`codigo_unico`,`item`, `finca`,`fecha`,`guia_m`, `guia_h`,`entrega`,`servicio`,`vuelo`,`aerolinea`,`tracking_asig`) VALUES ('$codigo','$item','$finca','$fecha','$Guia_madre','$Guia_hija','$entrega','$servicio','$vuelo','$aerolinea','$Tracking')";

                                    $insertado = mysqli_query($link, $sql) or die("COLDROMMFINCAS ERROR " . mysqli_error($link));

                                    //Actualizar la orden con los datos de la finca y caja
                                    $sql11 = "Update tbldetalle_orden Set tracking='$Tracking', status = 'Shipped', farm='$finca', codigo='$codigo' where id_orden_detalle = '$id_order'"; // actualizar el eBing ='$eBing',
                                    $actualizado = mysqli_query($link, $sql11);

                                    

                                    //echo "Update tracking Set tracking='$Tracking', eBing ='$eBing' where id = '$id'";
                                    if ($actualizado && $insertado) {
                                        echo "El tracking: " . $Tracking . " fue cargado correctamente.<br>";
                                        $j++;
                                    } else {
                                        echo "<font color='red'>Error cargando el tracking.</font><br>";
                                    }
                                } else {
                                    echo "<font color='red'>La orden con el tracking: " . $tracking . " ya fue insertado anteriormente.</font><br>";
                                    $j++;
                                }
                            }
                        }
                    }
                } 
                else {

                    //insertar nuevo tracking
                    $query = "select tracking, Ponumber, Custnumber, cpitem from tbldetalle_orden where tracking = '$Tracking'";
                    $row = mysqli_query($link, $query) or die("Error verificando el tracking");
                    $ray = mysqli_num_rows($row); //cuento las filas devueltas
                    //si existe este tracking
                    if ($ray != 0) {
                        $sql = mysqli_fetch_array($row);
                        $TRACKING = $sql['tracking'];
                        $PONUMBER = $sql['Ponumber'];
                        $CUSTNUMBER = $sql['Ponumber'];
                        $ITEM = $sql['cpitem'];

                        echo "Cantidad de ordenes:" . $ray . "<BR>";
                        echo "<font color='red'>La orden con Ponumber " . $Ponumber . " , Custnumber " . $CustNumber . " ya tiene un tracking asignado que es: " . $TRACKING . " y usted intenta agregar este tracking: " . $Tracking . "<font><br>";
                        $j++;
                    } else {
                        //Pregunto si el custnumber y ponumber e item existen, de ser asi asi lo actualizo
                        $query = "select id_orden_detalle,tracking from tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item'";
                        //echo "select id_detalleorden from  tbldetalle_orden where Ponumber= '$Ponumber' and Custnumber = '$CustNumber' and cpitem ='$item'";

                        $row = mysqli_query($link, $query) or die("Error verificando si la orden existe");
                        $ray = mysqli_num_rows($row); //cuento las filas devueltas

                        if ($ray == 0) {
                            //si no obtubo ninguna fila es pq esa orden no ha sido introducida
                            echo "Cantidad de ordenes:" . $ray . "<BR>";
                            echo "<font color='red'>La orden no existe en el sistema. Por favor inserte la orden con Ponumber " . $Ponumber . " , custnumber " . $CustNumber . " e item " . $item . "</font><br>";
                            $j++;
                        } else {
                            echo "Cantidad de ordenes:" . $ray . "<BR>";
                            for ($i = 0; $i < $ray; $i++) {
                                $sql = mysqli_fetch_array($row);
                                $id_order = $sql['id_orden_detalle'];
                                $tracking = $sql['tracking'];

                                $Tracking = $array [$j]['5'];
                                $Ponumber = $array [$j]['2'];
                                $CustNumber = $array [$j]['3'];
                                $item = $array [$j]['4'];

                                if ($tracking == '') {
                                    //si las ordenes subidas son consolidadas el mismo tracking subido es el codigo de la orden
                                    if ($consolidado == 'Y') {
                                        $codigo = $Tracking;
                                    } else {
                                        //Se genera el codigo unico de la caja
                                        $codigo = generarCodigoUnico();
                                        //Se inserta en la tabla de codigos
                                        $consulta = "INSERT INTO tblcodigo (`codigo`,`finca`) VALUES ('$codigo','$finca')";
                                        $ejecutar = mysqli_query($link, $consulta) or die("Error insertando el código único");
                                    }
                                    
                                    $fecha = date('Y-m-d');
                                    //Crear una entrada al cuarto frio de las fincas autonomas
                                    $sql = "INSERT INTO tblcoldrom_fincas (`codigo_unico`,`item`, `finca`,`fecha`,`guia_m`, `guia_h`,`entrega`,`servicio`,`vuelo`,`aerolinea`,`tracking_asig`) VALUES ('$codigo','$item','$finca','$fecha','$Guia_madre','$Guia_hija','$entrega','$servicio','$vuelo','$aerolinea','$Tracking')";
                                    $insertado = mysqli_query($link, $sql) or die("COLDROMMFINCAS ERROR " . mysqli_error($link));

                                    //Actualizar la orden con los datos de la finca y caja
                                    $sql11 = "Update tbldetalle_orden Set tracking='$Tracking', status = 'Shipped', farm='$finca', codigo='$codigo' where id_orden_detalle = '$id_order'"; // actualizar el eBing ='$eBing',
                                    $actualizado = mysqli_query($link, $sql11);

                                    
                                    //echo "Update tracking Set tracking='$Tracking', eBing ='$eBing' where id = '$id'";
                                    if ($actualizado && $insertado) {
                                        echo "El tracking: " . $Tracking . " fue cargado correctamente.<br>";
                                        $j++;
                                    } else {
                                        echo "<font color='red'>Error cargando el tracking.</font><br>";
                                    }
                                } else {
                                    echo "<font color='red'><font color='red'>La orden con el tracking: " . $tracking . " ya fue insertado anteriormente.</font><br>";
                                    $j++;
                                }
                            }//fin for
                        }//else
                    }//else
                }
            }
        } //while
        /* $handle = opendir($dir); 
          while ($file = readdir($handle))  {
          if (is_file($dir.$file)) {
          unlink($dir.$file);
          }
          }
         */
        echo "<a href='javascript:history.back(1)'>Volver Atras</a>";

        function validar_guia($guia, $tipo) {
            if ($tipo == 'm') {
                if (strlen($guia) != 12) {
                    return false;
                }
            }
            if ($tipo == 'h') {
                if (strlen($guia) < 8) {
                    return false;
                }
            }

            if (substr($guia, 3, 1) != "-") {
                return false;
            }
            return true;
        }
        ?>
    </body>
</html>
