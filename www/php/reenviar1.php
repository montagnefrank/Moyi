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
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reenviar Órden</title>
<link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="form1" name="form1" method="post" target="_self">
  <table width="300" border="0" align="center" class="alert">
    <tr>
      <td height="30" colspan="2" align="center"><strong><span id="result_box" lang="en" xml:lang="en">Esta seguro de reenviar la orden</span>?</strong></td>
    </tr>
    <tr>
      <td align="center"><input name="si" type="submit" class="btn-danger" id="si" value=Si" /></td>
      <td align="center"><input name="no" type="submit" class="alert-info" id="no" value="NO" /></td>
    </tr>
  </table>
</form>
 <?php 
  	  $codi    = $_GET["codigo"];
	  $deliver = $_GET["deliver"];
	  $item    = $_GET["item"];
	  
	  $sql="SELECT tracking,Ponumber, Custnumber FROM tbldetalle_orden where id_orden_detalle='".$codi."'";
	  $query = mysqli_query($link, $sql);
	  $row = mysqli_fetch_array($query);
	  
	  $ponumber   = $row['Ponumber'];
	  $custnumber = $row['Custnumber'];
  if(isset($_POST["si"])){
	  
	        		 
			 if($row['tracking']==''){ //Si el tracking es vacio no puede hacer reshipped
			        echo("<script> alert ('Esta orden no puede ser reenviada, pq no tiene tracking asignado');
								   window.close();
								   window.opener.document.location='reenvioordenes.php?id=".$row['Ponumber']."';
						 </script>");
				 
			 }else{	//Si el tracking no es vacio entonces puede hacer reshipped		  
				  
					  $sql="SELECT * FROM tbldetalle_orden where id_orden_detalle='".$codi."'";
					  $query = mysqli_query($link, $sql);
					  $row = mysqli_fetch_array($query);
					  
					  $sql1 = "Insert INTO `tbldetalle_orden`(`id_detalleorden`,`cpcantidad`,`cpitem`,`satdel`,`farm`,`cppais_envio`,`cpmoneda`,`cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`Ponumber`,`Custnumber`,`tracking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`reenvio`,`poline`,`unitprice`,`ups`,`vendor`)VALUES ('".$row['id_detalleorden']."','".$row['cpcantidad']."','".$item."','".$row['satdel']."','','".$row['cppais_envio']."','".$row['cpmoneda']."','".$row['cporigen']."','".$row['cpUOM']."','".$deliver."','".$row['ShipDT_traking']."','".$row['Ponumber']."','".$row['Custnumber']."','','".$row['estado_orden']."','not downloaded','','".$row['eBing']."','No','New','Forwarded','".$row['poline']."','0.00','".$row['ups']."','".$row['vendor']."')";
				  
					$reenvio= mysqli_query($link, $sql1)or die (mysqli_error());
					
					//Actualizar la tabla de atencion al cliente
					$sql2   = "SELECT reenvio FROM tblcustom_services WHERE Ponumber='".$ponumber."' AND Custnumber='".$custnumber."'";
					$query1 = mysqli_query($link, $sql2) or die("Error verificando si la orden tenia reenvio"); 
					$cant   = mysqli_num_rows($query1);
					
					$fecha = date('Y-m-d');
					
					if($cant == 0){						
						//Inserto una nueva fila en tabla de customer servces con un renvio
						$a = "Insert INTO tblcustom_services (`ponumber`,`custnumber`,`reenvio`,`fecha`,`id_orden`) VALUES ('".$ponumber."','".$custnumber."','Si','".$fecha."','".$row['id_orden_detalle']."')";
						$b = mysqli_query($link, $a) or die ("Error insertando datos del reenvio");
						
						if($reenvio && $b){
                                                        if(isset($_GET["pag"]) && ($_GET["pag"]=="reenvioordenes"))
                                                        {
                                                            echo("<script> alert ('Orden reenviada correctamente, recuerde editar datos de reenvio');
										   window.close();
										   window.opener.document.location='reenvioordenes.php?id=".$ponumber."';
								 </script>");
                                                        }else{
                                                          echo("<script> alert ('Orden reenviada correctamente, recuerde editar datos de reenvio');
										   window.close();
										   window.opener.document.location='cust_services.php?id=".$ponumber."';
								 </script>");
                                                        }
                                                  }else{
							 echo("<script> alert (".mysqli_error().");</script>");
						 }						
					}else{
						//Modifico la fila existente en tabla de customer servces con un renvio
						$a = "UPDATE tblcustom_services set reenvio='Si',fecha='".$fecha."' WHERE ponumber = '".$ponumber."' and custnumber='".$custnumber."'";
						$b = mysqli_query($link, $a) or die ("Error modificando datos del reenvio");
						
						if($reenvio && $b){
							if(isset($_GET["pag"]) && ($_GET["pag"]=="reenvioordenes"))
                                                        {
                                                            echo("<script> alert ('Orden reenviada correctamente, recuerde editar datos de reenvio');
										   window.close();
										   window.opener.document.location='reenvioordenes.php?id=".$ponumber."';
								 </script>");
                                                        }else{
                                                          echo("<script> alert ('Orden reenviada correctamente, recuerde editar datos de reenvio');
										   window.close();
										   window.opener.document.location='cust_services.php?id=".$ponumber."';
								 </script>");
                                                        }
							 }else{
								 echo("<script> alert (".mysqli_error().");</script>");
							}
						
					}
			 }
}

 
   if(isset($_POST["no"])){  
     //$_SESSION['PoNbr']= $PoNbr;
       
       if(isset($_GET["pag"]) && ($_GET["pag"]=="reenvioordenes"))
       {
           echo("<script>window.close();
	       window.opener.document.location='reenvioordenes.php?id=".$ponumber."';
	       </script>");
       }else{
 	 echo("<script>window.close();
	       window.opener.document.location='cust_services.php?id=".$ponumber."';
	       </script>");
       }
   } 
?>
</body>
</html>