<?php
include ("seguridad.php");
session_start();
$user =  $_SESSION["login"];

# definimos la carpeta destino	
    $carpetaDestino="../php/correos/";
    $cliente=$_POST['id'];
    $respuesta=array();
        # si hay algun archivo que subir
    
        if($_FILES["correo"]["name"])
        {  
            #divide el nombre del fichero con un .    
            $explode_name = explode('.',$_FILES["correo"]["name"]);           
            #Borramos el logo anterior    
            unlink($carpetaDestino.$cliente.".".$explode_name[1]);
            
            # si es un formato de excel
            if($explode_name[1] == 'msg' || $explode_name[1] == 'rtf' || $explode_name[1] == 'eml') 
            {
                    # si exsite la carpeta o se ha creado
                    if(file_exists($carpetaDestino) || @mkdir($carpetaDestino))
                    {
                        $origen=$_FILES["correo"]["tmp_name"];
                        $destino=$carpetaDestino.$_FILES["correo"]["name"];
                        //# movemos el archivo
                        if(@move_uploaded_file($origen, $destino))
                        {
                            
                           rename ($destino,$carpetaDestino.$cliente.".".$explode_name[1]);
                           $zip = new ZipArchive();
                           

                            if($zip->open($carpetaDestino.$cliente.".zip",ZIPARCHIVE::CREATE)===true) 
                            {
                             $zip->addFile($carpetaDestino.$cliente.".".$explode_name[1]);   
                             $zip->close();
                             unlink($carpetaDestino.$cliente.".".$explode_name[1]);
                             echo json_encode();       
                            }
                            else {
                                    $respuesta['error']='Error creando '.$cliente.'.zip';
                            }
                           
    			}else{ 
                            $respuesta['error']="Error: No se ha podido copiar el fichero.";
                            
                        }
                    }else{
                        $respuesta['error']="Error: No se ha podido crear la carpeta de destino.";
                     }
            }
            else{
                $respuesta['error']="Error: No se ha podido crear la carpeta de destino. Formato no admitido";
            }
            
        }else{
            $respuesta['error']="error: No hay archivo para subir.";
        }
        echo json_encode($respuesta);
        return;
    ?>

