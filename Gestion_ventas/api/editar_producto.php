<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);

    $sql = "UPDATE productos SET nombre='$nombre', precio=$precio, stock=$stock WHERE id=$id";

    if ($conn->query($sql)) {
        header("Location: ../inventario.php?status=updated");
    } else {
        echo "Error actualizando: " . $conn->error;
    }
}
?>