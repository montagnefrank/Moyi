 <?php
  //  UPS Tracker API - track specfic Waybill
  //  DEV server
  $access      = '1CEA1F8720EA71B6';
  $userid      = 'janpaul.sanchez';
  $passwd      = 'Welcome1!';
  $endpointUrl = 'https://www.ups.com/ups.app/xml/Track';
  $outFileName = './XOLTResult.xml'; 


  // Note: you need at least a UPS DEV account to test this
  $data ="<?xml version=\"1.0\"?><AccessRequest xml:lang='en-US'>
    <AccessLicenseNumber>$access</AccessLicenseNumber>
    <UserId>$userid</UserId>
    <Password>$passwd</Password>
    </AccessRequest>
    <?xml version=\"1.0\"?>
    <TrackRequest>
        <Request>
            <TransactionReference>
                <CustomerContext>
                    <InternalKey>hello</InternalKey>
                </CustomerContext>
                <XpciVersion>1.0</XpciVersion>
            </TransactionReference>
            <RequestAction>Track</RequestAction>
        </Request>
        <TrackingNumber>1Z66631E0458339573</TrackingNumber>
    </TrackRequest>";

    $ch = curl_init("https://www.ups.com/ups.app/xml/Track");
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_TIMEOUT, 60);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
    $result=curl_exec ($ch);
    $data = strstr($result, '<?');
    $xml=simplexml_load_string($data);
    echo "<pre>";
    print_r($xml);
	?>