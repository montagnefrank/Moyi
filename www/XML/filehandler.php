<?php

//Clase para manejar los archivos XML, básicamente cargar el archivo y obtener datos del mismo

class xmlHandler
{
	private $messageCount=0;
	private $xml="";
	private $hubOrder="";
	private $lineItem="";
	private $poNumber="";

	function __construct($fileName)
	{
		$this->xml = simplexml_load_file($fileName);
	}

	function getXML(){
		return $this->xml;
	}

	function MessageCount(){
		return $this->xml->messageCount;
	}

	function HubOrdersCount(){
		return count($this->xml->hubOrder);	 
	}

	function getHubOrders(){
		for ($i=0; $i < $this->xml->messageCount; $i++) { 
			foreach ($this->xml->hubOrder[$i]->children() as $hubOrder) {
				$this->hubOrder .= " ". $hubOrder;
			}
		}
		return $this->hubOrder;
	}

	function getHubOrder($index){
		return $this->xml->hubOrder[$index];
	}

	function getLineItem($hubOrderIndex,$lineItemIndex){
		return $this->xml->hubOrder[$hubOrderIndex]->lineItem[$lineItemIndex];
	}

	function LineItemCount($hubOrderIndex) {
		return count($this->xml->hubOrder[$hubOrderIndex]->lineItem);
	}

	function getPersonPlaceId($hubOrderIndex,$personPlaceIndex){
		return $this->xml->hubOrder[$i]->personPlace[$k]->attributes()->personPlaceID;
	}

	function moveXMLFile($origen,$destino) {
		if(copy($origen, "C:\Program Files\RSSBus\RSSBus Server\data\as2connector\profiles\COMMERCEHUB\Procesados"."\\".$destino)){
			unlink($origen);
			return true;
		}
		else{
			return false;
		}
	}
        function moveXMLAlertFile($origen,$destino) {
		if(copy($origen, "C:\Program Files\RSSBus\RSSBus Server\data\as2connector\profiles\COMMERCEHUB\Irregulares"."\\".$destino)){
			unlink($origen);
			return true;
		}
		else{
			return false;
		}
	}
        function moveXMLAuditFile($origen,$destino) {
		if(copy($origen, "C:\Program Files\RSSBus\RSSBus Server\data\as2connector\profiles\COMMERCEHUB\Auditados"."\\".$destino)){
			unlink($origen);
			return true;
		}
		else{
			return false;
		}
	}
        function moveXMLDefectFile($origen,$destino) {
		if(copy($origen, "C:\Program Files\RSSBus\RSSBus Server\data\as2connector\profiles\COMMERCEHUB\Defectuosos"."\\".$destino)){
			unlink($origen);
			return true;
		}
		else{
			return false;
		}
	}
        function moveXMLOSDFile($origen,$destino) {
		if(copy($origen, "C:\Program Files\RSSBus\RSSBus Server\data\as2connector\profiles\COMMERCEHUB\Xmlosd"."\\".$destino)){
			unlink($origen);
			return true;
		}
		else{
			return false;
		}
	}
        function moveXMLvalFile($origen,$destino) {
		if(copy($origen, "C:\Program Files\RSSBus\RSSBus Server\data\as2connector\profiles\COMMERCEHUB\Validate"."\\".$destino)){
			unlink($origen);
			return true;
		}
		else{
			return false;
		}
	}
}
?>