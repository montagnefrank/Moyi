<?php

$user="eBloomsPLD";
$pass="Password1#";

$handle = fopen ("./archivopld/pld_".$_GET['guia'].".txt", "r"); 
$pldfile = fgets($handle); 
fclose ($handle); 
  
//$pld0200request =urlencode(
//"--BOUNDARY
//Content-type: application/x-www-form-urlencoded
//Content-length: 136
//
//AppVersion")."=".urlencode("1.0")."&AcceptUPSLicenseAgreement=YES&ResponseType=application".
//urlencode("AcceptUPSLicenseAgreement/x-ups-pld")."&VersionNumber=V4R1&UserId=eBloomsPLD&Password=Password1#"
//        
//        
//        .urlencode("--BOUNDARY
//Content-type: application/x-ups-binary
//Content-length: ".strlen($pldfile)."
//
//".$pldfile."
//
//--BOUNDARY--
//");
$pld0200request =
"--BOUNDARY
Content-type: application/x-www-form-urlencoded
Content-length: 136

AppVersion=1.0&AcceptUPSLicenseAgreement=Yes&ResponseType=application/x-ups-pld&VersionNumber=V4R1&UserId=eBloomsPLD&Password=Password1#
    
--BOUNDARY
Content-type: application/x-ups-binary
Content-length: ".strlen($pldfile)."

".$pldfile."

--BOUNDARY--
"; 

$url = "https://www.pld-certify.ups.com/hapld/tos/kdwhapltos";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-Type: multipart/mixed;boundary=BOUNDARY")); 
curl_setopt ($ch, CURLOPT_POST, 0); 
curl_setopt ($ch, CURLOPT_POSTFIELDS,$pld0200request); 
curl_setopt ($ch, CURLOPT_HEADER, 0); 
curl_setopt ($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$response=curl_exec($ch); 
curl_close ($ch); 

if(strpos($response, 'Successful completion'))
{
    echo 'Archivo PLD creado y subido con exito';
}
else
{
    $sql="UPDATE tblguiamaster SET closeout='',cierredia='',book='',page='' WHERE guia='".$_GET["guia"]."'";
    $query = mysqli_query($sql,$conection);
    
    echo 'El Archivo PLD creado contiene errores que deben ser solucionados antes de subirlo correctamente.';
    echo '<p>'.$str = str_replace("\n", "</br>", $response).'</p>';
}

return;