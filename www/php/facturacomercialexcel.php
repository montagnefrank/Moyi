<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Commercial Invoice</title>
<script type="text/javascript" src="../js/script.js"></script>
 <link rel="icon" type="image/png" href="../images/favicon.ico" />
  <link rel="stylesheet" type="text/css" media="all" href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="../js/jquery-1.2.1.pack.js"></script>
<script type="text/javascript" src="../js/jquery-1.3.2.min.js"></script>
  <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
  <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
  <script type="text/javascript" src="../js/calendar.js"></script>
  <script type="text/javascript" src="../js/calendar-en.js"></script>
<script type="text/javascript" src="../js/calendar-setup.js"></script>
<script type="text/javascript" src="../js/script.js"></script>
<script>
function marcar() {
  for (var i = 0; i < document.form.elements.length; i++) {
    if(document.form.elements[i].type == 'checkbox'){
		if(document.form.elements[i].disabled == false){
      		document.form.elements[i].checked = !(document.form.elements[i].checked);
		}
    }
  }
}

function filtrar(){
	var cajas = [];
	var caja  = [];
	for(i=0;i<document.form.elements.length; i++){
		if((document.form[i].type == 'checkbox') && (document.form[i].checked == true)){
				cajas[i] = document.form[i].value;
		}else{
			cajas[i] = 0;
			}
    }

	window.open("archivar.php?codigo=0"+"&cajas="+cajas,"Cantidad","width=500,height=360,top=100,left=400,resizable=no,status=no");
	}
</script>
</head>
<body background="../images/fondo.jpg">
<table width="800" border="0" align="center">
  <tr>
    <td height="34" align="center" bgcolor="#3B5998" colspan="8"></td>
  </tr>
   <tr>
        <td colspan="6"></td>
      <!--  <td width="3%" align="right">    <form action="crearPdf.php" method="post" target="_blank">    
         <input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/pdf.png" heigth="40" value="" title = "Exportar a pdf" width="30" onclick = ""/>
         </form></td>
        <td width="3%"><form action="crearcsv1.php" method="post" target="_blank">
         <input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/excel.png" heigth="40" value="" title = "Exportar a excel" width="30" onclick = ""/>
         </form>
         
         </td>-->
    </tr>
</table>

<table border="1" align="center" width="800" style="border-collapse: collapse;">
<?php
  //Recorro cada codigo de cajas para cosntruir la factura comercial
for ($i=0; $i < count($cajas);$i++){
	if($cajas[$i] !=0){
		$sql = "SELECT codigo,item,finca,fecha_vuelo,fecha_entrega,servicio,guia_madre,guia_hija FROM tblcoldroom WHERE codigo = '".$cajas[$i]."' ORDER BY finca";
		$query = mysql_query($sql,$conection);
		$row   = mysql_fetch_array($query);
		//Se usan 8 columnas para crear la factura	
		echo '<tr bgcolor="#99CCFF">';
			echo '<td align="center" colspan="8"><strong>Comercial Invoice</strong></td>';
		echo '</tr>';
		//FECHA
		echo '<tr>';
			echo '<td align="center" colspan="6">&nbsp;</td>';
			echo '<td align="center" colspan="2"><strong>Date Finca</strong></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="center" colspan="6">&nbsp;</td>';
			echo '<td align="center" colspan="2">'.$row['fecha_entrega'].'</td>';
		echo '</tr>';
		//DATOS FINCA Y FECHA DE VUELO
		echo '<tr>';
			echo '<td  align="left" colspan="4"><strong>Shipper Name and Addres</strong>s</td>';
			echo '<td align="center" colspan="2"><strong>Farm Code</strong></td>';
			echo '<td align="center" colspan="2"><strong>Date Vuelo</strong></td>';
		echo '</tr>';
		
		//Consultamos los datos de la finca
		$sql1   = "SELECT * FROM tblfinca WHERE nombre = '".$row['finca']."'";
		$query1 = mysql_query($sql1,$conection);
		$row1   = mysql_fetch_array($query1);
		echo '<tr>';
			echo '<td align="left" colspan="4">'.$row['finca'].'</td>';
			echo '<td align="center" colspan="2">'.$row1['farm_code'].'</td>';
			echo '<td align="center" colspan="2">'.$row['fecha_vuelo'].'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" colspan="4">TELF:'.$row1['telefono'].'</td>';
			echo '<td align="center" colspan="2"><strong>Country Code</strong></td>';
			echo '<td align="center" colspan="2"><strong>Invoice No.</strong></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" colspan="4">'.$row1['prov_ciudad'].'</td>';
			echo '<td align="center" colspan="2">'.$row1['pais_code'].'</td>';
			echo '<td align="center" colspan="2">######</td>';
		echo '</tr>';
		//Marketing name
		echo '<tr>';
			echo '<td align="left" colspan="8"><strong>Marketing Name</strong>s</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" colspan="4"></td>';
			echo '<td align="left" colspan="4"><strong>AWB</strong></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" colspan="4">EBLOOMS DIRECT INC.</td>';
			echo '<td align="center" colspan="4">'.$row['guia_madre'].'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" colspan="4"></td>';
			echo '<td align="left" colspan="4"><strong>HAWB</strong></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" colspan="4"></td>';
			echo '<td align="center" colspan="4">'.$row['guia_hija'].'</td>';
		echo '</tr>';
		
		//	Foreign purchaser
		echo '<tr>';
			echo '<td align="left" colspan="8"><strong>Foreign Purchaser</strong>s</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" colspan="4">INBLOOM GROUP, LLC</td>';
			echo '<td align="center" colspan="4"><strong>Air Line & Flight #</strong></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" colspan="4">2846 NW 79 AVE</td>';
			echo '<td align="center" colspan="4">AIRLINE #####</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" colspan="4">DORAL FL 33122</td>';
			echo '<td align="center" colspan="4"><strong>ADD Cse #</strong></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" colspan="4">Telefax 305 436 0030</td>';
			echo '<td align="center" colspan="4"><strong>####</strong></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" colspan="4">Fax : 305 436 0022</td>';
			echo '<td align="center" colspan="4"></td>';
		echo '</tr>';
		
		//Country origen
		echo '<tr>';
			echo '<td align="center" colspan="4"></td>';
			echo '<td align="left" colspan="2"><strong>Country origen</strong></td>';
			echo '<td align="center" colspan="2"><strong>DAE No.</strong></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="center" colspan="4"></td>';
			echo '<td align="left" colspan="2">'.$row1['prov_ciudad'].'</td>';
			// Seleccionar el dae de esa finca
			$a = "SELECT dae FROM tbldae WHERE nombre_finca='".$row['finca']."'";
			$b = mysql_query($a, $conection);
			$fila = mysql_fetch_array($b);
			    			
			echo '<td align="center" colspan="2">'.$fila['dae'].'</strong></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" colspan="4"></td>';
			echo '<td align="left" colspan="4"><strong>Consignmet/ Consignación ( )</strong></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" colspan="4"></td>';
			echo '<td align="left" colspan="4"><strong>Fixed Price /Venta Directa  ( )</strong></td>';
		echo '</tr>';
		
		//Descrpciones, piezas, etc
		echo '<tr>';
			echo '<td align="center"><strong>Pieces</strong></td>';
			echo '<td align="center"><strong>Description</strong></td>';
			echo '<td align="center"><strong>ATPA</strong></td>';
			echo '<td align="center"><strong>HTS No.</strong></td>';
			echo '<td align="center"><strong>TOTAL UNITS</strong></td>';
			echo '<td align="center"><strong>Stem/bunch</strong></td>';
			echo '<td align="center"><strong>Price</strong></td>';
			echo '<td align="center"><strong>TOTAL</strong></td>';
		echo '</tr>';
		//Crear fila por cada tipo de item que haya en ese pedido
		//Seleccionar doatos asociado a ese item
		$d = "SELECT * FROM tblproductos WHERE id_item='".$row['item']."'";
		$e = mysql_query($d,$conection);
		$fila1 = mysql_fetch_array($e);
		echo '<tr>';
			echo '<td align="center"></td>';
			echo '<td align="center">'.$fila1['gen_desc'].'</td>';
			echo '<td align="center"></td>';
			echo '<td align="center">'.$fila1['tarif_code'].'</td>';
			echo '<td align="center"></td>';
			echo '<td align="center"></td>';
			echo '<td align="center"></td>';
			echo '<td align="center"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="center"></td>';
			echo '<td align="center"></td>';
			echo '<td align="center"></td>';
			echo '<td align="center"></td>';
			echo '<td align="center"></td>';
			echo '<td align="center"></td>';
			echo '<td align="center"></td>';
			echo '<td align="center"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="center">####</td>';
			echo '<td align="left" colspan="3"><strong>Total pieces</strong></td>';
			echo '<td align="right" colspan="3"><strong>Total Value</strong></td>';
			echo '<td align="center">####</td>';
		echo '</tr>';
		
		//Piezas descripcion
		echo '<tr>';
			echo '<td align="right" colspan="8"><strong>Pieces description</strong></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="center"><strong>Full 1/1</strong></td>';
			echo '<td align="center"><strong>Half 1/2</strong></td>';
			echo '<td align="center"><strong>Qtrs 1/4</strong></td>';
			echo '<td align="center"><strong>Sixth 1/6</strong></td>';
			echo '<td align="center"><strong>Eight 1/8</strong></td>';
			echo '<td align="center"><strong>Humpers</strong></td>';
			echo '<td align="center"><strong>Wet pack</strong></td>';
			echo '<td align="center"><strong>Total full Box</strong></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="center">##</td>';
			echo '<td align="center">##</td>';
			echo '<td align="center">##</td>';
			echo '<td align="center">##</td>';
			echo '<td align="center">##</td>';
			echo '<td align="center">##</td>';
			echo '<td align="center">##</td>';
			echo '<td align="center">##</td>';
		echo '</tr>';
		
		//Nombre y titulo
		echo '<tr>';
			echo '<td align="left" colspan="4"><strong>Name and Title of Person Preparing Invoice</strong></td>';
			echo '<td align="left" colspan="4"><strong>Freinght Fowarder</strong></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" colspan="4">####</td>';
			echo '<td align="left" colspan="4">####</td>';
		echo '</tr>';
		
		//Final
		echo '<tr height="60">';
			echo '<td align="left" colspan="4"></td>';
			echo '<td align="left" colspan="4"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="center" colspan="4"><strong>Customs Use Onty</strong></td>';
			echo '<td align="center" colspan="4"><strong>USDA, APHIS P.P.Q. Use Only</strong>
	</td>';
		echo '</tr>';
		
		
		echo '<tr>';
			echo '<td align="center" colspan="8">&nbsp;</td>';
		echo '</tr>';
	}
}
echo '</table>';
?>
   
<table border="0" align="center" width="800">
<tr>
    <td height="36" align="center" bgcolor="#3B5998" colspan="5"><strong><font color="#FFFFFF">Bit <img src="../images/r.png" width="15" height="15"/> 2015 versión 3 </font></strong></td>
  </tr>
  </table>
</body>
</html>