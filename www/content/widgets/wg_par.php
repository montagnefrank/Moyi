<div class="col-md-3">
    <?php
    $sql = "SELECT count(id_orden_detalle) FROM tblerror";
    $query = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($query);
    if ($row[0] <> 0) {
        echo '<div class="widget widget-danger widget-item-icon">';
    } else {
        echo '<div class="widget widget-primary widget-item-icon">';
    }
    ?>
        <div class="widget-item-left">
            <span class="fa fa-wrench"></span>
        </div>
        <div class="widget-data">
            <div class="widget-int num-count"><?php
                if ($row[0] <> 0) {
                    echo '<a style="color: #FFFFFF;" href= "./php/ordenes_error.php" data-toggle="tooltip" data-placement="bottom" title="Ver órdenes por arreglar"><strong>' . $row[0] . '</strong></a>';
                } else {
                    echo '<strong>0</strong>';
                }
                ?>
            </div>
            <div class="widget-title">Por arreglar</div>
            <div class="widget-subtitle">EDI connect</div>
        </div>
        <div class="widget-controls">                                
            <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Ocultar Widget"><span class="fa fa-times"></span></a>
        </div>                            
    </div>
</div>