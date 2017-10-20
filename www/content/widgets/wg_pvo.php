<div class="col-md-3">
    <?php
    if ($rol == 3) {
        $sql = "SELECT SUM(cpcantidad) FROM tbldetalle_orden inner join tblproductos on tbldetalle_orden.cpitem = tblproductos.id_item where ShipDT_traking <='" . date('Y-m-d') . "' AND tracking = '' AND estado_orden = 'Active' and finca = '$finca'";
        $query = mysqli_query($link,$sql);
        $row = mysqli_fetch_array($query);
        if ($row[0] <> 0) {
            echo '<div class="widget widget-danger widget-item-icon">';
        } else {
            echo '<div class="widget widget-primary widget-item-icon">';
        }
    } else {
        $sql = "SELECT SUM(cpcantidad) FROM tbldetalle_orden where ShipDT_traking <='" . date('Y-m-d') . "' AND tracking = '' AND estado_orden = 'Active'";
        $query = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($query);
        if ($row[0] <> 0) {
            echo '<div class="widget widget-danger widget-item-icon">';
        } else {
            echo '<div class="widget widget-primary widget-item-icon">';
        }
    }
    ?>
    <div class="widget-item-left">
        <span class="fa fa-rocket"></span>
    </div>
    <div class="widget-data">
        <div class="widget-int num-count"><?php
            if ($rol == 3) {
                if ($row[0] <> 0) {
                    echo '<a style="color: #FFFFFF;" href="./php/ordenesxvolar.php" data-toggle="tooltip" data-placement= "bottom" title="Ver órdenes por volar"><strong>' . $row[0] . '</strong></a>';
                } else {
                    echo '<strong>0</strong>';
                }
            } else {
                if ($row[0] <> 0) {
                    echo '<a style="color: #FFFFFF;" href="./php/ordenesxvolar.php" data-toggle="tooltip" data-placement= "bottom" title="Ver órdenes por volar"><strong>' . $row[0] . '</strong></a>';
                } else {
                    echo '<strong>0</strong>';
                }
            }
            ?>
        </div>
        <div class="widget-title">Por volar</div>
        <!--<div class="widget-subtitle">Texto complementario</div>-->
    </div>
    <div class="widget-controls">                                
        <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Ocultar Widget"><span class="fa fa-times"></span></a>
    </div>                            
</div>
</div>