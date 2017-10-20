<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

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

//para seleccionar el id de usuario registrado 
$user     =  $_SESSION["login"];
$sqluser = "SELECT id_usuario FROM tblusuario WHERE cpuser = '".$user."'";
$queryuser = mysqli_query($link, $sqluser);
$rowuser = mysqli_fetch_array($queryuser);
$id_usuario = $rowuser['id_usuario'];


if($_POST['tipo']=='automatico'){
    //Utilizando el último PO autogenerado en la tabla tbltransaccion
    //tener en cuenta que hay que revisar que ese po no exista ya en tbldetalleorden
    //hacer esto mismo a la hora de insertar definitivamente para garantizar insertar con el ultimo po

    $sqlpotran = "SELECT * FROM tbltransaccion WHERE Ponumber = (SELECT MAX(Ponumber) FROM tbltransaccion)";
    $querypotran = mysqli_query($link, $sqlpotran);
    $rowpotran = mysqli_fetch_array($querypotran);
    $ponumbershow = $rowpotran['Ponumber']+1;
   
    while(true)
    {
        //verifico que ese ponumber no este en la tabla detalleorden
        $sql = "SELECT * FROM tbldetalle_orden WHERE Ponumber = '".$ponumbershow."'";
        $val = mysqli_query($link, $sql);
        $total = mysqli_num_rows($val);
        if ($total == 0) {
            echo $ponumbershow;
            return;
        } else {
            $ponumbershow=$ponumbershow+1;
        }
    }
    return;
    
}
//para buscar todos los destinos de un cliente
if($_POST['tipo']=='buscarDestinos')
{
    $codcliente = $_POST['codigo_cliente'];
    //selecciono los destinos de ese cliente
    $sql = "SELECT * FROM tbldestinos WHERE codcliente = '".$codcliente."'";
    $val = mysqli_query($link, $sql);
    $i=0;
    $data = array();
    while($row = mysqli_fetch_array($val))
    {
        $data[$i] = $row;
        $i++;
    }
    $rawdata[0]=$data;  
    echo json_encode($rawdata); 
    return;
}
if($_POST['tipo']=='insertarDestino')
{
    $nombredestino = addslashes($_POST['nombredestino']);
    $shipto = addslashes($_POST['shipto']);
    $shipto2 = addslashes($_POST['shipto2']);
    $direccion = addslashes($_POST['direccion']);
    $direccion2 = addslashes($_POST['direccion2']);
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $zip = $_POST['zip'];
    $telefono = $_POST['telefono'];
    $mail = $_POST['mail'];
    $shipcountry = $_POST['pais'];
    $codcliente = $_POST['codigo_cliente'];
    
    //busco si el destino ya existe para no duplicarlo
    $sql = "SELECT * FROM tbldestinos INNER JOIN tblshipto_venta ON tblshipto_venta.iddestino = tbldestinos.iddestino
          WHERE tbldestinos.destino='". $nombredestino."' AND tbldestinos.codcliente='". $codcliente."' AND 
          tblshipto_venta.shipto1='". $shipto."' AND tblshipto_venta.direccion='".$direccion."' AND
          tblshipto_venta.cpestado_shipto='".$estado."' AND tblshipto_venta.cpcuidad_shipto='".$ciudad."'";
    $val = mysqli_query($link, $sql);
    if(mysqli_num_rows($val)>0)
    {
      echo json_encode("error");  
      return;
    }
    
    $sql3 = "Insert INTO tbldestinos(codcliente,destino) VALUES ('".$codcliente."','".$nombredestino."')";
    $creado_destinos = mysqli_query($link, $sql3);
    $iddestino = mysqli_insert_id($link);

    $sql1 = "Insert INTO tblshipto_venta(shipto1,shipto2,direccion,cpestado_shipto,cpcuidad_shipto,cptelefono_shipto,cpzip_shipto,mail,direccion2,shipcountry,iddestino) VALUES ('".$shipto."','".$shipto2."','".$direccion."','".$estado."','".$ciudad."','".$telefono."','".$zip."','".$mail."','".$direccion2."','".$shipcountry."','".$iddestino."')";
    $creado_ship = mysqli_query($link, $sql1);
    
    //selecciono los destinos de ese cliente
    $sql = "SELECT * FROM tbldestinos WHERE codcliente = '".$codcliente."'";
    $val = mysqli_query($link, $sql);
    $i=0;
    $data = array();
    $rawdata = array();
    while($row = mysqli_fetch_array($val))
    {
        $data[$i] = $row;
        $i++;
    }
    $rawdata[0]=$data;
    echo json_encode($rawdata); 
    return;
}

//para insertar un nuevo item
if($_POST['accion']=='nuevo'){
    $item1 = $_POST['item'];
    $cantidad1= $_POST['cantidad'];
    $precioUnitario = $_POST['precioUnitario'];
    $mensaje1 = addslashes($_POST['mensaje']);
    $codcliente = $_POST['codigo_cliente']; 
    
    $sqlitem = 'SELECT id_item FROM tblproductos WHERE id_item = "'.$item1.'"';
    $queryitem = mysqli_query($link, $sqlitem);
    $rowitem = mysqli_fetch_array($queryitem);

    $item1 = $rowitem['id_item'];
    
    $jsondata = array(); 
    
    if($item1 == ''){
         $jsondata["message"] = "Item no encontrado"; 
    }
    else{
       $sqlcarro = "INSERT INTO tblcarro_venta (codcliente, id_item, cantidad, preciounitario, mensaje, id_usuario) VALUES ('".$codcliente."','".$item1."','".$cantidad1."','".$precioUnitario."','".$mensaje1."','".$id_usuario."')";
       
       mysqli_query($link, $sqlcarro);
       $insertado=mysqli_insert_id($link);
       
       if($insertado){
           $rawdata = array(); //creamos un array
           //selecciono los destinos de ese cliente
            $sql = "SELECT tblcarro_venta.*,tblproductos.prod_descripcion FROM tblcarro_venta INNER JOIN tblproductos ON tblcarro_venta.id_item=tblproductos.id_item WHERE idcompra = '".$insertado."'";
            
            $val = mysqli_query($link, $sql);
            $data = array();
            while($row = mysqli_fetch_array($val))
            {
                $data[0] = $row;
            }
            $rawdata[0]=$data;
        
            //selecciono los destinos de ese cliente
            $sql = "SELECT * FROM tbldestinos WHERE codcliente = '".$codcliente."'";
            $val = mysqli_query($link, $sql);
            $i=0;
            $data = array();
            while($row = mysqli_fetch_array($val))
            {
                $data[$i] = $row;
                $i++;
            }
            $rawdata[1]=$data;
       }
       else
       {
          $jsondata["success"] = 'false';
          $jsondata["message"] = mysqli_error(); 
       }
    }
    
    echo json_encode($rawdata); 
    return;
}
if($_POST['accion']=='editar'){
    $item1 = $_POST['item'];
    $cantidad1= $_POST['cantidad'];
    $precioUnitario = $_POST['precioUnitario'];
    $mensaje1 = addslashes($_POST['mensaje']);
    $codcliente = $_POST['codigo_cliente']; 
    $idcompra = $_POST['id_item']; 
    
    $sqlitem = 'SELECT id_item FROM tblproductos WHERE id_item = "'.$item1.'"';
    $queryitem = mysqli_query($link, $sqlitem);
    $rowitem = mysqli_fetch_array($queryitem);
    $jsondata = array();
    
    $item1 = $rowitem['id_item'];
    if($item1 == ''){
         $jsondata["message"] = "Item no encontrado"; 
    }
    else
    {
        //actualizo carro de venta
        $sql = "UPDATE tblcarro_venta SET codcliente='".$codcliente."', id_item='".$item1."', cantidad='".$cantidad1."', preciounitario='".$precioUnitario."', mensaje='".$mensaje1."', id_usuario='".$id_usuario."' WHERE idcompra='".$idcompra."'";
        $insertado=mysqli_query($link, $sql);
        if($insertado){
           $rawdata = array(); //creamos un array
           //selecciono los destinos de ese cliente
            $sql = "SELECT tblcarro_venta.*,tblproductos.prod_descripcion FROM tblcarro_venta INNER JOIN tblproductos ON tblcarro_venta.id_item=tblproductos.id_item WHERE idcompra = '".$idcompra."'";
            
            $val = mysqli_query($link, $sql);
            $data = array();
            while($row = mysqli_fetch_array($val))
            {
                $data[0] = $row;
            }
            $rawdata[0]=$data;
        
            //selecciono los destinos de ese cliente
            $sql = "SELECT * FROM tbldestinos WHERE codcliente = '".$codcliente."'";
            $val = mysqli_query($link, $sql);
            $i=0;
            $data = array();
            while($row = mysqli_fetch_array($val))
            {
                $data[$i] = $row;
                $i++;
            }
            $rawdata[1]=$data;
       }
       else
       {
          $jsondata["success"] = 'false';
          $jsondata["message"] = mysqli_error(); 
       }
    }
    echo json_encode($rawdata); 
    return;
}

if($_POST['accion']=='eliminar')
{
  $idcompra = $_POST['id_item']; 
  $sql="DELETE FROM tblcarro_venta WHERE idcompra='".$idcompra."'";
  $eliminado= mysqli_query($link, $sql);
  if($eliminado){
        $jsondata["success"] = 'true';
        $jsondata["message"]='<div class="alert alert-success" role="alert"><strong>Producto Eliminado Correctamente.</strong></div>';
       }else{
           $jsondata["success"] = 'false';
           $jsondata["message"] = mysqli_error();
  }
   echo json_encode($jsondata); 
   return;
}

if($_POST['tipo']=='buscarItemCliente'){
       
    $codcliente = $_POST['codigo_cliente']; 
    $sql = "SELECT tblcarro_venta.*,tblproductos.prod_descripcion,tbldestinos.* FROM tblcarro_venta INNER JOIN tblproductos ON tblcarro_venta.id_item=tblproductos.id_item LEFT JOIN tbldestinos ON tbldestinos.iddestino = tblcarro_venta.iddestino WHERE tblcarro_venta.codcliente = '".$codcliente."' AND id_usuario= '".$id_usuario."'";
    $val = mysqli_query($link, $sql);
    $rawdata = array(); //creamos un array
    $data = array(); //creamos un array
    //guardamos en un array multidimensional todos los datos de la consulta
     $i=0;
     while($row = mysqli_fetch_array($val))
     {
         $data[$i] = $row;
         $i++;
     }
     $rawdata[0]=$data;
     
     //seleccionando los destinos de ese cliente
     $sql = "SELECT * FROM tbldestinos WHERE codcliente = '".$codcliente."'";
     $val = mysqli_query($link, $sql);
     $i=0;
     $data = array();
     while($row = mysqli_fetch_array($val))
     {
         $data[$i] = $row;
         $i++;
     }
     $rawdata[1]=$data;  
         
     echo json_encode($rawdata);
     return;
    
}

if($_POST['tipo']=='insertarCliente')
{
    $empresa = addslashes($_POST['empresa']);
    $empresa2 =addslashes($_POST['empresa2']);
    $direccion  = addslashes($_POST['direccion']);
    $direccion2 = addslashes($_POST['direccion2']);
    $ciudad     = $_POST['ciudad'];
    $estado  = $_POST['estado'];
    $zip  = $_POST['zip'];
    $pais  = $_POST['pais'];
    $telefono  = $_POST['telefono'];
    $vendedor  = addslashes($_POST['vendedor']);
    $mail  = $_POST['mail'];

    $sqlcod = "SELECT * FROM tblcliente WHERE codigo = (SELECT MAX(codigo) FROM tblcliente)";
    $querycod = mysqli_query($link, $sqlcod);
    $rowcod = mysqli_fetch_array($querycod);
    $codigo = $rowcod['codigo']+1;
    
    $sql="INSERT INTO tblcliente (codigo, empresa, direccion,direccion2,ciudad, estado, zip, pais, telefono, vendedor, mail, empresa2) VALUES ('".$codigo."','".$empresa."','".$direccion."','".$direccion2."','".$ciudad."','".$estado."','".$zip."','".$pais."','".$telefono."','".$vendedor."','".$mail."','".$empresa2."')";
    $insertado= mysqli_query($link, $sql);
    $insertado=mysqli_insert_id($link);
    
     if($insertado){
           $sql = "SELECT tblcliente.*,tblusuario.cpnombre FROM tblcliente INNER JOIN tblusuario ON tblusuario.id_usuario = tblcliente.vendedor WHERE id_vendedor = '".$insertado."'";
           $val = mysqli_query($link, $sql);
           $data = array();
           while($row = mysqli_fetch_array($val))
           {
                $data[0] = $row;
           }
        echo json_encode($data);
        return;
     }
     else
     {
          $jsondata["success"] = 'false';
          $jsondata["message"] = mysqli_error(); 
     }
     return;
}

//para asignar destinos a cada item de venta
if($_POST['tipo']=='asignarDestino')
{
    $itemDestino=$_POST['iddestino'];
    $idcompra=$_POST['idcompra'];
    
    if(isset($_POST['idcompra']) && $idcompra=="todas")
    {
       $sql="UPDATE tblcarro_venta SET iddestino='".$itemDestino."'";
    }
    else {
        
       $sql="UPDATE tblcarro_venta SET iddestino='".$itemDestino."' WHERE idcompra='".$idcompra."'";
    }
    $modificado= mysqli_query($link, $sql);
    return;
}

//registrar toda la tabla de ventas y clientes
if($_POST['tipo']=='registrar')
{
  $jsondata = array();   
  $orddate     = $_POST['orddate'];
  $deliver     = $_POST['deliver'];
  $satdel      = $_POST['satdel'];
  $consolidado = $_POST['consolidado'];
  $idcliente   = $_POST['codcliente'];
  
  
  //Recoger todos los datos del cliente
  $sqlsoldto = "SELECT * from tblcliente WHERE codigo = ".$idcliente." ";
  $querysoldto = mysqli_query($link, $sqlsoldto);
  $rowsoldto = mysqli_fetch_array($querysoldto);

  //datos de la tabla tblsoldto
  $soldto      = $rowsoldto['empresa'];
  $soldto2     = $rowsoldto['empresa2']; 
  $stphone     = $rowsoldto['telefono'];
  $adddress    = $rowsoldto['direccion'];
  $adddress2   = $rowsoldto['direccion2'];
  $city        = $rowsoldto['ciudad'];
  $state       = $rowsoldto['estado'];
  $billzip     = $rowsoldto['zip'];
  $country     = $rowsoldto['pais'];
  $billmail    = $rowsoldto['mail'];
  
  //verificando que el ponumber no exista en otra orden
  $ponumber= trim($_POST['ponumber']);
  $sqlPO   = "SELECT tbldetalle_orden.id_orden_detalle FROM tbldetalle_orden WHERE tbldetalle_orden.Ponumber = '".$ponumber."' ";
  $queryPO = mysqli_query($link, $sqlPO);
  $rowPO   = mysqli_fetch_array($queryPO);
  //verifico si hay datos 
  $ray = mysqli_num_rows($queryPO);
  if($ray > 0 ){ 
    $jsondata["success"] = 'false';
    $jsondata["message"]="Ese Ponumber ya está siendo utilizado por otra orden";
    echo json_encode($jsondata);
    return;
  }
  
  //borro todo lo que hay en tabla transaccion
  $sql="DELETE FROM tbltransaccion";
  $eliminado= mysqli_query($link, $sql);
    
  ////Verificar que todos los items en el carro de venta tengan un destino
  $sqldestcheck = "SELECT * FROM tblcarro_venta WHERE codcliente = '".$idcliente."' AND id_usuario = '".$id_usuario."' ";
  $querydestcheck = mysqli_query($link, $sqldestcheck);

  while ($rowdestcheck = mysqli_fetch_array($querydestcheck)) {
    if($rowdestcheck['iddestino'] == 0){
      $jsondata["success"] = 'false';
      $jsondata["message"]="Existen Items sin un destino asignado";
      echo json_encode($jsondata);
      return;
    }
  }
  
  //Recorrer los registros de tblcarro_venta//
  $sqlins = "SELECT * FROM tblcarro_venta WHERE codcliente = '".$idcliente."' AND id_usuario = '".$id_usuario."' ";
  $queryins = mysqli_query($link, $sqlins);
  while($rowins = mysqli_fetch_array($queryins)){

    $cantidad = $rowins['cantidad'];
    $item = $rowins['id_item'];
    $precio = $rowins['preciounitario'];
    $mensaje = addslashes($rowins['mensaje']);
    
    $iddestino = $rowins['iddestino'];

    $sqldest = "SELECT * FROM tblcarro_venta INNER JOIN tblshipto_venta ON tblcarro_venta.iddestino = tblshipto_venta.iddestino WHERE tblcarro_venta.iddestino = '".$iddestino."'";
    $querydest = mysqli_query($link, $sqldest);
    $rowdest = mysqli_fetch_array($querydest);
   
    $shipto   = addslashes($rowdest['shipto1']);
    $shipto2  = addslashes($rowdest['shipto2']);
    $direccion   = addslashes($rowdest['direccion']);
    $direccion2  = addslashes($rowdest['direccion2']);
    $ciudad      = addslashes($rowdest['cpcuidad_shipto']);
    $estado      = $rowdest['cpestado_shipto'];
    $zip         = $rowdest['cpzip_shipto'];
    $telefono    = $rowdest['cptelefono_shipto'];
    $mail        = $rowdest['mail'];

   //Calcular el shipdt
  $shipdt      = $_POST['shipdt'];
	 
  //Obteniendo el origen para obtener el pais de origen (codigo_ciudad-pais)
  $sqlorg4   = "SELECT origen FROM tblproductos WHERE tblproductos.id_item ='".$item."'";
  $query4 = mysqli_query($link, $sqlorg4);
  $row4   = mysqli_fetch_array($query4);
  $cporigen = $row4["origen"];
  $cporigen_city = explode("-", $cporigen);
  $cporigen = $cporigen_city[0];

  //Obteniendo el codigo del pais
  $sqlorg5   = "SELECT codpais_origen FROM tblciudad_origen WHERE tblciudad_origen.codciudad = '".$cporigen."'";
  $query5 = mysqli_query($link, $sqlorg5);
  $row5   = mysqli_fetch_array($query5);
  $origin = $row5["codpais_origen"];

  //Obtener dia de la semana para saber cuanto restar al deliver para asignarle al shipdt
  $fecha = date('l', strtotime($deliver));
  //verifico que dia es para restarle los dias que son 
  /*
    Si el envio es de ECUADOR
  */
   if($origin == "EC"){ 
  		// Si es Martes, Jueves o Viernes le resto 3 dias
  		if(strcmp($fecha,"Tuesday")==0 || strcmp($fecha,"Thursday")==0 || strcmp($fecha,"Friday")==0) {
  			$shipdt = strtotime ( '-3 day' , strtotime ( $deliver ) ) ;
  			$shipdt = date ( 'Y-m-j' , $shipdt );
  		}else{
  			//Si es otro dia de envio o sea Miercoles
  			$shipdt = strtotime ( '-4 day' , strtotime ( $deliver ) ) ;
  			$shipdt = date ( 'Y-m-j' , $shipdt );
  		}					
  }
  else{
        $shipdt = strtotime ( '-5 day' , strtotime ( $deliver ) ) ;
        $shipdt = date ( 'Y-m-j' , $shipdt );  //TBLDETALLE_ORDEN     
  }	//Fin del if 
	 

  $farm = $_POST['farm'];

  //El pais de envio hay que sacarlo tambien del shipto_venta
  $ctry = $rowdest['shipcountry'];

  /* VENDOR */
  $cliente = $idcliente;
  $enviaramsg = $rowdest['shipto1'];
  $clientmsg = $soldto;

  //Verifico si el mensaje esta en blanco, si es asi le pongo un valor por defecto
  if($mensaje == ''){
    $mensaje = "To-Blank Info   ::From- Blank Info   ::Blank .Info"; 
  }
  else{
    $mensaje = "To-".$enviaramsg."::From-".$clientmsg."::".$mensaje;
  }

  
    //***************** Insertando en las diferentes tablas para registrar la orden ****************************************//
    //Insertando los datos de la tabla orden  
    for($i=1;$i<=$cantidad;$i++){
      if($i == 1){
        //Insertando los datos de la tabla orden
        $sql="INSERT INTO tblorden (nombre_compania,cpmensaje,order_date) VALUES ('BurtonTech','".$mensaje."','".$orddate."')"; 
        $creado_orden= mysqli_query($link, $sql);
        $id_order = mysqli_insert_id($link);

        //Insertar los datos de Shipto
        $sql1 = "Insert INTO tblshipto(id_shipto,shipto1,shipto2,direccion,cpestado_shipto,cpcuidad_shipto,cptelefono_shipto,cpzip_shipto,mail,direccion2,shipcountry) VALUES ('".$id_order."','".$shipto."','".$shipto2."','".$direccion."','".$estado."','".$ciudad."','".$telefono."','".$zip."','".$mail."','".$direccion2."','".$ctry."')";
        $creado_ship = mysqli_query($link, $sql1);
        
        //Insertar los datos de Soldto
        $sql2 = "Insert INTO tblsoldto(id_soldto,soldto1,soldto2,cpstphone_soldto,address1,address2,city,state,postalcode,billcountry,billmail) VALUES ('".$id_order."','".$soldto."','".$soldto2."','".$stphone."','".$adddress."','".$adddress2."','".$city."','".$state."','".$billzip."','".$country."','".$billmail."')";
        $creado_sold = mysqli_query($link, $sql2);

        //Insertar los datos de tbldirector
        $sql5 = "Insert INTO tbldirector(id_director) VALUES ('".$id_order."')";
        $creado_director= mysqli_query($link, $sql5);
        	
        //Inserto los detalles del primer producto de la orden
        $sql3 = "Insert INTO tbldetalle_orden(id_detalleorden,cpcantidad,Ponumber,Custnumber,cpitem,satdel,farm,cppais_envio,cpmoneda,cporigen,cpUOM,delivery_traking,ShipDT_traking,estado_orden,descargada,user,eBing,coldroom,status,poline,unitprice,ups,tracking,vendor,consolidado) VALUES ('".$id_order."','1','".$ponumber."','".$idcliente."','".$item."','".$satdel."','".$farm."','".$ctry."','USD','".$origin."','BOX','".$deliver."','".$shipdt."','Active','not donwloaded','','0','No','New','0','".$precio."','','','".$cliente."','".$consolidado."')";
        $creado_detalle = mysqli_query($link, $sql3);
        
      }
      else{
        //Inserto los detalles del primer producto de la orden
        $sql3 = "Insert INTO tbldetalle_orden(id_detalleorden,cpcantidad,Ponumber,Custnumber,cpitem,satdel,farm,cppais_envio,cpmoneda,cporigen,cpUOM,delivery_traking,ShipDT_traking,estado_orden,descargada,user,eBing,coldroom,status,poline,unitprice,ups,tracking,vendor,consolidado) VALUES ('".$id_order."','1','".$ponumber."','".$idcliente."','".$item."','".$satdel."','".$farm."','".$ctry."','USD','".$origin."','BOX','".$deliver."','".$shipdt."','Active','not donwloaded','','0','No','New','0','".$precio."','','','".$cliente."','".$consolidado."')";																
        $creado_detalle = mysqli_query($link, $sql3);
      }
     }

    //Insertar en la tabla de transacciones
     $sqltrans = "INSERT INTO tbltransaccion(Ponumber,codcliente,cantidad,iddestino,id_item,idusuario) VALUES ('".$ponumber."','".$idcliente."','".$cantidad."','".$iddestino."','".$item."','".$id_usuario."')";
     $querytrans = mysqli_query($link, $sqltrans);
   

 }//FIN DEL WHILE
 
 if($creado_orden && $creado_ship && $creado_sold && $creado_detalle && $creado_director){ 
    //Vaciar carro de compra
    $sqlvaciar = "DELETE FROM tblcarro_venta WHERE codcliente = '".$idcliente."' AND id_usuario = '".$id_usuario."'";
    $queryvaciar = mysqli_query($link, $sqlvaciar);
 }
 $jsondata["success"] = 'true';
 $jsondata["message"]="Registro terminado satisfactoriamente";
 echo json_encode($jsondata);
 return; 
}

if (isset($_POST['id_cliente'])) {
        $sql = "SELECT * FROM tblcliente where codigo='".$_POST['id_cliente']."'";
        $consulta = mysqli_query($link, $sql);
        echo json_encode(mysqli_fetch_array($consulta));
       
}
else
{
    $sql = "SELECT * FROM tblcliente";
    $consulta = mysqli_query($link, $sql);
    echo json_encode(mysqli_fetch_array($consulta));
}
