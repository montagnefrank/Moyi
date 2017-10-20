<?php 
session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("codigounico.php");

$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());

$nropedido = $_GET['codigo'];

$sql = "SELECT SUM(solicitado) as cantidad, finca, item, fecha, fecha_tentativa, precio, destino, agencia FROM tbletiquetasxfinca WHERE nropedido='".$nropedido."'";
$query = mysql_query($sql,$link) or die ("Error consultando el pedido");
$row   = mysql_fetch_array($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Nuevo pedido</title>
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
            finca: "required",
            item: "required",
            cantidad: "required",
            fecha: "required",
            ftentativa: "required",
            precio: "required",
            destino: "required",
            agencia: "required"
        },
        
        // Specify the validation error messages
        messages: {
            finca:"Por favor selecccione una Finca",
            item: "Por favor inserte el Producto",
            cantidad: "Por favor inserte una Cantidad",
            fecha: "Por favor inserte una fecha",
            ftentativa:"Por favor inserte una fecha",
            precio:"Por favor inserte un Precio",
            destino:"Por favor seleccione un destino",
            agencia:"Por favor selecccione una Agencia"
        },
       
        submitHandler: function(form) {
            form.submit();
        }

        /*$('#Cancelar').delegate('','click change',function(){
            window.location = "gestionarordenes.php";
        return false;
        });*/
      });
    });
  </script>
</head>
<body>
<form id="form1" name="form1" method="post" novalidate="novalidate" action="" .error { color:red}>
<table width="500" border="0" align="center">
     <tr>
    <td width="747" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Registrar pedido a una finca</font></strong></td>
  </tr>
</table>
<table width="500" border="0" align="center">
 <tr height="20"><td></td></tr>
     <tr>
     	<td>
        <table border="0" align="center">
            <tr>
            	<td><strong>Finca:</strong></td>
                <td><select type="text" name="finca" id="finca">
                      <?php 
					  //Consulto la bd para obtener solo los nombres  de las fincas existentes
					  $sql   = "SELECT nombre FROM tblfinca";
					  $query = mysql_query($sql,$link);
					  echo "<option selected='selected'>".$row['finca']."</option>";
					  	//Recorrer los iteme para mostrar
						while($row1 = mysql_fetch_array($query)){
									if(strcmp($row1['nombre'],$row['finca'])!=0){
										echo '<option value="'.$row1["nombre"].'">'.$row1["nombre"].'</option>'; 
								}
						}
					  ?>                       
                    </select>
                <strong>*</strong></td>
            </tr>
            <tr>
            	<td><strong>Producto:</strong></td>
                <td><select type="text" name="item" id="item">
                      <?php 
					  //Consulto la bd para obtener solo los id de item existentes
					  $sql   = "SELECT id_item FROM tblproductos";
					  $query = mysql_query($sql,$link);
					  ?>
                      <option selected="selected"><?php echo $row['item'];?></option>
                      <?php 
					  	//Recorrer los iteme para mostrar
						while($row1 = mysql_fetch_array($query)){
								if($row1['id_item']-$row['cpitem']==0){
							    }else{
									echo '<option>'.$row1["id_item"].'</option>'; 
								}
						}
					  ?>                       
                    </select>
                <strong>*</strong></td>
            </tr>
            <tr>
            	<td><strong>Cantidad:</strong></td>
                <td><input type="text" id="cantidad" name="cantidad" value="<?php echo $row['cantidad'];?>" disabled="disabled"/>
                <strong>*</strong></td>
            </tr>
            <tr>
            <td><strong>Salida de Finca:</strong></td>
                <td><input type="text" id="fecha" name="fecha" value="<?php echo $row['fecha'];?>" readonly="readonly"/>
                <strong>*</strong>
            <script type="text/javascript">
        function catcalc(cal) {
            var date = cal.date;
            var time = date.getTime()
            // use the _other_ field
            var field = document.getElementById("f_calcdate");
            if (field == cal.params.inputField) {
                field = document.getElementById("fecha");
                time -= Date.WEEK; // substract one week
            } else {
                time += Date.WEEK; // add one week
            }
            var date2 = new Date(time);
            field.value = date2.print("%Y-%m-%d");
        }
        Calendar.setup({
            inputField     :    "fecha",   // id of the input field
            ifFormat       :    "%Y-%m-%d ",       // format of the input field
            showsTime      :    false,
            timeFormat     :    "24",
            onUpdate       :    catcalc
        });
    
                  </script>
                  </td>
            </tr>
            <tr>
            <td><strong>Fecha Tentativa de Vuelo :</strong></td>
                <td><input type="text" id="ftentativa" name="ftentativa" value="<?php echo $row['fecha_tentativa'];?>" readonly="readonly"/>
                <strong>*</strong>
            <script type="text/javascript">
        function catcalc(cal) {
            var date = cal.date;
            var time = date.getTime()
            // use the _other_ field
            var field = document.getElementById("f_calcdate");
            if (field == cal.params.inputField) {
                field = document.getElementById("ftentativa");
                time -= Date.WEEK; // substract one week
            } else {
                time += Date.WEEK; // add one week
            }
            var date2 = new Date(time);
            field.value = date2.print("%Y-%m-%d");
        }
        Calendar.setup({
            inputField     :    "ftentativa",   // id of the input field
            ifFormat       :    "%Y-%m-%d ",       // format of the input field
            showsTime      :    false,
            timeFormat     :    "24",
            onUpdate       :    catcalc
        });
    
                  </script>
                  </td>
            </tr>
            <tr>
            	<td><strong>Precio de Compra:</strong></td>
                <td><input type="text" id="precio" name="precio" value="<?php echo $row['precio'];?>" />
                <strong>*</strong></td>
            </tr>
            <tr>
            	<td><strong>Destino:</strong></td>
                <td><select id="destino" name="destino">                      
                <option selected="selected"><?php echo $row['destino'];?></option>
                      <option><?php 
					  			if($row['destino']=='US'){
									echo "CA";
								}else{
									echo "US";
									}			  
					  ?>
                </select>
                <strong>*</strong></td>
            </tr>
            <tr>
            	<td><strong>Agencia de Carga:</strong></td>
                <td>
                <select id="agencia" name="agencia">
                     <?php 
                        //Consulto la bd para obtener solo los id de item existentes
                        $sql   = "SELECT * FROM tblagencia";
                        $query = mysql_query($sql,$link);
			while($row1 = mysql_fetch_array($query)){
                       ?>
                          <option <?php echo $row['agencia']==$row1['nombre_agencia'] ? 'selected="selected"' : ''; ?> value="<?php echo $row1['nombre_agencia'] ?>"><?php echo $row1['nombre_agencia'] ?></option>
                     <?php   
                        }
                    ?>
                    
                    </select>

                <strong>*</strong></td>
            </tr>
            <tr>
            <td align="right"><input name="Registrar" type="submit" value="Registrar" /></td>
            <td><input name="Cancelar" type="submit" value="Cancelar" onClick="self.close();"/></td>
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
 $finca     = $_POST['finca'];
 $item      = $_POST['item'];
 $fecha     = $_POST['fecha'];
 $ftentativ = $_POST['ftentativa'];
 $precio    = $_POST['precio'];
 $destino   = $_POST['destino'];
 $agencia   = $_POST['agencia'];
 
 //Selecione los codigos de los pedidos para modificar
 $sentencia = "SELECT codigo FROM tbletiquetasxfinca WHERE nropedido = '$nropedido'";
 $consulta   = mysql_query($sentencia,$link) or die ("Error consultando la solicitud para modifcar");
 
  while($row = mysql_fetch_array($consulta)){

    //Modificar el pedido uno por uno hasta que este la cantidad 	  
    $sql="UPDATE tbletiquetasxfinca set finca = '".$finca."', item = '".$item."', fecha = '".$fecha."', fecha_tentativa='".$ftentativ."', precio = '".$precio."', destino = '".$destino."', agencia = '".$agencia."' WHERE codigo = '".$row['codigo']."'";
    $insertado= mysql_query($sql,$link) or die ("Error modiifcando el pedido");
     $j++;

    echo("<script> alert ('Pedido modidificado correctamente');
    			   window.close();					   
    			   window.opener.document.location='listado_pedido.php?finca=".$finca."';
    	 </script>");
	}
}
  
/*if(isset($_POST["Cancelar"])){  
 echo("<script> window.close()</script>");
} */ 
?>
</body>
</html>