<div class="col-md-3">
    <div class="widget widget-primary widget-item-icon" title='Cantidad de ordenes ingresadas a trav&eacute;s del AS2 para el mes actual'>
        <div class="widget-item-left">
            <span class="fa fa-briefcase"></span>
        </div>
        <div class="widget-data">
            <div class="widget-int num-count"><?php
                $enddate = date('m');
                $enddate--;
                $sql = "SELECT * FROM tblcostco where status = '5' OR status = '2'";
                $query = mysqli_query($link, $sql)or die("Error Searching....");
                $i = '0';
                $leftcap = date('Y-m-d', strtotime("first day of previous month"));
                $rightcap = date('Y-m-d', strtotime("last day of next month"));
                while ($row = mysqli_fetch_array($query)) {
                    //////////////////////////////////////////////////////////////////////////////////////////////////////////REMOVEMOS LA ULTIMA COMA PARA NO GENERAR ERROR DE SINTAXIS
                    $fecha = substr(trim($row['insert_date']), 0, -11);
                    if($leftcap < $fecha && $rightcap > $fecha){
                        $i++;
                    }
                }
                if ($i <> 0) {
                    echo "<strong>" . $i . "</strong>";
                } else {
                    echo "<strong>0</strong>";
                }
                ?>
            </div>
            <div class="widget-title">Compromiso de venta</div>
            <div class="widget-subtitle">EDI Connect</div>
        </div>
        <div class="widget-controls">                                
            <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Ocultar Widget"><span class="fa fa-times"></span></a>
        </div>                            
    </div>
</div>