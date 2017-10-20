<div class="col-md-3">
    <div class="widget widget-primary widget-item-icon" title='Totalidad de cajas solicitadas a las fincas'>
        <div class="widget-item-left">
            <span class="glyphicon glyphicon-shopping-cart"></span>
        </div>
        <div class="widget-data">
            <div class="widget-int num-count"><?php
                $sql = "SELECT DISTINCT SUM(tbletiquetasxfinca.solicitado) ordenadas FROM tbletiquetasxfinca where archivada = 'No' AND estado!='5'";
                $query = mysqli_query($link, $sql)or die("Error Searching....");
                $row = mysqli_fetch_array($query);
                if ($row[0] <> 0) {
                    echo "<strong>" . $row[0] . "</strong>";
                } else {
                    echo "<strong>0</strong>";
                }
                ?>
            </div>
            <div class="widget-title">Compras</div>
            <div class="widget-subtitle">Hacer pedido</div>
        </div>
        <div class="widget-controls">                                
            <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Ocultar Widget"><span class="fa fa-times"></span></a>
        </div>                            
    </div>
</div>