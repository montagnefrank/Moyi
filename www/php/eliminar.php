<?php 
   
///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////CONEXION A DB
session_start();
$user = $_SESSION["login"];
$rol = $_SESSION["rol"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

	  //recogiendo el id de la orden a activar o cancelar
	  $codi=$_GET["codigo"];
	  $sql= "SELECT estado_orden, Ponumber from tbldetalle_orden WHERE id_orden_detalle='".$codi."'"; 
	  $query= mysqli_query($link, $sql);
	  $row = mysqli_fetch_array($query);
	  $estado = $row['estado_orden'];
	  $id     = $row['Ponumber'];
	?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Activar/Cancelar Orden</title>
	<link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" >
  <table width="300" border="0" align="center" class="alert">
    <tr>
      <td height="30" colspan="2" align="center"><strong><span id="result_box" lang="en" xml:lang="en"><?php 
	  if($estado == 'Active'){
	     echo '¿Está seguro de cancelar esta orden?'; 
	  }else{
		  echo '¿Está seguro de activar esta orden?'; 
		 }	  
	  ?></span></strong></td>
    </tr>
    <tr>
        <td>Razón:</td><td> <textarea value="" class="form-control" maxlength="150" id="razon" name="razon" rows="4"></textarea></td>
    </tr>
    <tr>
        <td></td>
      <td align="center">
         <input name="si" type="submit" class="btn-danger" id="si" value="SI" />
         <input name="no" type="submit" class="alert-info" id="no" value="NO" onClick="self.close();"/>
       </td>
    </tr>
  </table>
</form>
<?php 
  if(isset($_POST["si"])){
  
	  if($estado == 'Active'){ 
	        //CONVERTIR EL CAMPO PRECIO A NEGATIVO
			$a = "SELECT unitprice,Ponumber FROM tbldetalle_orden WHERE id_orden_detalle='".$codi."'"; 
			$b = mysqli_query($link, $a) or die ("Error convirtiendo el precio");
			$fila = mysqli_fetch_array($b);
			$precio = -$fila['unitprice']; //poniendolo como negativo para que sea un credito
			
			$sql="UPDATE tbldetalle_orden set estado_orden='Canceled', status='Not Shipped', unitprice= '".$precio."',eBing = 0 where id_orden_detalle='".$codi."'";
			$eliminado= mysqli_query($link, $sql);
			
			//elimino el costo de esa orden en la tabla de costos
		    $sql1="DELETE FROM tblcosto WHERE po='".$fila['Ponumber']."'";
		    $eliminado1= mysqli_query($link, $sql1);
			
			if($eliminado && $eliminado1){
				            //Agregar al historico

            function getRealIP()
            {
             
                if (isset($_SERVER["HTTP_CLIENT_IP"]))
                {
                    return $_SERVER["HTTP_CLIENT_IP"];
                }
                elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
                {
                    return $_SERVER["HTTP_X_FORWARDED_FOR"];
                }
                elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
                {
                    return $_SERVER["HTTP_X_FORWARDED"];
                }
                elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
                {
                    return $_SERVER["HTTP_FORWARDED_FOR"];
                }
                elseif (isset($_SERVER["HTTP_FORWARDED"]))
                {
                    return $_SERVER["HTTP_FORWARDED"];
                }
                else
                {
                    return $_SERVER["REMOTE_ADDR"];
                }
             
            }

            $usuarioLog = $_SESSION["login"];
            $ip = getRealIP();
            $fecha = date('Y-m-d H:i:s');
            $operacion = "Cancelar Orden: ".$id;
            $razon=$_POST['razon'];
            $SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`,`razon`) 
                                               VALUES ('$usuarioLog','$operacion','$fecha','$ip','$razon')";
            $consultaHist = mysqli_query($link, $SqlHistorico) or die ("Error actualizando la bitácora de usuarios");



				echo("<script> alert ('Orden modificada correctamente');
							   window.close();
							   window.opener.document.location='gestionarordenes.php?id=".$id."';
					 </script>");	
			}else{
				 echo("<script> alert (".mysqli_error().");</script>");
				 }
	  }else{
		  
		  //CONVERTIR EL CAMPO PRECIO A positivo
			$a = "SELECT unitprice,tracking FROM tbldetalle_orden WHERE id_orden_detalle='".$codi."'"; 
			$b = mysqli_query($link, $a) or die ("Error convirtiendo el precio");
			$fila = mysqli_fetch_array($b);
			$precio = -$fila['unitprice']; //poniendolo como positivo  
			if($fila['tracking']!=''){
				$estado ="Shipped";
			}else{
				$estado = "New";
			}
			
		    $sql="UPDATE tbldetalle_orden set estado_orden='Active', status='".$estado."', unitprice= '".$precio."' where id_orden_detalle='".$codi."'";
			$eliminado= mysqli_query($link, $sql);
			if($eliminado){
				
			//Agregar a historico	
            function getRealIP()
            {
                if (isset($_SERVER["HTTP_CLIENT_IP"]))
                {
                    return $_SERVER["HTTP_CLIENT_IP"];
                }
                elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
                {
                    return $_SERVER["HTTP_X_FORWARDED_FOR"];
                }
                elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
                {
                    return $_SERVER["HTTP_X_FORWARDED"];
                }
                elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
                {
                    return $_SERVER["HTTP_FORWARDED_FOR"];
                }
                elseif (isset($_SERVER["HTTP_FORWARDED"]))
                {
                    return $_SERVER["HTTP_FORWARDED"];
                }
                else
                {
                    return $_SERVER["REMOTE_ADDR"];
                }
            }

            $usuarioLog = $_SESSION["login"];
            $ip = getRealIP();
            $fecha = date('Y-m-d H:i:s');
            $operacion = "Activar Orden: ".$id;
            $SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`) 
                                               VALUES ('$usuarioLog','$operacion','$fecha','$ip')";
            $consultaHist = mysqli_query($link, $SqlHistorico) or die ("Error actualizando la bitácora de usuarios");


				echo("<script> alert ('Orden modificada correctamente');
							   window.close();
							   window.opener.document.location='gestionarordenes.php?id=".$id."';
					 </script>");	
			}else{
				 echo("<script> alert (".mysqli_error().");</script>");
				 }
	}
  }
 /* if(isset($_POST["no"])){  
     //$_SESSION['PoNbr']= $PoNbr;
 	 echo("<script>window.close();
				   window.opener.document.location='gestionarordenes.php?id=".$id."';
				   </script>");
   }  
  */
  ?>
</body>
</html>