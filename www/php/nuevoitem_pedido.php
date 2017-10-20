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

$sqluser = "SELECT id_usuario FROM tblusuario WHERE cpuser = '" . $user . "'";
$queryuser = mysqli_query($link, $sqluser);
$rowuser = mysqli_fetch_array($queryuser);
$user = $rowuser['id_usuario'];


if (isset($_POST["btn_nuevoitem"])) {

    $codfinca = $_POST['codigo_finca'];
    $item1 = addslashes($_POST['item1']);
    $cantidad1 = $_POST['cantidad1'];
    $precioUnitario = $_POST['precioUnitario'];


    $sqlitem = 'SELECT * FROM tblproductos WHERE id_item = "' . $item1 . '"';
    $queryitem = mysqli_query($link, $sqlitem);
    $rowitem = mysqli_fetch_array($queryitem);
    $item1 = $rowitem['id_item'];

    if ($item1 == '') {
        echo('Item no encontrado');
    } else {
        $sqlcarro = "INSERT INTO tblcarro_pedido (codfinca, id_item, cantidad, preciounitario, id_usuario) VALUES ('" . $codfinca . "','" . $item1 . "','" . $cantidad1 . "','" . $precioUnitario . "','" . $user . "')";
        $query_carro = mysqli_query($link, $sqlcarro);
        $insertado = mysqli_insert_id();
        $valores = array($insertado, $item1, $cantidad1, $precioUnitario, 'insertar');
        echo json_encode($valores);
        return;
    }
    return;
}
if (isset($_POST["btn_editaritem"]) && isset($_POST["idcompra_pedido"])) {

    $codfinca = $_POST['codigo_finca'];
    $item1 = addslashes($_POST['item1']);
    $cantidad1 = $_POST['cantidad1'];
    $precioUnitario = $_POST['precioUnitario'];
    $idcompra_pedido = $_POST['idcompra_pedido'];

    $sqlcarro = "UPDATE tblcarro_pedido set codfinca='" . $codfinca . "', id_item='" . $item1 . "', cantidad='" . $cantidad1 . "', preciounitario='" . $precioUnitario . "', id_usuario='" . $user . "' WHERE idcompra_pedido='" . $idcompra_pedido . "'";
    $query_carro = mysqli_query($link, $sqlcarro);
    $valores = array($idcompra_pedido, $item1, $cantidad1, $precioUnitario, "editar");
    echo json_encode($valores);
    return;
}
if (isset($_POST["accion"]) && $_POST["accion"] == 'eliminar') {
    $idcompra_pedido = $_POST['id'];

    $sqlcarro = "DELETE FROM tblcarro_pedido WHERE idcompra_pedido='" . $idcompra_pedido . "'";
    $query_carro = mysqli_query($link, $sqlcarro);
    $valores = array("eliminar");
    echo json_encode($valores);
    return;
}

if (isset($_POST["accion"]) && $_POST["accion"] == "registrar") {
    include ("codigounico.php");
    $codigo_finca = $_POST['codigo_finca'];
    $finca = $_POST['finca'];
    $fecha = $_POST['fecha'];
    $ftentativ = $_POST['ftentativa'];
    $destino = $_POST['destino'];
    $agencia = $_POST['agencia'];


    //Recorrer los registros de tblcarro_venta//
    $sqlins = "SELECT * FROM tblcarro_pedido WHERE codfinca = '" . $codigo_finca . "' AND id_usuario = '" . $user . "' ";
    $queryins = mysqli_query($link, $sqlins);

    if (mysqli_num_rows($queryins) == 0) {
        echo "2";
        return;
    }
    //Recorriendo toda la tabla para efectuar inserciones
    while ($rowins = mysqli_fetch_array($queryins)) {

        $j = 1;

        $cant = $rowins['cantidad'];
        $item = $rowins['id_item'];
        $precio = $rowins['preciounitario'];

        //Se genera el número de orden
        $sql = "SELECT nropedido FROM tbletiquetasxfinca ORDER BY `nropedido` desc LIMIT 1";

        $val = mysqli_query($link, $sql) or die("Error buscando el nropedido");
        $row = mysqli_fetch_array($val);
        $nro = $row['nropedido'];
        $nro += 1;

        while ($j <= $cant) {
            //Se genera el codigo unico
            $codigo = generarCodigoUnico();

            //Se inserta en la tabla de codigos
            $consulta = "INSERT INTO tblcodigo (`codigo`,`finca`) VALUES ('$codigo','$finca')";
            $ejecutar = mysqli_query($link, $consulta) or die("Error insertando el código único");

            //Inserto el pedido uno por uno hasta que este la cantidad pedida	  
            $sql = "INSERT INTO tbletiquetasxfinca (`codigo`, `finca`, `item`,`fecha`,`solicitado`,`entregado`,`estado`,`nropedido`,`fecha_tentativa`,`precio`,`destino`,`agencia`) VALUES ('$codigo','$finca','$item','$fecha','1','0','0','$nro','$ftentativ','$precio','$destino','$agencia')";

            $insertado = mysqli_query($link, $sql) or die("Error insertando el pedido");
            $j++;
        }
    } //Fin del while que recorre los items

    if ($insertado && $ejecutar) {

        //Vaciar carro de pedido
        $sqlvaciar = "DELETE FROM `tblcarro_pedido` WHERE codfinca = '" . $codigo_finca . "' AND id_usuario = '" . $user . "' ";
        $queryvaciar = mysqli_query($link, $sqlvaciar);
        echo "3";
        return;
    } else {
        echo("<script> alert (" . mysqli_error() . ");</script>");
    }
}

//se ejecuta al cancelar un pedido
if (isset($_POST["accion"]) && $_POST["accion"] == "cancelar") {
    //Vaciar carro de compra
    $codigo_finca = $_POST['codigo_finca'];
    $sqlvaciar = "DELETE FROM `tblcarro_pedido` WHERE codfinca = '" . $codigo_finca . "' AND id_usuario = '" . $user . "'  ";
    $queryvaciar = mysqli_query($link, $sqlvaciar);
    return;
}

//se ejecuta al rechazar las cajas
if (isset($_POST["accion"]) && $_POST["accion"] == "rechazar_cajas") {
    $comentario = $_POST['comentario'];
    $cajas = $_POST['cajas'];   ///array con los codigos de las ordenes
    $cajas1 = $_POST['cajas1']; ///array con los motivos del rechazo
    $id = $_POST['id'];        //id del pedido
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

    //Armar el array en php de las razones
    $selected = json_encode($selected);

    //Armar el array en php
    $cajas = explode(",", $cajas);

    //hacer ciclo para recoger cada codigo marcado para asignarle la guia
    for ($i = 0; $i < count($cajas); $i++) {
        $sql = "SELECT estado,nropedido FROM tbletiquetasxfinca WHERE codigo='" . $cajas[$i] . "'";

        $query = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($query);

        $sql = "Update tblcoldroom set rechazada='2' WHERE codigo='" . $cajas[$i] . "'";
        $modificado1 = mysqli_query($link, $sql) or die("Error");

        //actualiza la tabla etiquetasxfinca
        $sql = "Update tbletiquetasxfinca set entregado='0', estado='2', comentario='" . $comentario . "', razones1= '" . $selected . "' WHERE codigo='" . $cajas[$i] . "'";
        $modificado = mysqli_query($link, $sql) or die("Error");

        ///////////////////////////////////// INSERTAMOS EN HISTORICO
        $ip       =  $_SERVER['REMOTE_ADDR'];
        $fecha = date('Y-m-d H:i:s');
        $SqlHistorico = "INSERT INTO tblhistorico (`usuario`,`operacion`,`fecha`,`ip`) 
                            VALUES ('$user','rechazar caja ".$cajas[$i]."','$fecha','$ip')";
        $consultaHist = mysqli_query($link,$SqlHistorico) or die("Error actualizando la bitacora de usuarios");
    }
    return;
}

//para cancelar un pedido
if (isset($_POST["accion"]) && $_POST["accion"] == "cancelar_pedido") {
    /*     * ** Estado 5 es cuando el pedidio es cancelado por error y con comentario por error** */
    $sql = "UPDATE tbletiquetasxfinca set estado='5', comentario='Error en la creacion' where nropedido='" . $_POST['nropedido'] . "'";
    mysqli_query($link, $sql);
    return;
}

//para archivar un pedido 
if (isset($_POST["accion"]) && $_POST["accion"] == "archivar_pedido") {
    $cajass = json_decode(stripslashes($_POST['cajas']));
    foreach ($cajass as $d) {
        $sql = "UPDATE tbletiquetasxfinca set archivada='Si' where nropedido='" . $d . "'";
        mysqli_query($link, $sql);
    }
    return;
}