<?php
class upstraking 
{
    function upstrack($trackingno,$account_data)
    {
		/*data ="<?xml version=\"1.0\"?>
        <AccessRequest xml:lang='en-US'>
        <AccessLicenseNumber>".$account_data['access_lic_no']."</AccessLicenseNumber>
        <UserId>".$account_data['username']."</UserId>
        <Password>".$account_data['password']."</Password>
        </AccessRequest>
        <?xml version=\"1.0\"?>
        <TrackRequest>
        <Request>
        <TransactionReference>
        <CustomerContext>
        <InternalKey>blah</InternalKey>
        </CustomerContext>
        <XpciVersion>1.0</XpciVersion>
        </TransactionReference>
        <RequestAction>Track</RequestAction>
        </Request>
        <TrackingNumber>".$trackingno."</TrackingNumber>
        </TrackRequest>";*/
		$data = '<?xml version="1.0"?>
<AccessRequest xml:lang="en-US">
  <AccessLicenseNumber>'.$account_data['access_lic_no'].'</AccessLicenseNumber>
  <UserId>'.$account_data['username'].'</UserId>
  <Password>'.$account_data['password'].'</Password>
</AccessRequest>
<?xml version="1.0"?>
<TrackRequest>
  <Request>
    <TransactionReference>
      <CustomerContext>Check Tracking</CustomerContext>
       <XpciVersion>1.0</XpciVersion>
    </TransactionReference>
	<RequestAction>Tracking Progress </RequestAction>
  </Request>
  <TrackingNumber>'.$trackingno.'</TrackingNumber>
</TrackRequest>
';
		$ch = curl_init("https://www.ups.com/ups.app/xml/Track");
		curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_TIMEOUT, 60);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        $result=curl_exec ($ch);
        //echo $result;
        $data = strstr($result, '<?');
        $xml_parser = xml_parser_create();
        xml_parse_into_struct($xml_parser, $data, $vals, $index);
        xml_parser_free($xml_parser);
        $params = array();
        $level = array();
        #print_r($vals);#die();
        foreach ($vals as $xml_elem) {
        if ($xml_elem['type'] == 'open') {
            if (array_key_exists('attributes',$xml_elem)) {
                    list($level[$xml_elem['level']],$extra) = array_values($xml_elem['attributes']);
            } else {
                    $level[$xml_elem['level']] = $xml_elem['tag'];
            }
        }
        if ($xml_elem['type'] == 'complete') {
            if(isset($xml_elem['value']) && $xml_elem['value'] != '') {
                $start_level = 1;
                $php_stmt = '$params';
                while($start_level < $xml_elem['level']) {
                    $php_stmt .= '[$level['.$start_level.']]';
                    $start_level++;
                }
                $php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
                eval($php_stmt);
            }

     }
}
curl_close($ch);

print_r($params);

$table = "";
$table .= "<table class='pure-table pure-table-horizontal' border='1'>";
$table .= "<th>TRACKING</th>";
$table .= "<th>DATE</th>";
$table .= "<th>DESCRIPTION</th>";
$table .= "<th>ADDRESS</th>";
//$table .= "<th>Signatory Name</th>";
//$table .= "<th>Event Type</th>";
$table .= "</tr>";

if($params['TRACKRESPONSE']['RESPONSE']['RESPONSESTATUSDESCRIPTION']=='Success')
{
	//return 0; // ya fue entregado
    $shipment=$params['TRACKRESPONSE']['SHIPMENT'];
    $address_arr=$shipment['PACKAGE']['ACTIVITY']['ACTIVITYLOCATION']['ADDRESS'];
    $address=$address_arr['CITY'].', '.$address_arr['STATEPROVINCECODE'].', '.$address_arr['COUNTRYCODE'];

    $table .= "<tr>";
    $table .= "<td>".$shipment['PACKAGE']['TRACKINGNUMBER']."</td>";
	$table .= "<td>".$shipment['PACKAGE']['ACTIVITY']['DATE']."</td>";
    $table .= "<td>".$shipment['PACKAGE']['ACTIVITY']['STATUS']['STATUSTYPE']['DESCRIPTION'] . "</td>";
    $table .= "<td>".$address."</td>";
    $table .= "</tr>";
/*}else{
	return 0; // no ha sido entregado*/
	
	}

$table .= "</table>";
echo $table;
    }
}

$obj_upstraking = new upstraking();
$account_data = array();
$account_data['access_lic_no']='1CEA1F8720EA71B6';
$account_data['username']='janpaul.sanchez';
$account_data['password']='Welcome1!';
$obj_upstraking->upstrack($trackingno='1Z66631E0458483210',$account_data);

?>
