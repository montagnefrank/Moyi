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

//recogiendo el id de la orden a activar o cancelar
$codi = $_GET["id"];
$cajas = $_GET["cajas"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Rechazar solicitud de caja</title>
        <link href="../css/estilo3_e.css" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <form id="form1" name="form1" method="post" action="">
            <table width="500" border="0" align="center" class="alert">
                <tr>
                    <td align="center"colspan="2"><strong>Comentarios</strong></td>
                </tr>
                <tr style="display:none">
                    <td align="center"colspan="2"><input name="cajas" type="text" value="<?php echo $cajas ?>"/></td>
                    <td width="144"colspan="2" align="center"><input name="id" type="text" value="<?php echo $codi ?>"/></td>
                </tr>
                <tr>
                    <td align="center"colspan="2"><textarea name="comentario" cols="50" rows="5" autofocus="autofocus" style="resize:none;"></textarea></td>
                </tr>
                <tr>
                    <td height="30" colspan="2" align="center"><strong><span id="result_box" lang="en" xml:lang="en">Razones</span></strong></td>
                </tr>
                <tr>
                    <td align="center" colspan="2"><table width="399" border="0">
                            <tr>
                                <td width="204"><input name="cajas1[]" type="checkbox" value="BO" />Botritys</td>
                                <td width="185"><input name="cajas1[]" type="checkbox" value="FG" />Flor Guardada</td>

                            </tr>
                            <tr>
                                <td width="204"><input name="cajas1[]" type="checkbox" value="DC" />DAE Caducado</td>
                                <td width="185"><input name="cajas1[]" type="checkbox" value="PE" />Problema de Empaque</td>

                            </tr>
                            <tr>
                                <td width="204"><p>
                                        <input name="cajas1[]" type="checkbox" value="RA" />
                                        Rechazado por Agrocalidad</p></td>
                                <td width="185"><p>
                                        <input name="cajas1[]" type="checkbox" value="NC" />
                                        No es el color Ordenado</p></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="30" colspan="2" align="center"><strong><span id="result_box" lang="en" xml:lang="en">Esta seguro de rechazar la(s) solicitud(es) marcada(s).</span></strong></td>
                </tr>
                <tr>
                    <td width="175" align="right"><input name="si" type="submit" class="btn-danger" id="si" value="Rechazar" /></td>
                    <td width="167" align="left"><input name="no" type="submit" class="alert-info" id="no" value="Cancelar" /></td>
                </tr>
            </table>
        </form>
        <?php
        if (isset($_POST["si"])) {
            $comentario = $_POST['comentario'];
            $cajas = $_POST['cajas'];
            $cajas1 = $_POST['cajas1'];
            $id = $_POST['id'];

            //Obtener las razones de credito seleccionada
            if (is_array($_POST['cajas1'])) {
                $selected = '';
                $num_razones = count($_POST['cajas1']);
                $current = 0;
                foreach ($_POST['cajas1'] as $key => $value) {
                    if ($current != $num_razones - 1)
                        $selected .= $value . ', ';
                    else
                        $selected .= $value;
                    $current++;
                }
            }
            else {
                echo("<script> alert ('Inserte al menos una razón');</script>");
            }
            //Armar el array en php de las razones
            $selected = json_encode($selected);
            //Armar el array en php
            $cajas = explode(",", $cajas);

            //hacer ciclo para recoger cada codigo marcado para asignarle la guia
            for ($i = 0; $i < count($cajas); $i++) {
                $sql = "SELECT estado,nropedido FROM tbletiquetasxfinca WHERE codigo='" . $cajas[$i] . "'";
                $query = mysqli_query($link,$sql) or die("Error consultando estado de las cajas");
                $row = mysqli_fetch_array($query);
                if ($row[0] == '1') {  //Si es entregada 
                    //Modifico el pedido
                    $sql = "Update tbletiquetasxfinca set entregado='0', estado='2', comentario='" . $comentario . "', razones1= '" . $selected . "' WHERE codigo='" . $cajas[$i] . "'";
                    $modificado = mysqli_query($link,$sql) or die("Error");

                    //Elimino las entradas 
                    $sql1 = "DELETE FROM tblcoldroom WHERE codigo='" . $cajas[$i] . "' AND salida='No'";
                    $query1 = mysqli_query($link,$sql1) or die("Error eliminando registro de entrada");
                }
            }
            if ($modificado && $query1) {
                echo("<script> alert ('Etiquetas rechazadas correctamente');
		   window.close();
		   window.opener.document.location='etiqentregada.php?id=" . $id . "';				   
						 </script>");
            } else {
                echo("<script> alert ('Estas cajas no se pueden rechazar pq ya tienen tracking asignado');
		   window.close();
		   window.opener.document.location='etiqentregada.php?id=" . $id . "';				   
						 </script>");
            }
        }

        if (isset($_POST["no"])) {
            echo("<script>window.close();
				   window.opener.document.location='etiqentregada.php?id=" . $_POST['id'] . "';
				   </script>");
        }
        ?>
    </body>
</html>