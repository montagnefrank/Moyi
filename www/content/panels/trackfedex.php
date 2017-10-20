<?php
///////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////INCLUIMOS LOS QUE NECESITAMOS PARA CARGAR
require_once ('php/PHPExcel.php');
include('php/date.php');
include('php/convertHex-Dec.php');
include('php/consecutivo.php');
include ('php/PHPExcel/IOFactory.php');
?>
<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="main.php?panel=mainpanel.php">BurtonTech</a></li>
    <li>Venta</li>
    <li class="active">Ver Órdenes </li>
</ul>
<!-- FIN BREADCRUMB -->

<div class="page-title">                    
    <h2><span class="fa fa-cloud-upload"></span> Trackings cargados para FedEx</h2>
</div>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <h3>Visor de eventos</h3>       
            <div class="col-md-12">  
                <div class="content-frame-body content-frame-body-left">
                    <div class="messages messages-img">
                        <?php
                        $message_alert = "<div class=\"item item-visible\"><div class=\"image\"><button class=\"btn btn-danger btn-condensed\"><i class=\"fa fa-exclamation\" aria-hidden=\"true\"></i></button></div><div class=\"text\"><div class=\"heading\"><a > ¡Alerta!</a><span class=\"date\">" . date('H:i') . "</span></div>";
                        $message_info = "<div class=\"item item-visible\"><div class=\"image\"><button class=\"btn btn-info btn-condensed\"><i class=\"fa fa-commenting-o\" aria-hidden=\"true\"></i></button></div><div class=\"text\"><div class=\"heading\"><a > Mensaje</a><span class=\"date\">" . date('H:i') . "</span></div>";
                        $message_success = "<div class=\"item item-visible\"><div class=\"image\"><button class=\"btn btn-success btn-condensed\"><i class=\"fa fa-check-square-o\" aria-hidden=\"true\"></i></button></div><div class=\"text\"><div class=\"heading\"><a > Evento exitoso</a><span class=\"date\">" . date('H:i') . "</span></div>";
                        $message_end = "</div></div>";
                        $carpetaDestino = "uploads/trackings/";
                        if ($_FILES["archivo"]["name"][0]) {
                            # recorremos todos los arhivos que se han subido
                            for ($i = 0; $i < count($_FILES["archivo"]["name"]); $i++) {
                                #divide el nombre del fichero con un .    
                                $explode_name = explode('.', $_FILES["archivo"]["name"][$i]);
                                # si es un formato de excel
                                if ($explode_name[1] == 'csv') {
                                    # si exsite la carpeta o se ha creado
                                    if (file_exists($carpetaDestino) || @mkdir($carpetaDestino)) {
                                        $origen = $_FILES["archivo"]["tmp_name"][$i];
                                        $destino = $carpetaDestino . $_FILES["archivo"]["name"][$i];
                                        # movemos el archivo
                                        if (@move_uploaded_file($origen, $destino)) {
                                            echo $message_success . " " . $_FILES["archivo"]["name"][$i] . " cargado correctamente" . $message_end;
                                            echo $message_success . " Procesando el archivo... " . $message_end;
                                            $orden = 0;
                                            $fila = 1;
                                            $array = array();
                                            $dir = "uploads/trackings/";
                                            //CONTAMOS CUANTOS ARCHIVOS HAY EN LA CARPETA
                                            $total_excel = count(glob("$dir/{*.csv}", GLOB_BRACE));  //("$dir/{*.xlsx,*.xls,*.csv}",GLOB_BRACE));
                                            if ($total_excel == 0) {
                                                echo $message_alert . " <font color='red'>No hay archivo para leer...</font>  " . $message_end;
                                            } else {
                                                echo $message_info . "Total de archivos cargados: " . $total_excel . $message_end;
                                                //LOS RENOMBRAMOS PARA LEERLOS AL CSV EN UN LOOP
                                                $a = 1;
                                                $excels = (glob("$dir/{*.csv}", GLOB_BRACE));
                                                foreach ($excels as $cvs) {
                                                    $expr = explode("/", $cvs);
                                                    $nombre = array_pop($expr);
                                                    rename("$dir/$nombre", "$dir/$a.csv");
                                                    $a++;
                                                }
                                            }
                                            //LEEMOS TODOS LOS ARCHIVOS Y GUARDAMOS LA INFO A UN ARRAY, LUEGO ELIMINO EL ARCHIVO DEL SERVIDOR
                                            for ($i = 1; $i <= $total_excel; $i++) {
                                                $orden ++;
                                                if (($gestor = fopen("$dir/$i.csv", "r")) !== FALSE) {
                                                    while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                                                        $numero = count($datos);
                                                        for ($col = 0; $col < $numero; $col++) {
                                                            $array [$fila][$col] = addslashes($datos[$col]);
                                                        }
                                                        $fila++;
                                                    }
                                                    fclose($gestor);
                                                    unlink("$dir/$i.csv");
                                                }
                                            }
                                            $j = 2; //COMIENZA A LEER A PARTIR DE LA FILA 2
                                            $contador = 1;
                                            $fila = $fila - 1;
                                            echo $message_info . " Cantidad de registros analizados: " . $fila . $message_end;
                                            $errorlog = 1;
                                            //RECORREMOS CADA FILA PARA OBTENER LOS ARRAY PARA LOS QUERY
                                            for ($j = 3; $j <= $fila; $j++) {
                                                
                                                $po_cust = explode("_", $array[$j][2]);

                                                //DECLARAMOS LOS CAMPOS QUE USAREMOS PARA ARMAR LOS QUERY
                                                $Tracking = $array [$j]['0'];
                                                $Ponumber = trim($po_cust[0]);
                                                $CustNumber = $po_cust[1];
                                                $item = $array [$j]['1'];

                                                //VERIFICAMOS CAMPOS VACIOS, EN CASO DE HABERLOS MOSTRAMOS MENSAJE EN PANTALLA
                                                if ($Tracking == '') {
                                                    echo $message_alert . 'La fila <b><font color="red">' . $j . '</font></b> del archivo tiene el campo <b><font color="red">"TRACKING"</font></b> vacío' . $message_end;
                                                }
                                                if ($Ponumber == '') {
                                                    echo $message_alert . 'La fila <b><font color="red">' . $j . '</font></b> del archivo tiene el campo <b><font color="red">"PONUMBER"</font></b> vacío' . $message_end;
                                                }
                                                if ($CustNumber == '') {
                                                    echo $message_alert . 'La fila <b><font color="red">' . $j . '</font></b> del archivo tiene el campo <b><font color="red">"CUSTNUMBER"</font></b> vacío' . $message_end;
                                                }
                                                if ($item == '') {
                                                    echo $message_alert . 'La fila <b><font color="red">' . $j . '</font></b> del archivo tiene el campo <b><font color="red">"ITEM"</font></b> vacío' . $message_end;
                                                }

                                                //DESCARTAMOS LAS FILAS CON CAMPOS VACIOS
                                                if ($Tracking !== '' && $Ponumber !== '' && $CustNumber !== '' && $item !== '') {

                                                    //ALIMENTAMOS LOS QUERY
                                                    $sql_tracking .= "'" . $Tracking . "',";
                                                    $sql_ponumber .= "'" . $Ponumber . "',";
                                                    $sql_custnumber .= "'" . $CustNumber . "',";
                                                    $sql_item .= "'" . $item . "',";
                                                } else {
                                                    echo $message_alert . "La fila " . $j . " no se puede procesar" . $message_end;
                                                }
                                            }

                                            //REMOVEMOS LA ULTIMA COMA PARA EVITAR ERROR DE SINTAXIS
                                            $sql_tracking = substr(trim($sql_tracking), 0, -1);
                                            $sql_ponumber = substr(trim($sql_ponumber), 0, -1);
                                            $sql_custnumber = substr(trim($sql_custnumber), 0, -1);
                                            $sql_item = substr(trim($sql_item), 0, -1);
                                            //CONVERTIMOS LAS COLUMNAS EN ARRAYS PARA ASOCIAR LOS VALORES DEL UPDATE
                                            $array_tracking = explode(",", $sql_tracking);
                                            $array_ponumber = explode(",", $sql_ponumber);
                                            $array_custnumber = explode(",", $sql_custnumber);
                                            $array_item = explode(",", $sql_item);

                                            array_multisort($array_item, SORT_ASC, $array_tracking, $array_ponumber, $array_custnumber);

                                            //LO ORDENAMOS POR CPITEM PARA EVITAR COLISIONES EN EL UPDATE
                                            //array_multisort($array_item,$array_custnumber,$array_ponumber,$array_tracking);
                                            //AGREGAMOS UN ELEMENTO AL INICIO PARA EVITAR DESCARTAR VALOR 0
                                            array_unshift($array_tracking, "inicio");
                                            array_unshift($array_ponumber, "inicio");
                                            array_unshift($array_custnumber, "inicio");
                                            array_unshift($array_item, "inicio");

                                            //REMOVEMOS LOS TRACKING DUPLICADOS DENTRO DEL MISMO ARCHIVO
                                            $array_tracking = array_unique($array_tracking);

                                            //VALIDAMOS QUE LOS TRACKINGS NO SE ENCUENTREN YA EN SISTEMA
                                            $query_tracking = "SELECT tracking,ponumber,custnumber,cpitem 
                                                                            FROM tbldetalle_orden 
                                                                            WHERE tracking IN (" . $sql_tracking . ")";
                                            $result_tracking = mysqli_query($link, $query_tracking) or die("Error validando trackings existentes en el sistema");
                                            while ($row_tracking = mysqli_fetch_array($result_tracking)) {
                                                if ($row_tracking['tracking'] != "") {
                                                    echo $message_alert . 'El Tracking: <b><font color="red">' . $row_tracking["tracking"] . '</font></b> ya se encuentra en sistema con el Ponumber <b><font color="red">' . $row_tracking["ponumber"] . '</b></font> , custnumber <b><font color="red">' . $row_tracking["custnumber"] . '</b></font> e item <b><font color="red">' . $row_tracking["cpitem"] . '</b></font>' . $message_end;

                                                    //SI EL TRACKING SE ENCUENTRA EN EL SISTEMA REMOVEMOS LA FILA PARA NO HACER UPDATE
                                                    $remover = array_search("'" . $row_tracking["tracking"] . "'", $array_tracking);
                                                    unset($array_tracking[$remover]);
                                                    unset($array_ponumber[$remover]);
                                                    unset($array_custnumber[$remover]);
                                                    unset($array_item[$remover]);
                                                }
                                            }


                                            //VALIDAMOS LAS ORDENES QUE TIENEN REENVIO = FORWARDED
                                            $query_reshipped = "SELECT id_orden_detalle,ponumber,status,cpitem,custnumber
                                                                    FROM  tbldetalle_orden 
                                                                    WHERE Ponumber IN (" . $sql_ponumber . ") 
                                                                    AND reenvio = 'Forwarded' 
                                                                    AND estado_orden != 'Canceled' 
                                                                    AND tracking = '' ORDER BY cpitem";

                                            //        //DEBUG TEST
                                            //        echo "QUERY DE REENVIOS: ". $query_reshipped . "<br />";

                                            $result_reshipped = mysqli_query($link, $query_reshipped) or die("Error validando ordenes reenviadas");
                                            while ($row_reshipped = mysqli_fetch_array($result_reshipped)) {

                                                //SI TIENE ESTADO READY TO SHIP PROCESA LA FILA
                                                if ($row_reshipped['status'] !== "shipped") {
                                                    $tracking_key = array_search("'" . $row_reshipped['ponumber'] . "'", $array_ponumber);
                                                    $compare_custnumber = "'" . $row_reshipped['custnumber'] . "'";
                                                    $compare_cpitem = "'" . $row_reshipped['cpitem'] . "'";
                                                    if ($compare_custnumber == $array_custnumber[$tracking_key] && $compare_cpitem == $array_item[$tracking_key]) {
                                                        if (!empty($tracking_key)) {
                                                            if (!empty($array_tracking[$tracking_key])) {
                                                                $sqlup_case .= "WHEN " . $row_reshipped['id_orden_detalle'] . " THEN  " . $array_tracking[$tracking_key] . " ";
                                                                $sql_ordendetalle .= "'" . $row_reshipped['id_orden_detalle'] . "',";

                                                                //ANUNCIAMOS LOS TRACKINGS QUE SE HAN CARGADO
                                                                echo $message_success . "Se ha insertado El tracking: <b><font color='red'>" . $array_tracking[$tracking_key] . "</font></b> Para el id: <b><font color='red'>" . $row_reshipped['id_orden_detalle'] . "</font></b> Con Ponumber asignado: <b><font color='red'>" . $array_ponumber[$tracking_key] . "</font></b> Con item: <b><font color='red'>" . $array_item[$tracking_key] . "</font></b> Con estado : <b><font color='red'> REENVIADO </font> </b>" . $message_end;
                                                                $countup++;

                                                                //REMOVEMOS EL PO PARA NO INSERTAR TRACKING REPETIDO
//                                                                 echo "REMOVEMOS LA POSICION: ". $tracking_key ." DE LA COLUMNA PONUMBER <br />"; //DEBUG TEST
                                                                $array_ponumber[$tracking_key] = "PONUMBER USADO";
                                                            } else {
                                                                echo $message_alert . "No se pudo alimentar el id: <b><font color='red'>" . $row_reshipped['id_orden_detalle'] . "</font></b> Con Ponumber asignado: <b><font color='red'>" . $array_ponumber[$tracking_key] . "</font></b> Porque su: <b><font color='red'>TRACKING</font></b> está repetido dentro del archivo" . $message_end;
                                                            }
                                                        }
                                                    }
                                                } else { //SI TIENE ESTADO SHIPPED O NEW NO PROCESA LA FILA
                                                    //DOY REPORTE DE QUE NO SE ACTUALIZO POR SER "SHIPPED" O "NEW"
                                                    $tracking_key = array_search("'" . $row_reshipped['ponumber'] . "'", $array_ponumber);
                                                    if (!empty($tracking_key)) {
                                                        echo $message_alert . "El tracking: <b><font color='red'>" . $array_tracking[$tracking_key] . "</font></b> Con el id: <b><font color='red'>" . $row_reshipped['id_orden_detalle'] . "</font></b> Con Ponumber asignado: <b><font color='red'>" . $array_ponumber[$tracking_key] . "</font></b> Con reenvio : <b><font color='red'>REENVIADO </font></b> -- <b><font color='red'> NO  </font></b> se actualizó por tener estado: <b><font color='red'> " . $row_reshipped['status'] . " </font></b>" . $message_end;
                                                    }
                                                }
                                            }

                                            //REMOVEMOS LA ULTIMA COMA PARA EVITAR ERROR DE SINTAXIS
                                            $sql_ordendetalle = substr(trim($sql_ordendetalle), 0, -1);

                                            //SI ENCUENTRA ORDENES CON FORWARDED HACE EL UPDATE
                                            if (!is_null($sqlup_case)) {
                                                $update1 = "UPDATE tbldetalle_orden 
                                                                SET status = 'shipped', tracking = 
                                                                    CASE id_orden_detalle
                                                                        " . $sqlup_case . "
                                                                    END
                                                                WHERE id_orden_detalle IN (" . $sql_ordendetalle . ");";
                                                mysqli_query($link, $update1) or die("Error cargando los trackings de ordenes REENVIADAS");
                                            } else {

                                                //PRIMER VALIDADOR DE QUE NO HUBO ORDENES SUBIDAS
                                                $noorders = 1;
                                            }

                                            //VALIDAMOS LAS ORDENES QUE ***NO*** TIENEN REENVIO
                                            $query_noreshipped = "SELECT id_orden_detalle,tracking,Ponumber,status,cpitem,custnumber 
                                                                    FROM  tbldetalle_orden 
                                                                    WHERE Ponumber IN (" . $sql_ponumber . ")  
                                                                    AND reenvio = 'No'
                                                                    AND estado_orden != 'Canceled' 
                                                                    AND tracking = '' ORDER BY cpitem";
                                            $filasprocesadas = array();
                                            $sql_ordendetalle = ''; ////////////////////////////////////////////VACIAMOS LA VARIABLE PARA EL QUERY
                                            $result_noreshipped = mysqli_query($link, $query_noreshipped) or die("Error validando ordenes LISTAS PARA ENVIAR");
                                            while ($row_noreshipped = mysqli_fetch_array($result_noreshipped)) {

                                                if ($row_noreshipped['status'] !== "shipped") {
                                                    $tracking_key = array_search("'" . $row_noreshipped['Ponumber'] . "'", $array_ponumber);

                                                    $compare_custnumber = "'" . $row_noreshipped['custnumber'] . "'";
                                                    $compare_cpitem = "'" . $row_noreshipped['cpitem'] . "'";
                                                    if ($compare_custnumber == $array_custnumber[$tracking_key] && $compare_cpitem == $array_item[$tracking_key] && $tracking_key != 0) {
                                                        if (!empty($tracking_key)) {
                                                            if (!empty($array_tracking[$tracking_key])) {
                                                                $sqlup2_case .= "WHEN " . $row_noreshipped['id_orden_detalle'] . " THEN  " . $array_tracking[$tracking_key] . " ";
                                                                $sql_ordendetalle .= "'" . $row_noreshipped['id_orden_detalle'] . "',";

                                                                //ANUNCIAMOS LOS TRACKINGS QUE SE HAN CARGADO
                                                                echo $message_success . "Se ha insertado El tracking: <b><font color='red'>" . $array_tracking[$tracking_key] . "</font></b> Para el id: <b><font color='red'>" . $row_noreshipped['id_orden_detalle'] . "</font></b> Con Ponumber asignado: <b><font color='red'>" . $array_ponumber[$tracking_key] . "</font></b> Con item: <b><font color='red'>" . $array_item[$tracking_key] . "</font></b> Con estado: <b><font color='red'>LISTO PARA ENVIAR</font></b>" . $message_end;
                                                                $countup++;
                                                                $filasprocesadas[] = $tracking_key;
                                                                //REMOVEMOS EL PO PARA NO INSERTAR TRACKING REPETIDO
                                                                $array_ponumber[$tracking_key] = "PONUMBER USADO";
                                                            } else {
                                                                echo $message_alert . "No se pudo alimentar el id: <b><font color='red'>" . $row_noreshipped['id_orden_detalle'] . "</font></b> Con Ponumber asignado: <b><font color='red'>" . $array_ponumber[$tracking_key] . "</font></b> Porque su: <b><font color='red'>TRACKING</font></b> está repetido dentro del archivo" . $message_end;
                                                            }
                                                        }
                                                    } else {
                                                        if ($tracking_key != 0) {
//                                        echo $message_alert . "No se pudo cargar el Ponumber asignado: <b><font color='red'>" . $array_ponumber[$tracking_key] . "</font></b> Porque la orden se encuentra: <b><font color='red'>CANCELADA </font></b> en la base de datos" . $message_end;
                                                        }
                                                    }
                                                } else {

                                                    //DOY REPORTE DE QUE NO SE ACTUALIZO POR SER "SHIPPED" O "NEW"
                                                    $tracking_key = array_search("'" . $row_noreshipped['Ponumber'] . "'", $array_ponumber);
                                                    if (!empty($tracking_key)) {
                                                        echo $message_alert . "El tracking: <b><font color='red'>" . $array_tracking[$tracking_key] . "</font></b> Con el id: <b><font color='red'>" . $row_noreshipped['id_orden_detalle'] . "</font></b> Con Ponumber asignado: <b><font color='red'>" . $array_ponumber[$tracking_key] . "</font></b> -- <b><font color='red'> NO  </font></b> se actualizó por tener estado: <b><font color='red'> " . $row_noreshipped['status'] . " </font></b>" . $message_end;
                                                    }
                                                }
                                            }

                                            //REMOVEMOS LA ULTIMA COMA PARA EVITAR ERROR DE SINTAXIS
                                            $sql_ordendetalle = substr(trim($sql_ordendetalle), 0, -1);

                                            //SI ENCUENTRA ORDENES READY TO SHIP HACE EL UPDATE
                                            if (!is_null($sqlup2_case)) {
                                                $update2 = "UPDATE tbldetalle_orden 
                                                                SET status = 'shipped', tracking = 
                                                                    CASE id_orden_detalle
                                                                        " . $sqlup2_case . "
                                                                    END
                                                                WHERE id_orden_detalle IN (" . $sql_ordendetalle . ");";
                                                mysqli_query($link, $update2) or die("Error cargando los trackings de ordenes Listas Para Enviar");
                                            } else {

                                                //PRIMERO VALIDAMOS SI SE ALIMENTO EL PRIMER VALIDADOR
                                                if ($noorders == 1) {
                                                    //SEGUNDO VALIDADOR DE QUE NO HUBO ORDENES SUBIDAS
                                                    $noorders = 2;
                                                }
                                            }
                                            if (!empty($filasprocesadas)) {
                                                $array_ponumber[0] = ' ';
                                                $ponoinsertados = array_diff_key($array_ponumber, array_flip($filasprocesadas));
                                                $inicio = array_shift($ponoinsertados);
                                                if (!empty($ponoinsertados)) {
                                                    echo $message_alert . 'Los siguientes PO no fueron procesados <b><font color="red"> ' . join(' - ', $ponoinsertados) . ' </font></b> Revise el archivo y compare contra sistema' . $message_end;
                                                }
                                            }

                                            //SI NO HUBO ORDENES PARA SUBIR, AVISAMOS EN PANTALLA
                                            if ($noorders == 2) {
                                                echo $message_alert . '<b><font color="red"> NO </font></b> se subieron Trackings a sistema' . $message_end;
                                            } else {
                                                echo $message_info . "Total de trackings subidos " . $countup . $message_end;
                                                echo $message_info . ' Todas las ordenes fueron subidas con éxito' . $message_end;
                                            }

                                            //ELIMINAMOS EL ARCHIVO DEL SERVIDOR
                                            $handle = opendir($dir);
                                            while ($file = readdir($handle)) {
                                                if (is_file($dir . $file)) {
                                                    unlink($dir . $file);
                                                }
                                            }
                                        } else {
                                            echo $message_alert . " No se ha podido mover el archivo: " . $_FILES["archivo"]["name"][$i] . $message_end;
                                        }
                                    } else {
                                        echo $message_alert . " No se encuentra la ruta de carga  " . $message_end;
                                    }
                                } else {
                                    echo $message_alert . $_FILES["archivo"]["name"][$i] . " - Formato no admitido" . $message_end;
                                }
                            }
                        } else {
                            echo $message_alert . " No se subio ningun archivo" . $message_end;
                        }
                        ?>
                    </div>   
                </div>  
            </div>
        </div>
    </div>
</div>