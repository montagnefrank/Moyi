<?php
include ("seguridad.php");
 //  UPS Quantum API ("Show list of recent tracking information")
 //  DEV server
 $access      = '1CEA1F8720EA71B6';
 $userid      = 'janpaul.sanchez';
 $passwd      = 'Welcome1!';
 $endpointUrl = 'https://wwwcie.ups.com/ups.app/xml/QVEvents';      // URL for testing Quantum
 $outFileName = './XOLTResult.xml'; 


try
{

$data ="<?xml version=\"1.0\"?>
        <AccessRequest xml:lang=\"en-US\">
        <AccessLicenseNumber>$access</AccessLicenseNumber>
        <UserId>$userid</UserId>
        <Password>$passwd</Password>
        </AccessRequest>
        <?xml version=\"1.0\"?>    
        <QuantumViewRequest xml:lang=\"en-US\">
            <Request>
                <TransactionReference>
                    <CustomerContext>Test XML</CustomerContext>
                    <XpciVersion>1.0007</XpciVersion>
                 </TransactionReference>
                 <RequestAction>QVEvents</RequestAction>
                 <IntegrationIndicator></IntegrationIndicator>
            </Request> 
			<TrackingNumber>1Z66631E0458339573</TrackingNumber>
        </QuantumViewRequest>";

  $postData = array
    (
      'content' =>  $data
    );


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);      
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_URL,$endpointUrl);
        curl_setopt($ch, CURLOPT_VERBOSE, 1 );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded'));     
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                // disable SSL verification if not installed
        //curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);             
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);                        // use Secure Socket v3 SSL3
        curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'SSLv3');             
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);        
        curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);


        if( ! $result = curl_exec($ch))
        {
            trigger_error(curl_error($ch));
        } 


        echo $result;

        $data = strstr($result, '<?');
        $xml=simplexml_load_string($data);


        echo "<pre>";
        print_r($xml);

}
catch(Exception $ex)
{
   echo ($ex . "!");
}

curl_close($ch);   