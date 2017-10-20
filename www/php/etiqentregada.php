<?php
///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");

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
$id = $_GET['id'];
$cajas[0] = 0;
//echo $id;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Etiquetas entregadas</title>
        <script type="text/javascript" src="../js/script.js"></script>
        <link rel="icon" type="image/png" href="../images/favicon.ico" />
        <link rel="stylesheet" type="text/css" media="all" href="../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
        <script type="text/javascript" src="../js/jquery-1.2.1.pack.js"></script>
        <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
        <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
        <script type="text/javascript" src="../js/calendar.js"></script>
        <script type="text/javascript" src="../js/calendar-en.js"></script>
        <script type="text/javascript" src="../js/calendar-setup.js"></script>
        <script type="text/javascript" src="../js/script.js"></script>
        <script type="text/javascript">
            function marcar() {
                for (var i = 0; i < document.form.elements.length; i++) {
                    if (document.form.elements[i].type == 'checkbox') {
                        if (document.form.elements[i].disabled == false) {
                            document.form.elements[i].checked = !(document.form.elements[i].checked);
                        }
                    }
                }
            }

            function filtrar(nropedido) {
                alert(nropedido);
                var cajas = [];
                var caja = [];
                for (i = 0; i < document.form.elements.length; i++) {
                    if ((document.form[i].type == 'checkbox') && (document.form[i].checked == true)) {
                        cajas[i] = document.form[i].value;
                    } else {
                        cajas[i] = 0;
                    }
                }

                window.open("rechazarent.php?id=" + nropedido + "&cajas=" + cajas, "Cantidad", "width=500,height=360,top=100,left=400");
            }
        </script>
    </head>
    <body background="../images/fondo.jpg">
        <form id="form1" name="form1" method="post">
            <table width="1024" border="0" align="center">
                <tr>
                    <td height="133" align="center" width="100%"><img src="../images/logo.png" width="328" height="110" /></td>
                </tr>
                <tr>
                    <td width="100%" colspan="2"><input type="image" src="../images/home.png" height="40" width="30" title="Inicio" formaction="etiquetasexistentes.php">
                    </td>
                </tr>
        </form>
        <form id="form" name="form" method="post">
            <tr>
                <td id="inicio" bgcolor="#CCCCCC" height="100"> 
                    <table width="800" border="0" align="center"> 
                        <tr>
                            <td colspan="4" align="center">
                                <h3><strong>Cajas entregadas</strong></h3>
                            </td>

                            <td width="81" align="right">  
                                <?php echo '<input type="image" style="cursor:pointer" name="btn_cliente1" id="btn_cliente1" heigth="40" value="" title = "Rechazar cajas" width="30" src="../images/rechazar.png" onclick="filtrar(' . $id . ')"/>';
                                ?>
                                <input type="image" style="cursor:pointer" name="btn_cliente" id="btn_cliente" src="../images/print.ico" heigth="40" value="" title = "Imprimir Listado" width="30" onclick = ""/>
                            </td>
                        </tr>
                        <tr bgcolor="#E8F1FD">
                            <td width="91" align="center"><strong>Código de caja</strong></td>
                            <td width="379" align="center"><strong>Finca</strong></td>
                            <td width="123" align="center"><strong>Producto</strong></td>
                            <td width="104" align="center"><strong>Fecha Vuelo</strong></td>
                            <td align="center"><strong><input type="checkbox" value="X" onchange="marcar()" title="Marcar todos"/></strong></td>
                        </tr>
                        <?php
                        //if($_GET['id']>= 1){
                        $sql = "SELECT * FROM tbletiquetasxfinca INNER JOIN tblcoldroom ON tbletiquetasxfinca.codigo = tblcoldroom.codigo where estado= 1 AND nropedido ='" . $id . "'";
                        $val = mysqli_query($link,$sql);
                        if (!$val) {
                            echo "<tr><td>" . mysqli_error($link) . "</td></tr>";
                        } else {
                            $cant = 0;
                            while ($row = mysqli_fetch_array($val)) {
                                $cant ++;
                                echo "<tr>";
                                echo "<td align='center'><strong>" . $row['codigo'] . "</strong></td>";
                                echo "<td>" . $row['finca'] . "</td>";
                                echo "<td align='center'>" . $row['item'] . "</td>";
                                echo "<td align='center'>" . $row['fecha'] . "</td>";
                                if ($row['salida'] == 'No') {
                                    echo '<td align="center"><input name="cajas[]" type="checkbox" value="' . $row['codigo'] . '" title="Marcar caja"/></td>';
                                } else {
                                    echo '<td align="center"><input name="cajas[]" type="checkbox"  title="Esta caja no se puede rechazar pq ya ha sido traqueada" disabled="true"/></td>';
                                }

                                echo "</tr>";
                            }
                            echo "<tr><td></td></tr>
								  <tr>
								  <td align='right'><strong>" . $cant . "</strong></td>
								  <td>Órden(es) encontradas</td>
								  </tr>";
                        }
                        $_SESSION ['filtro'] = $sql;

//}
                        ?>			   
                        </form>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="36" align="center" bgcolor="#3B5998" colspan="5"><strong><font color="#FFFFFF">Bit <img src="../images/r.png" width="15" height="15"/> 2015 versión 3 </font></strong></td>
            </tr>
            </table>
    </body>
</html>

