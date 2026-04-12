<?php
session_start();
include '../config/db.php';

$user = $_POST['usuario'];
$pass = $_POST['password'];

$res = $conn->query("SELECT * FROM usuarios WHERE usuario = '$user'");

if ($res && $res->num_rows > 0) {
    $datos = $res->fetch_assoc();
    
    if (password_verify($pass, $datos['password'])) {
        
        session_regenerate_id();

        $_SESSION['usuario_id'] = $datos['id'];
        $_SESSION['usuario']    = $datos['usuario'];
        $_SESSION['nombre']     = $datos['nombre'] ?? $datos['usuario'];
        $_SESSION['rol']        = $datos['rol']; 

        header("Location: ../menu.php");
        exit();
    } else {
        echo "<script>alert('Clave incorrecta'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Usuario no encontrado'); window.history.back();</script>";
}
?>