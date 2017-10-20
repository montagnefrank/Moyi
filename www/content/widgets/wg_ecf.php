<div class="col-md-3">
    <div class="widget widget-primary widget-item-icon">
        <div class="widget-item-left">
            <span class="fa fa-industry"></span>
        </div>
        <div class="widget-data">
            <div class="widget-int num-count"><?php
                //esta consulta selecciona las cajas que estan en cuarto frio y que no has sido rechazadas dentro del cuarto frio(rechazadas=2)
                $sql = "SELECT COUNT(*) FROM tblcoldroom where fecha<='" . date('Y-m-d') . "' AND entrada = 'Si' AND salida ='No' and rechazada='0'";
                $query = mysqli_query($link, $sql);
                $row = mysqli_fetch_array($query);
                if ($row[0] <> 0) {
                    echo "<strong>" . $row[0] . "</strong>";
                } else {
                    echo "<strong>0</strong>";
                }
                ?>
            </div>
            <div class="widget-title">Inventario</div>
            <div class="widget-subtitle">En cuarto fr&iacute;o</div>
        </div>
        <div class="widget-controls">                                
            <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Ocultar Widget"><span class="fa fa-times"></span></a>
        </div>                            
    </div>
</div>