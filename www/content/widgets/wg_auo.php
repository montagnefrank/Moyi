<div class="col-md-3">
    <div class="widget widget-primary widget-item-icon">
        <div class="widget-item-left">
            <span class="fa fa-eye"></span>
        </div>
        <div class="widget-data">
            <div class="widget-int num-count"><?php
                $sql = 'SELECT count(Ponumber),Ponumber, cpitem FROM `tbldetalle_orden` 
                inner join tblorden on id_detalleorden =id_orden where status="New" OR status="Ready to ship"
                group by Ponumber,cpitem HAVING count(Ponumber)>=3
                order by Ponumber desc';
                $query = mysqli_query($link, $sql)or die("Error Searching....");
                $row = mysqli_num_rows($query);
                if ($row > 0)
                    echo "<a style=\"color: #FFFFFF;\" href= './php/ordenes_ponumber_revisar.php' data-toggle='tooltip' data-placement= 'bottom' title='Ver órdenes por arreglar'><strong>" . $row . "</strong></a>";
                else
                    echo '<strong>' . $row . '</strong>'
                    ?>
            </div>
            <div class="widget-title">Auditar Ordenes</div>
            <!--<div class="widget-subtitle">Texto complementario</div>-->
        </div>
        <div class="widget-controls">                                
            <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Ocultar Widget"><span class="fa fa-times"></span></a>
        </div>                            
    </div>
</div>