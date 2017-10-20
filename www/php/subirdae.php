<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL); 
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");

session_start();
$user = $_SESSION["login"];
$pass = $_SESSION["pass"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

# definimos la carpeta destino
$finca = $_GET['finca'];
$dae = $_POST['dae'];
$pais = $_POST['pais'];
$finicio = $_POST['finicio'];
$ffin = $_POST['ffin'];

$carpetaDestino = "Archivos DAE/";

# si hay algun archivo que subir
if ($_FILES["archivo"]["name"][0]) {
    # recorremos todos los arhivos que se han subido
    for ($i = 0; $i < count($_FILES["archivo"]["name"]); $i++) {
        #divide el nombre del fichero con un .    
        $explode_name = explode('.', $_FILES["archivo"]["name"][$i]);
        # si es formato pdf
        if ($explode_name[1] == "pdf") {
            # si existe la carpeta o se ha creado
            if (file_exists($carpetaDestino) || @mkdir($carpetaDestino)) {
                $origen = $_FILES["archivo"]["tmp_name"][$i];
                $destino = $carpetaDestino . $_FILES["archivo"]["name"][$i];

                # movemos el archivo
                if (@move_uploaded_file($origen, $destino)) {
                    echo "<br>" . $_FILES["archivo"]["name"][$i] . " movido correctamente";

                    //renombrarlo
                    $nombreanterior = $carpetaDestino . $_FILES["archivo"]["name"][$i];
                    $nombrenuevo = $carpetaDestino . "DAE_" . $pais . "_" . $finca . ".pdf";
                    rename($nombreanterior, $nombrenuevo);

                    //verificar los datos introducidos
                    if (!$dae) {
                        header('Location:dae.php?id=2');
                    } else {

                        //Identificar que finca es para mostrar sus pedido	
                        $sql = "Select cpnombre from tblusuario where cpuser='" . $finca . "'";

                        $query = mysqli_query($link, $sql)or die("Error identificando nombre de finca");
                        $row = mysqli_fetch_array($query);
                        $finca1 = $row['cpnombre'];

                        //Obtener el mes de la fecha que estoy pasando
                        $mes = substr($ffin, 5, 2);

                        //VErificar que esa finca ya tenga dae asignado con ese pais y en ese mes
                        $sentencia = "SELECT * FROM tbldae WHERE nombre_finca = '" . $finca1 . "' AND pais_dae='" . $pais . "' AND ffin like '%-" . $mes . "-%' AND url != 'eliminado' ";
                        $consulta = mysqli_query($link, $sentencia) or die("Error consultando el DAE de la finca");
                        $cant = mysqli_num_rows($consulta);
                        //exit();

                        if ($cant == 1) {
                            /*
                              //Actualizar la tabla de los datos de la finca
                              $sql   = "Update tbldae set dae = '".$dae."', finicio = '".$finicio."', ffin = '".$ffin."', url='".$nombrenuevo."' WHERE nombre_finca='".$finca1."' AND pais_dae = '".$pais."'";
                              $query = mysqli_query($link, $sql)or die ("Error actualizando el DAE");
                             */
                            header('Location:dae.php?id=3');
                        } else {

                            //Elimiar automaticamnete el dae vencido 
                            //Le resto 1 al mes actual
                            $mes -= 1;
                            $sentencia = "UPDATE tbldae set url = 'eliminado' WHERE nombre_finca = '" . $finca1 . "' AND pais_dae='" . $pais . "' AND url != 'eliminado' ";
                            //exit();
                            $consulta = mysqli_query($link, $sentencia) or die("Error consultando el DAE de la finca");

                            //Insetar el dae de ese pais para esa finca 								
                            $sql = "INSERT INTO tbldae (`nombre_finca`,`dae`,`finicio`,`ffin`,`url`,`pais_dae`) VALUES ('" . $finca1 . "','" . $dae . "','" . $finicio . "','" . $ffin . "','" . $nombrenuevo . "','" . $pais . "')";
                            $query = mysqli_query($link, $sql)or die("Error insertando el DAE");

                            echo "dae cargado correctamente";
                            //header('Location:dae.php?id=1');
                        }
                    }
                } else {
                    echo "<br>No se ha podido mover el archivo: " . $_FILES["archivo"]["name"][$i];
                }
            } else {
                echo "<br>No se ha podido crear la carpeta: " . $carpetaDestino;
            }
        } else {
            echo "<br>" . $_FILES["archivo"]["name"][$i] . " - Formato no admitido";
        }
    }
} else {
    echo "<br>No hay ningun arhivo para subir";
}
?>
