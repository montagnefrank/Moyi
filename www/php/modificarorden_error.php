<?php 

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////CONEXION A DB
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

$sql = "select * FROM tblerror INNER JOIN tblproductos ON tblproductos.id_item = tblerror.cpitem WHERE id_orden_detalle = '".$id."';";

$query = mysqli_query($link, $sql);
$row   = mysqli_fetch_array($query);

if (!$row) {
    echo("<script> alert ('Usted debe registrar el item asociado antes de continuar');
        window.location = 'ordenes_error.php';</script>");
}
else{
    $id = $row['id_orden_detalle'];
	//$idorden = $row['id_orden'];
	$Ponumber =  $row['Ponumber'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Insertar Orden</title>
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
                    mail: "Por favor inserte una direccion de correo válida",
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

            $('#Cancelar').delegate('','click change',function(){
                window.location = "ordenes_error.php";
                return false;
            });

                    // Check on typing if a required field is empty
            $('.required').keyup(function(){
                if ($(this).val() == '' || $(this).val() == 0 || $(this).val() == '0' ) 
                    $(this).css('border-color', '#F00');
                else 
                    $(this).css('border-color', '#999');
            });
        });
    </script>
</head>
<body>
<form id="form1" name="form1" method="post" novalidate="novalidate" action="" .error { color:red}>
    <table width="900" border="0" align="center">
         <tr>
        <td width="900" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Insertar Datos de la orden con Ponumber </font> <font color="#FF0000"><?php echo $row['Ponumber']?></font><font color="#FFFFFF"> y Custnumber </font><font color="#FF0000"><?php echo $row['Custnumber']?></font></strong></td>
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
                    <td width="279"><input type="text" id="ponumber" name="ponumber" value="<?php echo $row['Ponumber'];?>" size="30" autofocus="autofocus" tabindex="0"/><font color="#CC0000">*</font></td>
                   <td>&nbsp;</td>
                <td>&nbsp;</td></tr>
            	<tr>               
                    <td width="130"><strong>Nombre Receptor:</strong></td>
                    <td width="279"><input type="text" id="shipto" name="shipto" value="<?php echo $row['shipto1'];?>"  tabindex="1" size="30"/><font color="#CC0000">*</font></td>
                 <td><strong>Teléfono:</strong></td>
                    <td><input type="text" id="billphone" name="billphone" value="<?php echo $row['cpstphone_soldto'];?>" tabindex="11" /></td>   
                </tr>
                <tr>
                	<td><strong>Apellido Receptor:</strong></td>
                    <td><input type="text" id="shipto2" name="shipto2" value="<?php echo $row['shipto2'];?>" tabindex="2" size="30"/></td>
                 <td><strong>Cobrar a Cod. Postal:</strong></td>
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
                    <input name="orddate" type="text" id="orddate" readonly="readonly" size="20" value="<?php echo $row['order_date'];?>"/>
                    <script type="text/javascript">
            function catcalc(cal) {
                var date = cal.date;
                var time = date.getTime()
                // use the _other_ field
                var field = document.getElementById("f_calcdate");
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
                    time -= Date.WEEK; // substract one week
                } else {
                    time += Date.WEEK; // add one week
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
                <td><strong>Cantidad:</strong></td>
                    <td><script type="text/javascript">
            function catcalc(cal) {
                var date = cal.date;
                var time = date.getTime()
                // use the _other_ field
                var field = document.getElementById("f_calcdate");
                if (field == cal.params.inputField) {
                    field = document.getElementById("shipdt");
                    time -= Date.WEEK; // substract one week
                } else {
                    time += Date.WEEK; // add one week
                }
                var date2 = new Date(time);
                field.value = date2.print("%Y-%m-%d");
            }
            Calendar.setup({
                inputField     :    "shipdt",   // id of the input field
                ifFormat       :    "%Y-%m-%d ",       // format of the input field
                showsTime      :    false,
                timeFormat     :    "24",
                onUpdate       :    catcalc
            });
        
                      </script>
                    <input type="number" id="cantidad" name="cantidad" value="<?php echo $row['cpcantidad'];?>" min="1" tabindex="16" size="7" readonly/></td>	
                   
                </tr>
                <tr>
                <td><strong>Cod. Postal:</strong></td>
                    <td><input type="text" id="zip" name="zip" value="<?php echo $row['cpzip_shipto'];?>" tabindex="6"/></td>
                <td><strong>Producto:</strong></td>
                    <td><select type="text" name="item" id="item">
                      <?php 
    					//Consulto la bd para obtener solo los id de item existentes
    					$sql   = "SELECT id_item, prod_descripcion FROM tblproductos";
    					$query = mysqli_query($link, $sql);

                        //$prod_descripcion = $row['prod_descripcion'];
                        //echo $prod_descripcion;
                        //echo "yo";


    					  ?>
                      <option selected="selected" value="<?php echo $row['cpitem'];?>"><?php echo $row['cpitem']."-".$row["prod_descripcion"];?></option>
                      <?php 
    					  	//Recorrer los items para mostrar
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
                 <td><strong>Teléfono:</strong></td>
                    <td><input type="text" id="telefono" name="telefono" value="<?php echo $row['cptelefono_shipto'];?>" tabindex="7"/></td>
                 <td><strong>País de envío:</strong></td>
                    <td><select type="text" name="ctry" id="ctry" tabindex="20">
                      <option selected="selected"><?php echo $row['cppais_envio'];?></option>
                      <option>
                        <?php 
    					  			if($row['cppais_envio']=='US'){
    									echo "CA";
    								}else{
    									echo "US";
    									}			  
    					  ?>
                      </option>
                    </select></td>     	
                </tr>
                <tr>
                <td><strong>e-Mail:</strong></td>
                    <td><input type="text" id="mail" name="mail" value="<?php echo $row['mail'];?>" tabindex="10" size="30" /></td>
                     <td><strong>Precio Unitario:</strong></td>
                    <td><input type="text" id="precio" name="precio" value="<?php echo $row['unitprice'];?>" tabindex="22" /></td>
                </tr>
                <tr>
                 <td><strong>Cobrar a:</strong></td>
                    <td><input type="text"  id="soldto" name="soldto" value="<?php echo $row['soldto1'];?>" tabindex="8" size="30"/><font color="#FF0000">*</font></td>
                    <td><strong>Cliente:</strong></td>
                    <td><select type="text" name="cliente" id="cliente" tabindex="23" style="width:240px">
                      <?php
    					  //$cliente = explode("-",$row['vendor']); 
    					  $SQL   = "SELECT vendor from tblerror";
    					  $QUERY = mysqli_query($link, $SQL);
    					  $ROW = mysqli_fetch_array($QUERY);
    					  
    					  //Consulto la bd para obtener solo los id de clientes existentes
    					  $sql   = "SELECT codigo,empresa FROM tblcliente";
    					  $query = mysqli_query($link, $sql);?>
                      <option selected="selected" value="<?php echo $ROW['vendor'];?>"><?php echo $ROW['vendor'];?></option>
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
                	<td height="38"><strong>Cobrar a2:</strong></td>
                  <td><input type="text" id="soldto2" name="soldto2" value="<?php echo $row['soldto2'];?>" tabindex="9" size="30"/></td>
                   <td><strong>Mensaje:</strong></td>
                    <td><textarea type="text" id="mensaje" name="mensaje" tabindex="19" size="50" style="width:50;height:150;resize:none;" /><?php echo $row['cpmensaje'];?></textarea></td>   
                </tr>
                <tr>
                	<td><strong>Dirección del Cliente:</strong></td>
                    <td><input type="text" id="billaddress" name="billaddress" value="<?php echo $row['address1'];?>" tabindex="9" size="30"/><font color="#FF0000">*</font></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                	<td><strong>Dirección2:</strong></td>
                    <td><input type="text" id="billaddress2" name="billaddress2" value="<?php echo $row['address2'];?>" tabindex="9" size="30"/></td>
                <td>&nbsp;</td>
                    <td>&nbsp;</td>    
                </tr>
                <tr>
                	<td><strong>Ciudad:</strong></td>
                    <td><input type="text" id="billcity" name="billcity" value="<?php echo $row['city'];?>" tabindex="9" size="30"/></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><strong>Estado:</strong></td>
                    <td><input type="text" id="billstate" name="billstate" value="<?php echo $row['state'];?>" tabindex="9" size="30"/></td>            <td>&nbsp;</td>
                    <td>&nbsp;</td>     
                </tr>
                <tr>            
                <td></td>
                <td colspan="2" align="right"><input name="Modificar" type="submit" value="Insertar Orden" />              <input name="Cancelar" id="Cancelar" type="submit" value="Cancelar"/></td>
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
    //echo "clic en insertar";
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
	$satdel      = '';
	$cpcantidad  = $_POST['cantidad'];
	$item        = $_POST['item'];
	$farm        = '';
	$tracking    = '';
	$ponumber    = $_POST['ponumber'];
	$ctry        = $_POST['ctry'];
	$origen      = '';
	$precio      = $_POST['precio'];
	$deliver     = $_POST['deliver'];
	$cliente     = $_POST['cliente']."-".$ctry;

    $cpmoneda = 'USD';
    //$cporigen = 'EC';
    $cpUOM = 'BOX';
    $estado_orden = 'Active';
    $descargada = 'not downloaded';
    $coldroom = 'No';
    $status = 'New';
    $reenvio = 'No';
    $codigo = 0;
    $user = '';
    $eBing = 0;
    
    /*$delivery = date_create($_POST['deliver']);
	$delivery = date_format($delivery, 'd/m/Y');  */
 
	//Obtener dia de la semana para saber cuanto restar al deliver para asignarle al shipdt
    $fecha = date('l', strtotime($deliver));

    $cpitem = $row['cpitem'];

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
  $cporigen = $row5["codpais_origen"];

/*echo $cporigen;
echo $sqlorg4;
echo "<br>";
echo $sqlorg5;
exit();*/

    //verifico que dia es para restarle los dias que son 
    //
    //Si el envio es de ECUADOR
    //
    if($cporigen == "EC"){ 
  		// Si es Martes, Jueves o Viernes le resto 3 dias
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
	 

    // Formateando la fecha para shipdttrakin

    $deliver = date_create($_POST['deliver']);
    $deliver = date_format($deliver, 'Y-m-j');

    //echo "deliver: ".$deliver;


    ////--- //Verifico que el item del producto este registrado en la bd ---////

    /*  $sql = "select *
                FROM
                tblorden
                INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
                INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
                INNER JOIN tbldirector ON tblorden.id_orden = tbldirector.id_director
                INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
                INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
                WHERE id_orden_detalle = '".$id."';";     */
        
    
    $sql = "select *
            FROM
            tblerror
            WHERE id_orden_detalle = '".$id."';";
    
    $query = mysqli_query($link, $sql);
    $row   = mysqli_fetch_array($query);
    $id = $row['id_orden_detalle'];
    //$idorden = $row['id_orden'];
    $Ponumber =  $row['Ponumber'];
    $poline = $row['poline'];

    $nombre_compania = $row['nombre_compania'];
    
    $Custnumber = $row['Custnumber'];
    
    $ups = $row['ups'];
    $billmail = $row['billmail'];


    $merchantSKU = $row['merchantSKU'];
    $shippingWeight = $row['shippingWeight'];
    $weightUnit = $row['weightUnit'];
    $merchantLineNumber = $row['merchantLineNumber'];


    //echo $cpitem;

    //echo $item;  // Este es el que llega por POST, el que hay que agregar a la base


    //Comprobar que el producto esté registrado//
    $errormsj ='';
    $query = "select * from tblproductos where id_item= '".$item."';"; 
    //echo $query;
    $sql     = mysqli_query($link, $query) or die (mysqli_error());//selecciona los registros iguales aItem
    $ray = mysqli_num_rows($sql);
    if($ray == 0 ){ //Si el item no esta registrado uso su detalles
        $errormsj = 'El producto asociado al item '.$cpitem.' no está registrado, por favor regístrelo antes de continuar';

        exit("<font color='red'>El producto asociado al item ". $cpitem." no está registrado, por favor regístrelo antes de continuar.</font>");
        echo "<br>";

    }


    ////----- Cuando finalmente se pueda insertar a la tabla detalle_orden -----/////
    //Hacer un query a la orden de la tabla de errores para obtener los datos que no estan en este formulario


                ///---------INSERCION A LA BASE DE DATOS DEL lineItem------////

    for ($l=0; $l < $cpcantidad; $l++) { 

        //Insertar los datos de tblorden
        if($mensaje == ''){
            $mensaje = "To-Blank Info   ::From- Blank Info   ::Blank .Info";
        }
        else{
            $mensaje = addslashes($mensaje);
        }

        if($l == 0) {// es la primera orden
            $sql = "Insert INTO tblorden(nombre_compania,cpmensaje,order_date)VALUES ('$nombre_compania','$mensaje','$orddate')";  
            mysqli_query($link, $sql) or die (mysqli_error()); //OK
            $modificado_orden= mysqli_query($link, $sql);
                                
            $id_order = mysqli_insert_id($link);  //Indice automatico
            //echo "este es el id order ".$id_order;
            
            //Insertar los datos de Shipto
            $sql1 = "Insert INTO `tblshipto`(`id_shipto`,`shipto1`,`shipto2`,`direccion`,`direccion2`,`cpestado_shipto`,`cpcuidad_shipto`,`cptelefono_shipto`,`cpzip_shipto`,`mail`,`shipcountry`)VALUES ('$id_order','$shipto1','$shipto2','$direccion','$direccion2','$estado','$ciudad','$telefono','$zip','$mail','$ctry')";
            mysqli_query($link, $sql1) or die (mysqli_error()); //OK
            $modificado_ship= mysqli_query($link, $sql);
                                        
            //Insertar los datos de BillTo (Soldto)
            $sql2 = "Insert INTO `tblsoldto`(`id_soldto`,`soldto1`,`soldto2`,`cpstphone_soldto`,`address1`,`address2`,`city`,`state`,`postalcode`,`billcountry`,`billmail`)VALUES ('$id_order','$soldto1','$soldto2','$stphone','$adddress','$adddress2','$city','$state','$billzip','$country','$billmail')";
            mysqli_query($link, $sql2) or die (mysqli_error()); //ok
            $modificado_sold= mysqli_query($link, $sql);
                                            
            //Insertar los datos de tbldirector
            $sql5 = "Insert INTO `tbldirector`(`id_director`)VALUES ('$id_order')";
            mysqli_query($link, $sql5) or die (mysqli_error()); //ok
                                                
            //Inserto los detalles del primer producto de la orden
            $cpcantidadsingle = 1;
            $sql3 = "Insert INTO `tbldetalle_orden`(`id_detalleorden`,`Custnumber`,`Ponumber`,`cpitem`,`cpcantidad`,`farm`,`satdel`,`cppais_envio`,`cpmoneda`,`cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`tracking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`reenvio`,`poline`,`unitprice`,`ups`,`codigo`,`vendor`,`merchantSKU`,`shippingWeight`,`weightUnit`,`merchantLineNumber`)VALUES ('$id_order','$Custnumber','$Ponumber','$item','$cpcantidadsingle','$farm','$satdel','$ctry','$cpmoneda','$cporigen','$cpUOM','$deliver','$shipdt','$tracking','$estado_orden','$descargada','$user','$eBing','$coldroom','$status','$reenvio','$poline','$precio','$ups','$codigo','$cliente','$merchantSKU','$shippingWeight','$weightUnit','$merchantLineNumber')";
            mysqli_query($link, $sql3)or die (mysqli_error());
            $modificado_detalle= mysqli_query($link, $sql);
        }
        else{
            //Inserto los detalles del resto de los productos de la orden
            $cpcantidadsingle = 1;
            $sql3 = "Insert INTO `tbldetalle_orden`(`id_detalleorden`,`Custnumber`,`Ponumber`,`cpitem`,`cpcantidad`,`farm`,`satdel`,`cppais_envio`,`cpmoneda`,`cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`tracking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`reenvio`,`poline`,`unitprice`,`ups`,`codigo`,`vendor`,`merchantSKU`,`shippingWeight`,`weightUnit`,`merchantLineNumber`)VALUES ('$id_order','$Custnumber','$Ponumber','$item','$cpcantidadsingle','$farm','$satdel','$ctry','$cpmoneda','$cporigen','$cpUOM','$deliver','$shipdt','$tracking','$estado_orden','$descargada','$user','$eBing','$coldroom','$status','$reenvio','$poline','$precio','$ups','$codigo','$cliente','$merchantSKU','$shippingWeight','$weightUnit','$merchantLineNumber')";
            mysqli_query($link, $sql3)or die (mysqli_error());
            $modificado_detalle= mysqli_query($link, $sql);
        }
    }
    
    //Javascript para comprobar


    if($modificado_orden && $modificado_ship && $modificado_sold && $modificado_detalle){      
    //$_SESSION['PoNbr']= $PoNbr;
        //Eliminar la orden una vez agregada a detalle_orden
        $sql_del = "delete FROM tblerror WHERE id_orden_detalle = '".$id."';";
        mysqli_query($link, $sql_del) or die (mysqli_error()); //ok

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
    $operacion = "Arreglar Orden: ".$Ponumber;
    $SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`) 
                                       VALUES ('$usuarioLog','$operacion','$fecha','$ip')";
    $consultaHist = mysqli_query($link, $SqlHistorico) or die ("Error actualizando la bitácora de usuarios");

    //Redirección
    echo("<script> alert ('Orden modificada correctamente');
                <!--    //window.close();
                //window.opener.document.location='ordenes_error.php';  //-->
                location.href = 'ordenes_error.php';
        </script>");

    }
    else{
        echo "error";
        echo("<script> alert (".mysqli_error().");</script>");
    }

} //fin del if isset
    
if(isset($_POST["Cancelar"])){  
    Helper::redirect('ordenes_error.php');
}


// Esto duplica innecesariamente las filas en las tablas shipto, billto y ordenes


    //Para la orden obtener la cantidad para iterar esas veces ($cpcantidad)
    /*
    for ($l=0; $l < $cpcantidad; $l++) { 

        //Insertar los datos de tblorden
        if($mensaje == ''){
            $mensaje = "To-Blank Info   ::From- Blank Info   ::Blank .Info";
        }
        else{
            $mensaje = addslashes($mensaje);
        }

        $sql = "Insert INTO tblorden(nombre_compania,cpmensaje,order_date)VALUES ('$nombre_compania','$mensaje','$orddate')";  
        mysqli_query($link, $sql) or die (mysqli_error()); //OK
        $modificado_orden= mysqli_query($link, $sql);     //Variable para controlar si se inserto
                            
        $id_order = mysqli_insert_id();  //Indice automatico
        //echo "este es el id order ".$id_order;
        
        
        //Insertar los datos de Shipto
        $sql1 = "Insert INTO `tblshipto`(`id_shipto`,`shipto1`,`shipto2`,`direccion`,`direccion2`,`cpestado_shipto`,`cpcuidad_shipto`,`cptelefono_shipto`,`cpzip_shipto`,`mail`,`shipcountry`)VALUES ('$id_order','$shipto1','$shipto2','$direccion','$direccion2','$estado','$ciudad','$telefono','$zip','$mail','$ctry')";
        mysqli_query($link, $sql1) or die (mysqli_error()); //OK                                                                                                                                 
        $modificado_ship= mysqli_query($link, $sql);     

        //Insertar los datos de BillTo (Soldto)
        $sql2 = "Insert INTO `tblsoldto`(`id_soldto`,`soldto1`,`soldto2`,`cpstphone_soldto`,`address1`,`address2`,`city`,`state`,`postalcode`,`billcountry`,`billmail`)VALUES ('$id_order','$soldto1','$soldto2','$stphone','$adddress','$adddress2','$city','$state','$billzip','$country','$billmail')";
        mysqli_query($link, $sql2) or die (mysqli_error()); //ok
        $modificado_sold= mysqli_query($link, $sql);       

        //Insertar los datos de tbldirector
        $sql5 = "Insert INTO `tbldirector`(`id_director`)VALUES ('$id_order')";
        mysqli_query($link, $sql5) or die (mysqli_error()); //ok
                                            
        //Inserto los detalles del primer producto de la orden
        $cpcantidadsingle = 1;
        $sql3 = "Insert INTO `tbldetalle_orden`(`id_detalleorden`,`Custnumber`,`Ponumber`,`cpitem`,`cpcantidad`,`farm`,`satdel`,`cppais_envio`,`cpmoneda`,`cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`tracking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`reenvio`,`poline`,`unitprice`,`ups`,`codigo`,`vendor`)VALUES ('$id_order','$Custnumber','$Ponumber','$cpitem','$cpcantidadsingle','$farm','$satdel','$ctry','$cpmoneda','$cporigen','$cpUOM','$deliver','$shipdt','$tracking','$estado_orden','$descargada','$user','$eBing','$coldroom','$status','$reenvio','$poline','$precio','$ups','$codigo','$cliente')";
        mysqli_query($link, $sql3)or die (mysqli_error());
        $modificado_detalle= mysqli_query($link, $sql);
    }
    */

?>
</body>
</html>