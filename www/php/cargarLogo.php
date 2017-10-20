<?php
include ("seguridad.php");
session_start();
$user     =  $_SESSION["login"];

	# definimos la carpeta destino	
    $carpetaDestino="../images/";
    
    if($_GET['id'] == 1){ //Si es para actualizar
    
        # si hay algun archivo que subir
        if($_FILES["archivo_logo"]["name"])
        {  
            #Borramos el logo anterior    
            unlink($carpetaDestino."logo.png");
        
            # recorremos todos los arhivos que se han subido
//            for($i=0;$i<count($_FILES["archivo"]["name"]);$i++)
//            {        
                #divide el nombre del fichero con un .    
    	        $explode_name = explode('.',$_FILES["archivo_logo"]["name"]);
                # si es un formato de excel
                if($explode_name[1] == 'png') 
                {
                    # si exsite la carpeta o se ha creado
                    if(file_exists($carpetaDestino) || @mkdir($carpetaDestino))
                    {
                        $origen=$_FILES["archivo_logo"]["tmp_name"];
                        $destino=$carpetaDestino.$_FILES["archivo_logo"]["name"];
                       
                        # movemos el archivo
                        if(@move_uploaded_file($origen, $destino))
                        {
                            rename ($destino,$carpetaDestino."logo.png");
    			   header('Location:apariencia.php?error=0'); //Correcto
                        }else{
                            echo "<br>No se ha podido mover el archivo: ".$_FILES["archivo_logo"]["name"];
                            header('Location:apariencia.php?error=1'); //No se pudo subir el archivo
                        }
                    }else{
                        echo "<br>No se ha podido crear la carpeta: up/".$user;
                        header('Location:apariencia.php?error=2'); //No se pudo crear la carpeta
                    }
                }else{
                    echo "<br>".$_FILES["archivo_logo"]["name"]." - Formato no admitido";
                    header('Location:apariencia.php?error=3'); //FORMATO NO ADMITIDO
                }
           // }
    			//header('Location:apariencia.php');
        }else{
            echo "<br>No hay ningun arhivo para subir";
            header('Location:apariencia.php?error=4'); //No hay archivo para subir
        }
    }else{ //Para eliminar el logo
        unlink($carpetaDestino."logo.png");
        header('Location:apariencia.php?error=0'); //Correcto
    }       
    
    ?>
