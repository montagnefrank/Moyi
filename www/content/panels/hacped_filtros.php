<?php ?>
<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="main.php?panel=mainpanel.php">BurtonTech</a></li>
    <li>Pedidos</li>
    <li>Hacer Pedidos</li>
    <li class="active">Filtros</li>
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

    <div class="col-md-6 pull-right">
        <form method="post" enctype="multipart/form-data">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form method="post" enctype="multipart/form-data">
                        <h3>Filtros de reporte</h3>       
                        <div class="col-md-3">   
                            <div class="form-group">
                                <input type="text" class="form-control datepicker" id="hacped_filtros_fechasalida_input" name="fechasalida" placeholder="Salida..."  onkeyup="()"/>
                            </div>
                        </div>
                        <div class="col-md-3"> 
                            <div class="form-group">
                                <input type="text" class="form-control datepicker" id="hacped_filtros_fechavuelo_input" name="fechavuelo" placeholder="Vuelo..." onkeyup="()" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control" id="agencia_ac" name="agencia" placeholder="Agencia..." onkeyup="()"  />
                            </div>
                        </div>
                        <div class="col-md-3"> 
                            <div class="form-group">
                                <input type="text" class="form-control" id="finca_ac" name="finca" placeholder="Finca..." onkeyup="()" />
                            </div>
                        </div>
                        <div style="margin-top: 16px;" class="col-md-2 pull-right">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-filter" aria-hidden="true"></i> Filtrar </button>
                            </div>
                        </div>
                        <div style="margin-top: 16px;" class="col-md-2 pull-right">
                            <div class="form-group">
                                <a href="main.php?panel=hacped_filtros.php" class="btn btn-info"><i class="fa fa-refresh" aria-hidden="true"></i> Limpiar </a>
                            </div>
                        </div>

                    </form>
                </div>        
            </div>
    </div>
    <div class="page-title">                    
        <h2><span class="fa fa-cart-plus"></span> Listado de pedidos</h2>
        <div class="btn-group pull-right">
            <form action="php/Fichero_Excel.php" method="post" target="_blank" id="FormularioExportacion">
                <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> Exportar</button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="botonExcel" style="cursor: pointer;">
                            <img src='img/icons/xls.png' width="24"/> XLS
                        </a>
                        <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
                    </li>
                    <li class="divider"></li>
                    <li><a href="#" onClick ="$('#hacped_filtros_table').tableExport({type: 'png', escape: 'false'});"><img src='img/icons/png.png' width="24"/> PNG</a></li>
                    <li><a href="#" onClick ="$('#hacped_filtros_table').tableExport({type: 'pdf', escape: 'false'});"><img src='img/icons/pdf.png' width="24"/> PDF</a></li>
                </ul>
            </form>
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">                                
            <div class="panel-body">
                <div class="divtabla">
                    <div class="table-responsive">
                        <table id="hacped_filtros_table" class="table table-striped" >
                            <tbody id="hacped_filtros_table_body">
                                <?php
                                //recorro por los destinos primero
                                $sql = "SELECT DISTINCT tbletiquetasxfinca.destino FROM tbletiquetasxfinca";
                                $res = mysqli_query($link, $sql);
                                while ($row = mysqli_fetch_array($res)) {
                                    //recorro por las agencias
                                    $sql1 = "SELECT DISTINCT tbletiquetasxfinca.agencia FROM tbletiquetasxfinca WHERE tbletiquetasxfinca.archivada!='Si' AND (tbletiquetasxfinca.estado!='5')";

                                    //si esta el filtro de agencia
                                    if (isset($_POST['agencia']) && $_POST['agencia'] != "") {
                                        $sql1 .= " AND agencia='" . $_POST['agencia'] . "'";
                                    }

                                    $res1 = mysqli_query($link, $sql1);

                                    while ($row1 = mysqli_fetch_array($res1)) {

                                        $sql3 = "SELECT COUNT(tbletiquetasxfinca.item) as cant,
                    tbletiquetasxfinca.fecha,
                    tbletiquetasxfinca.fecha_tentativa,
                    tbletiquetasxfinca.destino,
                    tbletiquetasxfinca.agencia,
                    tbletiquetasxfinca.finca,
                    tbletiquetasxfinca.item,
                    tblproductos.prod_descripcion,
                    tblproductos.item as id_item,
                    tblproductos.pack,
                    tblproductos.receta,
                    tbletiquetasxfinca.precio,
                    tblboxtype.tipo_Box
                    FROM
                    tbletiquetasxfinca
                    INNER JOIN tblproductos ON tblproductos.id_item = tbletiquetasxfinca.item
                    INNER JOIN tblboxtype ON tblproductos.boxtype = tblboxtype.id_Box
                    WHERE tbletiquetasxfinca.agencia='" . $row1['agencia'] . "'
                    AND tbletiquetasxfinca.destino='" . $row['destino'] . "'
                    AND tbletiquetasxfinca.archivada!='Si'
                    AND (tbletiquetasxfinca.estado!='5')";

                                        //aplicando los filtros de fecha de salida, vuelo y finca
                                        if (isset($_POST['fechasalida']) && $_POST['fechasalida'] != "")
                                            $sql3 .= " AND tbletiquetasxfinca.fecha='" . $_POST['fechasalida'] . "'";
                                        if (isset($_POST['fechavuelo']) && $_POST['fechavuelo'] != "")
                                            $sql3 .= " AND tbletiquetasxfinca.fecha_tentativa='" . $_POST['fechavuelo'] . "'";
                                        if (isset($_POST['finca']) && $_POST['finca'] != "")
                                            $sql3 .= " AND tbletiquetasxfinca.finca='" . $_POST['finca'] . "'";

                                        $sql3 .= " GROUP BY tbletiquetasxfinca.fecha,tbletiquetasxfinca.fecha_tentativa,tbletiquetasxfinca.item,tbletiquetasxfinca.finca ORDER BY tbletiquetasxfinca.finca ASC";

                                        $res3 = mysqli_query($link, $sql3);
                                        $num = mysqli_num_rows($res3);
                                        $finca = "0";
                                        $contador = 1;
                                        $i = 0;
                                        $tot_cajasOrdenadas = 0;
                                        $tot_precio = 0;
                                        $tot_fullbox = 0;

                                        while ($row3 = mysqli_fetch_array($res3)) {
                                            if ($i == 0) {
                                                ?>
                                                <tr style="font-weight: bold">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="font-size: 30px;"><strong><?php echo $row['destino'] ?></strong></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr style="font-weight: bold">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="font-size: 30px;" colspan="2"><strong><?php echo $row1['agencia'] ?></strong></td>
                <!--                                                    <td></td>-->
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr >
                                                    <td style="background-color: #428bca !important;color: white;">Salida de Finca</td>
                                                    <td style="background-color: #428bca !important;color: white;">Fecha Vuelo</td>
                                                    <td style="background-color: #428bca !important;color: white;">Destino</td>
                                                    <td style="background-color: #428bca !important;color: white;">Agencia</td>
                                                    <td style="background-color: #428bca !important;color: white;">Finca</td>
                                                    <td style="background-color: #428bca !important;color: white;">Producto</td>
                                                    <td style="background-color: #428bca !important;color: white;">Desc.Prod.</td>
                                                    <td style="background-color: #428bca !important;color: white;">Pack</td>
                                                    <td style="background-color: #428bca !important;color: white;">Receta</td>
                                                    <td style="background-color: #428bca !important;color: white;">Imagen</td>
                                                    <td style="background-color: #428bca !important;color: white;">Cajas Ordenadas</td>
                                                    <td style="background-color: #428bca !important;color: white;">Precio/Caja</td>
                                                    <td style="background-color: #428bca !important;color: white;">Precio Total</td>
                                                    <td style="background-color: #428bca !important;color: white;">Tipo Caja</td>
                                                    <td style="background-color: #428bca !important;color: white;">Full Box</td>
                                                </tr>
                                                <?php
                                                $i = 1;
                                            }


                                            if ($finca != $row3['finca']) {
                                                if ($finca != "0") {
                                                    echo '<tr class="total_hide" style="background-color:#4f4a4a;font-weight: bold">
                                                    <td class="clear_before_report" style="display: none;">' . $row3['fecha'] . '</td>    
                                                    <td class="clear_before_report" style="display: none;">' . $row3['fecha_tentativa'] . '</td>
                                                    <td  style="background-color: #4f4a4a !important;color: white;"></td>    
                                                    <td class="clear_before_report" style="display: none;">' . $row3['agencia'] . '</td>    
                                                    <td class="clear_before_report" style="display: none;">' . $finca . '</td>    
                                                    <td style="background-color: #4f4a4a !important;color: white;"></td>
                                                    <td style="background-color: #4f4a4a !important;color: white;"></td>
                                                    <td style="background-color: #4f4a4a !important;color: white;"></td>
                                                    <td style="background-color: #4f4a4a !important;color: white;"><strong>TOTAL</strong></td>
                                                    <td style="background-color: #4f4a4a !important;color: white;"></td>
                                                    <td style="background-color: #4f4a4a !important;color: white;"></td>
                                                    <td style="background-color: #4f4a4a !important;color: white;"></td>
                                                    <td style="background-color: #4f4a4a !important;color: white;"></td>
                                                    <td style="background-color: #4f4a4a !important;color: white;"></td>
                                                    <td style="background-color: #4f4a4a !important;color: white;">' . $tot_cajasOrdenadas . '</td>
                                                    <td style="background-color: #4f4a4a !important;color: white;"></td>
                                                    <td style="background-color: #4f4a4a !important;color: white;">' . $tot_precio . '</td>
                                                    <td style="background-color: #4f4a4a !important;color: white;"></td>
                                                    <td style="background-color: #4f4a4a !important;color: white;">' . $tot_fullbox . '</td>
                                                    </tr>';
                                                    $finca = $row3['finca'];
                                                    $tot_cajasOrdenadas = 0;
                                                    $tot_precio = 0;
                                                    $tot_fullbox = 0;
                                                } else {
                                                    $finca = $row3['finca'];
                                                }
                                            }
                                            ?>
                                            <tr class="total_hide">
                                                <td  ><?php echo $row3['fecha']; ?></td>
                                                <td  ><?php echo $row3['fecha_tentativa']; ?></td>
                                                <td  ><?php echo $row3['destino']; ?></td>
                                                <td  ><?php echo $row3['agencia']; ?></td>
                                                <td ><?php echo $row3['finca']; ?></td>
                                                <td  ><?php echo $row3['item']; ?></td>
                                                <td  ><?php echo $row3['prod_descripcion']; ?></td>
                                                <td  ><?php echo $row3['pack']; ?></td>
                                                <td  ><?php echo substr($row3['receta'], 0, 50) . " " . substr($row3['receta'], 50); ?></td>
                                                <td  ><?php
                                                    if (file_exists('images/productos/' . $row3['id_item'] . '.jpg'))
                                                        echo '<img class="imag" src="images/productos/' . $row3['id_item'] . '.jpg" alt="Imagen" width="50px" height="50px"/>';
                                                    ?>
                                                </td>
                                                <td style="background-color: #F1F1F1;"><?php
                                                    $tot_cajasOrdenadas += $row3['cant'];
                                                    echo $row3['cant'];
                                                    ?>
                                                </td>
                                                <td style='width:1%;white-space:nowrap;'><?php echo number_format((float) $row3['precio'], 2, '.', ''); ?></td>
                                                <td style="background-color: #F1F1F1;"><?php
                                                    $tot_precio += floatval($row3['precio'] * $row3['cant']);
                                                    echo floatval($row3['precio'] * $row3['cant']);
                                                    ?>
                                                </td>
                                                <td style="mso-number-format:'@'"><?php echo $row3['tipo_Box']; ?></td>
                                                <td style="background-color: #F1F1F1;"><?php
                                                    if ($row3['tipo_Box'] == 'QBX') {
                                                        $tot_fullbox += floatval($row3['cant'] * 0.25);
                                                        echo floatval($row3['cant'] * 0.25);
                                                    }
                                                    if ($row3['tipo_Box'] == 'HLF') {
                                                        $tot_fullbox += floatval($row3['cant'] * 0.5);
                                                        echo floatval($row3['cant'] * 0.5);
                                                    }
                                                    if ($row3['tipo_Box'] == '1/8') {
                                                        $tot_fullbox += floatval($row3['cant'] * 0.125);
                                                        echo floatval($row3['cant'] * 0.125);
                                                    }
                                                    ?></td>
                                            </tr>   





                                            <?php
                                            //si estoy en la ultima fila
                                            if ($contador == $num) {
                                                echo '<tr class="total_hide" style="background-color:#D3D3D3;font-weight: bold">
                                                <td style="background-color: #000000 !important;color: white;"></td>
                                                <td style="background-color: #000000 !important;color: white;"></td>
                                                <td style="background-color: #000000 !important;color: white;"></td>
                                                <td style="background-color: #000000 !important;color: white;"></td>
                                                <td style="background-color: #000000 !important;color: white;">TOTAL</td>
                                                <td style="background-color: #000000 !important;color: white;"></td>
                                                <td style="background-color: #000000 !important;color: white;"></td>
                                                <td style="background-color: #000000 !important;color: white;"></td>
                                                <td style="background-color: #000000 !important;color: white;"></td>
                                                <td style="background-color: #000000 !important;color: white;"></td>
                                                <td style="background-color: #000000 !important;color: white;">' . $tot_cajasOrdenadas . '</td>
                                                <td style="background-color: #000000 !important;color: white;"></td>
                                                <td style="background-color: #000000 !important;color: white;">' . $tot_precio . '</td>
                                                <td style="background-color: #000000 !important;color: white;"></td>
                                                <td style="background-color: #000000 !important;color: white;">' . $tot_fullbox . '</td>
                                                </tr>';
                                                $tot_cajasOrdenadas = 0;
                                                $tot_precio = 0;
                                                $tot_fullbox = 0;
                                                ?>   


                                                <?php
                                            }
                                            $contador++;
                                        }
                                        ?> 

                                        <?php
                                    }
                                }
                                ?> 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>