<?php
if (isset($_POST['submit_coldroom_changes'])) {
    $update_codigo_unico = "UPDATE tblcoldroom SET fecha_vuelo = '" . $_POST['codigodebug_fechavuelo'] . "', rechazada = '" . $_POST['codigodebug_recha'] . "', guia_master = '" . $_POST['codigodebug_gmaster'] . "', palet = '" . $_POST['codigodebug_palet'] . "', guia_madre = '" . $_POST['codigodebug_gmadre'] . "', guia_hija = '" . $_POST['codigodebug_ghija'] . "'";
    $result_codigo_unico = mysqli_query($link, $update_codigo_unico);
}
?>
<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="main.php?panel=mainpanel.php">BurtonTech</a></li>
    <li>Cuarto Frio</li>
    <li class="active">Ver C&oacute;digos</li>
</ul>
<!-- BREADCRUMB -->
<div class="page-title">                    
    <h2><span class="fa fa-search"></span> Ver C&oacute;digo</h2>
</div>
<?php
if (isset($_POST['submit_coldroom_changes'])) {
    if ($result_codigo_unico) {
        echo '
                <div class="row">
                    <div class="col-md-3">
                        <div class="widget widget-success widget-item-icon">
                            <div class="widget-item-left">
                                <span class="fa fa-check"></span>
                            </div>
                            <div class="widget-data">
                                <div class="widget-title">Notificación</div>
                                <div class="widget-subtitle">
                                    <div role="alert">
                                        El c&oacute;digo ha sido actualizado con &eacute;xito
                                    </div>
                                </div>
                            </div>
                            <div class="widget-controls">                                
                                <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                            </div>                             
                        </div>
                    </div>
                </div>
        ';
    } else {
        echo '
                <div class="row">
                    <div class="col-md-3">
                        <div class="widget widget-danger widget-item-icon">
                            <div class="widget-item-left">
                                <span class="fa fa-exclamation"></span>
                            </div>
                            <div class="widget-data">
                                <div class="widget-title">Notificación</div>
                                <div class="widget-subtitle">
                                    <div role="alert">
                                        No se realizaron los cambios, intente de nuevo.
                                    </div>
                                </div>
                            </div>
                            <div class="widget-controls">                                
                                <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                            </div>                             
                        </div>
                    </div>
                </div>
        ';
    }
}
?>
<div class="col-md-3">
    <div class="panel panel-default" id="ttracking">
        <div class="panel-heading" >
            <h3 class="panel-title"><strong>BUSCAR CÓDIGOS EN EL SISTEMA</strong></h3>
        </div>
        <form method="post" enctype="multipart/form-data">
            <div class="panel-body">
                <div class="col-md-12">
                    <label><strong>C&oacute;digo:</strong></label>
                    <input name="codigodebug_buscarcodigo" type="text" id="codigodebug_buscarcodigo" class="form-control" style="width:200px; margin-bottom: 16px;"/>
                    <input type="submit" name="codigodebug_buscarcodigo_submit" id="codigodebug_buscarcodigo_submit" value="Buscar" class="btn btn-primary"/>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="col-md-3">
    <div class="panel panel-default" id="ttracking">
        <div class="panel-heading" >
            <h3 class="panel-title"><strong>BUSCAR PONUMBER EN EL SISTEMA</strong></h3>
        </div>
        <form method="post" enctype="multipart/form-data">
            <div class="panel-body">
                <div class="col-md-12">
                    <label><strong>Ponumber:</strong></label>
                    <input name="codigodebug_buscarpo" type="text" id="codigodebug_buscarpo" class="form-control" style="width:200px; margin-bottom: 16px;"/>
                    <input type="submit" name="codigodebug_buscarpo_submit" id="codigodebug_buscarpo_submit" value="Buscar" class="btn btn-primary"/>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="col-md-3">
    <div class="panel panel-default" id="ttracking">
        <div class="panel-heading" >
            <h3 class="panel-title"><strong>BUSCAR TRACKINGS EN EL SISTEMA</strong></h3>
        </div>
        <form action="scripts/codigo_depurar.php" method="post" enctype="multipart/form-data">
            <div class="panel-body">
                <div class="col-md-12">
                    <label><strong>Tracking:</strong></label>
                    <input name="codigodebug_buscartrac" type="text" id="codigodebug_buscartrac" class="form-control" style="width:200px; margin-bottom: 16px;"/>
                    <input type="submit" name="codigodebug_buscartrac_submit" id="codigodebug_buscartrac_submit" value="Buscar" class="btn btn-primary"/>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
if (isset($_POST['codigodebug_buscarcodigo_submit'])) {
    $select_code_validate = "SELECT codigo FROM tbletiquetasxfinca WHERE codigo =\"" . $_POST['codigodebug_buscarcodigo'] . "\" ";
    $result_code_validate = mysqli_query($link, $select_code_validate);
    if (mysqli_num_rows($result_code_validate) <= 0) {
        echo '
                <div class="row">
                    <div class="col-md-3">
                        <div class="widget widget-danger widget-item-icon">
                            <div class="widget-item-left">
                                <span class="fa fa-exclamation"></span>
                            </div>
                            <div class="widget-data">
                                <div class="widget-title">Notificación</div>
                                <div class="widget-subtitle">
                                    <div role="alert">
                                        El c&oacute;digo no se encuentra registrado en la base de datos
                                    </div>
                                </div>
                            </div>
                            <div class="widget-controls">                                
                                <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                            </div>                             
                        </div>
                    </div>
                </div>
        ';
        goto endscript;
    } else {
        $select_get_id = "SELECT id_detalleorden FROM tbldetalle_orden WHERE codigo =\"" . $_POST['codigodebug_buscarcodigo'] . "\" ";
        $result_get_id = mysqli_query($link, $select_get_id);
        $row_get_id = mysqli_fetch_array($result_get_id);
        $get_id = $row_get_id[0];
        
        $select_get_mesasge = "SELECT cpmensaje FROM tblorden WHERE id_orden =\"" . $get_id . "\" ";
        $result_get_mesasge = mysqli_query($link, $select_get_mesasge);
        $row_get_mesasge = mysqli_fetch_array($result_get_mesasge);
        $get_mesasge = $row_get_mesasge[0];
        
        $select_printdata = "SELECT * FROM tbletiquetasxfinca WHERE codigo =\"" . $_POST['codigodebug_buscarcodigo'] . "\" ";
        $result_printdata = mysqli_query($link, $select_printdata);
        $row_printdata = mysqli_fetch_array($result_printdata);
        echo '
                <div class="row">
                    <div class="col-md-3">
                        <div class="widget widget-primary widget-item-icon">
                            <div class="widget-item-left">
                                <span class="fa fa-commenting"></span>
                            </div>
                            <div class="widget-data">
                                <div class="widget-title">Mensaje</div>
                                <div class="widget-subtitle">
                                    <div role="alert"><br />';
        echo $get_mesasge;
        echo '
                                    </div>
                                </div>
                            </div>
                            <div class="widget-controls">                                
                                <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                            </div>                             
                        </div>
                    </div>
                    <a class="btn btn-info" href="#" onclick="coldroom_print_tag(\''.$row_printdata['finca'].'\',\''.$row_printdata['fecha'].'\',\''.$row_printdata['item'].'\',\'true\',\''.$row_printdata['fecha_tentativa'].'\')"><i class="fa fa-print" aria-hidden="true"></i></a>
                </div>
        ';
        goto searchbycode;
    }
} elseif (isset($_POST['codigodebug_buscarpo_submit'])) {
    $select_po_validate = "SELECT ponumber FROM tbldetalle_orden WHERE ponumber =\"" . $_POST['codigodebug_buscarpo'] . "\" ";
    $result_po_validate = mysqli_query($link, $select_po_validate);
    if (mysqli_num_rows($result_po_validate) <= 0) {
        echo '
                <div class="row">
                    <div class="col-md-3">
                        <div class="widget widget-danger widget-item-icon">
                            <div class="widget-item-left">
                                <span class="fa fa-exclamation"></span>
                            </div>
                            <div class="widget-data">
                                <div class="widget-title">Notificación</div>
                                <div class="widget-subtitle">
                                    <div role="alert">
                                        El Ponumber no se encuentra registrado en la base de datos
                                    </div>
                                </div>
                            </div>
                            <div class="widget-controls">                                
                                <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                            </div>                             
                        </div>
                    </div>
                </div>
        ';
        goto endscript;
    } else {
        goto searchbypo;
    }
} elseif (isset($_POST['codigodebug_buscartrac_submit'])) {
    
} else {
    goto endscript;
}
?>
<?php
searchbypo:
?>

<?php
searchbycode:
?>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Tabla de Detalle de &oacute;rdenes </h3>
            <div class="col-md-2 pull-right" >
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table id="verorden_reporte" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>&Oacute;rden de compra</th>
                            <th>N&uacute;mero de cliente</th>
                            <th>Producto</th>
                            <th>Finca</th>
                            <th>Tracking</th>
                            <th>Estado</th>
                            <th>Cuarto Fr&iacute;o</th>
                            <th>Proceso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $select_detord = "SELECT * FROM tbldetalle_orden WHERE codigo =" . $_POST['codigodebug_buscarcodigo'];
                        $result_detord = mysqli_query($link, $select_detord);
                        while ($row_detord = mysqli_fetch_array($result_detord)) {
                            echo "
                            <tr>
                                <td>" . $row_detord['codigo'] . "</td>
                                <td>" . $row_detord['Ponumber'] . "</td>
                                <td>" . $row_detord['Custnumber'] . "</td>    
                                <td>" . $row_detord['cpitem'] . "</td>    
                                <td>" . $row_detord['farm'] . "</td>    
                                <td>" . $row_detord['tracking'] . "</td>    
                                <td>" . $row_detord['estado_orden'] . "</td>    
                                <td>" . $row_detord['coldroom'] . "</td>    
                                <td>" . $row_detord['status'] . "</td>    
                            </tr>
                                ";
                        }
                        ?>
                    </tbody>
                </table>                                    
            </div>
        </div>
        <div class="panel-footer">
        </div>
    </div>
</div>
<div class="col-md-12">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Tabla de Detalle de Etiquetas por finca </h3>
            <div class="col-md-2 pull-right" >
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table id="verorden_reporte" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Producto</th>
                            <th>Finca</th>
                            <th>Solicitado</th>
                            <th>Entregado</th>
                            <th>N&uacute;mero de pedido</th>
                            <th>Archivada</th>
                            <th>Destino</th>
                            <th>Agencia</th>
                            <th>Tentativa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $select_etixfi = "SELECT * FROM tbletiquetasxfinca WHERE codigo =" . $_POST['codigodebug_buscarcodigo'];
                        $result_etixfi = mysqli_query($link, $select_etixfi);
                        while ($row_etixfi = mysqli_fetch_array($result_etixfi)) {
                            echo "
                            <tr>
                                <td>" . $row_etixfi['codigo'] . "</td>
                                <td>" . $row_etixfi['item'] . "</td>
                                <td>" . $row_etixfi['finca'] . "</td>    
                                <td>" . $row_etixfi['solicitado'] . "</td>    
                                <td>" . $row_etixfi['entregado'] . "</td>    
                                <td>" . $row_etixfi['nropedido'] . "</td>    
                                <td>" . $row_etixfi['archivada'] . "</td>    
                                <td>" . $row_etixfi['destino'] . "</td>    
                                <td>" . $row_etixfi['agencia'] . "</td>    
                                <td>" . $row_etixfi['fecha_tentativa'] . "</td>    
                            </tr>
                                ";
                        }
                        ?>
                    </tbody>
                </table>                                    
            </div>
        </div>
        <div class="panel-footer">
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Tabla de Detalle de Cuarto Fr&iacute;o </h3>
            <button id="codigodebug_editar_btn" style="margin-left: 16px; margin-right: 16px;" class="btn btn-primary pull-right"><span class="fa fa-edit"></span> Editar </button>
            <form method="post">
                <button type="submit" name="submit_coldroom_changes" id="codigodebug_guardar_btn" style="margin-left: 16px; margin-right: 16px; display: none;" class="btn btn-success pull-right"><span class="fa fa-save"></span> Guardar</button>  
                <div class="col-md-2 pull-right" >
                </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table id="verorden_reporte" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Producto</th>
                            <th>Finca</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Fecha</th>
                            <th>Fecha Tracking</th>
                            <th>Fecha de Entrega</th>
                            <th>Fecha de Vuelo</th>
                            <th>Tracking</th>
                            <th>Rechazada</th>
                            <th>Master</th>
                            <th>Palet</th>
                            <th>Madre</th>
                            <th>Hija</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $select_coldro = "SELECT * FROM tblcoldroom WHERE codigo =" . $_POST['codigodebug_buscarcodigo'];
                        $result_coldro = mysqli_query($link, $select_coldro);
                        while ($row_coldro = mysqli_fetch_array($result_coldro)) {
                            echo "
                            <tr>
                                <td>" . $row_coldro['codigo'] . "</td>
                                <td>" . $row_coldro['item'] . "</td>
                                <td>" . $row_coldro['finca'] . "</td>    
                                <td>" . $row_coldro['entrada'] . "</td>    
                                <td>" . $row_coldro['salida'] . "</td>    
                                <td>" . $row_coldro['fecha'] . "</td>    
                                <td>" . $row_coldro['fecha_tracking'] . "</td>    
                                <td>" . $row_coldro['fecha_entrega'] . "</td>  
                                <td><input id=\"codigodebug_fechavuelo_input\" name=\"codigodebug_fechavuelo\" disabled=\"disabled\" style=\"width: 100px;color: red;\" class=\"form-control\" value=\"" . $row_coldro['fecha_vuelo'] . "\" /></td> 
                                <td>" . $row_coldro['tracking_asig'] . "</td>    
                                <td><input id=\"codigodebug_recha_input\" name=\"codigodebug_recha\" disabled=\"disabled\" style=\"width: 100px;color: red;\" class=\"form-control\" value=\"" . $row_coldro['rechazada'] . "\" /></td>    
                                <td><input id=\"codigodebug_gmaster_input\" name=\"codigodebug_gmaster\" disabled=\"disabled\" style=\"width: 100px;color: red;\" class=\"form-control\" value=\"" . $row_coldro['guia_master'] . "\" /></td>    
                                <td><input id=\"codigodebug_palet_input\" name=\"codigodebug_palet\" disabled=\"disabled\" style=\"width: 100px;color: red;\" class=\"form-control\" value=\"" . $row_coldro['palet'] . "\" /></td>    
                                <td><input id=\"codigodebug_gmadre_input\" name=\"codigodebug_gmadre\" disabled=\"disabled\" style=\"width: 100px;color: red;\" class=\"form-control\" value=\"" . $row_coldro['guia_madre'] . "\" /></td>    
                                <td><input id=\"codigodebug_ghija_input\" name=\"codigodebug_ghija\" disabled=\"disabled\" style=\"width: 100px;color: red;\" class=\"form-control\" value=\"" . $row_coldro['guia_hija'] . "\" /></td>    
                            </tr>
                                ";
                        }
                        ?>
                    </tbody>
                </table>
                </form>
            </div>
        </div>
        <div class="panel-footer">
        </div>
    </div>
</div>
<?php
endscript:
?>