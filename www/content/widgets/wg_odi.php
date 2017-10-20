<div class="col-md-3">
    <div class="widget widget-primary widget-item-icon">
        <div class="widget-item-left">
            <span class="fa fa-paperclip"></span>
        </div>
        <div class="widget-data">
            <div class="widget-int num-count"><?php
                $today = date("Y-m-d");

                if ($rol == 3) {
                    $sql = "select count(*)
                FROM
                tblorden
                INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                WHERE `order_date`='" . $today . "'";

                    $query = mysqli_query($link, $sql);
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
                    tblorden
                    INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                    WHERE `order_date`='" . $today . "'";

                    //$sql = "select count(*) FROM tbldetalle_orden WHERE status = 'New'";
                    $query = mysqli_query($link, $sql);
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
            <div class="widget-title">&Oacute;rdenes diarias</div>
            <!--<div class="widget-subtitle">Texto complementario</div>-->
        </div>
        <div class="widget-controls">                                
            <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Ocultar Widget"><span class="fa fa-times"></span></a>
        </div>                            
    </div>
</div>