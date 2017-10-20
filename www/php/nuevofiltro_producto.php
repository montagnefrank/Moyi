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
$item_producto = $_POST['item'];
$desc_producto = strtoupper($_POST['desc']);
$receta_producto = strtoupper($_POST['receta']);
$boxtype = $_POST['boxtype'];
$Pack_producto = $_POST['Pack'];
$Dclvalue_producto = $_POST['Dclvalue'];
$largo_producto = $_POST['largo'];
$ancho_producto = $_POST['ancho'];
$alto_producto = $_POST['alto'];
$peso_producto = $_POST['peso'];
$servicio_producto = strtoupper($_POST['servicio']);
$origen_producto = $_POST['origen'];
$packtype_producto = $_POST['packtype'];
$descgen_producto = strtoupper($_POST['descgen']);
$finca_producto = $_POST['finca'];
$carpetaDestino = "../images/productos/"; //carpeta donde se almacenan las imagenes
$id_producto = "";

if (isset($_POST["accion"]) && $_POST["accion"] == 'selItem') {
    $sql = "SELECT id_item FROM tblproductos WHERE id_item='" . $_POST['id'] . "' ";
    $query = mysqli_query($link, $sql) or die("Error seleccionando los productos");
    if ($query) {
        $response = mysqli_fetch_array($query);
        echo json_encode($response['id_item']);
        return;
    } else {
        echo 'NO MANDO NADA';
        return;
    }
}
if (isset($_POST["accion"]) && $_POST["accion"] == 'selTodos') {
    $sql = "SELECT *,tblboxtype.nombre_Box FROM tblproductos INNER JOIN tblboxtype ON tblproductos.boxtype = tblboxtype.id_Box WHERE id_item='" . $_POST['id'] . "' ";
    $query = mysqli_query($link, $sql) or die("Error seleccionando los productos");
    echo json_encode(mysqli_fetch_array($query));
    return;
}
if (isset($_POST["accion"]) && $_POST["accion"] == 'selBox') {
    $sqlBox = "SELECT * FROM tblboxtype WHERE id_Box='" . $_POST['id_Box'] . "'";
    $queryBox = mysqli_query($link, $sqlBox) or die("Error seleccionando los Boxtype");
    echo json_encode(mysqli_fetch_array($queryBox));
    return;
}
if (isset($_POST["accion"]) && $_POST["accion"] == 'selListaProd') {
    $sqllista = "SELECT * FROM tbllistaproducto WHERE id='" . $_POST['id'] . "'";
    $querylista = mysqli_query($link, $sqllista) or die("Error seleccionando la lista de productos");
    echo json_encode(mysqli_fetch_array($querylista));
    return;
}
if (isset($_POST["accion"]) && $_POST["accion"] == 'selDetalleReceta') {
    //si estoy editando y abro la ventana de recetas
    $sqlreceta = "SELECT tbldetallereceta.id,
                        tbldetallereceta.id_receta,
                        tbldetallereceta.cantidad,
                        tbldetallereceta.producto,
                        tbldetallereceta.longitud,
                        tbldetallereceta.hts,
                        tbldetallereceta.color,
                        tbldetallereceta.stem,
                        tbldetallereceta.total,
                        tbldetallereceta.nandina,
                        tbldetallereceta.pack FROM tbldetallereceta INNER JOIN tblrecetas ON tbldetallereceta.id_receta = tblrecetas.id INNER JOIN tblproductos ON tblrecetas.id = tblproductos.id_receta WHERE item='" . $_POST['id'] . "'";

    $queryreceta = mysqli_query($link, $sqlreceta) or die("Error seleccionando los detalles de receta");
    $rawdata = array(); //creamos un array
    //guardamos en un array multidimensional todos los datos de la consulta
    $i = 0;
    while ($row = mysqli_fetch_array($queryreceta)) {
        $rawdata[$i] = $row;
        $i++;
    }
    echo json_encode($rawdata);
    return;
}
if (isset($_POST["accion"]) && $_POST["accion"] == 'nuevo') {

    $arr = json_decode($_POST['datoReceta']); //decodificamos el array de la receta
    $id_receta = "";

    //primero Insertamos la receta
    $sqlreceta = "INSERT INTO tblrecetas VALUES(DEFAULT)";
    $res1 = mysqli_query($link, $sqlreceta);
    $id_receta = mysqli_insert_id($link);

    //recorremos el array de las recetas
    foreach ($arr as $datoreceta) {
        //insertamos despues cada detalle de la receta 
        $sqlreceta = "INSERT INTO tbldetallereceta (`id_receta`,`cantidad`,`pack`,`producto`,`longitud`,`hts`,`nandina`,`color`,`stem`,`total`) VALUES ('" . $id_receta . "','" . $datoreceta[0] .
                "','" . $datoreceta[1] . "','" . $datoreceta[2] . "','" . $datoreceta[3] . "','" . $datoreceta[4] . "','" . $datoreceta[5] . "','" . $datoreceta[6] . "','" . $datoreceta[7] . "','" . $datoreceta[8] . "')";
        $res2 = mysqli_query($link, $sqlreceta);
    }

    $sql = "INSERT INTO tblproductos (`id_item`,`prod_descripcion`,`length`,`width`,`heigth`,`wheigthKg`,`dclvalue`,`origen`,`cpservicio`,`cptipo_pack`,`gen_desc`,`receta`,`boxtype`,`pack`,`finca`,`id_receta`) VALUES " .
            "('" . $item_producto . "','" . $desc_producto . "','" . $largo_producto . "','" . $ancho_producto . "','" . $alto_producto . "','" . $peso_producto . "','" . $Dclvalue_producto .
            "','" . $origen_producto . "','" . $servicio_producto . "','" . $packtype_producto . "','" . $descgen_producto . "','" . $receta_producto . "','" . $boxtype . "','" . $Pack_producto . "','" . $finca_producto . "','" . $id_receta . "')";

    $insertado = mysqli_query($link, $sql);
    $id = mysqli_insert_id($link);
    $jsondata = array();
    $jsondata["id"] = $id;
    $id_producto = $id;

    if ($insertado) {
        $jsondata["success"] = 'true';
        $jsondata["message"] = '<div class="alert alert-success" role="alert"><strong>Producto Insertado Correctamente.</strong></div>';
    } else {
        $jsondata["success"] = 'false';
        $jsondata["message"] = mysqli_error();
    }
} else if (isset($_POST["accion"]) && $_POST["accion"] == 'editar') {
    $arr = json_decode($_POST['datoReceta']); //decodificamos el array de la receta
    $id_receta = $_POST["id_receta"];

    //elimino los detalles que pertenecen a la receta con idreceta enviada por post
    $sqlreceta = "DELETE FROM tbldetallereceta WHERE id_receta='" . $id_receta . "'";

    $res1 = mysqli_query($link, $sqlreceta);

    //recorremos el array de las recetas
    foreach ($arr as $datoreceta) {
        //insertamos despues cada detalle de la receta 
        $sqlreceta = "INSERT INTO tbldetallereceta (`id_receta`,`cantidad`,`pack`,`producto`,`longitud`,`hts`,`nandina`,`color`,`stem`,`total`) VALUES ('" . $id_receta . "','" . $datoreceta[0] .
                "','" . $datoreceta[1] . "','" . $datoreceta[2] . "','" . $datoreceta[3] . "','" . $datoreceta[4] . "','" . $datoreceta[5] . "','" . $datoreceta[6] . "','" . $datoreceta[7] . "','" . $datoreceta[8] . "')";

        $res2 = mysqli_query($link, $sqlreceta);
    }

    //actualizo la tabla de productos       
    $id_producto = $_POST["id"];
    $sql = "UPDATE tblproductos SET id_item='" . $item_producto . "',prod_descripcion='" . $desc_producto . "',length='" . $largo_producto . "',width='" . $ancho_producto . "',heigth='" . $alto_producto . "',wheigthKg='" . $peso_producto . "',dclvalue='" . $Dclvalue_producto .
            "',origen='" . $origen_producto . "',cpservicio='" . $servicio_producto . "',cptipo_pack='" . $packtype_producto . "',gen_desc='" . $descgen_producto . "',receta='" . $receta_producto . "',boxtype='" . $boxtype .
            "',pack='" . $Pack_producto . "',finca='" . $finca_producto . "',id_receta='" . $id_receta . "' WHERE item='" . $id_producto . "'";
    $insertado = mysqli_query($link, $sql);

    $jsondata = array();

    if ($insertado) {
        $jsondata["success"] = 'true';
        $jsondata["message"] = '<div class="alert alert-success" role="alert"><strong>Producto Actualizado Correctamente.</strong></div>';
    } else {
        $jsondata["success"] = 'false';
        $jsondata["message"] = mysqli_error($link);
    }
} else if (isset($_POST["accion"]) && $_POST["accion"] == 'eliminar') {
    $id_producto = $_POST["id"];

    //elimino la receta asociada a ese producto
    $sql = "SELECT id_receta FROM tblproductos WHERE item='" . $id_producto . "'";
    $resultado = mysqli_query($link, $sql) or die("Error seleccionando la lista de productos");
    $fila = mysqli_fetch_row($resultado);

    $sql = "DELETE FROM tblrecetas WHERE id='" . $fila[0] . "'";
    $insertado = mysqli_query($link, $sql);

    $sql = "DELETE FROM tblproductos WHERE item='" . $id_producto . "'";
    $insertado = mysqli_query($link, $sql);

    $jsondata = array();
    if ($insertado) {
        $jsondata["success"] = 'true';
        $jsondata["message"] = '<div class="alert alert-success" role="alert"><strong>Producto Eliminado Correctamente.</strong></div>';
        unlink($carpetaDestino . $id_producto . ".jpg");
    } else {
        $jsondata["success"] = 'false';
        $jsondata["message"] = mysqli_error();
    }
}

//Para subir imagen del producto
if ($_FILES["archivo"]["name"]) {
    #Borramos el producto anterior    
    unlink($carpetaDestino . $id_producto . ".jpg");

    if ($_POST["accion"] != 'eliminar') {

        #divide el nombre del fichero con un .    
        $explode_name = explode('.', $_FILES["archivo"]["name"]);

        # si es un formato de excel
        if ($explode_name[1] == 'jpg' || $explode_name[1] == 'JPG') {
            # si exsite la carpeta o se ha creado
            if (file_exists($carpetaDestino) || @mkdir($carpetaDestino)) {
                $origen = $_FILES["archivo"]["tmp_name"];
                $destino = $carpetaDestino . $_FILES["archivo"]["name"];

                # movemos el archivo
                if (@move_uploaded_file($origen, $destino)) {
                    rename($destino, $carpetaDestino . $id_producto . ".jpg");
                    $jsondata["imagen"] = $id_producto;
                } else {
                    $jsondata["message"] .= "<br>No se ha podido mover el archivo: " . $_FILES["archivo"]["name"];
                }
            } else {
                $jsondata["message"] .= "<br>No se ha podido crear la carpeta: up/" . $user;
            }
        } else {
            $jsondata["message"] .= "<br>" . $_FILES["archivo"]["name"] . " - Formato no admitido";
        }
    }
}


$jsondata["item"] = $item_producto;
$jsondata["desc"] = $desc_producto;

//consulta para seleccionar el boxtype
$sql = "SELECT tblboxtype.nombre_Box FROM tblboxtype INNER JOIN tblproductos ON tblproductos.boxtype = tblboxtype.id_Box where tblproductos.item ='" . $id_producto . "'";
$resultado = mysqli_query($link, $sql) or die("Error seleccionando el boxtype");
$fila = mysqli_fetch_row($resultado);
$jsondata["boxtype"] = $fila[0];

$jsondata["Dclvalue"] = $Dclvalue_producto;
$jsondata["largo"] = $largo_producto;
$jsondata["ancho"] = $ancho_producto;
$jsondata["alto"] = $alto_producto;
$jsondata["peso"] = $peso_producto;
$jsondata["origen"] = $origen_producto;
$jsondata["servicio"] = $servicio_producto;
$jsondata["packtype"] = $packtype_producto;
$jsondata["descgen"] = $descgen_producto;
$jsondata["finca"] = $finca_producto;
$jsondata["pack"] = $Pack_producto;
$jsondata["receta"] = $receta_producto;
$jsondata["imagen"] = $id_producto;
echo json_encode($jsondata);
