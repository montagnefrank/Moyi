<?php
//ini_set('display_errors', 'On');
//ini_set('display_errors', 1);
//Archivo de conexión a la base de datos
include ("conectarSQL.php");
include ("conexion.php");

$conexion = conectar(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)or die('No pudo conectarse : '. mysql_error());

//Variable de búsqueda
$consultaBusqueda = $_POST['valorBusqueda'];

//echo "valor: ".$consultaBusqueda;


//Filtro anti-XSS
//$caracteres_malos = array("<", ">", "\"", "'", "/", "<", ">", "'", "/");
//$caracteres_buenos = array("& lt;", "& gt;", "& quot;", "& #x27;", "& #x2F;", "& #060;", "& #062;", "& #039;", "& #047;");
//$consultaBusqueda = str_replace($caracteres_malos, $caracteres_buenos, $consultaBusqueda);

//Variable vacía (para evitar los E_NOTICE)
$mensaje = "";


//Comprueba si $consultaBusqueda está seteado
if (isset($consultaBusqueda)) {

	//Selecciona todo de la tabla mmv001 
	//donde el nombre sea igual a $consultaBusqueda, 
	//o el apellido sea igual a $consultaBusqueda, 
	//o $consultaBusqueda sea igual a nombre + (espacio) + apellido
	$sql = "SELECT * FROM tblfinca
	WHERE nombre COLLATE UTF8_SPANISH_CI LIKE '%$consultaBusqueda%' 
	OR codigo COLLATE UTF8_SPANISH_CI LIKE '%$consultaBusqueda%'
	OR CONCAT(nombre,' ',codigo) COLLATE UTF8_SPANISH_CI LIKE '%$consultaBusqueda%'";
	$consulta = mysql_query($sql,$conexion);

	//echo "consulta: ".$consulta;

	//Obtiene la cantidad de filas que hay en la consulta
	$filas = mysql_num_rows($consulta);

	//Si no existe ninguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
	if ($filas === 0) {
		//$mensaje = "<p>No hay ningún usuario con ese nombre y/o apellido</p>";
	} else {
		//Si existe alguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
		echo 'Resultados para <strong>'.$consultaBusqueda.'</strong>';


?>

<table width="100%" id="tabla_fincas" border="0" align="center" class="table table-striped" >
<thead>
   <tr>
    <td align="center"><strong>Código</strong></td>
    <td align="center"><strong>Nombre</strong></td>
    <td align="center"><strong>Dirección</strong></td>
    <td align="center"><strong>RUC</strong></td>
    <td align="center"><strong>Ciudad</strong></td>
    <td align="center"><strong>País</strong></td>
</thead>
<tbody>
<?php
		//La variable $resultado contiene el array que se genera en la consulta, así que obtenemos los datos y los mostramos en un bucle
		while($row = mysql_fetch_array($consulta)) {
			$nombre = $row['empresa'];
			$apellido = $row['vendedor'];
			echo "<tr id='".$row['codigo']."' style='cursor:pointer'>";
			echo "<td><a href='#'><strong>".$row['codigo']."</strong></a></td>";
			echo "<td><strong>".$row['nombre']."</strong></td>";
			echo "<td align='center'>".$row['direccion']."</td>";
			echo "<td align='center'>".$row['ruc']."</td>";
			echo "<td align='center'>".$row['prov_ciudad']."</td>";
			echo "<td align='center'>".$row['pais_code']."</td>";
			echo "</tr>";
		};?>
</table> 

<?php
};
};
?>
