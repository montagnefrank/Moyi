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

    //recojo el id de la orden a modificar
    $id = $_GET['codigo'];	

    $sql = "select *
            FROM
            tblorden
            INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
            INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
            INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
            INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
            INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
            WHERE id_orden_detalle = '".$id."';";
    $query = mysqli_query($link, $sql);
    $row   = mysqli_fetch_array($query);
    $id = $row['id_orden_detalle'];
    $idorden = $row['id_orden'];
    $Ponumber =  $row['Ponumber'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Modificar Orden</title>
    <link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" media="all" href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
    <script type="text/javascript" src="../js/calendar.js"></script>
    <script type="text/javascript" src="../js/calendar-en.js"></script>
    <script type="text/javascript" src="../js/calendar-setup.js"></script>
    <script src="//code.jquery.com/jquery-1.9.1.js"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
    <style type="text/css">
        .my-error-class {
            color:red;
        }
        /*.my-valid-class {
            color:green;
        }*/
    </style>
    <script>
        // When the browser is ready...
        $(function() {
            // Setup form validation on the #register-form element
            $("#form1").validate({
                errorClass: "my-error-class",
                validClass: "my-valid-class",
            
                // Specify the validation rules
                rules: {
                    ponumber: "required",
                    shipto: "required",
                    direccion: "required",
                    billaddress: "required",
                    mail: {
                        //required: true,
                        email: true
                    },
                    soldto: {
                        required: true,
                        //minlength: 5
                    },
                    billcountry: "required"
                    
                },
                
                // Specify the validation error messages
                messages: {
                    ponumber: "Por favor inserte el poNumber",
                    shipto: "Por favor inserte el shipto",
                    direccion: "Por favor inserte la dirección",
                    billaddress:"Por favor inserte la dirección cobrar a",
                    mail: "Inserte una dirección de correo válida",
                    soldto: {
                        required: "Por favor inserte el soldto",
                        //minlength: "Your password must be at least 5 characters long"
                    },   
                    billcountry: "Por favor inserte el País"
                    
                },
                
                submitHandler: function(form) {
                    form.submit();
                }
                
            });
        });
    </script>
</head>
<body>
<form id="form1" name="form1" method="post" novalidate action="" .error { color:red}>
<table width="900" border="0" align="center">
     <tr>
    <td width="900" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Modificar Datos de la orden con Ponumber </font> <font color="#FF0000"><?php echo $row['Ponumber']?></font><font color="#FFFFFF"> y Custnumber </font><font color="#FF0000"><?php echo $row['Custnumber']?></font></strong></td>
  </tr>
</table>
<table width="900" border="0" align="center">
 <tr height="20"><td></td></tr>
     <tr>
     	<td>
        <table border="0" align="center">
            <tr>
            	<td colspan="4" align="center"><strong>Campos a modificar</strong>
            </tr>
                <td width="130"><strong>Ponumber:</strong></td>
                <td width="279"><input type="text" id="ponumber" name="ponumber" value="<?php echo $row['Ponumber'];?>" size="30" autofocus tabindex="0"/><font color="#CC0000">*</font></td>
               <td><strong>Tracking:</strong></td>
            <td><input type="text" id="tracking" name="tracking" <?php 
			//Verificar si tiene tracking
			if($row['tracking'] == ''){
				echo 'readonly="true"';
			}else{
				echo 'value="'.$row['tracking'].'" readonly="true"';
			}
			?>/></td></tr>
        	<tr>               
                <td width="130"><strong>Nombre Receptor:</strong></td>
                <td width="279"><input type="text" id="shipto" name="shipto" value="<?php echo $row['shipto1'];?>"  tabindex="1" size="30"/><font color="#CC0000">*</font></td>
             <td><strong>Teléfono:</strong></td>
                <td><input type="text" id="billphone" name="billphone" value="<?php echo $row['cpstphone_soldto'];?>" tabindex="11" /></td>   
            </tr>
            <tr>
            	<td><strong>Apellido Receptor:</strong></td>
                <td><input type="text" id="shipto2" name="shipto2" value="<?php echo $row['shipto2'];?>" tabindex="2" size="30"/></td>
             <td><strong>Cod. Postal:</strong></td>
                <td><input type="text" id="billzip" name="billzip" value="<?php echo $row['postalcode'];?>" tabindex="9" size="30"/></td>   
                
            </tr>
            <tr>
            	<td><strong>Dirección:</strong></td>
                <td><input type="text" id="direccion" name="direccion" value="<?php echo $row['direccion'];?>" tabindex="3" size="30" /><font color="#FF0000">*</font></td>
              <td><strong>País:</strong></td>
                <td><input type="text" id="billcountry" name="billcountry" value="<?php echo $row['billcountry'];?>" tabindex="9" size="30"/><font color="#FF0000">*</font></td>  
                
            </tr>
            <tr>
            <td><strong>Dirección2:</strong></td>
                <td><input type="text" id="direccion2" name="direccion2" value="<?php echo $row['direccion2'];?>" tabindex="3" size="30" /></td>
            <td width="102"><strong>Fecha de Orden:</strong></td>
                <td width="282">
                <input name="orddate" type="text" id="orddate" readonly size="20" value="<?php echo $row['order_date'];?>"/>
                <script type="text/javascript">
        function catcalc(cal) {
            var date = cal.date;
            var time = date.getTime()
            // use the _other_ field
            var field = document.getElementById("awdawd");
            if (field == cal.params.inputField) {
                field = document.getElementById("orddate");
                time -= Date.WEEK; // substract one week
            } else {
                time += Date.WEEK; // add one week
            }
            var date2 = new Date(time);
            field.value = date2.print("%Y-%m-%d");
        }
        Calendar.setup({
            inputField     :    "orddate",   // id of the input field
            ifFormat       :    "%Y-%m-%d ",       // format of the input field
            showsTime      :    false,
            timeFormat     :    "24",
            onUpdate       :    catcalc
        });
    
                  </script></td>	
            </tr>
            <tr>
            <td><strong>Ciudad:</strong></td>
                <td><input type="text" id="ciudad" name="ciudad" value="<?php echo $row['cpcuidad_shipto'];?>" tabindex="4"/></td>
            <td><strong>Fecha de Entrega:</strong></td>
                <td><input type="text" id="deliver" name="deliver" value="<?php echo $row['delivery_traking'];?>"  readonly="readonly" size="20"/>
                <script type="text/javascript">
        function catcalc(cal) {
            var date = cal.date;
            var time = date.getTime()
            // use the _other_ field
            var field = document.getElementById("f_calcdate");
            if (field == cal.params.inputField) {
                field = document.getElementById("deliver");
                time -= Date.DAY; // substract one week
                time -= Date.DAY; // substract one week
                time -= Date.DAY; // substract one week
            } else {
                time -= Date.DAY; // add one week
                time -= Date.DAY; // add one week
                time -= Date.DAY; // add one week
            }
            var date2 = new Date(time);
            field.value = date2.print("%Y-%m-%d");
        }
        Calendar.setup({
            inputField     :    "deliver",   // id of the input field
            ifFormat       :    "%Y-%m-%d ",       // format of the input field
            showsTime      :    false,
            timeFormat     :    "24",
            onUpdate       :    catcalc
        });
    
                  </script></td>	
                
            </tr>
            <tr>
            <td><strong>Estado:</strong></td>
                <td><input type="text" id="estado" name="estado" value="<?php echo $row['cpestado_shipto'];?>" tabindex="5"/></td>
            <td><strong>Fecha de vuelo:</strong></td>
                <td><input type="text" id="f_calcdate" name="shipdt" value="<?php echo $row['ShipDT_traking'];?>" readonly size="20" />
                <script type="text/javascript">
//        function catcalc(cal) {
//            var date = cal.date;
//            var time = date.getTime()
//            // use the _other_ field
//            var field = document.getElementById("f_calcdate");
//            if (field == cal.params.inputField) {
//                field = document.getElementById("shipdt");
//                time -= Date.WEEK; // substract one week
//            } else {
//                time += Date.WEEK; // add one week
//            }
//            var date2 = new Date(time);
//            field.value = date2.print("%Y-%m-%d");
//        }
//        Calendar.setup({
//            inputField     :    "shipdt",   // id of the input field
//            ifFormat       :    "%Y-%m-%d ",       // format of the input field
//            showsTime      :    false,
//            timeFormat     :    "24",
//            onUpdate       :    catcalc
//        });
//    
                  </script></td>	
               
            </tr>
            <tr>
            <td><strong>Cod. Postal:</strong></td>
            <td><input type="text" id="zip" name="zip" value="<?php echo $row['cpzip_shipto'];?>" tabindex="6"/></td>
            <td><strong>SatDel:</strong></td>
                <td>
                <select  type="text" name="satdel" id="satdel">
                      <option selected="selected"><?php echo $row['satdel'];?></option>
                      <option>
                          <?php 
                                if($row['satdel']=='Y'){
                                    echo "N";
                                }else{
                                    echo "Y";
                                }			  
                            ?>
                      </option>  
                    </select></td>	
                
            </tr>
             <tr>
             <td><strong>Teléfono:</strong></td>
                <td><input type="text" id="telefono" name="telefono" value="<?php echo $row['cptelefono_shipto'];?>" tabindex="7"/></td>
             <td><strong>Cantidad:</strong></td>
                <td><input type="number" id="cantidad" name="cantidad" value="<?php echo $row['cpcantidad'];?>" min="1" tabindex="16" size="7" readonly/></td>     	
            </tr>
            <tr>
            <td><strong>e-Mail:</strong></td>
                <td><input type="text" id="mail" name="mail" value="<?php echo $row['mail'];?>" tabindex="10" size="30" /></td>
                 <td><strong>Producto:</strong></td>
                <td>
                <select type="text" name="item" id="item">
                      <?php 
                        //Consulto la bd para obtener solo los id de item existentes
                        $sql   = "SELECT id_item, prod_descripcion FROM tblproductos";
                        $query = mysqli_query($link, $sql);
                        ?>
                      <option selected="selected" value="<?php echo $row['cpitem'];?>"><?php echo $row['cpitem']."-".$row["prod_descripcion"];?></option>
                      <?php 
                            //Recorrer los iteme para mostrar
                            while($row1 = mysqli_fetch_array($query)){
                                if($row1['id_item']-$row['cpitem']==0){
                                }else{
                                    echo '<option value="'.$row1["id_item"].'">'.$row1["id_item"].'-'.$row1["prod_descripcion"].'</option>'; 
                                }
                            }
                        ?>                       
                    </select></td>
            </tr>
            <tr>
             <td><strong>Cobrar a:</strong></td>
                <td><input type="text"  id="soldto" name="soldto" value="<?php echo $row['soldto1'];?>" tabindex="8" size="30"/><font color="#FF0000">*</font></td>
                <td><strong>Finca:</strong></td>
                <td><input type="text" id="farm" name="farm" value="<?php echo $row['farm'];?>" tabindex="18" size="30" /></td>
            </tr>
            <tr>
            	<td height="38"><strong>Cobrar a2:</strong></td>
              <td><input type="text" id="soldto2" name="soldto2" value="<?php echo $row['soldto2'];?>" tabindex="9" size="30"/></td>
               <td><strong>Mensaje:</strong></td>
                <td><textarea type="text" id="mensaje" name="mensaje" tabindex="19" size="50" style="width:50;height:150;resize:none;" /><?php echo $row['cpmensaje'];?></textarea></td>   
            </tr>
            <tr>
            	<td><strong>Dirección del Cliente:</strong></td>
                <td><input type="text" id="billaddress" name="billaddress" value="<?php echo $row['address1'];?>" tabindex="9" size="30"/><font color="#FF0000">*</font></td>
                <td><strong>País de envío:</strong></td>
                <td><select type="text" name="ctry" id="ctry" tabindex="20">
                <option selected="selected"><?php echo $row['cppais_envio'];?></option>
                      <option><?php 
					  			if($row['cppais_envio']=='US'){
									echo "CA";
								}else{
									echo "US";
									}			  
					  ?>
                </select></td>
            </tr>
            <tr>
            	<td><strong>Dirección2:</strong></td>
                <td><input type="text" id="billaddress2" name="billaddress2" value="<?php echo $row['address2'];?>" tabindex="9" size="30"/></td>
                <td><strong>Origen:</strong></td>
                <td>
                   
                  <select type="text" name="origen" id="origen" tabindex="21" disabled="true">
                    <?php   /*
                      //Consulto la bd para obtener el codigo de la ciudad y el nombre de la ciudad origen
                      $sql1   = "SELECT * FROM tblciudad_origen where codpais_origen='".$row["cporigen"]."'";
                      $query1 = mysqli_query($link, $sql1);
                      echo '<option value=""></option>';
                      
                      while($row2 = mysqli_fetch_array($query1)){
                            echo '<option value="'.$row2["codpais_origen"].'">'.$row2["ciudad_origen"].'</option>';
                          }
                          */
                    ?>      
                    
                  </select>
                </td>    
            </tr>
            <tr>
            	<td><strong>Ciudad:</strong></td>
                <td><input type="text" id="billcity" name="billcity" value="<?php echo $row['city'];?>" tabindex="9" size="30"/></td>
                <td><strong>Precio Unitario:</strong></td>
                <td><input type="text" id="precio" name="precio" value="<?php echo $row['unitprice'];?>" tabindex="22" /></td>
            </tr>
            <tr>
                <td><strong>Estado:</strong></td>
                <td><input type="text" id="billstate" name="billstate" value="<?php echo $row['state'];?>" tabindex="9" size="30"/></td>            <td><strong>Cliente:</strong></td>
                <td><select type="text" name="cliente" id="cliente" tabindex="23" style="width:240px">
                      <?php
					  $cliente = explode("-",$row['vendor']); 
					  $SQL   = "SELECT codigo,empresa FROM tblcliente WHERE codigo LIKE '".$cliente[0]."%'";
					  $QUERY = mysqli_query($link, $SQL);
					  $ROW = mysqli_fetch_array($QUERY);
					  
					  //Consulto la bd para obtener solo los id de clientes existentes
					  $sql   = "SELECT codigo,empresa FROM tblcliente";
					  $query = mysqli_query($link, $sql);?>
					  	<option selected="selected" value="<?php echo $ROW['codigo'];?>"><?php echo $ROW['codigo']."-".$ROW["empresa"];?></option>
                      <?php 
					  	//Recorrer los iteme para mostrar
						while($row1 = mysqli_fetch_array($query)){
								if($row1['codigo']-$ROW['codigo']==0){
							    }else{
									echo '<option value="'.$row1["codigo"].'">'.$row1["codigo"].'-'.$row1["empresa"].'</option>'; 
								}
						}
						?>                      
                    </select></td>     
            </tr>
            <tr> 
            
            <td></td>
            <td colspan="2" align="right">
                <input name="Modificar" type="submit" value="Modificar" />
                <input name="Cancelar" type="submit" value="Cancelar" onClick="self.close();"/>
            </td>
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
 if(isset($_POST["Modificar"])){ 
     //datos de la tabla tblorden 
	 $orddate     = $_POST['orddate'];
	 $mensaje     = addslashes($_POST['mensaje']);
	 
	 //datos de la tabla tblshipto
	 $shipto1      = addslashes($_POST['shipto']);
	 $shipto2     = addslashes($_POST['shipto2']);
	 $direccion   = addslashes($_POST['direccion']);
	 $direccion2   = addslashes($_POST['direccion2']);
	 $ciudad      = addslashes($_POST['ciudad']);
	 $estado      = addslashes($_POST['estado']);
	 $zip         = $_POST['zip'];
	 $telefono    = $_POST['telefono'];
	 $mail        = $_POST['mail'];
	 
	 //datos de la tabla tblsoldto
	 $soldto1     = addslashes($_POST['soldto']);
	 $soldto2     = addslashes($_POST['soldto2']); 
	 $stphone     = $_POST['billphone'];
	 $adddress    = addslashes($_POST['billaddress']);
	 $adddress2   = addslashes($_POST['billaddress2']);
	 $city        = addslashes($_POST['billcity']);
	 $state       = addslashes($_POST['billstate']);
	 $billzip     = $_POST['billzip'];
	 $country     = addslashes($_POST['billcountry']);
	 
	 //datos de la tabla tbldetalleorden
	 $satdel      = $_POST['satdel'];
	 $cantidad    = $_POST['cantidad'];
	 $item        = $_POST['item'];
	 $farm        = addslashes($_POST['farm']);
	// $tracking    = $_POST['tracking'];
	 $ponumber    = $_POST['ponumber'];
	 $ctry        = $_POST['ctry'];
	 //$origen      = $_POST['origen'];
	 $precio      = $_POST['precio'];
	 $deliver     = $_POST['deliver'];
         echo $deliver;
	 $cliente     = $_POST['cliente']."-".$ctry;
	/* $deliver     = date_create($_POST['deliver']);
	 $deliver = date_format($deliver, 'd/m/Y');*/


      //Obteniendo el origen para obtener el pais de origen (codigo_ciudad-pais)
      $sqlorg4   = "SELECT origen FROM tblproductos WHERE tblproductos.id_item = '$item'";
      $query4 = mysqli_query($link, $sqlorg4);
      $row4   = mysqli_fetch_array($query4);
      $cporigen = $row4["origen"];
      $cporigen_city = explode("-", $cporigen);
      $cporigen = $cporigen_city[0];

      //Obteniendo el codigo del pais
      $sqlorg5   = "SELECT codpais_origen FROM tblciudad_origen WHERE tblciudad_origen.codciudad = '$cporigen'";
      $query5 = mysqli_query($link, $sqlorg5);
      $row5   = mysqli_fetch_array($query5);
      $origen = $row5["codpais_origen"];

/*echo $sqlorg4;
echo "<br>";
echo $sqlorg5;
echo $origen;
exit();
*/

	 //Obtener dia de la semana para saber cuanto restar al deliver para asignarle al shipdt
		  $fecha = date('l', strtotime($deliver));
		  //verifico que dia es para restarle los dias que son 
		  /*
		    Si el envio es de ECUADOR
		  */
                if($origen  == "EC"){ 
                    // Si es Maertes, Jueves o Viernes le resto 3 dias
                    if(strcmp($fecha,"Tuesday")==0 || strcmp($fecha,"Thursday")==0 || strcmp($fecha,"Friday")==0) {
                            $shipdt = strtotime ( '-3 day' , strtotime ( $deliver ) ) ;
                            $shipdt = date ( 'Y-m-j' , $shipdt );
                    }else{
                            //Si es otro dia de envio osea Miercoles
                            $shipdt = strtotime ( '-4 day' , strtotime ( $deliver ) ) ;
                            $shipdt = date ( 'Y-m-j' , $shipdt );
                    }					
                }else{
                    $shipdt = strtotime ( '-5 day' , strtotime ( $deliver ) ) ;
                    $shipdt = date ( 'Y-m-j' , $shipdt );  //TBLDETALLE_ORDEN    
                }
                $deliver = date_create($deliver);
                $deliver = date_format($deliver, 'Y-m-j'); 
	 	 
                
		 //Actualizar datos de la tabla orden
		 $sql="UPDATE tblorden set order_date ='".$orddate ."' , cpmensaje ='".$mensaje ."' where id_orden='".$idorden."'";		 
		 $modificado_orden= mysqli_query($link, $sql);
		 //echo "Orden<br>";
		 
		 //Actualizar datos de la tabla shipto
		 $sql="UPDATE tblshipto set shipto1 ='".$shipto1."' , shipto2 ='".$shipto2."', direccion ='".$direccion."' , direccion2 = '".$direccion2."', cpcuidad_shipto ='".$ciudad."', cpestado_shipto ='".$estado."' , cpzip_shipto ='".$zip."', cptelefono_shipto ='".$telefono."' , mail  ='".$mail ."' where id_shipto='".$idorden."'";	  
		 $modificado_ship= mysqli_query($link, $sql);
		 // echo "Shipto<br>";
		 //Actualizar datos de la tabla soldto
		 $sql="UPDATE tblsoldto set soldto1 ='".$soldto1."' , soldto2 ='".$soldto2."', cpstphone_soldto ='".$stphone."', address1 = '".$adddress."', address2 = '".$adddress2."', city ='".$city."', state = '".$state."', postalcode = '".$billzip."', billcountry = '".$country."' where id_soldto='".$idorden."'"; 
		 $modificado_sold= mysqli_query($link, $sql);
		 
                //Actualizar datos de la tabla soldto
                $sql="UPDATE tbldetalle_orden set ShipDT_traking ='".$shipdt."', delivery_traking ='".$deliver."', satdel ='".$satdel."', cpcantidad ='".$cantidad."', farm='".$farm."', cpitem='".$item."', Ponumber = '".$ponumber."', cporigen = '".$origen."', cppais_envio = '".$ctry."', unitprice = '".$precio."' where id_orden_detalle='".$id."'";
                
                $modificado_detalle= mysqli_query($link, $sql);
				 
		//Actualizar costo de ese Ponumber en la tabla de costo
		//Insertando o modificando la tabla de costos
                //Ahora se verifica si el Ponumber existe ya en la tabla de costo
                     $a = "SELECT * FROM tblcosto WHERE po = '".$ponumber."'";
                     $b = mysqli_query($link, $a) or die ("Error consultando el POnumber: ".$ponumber." en la tabla de costos");
                     $c = mysqli_num_rows($b);

                     //Obtengo la suma total de los costos asosciados al ponumber en cuestion
                     $d = "SELECT SUM(unitprice) FROM tbldetalle_orden WHERE Ponumber = '".$ponumber."' AND estado_orden='Active' AND tracking!='' AND status='Shipped'";
                     $e = mysqli_query($link, $d) or die ("Error sumando los costos del POnumber: ".$ponumber);

                     //Obtengo el costo total del ponumber
                     $f = mysqli_fetch_array($e);
                     $costo = $f[0];

                     //Se modifica el costo de este po en la tabla de costos
                     $x = "UPDATE tblcosto set costo = '$costo' WHERE po='".$ponumber."' ";
                     $modificado_costo = mysqli_query($link, $x) or die ("Error insertando el costo total del Ponumber: ".$row ['Ponumber']);	 
		 
		 if($modificado_orden && $modificado_ship && $modificado_sold && $modificado_detalle && $modificado_costo){
			//$_SESSION['PoNbr']= $PoNbr;
			
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
            $operacion = "Modificar Orden: ".$ponumber;
            
            $SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`) 
                                               VALUES ('$usuarioLog','$operacion','$fecha','$ip')";
            $consultaHist = mysqli_query($link, $SqlHistorico) or die ("Error actualizando la bitácora de usuarios");


            echo("<script> alert ('Orden modificada correctamente');
						   window.close();
						   window.opener.document.location='gestionarordenes.php?id=".$ponumber."';
				 </script>");
            mysqli_close($link);
        }else{
            echo("<script> alert (".mysqli_error().");</script>");
            mysqli_close($link);
        }
			 
 }
	
	  /* if(isset($_POST["Cancelar"])){  
		 //$_SESSION['PoNbr']= $PoNbr;
		 echo("<script>window.close();
					   window.opener.document.location='gestionarordenes.php?id=".$Ponumber."';
					   </script>");
					   mysqli_close($link);
	   }*/
  ?>

</body>
</html>