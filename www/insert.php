<?php
///////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
error_reporting(E_ALL);
ini_set('display_errors', 1);

require ("scripts/conn.php");
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

$q = "SELECT * FROM tblproductos pro JOIN tblboxtype box ON box.id_Box = pro.boxtype ORDER BY pro.item";
$res = $link->query($q);
if(!$res) die($conn->error);
while ($row = $res->fetch_array(MYSQLI_ASSOC)){
    $update .= "UPDATE tblproductos SET length = '".$row["longitud_Box"]."', width = '".$row["ancho_Box"]."', heigth = '".$row["alto_Box"]."' WHERE  item = '".$row["item"]."'; ";
    
}
echo $update ."<br /><br />";
echo "LISTO";
        
?>