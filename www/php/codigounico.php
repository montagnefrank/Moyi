<?php
//////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL); 
//ini_set('display_errors', 1);
//generar consecitivo para las etiquetas de las fincas
function generarCodigoUnico() {

    //Conexion a la BD	
    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

    if (!$link) {
        die('Could not connect: ' . mysql_error());
    } else {

        //Seleccionamos el ultimo valor consecutivo
        $query = "select * from tblcodigo Order By id_codigo DESC";
        $sql = mysqli_query($link,$query) or die(mysql_error($link));

        $row = mysqli_fetch_array($sql);
        $ultimovalor = $row[1];
        $ultimovalor += 1;
        return $ultimovalor;
    }
}

?>