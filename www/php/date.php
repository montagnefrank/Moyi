<?php
include ("seguridad.php");
function to_date($oldDate){
// convertir de 30/12/14 a 2014-12-30
$time = strtotime($oldDate);
$newformat = date('Y-m-d',$time);
return $newformat;
}

?>
