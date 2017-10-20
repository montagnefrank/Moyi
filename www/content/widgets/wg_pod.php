<div class="col-md-3">
    <div class="widget widget-primary widget-item-icon">
        <div class="widget-item-left">
            <span class="fa fa-plane"></span>
        </div>
        <div class="widget-data">
            <div style="text-align: left;" class="widget-int num-count"><?php
                $sql = 'select count(*) FROM tbldetalle_orden WHERE estado_orden="Active" AND (status = "New" OR status="Ready to ship")';
                $query = mysqli_query($link, $sql)or die("Error Searching....");
                $row = mysqli_fetch_array($query);
                if ($row[0] <> 0) {
                    echo "<strong>" . $row[0] . "</strong>";
                } else {
                    echo "<strong>0</strong>";
                }
                ?>
            </div>
            <div class="widget-title">Por Despachar</div>
            <!--<div class="widget-subtitle">Texto complementario</div>-->
        </div>
        <div class="widget-controls">                                
            <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Ocultar Widget"><span class="fa fa-times"></span></a>
        </div>                            
    </div>
</div>