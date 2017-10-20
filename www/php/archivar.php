<?php 
session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");

$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE) or die('No pudo conectarse : '. mysql_error());
//recogiendo el id de la orden a activar o cancelar
$codi=$_GET["codigo"];
$cajass=$_GET["cajas"];

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Archivar solicitud</title>
	<link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="300" border="0" align="center" class="alert">
    <tr>
      <td height="30" colspan="2" align="center"><strong><span id="result_box" lang="en" xml:lang="en">Está seguro de archivar esta solicitud?</span></strong></td>
    </tr>
    <tr>
      <td align="center"><input name="si" type="submit" class="btn-danger" id="si" value="SI" /></td>
      <td align="center"><input name="no" type="submit" class="alert-info" id="no" value="NO" /></td>
    </tr>
  </table>
</form>
<?php 
if(isset($_POST["si"])){
   
    $cajas=array();
    
    if($codi==0){
       //Armar el array en php
       $cajas = explode(",",$cajass);
        
    }
    else{
        $cajas[0]=$codi;
    }

       for ($i=0; $i < count($cajas);$i++){	
            $sql="UPDATE tbletiquetasxfinca set archivada='Si' where nropedido='".$cajas[$i]."'";
            $actualizado= mysql_query($sql,$link);
            if($actualizado){
                    echo("<script> alert ('Solictud(es) archivada(s) correctamente');
                                window.close();
                                window.opener.document.location='asig_etiquetas.php';
                        </script>");	
            }else{
                echo("<script> alert (".mysql_error().");</script>");
            }
       }
    }

  
  if(isset($_POST["no"])){  
 	 echo("<script>window.close();
		window.opener.document.location='asig_etiquetas.php';
	</script>");
   }  
?>
</body>
</html>