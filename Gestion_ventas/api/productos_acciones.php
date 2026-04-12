<?php
include '../config/db.php';
session_start();

if (isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $usuario = $_SESSION['usuario_id'];

    $sql = "INSERT INTO productos (nombre, precio, stock, id_usuario) VALUES ('$nombre', '$precio', '$stock', '$usuario')";
    
    if ($conn->query($sql)) {
        header("Location: ../inventario.php?success=creado");
    }
}

if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar') {
    $id = $_GET['id'];
    
    $sql = "UPDATE productos SET estado = 0 WHERE id = $id";
    
    if ($conn->query($sql)) {
        header("Location: ../inventario.php?success=eliminado");
    }
}
?>