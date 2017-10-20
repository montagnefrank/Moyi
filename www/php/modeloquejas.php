<?php

///////////////////////////////////////////////////////////////////////////////////////////DEBUG EN PANTALLA
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
require ("../scripts/conn.php");
require ("../scripts/islogged.php");
include ("track.php");

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


if (isset($_POST['datos_orden'])) {
    $id = $_POST['id'];

    $sql = "SELECT id_orden_detalle,estado_orden,Ponumber, Custnumber, cpitem, delivery_traking,reenvio, soldto1, unitprice
            FROM
            tblorden
            INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
            INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
            WHERE id_orden_detalle = '" . $id . "';";
    $query = mysqli_query($link, $sql);

    $i = 0;
    $dato = array();
    while ($fila = mysqli_fetch_array($query)) {
        $dato[$i++] = $fila;
    }

    $sql = "SELECT * FROM tblcustom_services WHERE id_orden='" . $id . "'";
    $query = mysqli_query($link, $sql)or die("Error leyendo las quejas de esta orden");
    $row1 = mysqli_fetch_array($query);
    if ($row1['credito'] <> 0) {
        $dato[$i] = $row1['credito'];
    } else {
        $dato[$i] = 0;
    }

    echo json_encode($dato);
    return;
//    $sql= "SELECT * FROM tblcustom_services WHERE id_orden='".$id."'";
//    $query = mysqli_query($link, $sql)or die ("Error leyendo las quejas de esta orden");
//    $row1 = mysqli_fetch_array($query);
}

//para insertar una nueva queja
if (isset($_POST['accion']) && $_POST['accion'] == 'registrar') {
    $id = $_POST['id'];
    $credito = $_POST['credito'];
    $fecha = date('Y-m-d');
    $reenvio = 'No';

    $sql = "SELECT id_orden_detalle,estado_orden,Ponumber, Custnumber, cpitem, delivery_traking,reenvio, soldto1, unitprice
            FROM
            tblorden
            INNER JOIN tblsoldto ON tblorden.id_orden = tblsoldto.id_soldto
            INNER JOIN tbldetalle_orden ON tbldetalle_orden.id_detalleorden = tblorden.id_orden
            WHERE id_orden_detalle = '" . $id . "'";
    $query = mysqli_query($link, $sql);

    while ($fila = mysqli_fetch_array($query)) {
        $ponumber = $fila['Ponumber'];
        $custnumber = $fila['Custnumber'];
        $id_orden = $fila['id_orden_detalle'];
    }

    $sentencia = "SELECT * FROM tblcustom_services WHERE id_orden = '" . $id_orden . "'";
    $consulta = mysqli_query($link, $sentencia) or die(mysqli_error($consulta));
    $cantfila = mysqli_num_rows($consulta);

    if ($cantfila == 0) {
        //inserto en customer service 
        $sql = "INSERT INTO tblcustom_services (`ponumber`, `custnumber`, `reenvio`,`credito`,`id_orden`) VALUES ('$ponumber','$custnumber ','$reenvio','$credito','$id_orden')";
        $row = mysqli_query($link, $sql);

        $insert_last_id = "SELECT id_custom FROM tblcustom_services GROUP BY id_custom DESC LIMIT 1";
        $result_insert_last_id = mysqli_query($link, $insert_last_id);
        $row_insert_last_id = mysqli_fetch_array($result_insert_last_id);
        $insertado = $row_insert_last_id[0];

        //obtengo listado de las causas
        $listacausas = explode(",", $_POST['listacausas']);

        foreach ($listacausas as $opcionId) {
            //inserto en la tabla de causas
            $sql = "INSERT INTO tblcausas_devolucion (`id_orden`, `id_causa`) VALUES ('$insertado','$opcionId')";
            mysqli_query($link, $sql);
        }

        //obtengo listado de quejas
        $i = 0;
        $queja = array();
        foreach ($_POST["queja"] as $val) {
            $queja[$i++] = $val;
        }

        //obtengo listado de fechas
        $i = 0;
        $fecha = array();
        foreach ($_POST["fecha"] as $val) {
            $fecha[$i++] = $val;
        }
        //inserto en la tabla de quejas
        for ($i = 0; $i < count($fecha); $i++) {
            $queja[$i] = addslashes($queja[$i]);
            $sql = "INSERT INTO tblquejas (`fecha`, `queja`,`id_customer`) VALUES ('$fecha[$i]','$queja[$i]','$insertado')";
            mysqli_query($link, $sql);
        }
    } else {
        $sql = "UPDATE tblcustom_services set ponumber='$ponumber',custnumber='$custnumber',reenvio='$reenvio',credito='$credito' WHERE id_orden='$id_orden'";
        $res = mysqli_query($link, $sql);

        //borro de la tabla de causas
        $sql = "DELETE FROM tblcausas_devolucion WHERE id_orden=(SELECT id_custom FROM tblcustom_services WHERE id_orden='$id')";
        $query = mysqli_query($link, $sql);

        $sql1 = "SELECT id_custom FROM tblcustom_services WHERE id_orden='$id'";
        $query1 = mysqli_query($link, $sql1);
        $a = mysqli_fetch_row($query1);
        $insertado = $a[0];

        //obtengo listado de las causas
        $listacausas = explode(",", $_POST['listacausas']);
        foreach ($listacausas as $opcionId) {
            //inserto en la tabla de causas
            $sql = "INSERT INTO tblcausas_devolucion (`id_orden`, `id_causa`) VALUES ('$insertado','$opcionId')";
            mysqli_query($link, $sql);
        }


        //borro de la tabla de causas
        $sql = "DELETE FROM tblquejas WHERE id_customer=(SELECT id_custom FROM tblcustom_services WHERE id_orden='$id')";
        $query = mysqli_query($link, $sql);

        //obtengo listado de quejas
        $i = 0;
        $queja = array();
        foreach ($_POST["queja"] as $val) {
            $queja[$i++] = $val;
        }

        //obtengo listado de fechas
        $i = 0;
        $fecha = array();
        foreach ($_POST["fecha"] as $val) {
            $fecha[$i++] = $val;
        }
        //inserto en la tabla de quejas
        for ($i = 0; $i < count($fecha); $i++) {
            $sql = "INSERT INTO tblquejas (`fecha`, `queja`,`id_customer`) VALUES ('$fecha[$i]','$queja[$i]','$insertado')";
            mysqli_query($link, $sql);
        }
    }
}


//para eliminar una queja
if (isset($_POST['accion']) && $_POST['accion'] == 'eliminar_quejas') {
    $id = $_POST['id'];
    $sql = "DELETE FROM tblquejas WHERE id='" . $id . "'";
    $query = mysqli_query($link, $sql);
    return;
}

if (isset($_POST['accion']) && $_POST['accion'] == 'obtener_causas') {
    $id = $_POST['id'];

    $sql = "SELECT * FROM tblcausas";
    $query = mysqli_query($link, $sql);
    $option = '';

    $iii = 0;
    while ($row = mysqli_fetch_array($query)) {

        //compruebo si esa causa esta asiganada a la orden
        $sql1 = "SELECT tblcausas_devolucion.id_causa FROM tblcausas_devolucion
           INNER JOIN tblcustom_services ON tblcausas_devolucion.id_orden = tblcustom_services.id_custom
           WHERE tblcustom_services.id_orden = '$id' AND id_causa='" . $row['id'] . "'";
        $query1 = mysqli_query($link, $sql1);
        if (mysqli_num_rows($query1) == 0) {
            if ($iii == 0) {
                $option .= '<option selected="selected" value="' . $row['id'] . '">' . $row['causa'] . '</option>';
            } else {
                $option .= '<option value="' . $row['id'] . '">' . $row['causa'] . '</option>';
            }
        } else {
            $option .= '<option selected="selected" value="' . $row['id'] . '">' . $row['causa'] . '</option>';
        }
        $iii++;
    }
    echo json_encode($option);
    return;
}

if (isset($_POST['accion']) && $_POST['accion'] == 'obtener_quejas') {
    $id = $_POST['id'];

    $sql = "SELECT tblquejas.id,tblquejas.fecha, tblquejas.queja FROM tblcustom_services INNER JOIN tblquejas ON tblquejas.id_customer = tblcustom_services.id_custom
            WHERE tblcustom_services.id_orden = '$id'";
    $query = mysqli_query($link, $sql);
    $quejas = array();
    $i = 0;
    while ($row = mysqli_fetch_array($query)) {
        $quejas[$i++] = $row;
    }
    echo json_encode($quejas);
    return;
}

//pare verificar si existe mensaje en el servidor
if (isset($_POST['accion']) && $_POST['accion'] == 'verificar_mensaje') {
    if (file_exists("../php/correos/" . $_POST['id'] . ".zip")) {
        echo json_encode('existe');
        return;
    } else {
        echo json_encode('no_existe');
        return;
    }
}

//pare eliminar mensaje en el servidor
if (isset($_POST['accion']) && $_POST['accion'] == 'eliminar_mensaje') {
    if (file_exists("../php/correos/" . $_POST['id'] . ".zip")) {
        if (unlink("../php/correos/" . $_POST['id'] . ".zip")) {
            echo json_encode('eliminado');
            return;
        } else {
            echo json_encode('error');
            return;
        }
    }
}

//codigo que se ejecuta al reenviar una orden
if (isset($_POST['accion']) && $_POST['accion'] == 'reenviar') {
    $result = array();
    list($codi, $item, $deliver) = explode(",", $_POST['id']);

    $sql = "SELECT * FROM tbldetalle_orden where id_orden_detalle='" . $codi . "'";
    $query = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($query);

    $ponumber = $row['Ponumber'];
    $custnumber = $row['Custnumber'];

    if ($row['tracking'] == '') { //Si el tracking es vacio no puede hacer reshipped
        $result[0] = "error1";
        $result[1] = $row['Ponumber'];
        echo json_encode($result);
        return;
    } else {
        //Si el tracking no es vacio entonces puede hacer reshipped		  
        $sql1 = "Insert INTO tbldetalle_orden(`id_detalleorden`,`cpcantidad`,`cpitem`,`satdel`,`farm`,`cppais_envio`,`cpmoneda`,`cporigen`,`cpUOM`,`delivery_traking`,`ShipDT_traking`,`Ponumber`,`Custnumber`,`tracking`,`estado_orden`,`descargada`,`user`,`eBing`,`coldroom`,`status`,`reenvio`,`poline`,`unitprice`,`ups`,`vendor`) VALUES ('" . $row['id_detalleorden'] . "','" . $row['cpcantidad'] . "','" . $item . "','" . $row['satdel'] . "','','" . $row['cppais_envio'] . "','" . $row['cpmoneda'] . "','" . $row['cporigen'] . "','" . $row['cpUOM'] . "','" . $deliver . "','" . $row['ShipDT_traking'] . "','" . $row['Ponumber'] . "','" . $row['Custnumber'] . "','','" . $row['estado_orden'] . "','not downloaded','','" . $row['eBing'] . "','No','New','Forwarded','" . $row['poline'] . "','0.00','" . $row['ups'] . "','" . $row['vendor'] . "')";
        $reenvio = mysqli_query($link, $sql1) or die(mysqli_error());

        //Actualizar la tabla de atencion al cliente
        $sql2 = "SELECT reenvio FROM tblcustom_services WHERE Ponumber='" . $ponumber . "' AND Custnumber='" . $custnumber . "'";
        $query1 = mysqli_query($link, $sql2) or die("Error verificando si la orden tenia reenvio");
        $cant = mysqli_num_rows($query1);

        $fecha = date('Y-m-d');

        if ($cant == 0) {
            //Inserto una nueva fila en tabla de customer servces con un renvio
            $a = "Insert INTO tblcustom_services (`ponumber`,`custnumber`,`reenvio`,`fecha`,`id_orden`) VALUES ('" . $ponumber . "','" . $custnumber . "','Si','" . $fecha . "','" . $row['id_orden_detalle'] . "')";
            $b = mysqli_query($link, $a) or die("Error insertando datos del reenvio");

            if ($reenvio && $b) {
                $result[0] = "ok";
                $result[1] = $ponumber;
                echo json_encode($result);
                return;
            } else {
                $result[0] = "error2";
                $result[1] = mysqli_error();
                echo json_encode($result);
                return;
            }
        } else {
            //Modifico la fila existente en tabla de customer servces con un renvio
            $a = "UPDATE tblcustom_services set reenvio='Si',fecha='" . $fecha . "' WHERE ponumber = '" . $ponumber . "' and custnumber='" . $custnumber . "'";
            $b = mysqli_query($link, $a) or die("Error modificando datos del reenvio");
            if ($reenvio && $b) {
                $result[0] = "ok";
                $result[1] = $ponumber;
                echo json_encode($result);
                return;
            } else {
                $result[0] = "error2";
                $result[1] = mysqli_error();
                echo json_encode($result);
                return;
            }
        }
    }
}