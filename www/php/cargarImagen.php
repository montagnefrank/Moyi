<?php
include ("seguridad.php");
session_start();
$user     =  $_SESSION["login"];

	# definimos la carpeta destino	
    $carpetaDestino="../images/";
    
    if($_GET['id'] == 1){ //Si es para actualizar
    
        # si hay algun archivo que subir
        if($_FILES["archivo"]["name"])
        {  
            #Borramos el logo anterior    
            unlink($carpetaDestino."inicio.jpg");
        
            # recorremos todos los arhivos que se han subido
//            for($i=0;$i<count($_FILES["archivo"]["name"]);$i++)
//            {        
                #divide el nombre del fichero con un .    
    	        $explode_name = explode('.',$_FILES["archivo"]["name"]);
                # si es un formato de excel
                if($explode_name[1] == 'jpg') 
                {
                    # si exsite la carpeta o se ha creado
                    if(file_exists($carpetaDestino) || @mkdir($carpetaDestino))
                    {
                        $origen=$_FILES["archivo"]["tmp_name"];
                        $destino=$carpetaDestino.$_FILES["archivo"]["name"];
                       
                        # movemos el archivo
                        if(@move_uploaded_file($origen, $destino))
                        {
                            rename ($destino,$carpetaDestino."inicio.jpg");
    			    header('Location:apariencia.php?error=5'); //Correcto
                        }else{
                            echo "<br>No se ha podido mover el archivo: ".$_FILES["archivo"]["name"];
                            header('Location:apariencia.php?error=6'); //No se pudo subir el archivo
                        }
                    }else{
                        echo "<br>No se ha podido crear la carpeta: up/".$user;
                        header('Location:apariencia.php?error=7'); //No se pudo crear la carpeta
                    }
                }else{
                    echo "<br>".$_FILES["archivo"]["name"]." - Formato no admitido";
                    header('Location:apariencia.php?error=8'); //FORMATO NO ADMITIDO
                }
           // }
    			//header('Location:apariencia.php');
        }else{
            echo "<br>No hay ningun arhivo para subir";
            header('Location:apariencia.php?error=9'); //No hay archivo para subir
        }
    }else{ //Para eliminar el logo
        unlink($carpetaDestino."inicio.jpg");
        header('Location:apariencia.php?error=5'); //Correcto
    }       
    
    ?>

