<?php

session_start();
$rol = $_SESSION["rol"];
include ("seguridad.php");
//include('php/js/script.js');
# definimos la carpeta destino	
$carpetaDestino = "./Archivos subidos/";

# si hay algun archivo que subir
if ($_FILES["archivo"]["name"][0]) {
    # recorremos todos los arhivos que se han subido
    for ($i = 0; $i < count($_FILES["archivo"]["name"]); $i++) {
        #divide el nombre del fichero con un .    
        $explode_name = explode('.', $_FILES["archivo"]["name"][$i]);
        # si es un formato de excel
        if ($explode_name[1] == 'csv') {
            # si exsite la carpeta o se ha creado
            if (file_exists($carpetaDestino) || @mkdir($carpetaDestino)) {
                $origen = $_FILES["archivo"]["tmp_name"][$i];
                $destino = $carpetaDestino . $_FILES["archivo"]["name"][$i];


                # movemos el archivo
                if (@move_uploaded_file($origen, $destino)) {
                    echo "<br>" . $_FILES["archivo"]["name"][$i] . " movido correctamente";
                    //echo $_FILES['archivo']['name'][$i];
                    //unlink($_FILES['archivo']['name'][$i]);
                    //header('Location: index.php');
                } else {
                    echo "<br>No se ha podido mover el archivo: " . $_FILES["archivo"]["name"][$i];
                }
            } else {
                echo "<br>No se ha podido crear la carpeta: up/" . $user;
            }
        } else {
            echo "<br>" . $_FILES["archivo"]["name"][$i] . " - Formato no admitido";
        }
    }
    if ($rol == 3) {
        header('Location:cargarTracking_fincas.php');
    } else {
        header('Location:cargarTracking2.php');
    }
} else {
    echo "<br>No hay ningun arhivo para subir";
}
echo "<a href='javascript:history.back(1)'>Volver Atr�s</a>";
?>
