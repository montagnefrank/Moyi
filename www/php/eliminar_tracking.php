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
$codi=$_GET["codigo"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Eliminar Tracking</title>
	<link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="300" border="0" align="center" class="alert">
    <tr>
      <td height="30" colspan="2" align="center"><strong><span id="result_box" lang="en" xml:lang="es">¿Está seguro que desea eliminar el tracking? </span></strong></td>
    </tr>
    <tr>
        <td>Razón:</td><td> <textarea value="" class="form-control" maxlength="150" id="razon" name="razon" rows="4"></textarea></td>
    </tr>
    <tr>
        <td></td>
      <td align="center"><input name="si" type="submit" class="btn-danger" id="si" value="SI" />
      <input name="no" type="submit" class="alert-info" id="no" value="NO" onClick="self.close();" /></td>
    </tr>
  </table>
</form>
  <?php 
  	  //Obtengo el Ponumber, para verificar cuantas ordenes hay con ese ponumber
	  $sql="SELECT Ponumber,Custnumber,tracking FROM tbldetalle_orden WHERE id_orden_detalle='".$codi."'";
	  $query= mysqli_query($link, $sql);
	  $row = mysqli_fetch_array($query);
	  $PoNbr = $row['Ponumber'];
	  $CustNbr = $row['Custnumber'];
	  $tracking = $row['tracking'];

  if(isset($_POST["si"])){
 
	  //Obtengo el Ponumber, para verificar cuantas ordenes hay con ese ponumber
	  //$sql="SELECT id_orden_detalle,id_detalleorden FROM tbldetalle_orden WHERE Ponumber='".$PoNbr."' AND Custnumber = '".$CustNbr."'";
	  $sql="SELECT id_orden_detalle,id_detalleorden,tracking FROM tbldetalle_orden WHERE Ponumber='".$PoNbr."' AND Custnumber = '".$CustNbr."' AND tracking= '".$tracking."' ";
  
	  $query= mysqli_query($link, $sql);
	  $row = mysqli_fetch_array($query); 
	  $cant = mysqli_num_rows($query);
	  echo $cant;
	  if($cant == 1){   //Si solo hay un detalle elimino la orden completa
	  	  $sql = "UPDATE tbldetalle_orden SET tracking='', status='Ready to ship', descargada='not downloaded', user='', farm='', coldroom='No', codigo='0000000000' WHERE id_orden_detalle='".$codi."' AND tracking= '".$tracking."'";
		  //$sql="DELETE FROM tblorden WHERE id_orden='".$row['id_detalleorden']."'";
		  $eliminado= mysqli_query($link, $sql);
		  
		  //elimino el costo de esa orden en la tabla de costos
		  //$sql1="DELETE FROM tblcosto WHERE po='".$PoNbr."'";
		  //$eliminado1= mysqli_query($link, $sql1);
		  
			$sqlcold = "UPDATE tblcoldroom SET tracking_asig='', guia_hija='0', guia_madre='0', salida='No', guia_master='0', palet='0' WHERE tracking_asig = '".$tracking."' ";
			$querycold= mysqli_query($link, $sqlcold);


			if($eliminado /*&& $eliminado1*/){

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
            $operacion = "Eliminar tracking: PO: ".$PoNbr." Trk: ".$tracking;
            $razon=$_POST['razon'];
            $SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`,`razon`) 
                                               VALUES ('$usuarioLog','$operacion','$fecha','$ip','$razon')";
            $consultaHist = mysqli_query($link, $SqlHistorico) or die ("Error actualizando la bitácora de usuarios");


				echo("<script> alert ('Tracking eliminado correctamente');
							   window.close();
							   window.opener.document.location='gestionarordenes.php?id=".$codi."';
					 </script>");
			 }else{
				 echo("<script> alert (".mysqli_error().");</script>");
				 }
	  }
	  /*else{
		  //Si tiene mas de un 
		  //$sql="DELETE FROM tbldetalle_orden WHERE id_orden_detalle='".$codi."'";
	  	  $sql = "UPDATE tbldetalle_orden SET tracking='', status='Ready to ship', descargada='not downloaded', user='' WHERE id_orden_detalle='".$codi."'";
		  $eliminado= mysqli_query($link, $sql);
			if($eliminado){
				echo("<script> alert ('Orden eliminada correctamente');
							   window.close();
							   window.opener.document.location='gestionarordenes.php?id=".$PoNbr."';
					 </script>");
			 }else{
				 echo("<script> alert (".mysqli_error().");</script>");
				 }
		}*/
  }
  
  /* if(isset($_POST["no"])){  
  echo("<script>window.close();
				   window.opener.document.location='gestionarordenes.php?id=".$PoNbr."';
				   </script>");
  }   */
  
?>
</body>
</html>