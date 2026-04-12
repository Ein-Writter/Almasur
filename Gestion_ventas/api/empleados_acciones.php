<?php
include '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO usuarios (nombre, usuario, email, password, rol) 
            VALUES ('$nombre', '$usuario', '$email', '$password', '$rol')";

    if ($conn->query($sql)) {
        header("Location: ../empleados.php?success=1");
    } else {
        echo "Error al registrar: " . $conn->error;
    }
}
?>