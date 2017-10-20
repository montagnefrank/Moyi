<?php 
session_start();
$usuario=  $_SESSION["login"];
$ip= $_SERVER['REMOTE_ADDR'];

//incluir otros archivos php
include('conectarSQL.php');
include('conexion.php');
include('date.php');
include('convertHex-Dec.php');
include('consecutivo.php');
include('PHPExcel/IOFactory.php');
include('convertir_Excel.php');
include ("seguridad.php");


$link = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());
 
$fila = 1;
$array = '';
$dir = "./Archivos subidos/";

$orden= 2;
$id_order = 0;
//contar archivos
$total_excel = count(glob("$dir/{*.csv}",GLOB_BRACE));  //("$dir/{*.xlsx,*.xls,*.csv}",GLOB_BRACE));

	//renombrarlos para cargarlos
	$a= 1;
	$excels = (glob("$dir/{*.csv}",GLOB_BRACE));
	 foreach ($excels as $cvs){
            $expr = explode("/",$cvs);
            $nombre=array_pop($expr);
            rename("$dir/$nombre","$dir/$a.csv");		
            $a++;
	 }
	
	//Convertir csv a excel
    try 
           { 		
                   CSVToExcelConverter::convert("$dir/1.csv", "$dir/1.xlsx");
                   unlink($dir."1.csv");  
           } catch(Exception $e) { 
                   echo $e->getMessage(); 
           }
           for($i=1; $i <= $total_excel ; $i++){
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objReader->setReadDataOnly(true);
		//cargamos el archivo que deseamos leer
		$direccion = "$dir/$i.xlsx";
		$objPHPExcel = $objReader->load($direccion);
		$objHoja=$objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	
		foreach ($objHoja as $iIndice=>$objCelda) {
                  $array = explode(";", $objCelda['A']);  
		  $PoNbr  = $array[0];
                  $CustNbr  = $array[1];
                  $item  = $array[2];
                  $tracking  = $array[3];
               
	  //Obtengo el Ponumber, para verificar cuantas ordenes hay con ese ponumber
	  //$sql="SELECT id_orden_detalle,id_detalleorden FROM tbldetalle_orden WHERE Ponumber='".$PoNbr."' AND Custnumber = '".$CustNbr."'";
	  $sql="SELECT id_orden_detalle,id_detalleorden,tracking FROM tbldetalle_orden WHERE Ponumber='".$PoNbr."' AND Custnumber = '".$CustNbr."' and cpitem='".$item."'";
          echo $sql."</br>";
	  $query= mysql_query($sql,$link);
	  $row = mysql_fetch_array($query); 
	  
          $sql = "UPDATE tbldetalle_orden SET tracking='', status='Ready to ship', descargada='not downloaded', user='', farm='', coldroom='No', codigo='0000000000' WHERE id_orden_detalle='".$row['id_orden_detalle']."' AND tracking= '".$row['tracking']."'";
          echo $sql."</br>";
          $eliminado= mysql_query($sql,$link);

          $sqlcold = "UPDATE tblcoldroom SET tracking_asig='', guia_hija='0', guia_madre='0', salida='No' WHERE tracking_asig = '".$row['tracking']."' and item='".$item."'";
           echo $sqlcold."</br>";
          $querycold= mysql_query($sqlcold,$link);

          if($eliminado){
          $usuarioLog = $_SESSION["login"];
          $ip = getRealIP();
          $fecha = date('Y-m-d H:i:s');
          $operacion = "Eliminar tracking: PO: ".$PoNbr." Trk: ".$row['tracking'];
          $SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`,`razon`) 
                                             VALUES ('$usuarioLog','$operacion','$fecha','$ip','')";
          $consultaHist = mysql_query($SqlHistorico,$link) or die ("Error actualizando la bitácora de usuarios");

          }
         }
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