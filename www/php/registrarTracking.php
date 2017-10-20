<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL); 
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");
require_once('barcode.inc.php');

$user = $_SESSION["login"];
$rol = $_SESSION["rol"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
$jsondata = array();

//verificar si existe la guia master
if (isset($_POST["accion"]) && $_POST["accion"] == "buscarguia") {
    $sql1 = "SELECT MAX(palet) as palet,guia_master FROM tblcoldroom WHERE guia_master='" . $_POST["master"] . "'";
    $query1 = mysqli_query($link, $sql1)or die("Error buscando el palet...");
    $row = mysqli_fetch_array($query1);

    if ($row['palet'] != "") {

        $jsondata["existe_guia"] = "si";
        $jsondata["palet"] = $row['palet'];
    } else {
        $jsondata["existe_guia"] = "no";
    }
    echo json_encode($jsondata);
    return;
}

//seleccionar todos los palets de una guia master
if (isset($_POST["accion"]) && $_POST["accion"] == "buscarpalet") {
    $sql1 = "SELECT DISTINCT palet FROM tblcoldroom WHERE guia_master='" . $_POST["master"] . "' order by palet ASC";
    $query1 = mysqli_query($link, $sql1)or die("Error buscando el palet...");
    while ($row = mysqli_fetch_array($query1)) {
        $jsondata[] = $row['palet'];
    }
    echo json_encode($jsondata);
    return;
}

if (isset($_POST["accion"]) && $_POST["accion"] == "palet") {
    $sql1 = "SELECT palet,guia_master FROM tblcoldroom WHERE palet ='" . $_POST["palet"] . "' and guia_master='" . $_POST["master"] . "'";
    $query1 = mysqli_query($link, $sql1)or die("Error buscando el palet...");
    $cant = mysqli_num_rows($query1);
    if ($cant != 0) {
        echo "ERROR";
    } else {
        echo "OK";
    }
    return;
}

$tracking = strtoupper($_POST['tracking']);
$codebar = strtoupper($_POST['codigo']);
$guia_master = strtoupper($_POST['guia_master']);
$palet = strtoupper($_POST['palet']);
$tracking = limpia_espacios($tracking);
$codebar = limpia_espacios($codebar);
list($codfinca, $item, $codigo) = explode("-", $codebar);
$fechasalida = date('Y-m-d');
$cons = $_POST['consolidado'];


///////////////////////////////////////////////////////////////////////////////////
//codigo para traquear ordenes consolidadas
//verifico si ese codigo no existe en detalleo orden
///////////////////////////////////////////////////////////////////////////////////
if (isset($cons) && $cons == "ON") {
    $sql1 = "SELECT Ponumber,codigo FROM tbldetalle_orden WHERE codigo ='" . $codigo . "'";
    $query1 = mysqli_query($link, $sql1)or die("Error buscando el código...");
    $cant = mysqli_num_rows($query1);
    $result = mysqli_fetch_array($query1);

    if ($cant > 0) {
        $jsondata["error"] = 1;
        $jsondata["mensaje"] = 'El código único leído ya fue asignado a la orden con Ponumber ' . $result['Ponumber'];
        echo json_encode($jsondata);
        return;
    } else {
        $sql1 = "SELECT item, finca,rechazada FROM tblcoldroom WHERE codigo ='" . $codigo . "'";
        $resp1 = mysqli_query($link, $sql1)or die("Error buscando los datos de la caja " . $codigo);
        $result1 = mysqli_fetch_row($resp1);

        //verifico que el codigo exista en las entradas a cuarto frio
        $ray1 = mysqli_num_rows($resp1); //cuento las filas devueltas del codigo
        //sino existe el codigo en el cuarto frio
        if ($ray1 <= 0) {
            $jsondata["error"] = 1;
            $jsondata["mensaje"] = 'El código de la caja leído no tiene entrada registrada';
            echo json_encode($jsondata);
            return;
        } else {
            //verifico que la caja no este rerchazada
            if ($result1[2] == '2') {
                $jsondata["error"] = 1;
                $jsondata["mensaje"] = 'Esta caja esta rechazada y no puede ser traqueada';
                echo json_encode($jsondata);
                return;
            }

            $sql1 = "SELECT id_orden_detalle FROM tbldetalle_orden WHERE consolidado ='Y' AND codigo ='%" . $codigo . "%' AND tracking='' AND cpitem='" . $result1[0] . "' LIMIT 1";
            $query1 = mysqli_query($link, $sql1)or die("Error buscando el código...");
            $result = mysqli_fetch_row($query1);
            $cant = mysqli_num_rows($query1);
            if ($cant > 0) {
                //actualizo detalleorden 
                $sql = "update tbldetalle_orden set coldroom='Yes', farm='" . $result1[1] . "',tracking='" . $codigo . "', codigo='" . $codigo . "' where id_orden_detalle ='" . $result[0] . "'";
                $query = mysqli_query($link, $sql);

                //actualizo coodroom
                $consulta1 = "update tblcoldroom set salida='Si', fecha_tracking='" . $fechasalida . "', tracking_asig = '" . $codigo . "', guia_master='" . $guia_master . "', palet='" . $palet . "' where codigo ='" . $codigo . "'";
                $ejecutar1 = mysqli_query($link, $consulta1);

                if ($ejecutar1) {
                    //Actualizar los pedidos de las etiquetas
                    $sentencia = "Update tbletiquetasxfinca set entregado=1, estado=1 WHERE codigo='" . $codigo . "'";
                    $consulta = mysqli_query($link, $sentencia);
                    if ($consulta) {
                        $jsondata["error"] = 2;
                        $jsondata["mensaje"] = 'Tracking movido al cuarto frío y listo para volar';
                        $hoy = date('Y-m-d');
                        $sql = "SELECT * FROM tblcoldroom where fecha_tracking ='" . $hoy . "' AND salida = 'Si' AND tracking_asig='" . $codigo . "'";
                        $val = mysqli_query($link, $sql);
                        while ($row = mysqli_fetch_array($val)) {
                            $jsondata["tracking"] = $row['tracking_asig'];
                            $jsondata["finca"] = $row['finca'];
                            $jsondata["item"] = $row['item'];
                            $jsondata["codigo"] = $row['codigo'];
                            $jsondata["guia_master"] = $row['guia_master'];
                            $jsondata["palet"] = $row['palet'];
                        }
                        echo json_encode($jsondata);
                        return;
                    } else {
                        $jsondata["error"] = 1;
                        $jsondata["mensaje"] = 'Error actualizando pedido de etiquetas....';
                        echo json_encode($jsondata);
                        return;
                    }
                }
            } else {
                $jsondata["error"] = 1;
                $jsondata["mensaje"] = 'ESA ORDEN NO ES UNA ORDEN CONSOLIDADA';
                echo json_encode($jsondata);
                return;
            }
        }
    }
} else {
    if ($tracking == '' || $codigo == '') {
        $jsondata["error"] = 1;
        $jsondata["mensaje"] = 'Error: Faltan datos por introducir, por favor complete todos los campos.';
        echo json_encode($jsondata);
        return;
    } else {

        //Obtengo los valores del item, finca y cuarto frio de la orden para comparar con los leidos
        $sql1 = "SELECT Ponumber,codigo FROM tbldetalle_orden WHERE codigo ='" . $codigo . "'";
        $query1 = mysqli_query($link, $sql1)or die("Error buscando el código...");
        $cant = mysqli_num_rows($query1);
        $result = mysqli_fetch_array($query1);

        if ($cant > 0) {////////////////////////////
            $jsondata["error"] = 1;
            $jsondata["mensaje"] = 'El código único leído ya fue asignado a la orden con Ponumber ' . $result['Ponumber'];
            echo json_encode($jsondata);
            return;
        } else {

            //Obtengo los valores del item y la finca de la caja con el codigo leido
            $sentecia = "SELECT item, finca,rechazada FROM tblcoldroom WHERE codigo ='" . $codigo . "'";
            $consulta = mysqli_query($link, $sentecia)or die("Error buscando los datos de la caja " . $codigo);

            //Obtengo los valores del item, finca y cuarto frio de la orden para comparar con los leidos
            $sql = "SELECT farm,coldroom,cpitem,codigo FROM tbldetalle_orden WHERE tracking ='" . $tracking . "'";
            $query = mysqli_query($link, $sql)or die("Error buscando el tracking....");

            $ray = mysqli_num_rows($query); //cuento las filas devueltas del tracking
            $row = mysqli_fetch_array($query);


            if ($ray <= 0) {///////////////////////////////////////////////
                $jsondata["error"] = 1;
                $jsondata["mensaje"] = 'El tracking leído no existe en el sistema';
                echo json_encode($jsondata);
                return;
            } else {
                //Si el tracking existe verifico que el codigo exista en las entradas a cuarto frio
                $ray1 = mysqli_num_rows($consulta); //cuento las filas devueltas del codigo

                $fila = mysqli_fetch_array($consulta);
                if ($ray1 <= 0) {/////////////////////////////////////
                    $jsondata["error"] = 1;
                    $jsondata["mensaje"] = 'El código de la caja leído no tiene entrada registrada';
                    echo json_encode($jsondata);
                    return;
                } else {

                    //veirifico que el campo rechazada sea 2 que significa que las cajas estan rechazadas y por tanto no se pueden traquear											
                    if ($fila['rechazada'] == '2') {
                        $jsondata["error"] = 1;
                        $jsondata["mensaje"] = 'Esta caja esta rechazada y no puede ser traqueada';
                        echo json_encode($jsondata);
                        return;
                    }

                    //Si la orden con el tracking leida no ha sido introducida al cuarto frio entonces la recibo
                    if (strcmp($row ['coldroom'], 'No') == 0 && $row ['codigo'] == 0) {
                        //verifico que los datos del codigo y el tracking sean iguales
                        if ($row['cpitem'] != $fila['item']) {
                            //comparo el item de cuarto firo con el 101010
                            if ($fila['item'] == '101010' || $fila['item'] == '101011' || $fila['item'] == '101012' || $fila['item'] == '101013') {
                                //Actualizo la orden pistoleada
                                $sql = "update tbldetalle_orden set coldroom='Yes', farm='" . $fila['finca'] . "', codigo='" . $codigo . "' where tracking ='" . $tracking . "'";
                                $query = mysqli_query($link, $sql)or die("Error moviendo tracking al cuarto frio....");

                                //Descuento una entrad del cuarto frio
                                $consulta1 = "update tblcoldroom set salida='Si', fecha_tracking='" . $fechasalida . "', tracking_asig = '" . $tracking . "', guia_master='" . $guia_master . "', palet='" . $palet . "' where codigo ='" . $codigo . "'";
                                $ejecutar1 = mysqli_query($link, $consulta1);

                                if ($ejecutar1) {
                                    //Actualizar los pedidos de las etiquetas
                                    $sentencia = "Update tbletiquetasxfinca set entregado=1, estado=1 WHERE codigo='" . $codigo . "'";
                                    $consulta = mysqli_query($link, $sentencia);
                                    if ($consulta) {
                                        $jsondata["error"] = 2;
                                        $jsondata["mensaje"] = 'Tracking movido al cuarto frío y listo para volar';
                                        $hoy = date('Y-m-d');
                                        $sql = "SELECT * FROM tblcoldroom where fecha_tracking ='" . $hoy . "' AND salida = 'Si' AND tracking_asig='" . $tracking . "'";
                                        $val = mysqli_query($link, $sql);
                                        while ($row = mysqli_fetch_array($val)) {
                                            $jsondata["tracking"] = $row['tracking_asig'];
                                            $jsondata["finca"] = $row['finca'];
                                            $jsondata["item"] = $row['item'];
                                            $jsondata["codigo"] = $row['codigo'];
                                            $jsondata["guia_master"] = $row['guia_master'];
                                            $jsondata["palet"] = $row['palet'];
                                        }
                                        echo json_encode($jsondata);
                                        return;
                                    } else {////////////////
                                        $jsondata["error"] = 1;
                                        $jsondata["mensaje"] = 'Error actualizando pedido de etiquetas....';
                                        echo json_encode($jsondata);
                                        return;
                                    }
                                } else {//////////////////////////
                                    $jsondata["error"] = 1;
                                    $jsondata["mensaje"] = 'Error moviendo tracking al cuarto frio....';
                                    echo json_encode($jsondata);
                                    return;
                                }
                            } else {
                                $jsondata["error"] = 1;
                                $jsondata["mensaje"] = 'Ese item no corresponde con el tracking leído. Por favor revise sus datos..';
                                echo json_encode($jsondata);
                                return;
                            }
                        } else {
                            //Actualizo la orden pistoleada
                            $sql = "update tbldetalle_orden set coldroom='Yes', farm='" . $fila['finca'] . "', codigo='" . $codigo . "' where tracking ='" . $tracking . "'";
                            $query = mysqli_query($link, $sql)or die("Error moviendo tracking al cuarto frio....");

                            //Descuento una entrad del cuarto frio
                            $consulta1 = "update tblcoldroom set salida='Si', fecha_tracking='" . $fechasalida . "', tracking_asig = '" . $tracking . "', guia_master='" . $guia_master . "', palet='" . $palet . "' where codigo ='" . $codigo . "'";
                            $ejecutar1 = mysqli_query($link, $consulta1);

                            if ($ejecutar1) {
                                //Actualizar los pedidos de las etiquetas
                                $sentencia = "Update tbletiquetasxfinca set entregado=1, estado=1 WHERE codigo='" . $codigo . "'";
                                $consulta = mysqli_query($link, $sentencia);
                                if ($consulta) {
                                    $jsondata["error"] = 2;
                                    $jsondata["mensaje"] = 'Tracking movido al cuarto frío y listo para volar';
                                    $hoy = date('Y-m-d');
                                    $sql = "SELECT * FROM tblcoldroom where fecha_tracking ='" . $hoy . "' AND salida = 'Si' AND tracking_asig='" . $tracking . "'";
                                    $val = mysqli_query($link, $sql);
                                    while ($row = mysqli_fetch_array($val)) {
                                        $jsondata["tracking"] = $row['tracking_asig'];
                                        $jsondata["finca"] = $row['finca'];
                                        $jsondata["item"] = $row['item'];
                                        $jsondata["codigo"] = $row['codigo'];
                                        $jsondata["guia_master"] = $row['guia_master'];
                                        $jsondata["palet"] = $row['palet'];
                                    }
                                    echo json_encode($jsondata);
                                    return;
                                } else {
                                    $jsondata["error"] = 1;
                                    $jsondata["mensaje"] = 'Error actualizando pedido de etiquetas....';
                                    echo json_encode($jsondata);
                                    return;
                                }
                            } else {
                                $jsondata["error"] = 1;
                                $jsondata["mensaje"] = 'Error moviendo tracking al cuarto frio....';
                                echo json_encode($jsondata);
                                return;
                            }
                        }
                    } else {  ///////////////////////////////////////////							
                        $jsondata["error"] = 1;
                        $jsondata["mensaje"] = 'Ya ese Tracking fue movido al cuarto frío, deséchelo';
                        echo json_encode($jsondata);
                        return;
                    }
                } //fin else
            }//fin else
        }//fin else
    }//fin else
    echo json_encode($jsondata);
}

function limpia_espacios($cadena) {
    $cadena = str_replace(' ', '', $cadena);
    return $cadena;
}
