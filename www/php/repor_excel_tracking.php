<?php
//ini_set('memory_limit', '-1');
session_start();
include ("conectarSQL.php");
include ("conexion.php");
include ("seguridad.php");
include ("./calculosUPS.php");

$user     =  $_SESSION["login"];
$passwd   =  $_SESSION["passwd"];
$rol      =  $_SESSION["rol"];
	
$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysqli_error());

$fecha_inicio=$_GET["fecha_inicio"];
$fecha_fin=$_GET["fecha_fin"];
$pais =$_GET["pais"];
$estado = $_GET["estado"];

$cliente= $_GET["cliente"];
$item= $_GET["item"];
$tipo= $_GET["tipo"];

/////////////////
//Para ver el numero de ordenes que tiene una guia master
/////////////////////
if(isset($_POST["numguia"]))
{
   $sql="SELECT
            tblorden.id_orden,
            tblorden.nombre_compania,
            tblorden.cpmensaje,
            tblorden.order_date,
            tblshipto.shipto1,
            tblshipto.direccion,
            tblshipto.cpestado_shipto,
            tblshipto.cptelefono_shipto,
            tblshipto.cpzip_shipto,
            tblshipto.shipcountry,
            tblsoldto.soldto1,
            tblsoldto.address1,
            tblsoldto.postalcode,
            tbldetalle_orden.id_orden_detalle,
            tbldetalle_orden.Custnumber,
            tbldetalle_orden.Ponumber,
            tbldetalle_orden.cpitem,
            tbldetalle_orden.farm,
            tbldetalle_orden.cppais_envio,
            tbldetalle_orden.cporigen,
            tbldetalle_orden.delivery_traking,
            tbldetalle_orden.ShipDT_traking,
            tbldetalle_orden.tracking,
            tbldetalle_orden.user_bloqueo,
            tbldetalle_orden.guiamaster,
            tblproductos.wheigthKg,
            tblproductos.cpservicio,
            tblproductos.gen_desc,
            tblproductos.item,
            tblproductos.length,
            tblproductos.width,
            tblproductos.heigth
            FROM
            tblorden
            INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
            INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
            INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
            INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem
            INNER JOIN tblguiamaster ON tbldetalle_orden.guiamaster = tblguiamaster.id 
            WHERE tblguiamaster.guia='".$_POST['numguia']."'";
   
   $query = mysqli_query($sql,$link);
   $row = mysqli_num_rows($query);
   echo $row;
   return;
}


////////////////////////////////////////////////////////////////////////////////
//para eliminar guia master con sus trackings asignados
if(isset($_POST["eliminar"]) && $_POST["eliminar"]=="guiamaster")
{
  //para eliminar la guia master primero verifico si la guia no esta closeout ni cierre de dia
  $sql1= "SELECT * FROM tblguiamaster where guia='".$_POST["nodoElim"]."'";
  $query1=mysqli_query($sql1,$link) or die("Error Actualizando tracking de Detalle Orden".  mysqli_error());
  $com=mysqli_fetch_array($query1);
  if($com['closeout']=="" && $com['cierredia']=="")
  {
    //busco todos los trackings de esa guia  
    $sql11= "SELECT tracking FROM tbldetalle_orden where guiamaster='".$_POST["nodoElim"]."'";
    $query11=mysqli_query($sql11,$link) or die("Error obteniendo datos de Detalle Orden".  mysqli_error());
    
    while($com1=mysqli_fetch_array($query11))
    {
        //elimino los tracking asociados con esa guia master  
        $sql2= "Update tbldetalle_orden set tracking='',status='Ready to ship', descargada='not downloaded', user='', farm='', coldroom='No', codigo='0000000000',guiamaster=NULL WHERE tracking = '".$com1['tracking']."' ";
        $eliminado=mysqli_query($sql2,$link) or die("Error Actualizando tracking de Detalle Orden".  mysqli_error()); 
        if($eliminado)
        {
            //actualizo coolrom
            $sqlcold = "UPDATE tblcoldroom SET tracking_asig='', guia_hija='0', guia_madre='0', salida='No' WHERE tracking_asig = '".$com1['tracking']."' ";
            $querycold= mysqli_query($sqlcold,$link);


            //inserto en historico
            $usuarioLog = $_SESSION["login"];
            $ip = getRealIP();
            $fecha = date('Y-m-d H:i:s');
            $operacion = "Eliminar tracking: Trk: ".$com1['tracking'];
            $SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`,`razon`) 
                                               VALUES ('$usuarioLog','$operacion','$fecha','$ip','')";
            $consultaHist = mysqli_query($SqlHistorico,$link) or die ("Error insertando en historico");
        }
    }
    //elimino la guia master
    $sql3= "delete FROM tblguiamaster where guia='".$_POST["nodoElim"]."'";
    $query3=mysqli_query($sql3,$link) or die("Error eliminando Guia Mater".  mysqli_error());
  
  }
  else if($com['closeout']=="SI"){
    echo "1";   
    return;  
  }
  else if($com['cierredia']=="SI"){
    echo "2"; 
    return;
  }
  echo "0"; 
  return;
}

//////////////////////////////////////////////////////////////////////////////////
//para eliminar trackings
if(isset($_POST["eliminar"]) && $_POST["eliminar"]=="trackings")
{
  $trackings= split(",", $_POST["nodoElim"]); 
  $valor=0;
  //elimino los tracking asociados con esa guia master 
  for($i=0;$i<count($trackings);$i++)
  {
    //para eliminar la tracking primero verifico si la guia no tiene cierre de dia
    $sql1= "SELECT tbldetalle_orden.guiamaster,tblguiamaster.cierredia FROM tbldetalle_orden INNER JOIN tblguiamaster ON tbldetalle_orden.guiamaster=tblguiamaster.id where tracking='".$trackings[$i]."'";
    $query1=mysqli_query($sql1,$link) or die("Error Actualizando tracking de Detalle Orden".  mysqli_error());
    $com=mysqli_fetch_array($query1);  
      
    if($com['cierredia']=="")  
    {
        $sqll= "Update tbldetalle_orden set tracking='',status='Ready to ship', descargada='not downloaded', user='', farm='', coldroom='No', codigo='0000000000',guiamaster=NULL where tracking='".$trackings[$i]."'";
        $eliminado=mysqli_query($sqll,$link) or die("Error Actualizando tracking de Detalle Orden".  mysqli_error()); 
        if($eliminado)
        {
            //actualizo coolrom
            $sqlcold = "UPDATE tblcoldroom SET tracking_asig='', guia_hija='0', guia_madre='0', salida='No' WHERE tracking_asig = '".$trackings[$i]."' ";
            $querycold= mysqli_query($sqlcold,$link);


            //inserto en historico
            $usuarioLog = $_SESSION["login"];
            $ip = getRealIP();
            $fecha = date('Y-m-d H:i:s');
            $operacion = "Eliminar tracking: Trk: ".$trackings[$i];
            $SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`,`razon`) 
                                               VALUES ('$usuarioLog','$operacion','$fecha','$ip','')";
            $consultaHist = mysqli_query($SqlHistorico,$link) or die ("Error insertando en historico");
        }
    }
    else if($com['cierredia']=="SI"){
      $valor="1"; 
    }
      
  }
  echo $valor;
  return;
}
/////////////////////////////////////////////////////////////////////////////
//para obtener las guias master disponibles
if(isset($_POST["obtenerguia"]))
{
  $guias=array();
  $sql="SELECT
        tblguiamaster.guia
        FROM
        tblguiamaster
        INNER JOIN tblusuario ON tblguiamaster.usuario = tblusuario.id_usuario
        WHERE
        tblusuario.cpuser='".$user."' AND
        tblguiamaster.closeout = '' AND
        tblguiamaster.cierredia = ''";
      
  $query=mysqli_query($sql,$link) or die("Error obteniendo guia master".  mysqli_error());
  $i=0;
  while($com=mysqli_fetch_array($query))
  {
    $guias[$i]=$com;
    $i++;
  }
  echo json_encode($guias);
  return;  
}

//para obtener los estados dependiente del pais
if(isset($_POST["accion"]) && $_POST["accion"]=='selec_estado'){
    $sql   = "SELECT * FROM tblestados WHERE pais='".$_POST["pais"]."'";
    $query = mysqli_query($sql,$link);
    $estados=array();
    while($row1 = mysqli_fetch_array($query)){
        $estados[]=$row1;
    }
    echo json_encode($estados);
    return;
}


//para aisgnar trackings. el tracking esta dividido en las siguientes partes
//1Z------Siempre lo lleva
//123x56--6digitos que dependen de la cuenta. Se saca de la tabla tblclienteups
//66------2digitos que sera el tipo de servicio. Se saca de tblproductos, cpservicio
//xxxxxxx-7digitos que se tomara del rango 0000000-3999999/ 7900000-9999999
//x-------1digito que es un algoritmo.
if(isset($_POST["asignar"]))
{
   $identificador="1Z";
   $cuenta="";
   $guia="";
   $id_guia="";
    
//si selecciona guia ya existente
   if(isset($_POST["guia"]) && $_POST["guia"]=="nueva")
   {
     $cuenta=$_POST['cuenta'];  
   }
   else if(isset($_POST["guia"]) && $_POST["guia"]!="nueva")
   {
      //obtengo el string de la cuenta que se utilizo para esta guia 
      $cuenta=substr($_POST["guia"], 0,6); 
      $guia= $_POST["guia"];
      $sql22="Select id from tblguiamaster where guia='".$guia."'";
      $query22=mysqli_query($sql22,$link) or die("Error obteniendo ultimo rango".  mysqli_error());
      $fila22=mysqli_fetch_row($query22);
      $id_guia=$fila22[0];
   }
   
   $serv=array('ES'=>'66','SV'=>'04');
   $ordenes=json_decode($_POST['asignar']);
   
   //una vez que se pulso asignar guardo en la tabla rangos el rango que tendran los trackings en dependencia 
   //al numero de ordenes a las que se les asignara los trackings
   //1-obtengo de la tblrangos el ultimo rango utilizado
   //2-al rango superior le adiciono 1, y le sumo el numero total de ordenes
   $sql="Select rango2 from tblrangos order by id desc limit 1";
   $query=mysqli_query($sql,$link) or die("Error obteniendo ultimo rango".  mysqli_error());
   $fila=mysqli_fetch_row($query);
   $ultimo_rango=$fila[0];
   $rango1=$ultimo_rango+1;
   $band=0;
   //existen dos rangos de trackings que van desde el 0000000-3999999/7900000-9999999
   //debo verificar que ese rango  no se pase de esos valores.
   if($rango1>=0 && $rango1<=3999999){
     $band=1;
   }else if($rango1>=7999999 && $rango1<=9999999)
   {
     $band=2;
   }
   else if($rango1>3999999 && $rango1<7999999)
   {
     $band=3;
     $rango1=7999999;
   }
   else if($rango1>9999999)
   {
     $band=4;
     $rango1=0;
   }
   
   //si la guia es nueva, debo calcularla y sera el SHIP#
   if(isset($_POST["guia"]) && $_POST["guia"]=="nueva")
   {
     $guia=shp_resumido($rango1,$cuenta);
     $rango2=$rango1+count($ordenes); //como la guia es nueva debo adicionarle uno mas que sera el numero del tracking de documentos
        
     if($band==1 && $rango2>3999999){
        $diferencia=3999999-$rango1; //esta es la cantidad que habra desdes el rango1 hasta el limite 3999999
        $diferencia2=$ordenes-$diferencia; //esta es la cantidad que hay que sumarle al segundo rando           
        $rango2=7999999+$diferencia2;
     }
     if($band==2 && $rango2>9999999){
       
        $diferencia=9999999-$rango1; //esta es la cantidad que habra desdes el rango1 hasta el limite 3999999
        $diferencia2=count($ordenes)-$diferencia; //esta es la cantidad que hay que sumarle al segundo rando      
        $rango2=0+$diferencia2;
     }
   }
   else{
      $rango2=$ultimo_rango+count($ordenes);
      if($band==1 && $rango2>3999999){
        $diferencia=3999999-$rango1; //esta es la cantidad que habra desdes el rango1 hasta el limite 3999999
        $diferencia2=count($ordenes)-$diferencia; //esta es la cantidad que hay que sumarle al segundo rando           
        $rango2=7999999+$diferencia2;
     }
     if($band==2 && $rango2>9999999){
        $diferencia=9999999-$rango1; //esta es la cantidad que habra desdes el rango1 hasta el limite 3999999
        $diferencia2=count($ordenes)-$diferencia; //esta es la cantidad que hay que sumarle al segundo rando           
        $rango2=0+$diferencia2;
     }
     
   }
   
   
   
   
   $sql1="INSERT INTO tblrangos (`rango1`,`rango2`) VALUES (".$rango1.",".$rango2.")";
   $query1=mysqli_query($sql1,$link) or die("Error insertando rango".  mysqli_error());
   
      //obtengo el id del usario que esta logueado y esta asignando trackings
      $sqluser="Select id_usuario from tblusuario where cpuser='".$user."'";
      $queryuser=mysqli_query($sqluser,$link) or die("Error obteniendo ultimo rango".  mysqli_error());
      $filauser=mysqli_fetch_row($queryuser);
      $id_user=$filauser[0];
   
    $bandera=0; 
    for($i=0;$i<=count($ordenes);$i++)
    { 
      //si la guia es nueva le asigno como servicio el 66, este solo se utiliza para insertar en la guia master la guia d documentos
      if(isset($_POST["guia"]) && $_POST["guia"]=="nueva" && $bandera==0)
      {
        $servicio='66';
      }
      else{
        //para obtener el tipo de servicio necesito buscar el item que tiene la orden y en la tabla producto buscar el campo cpservicio
        //y en dependencia del que sea
        $sql2="SELECT tblproductos.cpservicio FROM tbldetalle_orden INNER JOIN tblproductos ON tblproductos.id_item = tbldetalle_orden.cpitem WHERE tbldetalle_orden.id_orden_detalle='".$ordenes[$i]."'";
        $query2=mysqli_query($sql2,$link) or die("Error obteniendo tipo de servicio".  mysqli_error());
        $fila=mysqli_fetch_row($query2);
        $servicio=$serv[$fila[0]];
      }
      
     //rellenando el rango con digitos 0 a la izquierda
     $rango=str_pad($rango1, 7, "0", STR_PAD_LEFT);
     
     //calculando digito de chequeo
     $digito=checkDigit($cuenta.$servicio.$rango);
     
     //construimos el tracking
     $tracking=$identificador.$cuenta.$servicio.$rango.$digito;
     
     //si la guia es nueva inserto la guia en tblguiamaster con su tracking
    if(isset($_POST["guia"]) && $_POST["guia"]=="nueva" && $bandera==0)
    {
      $sql11="INSERT INTO tblguiamaster (guia,tracking_documentos,usuario) VALUES ('".$guia."','".$tracking."','".$id_user."')";
      $query11=mysqli_query($sql11,$link) or die("Error insertando en tbl guiamaster".  mysqli_error());
      $id_guia=  mysqli_insert_id($link);
      $rango1++;
      if ($rango1 == 4000000) {
                $rango1 = 7999999;
            } else if ($rango1 == 10000000) {
                $rango1 = 0;
            }

            $bandera=1;
      $i=-1;
      continue;
    }
     
    $sql3="update tbldetalle_orden set tracking ='".$tracking."',guiamaster='".$id_guia."',status = 'Shipped' WHERE id_orden_detalle='".$ordenes[$i]."'";
    $query3=mysqli_query($sql3,$link) or die("Error insertando en detalle orden".  mysqli_error());
     
    //incrementando el rango1
    $rango1++;
    if ($rango1 == 4000000) {
            $rango1 = 7999999;
    } else if ($rango1 == 10000000) {
        $rango1 = 0;
    }
}

   $respuesta="ok";
   echo json_encode($respuesta);
   return ;
}

////////////////////////////////////////////////////////////
//para seleccionar todas las ordenes sin tracking
if(!isset($_GET["conTrack"]))
{
if($tipo=='forden'){
    
      $sql = "Select tbldetalle_orden.Ponumber,
            tbldetalle_orden.Custnumber,
            tbldetalle_orden.cpitem,
            tbldetalle_orden.cppais_envio,
            tblshipto.cpestado_shipto,
            tblshipto.cpcuidad_shipto,
            tbldetalle_orden.delivery_traking,
            tbldetalle_orden.ShipDT_traking,
            tblorden.order_date,
            tbldetalle_orden.id_orden_detalle,
            tbldetalle_orden.status
            FROM
            tblorden INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
            INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
            WHERE order_date BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' and estado_orden='Active'";    
 }
if($tipo=='fvuelo'){
    $sql = "Select tbldetalle_orden.Ponumber,
            tbldetalle_orden.Custnumber,
            tbldetalle_orden.cpitem,
            tbldetalle_orden.cppais_envio,
            tblshipto.cpestado_shipto,
            tblshipto.cpcuidad_shipto,
            tbldetalle_orden.delivery_traking,
            tbldetalle_orden.ShipDT_traking,
            tblorden.order_date,
            tbldetalle_orden.id_orden_detalle,
            tbldetalle_orden.status
            FROM
            tblorden
            INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
            INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
            WHERE ShipDT_traking BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' and estado_orden='Active'";
            
}
if($tipo=='fentrega'){
    $sql = "Select tbldetalle_orden.Ponumber,
            tbldetalle_orden.Custnumber,
            tbldetalle_orden.cpitem,
            tbldetalle_orden.cppais_envio,
            tblshipto.cpestado_shipto,
            tblshipto.cpcuidad_shipto,
            tbldetalle_orden.delivery_traking,
            tbldetalle_orden.ShipDT_traking,
            tblorden.order_date,
            tbldetalle_orden.id_orden_detalle,
            tbldetalle_orden.status
            FROM
            tblorden
            INNER JOIN tblshipto ON tblorden.id_orden = tblshipto.id_shipto
            INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
            WHERE delivery_traking BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' and estado_orden='Active'";
    }
}
////////////////////////////////////////////////////////////
//para seleccionar todas las ordenes con tracking
else{
    if($tipo=='forden'){
           $sql= "SELECT tblguiamaster.guia,
            tblguiamaster.tracking_documentos,
            tblguiamaster.closeout,
            tblguiamaster.cierredia,
            tblorden.id_orden,
            tbldetalle_orden.Ponumber,
            tbldetalle_orden.tracking,
            tbldetalle_orden.Custnumber,
            tbldetalle_orden.cpitem,
            tbldetalle_orden.cppais_envio,
            tblshipto.cpestado_shipto,
            tblshipto.cpcuidad_shipto,
            tbldetalle_orden.delivery_traking,
            tbldetalle_orden.ShipDT_traking,
            tblorden.order_date,
            tbldetalle_orden.id_orden_detalle,
            tbldetalle_orden.status
            FROM
            tblorden
            INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
            INNER JOIN tblshipto ON tblshipto.id_shipto = tblorden.id_orden
            INNER JOIN tblguiamaster ON tbldetalle_orden.guiamaster = tblguiamaster.id
            INNER JOIN tblusuario ON tblguiamaster.usuario = tblusuario.id_usuario
            WHERE order_date BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' and estado_orden='Active' AND tblusuario.cpuser='".$user."'";
    }
    if($tipo=='fvuelo'){
        $sql="SELECT tblguiamaster.guia,
            tblguiamaster.tracking_documentos,
            tblguiamaster.closeout,
            tblguiamaster.cierredia,
            tblorden.id_orden,
            tbldetalle_orden.Ponumber,
            tbldetalle_orden.tracking,
            tbldetalle_orden.Custnumber,
            tbldetalle_orden.cpitem,
            tbldetalle_orden.cppais_envio,
            tblshipto.cpestado_shipto,
            tblshipto.cpcuidad_shipto,
            tbldetalle_orden.delivery_traking,
            tbldetalle_orden.ShipDT_traking,
            tblorden.order_date,
            tbldetalle_orden.id_orden_detalle,
            tbldetalle_orden.status
            FROM
            tblorden
            INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
            INNER JOIN tblshipto ON tblshipto.id_shipto = tblorden.id_orden
            INNER JOIN tblguiamaster ON tbldetalle_orden.guiamaster = tblguiamaster.id
            INNER JOIN tblusuario ON tblguiamaster.usuario = tblusuario.id_usuario
            WHERE ShipDT_traking BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' and estado_orden='Active' AND tblusuario.cpuser='".$user."'";
    }
    if($tipo=='fentrega'){
       $sql="SELECT tblguiamaster.guia,
            tblguiamaster.tracking_documentos,
            tblguiamaster.closeout,
            tblguiamaster.cierredia,
            tblorden.id_orden,
            tbldetalle_orden.Ponumber,
            tbldetalle_orden.tracking,
            tbldetalle_orden.Custnumber,
            tbldetalle_orden.cpitem,
            tbldetalle_orden.cppais_envio,
            tblshipto.cpestado_shipto,
            tblshipto.cpcuidad_shipto,
            tbldetalle_orden.delivery_traking,
            tbldetalle_orden.ShipDT_traking,
            tblorden.order_date,
            tbldetalle_orden.id_orden_detalle,
            tbldetalle_orden.status
            FROM
            tblorden
            INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
            INNER JOIN tblshipto ON tblshipto.id_shipto = tblorden.id_orden
            INNER JOIN tblguiamaster ON tbldetalle_orden.guiamaster = tblguiamaster.id
            INNER JOIN tblusuario ON tblguiamaster.usuario = tblusuario.id_usuario
            WHERE delivery_traking BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' and estado_orden='Active' AND tblusuario.cpuser='".$user."'";
    }
}

if($pais!= null){
    $sql = $sql." AND cppais_envio= '".$pais."'";
}
if($estado!= null){
    $sql = $sql." AND cpestado_shipto= '".$estado."'";
}	
//if($cliente!=""){
//    $sql = $sql." AND vendor like %'".$cliente."'%";
//}
if($item!=null){
   $sql = $sql." AND cpitem= '".$item."'";
}
if(!isset($_GET["conTrack"])){
   //si la finca es autonoma deben mostrarse solamente las ordenes con el usuario de esa finca
  if($rol == 3){
    $sql = $sql." AND tbldetalle_orden.`user`='".$user."'";
  }  
 
 $sql = $sql." AND tracking='' AND (user_bloqueo='' OR user_bloqueo='".$user."')"; 
//echo $sql;
}
else
{
  $sql = $sql." AND tbldetalle_orden.tracking!='' ORDER BY tblguiamaster.guia ASC";
}

//siempre que realice una nueva busqueda debo desbloquear posibles ordenes que haya bloqueado anteriormente, cuando busco ordenes sin track
$sqldesbloquear="Update tbldetalle_orden set user_bloqueo='' where tracking='' and user_bloqueo='".$user."'";
$querydesbloquear=mysqli_query($sqldesbloquear,$link);

$query1=mysqli_query($sql,$link);
$datos = array();
$rawdata = array();
$i=0;

while($com=mysqli_fetch_array($query1))
{
    //para las ordenes sin tracking
    if(!isset($_GET["conTrack"])){
        //codigo que bloquea las ordenes de detalleorden por el usuario que las esta viendo
        $sqll= "Update tbldetalle_orden set user_bloqueo='".$user."' where id_orden_detalle='".$com['id_orden_detalle']."'";
        
        $queryy=mysqli_query($sqll,$link);
        if($rol == 1 || $rol == 2){
          $rawdata[$i] = $com;
          $i++;
        }
        else
        {
         if(strcmp($com['status'],'Ready to ship') == 0 || strcmp($com['status'],'New') == 0){
            $rawdata[$i] = $com;
            $i++;
         }
        }
    }
    else
    { 
        $datos[]=$com;
    }
}

if(!isset($_GET["conTrack"]))
{
  $datos[0]=$rawdata;
  echo json_encode($datos);
}
else
{
  echo llenarJson($datos); 
}
return;


////////////////////////////////////////////////////////////////////////////////
//FUNCIONES UTILES
/////////////////////////////////////////////
//para el arbol
//hay que formar el string en formato json de la siguiente manera
//[{"title": "Animalia", "expanded": true, "folder": true, "children": [
//  {"title": "Carnivora", "children": []}
//]}
        
function llenarJson($datos)
{
    $j=0;
    $json.="[";
    for($i=0;$i<count($datos);$i++)
    {
      $guia=$datos[$i]['guia'];
      if($datos[$i+1]['guia']==$guia)
      {
        if($j==0){
          $json.='{"title": "'.$guia.'","closeout": "'.$datos[$i]['closeout'].'","cierredia": "'.$datos[$i]['cierredia'].'", "expanded": "true", "folder": "true", "children": [';
        }
        $json.='{"title": "'.$guia.'","tracking":"'.$datos[$i]['tracking'].'","Ponumber":"'.$datos[$i]['Ponumber'].'","Custnumber":"'.$datos[$i]['Custnumber'].'", "cpitem":"'.$datos[$i]['cpitem'].'","cppais_envio":"'.$datos[$i]['cppais_envio'].'","cpestado_shipto":"'.$datos[$i]['cpestado_shipto'].'","cpcuidad_shipto":"'.$datos[$i]['cpcuidad_shipto'].'","delivery_traking":"'.$datos[$i]['delivery_traking'].'","ShipDT_traking":"'.$datos[$i]['ShipDT_traking'].'","order_date":"'.$datos[$i]['order_date'].'","id_orden_detalle":"'.$datos[$i]['id_orden_detalle'].'"},'; 
        $j++;
      }
      else
      {
          if($j==0){
             $json.='{"title": "'.$guia.'","closeout": "'.$datos[$i]['closeout'].'","cierredia": "'.$datos[$i]['cierredia'].'", "expanded": "true", "folder": "true", "children": [';
            $json.='{"title": "'.$guia.'","tracking":"'.$datos[$i]['tracking'].'","Ponumber":"'.$datos[$i]['Ponumber'].'","Custnumber":"'.$datos[$i]['Custnumber'].'", "cpitem":"'.$datos[$i]['cpitem'].'","cppais_envio":"'.$datos[$i]['cppais_envio'].'","cpestado_shipto":"'.$datos[$i]['cpestado_shipto'].'","cpcuidad_shipto":"'.$datos[$i]['cpcuidad_shipto'].'","delivery_traking":"'.$datos[$i]['delivery_traking'].'","ShipDT_traking":"'.$datos[$i]['ShipDT_traking'].'","order_date":"'.$datos[$i]['order_date'].'","id_orden_detalle":"'.$datos[$i]['id_orden_detalle'].'"}]},'; 
           continue;
          }
          
        
          if($j!=0)
          {
             $json.='{"title": "'.$guia.'","tracking":"'.$datos[$i]['tracking'].'","Ponumber":"'.$datos[$i]['Ponumber'].'","Custnumber":"'.$datos[$i]['Custnumber'].'", "cpitem":"'.$datos[$i]['cpitem'].'","cppais_envio":"'.$datos[$i]['cppais_envio'].'","cpestado_shipto":"'.$datos[$i]['cpestado_shipto'].'","cpcuidad_shipto":"'.$datos[$i]['cpcuidad_shipto'].'","delivery_traking":"'.$datos[$i]['delivery_traking'].'","ShipDT_traking":"'.$datos[$i]['ShipDT_traking'].'","order_date":"'.$datos[$i]['order_date'].'","id_orden_detalle":"'.$datos[$i]['id_orden_detalle'].'"}'; 
             $json.=']},'; 
             $j=0;
             continue;
          }
          $json.='{"title": "'.$guia.'","tracking":"'.$datos[$i]['tracking'].'","Ponumber":"'.$datos[$i]['Ponumber'].'","Custnumber":"'.$datos[$i]['Custnumber'].'", "cpitem":"'.$datos[$i]['cpitem'].'","cppais_envio":"'.$datos[$i]['cppais_envio'].'","cpestado_shipto":"'.$datos[$i]['cpestado_shipto'].'","cpcuidad_shipto":"'.$datos[$i]['cpcuidad_shipto'].'","delivery_traking":"'.$datos[$i]['delivery_traking'].'","ShipDT_traking":"'.$datos[$i]['ShipDT_traking'].'","order_date":"'.$datos[$i]['order_date'].'","id_orden_detalle":"'.$datos[$i]['id_orden_detalle'].'"},'; 
      }
    }
    $json=trim($json, ',');
    $json.="]";
    return $json;
}

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
