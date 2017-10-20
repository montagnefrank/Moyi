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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Cajas rechazadas</title>
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
    </head>
    <body background="../images/fondo.jpg">
        <form id="form1" name="form1" method="post">
            <table width="1024" border="0" align="center">
                <tr>
                    <td height="133" align="center" width="100%"><img src="../images/logo.png" width="328" height="110" /></td>
                </tr>
                <tr>
                    <td width="100%" colspan="2"><input type="image" src="../images/home.png" height="40" width="30" title="Inicio" formaction="imp_etiquetas.php">
                    </td>
                </tr>
        </form>
        <form id="form" name="form" method="post">
            <tr>
                <td id="inicio" bgcolor="#CCCCCC" height="100"> 
                    <table width="800" border="0" align="center"> 
                        <tr>
                            <td colspan="6" align="center">
                                <h3><strong>Cajas rechazadas</strong></h3>
                            </td>
                        </tr>
                        <tr bgcolor="#E8F1FD">
                            <td align="center"><strong>Código</strong></td>
                            <td align="center"><strong>Finca</strong></td>
                            <td align="center"><strong>Producto</strong></td>
                            <td align="center"><strong>Fecha Vuelo</strong></td>
                            <td align="center"><strong>Comentario</strong></td>
                            <td align="center"><strong>Razones</strong></td>
                        </tr>
                        <?php
                        //if($_GET['id']>= 1){
                        $sql = "SELECT * FROM tbletiquetasxfinca where estado= 2 AND nropedido ='" . $id . "'";
                        $val = mysqli_query($link,$sql);
                        if (!$val) {
                            echo "<tr><td>" . mysqli_error() . "</td></tr>";
                        } else {
                            $cant = 0;
                            while ($row = mysqli_fetch_array($val)) {
                                $cant ++;
                                echo "<tr>";
                                echo "<td align='center'><strong>" . $row['codigo'] . "</strong></td>";
                                echo "<td>" . $row['finca'] . "</td>";
                                echo "<td align='center'>" . $row['item'] . "</td>";
                                echo "<td align='center'>" . $row['fecha'] . "</td>";
                                echo "<td align='center'>" . $row['comentario'] . "</td>";
                                echo "<td align='center'>" . $row['razones1'] . "</td>";
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
                <td colspan="6" align="center">
                    <h3><strong>Leyenda de razones</strong></h3>
                </td>
            </tr>
            <tr>
                <td align="center"><strong>BO:</strong> BOTRITYS --
                    <strong>FG:</strong> FLOR GUARDADA --
                    <strong>DC:</strong> DAE CADUCADO</td>
            </tr>
            <tr>
                <td align="center"><strong>PE:</strong> PROBLEMA DE EMPAQUE --
                    <strong>RA:</strong> RECHAZO POR AGROCALIDAD --
                    <strong>NC:</strong> NO ES EL COLOR ORDENADO</td>
            </tr>
            <tr>
                <td height="36" align="center" bgcolor="#3B5998" colspan="5"><strong><font color="#FFFFFF">Bit <img src="../images/r.png" width="15" height="15"/> 2015 versión 3 </font></strong></td>
            </tr>
            </table>
    </body>
</html>

