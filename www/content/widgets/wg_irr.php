<div class="col-md-3">
    <?php
    $iii = 0;
    $select_alerted_irr = "SELECT * FROM tblcostco WHERE status NOT IN (5,2)";
    $result_alerted_irr = mysqli_query($link, $select_alerted_irr);
    while ($row_alerted_irr = mysqli_fetch_array($result_alerted_irr)){
        $iii++;
    }
    if ($iii <> 0 ) {
        echo '<div class="widget widget-danger widget-item-icon" >';
    } else {
        echo '<div class="widget widget-primary widget-item-icon">';
    }
    ?>
    <div class="widget-item-left">
        <i class="fa fa-shield" aria-hidden="true"></i>
    </div>
    <div class="widget-data">
        <div class="widget-int num-count"><?php
            if ($iii <> 0) {
                echo '<strong>' . $iii . '</strong>';
            } else {
                echo '<strong>0</strong>';
            }
            ?>
        </div>
        <div class="widget-title">
            Ordenes Irregulares
        </div>
        <div class="widget-subtitle">EDI connect</div>
    </div>
    <div class="widget-controls">                                
        <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Ocultar Widget"><span class="fa fa-times"></span></a>
    </div>                            
</div>
</div>