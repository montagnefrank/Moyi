<?php
	session_start();
	require_once('conexion.php');
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link) {
		die('Fallo la conexion con el servidor ' . mysql_error());
	}
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Ubase de datos no encontrada");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Buscar Cliente</title>
<script type="text/javascript" src="../js/jquery-1.2.1.pack.js"></script>
<base target="contents">
<!--Validar texto-->
<script type="text/javascript">
function sololetras(oEvent) {
var iKeyCode;
if(document.all){
iKeyCode = oEvent.keyCode;
}else{
iKeyCode = oEvent.which;
}
if(((iKeyCode > 47) && (iKeyCode < 58))){
//	alert('No puede ingresar numeros');
return false;
}
}
</script>


<!--Validacionde Numeros-->
<script>
function validar_texto(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
		alert('No puede ingresar letras');
        return true;
    }
        
    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-9]/;
    
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}
</script>
				

<script type="text/javascript">
	function lookup(inputString) {
		
		if(inputString.length == 0) {
			// Hide the suggestion box.
			$('#suggestions').hide();
		} else {
			$.post("bcd_cliente.php", {queryString: ""+inputString+""}, function(data){
				if(data.length >0) {
					$('#suggestions').show();
					$('#autoSuggestionsList').html(data);
				}
			});
		}
	} // lookup
	
	function fill(thisValue) {
	
		$('#inputString').val(thisValue);
		setTimeout("$('#suggestions').hide();", 200);
	}
</script>

<link href="../css/estilo_personalizado.css" rel="stylesheet" type="text/css" />
</head>

<body background="../images/fondo.jpg">
 
    <form method="post" id="frm1" name="frm1" >
    <table width="627" border="0" cellspacing="5" cellpadding="5" class="hovertable" align="center">
        <td height="133" align="center" colspan="6"><img src="../images/logo.png" width="328" height="110" /></td>
  </tr>
    <tr>  
    <td height="34" align="center" bgcolor="#3B5998" colspan="6"></td>
  </tr>
      <tr>
    <th  colspan="5" >BUSQUEDA DE CLIENTES</th>
  </tr>
      <tr>
        <td width="81" ><strong>Nombre:</strong></td>
        <td width="195"><input name="inputString2" type="text" class="text"  id="inputString2" onBlur="fill();" onKeyPress="return sololetras(event)" onKeyUp="lookup(this.value);" value="" size="25" /></td>
        <td width="89" > <strong>C&eacute;dula:</strong></td>
        <td width="197"><input name="inputString" type="text" class="text"  id="inputString" onBlur="fill();" onKeyPress="return validar_texto(event)" onKeyUp="lookup(this.value);" size="20" maxlength="10"/></td>
        
      </tr>
      <tr>
        <td colspan="5"><div class="suggestionsBox" id="suggestions">&nbsp;
          </p>
          <div class="suggestionList" id="autoSuggestionsList"> &nbsp;</div>
        </div></td>
      </tr>
              <tr>
    <td height="36" align="center" bgcolor="#3B5998" colspan="6"><strong><font color="#FFFFFF">Bit <img src="../images/r.png" width="15" height="15"/> 2015 versión 3 </font></strong></td>
  </tr>
</table>
</form>
    <p>&nbsp;</p>
</body>
</html>