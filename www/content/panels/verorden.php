<?php
$pageindex = $_GET['reportindex'];
if ($pageindex) {
    $page = $_SESSION['page'];
    $pagination = $_SESSION['pagination'];
    $leftcap = $_SESSION['leftcap'];
    $i = $_SESSION['i'];
}
?>
<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="main.php?panel=mainpanel.php">BurtonTech</a></li>
    <li>Órdenes</li>
    <li class="active">Ver Órdenes </li>
</ul>
<!-- FIN BREADCRUMB -->

<!-- FILTROS -->
<div class="page-title">                    
    <h2><span class="fa fa-search"></span> Ver órdenes</h2>
</div>
<!--OPCIONES-->
<div class="col-md-6 pull-right">
    <form action="scripts/verord_filtros.php" method="post" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>Opciones</h3>       
                <div class="col-md-3">                                                                                
                    <select name="pais_select" class="form-control select" data-style="btn-primary">
                        <?php
                        $select_paisdestino = "SELECT codpais,pais_dest FROM tblpaises_destino";
                        $result_paisesdestino = mysqli_query($link, $select_paisdestino);
                        while ($row_paisesdestino = mysqli_fetch_array($result_paisesdestino, MYSQLI_BOTH)) {
                            echo '<option value="' . $row_paisesdestino["codpais"] . '">' . $row_paisesdestino["pais_dest"] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3"> 
                    <button type="button" class="btn btn-primary dropdown-toggle col-md-12" data-toggle="dropdown">Origen</button>
                    <ul class="dropdown-menu">
                        <?php
                        $select_ciudadorigen = "SELECT codciudad,ciudad_origen FROM tblciudad_origen";
                        $result_ciudadorigen = mysqli_query($link, $select_ciudadorigen);
                        if ($rol == 3) {
                            echo '<li><label class="check">&nbsp;&nbsp;&nbsp;<input checked="checked" type="checkbox" class="icheckbox"/> ' . $finca . '</label></li>';
                        } else {
                            while ($row_ciudadorigen = mysqli_fetch_array($result_ciudadorigen, MYSQLI_BOTH)) {
                                if ($row_ciudadorigen["ciudad_origen"] == "Quito") {
                                    echo '<li><label class="check">&nbsp;&nbsp;&nbsp;<input checked="checked" name="' . $row_ciudadorigen["codciudad"] . '" type="checkbox" class="icheckbox"/> ' . $row_ciudadorigen["ciudad_origen"] . '</label></li>';
                                } else {
                                    echo '<li><label class="check">&nbsp;&nbsp;&nbsp;<input name="' . $row_ciudadorigen["codciudad"] . '" type="checkbox" class="icheckbox"/> ' . $row_ciudadorigen["ciudad_origen"] . '</label></li>';
                                }
                            }
                        }
                        ?>
                    </ul>
                </div>
                <div class="col-md-3">                                                                                
                    <select name="estado_select" class="form-control select" data-style="btn-primary">
                        <option value="1">Activas</option>
                        <option value="2">Canceladas</option>
                        <option value="3">Ambas</option>
                    </select>
                </div>
                <div class="col-md-3">                                                                                
                    <select name="track_select" class="form-control select" data-style="btn-primary">
                        <option value="1"> Sin Trackings</option>
                        <option value="2">Con trackings</option>
                        <option value="3">Todas</option>
                    </select>
                </div>
            </div>        
        </div>
</div>
<div class="col-md-12">                                
    <div class="panel panel-default tabs">                            
        <ul class="nav nav-tabs" role="tablist">
            <?php
            $buscarpo = $_GET['ponumber'];
            if ($buscarpo == 'true') {
                echo "
                    <li><a href=\"#filtrosporfecha\" role=\"tab\" data-toggle=\"tab\">Filtros por fecha</a></li>
                    <li class=\"active\"><a href=\"#filtrospordetalle\" role=\"tab\" data-toggle=\"tab\">Filtros por detalles</a></li>
                    <li><a href=\"#filtrosporcliente\" role=\"tab\" data-toggle=\"tab\">Filtros por clientes</a></li>
                  ";
            } else {
                echo "
                    <li class=\"active\"><a href=\"#filtrosporfecha\" role=\"tab\" data-toggle=\"tab\">Filtros por fecha</a></li>
                    <li><a href=\"#filtrospordetalle\" role=\"tab\" data-toggle=\"tab\">Filtros por detalles</a></li>
                    <li><a href=\"#filtrosporcliente\" role=\"tab\" data-toggle=\"tab\">Filtros por clientes</a></li>
                  ";
            }
            ?>
        </ul>                            
        <div class="panel-body tab-content">
            <div class="tab-pane 
                 <?php
                if ($buscarpo == 'true') {
                    echo " ";
                } else {
                    echo "active";
                }
                ?>
                 " id="filtrosporfecha">
                <div class="col-md-12">
                    <div class="panel panel-default tabs nav-tabs-vertical">                   
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#fecha_nuevas" data-toggle="tab">Nuevas</a></li>
                            <li><a href="#fecha_orden" data-toggle="tab">Fecha de orden</a></li>
                            <li><a href="#fecha_vuelo" data-toggle="tab">Fecha de vuelo</a></li>
                            <li><a href="#fecha_entrega" data-toggle="tab">Fecha de entrega</a></li>
                        </ul>                    
                        <div class="panel-body tab-content">
                            <div class="tab-pane active" id="fecha_nuevas">
                                <div class="panel panel-default">
                                    <div class="panel-heading" >
                                        <h3 class="panel-title"><strong>Nuevas</strong></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-4">
                                            <label>Desde:</label>
                                            <div class="input-group" style="width: auto;">
                                                <input type='text' class="form-control datepicker" name="verord_new_from" id="verord_new_from" value="<?php echo date('Y-m-d') ?>" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Hasta:</label>
                                            <div class="input-group" style="width: auto;">
                                                <input type='text' class="form-control datepicker" name="verord_new_to" id="verord_new_to" value="<?php echo date('Y-m-d') ?>" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div style="padding-top: 20px" class="col-md-2">
                                            <input type="submit" name="verord_new_submit" id="verord_new_submit" value="Buscar" class="btn btn-primary" title="Buscar"/>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="tab-pane" id="fecha_orden">
                                <div class="panel panel-default" id="ordate">
                                    <div class="panel-heading" >
                                        <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR FECHA DE ORDEN</strong></h3>
                                    </div>
                                    <div class="panel-body">

                                        <div class="col-md-4">
                                            <p><strong>Desde</strong></p>
                                            <div class='input-group'>
                                                <input type='text' class="form-control datepicker" name="verord_ord_from" id="verord_ord_from" value="<?php echo date('Y-m-d') ?>" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Hasta:</strong></p>
                                            <div class='input-group date' id='datetimepicker4'>
                                                <input type='text' class="form-control" name="verord_ord_to" id="verord_ord_to" value="<?php echo date('Y-m-d') ?>" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div style="padding-top: 20px" class="col-md-2">
                                                <input type="submit" name="verord_ord_submit" id="verord_ord_submit" value="Buscar" class="btn btn-primary" title="Buscar"/>
                                            </div>
                                        </div>
                                        <label class="my-error-class" id='errorFecha' style="display: none;"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="fecha_vuelo">
                                <div class="panel panel-default" id="sshipto">
                                    <div class="panel-heading" >
                                        <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR FECHA DE VUELO</strong></h3>
                                    </div>
                                    <div class="panel-body">

                                        <div class="col-md-4">
                                            <p><strong>Desde</strong></p>
                                            <div class='input-group'>
                                                <input type='text' class="form-control datepicker" name="verord_fli_from" id="verord_fli_from" value="<?php echo date('Y-m-d') ?>" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Hasta:</strong></p>
                                            <div class='input-group date' id='datetimepicker4'>
                                                <input type='text' class="form-control" name="verord_fli_to" id="verord_fli_to" value="<?php echo date('Y-m-d') ?>" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div style="padding-top: 20px" class="col-md-2">
                                                <input type="submit" name="verord_fli_submit" id="verord_fli_submit" value="Buscar" class="btn btn-primary" title="Buscar"/>
                                            </div>
                                        </div>
                                        <label class="my-error-class" id='errorFecha' style="display: none;"></label>
                                    </div>
                                </div>
                            </div>  
                            <div class="tab-pane" id="fecha_entrega">
                                <div class="panel panel-default" id="ddeliver">
                                    <div class="panel-heading" >
                                        <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR FECHA DE ENTREGA</strong></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-4">
                                            <label>Desde:</label>
                                            <div class="input-group" style="width: auto;">
                                                <input type='text' class="form-control datepicker" name="verord_del_from" id="verord_del_from" value="<?php echo date('Y-m-d') ?>" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Hasta:</label>
                                            <div class="input-group" style="width: auto;">
                                                <input type='text' class="form-control datepicker" name="verord_del_to" id="verord_del_to" value="<?php echo date('Y-m-d') ?>" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div style="padding-top: 20px" class="col-md-2">
                                            <input type="submit" name="verord_del_submit" id="verord_del_submit" value="Buscar" class="btn btn-primary" title="Buscar"/>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane 
                 <?php
                if ($buscarpo == 'true') {
                    echo "active";
                } else {
                    echo " ";
                }
                ?>
                 " id="filtrospordetalle">
                <div class="col-md-12">
                    <div class="panel panel-default tabs nav-tabs-vertical">                   
                        <ul class="nav nav-tabs">
                             <?php
                            if ($buscarpo == 'true') {
                                echo "
                                    <li><a href=\"#detalle_tracking\" data-toggle=\"tab\">Por tracking</a></li>
                                    <li class=\"active\"><a href=\"#detalle_ponumber\" data-toggle=\"tab\">Por ponumber</a></li>
                                    <li><a href=\"#detalle_custnumber\" data-toggle=\"tab\">Por custnumber</a></li>
                                    <li><a href=\"#detalle_producto\" data-toggle=\"tab\">Por producto</a></li>
                                  ";
                            } else {
                                echo "
                                    <li class=\"active\"><a href=\"#detalle_tracking\" data-toggle=\"tab\">Por tracking</a></li>
                                    <li><a href=\"#detalle_ponumber\" data-toggle=\"tab\">Por ponumber</a></li>
                                    <li><a href=\"#detalle_custnumber\" data-toggle=\"tab\">Por custnumber</a></li>
                                    <li><a href=\"#detalle_producto\" data-toggle=\"tab\">Por producto</a></li>
                                  ";
                            }
                            ?>
                        </ul>                    
                        <div class="panel-body tab-content">
                            <div class="tab-pane 
                                 <?php
                                if ($buscarpo == 'true') {
                                    echo " ";
                                } else {
                                    echo "active";
                                }
                                ?>
                                 " id="detalle_tracking">
                                <div class="panel panel-default" id="ttracking">
                                    <div class="panel-heading" >
                                        <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR TRACKING</strong></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <label><strong>Tracking:</strong></label>
                                            <input name="verord_tra_input" type="text" id="verord_tra_input" class="form-control" style="width:200px; margin-bottom: 16px;"/>
                                            <input type="submit" name="verord_tra_submit" id="verord_tra_submit" value="Buscar" class="btn btn-primary"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane 
                                 <?php
                                if ($buscarpo == 'true') {
                                    echo "active";
                                } else {
                                    echo " ";
                                }
                                ?>
                                 " id="detalle_ponumber">
                                <div class="panel panel-default" id="po">
                                    <div class="panel-heading" >
                                        <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR PONUMBER</strong></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <label><strong>Ponumber:</strong></label>
                                            <input name="verord_pon_input" type="text" id="verord_pon_input"  class="form-control" style="width:200px; margin-bottom: 16px;"/>
                                            <input type="submit" name="verord_pon_submit" id="verord_pon_submit" value="Buscar" class="btn btn-primary" title="Buscar" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="detalle_custnumber">
                                <div class="panel panel-default" id="ccustnumber">
                                    <div class="panel-heading" >
                                        <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR CUSTNUMBER</strong></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <label><strong>Custnumber:</strong></label>
                                            <input name="verord_cus_input" type="text" id="verord_cus_input"  class="form-control" style="width:200px; margin-bottom: 16px;"/>
                                            <input type="submit" name="verord_cus_submit" id="verord_cus_submit" value="Buscar" class="btn btn-primary" title="Buscar" />
                                        </div>
                                    </div>
                                </div>
                            </div>      
                            <div class="tab-pane" id="detalle_producto">
                                <div class="panel panel-default" id="iitem">
                                    <div class="panel-heading" >
                                        <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR PRODUCTO</strong></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <label><strong>Producto:</strong></label>
                                            <input name="verord_ite_input" type="text" id="verord_ite_input"  class="form-control" style="width:200px; margin-bottom: 16px;"/>
                                            <input type="submit" name="verord_ite_submit" id="verord_ite_submit" value="Buscar" class="btn btn-primary" title="Buscar" />
                                        </div>
                                    </div>
                                </div>     
                            </div>  
                        </div>
                    </div>                        
                </div>
            </div>
            <div class="tab-pane" id="filtrosporcliente">
                <div class="col-md-12">
                    <div style="height: 50vh" class="panel panel-default tabs nav-tabs-vertical">                   
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#cliente_recnombre" data-toggle="tab">Nombre del receptor</a></li>
                            <li><a href="#cliente_recdir" data-toggle="tab">Dirección del receptor</a></li>
                            <li><a href="#cliente_comnombre" data-toggle="tab">Nombre del comprador</a></li>
                            <li><a href="#cliente_comdir" data-toggle="tab">Dirección del comprador</a></li>
                        </ul>                    
                        <div class="panel-body tab-content">
                            <div class="tab-pane active" id="cliente_recnombre">
                                <div class="panel panel-default" id="sshipto1">
                                    <div class="panel-heading" >
                                        <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR NOMBRE DEL RECEPTOR</strong></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <label><strong>Nombre:</strong></label>
                                            <input name="verord_ndr_input" type="text" id="verord_ndr_input"  class="form-control" style="width:200px; margin-bottom: 16px;"/>
                                            <input type="submit" name="verord_ndr_submit" id="verord_ndr_submit" value="Buscar" class="btn btn-primary" title="Buscar" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="cliente_recdir">
                                <div class="panel panel-default" id="ddireccion">
                                    <div class="panel-heading" >
                                        <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR DIRECCIÓN DEL RECEPTOR</strong></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <label><strong>Direcicon:</strong></label>
                                            <input name="verord_ddr_input" type="text" id="verord_ddr_input"  class="form-control" style="width:200px; margin-bottom: 16px;"/>
                                            <input type="submit" name="verord_ddr_submit" id="verord_ddr_submit" value="Buscar" class="btn btn-primary" title="Buscar" />
                                        </div>
                                    </div>
                                </div>      
                            </div>
                            <div class="tab-pane" id="cliente_comnombre">
                                <div class="panel panel-default" id="ssoldto1">
                                    <div class="panel-heading" >
                                        <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR NOMBRE DEL COMPRADOR</strong></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <label><strong>Nombre:</strong></label>
                                            <input name="verord_ndc_input" type="text" id="verord_ndc_input"  class="form-control" style="width:200px; margin-bottom: 16px;"/>
                                            <input type="submit" name="verord_ndc_submit" id="verord_ndc_submit" value="Buscar" class="btn btn-primary" title="Buscar" />
                                        </div>
                                    </div>
                                </div> 
                            </div>      
                            <div class="tab-pane" id="cliente_comdir">
                                <div class="panel panel-default" id="ccpdireccion_soldto">
                                    <div class="panel-heading" >
                                        <h3 class="panel-title"><strong>BUSCAR ÓRDENES POR DIRECCIÓN DEL COMPRADOR</strong></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <label><strong>Direcicon:</strong></label>
                                            <input name="verord_ddc_input" type="text" id="verord_ddc_input"  class="form-control" style="width:200px; margin-bottom: 16px;"/>
                                            <input type="submit" name="verord_ddc_submit" id="verord_ddc_submit" value="Buscar" class="btn btn-primary" title="Buscar" />
                                        </div>
                                    </div>
                                </div> 
                            </div>  
                        </div>
                    </div> 
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- FIN FILTROS -->

    <!-- REPORTE -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Reporte de &oacute;rdenes <b><?php echo " " . $_SESSION['reportede']; ?></b></h3>
            <div class="btn-group pull-right">
                <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> Exportar</button>
                <ul class="dropdown-menu">
                    <li><a href="scripts/xlsups.php" ><img src='img/icons/ups.png' width="24"/> UPS</a></li>
                    <li id="fedex_btn"><a href="" ><img src='img/icons/fex.png' width="24"/> FedEx</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onClick ="$('#verorden_reporte').tableExport({type: 'excel', escape: 'false'});"><img src='img/icons/xls.png' width="24"/> XLS</a></li>
                    <li class="divider"></li>
                    <li><a href="scripts/verord_csv.php" ><img src='img/icons/csv.png' width="24"/> CSV</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onClick ="$('#verorden_reporte').tableExport({type: 'png', escape: 'false'});"><img src='img/icons/png.png' width="24"/> PNG</a></li>
                    <li><a href="#" onClick ="$('#verorden_reporte').tableExport({type: 'pdf', escape: 'false'});"><img src='img/icons/pdf.png' width="24"/> PDF</a></li>
                </ul>
            </div>
            <div class="col-md-2 pull-right" >
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table id="verorden_reporte" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Estatus</th>
                            <th>Env&iacute;o</th>
                            <th>Reenv&iacute;o</th>
                            <th>Tracking</th>
                            <th>Fecha de órden</th>
                            <th>Enviar a</th>
                            <th>Dirección</th>
                            <th>Ciudad</th>
                            <th>PO</th>
                            <th>Custnumber</th>
                            <th>Fecha de vuelo</th>
                            <th>Fecha de entrega</th>  
                            <th>Producto</th>            
                            <th>Descripci&oacute;n</th>            
                            <th>País de envío</th>
                            <th>Finca</th>
                        </tr>
                    </thead>
                    <tbody><?php
                        print_r($page);
                        ?>
                    </tbody>
                </table>                                    
            </div>
        </div>
        <div class="panel-footer">
            <h5 class="panel-title pull-left">
                <b>Mostrando: </b>
                <span class="btn btn-primary">
                    <strong style="font-size: 200%"><?php
                        if ($pageindex) {
                            $pagination = $leftcap + $pagination;
                            $pagination = $pagination - 1;
                            $i = $i - 1;
                            if ($leftcap == 0) {
                                $leftcap = 0;
                            } else {
                                $leftcap = $leftcap + 1;
                            }
                            echo $leftcap . " - " . $pagination;
                        } else {
                            echo "0 - 0";
                        }
                        ?></strong>
                </span>
                <b> de un total de </b>
                <span class="btn btn-primary">
                    <strong style="font-size: 200%"><?php echo $i; ?></strong>
                </span>
                <b> registros </b>
            </h5>
            <h5 class="panel-title pull-right">
                <?php
                if ($pageindex) {
                    $_SESSION['pageindex'] = $pageindex;
                    echo "
                        <form id=\"prevpage\" action=\"scripts/verord_filtros.php\" method=\"post\" enctype=\"multipart/form-data\">";
                    if ($leftcap == 0) {
                        echo "<span class=\"btn btn-primary\">
                                <button disabled=\"true\" type=\"submit\" name=\"step_back\" class=\"btn btn-primary\"><i class=\"fa fa-step-backward\" aria-hidden=\"true\"></i></button>
                            </span>";
                    } else {
                        echo "<span class=\"btn btn-primary\">
                                <button type=\"submit\" name=\"step_back\" class=\"btn btn-primary\"><i class=\"fa fa-step-backward\" aria-hidden=\"true\"></i></button>
                            </span>";
                    }
                    $rightcap = $leftcap + 5000;
                    if ($i < $rightcap) {
                        echo "<span class=\"btn btn-primary\">
                                <button disabled=\"true\" type=\"submit\" name=\"step_ford\" class=\"btn btn-primary\"><i class=\"fa fa-step-forward\" aria-hidden=\"true\"></i></strong></button>
                            </span>
                        </form>";
                    } else {
                        echo "<span class=\"btn btn-primary\">
                                <button type=\"submit\" name=\"step_ford\" class=\"btn btn-primary\"><i class=\"fa fa-step-forward\" aria-hidden=\"true\"></i></strong></button>
                            </span>
                        </form>";
                    }
                } else {
                    echo "
                        <span class=\"btn btn-primary\">
                            <button type=\"submit\" name=\"step_back\" class=\"btn btn-primary\"><i class=\"fa fa-step-backward\" aria-hidden=\"true\"></i></button>
                        </span>
                        <span class=\"btn btn-primary\">
                            <button type=\"submit\" name=\"step_ford\" class=\"btn btn-primary\"><i class=\"fa fa-step-forward\" aria-hidden=\"true\"></i></strong></button>
                        </span>";
                }
                ?>

            </h5>
        </div>
        <a name="reporte"></a> 
    </div>
    <!-- FIN REPORTE -->
