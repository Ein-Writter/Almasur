<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre_negocio'];
    $ruc = $_POST['ruc'];
    $dir = $_POST['direccion'];
    $tel = $_POST['telefono'];
    $msj = $_POST['mensaje_factura'];
    $mon = $_POST['moneda'];

    $query_logo = "";
    if (!empty($_FILES['logo']['name'])) {
        $nombre_logo = time() . "_" . $_FILES['logo']['name'];
        $ruta = "../assets/img/" . $nombre_logo;
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $ruta)) {
            $query_logo = ", logo = '$nombre_logo'";
        }
    }

    $sql = "UPDATE configuracion SET 
            nombre_negocio = '$nombre', 
            ruc = '$ruc', 
            direccion = '$dir', 
            telefono = '$tel', 
            mensaje_factura = '$msj', 
            moneda = '$mon' 
            $query_logo
            WHERE id = 1";

    if ($conn->query($sql)) {
        header("Location: ../ajustes.php?status=ok");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>