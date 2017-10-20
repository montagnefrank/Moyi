<div class="col-md-3">
    <div class="widget widget-primary widget-item-icon">
        <div class="widget-item-left">
            <span class="fa fa-plus-circle"></span>
        </div>
        <div class="widget-data">
            <div class="widget-int num-count"><?php
                if ($rol == 3) {
                    $sql = "SELECT count(*) FROM tbldetalle_orden inner join tblproductos on tbldetalle_orden.cpitem = tblproductos.id_item WHERE status = 'New' and finca = '$finca'";
                    $query = mysqli_query($link, $sql)or die("Error Searching....");
                    $row = mysqli_fetch_array($query);
                    if ($row[0] <> 0) {
                        echo "<strong>" . $row[0] . "</strong>";
                    } else {
                        echo "<strong>0</strong>";
                    }
                } else {
                    $sql = "select count(*)
                                    FROM
                                    tbldetalle_orden WHERE status = 'New'";
                    $query = mysqli_query($link, $sql)or die("Error Searching....");
                    $row = mysqli_fetch_array($query);
                    if ($row[0] <> 0) {
                        echo "<strong>" . $row[0] . "</strong>";
                    } else {
                        echo "<strong>0</strong>";
                    }
                }
                ?>
            </div>
            <div class="widget-title">Ordenes nuevas</div>
            <!--<div class="widget-subtitle">Texto complementario</div>-->
        </div>
        <div class="widget-controls">                                
            <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Ocultar Widget"><span class="fa fa-times"></span></a>
        </div>                            
    </div>
</div>
