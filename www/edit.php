<?php

error_reporting(E_ALL);
ini_set('display_errors', 'on');
ini_set('memory_limit', '2048M');

require ("scripts/sqlconn.php");
require ("scripts/conn.php");
require ("scripts/islogged.php");

$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    $table .= "Error: Unable to connect to MySQL." . PHP_EOL;
    $table .= "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    $table .= "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
$select_vo_reporte = "
                            SELECT estado_orden,status,reenvio,tracking,nombre_compania,eBing,order_date,shipto1,shipto2,direccion,direccion2,cpcuidad_shipto,cpestado_shipto,cpzip_shipto,cptelefono_shipto,soldto1 ,soldto2 ,cpstphone_soldto ,Ponumber,Custnumber,ShipDT_traking,delivery_traking,satdel,cpcantidad,cpitem,prod_descripcion,length,width,wheigthKg,dclvalue,cpmensaje,cpservicio,cptipo_pack,gen_desc,cppais_envio,cpmoneda,cporigen,cpUOM,empresa,director,direccion_director,cuidad_director,estado_director,pais_director,tpzip_director,tpphone_director,tpacct_director,farm
                            FROM tblorden
                            INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                            INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                            INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                            INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                            INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem";
$result_vo_reporte = mysqli_query($link, $select_vo_reporte);
$i = 0;
$table = "";
while ($row_vo_reporte = mysqli_fetch_array($result_vo_reporte, MYSQLI_BOTH)) {
    $table .= "<tr>";
    $table .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['estado_orden'] . " </td>";
    $table .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['status'] . " </td>";
    $table .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['reenvio'] . " </td>";
    $table .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['tracking'] . " </td>";
    $table .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['order_date'] . " </td>";
    $table .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['shipto1'] . " </td>";
    $table .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['direccion'] . " </td>";
    $table .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['cpcuidad_shipto'] . " </td>";
    $table .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['Ponumber'] . " </td>";
    $table .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['Custnumber'] . " </td>";
    $table .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['ShipDT_traking'] . " </td>";
    $table .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['delivery_traking'] . " </td>";
    $table .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['cpitem'] . " </td>";
    $table .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['cppais_envio'] . " </td>";
    $table .= "<td style='width:1%;white-space:nowrap;'>" . $row_vo_reporte['farm'] . " </td>";
    $table .= "</tr>";
    $i++;
}
$table .= "Total de vueltas " . $i . " <br />";
print_r($table);
?>