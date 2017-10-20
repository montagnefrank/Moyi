<?php 
    session_start();
	include ("conectarSQL.php");
    include ("conexion.php");
	include ("seguridad.php");
	
	$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());
	
	//recojo el id de la orden a modificar
	$id = $_GET['codigo'];	
	
	$sql = "SELECT id_orden_detalle,estado_orden,Ponumber, Custnumber, cpitem, delivery_traking,reenvio, soldto1, unitprice
						FROM
						tblorden
						INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
						INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
						WHERE id_orden_detalle = '".$id."';";
	$query = mysql_query($sql,$link);
	$row   = mysql_fetch_array($query);
	$Ponumber = $row['Ponumber'];
	
	$sql= "SELECT * FROM tblcustom_services WHERE id_orden='".$id."'";
	$query = mysql_query($sql,$link)or die ("Error leyendo las quejas de esta orden");
	$row1 = mysql_fetch_array($query);
	$array =  json_decode($row1['razones'],true);
	$array = explode(",",$array);

	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>credito</title>
 <link rel="icon" type="image/png" href="../images/favicon.ico" />
<link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="form1" name="form1" method="post">
<table width="500" border="0" align="center">
     <tr>
    <td width="500" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Registrar crédito al cliente <font color="#FF0000"><?php echo $row['soldto1']?></font> de la orden </font><font color="#FF0000"><?php echo $row['Ponumber']?></font></strong></td>
  </tr>
</table>
<table width="500" border="0" align="center">
 <tr height="20"><td></td></tr>
     <tr>
     	<td>
        <table border="0" align="center">
        <tr>
                <td width="233" height="24" align="right"><strong>Precio unitario</strong>:</td>
              <td width="253"><input name="credito" type="text" autofocus="autofocus" value="<?php echo $row['unitprice']?>" disabled="disabled"/></td>
          </tr>
            <tr>
                <td width="233" height="24" align="right"><strong>Crédito a devolver</strong>:</td>
              <td width="253"><input name="credito" type="text" autofocus="autofocus" value="<?php
			   if ($row1['credito'] <> 0){
				   echo $row1['credito'];
				   }else{
					   echo 0;
					   }
			   ?>"/></td>
            </tr>
             <tr>
                <td colspan="2" align="center"><p><strong>Posibles razones de credito:</strong></p>
                  <table width="490">
                    <tr>
                      <td width="223"><label>
                        <input type="checkbox" name="CheckboxGroup1[]" value="FZ" id="CheckboxGroup1_0" <?php if (in_array("FZ",$array,true)) { echo 'checked="checked"'; } ?>/>
                        Frozen</label></td>
                        <td width="248"><label>
                        <input type="checkbox" name="CheckboxGroup1[]" value="GP" id="CheckboxGroup1_1" <?php if (in_array("GP",$array,true)) { echo 'checked="checked"'; } ?>/>
                        Guard Petals</label></td>
                    </tr>
                    <tr>
                      <td><label>
                        <input type="checkbox" name="CheckboxGroup1[]" value="BO" id="CheckboxGroup1_2"<?php if (in_array("BO",$array,true)) { echo 'checked="checked"'; } ?> />
                        Botritis</label></td>
                        <td><label>
                        <input type="checkbox" name="CheckboxGroup1[]" value="WAB" id="CheckboxGroup1_3" <?php if (in_array("WAB",$array,true)) { echo 'checked="checked"'; } ?>/>Wrong Amount of Blooms</label></td>
                    </tr>
                    <tr>
                      <td><label>
                        <input type="checkbox" name="CheckboxGroup1[]" value="CV" id="CheckboxGroup1_4" <?php if (in_array("CV",$array,true)) { echo 'checked="checked"'; } ?>/>
                        Craked Vase</label></td>
                        <td><label>
                        <input type="checkbox" name="CheckboxGroup1[]" value="WB" id="CheckboxGroup1_5" <?php if (in_array("WB",$array,true)) { echo 'checked="checked"'; } ?>/>Wrong Bouquet</label></td>
                    </tr>
                    <tr>
                      <td><label>
                        <input type="checkbox" name="CheckboxGroup1[]" value="IH" id="CheckboxGroup1_6" <?php if (in_array("IH",$array,true)) { echo 'checked="checked"'; } ?>/>
                        Improper Hydration</label></td>
                        <td><label>
                        <input type="checkbox" name="CheckboxGroup1[]" value="WC" id="CheckboxGroup1_7" <?php if (in_array("WC",$array,true)) { echo 'checked="checked"'; } ?>/>Wrong Color</label></td>
                    </tr>
                    <tr>
                    <td><label>
                        <input type="checkbox" name="CheckboxGroup1[]" value="UPS" id="CheckboxGroup1_8" <?php if (in_array("UPS",$array,true)) { echo 'checked="checked"'; } ?>/>
                        UPS error</label></td>
                      <td><label>
                        <input type="checkbox" name="CheckboxGroup1[]" value="DWA" id="CheckboxGroup1_9" <?php if (in_array("DWA",$array,true)) { echo 'checked="checked"'; } ?>/>Delivered to wrong Address</label></td>
                    </tr>
                    <tr>
                    <td><label>
                        <input type="checkbox" name="CheckboxGroup1[]" value="SHC" id="CheckboxGroup1_10" <?php if (in_array("SHC",$array,true)) { echo 'checked="checked"'; } ?>/>
                        Shipment Delivered Correctly</label></td>
                      <td><label>
                        <input type="checkbox" name="CheckboxGroup1[]" value="D2DL" id="CheckboxGroup1_11"<?php if (in_array("D2DL",$array,true)) { echo 'checked="checked"'; } ?> />Delivery 2+ days late</label></td>
                    </tr>
                    <tr>
                      <td><label>
                        <input type="checkbox" name="CheckboxGroup1[]" value="SHI" id="CheckboxGroup1_12" <?php if (in_array("SHI",$array,true)) { echo 'checked="checked"'; } ?>/>
                        Shipment Delivered Incorrectly</label></td>
                        <td><label>
                        <input type="checkbox" name="CheckboxGroup1[]" value="IND" id="CheckboxGroup1_13" <?php if (in_array("IND",$array,true)) { echo 'checked="checked"'; } ?>/>Item Not Delivered</label></td>
                    </tr>
                     <tr>
                      <td><label>
                        <input type="checkbox" name="CheckboxGroup1[]" value="NMS" id="CheckboxGroup1_14" <?php if (in_array("NMS",$array,true)) { echo 'checked="checked"'; } ?>/>
                        No Menssage Sent</label></td>
                        <td><label>
                        <input type="checkbox" name="CheckboxGroup1[]" value="IN" id="CheckboxGroup1_15" <?php if (in_array("IN",$array,true)) { echo 'checked="checked"'; } ?>/>
                        Insects</label></td>
                    </tr>
                    <tr>
                    <td><label>
                        <input type="checkbox" name="CheckboxGroup1[]" value="OT" id="CheckboxGroup1_15" <?php if (in_array("OT",$array,true)) { echo 'checked="checked"'; } ?>/>
                        Other</label></td>
                    </tr>
                    <tr>
                    <td colspan="2" align="center"><strong>Quejas</strong></td>
                    </tr>
                <td height="127" colspan="2"><textarea name="quejas" id="quejas" style="resize:none;height: 120px;max-height: 280px;max-width: 100%;min-height: 100px;min-width: 100%;width: 100%;azimuth:left"><?php
				echo trim($row1['quejas']);			
                ?>
                </textarea></td>
            </tr>
               </table></td>
            </tr>
            <tr>
            <td></td>
            <td align="left"><input name="Devolver" type="submit" value="Registrar" title="Registrar quejas" />
            <input name="Cancelar" type="submit" value="Cancelar" title="Cancelar el registro"/></td>
            </tr>
        </table>
        </td>
     </tr>
     <tr height="20"><td></td></tr>
<tr>
    <td height="36" align="center" bgcolor="#3B5998" colspan="5"><strong><font color="#FFFFFF">Bit <img src="../images/r.png" width="15" height="15"/> 2015 versión 3 </font></strong></td>
  </tr>
</table>
</form>
  <?php
   if(isset($_POST["Devolver"])){  
 	 /*echo("<script> window.close()</script>");*/
	 $credito     = $_POST['credito'];
	 $ponumber   = $row['Ponumber'];
	 $custnumber = $row['Custnumber'];
	 $fecha      = date ('Y-m-d');
	 $reenvio    = 'No';
	 $id_orden   = $row['id_orden_detalle'];
	 $quejas     = trim($_POST['quejas']);
	 
	 //Obtener las razones de credito seleccionada
	 if (is_array($_POST['CheckboxGroup1'])) {
        $selected = '';
        $num_razones = count($_POST['CheckboxGroup1']);
        $current = 0;
        foreach ($_POST['CheckboxGroup1'] as $key => $value) {
            if ($current != $num_razones-1)
                $selected .= $value.', ';
            else
                $selected .= $value;
            $current++;
        }
    }
    else {
        echo("<script> alert ('Inserte al menos una razón');</script>");
		break;
    }
		 $selected = json_encode($selected);
		 $sentencia ="SELECT * FROM tblcustom_services WHERE id_orden = '".$id_orden."'";
		 $consulta   = mysql_query($sentencia,$link) or die (mysql_error($consulta));
		 $cantfila  = mysql_num_rows($consulta);
		 
		 if($cantfila == 0){
		  	$sql="INSERT INTO tblcustom_services (`ponumber`, `custnumber`, `quejas`,`fecha`,`reenvio`,`credito`,`razones`,`id_orden`) VALUES ('$ponumber','$custnumber ','$quejas','$fecha','$reenvio','$credito','$selected','$id_orden')";	
			 $insertado= mysql_query($sql,$link);
			 if($insertado){
				echo("<script> alert ('Crédito registrado correctamente');
							   window.close();					   
							   window.opener.document.location='cust_services.php?id=".$ponumber ."';
					 </script>");
			 }else{
				 echo("<script> alert (".mysql_error().");</script>");
				 }
		 }else{
			 $sql="UPDATE tblcustom_services set ponumber='$ponumber',custnumber='$custnumber',fecha= '$fecha',reenvio='$reenvio',credito='$credito',razones='$selected', quejas='$quejas' WHERE id_orden='$id_orden'"; 	
		 $insertado= mysql_query($sql,$link);
			 if($insertado){
				echo("<script> alert ('Crédito registrado correctamente');
							   window.close();					   
							   window.opener.document.location='cust_services.php?id=".$ponumber ."';
					 </script>");
			 }else{
				 echo("<script> alert (".mysql_error().");</script>");
				 }
		 }
   }
    
   if(isset($_POST["Cancelar"])){  
 	 echo("<script>    window.close();					   
					   window.opener.document.location='cust_services.php?id=".$Ponumber."';
		     </script>");
   }  
  ?>
</body>
</html>