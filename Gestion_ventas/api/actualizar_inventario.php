<?php
include '../config/db.php';
$id_u = $_SESSION['usuario_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id_producto'];
    $cantidad = $_POST['cantidad_añadir'];

    $sql = "UPDATE productos SET stock = stock + $cantidad WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../index.php?status=stock_actualizado");
    } else {
        echo "Error actualizando: " . $conn->error;
    }
}
?>