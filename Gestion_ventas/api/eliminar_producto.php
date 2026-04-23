<?php
include '../config/db.php';
session_start();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // En lugar de DELETE, usamos UPDATE
    $sql = "UPDATE productos SET estado = 0 WHERE id = $id";

    if ($conn->query($sql)) {
        header("Location: ../inventario.php?status=hidden");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>