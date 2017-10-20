<?php 

//ini_set('display_errors', 'On');
//ini_set('display_errors', 1);

session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");

$user     =  $_SESSION["login"];
$passwd   =  $_SESSION["passwd"];
$rol      =  $_SESSION["rol"];

$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());
$id_order = 0;
$error = $_GET['error'];

$codigo = $_GET['codigo'];  

if($codigo) {

  $sql = "select * from tbldestinos inner join tblshipto_venta on tbldestinos.iddestino = tblshipto_venta.iddestino where tbldestinos.iddestino = '".$codigo."';"; 

  $query = mysql_query($sql,$link);
  $row9   = mysql_fetch_array($query);

  //echo $sql;
  //echo $row9['soldto1'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Modificar Destino</title>
    <link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
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
        	   nombredestino: "required",
              shipto: "required",
              //shipto2: "required",
              direccion: "required",
              //direccion2: "required",
              ciudad: "required",
              estado: "required",
              pais: "required",
              zip: "required",
              //telefono: "required",
              mail: {
                //required: true,
                email: true
              }
        },
        
        // Specify the validation error messages
        messages: {
        	  nombredestino: "Por favor inserte el Nombre del destino",
              shipto: "Por favor inserte el shipto",
              //shipto2: "Por favor inserte el shipto2",
              direccion: "Por favor inserte la Dirección",
              //direccion2: "Por favor inserte la Dirección",
              ciudad: "Por favor inserte la Ciudad",
              estado: "Por favor inserte el Estado",
              pais: "Por favor inserte el País",
              zip:"Por favor inserte el Código Postal",
              telefono:"Por favor inserte un número de Teléfono",
              mail: "Por favor inserte una dirección de correo válida"
        },
       
        submitHandler: function(form) {
            form.submit();
        }


      });
    });
  </script>
</head>
<body>
<form id="form1" name="form1" method="post" novalidate="novalidate" action="" .error { color:red}>
<table width="500" border="0" align="center">
     <tr>
    <td width="500" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Modificar destino</font> <font color="#FF0000"><?php echo $row['empresa']?></font></strong></td>
  </tr>
</table>
<table width="500" border="0" align="center">
 <tr height="20"><td></td></tr>
     <tr>
     	<td>
        <table border="0" align="center">
        	<tr>
				<td width="79"><strong>Destino:</strong></td>
                <td width="150"><input type="text" id="nombredestino" name="nombredestino" value="<?php echo $row9['destino'];?>"  tabindex="1" size="30"/><font color="#CC0000">*</font></td>
            </tr>
        	<tr>
				<td width="79"><strong>Enviar a:</strong></td>
                <td width="150"><input type="text" id="shipto" name="shipto" value="<?php echo $row9['shipto1'];?>"  tabindex="1" size="30"/><font color="#CC0000">*</font></td>
            </tr>
            <tr>
            	<td><strong>Enviar a2:</strong></td>
                <td><input type="text" id="shipto2" name="shipto2" value="<?php echo $row9['shipto2'];?>" tabindex="2" size="30"/></td>
            </tr>
            <tr>
				<td><strong>Enviar a la dirección:</strong></td>
                <td><input type="text" id="direccion" name="direccion" value="<?php echo $row9['direccion'];?>" tabindex="3" size="30" /><font color="#FF0000">*</font></td>
            </tr>
            <tr>
            	<td><strong>Enviar a la dirección2:</strong></td>
                <td><input type="text" id="direccion2" name="direccion2" value="<?php echo $row9['direccion2'];?>" tabindex="3" size="30" /></td>
            </tr>
            <tr>
            	<td><strong>Enviar a la ciudad:</strong></td>
                <td><input type="text" id="ciudad" name="ciudad" value="<?php echo $row9['cpcuidad_shipto'];?>" tabindex="4"/></td>
            </tr>
            <tr>
            	<td><strong>Enviar al estado</strong></td>
                <td><input type="text" id="estado" name="estado" value="<?php echo $row9['cpestado_shipto'];?>" /></td>
            </tr>
            <tr>
            	<td><strong>Enviar a pais:</strong></td>
                <td>
                  <select type="text" name="pais" id="pais" tabindex="20">
                    <?php 
                      //Consulto la bd para obtener solo los id de item existentes
                      $sql   = "SELECT * FROM tblpaises_destino";
                      $query = mysql_query($sql,$link);
                        //Recorrer los iteme para mostrar
                      echo '<option value=""></option>'; 
                      while($row1 = mysql_fetch_array($query)){
                            echo '<option value="'.$row1["codpais"].'">'.$row1["pais_dest"].'</option>'; 
                          }
                    ?>    
                </select>

                <font color="#CC0000">*</font>
                </td>
            </tr>
            <tr>
            	<td><strong>Enviar a Cod. Postal:</strong></td>
                <td><input type="text" id="zip" name="zip" value="<?php echo $row9['cpzip_shipto'];?>" tabindex="6"/></td>
            </tr>
             <tr>
             	<td><strong>Enviar a Contacto:</strong></td>
                <td><input type="text" id="telefono" name="telefono" value="<?php echo $row9['cptelefono_shipto'];?>" tabindex="7"/></td>
            </tr>
            <tr>
            	<td><strong>Enviar a e-Mail:</strong></td>
                <td><input type="text" id="mail" name="mail" value="<?php echo $row9['mail'];?>" tabindex="10" size="30" /></td>
            </tr>
            <tr>
            <td><input name="Registrar" type="submit" value="Modificar" /></td>
            <td><input name="Cancelar" type="submit" value="Cancelar" onclick="self.close();" /></td>
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
if(isset($_POST["Registrar"])){  
    /*echo("<script> window.close()</script>");*/
  /*  $codigo       = $_POST['codigo'];
    $empresa      = addslashes($_POST['empresa']);
    $direccion     = addslashes($_POST['direccion']);
    $direccion2     = addslashes($_POST['direccion2']);
    $ciudad     = addslashes($_POST['ciudad']);
    $estado  = addslashes($_POST['estado']);
    $zip  = addslashes($_POST['zip']);
    $pais  = addslashes($_POST['pais']);
    $telefono  = $_POST['telefono'];
    $vendedor  = addslashes($_POST['vendedor']);
    $mail  = $_POST['mail'];
*/

  $nombredestino = addslashes($_POST['nombredestino']);
	$shipto = addslashes($_POST['shipto']);
	$shipto2 = addslashes($_POST['shipto2']);
	$direccion = addslashes($_POST['direccion']);
	$direccion2 = addslashes($_POST['direccion2']);
	$ciudad = addslashes($_POST['ciudad']);
	$estado = addslashes($_POST['estado']);
	$zip = $_POST['zip'];
	$telefono = $_POST['telefono'];
	$mail = $_POST['mail'];
	$shipcountry = $_POST['pais'];


    //echo $nombredestino,$shipto,$shipto2,$direccion,$direccion2,$ciudad,$estado,$zip,$telefono,$mail,$shipcountry;
	//exit();



    //$sql="INSERT INTO tblcliente (`codigo`, `empresa`, `direccion`, `direccion2`,`ciudad`, `estado`, `zip`, `pais`, `telefono`, `vendedor`, `mail`) VALUES ('$codigo','$empresa','$direccion','$direccion2','$ciudad','$estado','$zip','$pais','$telefono','$vendedor','$mail')";
     
//hay que insertar en tbldestinos y en tblsoldto

		
    //***************** Insertando en las diferentes tablas para regitrar la orden ****************************************//
    //Insertando los datos de la tabla orden
    /*$sql="INSERT INTO tblorden (`nombre_compania`,`cpmensaje`,`order_date`) VALUES ('eblooms','$mensaje','$orddate')"; 
    $creado_orden= mysql_query($sql,$link);
    $id_order = mysql_insert_id();*/

    //Insertar los datos de Shipto

    /*$sql3 = "Insert INTO `tbldestinos`(`codcliente`,`destino`) VALUES ('$codcliente','$nombredestino')";
    $creado_destinos = mysql_query($sql3,$link);
	  $iddestino = mysql_insert_id();

    $sql1 = "Insert INTO `tblshipto_venta`(`shipto1`,`shipto2`,`direccion`,`cpestado_shipto`,`cpcuidad_shipto`,`cptelefono_shipto`,`cpzip_shipto`,`mail`,`direccion2`,`shipcountry`,`iddestino`)VALUES ('$shipto','$shipto2','$direccion','$estado','$ciudad','$telefono','$zip','$mail','$direccion2','$shipcountry','$iddestino')";
    $creado_ship = mysql_query($sql1,$link);  */
    
    $sql3 = "UPDATE tbldestinos SET destino = '".$nombredestino."' where iddestino='".$codigo."'";
    $creado_destinos = mysql_query($sql3,$link);
    $iddestino = mysql_insert_id();

    $sql1 = "UPDATE tblshipto_venta SET shipto1 = '".$shipto."', shipto2 = '".$shipto2."', direccion = '".$direccion."', cpestado_shipto ='".$estado."', cpcuidad_shipto ='".$ciudad."', cptelefono_shipto ='".$telefono."',cpzip_shipto ='".$zip."', mail = '".$mail."', direccion2 = '".$direccion2."', shipcountry = '".$shipcountry."' where iddestino='".$codigo."'";
    $creado_ship = mysql_query($sql1,$link);  

    //Insertar los datos de Soldto
    /*
    $sql2 = "Insert INTO `tblsoldto`(`id_soldto`,`soldto1`,`soldto2`,`cpstphone_soldto`,`address1`,`address2`,`city`,`state`,`postalcode`,`billcountry`)VALUES ('$id_order','$soldto','$soldto2','$stphone','$adddress','$adddress2','$city','$state','$billzip','$country')";
    $creado_sold = mysql_query($sql2,$link);
*/


/*
    //Insertar los datos de tbldirector
    $sql5 = "Insert INTO `tbldirector`(`id_director`)VALUES ('$id_order')";
    $creado_director= mysql_query($sql5,$link);
    	
    //Inserto los detalles del primer producto de la orden
    $sql3 = "Insert INTO `tbldetalle_orden`(`id_detalleorden`,`cpcantidad`,`Ponumber`,`Custnumber`,`cpitem`,`satdel`,`farm`,`cppais_envio`,`cpmoneda`,`cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`poline`,`unitprice`,`ups`,`tracking`,`vendor`)VALUES ('$id_order','1','$ponumber','$custnumber','$item','$satdel','$farm','$ctry','USD','$origin','BOX','$deliver','$shipdt','Active','not donwloaded','','0','No','New','0','$precio','','','$cliente')";																
    $creado_detalle = mysql_query($sql3,$link); 

*/

    /*$insertado= mysql_query($sql,$link);
    if($insertado){
    	echo("<script> alert ('Cliente insertado correctamente');
    				   window.close();	
    				   window.opener.document.location='crearClientes.php';
    		 </script>");
    }else{
    	echo("<script> alert (".mysql_error().");</script>");
    }*/

    if($creado_destinos&&$creado_ship){
    echo("<script> alert ('Destino modificado correctamente');
       window.close();	
       window.opener.document.location='gestionardestinos.php';
          </script>");
   }
   else{
      echo("<script> alert (".mysql_error().");</script>");
   }

}  //Fin del isset Registrar


    /*if(isset($_POST["Cancelar"])){  
     echo("<script> window.close()</script>");
    } */ 
?>
</body>
</html>
