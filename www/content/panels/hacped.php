<?php
//LLAMAMOS LAS FINCAS A MOSTRAR
$select_finca = "SELECT DISTINCT finca FROM tbletiquetasxfinca where archivada = 'No' AND estado!='5' order by finca ASC";
$result_finca = mysqli_query($link, $select_finca);

//GUARDAMOS TODAS LAS FINCAS A LISTA EN UNA VARIABLE PARA LOS QUERY
$fincas = "";
$i = 0;
while ($row_finca = mysqli_fetch_array($result_finca, MYSQLI_BOTH)) {
    $fincas .= "'" . $row_finca[0] . "',";
    $i++;
}

//REMOVEMOS LA ULTIMA COMA PARA NO GENERAR ERROR DE SINTAXIS
$fincas = substr(trim($fincas), 0, -1);

//LLAMAMOS A LAS ORDENADAS Y LAS ENTREGADAS
$select_ord_ent = "SELECT DISTINCT SUM(tbletiquetasxfinca.solicitado) ordenadas, SUM(tbletiquetasxfinca.entregado) entregadas, finca FROM tbletiquetasxfinca where archivada = 'No' AND estado!='5' GROUP BY finca;";
$result_ord_ent = mysqli_query($link, $select_ord_ent);
$ii = 0;
while ($row_ord_ent = mysqli_fetch_array($result_ord_ent, MYSQLI_BOTH)) {
    $ord_ent[$ii] = $row_ord_ent[2];
    $ii++;
}
array_unshift($ord_ent, "INICIO");

//LLAMAMOS LAS SIN TRAQUEAR
$select_sintra = "SELECT COUNT(tblcoldroom.finca), tbletiquetasxfinca.finca FROM tblcoldroom INNER JOIN tbletiquetasxfinca ON tblcoldroom.codigo = tbletiquetasxfinca.codigo where entrada= 'Si' AND salida ='No' AND tbletiquetasxfinca.archivada='No' AND tbletiquetasxfinca.estado='1' GROUP BY tbletiquetasxfinca.finca";
$result_sintra = mysqli_query($link, $select_sintra);
$ii = 0;
while ($row_sintra = mysqli_fetch_array($result_sintra, MYSQLI_BOTH)) {
    $sintra[$ii] = $row_sintra[1];
    $ii++;
}
array_unshift($sintra, "INICIO");

//LLAMAMOS LAS TRAQUEADAS
//LO HACEMOS EN DOS PASOS< PRIMERO LLAMAMOS LAS FINCAS DE ETIQXFINCA
$select_traque_cod = "SELECT DISTINCT codigo FROM tbletiquetasxfinca where archivada = 'No' AND estado='1' order by finca ASC";
$result_traque_cod = mysqli_query($link, $select_traque_cod);
//GUARDAMOS LAS FINCAS EN UNA VARIABLE PARA EL SIGUIENTE QUERY
$traque_cod = "";
while ($row_traque_cod = mysqli_fetch_array($result_traque_cod, MYSQLI_BOTH)) {
    $traque_cod .= "'" . $row_traque_cod[0] . "',";
}
//REMOVEMOS LA ULTIMA COMA PARA NO GENERAR ERROR DE SINTAXIS
$traque_cod = substr(trim($traque_cod), 0, -1);
//AHORA LLAMAMOS LAS TRAQUEADAS USANDO LAS FINCAS DEL QUERY DE ARRIBA
$select_traque = "SELECT COUNT(finca) trackeadas, finca FROM tblcoldroom where salida ='Si' AND tracking_asig !='' AND codigo IN (" . $traque_cod . ") GROUP BY finca";
$result_traque = mysqli_query($link, $select_traque);
$ii = 0;
while ($row_traque = mysqli_fetch_array($result_traque, MYSQLI_BOTH)) {
    $traque[$ii] = $row_traque[1];
    $ii++;
}
array_unshift($traque, "INICIO");

//LLAMAMOS A LAS RECHAZADAS
$select_recha = "SELECT COUNT(finca) rechazado,finca FROM tbletiquetasxfinca where estado='2' AND archivada = 'No' AND finca IN (" . $fincas . ") GROUP BY finca";
$result_recha = mysqli_query($link, $select_recha);
$ii = 0;
while ($row_recha = mysqli_fetch_array($result_recha, MYSQLI_BOTH)) {
    $recha[$ii] = $row_recha[1];
    $ii++;
}
array_unshift($recha, "INICIO");

//LLAMAMOS A LAS CIERRE DE DIA
$select_cierre = "SELECT COUNT(*) cierre, finca FROM tbletiquetasxfinca where estado='3' AND archivada = 'No' AND finca IN (" . $fincas . ") GROUP BY finca";
$result_cierre = mysqli_query($link, $select_cierre);
$ii = 0;
while ($row_cierre = mysqli_fetch_array($result_cierre, MYSQLI_BOTH)) {
    $cierre[$ii] = $row_cierre[1];
    $ii++;
}
array_unshift($cierre, "INICIO");

//LLAMAMOS A LAS REUTILIZADAS
$select_reut = "SELECT COUNT(finca) reutilizadas, finca FROM tbletiquetasxfinca where estado='4' AND archivada = 'No' AND finca IN (" . $fincas . ") GROUP BY finca";
$result_reut = mysqli_query($link, $select_reut);
$ii = 0;
while ($row_reut = mysqli_fetch_array($result_reut, MYSQLI_BOTH)) {
    $reut[$ii] = $row_reut[1];
    $ii++;
}
array_unshift($reut, "INICIO");

//ASIGNAMOS LOS VALORES AL ARRAY EN CASO DE NO EXISTIR VALOR ASIGNAMOS VALOR CERO
$printtoscreen = array("finca", "ordenadas", "entregadas", "sintra", "traque", "recha", "cierre", "reut");
$ii = 0;
$result_printtoscreen = mysqli_query($link, $select_finca);
while ($row_printtoscreen = mysqli_fetch_array($result_printtoscreen, MYSQLI_BOTH)) {
    $printtoscreen["finca"][$ii] = $row_printtoscreen[0];
    $dataseek_row = array_search($printtoscreen["finca"][$ii], $ord_ent);
    if ($dataseek_row != "") {
        mysqli_data_seek($result_ord_ent, $dataseek_row - 1);
        $row_ord_ent = mysqli_fetch_row($result_ord_ent);
        $printtoscreen["ordenadas"][$ii] = $row_ord_ent[0];
        $printtoscreen["entregadas"][$ii] = $row_ord_ent[1];
    } else {
        $printtoscreen["ordenadas"][$ii] = "0";
        $printtoscreen["entregadas"][$ii] = "0";
    }

    $dataseek_row = array_search($printtoscreen["finca"][$ii], $sintra);
    if ($dataseek_row != "") {
        mysqli_data_seek($result_sintra, $dataseek_row - 1);
        $row_sintra = mysqli_fetch_row($result_sintra);
        $printtoscreen["sintra"][$ii] = $row_sintra[0];
    } else {
        $printtoscreen["sintra"][$ii] = "0";
    }

    $dataseek_row = array_search($printtoscreen["finca"][$ii], $traque);
    if ($dataseek_row != "") {
        mysqli_data_seek($result_traque, $dataseek_row - 1);
        $row_traque = mysqli_fetch_row($result_traque);
        $printtoscreen["traque"][$ii] = $row_traque[0];
    } else {
        $printtoscreen["traque"][$ii] = "0";
    }

    $dataseek_row = array_search($printtoscreen["finca"][$ii], $recha);
    if ($dataseek_row != "") {
        mysqli_data_seek($result_recha, $dataseek_row - 1);
        $row_recha = mysqli_fetch_row($result_recha);
        $printtoscreen["recha"][$ii] = $row_recha[0];
    } else {
        $printtoscreen["recha"][$ii] = "0";
    }

    $dataseek_row = array_search($printtoscreen["finca"][$ii], $cierre);
    if ($dataseek_row != "") {
        mysqli_data_seek($result_cierre, $dataseek_row - 1);
        $row_cierre = mysqli_fetch_row($result_cierre);
        $printtoscreen["cierre"][$ii] = $row_cierre[0];
    } else {
        $printtoscreen["cierre"][$ii] = "0";
    }

    $dataseek_row = array_search($printtoscreen["finca"][$ii], $reut);
    if ($dataseek_row != "") {
        mysqli_data_seek($result_reut, $dataseek_row - 1);
        $row_reut = mysqli_fetch_row($result_reut);
        $printtoscreen["reut"][$ii] = $row_reut[0];
    } else {
        $printtoscreen["reut"][$ii] = "0";
    }

    $ii++;
}
//LLAMAMOS EL VALOR DE LA URL EN CASO DE EXISTIR MUESTRA VENTANA MODAL
$listarfinca = $_GET['listarfinca'];
?>
<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="main.php?panel=mainpanel.php">BurtonTech</a></li>
    <li>Pedidos</li>
    <li class="active">Hacer pedido</li>
</ul>
<!-- FIN BREADCRUMB -->

<div class="page-content-wrap">  
    <?php
    if (isset($_SESSION['msg'])) {
        echo '
                <div class="row">
                    <div class="col-md-4">
                        <div class="widget widget-';
        echo $_SESSION['box'];
        echo ' widget-item-icon">
                            <div class="widget-item-left">
                                <span class="fa fa-exclamation"></span>
                            </div>
                            <div class="widget-data">
                                <div class="widget-title">Notificación</div>
                                <div class="widget-subtitle">
                                    <div role="alert">
                                        ' . $_SESSION['msg'] . '
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
        unset($_SESSION['msg']);
    }
    ?>
    <div class="page-title">                    
        <h2><span class="fa fa-cart-plus"></span> Listado de pedidos</h2>
    </div>
    <div class="row" style="padding-top: 50px;padding-bottom: 16px;">
        <button style="margin-left: 16px; margin-right: 16px;" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modal_nuevo_pedido"><span class="glyphicon glyphicon-plus-sign black"> </span> Nuevo pedido </button>    
        <a href="main.php?panel=hacped_filtros.php" class="btn btn-primary pull-right"><i class="fa fa-filter" aria-hidden="true"></i> Filtros </a>
    </div>
    <?php
    $result_finca = mysqli_query($link, $select_finca);
    $ii = 0;
    while ($row_finca = mysqli_fetch_array($result_finca, MYSQLI_BOTH)) {
        $por_entregar = $printtoscreen['ordenadas'][$ii] - $printtoscreen['entregadas'][$ii] - $printtoscreen['recha'][$ii] - $printtoscreen['cierre'][$ii] - $printtoscreen['reut'][$ii];

        if ($por_entregar < "0") {
            $por_entregar = "0";
        }
        if ($por_entregar != "0") {
            $badge = "danger";
        } else {
            $badge = "success";
        }
        echo "
            <div class=\"col-md-3\">
                <div class=\"panel panel-default\">
                    <div class=\"panel-heading\">
                        <h3 style=\"text-align: center!important; float: none!important;font-size: 12px;\" class=\"panel-title\"><a href=\"main.php?panel=hacped.php&listarfinca=" . $printtoscreen["finca"][$ii] . "\"> " . $printtoscreen["finca"][$ii] . "</a></h3>
                    </div>
                    <div class=\"panel-body\">
                        <ul class=\"list-group border-bottom\">
                            <li class=\"list-group-item\">Ordenadas <span class=\"badge badge-primary font-x2\">" . $printtoscreen["ordenadas"][$ii] . "</span></li>
                            <li class=\"list-group-item\">Entregadas <span class=\"badge badge-primary font-x2\">" . $printtoscreen["entregadas"][$ii] . "</span></li>
                            <li class=\"list-group-item\">Sin traquear <span class=\"badge badge-primary font-x2\">" . $printtoscreen["sintra"][$ii] . "</span></li>
                            <li class=\"list-group-item\">Traqueadas <span class=\"badge badge-primary font-x2\">" . $printtoscreen["traque"][$ii] . "</span></li>
                            <li class=\"list-group-item\">Rechazadas <span class=\"badge badge-primary font-x2\">" . $printtoscreen["recha"][$ii] . "</span></li>
                            <li class=\"list-group-item\">Cierre de d&iacute;a <span class=\"badge badge-primary font-x2\">" . $printtoscreen["cierre"][$ii] . "</span></li>
                            <li class=\"list-group-item\">Reutilizadas <span class=\"badge badge-primary font-x2\">" . $printtoscreen["reut"][$ii] . "</span></li>
                            <li class=\"list-group-item\">Por entregar<span class=\"badge badge-" . $badge . " font-x2\">" . $por_entregar . "</span></li>
                        </ul>                                
                    </div>
                </div>
            </div>";
        $ii++;
    }
    ?>
</div>
<div class="modal fade" id="modal_listado_finca" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" style="width: 90vw;">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                <h4 class="modal-title" id="smallModalHead">LISTADO DE PEDIDOS DE LA FINCA <b><?php echo $listarfinca; ?></b></h4>
            </div>                    
            <div class="modal-body" style="overflow-x: scroll;">
                <div class="row">
                    <div class="btn-group pull-right">
                        <form action="php/pedidos_fincasExcel.php" method="post" target="_blank" id="FormularioExportacionFINCA">
                            <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> Exportar</button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="botonExcel2" style="cursor: pointer;">
                                        <img src='img/icons/xls.png' width="24"/> XLS
                                    </a>
                                    <input type="hidden" id="finca" name="finca" value="<?php echo $_GET['listarfinca'] ?>" />
                                </li>
                                <li class="divider"></li>
                                <li><a href="#" onClick ="$('#hacped_filtros_table').tableExport({type: 'png', escape: 'false'});"><img src='img/icons/png.png' width="24"/> PNG</a></li>
                                <li><a href="#" onClick ="$('#hacped_filtros_table').tableExport({type: 'pdf', escape: 'false'});"><img src='img/icons/pdf.png' width="24"/> PDF</a></li>
                            </ul>
                        </form>
                    </div>
                    <div class="col-md-2" style="float: right;">
                        <a href="main.php?panel=hacped_filtros.php" class="btn btn-primary pull-right"><i class="fa fa-filter" aria-hidden="true"></i> Filtros </a>
                    </div>
                    <div class="col-md-2" style="float: right;">
                        <button style="margin-left: 16px; margin-right: 16px;" class="btn btn-primary pull-right" data-dismiss="modal" data-toggle="modal" data-target="#modal_nuevo_pedido"><span class="glyphicon glyphicon-plus-sign black"> </span> Nuevo pedido </button>    
                    </div>
                    <div class="col-md-2" style="float: right;">
                        <button type="button" class="btn btn-primary" name="btn_deseleccionar" id="btn_deseleccionar" data-toggle="tooltip" aria-label="Deseleccionar todas las filas" title = "Deseleccionar todas las filas">
                            <i class="fa fa-times-circle-o" aria-hidden="true"></i> Deseleccionar
                        </button>
                    </div>
                    <div class="col-md-2" style="float: right;">
                        <button type="button" class="btn btn-primary" name="btn_seleccionar" id="btn_seleccionar" data-toggle="tooltip" aria-label="Seleccionar todas las filas" title = "Seleccionar todas las filas">
                            <i class="fa fa-check-square" aria-hidden="true"></i> Seleccionar
                        </button>
                    </div>

                </div>
                <form action="scripts/pedido_editar.php" id="hacped_edititem_form" method="post" enctype="multipart/form-data">
                    <div style="margin-top: 16px;" class="table-responsive">
                        <table id="listado" border="0" align="center" class="table table-striped">  
                            <thead>
                                <?php
                                //Agrupar el reporte por destino
                                $a = "SELECT distinct destino FROM tbletiquetasxfinca where archivada = 'No' AND estado!='5' AND finca='" . $_GET['listarfinca'] . "' order by destino DESC";
                                $b = mysqli_query($link, $a) or die('Error seleccionando el origen');

                                $TOTALSOL = 0;
                                $TOTALENTSTRACK = 0;
                                $TOTALENTTRACK = 0;
                                $TOTALENT = 0;
                                $TOTALRECH = 0;
                                $TOTALDIF = 0;
                                $TOTALCIERRE = 0;
                                $TOTALREUT = 0;
                                $TOTALPRECIO = 0;
                                $cont = 0;

                                while ($fila = mysqli_fetch_array($b, MYSQLI_BOTH)) {
                                    echo '
                                <th align="center"><strong>Salida de Finca</strong></th>
                                <th align="center"><strong>Producto</strong></th>
                                <th align="center"><strong>Desc. Prod. </strong></th>
                                <th align="center"><strong>Fecha Vuelo</strong></th>
                                <th align="center"><strong>Destino</strong></th>
                                <th align="center"><strong>Precio Compra</strong></th>
                                <th align="center" title="Ordenadas"><strong>Ord</strong></th>
                                <th align="center" title="Sin Traquear"><strong>STRAQ</strong></th>
                                <th align="center" title="Traqueadas"><strong>TRAQ</strong></th>
                                <th align="center" title="Total Cajas Recibidas"><strong>TCR</strong></th>
                                <th align="center" title="Rechazadas"><strong>REC</strong></th>
                                <th align="center" title="Cierre del dia"><strong>CDD</strong></th>
                                <th align="center" title="Reutilizadas"><strong>REUT</strong></th>
                                <th align="center" title="Por Entregar"><strong>PENT</strong></th>

                               </thead>
                           <tbody>';
                                    $cont++;

                                    //Leer las fechas de los pedidios PARA ORDENARLOS POR FECHAS EN PANTALLA
                                    $select_pedidos = "SELECT distinct nropedido,fecha,fecha_tentativa FROM tbletiquetasxfinca where archivada = 'No' AND estado!='5' AND finca='" . $_GET['listarfinca'] . "' AND destino = '" . $fila['destino'] . "' order by fecha";
                                    $result_pedidos = mysqli_query($link, $select_pedidos) or die("Error obteniendo los numeros de pedido.");
                                    $container_nropedido = array();
                                    $container_fechaentrega = array();
                                    $container_fechatentativa = array();
                                    $container_firstquery = array();
                                    //////////////////////////////////////////////////////////////GUARDAMOS LOS RESULTADOS EN LOS ARRAYS
                                    while ($row_pedidos = mysqli_fetch_array($result_pedidos, MYSQLI_BOTH)) {
                                        $container_nropedido[] = $row_pedidos[0];
                                        $container_fechaentrega[] = $row_pedidos[1];
                                        $container_fechatentativa[] = $row_pedidos[2];
                                        $container_firstquery[] = "('" . $row_pedidos[0] . "','" . $row_pedidos[1] . "','" . $row_pedidos[2] . "')";
                                    }

                                    ///////////////////////////////////////////////////////SEGUNDO QUERY OBTENEMOS LOS RENGLONES DE LA TABLA ****SOLICITADO, ENTREGADO, RECHAZADO, CIERRE DEL DIA, REUTILIZADO****
                                    $select_items = "SELECT distinct nropedido, item, precio, destino, finca, fecha, fecha_tentativa, SUM(solicitado) as solicitado, SUM(entregado) as entregado, SUM(case when estado = 2 then 1 else 0 end) as rechazado, SUM(case when estado = 3 then 1 else 0 end) as cierre, SUM(case when estado = 4 then 1 else 0 end) as reutilizado FROM tbletiquetasxfinca WHERE (nropedido, fecha, fecha_tentativa) IN (" . implode(",", $container_firstquery) . ")  AND estado!='5' AND archivada = 'No' AND finca='" . $_GET['listarfinca'] . "' GROUP BY nropedido";
                                    $result_items = mysqli_query($link, $select_items);
                                    $container_item = array();
                                    $container_item_nropedido = array();
                                    $container_item_precio = array();
                                    $container_item_solicitado = array();
                                    $container_item_entregado = array();
                                    $container_item_rechazado = array();
                                    $container_item_cierre = array();
                                    $container_item_reutilizado = array();
                                    while ($row_items = mysqli_fetch_array($result_items, MYSQLI_BOTH)) {
                                        $container_item_nropedido[] = $row_items[0];
                                        $container_item[] = $row_items[1];
                                        $container_item_precio[] = $row_items[2];
                                        $container_item_solicitado[] = $row_items[7];
                                        $container_item_entregado[] = $row_items[8];
                                        $container_item_rechazado[] = $row_items[9];
                                        $container_item_cierre[] = $row_items[10];
                                        $container_item_reutilizado[] = $row_items[11];
                                    }

                                    ////////////////////////////////////////////////////////////////////////////////////////////////////YA CON LOS ITEMS BUSCAMOS SU DESCRIPCION
                                    $select_itemdesc = "SELECT DISTINCT prod_descripcion, id_item FROM tblproductos where id_item IN (" . implode(",", $container_item) . ")";
                                    $result_itemdesc = mysqli_query($link, $select_itemdesc) or die("Error seleccionando la descripcion del item");
                                    $container_itemdesc = array();
                                    $container_itemid = array();
                                    while ($row_itemdesc = mysqli_fetch_array($result_itemdesc, MYSQLI_BOTH)) {
                                        $container_itemdesc[] = $row_itemdesc[0];
                                        $container_itemid[] = $row_itemdesc[1];
                                    }

                                    ////////////////////////////////////////////////////////////////////////////////////////////////////OBTENEMOS LOS CODIGOS A BUSCAR EN COLDROOM
                                    $select_codigos = "SELECT codigo,nropedido FROM tbletiquetasxfinca WHERE nropedido IN (" . implode(",", $container_nropedido) . ") AND archivada = 'No'";
                                    $result_codigos = mysqli_query($link, $select_codigos) or die("Error seleccionando la descripcion del item");
                                    $container_codigos = array();
                                    $container_codigo_nropedido = array();
                                    while ($row_codigos = mysqli_fetch_array($result_codigos, MYSQLI_BOTH)) {
                                        $container_codigos[] = $row_codigos[0];
                                        $container_codigo_nropedido[] = $row_codigos[1];
                                    }

                                    ///////////////////////////////////////////////////////////////////////////////////OBTENEMOS LAS SIN TRAQUEAR Y LAS TRAQUEADAS
                                    $select_sintra_traq = "SELECT codigo,SUM(case when (entrada,salida) IN (('Si','No')) then 1 else 0 end) as sintraquear,SUM(case when (entrada,salida) IN (('Si','Si')) then 1 else 0 end) as traqueada FROM tblcoldroom WHERE codigo IN (" . implode(",", $container_codigos) . ") GROUP BY codigo";
                                    $result_sintra_traq = mysqli_query($link, $select_sintra_traq) or die("ERROR Llamando las taqueadas y sin traquear");
                                    $container_sintra_traq_nropedido = array();
                                    $container_sintra = array();
                                    $container_traq = array();
                                    while ($row_sintra_traq = mysqli_fetch_array($result_sintra_traq, MYSQLI_BOTH)) {
                                        $posicion_nropeddo = array_search($row_sintra_traq[0], $container_codigos);
                                        $ainsertar = $container_codigo_nropedido[$posicion_nropeddo];
                                        $ainsertar = (int) $ainsertar;
                                        if (in_array($ainsertar, $container_sintra_traq_nropedido)) {
                                            $posicion_insertar = array_search($ainsertar, $container_sintra_traq_nropedido);
                                            $container_sintra[$posicion_insertar] += (int) $row_sintra_traq[1];
                                            $container_traq[$posicion_insertar] += (int) $row_sintra_traq[2];
                                        } else {
                                            $container_sintra_traq_nropedido[] = $ainsertar;
                                            $container_sintra[] = (int) $row_sintra_traq[1];
                                            $container_traq[] = (int) $row_sintra_traq[2];
                                        }
                                    }
                                    array_unshift($container_sintra_traq_nropedido , 'inicio');
                                    array_unshift($container_sintra , '0');
                                    array_unshift($container_traq , '0');

                                    ////////////////////////////////////////////////////////////////VACIAMOS LOS CONTADORES
                                    $totalsol = 0;
                                    $totalentstrack = 0;
                                    $totalenttrack = 0;
                                    $totalent = 0;
                                    $totalrech = 0;
                                    $totaldif = 0;
                                    $totalcierre = 0;
                                    $totalreut = 0;

                                    foreach ($container_nropedido as $nropedido) {
                                        $nropedido_iteracion = array_search($nropedido, $container_nropedido);
                                        $item_iteracion = array_search($nropedido, $container_item_nropedido);
                                        $itemdesc_iteracion = array_search($container_item[$item_iteracion], $container_itemid);
                                        $coldroom_iteracion = array_search($nropedido, $container_sintra_traq_nropedido);
                                        if ($coldroom_iteracion == 0){
                                           $container_sintra[$coldroom_iteracion] = "0";
                                        }
                                        echo "<tr style='cursor:pointer;' id='" . $nropedido . "' class='seleccionable'>";
                                        echo "<td style='width:1%;white-space:nowrap;'><strong>" . $container_fechaentrega[$nropedido_iteracion] . "</strong></td>";
                                        echo "<td style='width:1%;white-space:nowrap;'><strong>" . $container_item[$item_iteracion] . "</strong></td>";
                                        echo "<td style='width:1%;white-space:nowrap;'>" . $container_itemdesc[$itemdesc_iteracion] . "</td>";
                                        echo "<td style='width:1%;white-space:nowrap;'><strong>" . $container_fechatentativa[$nropedido_iteracion] . "</strong></td>";
                                        echo "<td style='width:1%;white-space:nowrap;'>" . $fila['destino'] . "</td>";
                                        echo "<td align='center'>" . $container_item_precio[$item_iteracion] . "</td>";
                                        echo "<td align='center'><a href= 'php/etiqxentregar_rechazar.php?id=" . $nropedido . "' title='Rechazar cajas'><strong>" . $container_item_solicitado[$item_iteracion] . "</strong></a></td>";
                                        echo "<td align='center'><a href= 'php/etiqxentregar_rechazar.php?id=" . $nropedido . "' title='Rechazar cajas'><strong>" . $container_sintra[$coldroom_iteracion] . "</strong></a></td>";
                                        echo "<td align='center'><a href= 'php/etiqxentregar_rechazar.php?id=" . $nropedido . "' title='Rechazar cajas'><strong>" . $container_traq[$coldroom_iteracion] . "</strong></a></td>";
                                        echo "<td align='center'>" . $container_item_entregado[$item_iteracion] . "</td>";
                                        echo "<td align='center'>" . $container_item_rechazado[$item_iteracion] . "</td>";
                                        echo "<td align='center'>" . $container_item_cierre[$item_iteracion] . "</td>";
                                        echo "<td align='center'>" . $container_item_reutilizado[$item_iteracion] . "</td>";

                                        $totalsol += $container_item_solicitado[$item_iteracion];
                                        $totalrech += $container_item_rechazado[$item_iteracion];
                                        $totalentstrack += $container_sintra[$coldroom_iteracion];
                                        $totalenttrack += $container_traq[$coldroom_iteracion];
                                        $totalent += $container_item_entregado[$item_iteracion];
                                        $dif = $container_item_solicitado[$item_iteracion] - $container_item_entregado[$item_iteracion] - $container_item_rechazado[$item_iteracion] - $container_item_cierre[$item_iteracion] - $container_item_reutilizado[$item_iteracion];
                                        if ($dif < 0) {
                                            $dif = "0";
                                        }
                                        $totalcierre += $container_item_cierre[$item_iteracion];
                                        $totalreut += $container_item_reutilizado[$item_iteracion];
                                        $totaldif += $dif;
                                        $totalprecio += $container_item_precio[$item_iteracion];

                                        if ($dif == 0) {
                                            echo "<td align='center'><button type='button' class='btn btn-success' data-toggle='tooltip' data-placement='left' title = 'No hay  cajas pendientes'><strong>" . $dif . "</strong></button></td>";
                                        } else {
                                            echo "<td align='center'><button type='button' class='btn btn-danger' data-toggle='tooltip' data-placement='rigth' title = 'Ver cajas por entregar'><a href= 'php/etiqxentregar.php?id=" . $nropedido . "' title='Ver cajas pendientes'><strong>" . $dif . "</strong></a></button></td>";
                                        }

                                        echo "<input type=\"hidden\" disabled=\"true\" class=\"form-control nropedido\" name=\"nropedido\" id=\"nropedido\" value=\"" . $nropedido . "\"/> ";
                                        echo "</tr>";
                                    }

                                    echo "
                      <tr class='totalpais'>
                      <td align='right'></td>
                      <td align='right'></td>
                      <td align='right'></td>
                      <td align='center'><strong>Total por país:</strong></td>
                      <td align='right'></td>
                      <td align='center'><strong>" . $totalprecio . "</strong></td>
                      <td align='center'><strong>" . $totalsol . "</strong></td>
                      <td align='center'><strong>" . $totalentstrack . "</strong></td>
                      <td align='center'><strong>" . $totalenttrack . "</strong></td>
                      <td align='center'><strong>" . $totalent . "</strong></td>
                      <td align='center'><strong>" . $totalrech . "</strong></td>
                      <td align='center'><strong>" . $totalcierre . "</strong></td>
                      <td align='center'><strong>" . $totalreut . "</strong></td>";

                                    if ($totaldif == 0) {
                                        echo "<td align='center'><button type='button' class='btn btn-success btn-lg' data-toggle='tooltip' data-placement='right' title = 'Cajas faltan por entregar'><strong>0</strong></button></td>";
                                    } else {
                                        echo "<td align='center'><button type='button' class='btn btn-danger btn-lg' data-toggle='tooltip' data-placement='right' title = 'Cajas faltan por entregar'><strong>" . $totaldif . "</strong></button></td>";
                                    }
                                    echo "</tr>";

                                    //Sumar alos totales
                                    $TOTALPRECIO += $totalprecio;
                                    $TOTALSOL += $totalsol;
                                    $TOTALENTSTRACK += $totalentstrack;
                                    $TOTALENTTRACK += $totalenttrack;
                                    $TOTALENT += $totalent;
                                    $TOTALRECH += $totalrech;
                                    $TOTALCIERRE += $totalcierre;
                                    $TOTALREUT += $totalreut;
                                    $TOTALDIF += $totaldif;

                                    //Resetear los subtotales
                                    $totalprecio = 0;
                                    $totalsol = 0;
                                    $totalentstrack = 0;
                                    $totalenttrack = 0;
                                    $totalent = 0;
                                    $totalrech = 0;
                                    $totalcierre = 0;
                                    $totalreut = 0;
                                    $totaldif = 0;
                                    //FIN ELSE		   
                                }//FIN 1ER WHILE
                                echo "
                <tr class='totalgeneral'>
                <td align='right'></td>
                <td align='right'></td>
                <td align='right'></td>				  
                <td align='center'><strong>Total General:</strong></td>
                <td align='right'></td>
                <td align='center'><strong>" . $TOTALPRECIO . "</strong></td>
                <td align='center'><strong>" . $TOTALSOL . "</strong></td>
                <td align='center'><strong>" . $TOTALENTSTRACK . "</strong></td>
                <td align='center'><strong>" . $TOTALENTTRACK . "</strong></td>
                <td align='center'><strong>" . $TOTALENT . "</strong></td>
                <td align='center'><strong>" . $TOTALRECH . "</strong></td>
                <td align='center'><strong>" . $TOTALCIERRE . "</strong></td>
                <td align='center'><strong>" . $TOTALREUT . "</strong></td>";

                                if ($TOTALDIF == 0) {
                                    echo "<td align='center'><button type='button' class='btn btn-success btn-lg' data-toggle='tooltip' data-placement='rigth' title = 'Cajas faltan por entregar'><strong>0</strong></button></td>";
                                } else {
                                    echo "<td align='center'><button type='button' class='btn btn-danger btn-lg' data-toggle='tooltip' data-placement='rigth' title = 'Cajas faltan por entregar'><strong>" . $TOTALDIF . "</strong></button></td>";
                                }
                                echo "</tr>";
                                ?>
                                </tbody>   
                        </table>
                    </div>
            </div>
            <div style="bottom: 0px" class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-sign-out" aria-hidden="true"></i> Salir</button>
                <div class="btn btn-success" id="hacped_edititem_btn"> <i class="fa fa-pencil-square fa-2x" aria-hidden="true"></i> Editar</div>
                <div class="btn btn-danger" id="hacped_deleteitem_btn"> <i class="fa fa-trash fa-2x" aria-hidden="true"></i> Eliminar</div>
                <div class="btn btn-info" id="hacped_archiveitem_btn"> <i class="fa fa-archive fa-2x" aria-hidden="true"></i> Archivar</div>
            </div>
            </form>
        </div>
    </div>
</div>
<div style="z-index: 999;" class="modal animated fadeIn" id="modal_nuevo_pedido" tabindex="-1" role="dialog" aria-labelledby="largeModalHead" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="scripts/pedido_registrar.php" id="hacped_additem" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                    <h4 class="modal-title" id="smallModalHead">Nuevo pedido</h4>
                </div>                    
                <div id="hacped_nuevopedido" class="modal-body">

                    <div class="col-md-6">
                        <div class="form-group" style="width: 80%">
                            <label for="finca">Finca:</label>
                            <input class="form-control finca_ac" type="text" id="finca_ac" name="finca_ac" value="" />
                        </div>
                        <div class="form-group" style="width: 80%">
                            <label for="item1">Producto:</label>
                            <input class="form-control insert_item" type="text" id="prod_ac" name="prod_ac" value="" />
                        </div>
                        <div class="form-group" style="width: 80%">
                            <label for="cantidad1">Cantidad:</label>
                            <input type="text" id="hacped_additem_cantidad" name="hacped_additem_cantidad" placeholder="Cantidad" size="10" class="form-control insert_item"/>
                        </div>
                        <div class="form-group" style="width: 80%">
                            <label for="precioUnitario">Precio:</label>
                            <input type="text" id="hacped_additem_precio" name="hacped_additem_precio" placeholder="Precio Unitario" class="form-control insert_item"/>
                        </div>
                        <button id="hacped_insertar_item" type="button" class="btn btn-primary" >Añadir item</button>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha">Salida de Finca:</label>
                            <div class='input-group 'style="width: 80%">
                                <input type='text'  class="form-control datepicker" name="hacped_additem_salidafinca" id="hacped_additem_salidafinca" value="" placeholder="Fecha salida finca"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group" style="width: 80%">
                            <label for="ftentativa">Fecha Tentativa de Vuelo:</label>
                            <div class='input-group date' >
                                <input type='text' class="form-control datepicker" name="hacped_additem_tentativa" id="hacped_additem_tentativa" value="" placeholder="Fecha tentativa vuelo"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group" style="width: 80%">
                            <label for="finca">Destino:</label>
                            <select id="hacped_additem_destino" name="hacped_additem_destino" class="form-control">
                                <option selected value="CA">CANADA</option>
                                <option selected value="EC">ECUADOR</option>
                                <option selected value="CO">COLOMBIA</option>
                                <option selected value="US">ESTADOS UNIDOS</option>
                            </select>
                        </div>
                        <div class="form-group" style="width: 80%">
                            <label for="agencia">Agencia de Carga:</label>
                            <select id="hacped_additem_agencia" name="hacped_additem_agencia" class="form-control">
                                <?php
                                $select_hacped_agencia = "SELECT nombre_agencia FROM tblagencia";
                                $result_hacped_agencia = mysqli_query($link, $select_hacped_agencia) or die("Error leyendo las agencias");

                                while ($row_hacped_agencia = mysqli_fetch_array($result_hacped_agencia)) {
                                    echo '<option value="' . $row_hacped_agencia['nombre_agencia'] . '">' . $row_hacped_agencia['nombre_agencia'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="hacped_editarpedido" style="display: none;" class="modal-body">
                    <div class="col-md-12">
                        <div class="form-group" style="width: 80%">
                            <label for="item1">Producto:</label>
                            <input class="form-control edit_item" type="text" id="prod_ac2" name="prod_ac" value="" />
                        </div>
                        <div class="form-group" style="width: 80%">
                            <label for="cantidad1">Cantidad:</label>
                            <input type="text" id="hacped_additem_cantidad2" name="hacped_additem_cantidad" placeholder="Cantidad" size="10" class="form-control edit_item"/>
                        </div>
                        <div class="form-group" style="width: 80%">
                            <label for="precioUnitario">Precio:</label>
                            <input type="text" id="hacped_additem_precio2" name="hacped_additem_precio" placeholder="Precio Unitario" class="form-control edit_item"/>
                        </div>
                        <button id="hacped_editar_item_cancelar" type="button" class="btn btn-default" >Cancelar</button>
                        <button id="hacped_editar_item" type="button" class="btn btn-primary" >Editar pedido</button>
                    </div>
                </div>
                <div style="margin-top: 5px;">
                    <div class="row" style="text-align: center">
                        <h3><strong>Listado de items</strong></h3>
                    </div>   
                    <div style="height: 300px;" class="table-responsive">
                        <table id="hacped_items_table" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Editar</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--AQUI SE AGREGAN DINAMICAMENTE LOS VALORES DE LOS INPUT SUPERIORES-->
                            </tbody>
                        </table>                                    
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="hacped_cancelar" type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
                    <input class="btn btn-success" type="submit" value="Registrar" name="submit"  formnovalidate="formnovalidate">
                </div>
            </form>
        </div>
    </div>
</div>