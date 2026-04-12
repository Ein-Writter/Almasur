<?php
include '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $precio = floatval($_POST['precio']);
    $stock  = intval($_POST['stock']);

    if (empty($nombre) || $precio < 0 || $stock < 0) {
        header("Location: ../inventario.php?error=datos_invalidos");
        exit;
    }

    $sql = "INSERT INTO productos (nombre, precio, stock) 
            VALUES ('$nombre', $precio, $stock)";

    if ($conn->query($sql)) {
        header("Location: ../inventario.php?status=success");
    } else {
        header("Location: ../inventario.php?error=" . urlencode($conn->error));
    }
} else {
    header("Location: ../inventario.php");
}
$conn->close();
?>