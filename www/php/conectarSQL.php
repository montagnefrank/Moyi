<?php
function conectar($server, $user, $passwd, $bd){
$link = mysql_connect($server,$user,$passwd);
mysql_select_db($bd,$link);
mysql_query("SET NAMES 'utf8'"); //change added by me resuelve el problema del cotejamiento
if (!$link) {
   die('No se pudo establecer una conexión: ' . mysql_error());
   //echo 'Connected not successfully';
}
	//echo 'Connected successfully';
	return $link;
}
//header('Location: cargarExcel.php');
?>