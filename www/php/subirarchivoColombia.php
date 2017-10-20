<?php
session_start();
$rol      =  $_SESSION["rol"];
include ("seguridad.php");
		
$carpetaDestino="./Archivos Colombia/";

# si hay algun archivo que subir
if($_FILES["archivo"]["name"])
{
            
        #divide el nombre del fichero con un .    
        $explode_name = explode('.',$_FILES["archivo"]["name"]);
        # si es un formato de excel
        if($explode_name[1] == 'csv') 
        {
            # si exsite la carpeta o se ha creado
            if(file_exists($carpetaDestino) || @mkdir($carpetaDestino))
            {
                $origen=$_FILES["archivo"]["tmp_name"];
                $destino=$carpetaDestino.$_FILES["archivo"]["name"];


                # movemos el archivo
                if(@move_uploaded_file($origen, $destino))
                {
                    echo "<br>".$_FILES["archivo"]["name"]." movido correctamente";
                                            //echo $_FILES['archivo']['name'][$i];
                                            //unlink($_FILES['archivo']['name'][$i]);
                                            //header('Location: index.php');
                }else{
                    echo "<br>No se ha podido mover el archivo: ".$_FILES["archivo"]["name"];
                }
            }else{
                echo "<br>No se ha podido crear la carpeta: up/".$user;
            }
        }else{
            echo "<br>".$_FILES["archivo"]["name"]." - Formato no admitido";
        }
    
    header('Location:cargarTrackingColombia.php');
}else{
    echo "<br>No hay ningun arhivo para subir";
}
    echo "<a href='javascript:history.back(1)'>Volver Atrás</a>";
?>
