<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="main.php?panel=mainpanel.php">BurtonTech</a></li>
    <li>Herramientas</li>
    <li class="active">Eliminar Trackings </li>
</ul>
<!-- FIN BREADCRUMB -->

<div class="page-title">                    
    <h2><span class="fa fa-ban"></span> Trackings eliminados</h2>
</div>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <h3>Visor de eventos</h3>       
            <div class="col-md-12">  
                <div class="content-frame-body content-frame-body-left">
                    <div class="messages messages-img">
                        <!-- ///////////////////////////////////////////////////////////////////////////////////////EJEMPLO DE COMO SE DEBE MOSTRAR EL MENSAJE EN PANTALLA--> 
<!--                        <div class="item">
                            <div class="image">
                                <button class="btn btn-danger btn-condensed"><i class="fa fa-exclamation" aria-hidden="true"></i></button>
                            </div>                                
                            <div class="text">
                                <div class="heading">
                                    <a href="#">Tracking existente en la base de datos</a>
                                    <span class="date">09:11</span>
                                </div>                                    
                                El tracking 1ZJAWID345324AIEFAMWE ya se encuentra en la base de datos
                            </div>
                        </div>-->
                        <?php
                        $orden = 0;
                        $fila = 1;
                        $array = array();
                        $dir = "uploads/trackings/";
                        $message_alert = "<div class=\"item item-visible\"><div class=\"image\"><button class=\"btn btn-danger btn-condensed\"><i class=\"fa fa-exclamation\" aria-hidden=\"true\"></i></button></div><div class=\"text\"><div class=\"heading\"><a > ¡Alerta!</a><span class=\"date\">" . date('H:i') . "</span></div>";
                        $message_info = "<div class=\"item item-visible\"><div class=\"image\"><button class=\"btn btn-info btn-condensed\"><i class=\"fa fa-commenting-o\" aria-hidden=\"true\"></i></button></div><div class=\"text\"><div class=\"heading\"><a > Mensaje</a><span class=\"date\">" . date('H:i') . "</span></div>";
                        $message_success = "<div class=\"item item-visible\"><div class=\"image\"><button class=\"btn btn-success btn-condensed\"><i class=\"fa fa-check-square-o\" aria-hidden=\"true\"></i></button></div><div class=\"text\"><div class=\"heading\"><a > Evento exitoso</a><span class=\"date\">" . date('H:i') . "</span></div>";
                        $message_end = "</div></div>";
                        //CONTAMOS CUANTOS ARCHIVOS HAY EN LA CARPETA
                        $total_excel = count(glob("$dir/{*.csv}", GLOB_BRACE));  //("$dir/{*.xlsx,*.xls,*.csv}",GLOB_BRACE));
                        if ($total_excel == 0) {
                            echo "<font color='red'>No hay archivo para leer...</font><br>";
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
                        $sql_tracking = '';
                        $sql_ponumber = '';
                        $sql_custnumber = '';
                        $sql_item = '';
                        $eliminar_trackings = '';
                        //RECORREMOS CADA FILA PARA OBTENER LOS ARRAY PARA LOS QUERY
                        for ($j = 2; $j <= $fila; $j++) {

                            //DECLARAMOS LOS CAMPOS QUE USAREMOS PARA ARMAR LOS QUERY
                            $Tracking = $array [$j]['5'];
                            $Ponumber = trim($array [$j]['2']);
                            $CustNumber = $array [$j]['3'];
                            $item = $array [$j]['4'];

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

                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////ORDENAMOS LOS RESULTADOS POR ITEM
                        array_multisort($array_item, SORT_ASC, $array_tracking, $array_ponumber, $array_custnumber);

                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////AUDITORIA EN PANTALLA
//                        var_dump($array_tracking);
//                        echo '<br /><br /><br />';
//                        var_dump($array_ponumber);
//                        echo '<br /><br /><br />';
//                        var_dump($array_custnumber);
//                        echo '<br /><br /><br />';
//                        var_dump($array_item);
//                        echo '<br /><br /><br />';
//                        die;
//                        
                        //////////////////////////////////////////////////////////////////////////////////////////////////////////////AGREGAMOS UN ELEMENTO AL INICIO PARA EVITAR DESCARTAR VALOR 0
                        array_unshift($array_tracking, "inicio");
                        array_unshift($array_ponumber, "inicio");
                        array_unshift($array_custnumber, "inicio");
                        array_unshift($array_item, "inicio");

                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////REMOVEMOS LOS TRACKING DUPLICADOS DENTRO DEL MISMO ARCHIVO
                        $array_tracking = array_unique($array_tracking);

                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VALIDAMOS QUE LOS TRACKINGS NO SE ENCUENTREN YA EN SISTEMA
                        $query_tracking = "SELECT tracking,ponumber,custnumber,cpitem 
                                                                            FROM tbldetalle_orden 
                                                                            WHERE tracking IN (" . $sql_tracking . ")";
                        $result_tracking = mysqli_query($link, $query_tracking) or die("Error validando trackings existentes en el sistema");
                        while ($row_tracking = mysqli_fetch_array($result_tracking)) {
                            if ($row_tracking['tracking'] != "") {
                                echo $message_alert . 'El Tracking: <b><font color="red">' . $row_tracking["tracking"] . '</font></b> Se ha eliminado del sistema ' . $message_end;
                                $eliminar_trackings .= "'" . $row_tracking['tracking'] . "',";
                                $eliminar_po .= "'" . $row_tracking['ponumber'] . "',";
                            }
                        }

                        if (!empty($eliminar_trackings)) {
                            //REMOVEMOS LA ULTIMA COMA PARA EVITAR ERROR DE SINTAXIS
                            $eliminar_trackings = substr(trim($eliminar_trackings), 0, -1);

                            //////////////////////////////////////////////////////////////////////////////////////////////////EL UPDATE PARA ELIMINAR TRACKING EN DETALLE ORDEN
                            $query_eliminar = "UPDATE tbldetalle_orden SET 
                                                tracking='', 
                                                status='Ready to ship', 
                                                descargada='not downloaded', 
                                                user='', 
                                                farm='', 
                                                coldroom='No', 
                                                codigo='0000000000' 
                                                WHERE tracking IN (" . $eliminar_trackings . ")";
                            $result_eliminar = mysqli_query($link, $query_eliminar);


                            //////////////////////////////////////////////////////////////////////////////////////////////////EL UPDATE PARA ELIMINAR TRACKING EN COLROOM
                            $query_eliminar_cold = "UPDATE tblcoldroom SET 
                                                    tracking_asig='', 
                                                    guia_hija='0', 
                                                    guia_madre='0', 
                                                    guia_master='0', 
                                                    palet='0', 
                                                    salida='No' 
                                                    WHERE tracking IN (" . $eliminar_trackings . ")";
                            $result_eliminar_cold = mysqli_query($link, $query_eliminar_cold);

                            //////////////////////////////////////////////////////////////////////////////////////////////////INSERTAMOS EN HISTORICO
                            $usuarioLog = $_SESSION["login"];
                            $ip = getRealIP();
                            $fecha = date('Y-m-d H:i:s');
                            $operacion = "Eliminados los tracking: PO: " . $eliminar_po . " Trk: " . $eliminar_trackings;
                            $razon = "A traves de Herramientas / Eliminar Tracking por archivo";
                            $SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`,`razon`) 
                                               VALUES ('$usuarioLog','$operacion','$fecha','$ip','$razon')";
                            $consultaHist = mysqli_query($link, $SqlHistorico) or die("Error actualizando la bitácora de usuarios");
                        } else {
                            echo $message_alert . '<b><font color="red">NO</font></b> Se encontraron Trackings para eliminar ' . $message_end;
                        }

                        //ELIMINAMOS EL ARCHIVO DEL SERVIDOR
                        $handle = opendir($dir);
                        while ($file = readdir($handle)) {
                            if (is_file($dir . $file)) {
                                unlink($dir . $file);
                            }
                        }
                        ?>
                    </div>   
                </div>  
            </div>
        </div>
    </div>
</div>