<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="main.php?panel=mainpanel.php">BurtonTech</a></li>
    <li>Venta </li>
    <li class="active">Punto de venta</li>
</ul>
<!-- FIN BREADCRUMB -->
<div class="page-title">                    
    <h2><span class="fa fa-check-square"></span> Registrar nueva orden</h2>
</div>
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
    <!-- PASO POR PASO -->
    <div class="panel panel-default">
        <div class="panel-body">
            <form id="pdv_registrarorden_form" action="scripts/pdv_controller.php" method="post" role="form" class="form-horizontal">
                <div class="wizard show-submit">                                
                    <ul>
                        <li>
                            <a href="#step-1">
                                <span class="stepNumber">1</span>
                                <span class="stepDesc">Datos del cliente <br /><small> Datos generales del cliente</small></span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-5">
                                <span class="stepNumber">1</span>
                                <span class="stepDesc">Datos de la orden<br /><small>Fechas y filtros</small></span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-6">
                                <span class="stepNumber">2</span>
                                <span class="stepDesc">Datos del producto<br /><small>Productos y datos de despacho</small></span>
                            </a>
                        </li>                                    
                    </ul>
                    <div id="step-1">   
                        <!-- TABLA DE CLIENTES -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Seleccione el cliente</h3>
                                <div class="btn-group pull-right">
                                    <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> Exportar</button>
                                    <ul class="dropdown-menu">
                                        <li><a href="#" onClick ="$('#pdv_client_table').tableExport({type: 'csv', escape: 'false'});"><img src='img/icons/csv.png' width="24"/> CSV</a></li>
                                        <li><a href="#" onClick ="$('#pdv_client_table').tableExport({type: 'txt', escape: 'false'});"><img src='img/icons/txt.png' width="24"/> TXT</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#" onClick ="$('#pdv_client_table').tableExport({type: 'excel', escape: 'false'});"><img src='img/icons/xls.png' width="24"/> XLS</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#" onClick ="$('#pdv_client_table').tableExport({type: 'png', escape: 'false'});"><img src='img/icons/png.png' width="24"/> PNG</a></li>
                                        <li><a href="#" onClick ="$('#pdv_client_table').tableExport({type: 'pdf', escape: 'false'});"><img src='img/icons/pdf.png' width="24"/> PDF</a></li>
                                    </ul>
                                </div>
                                <div class="col-md-2 pull-right" >
                                    <button type="button" class="btn btn-primary" id="btn_nuevo_cliente" data-toggle="modal" data-target="#modal_nuevo_cliente" data-placement="rigth" title="Crear nuevo Cliente">
                                        <span class="glyphicon glyphicon-plus-sign black"> </span> Nuevo Cliente
                                    </button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div style="height: 400px;" class="table-responsive">
                                    <table id="pdv_client_table" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>Dirección</th>
                                                <th>Dirección +</th>
                                                <th>Ciudad</th>
                                                <th>Estado</th>
                                                <th>ZIP</th>
                                                <th>País</th>
                                                <th>Teléfono</th>
                                                <th>Vendedor</th>
                                                <th>E-mail</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pdv_client_table_body">
                                            <?php
                                            $query_clientes = "SELECT tblcliente.*,tblusuario.cpnombre FROM tblcliente INNER JOIN tblusuario ON tblusuario.id_usuario = tblcliente.vendedor";
                                            $result_clientes = mysqli_query($link, $query_clientes);
                                            $filas = mysqli_num_rows($result_clientes);
                                            $cliente_seleccionado = $_GET['cliente'];
                                            while ($row = mysqli_fetch_array($result_clientes, MYSQLI_BOTH)) {
                                                $nombre = $row['empresa'];
                                                $apellido = $row['vendedor'];
                                                $i++;
                                                if ($cliente_seleccionado == $row['codigo']) {
                                                    echo "<tr class='table_selected'>";
                                                } else {

                                                    echo "<tr>";
                                                }
                                                echo "<td style='width:1%;white-space:nowrap;'>" . $row['codigo'] . "</td>";
                                                echo "<td style='width:1%;white-space:nowrap;'>" . $row['empresa'] . "</td>";
                                                echo "<td style='width:1%;white-space:nowrap;'>" . $row['direccion'] . "</td>";
                                                echo "<td style='width:1%;white-space:nowrap;' align='center'>" . $row['direccion2'] . "</td>";
                                                echo "<td style='width:1%;white-space:nowrap;' align='center'>" . $row['ciudad'] . "</td>";
                                                echo "<td style='width:1%;white-space:nowrap;' align='center'>" . $row['estado'] . "</td>";
                                                echo "<td style='width:1%;white-space:nowrap;' align='center'>" . $row['zip'] . "</td>";
                                                echo "<td style='width:1%;white-space:nowrap;'>" . $row['pais'] . "</td>";
                                                echo "<td style='width:1%;white-space:nowrap;' align='center'>" . $row['telefono'] . "</td>";
                                                echo "<td style='width:1%;white-space:nowrap;' align='center'>" . $row['cpnombre'] . "</td>";
                                                echo "<td style='width:1%;white-space:nowrap;'>" . $row['mail'] . "</td></label></td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        <input type="hidden" class="form-control" name="clientid" id="clientid" value="<?php echo $_GET['cliente']; ?>"/> 
                                        </tbody>
                                    </table>                                    
                                </div>
                            </div>
                        </div>
                    </div>                       
                    <div id="step-5">   
                        <div class="row">
                            <div class="col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-heading" style="text-align: left;">Seleccione el número de PO</div>
                                    <div class="panel-body">
                                        <table class="table">
                                            <tr>
                                                <td style="background: #FFFFFF;">
                                                    <label for="poradiodefault" class="radio-inline"> 
                                                        <input type="radio" class="icheckbox iradio" name="poradio" id="poradiodefault" value="defaultpo" checked="checked"> Predeterminado
                                                    </label>
                                                </td>
                                                <td style="background: #FFFFFF;">
                                                    <input type="text" class="form-control" readonly="readonly" name="defaultpo" id="defaultpo" value="<?php
                                                    $select_nextpo = "SELECT Ponumber FROM tbltransaccion WHERE Ponumber = (SELECT MAX(Ponumber) FROM tbltransaccion)";
                                                    $result_nextpo = mysqli_query($link, $select_nextpo);
                                                    $row_newtpo = mysqli_fetch_array($result_nextpo, MYSQLI_BOTH);
                                                    $nextpo = $row_newtpo['Ponumber'] + 1;
                                                    echo $nextpo;
                                                    ?>"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background: #FFFFFF;">
                                                    <label for="poradiocustom" class="radio-inline">
                                                        <input type="radio" class="icheckbox iradio" name="poradio" id="poradiocustom" value="custompo"> Personalizado
                                                    </label>  
                                                </td>
                                                <td style="background: #FFFFFF;">
                                                    <input type="text" class="form-control" name="custompo" id="custompo"/> 
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-heading" style="text-align: left;">Fechas</div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="deliverydate" class="control-label">Fecha de Entrega:</label>
                                            <div class='input-group date'>
                                                <input type='text' class="form-control datepicker" id="deliverydate" name="deliverydate" value="<?php echo date('Y-m-d') ?>" tabindex="13" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="shippingdate" class="control-label">Fecha de Vuelo:</label>
                                            <div class='input-group date'>
                                                <input type='text' class="form-control datepicker" id="shippingdate" name="shippingdate" value="<?php
                                                $fecha = date('Y-m-d');
                                                $nuevafecha = strtotime('-3 day', strtotime($fecha));
                                                $nuevafecha = date('Y-m-d', $nuevafecha);
                                                echo $nuevafecha
                                                ?>" tabindex="14" required />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="orderdate" class="control-label">Fecha de Orden:</label>
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input type='text' class="form-control datepicker" name="orderdate" id="orderdate" value="<?php echo date('Y-m-d') ?>" tabindex="12" required />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="satdel" class="control-label">SatDel:</label>
                                    <select class="form-control" data-style="btn-primary" name="satdel" id="satdel">
                                        <option value="N">N</option>
                                        <option value="Y">Y</option>  
                                    </select>
                                </div>
                                <div class="form-group" >
                                    <label for="consolidado" class="control-label" >Consolidado:</label>
                                    <select class="form-control" name="consolidado" id="consolidado">
                                        <option value="N">N</option>
                                        <option value="Y">Y</option>  
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="step-6">
                        <!-- TABLA DE CLIENTES -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <!--BOTON DE EXPORTAR-->
                                <!--<div class="btn-group pull-right">
                                        <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> Exportar</button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" onClick ="$('#pdv_items').tableExport({type: 'csv', escape: 'false'});"><img src='img/icons/csv.png' width="24"/> CSV</a></li>
                                            <li><a href="#" onClick ="$('#pdv_items').tableExport({type: 'txt', escape: 'false'});"><img src='img/icons/txt.png' width="24"/> TXT</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" onClick ="$('#pdv_items').tableExport({type: 'excel', escape: 'false'});"><img src='img/icons/xls.png' width="24"/> XLS</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" onClick ="$('#pdv_items').tableExport({type: 'png', escape: 'false'});"><img src='img/icons/png.png' width="24"/> PNG</a></li>
                                            <li><a href="#" onClick ="$('#pdv_items').tableExport({type: 'pdf', escape: 'false'});"><img src='img/icons/pdf.png' width="24"/> PDF</a></li>
                                        </ul>
                                    </div>-->
                                <div class="col-md-3 pull-right" >
                                    <a href='php/gestionardestinos.php' target="_blank" class="btn btn-primary">
                                        Gestionar destino
                                    </a>
                                </div>
                                <div class="col-md-2 pull-right" >
                                    <button type="button" class="btn btn-primary" id="btn_nuevo_destino" data-toggle="modal" data-target="#modal_nuevo_destino" data-placement="rigth" title="Crear nuevo Destino">
                                        <span class="glyphicon glyphicon-plus-sign black"> </span> Añadir destino
                                    </button>
                                </div>
                                <div class="col-md-2 pull-left" >
                                    <button type="button" class="btn btn-primary" id="btn_nuevo_producto" data-toggle="modal" data-target="#modal_nuevo_producto" data-placement="rigth" title="Agregar nuevo producto">
                                        <span class="glyphicon glyphicon-plus-sign black"> </span> Añadir Producto 
                                    </button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div style="height: 400px;" class="table-responsive">
                                    <table id="pdv_items" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Código - Descripci&oacute;n</th>
                                                <th>Cantidad</th>
                                                <th>Precio</th>
                                                <th>Mensaje</th>
                                                <th>Editar</th>
                                                <th>Eliminar</th>
                                                <th>
                                                    <select class="form-control select" data-live-search="true" data-style="btn-primary" id="select_control">
                                                        <?php
                                                        $cliente_seleccionado = $_GET['cliente'];
                                                        $cliente = $cliente_seleccionado;

                                                        $select_cliente_destino = 'SELECT iddestino,destino FROM tbldestinos WHERE codcliente = "' . $cliente . '"';
                                                        $result_cliente_destino = mysqli_query($link, $select_cliente_destino);
                                                        echo "<option  value='-1'> CAMBIAR DESTINOS </option>";
                                                        while ($row_cloente_destino = mysqli_fetch_array($result_cliente_destino, MYSQLI_BOTH)) {
                                                            echo "<option class='option_trigger' value='" . $row_cloente_destino['iddestino'] . "'>" . $row_cloente_destino['destino'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!--AQUI SE AGREGAN DINAMICAMENTE LOS VALORES DEL MODAL NUEVO ITEM-->
                                        </tbody>
                                    </table>  
                                    <input type='hidden' class="form-control" name="submit_neworder" id="submit_neworder" value="" tabindex=""  />
                                </div>
                            </div>
                        </div>
                    </div>                                                                                                            
                </div>
            </form>
        </div>
    </div>                        
    <!-- FIN PASO POR PASO -->
</div>
<div style="z-index: 999;" class="modal animated fadeIn" id="modal_nuevo_cliente" tabindex="-1" role="dialog" aria-labelledby="smallModalHead" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Nuevo Cliente</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="newclient_form" action="scripts/pdv_controller.php" role="form">
                    <div class="row">     
                        <div class="col-md-6">
                            <label for="empresa" class="control-label">Nombre:</label>
                            <input type="text" class="form-control" id="empresa" name="empresa" value=""  tabindex="1" size="30"/>

                        </div>
                        <div class="col-md-6">
                            <label for="empresa2" class="control-label">Apellido:</label>
                            <input type="text" class="form-control" id="empresa2" name="empresa2" value=""  tabindex="1" size="30"/>

                        </div>
                    </div>
                    <div class="row">     
                        <div class="col-md-6">
                            <label for="direccion" class="control-label">Dirección:</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" value=""  tabindex="1" size="30"/>

                        </div>
                        <div class="col-md-6">
                            <label for="direccion2" class="control-label">Direccion2:</label>
                            <input type="text" class="form-control" id="direccion2" name="direccion2" value=""  tabindex="1" size="30"/>

                        </div>
                    </div>  
                    <div class="row">     
                        <div class="col-md-6">
                            <label for="ciudad" class="control-label">Ciudad:</label>
                            <input type="text" class="form-control" id="ciudad" name="ciudad" value=""  tabindex="1" size="30"/>

                        </div>
                        <div class="col-md-6">
                            <label for="estado" class="control-label">Estado:</label>
                            <input type="text" class="form-control" id="estado" name="estado" value=""  tabindex="1" size="30"/>

                        </div>
                    </div>  	            
                    <div class="row">     
                        <div class="col-md-6">
                            <label for="zip" class="control-label">Zip:</label>
                            <input type="text" class="form-control" id="zip" name="zip" value=""  tabindex="1" size="30"/>

                        </div>
                        <div class="col-md-6">
                            <label for="pais" class="control-label">Pais:</label>
                            <select type="text" class="form-control" name="pais" id="pais" >
                                <?php
                                //Consulto la bd para obtener solo los id de item existentes
                                $sql = "SELECT * FROM tblpaises_destino";
                                $query = mysqli_query($link, $sql);
                                //Recorrer los iteme para mostrar
                                echo '<option value=""></option>';
                                while ($row1 = mysqli_fetch_array($query, MYSQLI_BOTH)) {
                                    echo '<option value="' . $row1["codpais"] . '">' . $row1["pais_dest"] . '</option>';
                                }
                                ?>    
                            </select>

                        </div>
                    </div>	
                    <div class="row">     
                        <div class="col-md-6">
                            <label for="telefono" class="control-label">Teléfono:</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value=""  tabindex="1" size="30"/>

                        </div>
                        <div class="col-md-6">
                            <label for="vendedor" class="control-label">Vendedor Asignado:</label>
                            <select type="text" class="form-control" name="vendedor" id="vendedor" >
                                <?php
                                //Consulto la bd para obtener solo los id de item existentes
                                $sql = "SELECT id_usuario,cpnombre FROM tblusuario";
                                $query = mysqli_query($link, $sql);
                                //Recorrer los iteme para mostrar
                                echo '<option value=""></option>';
                                while ($row1 = mysqli_fetch_array($query, MYSQLI_BOTH)) {
                                    echo '<option value="' . $row1["id_usuario"] . '">' . $row1["cpnombre"] . '</option>';
                                }
                                ?>    
                            </select>

                        </div> 
                    </div>
                    <div class="row">     
                        <div class="col-md-12">
                            <label for="mail" class="control-label">E-Mail:</label>
                            <input type="email" class="form-control" id="mail" name="mail" value="" />

                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" id="insertar_cliente" name="submit_nuevocliente" class="btn btn-primary">Insertar</button>
            </div>
        </div>
        </form>
    </div>
</div>
<div style="z-index: 999;" class="modal animated fadeIn" id="modal_nuevo_producto" tabindex="-1" role="dialog" aria-labelledby="smallModalHead" aria-hidden="true">
    <div class="modal-dialog modal-primary" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Nuevo Producto</h4>
            </div>
            <form id="pdv_items_insert" method="post">
                <div class="modal-body">
                    <div class="row">     
                        <!-- AUTOCOMPLETE -->  
                        <div class="col-md-8">
                            <label class="control-label">Producto:</label>
                            <input id="prod_ac" name='prod_ac' class="form-control"/>
                        </div>                      
                        <!-- FIN AUTOCOMPLETE -->
                    </div>
                    <div class="row">     
                        <div class="col-md-8">
                            <label for="cantidad" class="control-label">Cantidad:</label>
                            <input type="text" id="cantidad" class="form-control" name="cantidad" value="" size="10"/>
                        </div>
                    </div>
                    <div class="row">     
                        <div class="col-md-8">
                            <label for="precioUnitario" class="control-label">Precio Unitario:</label>
                            <input type="text" id="precioUnitario" class="form-control" name="precioUnitario" value="" size="10"/>
                        </div>
                    </div>   
                    <div class="row">     
                        <div class="col-md-8">
                            <label for="mensaje" class="control-label">Mensaje:</label>
                            <input type="text" id="mensaje" name="mensaje" tabindex="19" class="form-control" size="70" />
                        </div>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button id="insertar_item" type="button" class="btn btn-primary" >Insertar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div style="z-index: 999;" class="modal animated fadeIn" id="modal_editar_item" tabindex="-1" role="dialog" aria-labelledby="smallModalHead" aria-hidden="true">
    <div class="modal-dialog modal-primary" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Editar fila</h4>
            </div>
            <form id="pdv_editaritem_form" method="post">
                <div class="modal-body">
                    <div class="row">     
                        <!-- AUTOCOMPLETE -->  
                        <div class="col-md-8">
                            <label class="control-label">Producto:</label>
                            <input id="prod_ac2" name='prod_ac' class="form-control"/>
                        </div>                      
                        <!-- FIN AUTOCOMPLETE -->
                    </div>
                    <div class="row">     
                        <div class="col-md-8">
                            <label for="cantidad" class="control-label">Cantidad:</label>
                            <input type="text" id="edit_cantidad" class="form-control" name="cantidad" value="" size="10"/>
                        </div>
                    </div>
                    <div class="row">     
                        <div class="col-md-8">
                            <label for="precioUnitario" class="control-label">Precio Unitario:</label>
                            <input type="text" id="edit_precioUnitario" class="form-control" name="precioUnitario" value="" size="10"/>
                        </div>
                    </div>   
                    <div class="row">     
                        <div class="col-md-8">
                            <label for="mensaje" class="control-label">Mensaje:</label>
                            <input type="text" id="edit_mensaje" name="mensaje" tabindex="19" class="form-control" size="70" />
                        </div>
                    </div>   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button id="pdv_editaritem_btn" type="button" class="btn btn-primary">Editar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div style="z-index: 999;" class="modal animated fadeIn" id="modal_nuevo_destino" tabindex="-1" role="dialog" aria-labelledby="smallModalHead" aria-hidden="true">
    <div class="modal-dialog modal-primary" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Nuevo Destino</h4>
            </div>
            <div class="modal-body">
                <form method="post" id="newdest_form"  role="form">
                    <div class="row">     
                        <div class="col-md-8">
                            <label for="nombredestino" class="control-label">Nombre del Destino:</label>
                            <input type="text" class="form-control" id="nombredestino" name="nombredestino" value=""  tabindex="1" size="30"/>

                        </div>
                    </div>
                    <div class="row">     
                        <div class="col-md-6">
                            <label for="shipto" class="control-label">Nombre y Apellido del destinatario:</label>
                            <input type="text" class="form-control" id="shipto" name="shipto" value=""  tabindex="1" size="30"/>
                        </div>

                        <div class="col-md-6">
                            <label for="shipto2" class="control-label">Empresa del destinatario:</label>
                            <input type="text" class="form-control" id="shipto2" name="shipto2" value="" tabindex="2" size="30"/>
                        </div>
                    </div>   
                    <div class="row">     
                        <div class="col-md-6">
                            <label for="direccion" class="control-label">Dirección:</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" value="" tabindex="3" size="30" />
                        </div>

                        <div class="col-md-6">
                            <label for="direccion2" class="control-label">Dirección2:</label>
                            <input type="text" class="form-control" id="direccion2" name="direccion2" value="" tabindex="3" size="30" />
                        </div>
                    </div>
                    <div class="row">     
                        <div class="col-md-4">
                            <label for="ciudad" class="control-label">Ciudad:</label>
                            <input type="text" class="form-control" id="ciudad" name="ciudad" value="" tabindex="4"/>
                        </div>
                        <div class="col-md-4">
                            <label for="estado" class="control-label">Estado:</label>
                            <input type="text" class="form-control" id="estado" name="estado" value="" />
                        </div>
                        <div class="col-md-4">
                            <label for="pais" class="control-label">Pais:</label>
                            <select type="text" class="form-control" name="pais" id="pais" tabindex="20">
                                <?php
                                //Consulto la bd para obtener solo los id de item existentes
                                $sql = "SELECT * FROM tblpaises_destino";
                                $query = mysqli_query($link, $sql);
                                //Recorrer los iteme para mostrar
                                echo '<option value=""></option>';
                                while ($row1 = mysqli_fetch_array($query)) {
                                    echo '<option value="' . $row1["codpais"] . '">' . $row1["pais_dest"] . '</option>';
                                }
                                ?>    
                            </select>
                        </div>
                    </div>

                    <div class="row">     
                        <div class="col-md-6">
                            <label for="zip" class="control-label">Cod. Postal:</label>
                            <input type="text" class="form-control" id="zip" name="zip" value="" tabindex="6"/>
                        </div>
                        <div class="col-md-6">
                            <label for="estado" class="control-label">Teléfono:</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="" tabindex="7"/>
                            <input type="hidden" class="form-control" id="codigo_cliente" name="codigo_cliente" value="<?php echo $_GET['cliente']; ?>" tabindex="7"/>
                        </div>
                    </div>

                    <div class="row">     
                        <div class="col-md-12">
                            <label for="mail" class="control-label">Email:</label>
                            <input type="text" class="form-control" id="mail" name="mail" value=""tabindex="10" size="30" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <input type="button" name="submit_nuevodest" class="btn btn-primary" onclick="insertar_nuevodestino()" value="Insertar">
            </div>
        </div>
    </div>
</div>
<!--LA CAJA DE NOTIFICACION EXITOSA-->
<div class="message-box message-box-success animated fadeIn" id="message-box-success">
    <div class="mb-container">
        <div class="mb-middle">
            <div class="mb-title"><span class="fa fa-check"></span> Ingreso exitoso</div>
            <div class="mb-content">
                <p>El nuevo destino fue ingresado con &eacute;xito</p>
            </div>
            <div class="mb-footer">
                <button class="btn btn-default btn-lg pull-right mb-control-close">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!--LA CAJA DE NOTIFICACION ERROR-->
<div class="message-box message-box-danger animated fadeIn" id="message-box-danger">
    <div class="mb-container">
        <div class="mb-middle">
            <div class="mb-title"><span class="fa fa-times"></span> Error</div>
            <div class="mb-content">
                <p>El destino que intentas ingresar ya se encuentra en el sistema</p>
            </div>
            <div class="mb-footer">
                <button class="btn btn-default btn-lg pull-right mb-control-close">Cerrar</button>
            </div>
        </div>
    </div>
</div>