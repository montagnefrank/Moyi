<?php
///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////CONEXION A DB
$user = $_SESSION["login"];
$rol = $_SESSION["rol"];
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
include ("codigounico.php");

$cajas = $_SESSION['cajas'];
$cant = $_SESSION['cant'];


//Seleccionar la finca del pedido a reutilizar
$sql = "SELECT finca FROM tbletiquetasxfinca WHERE codigo='" . $cajas[0] . "'";
$query = mysqli_query($link,$sql)or die("Error seleccionando las cajas");
$row = mysqli_fetch_array($query);
$finca = $row['finca'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Nuevo pedido</title>
        <link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" media="all" href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
        <script type="text/javascript" src="../js/calendar.js"></script>
        <script type="text/javascript" src="../js/calendar-en.js"></script>
        <script type="text/javascript" src="../js/calendar-setup.js"></script>
    </head>
    <body>
        <form id="form1" name="form1" method="post">
            <table width="500" border="0" align="center">
                <tr>
                    <td width="747" height="36" align="center" bgcolor="#3B5998"><strong><font color="#FFFFFF">Reutilizar pedido de la finca <?php echo $finca; ?></font></strong></td>
                </tr>
            </table>
            <table width="500" border="0" align="center">
                <tr height="20"><td></td></tr>
                <tr>
                    <td>
                        <table border="0" align="center">
                            <tr>
                                <td><strong>Finca:</strong></td>
                                <td><input readonly="readonly" type="text" name="finca" id="finca" value="<?php echo $finca ?>"/>                     
                                    <strong>*</strong></td>
                            </tr>
                            <tr>
                                <td><strong>producto:</strong></td>
                                <td><select type="text" name="item" id="item">
                                        <?php
                                        //Consulto la bd para obtener solo los id de item existentes
                                        $sql = "SELECT id_item, prod_descripcion FROM tblproductos order by id_item";
                                        $query = mysqli_query($link,$sql);
                                        //Recorrer los iteme para mostrar
                                        while ($row1 = mysqli_fetch_array($query)) {
                                            echo '<option value="' . $row1["id_item"] . '">' . $row1["id_item"] . ' - ' . $row1["prod_descripcion"] . '</option>';
                                        }
                                        ?>                       
                                    </select>
                                    <strong>*</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Cantidad:</strong></td>
                                <td><input readonly="readonly" type="text" id="cantidad" name="cantidad" value="<?php echo $cant; ?>" />
                                    <strong>*</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Salida de Finca:</strong></td>
                                <td><input type="text" id="fecha" name="fecha" value="" readonly="readonly"/>
                                    <strong>*</strong>
                                    <script type="text/javascript">
                                        function catcalc(cal) {
                                            var date = cal.date;
                                            var time = date.getTime()
                                            // use the _other_ field
                                            var field = document.getElementById("f_calcdate");
                                            if (field == cal.params.inputField) {
                                                field = document.getElementById("fecha");
                                                time -= Date.WEEK; // substract one week
                                            } else {
                                                time += Date.WEEK; // add one week
                                            }
                                            var date2 = new Date(time);
                                            field.value = date2.print("%Y-%m-%d");
                                        }
                                        Calendar.setup({
                                            inputField: "fecha", // id of the input field
                                            ifFormat: "%Y-%m-%d ", // format of the input field
                                            showsTime: false,
                                            timeFormat: "24",
                                            onUpdate: catcalc
                                        });

                                    </script>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Fecha Tentativa de Vuelo :</strong></td>
                                <td><input type="text" id="ftentativa" name="ftentativa" value="" readonly="readonly"/>
                                    <strong>*</strong>
                                    <script type="text/javascript">
                                        function catcalc(cal) {
                                            var date = cal.date;
                                            var time = date.getTime()
                                            // use the _other_ field
                                            var field = document.getElementById("f_calcdate");
                                            if (field == cal.params.inputField) {
                                                field = document.getElementById("ftentativa");
                                                time -= Date.WEEK; // substract one week
                                            } else {
                                                time += Date.WEEK; // add one week
                                            }
                                            var date2 = new Date(time);
                                            field.value = date2.print("%Y-%m-%d");
                                        }
                                        Calendar.setup({
                                            inputField: "ftentativa", // id of the input field
                                            ifFormat: "%Y-%m-%d ", // format of the input field
                                            showsTime: false,
                                            timeFormat: "24",
                                            onUpdate: catcalc
                                        });

                                    </script>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Precio de Compra:</strong></td>
                                <td><input type="text" id="precio" name="precio" value="" />
                                    <strong>*</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Destino:</strong></td>
                                <td><select id="destino" name="destino">
                                        <option value="US">US</option>
                                        <option value="CA">CA</option>
                                    </select>
                                    <strong>*</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Agencia de Carga:</strong></td>
                                <td>
                                    <select id="agencia" name="agencia">
                                        <?php
                                        $sql1 = "SELECT * FROM tblagencia";
                                        $query1 = mysqli_query($link,$sql1) or die("Error leyendo las agencias");
                                        while ($row2 = mysqli_fetch_array($query1)) {
                                            echo '<option value="' . $row2['nombre_agencia'] . '">' . $row2['nombre_agencia'] . '</option>';
                                        }
                                        ?>
                                    </select>

                                    <strong>*</strong></td>
                            </tr>
                            <tr>
                                <td align="right"><input name="Registrar" type="submit" value="Reutilizar" /></td>
                                <td><input name="Cancelar" type="submit" value="Cancelar"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr height="20"><td></td></tr>
                <tr>
                    <td height="36" align="center" bgcolor="#3B5998" colspan="5"><strong><font color="#FFFFFF">Bit <img src="../images/r.png" width="15" height="15"/> 2015 versión 3 </font></strong></td>
                </tr>
            </table>
        </form>
        <?php
        if (isset($_POST["Registrar"])) {
            $finca = $_POST['finca'];
            $item = $_POST['item'];
            $cant = $_POST['cantidad'];
            $fecha = $_POST['fecha'];
            $ftentativ = $_POST['ftentativa'];
            $precio = $_POST['precio'];
            $destino = $_POST['destino'];
            $agencia = $_POST['agencia'];

            //Se genera el numero de orden
            $sql = "SELECT nropedido FROM tbletiquetasxfinca ORDER BY `nropedido` desc LIMIT 1";
            $val = mysqli_query($link,$sql);
            $row = mysqli_fetch_array($val);
            $nro = $row['nropedido'];
            $nro += 1;

            //Por cada caja inserto una nueva solicitud asociaida a la orden generada anteriormente

            for ($i = 0; $i < count($cajas); $i++) {

                //mODIFICO LA ORDEN ORIGINAL PARA QUE QUEDE COMO REUTILIZADA
                $sentencia = "Update tbletiquetasxfinca set estado='4' WHERE codigo='" . $cajas[$i] . "'";
                $consulta = mysqli_query($link,$sentencia) or die("Error modificando pedido anterior");

                //Inserto el nuevo pedido uno por uno hasta que este la cantidad pedida	  
                $sql = "INSERT INTO tbletiquetasxfinca (`codigo`, `finca`, `item`,`fecha`,`solicitado`,`entregado`,`estado`,`nropedido`,`fecha_tentativa`,`precio`,`destino`,`agencia`) VALUES ('$cajas[$i]','$finca','$item','$fecha','1','0','0','$nro','$ftentativ','$precio','$destino','$agencia')";
                $insertado = mysqli_query($link,$sql) or die("Error insertando el pedido");
            }
            if ($insertado && $consulta)
                echo("<script> alert ('Etiquetas listas para volver a imprimir');
								   window.close();					   
								   window.location='reutilizar.php';
			</script>");
        }

        if (isset($_POST["Cancelar"])) {
            echo("<script> window.close()</script>");
        }
        ?>
    </body>
</html>