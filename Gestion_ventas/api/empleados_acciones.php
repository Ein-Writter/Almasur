<?php
include '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nombre = $conn->real_escape_string(trim($_POST['nombre']));
    $usuario = $conn->real_escape_string(trim($_POST['usuario']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $rol = $_POST['rol']; // Captura: Administrador, Gerente o Empleado

    if ($id > 0) {
        $sql = "UPDATE usuarios SET nombre='$nombre', usuario='$usuario', email='$email', rol='$rol' WHERE id=$id";
    } else {
        $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $sql = "INSERT INTO usuarios (nombre, usuario, email, password, rol) 
                VALUES ('$nombre', '$usuario', '$email', '$pass', '$rol')";
    }

    if ($conn->query($sql)) {
        header("Location: ../empleados.php?status=success");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
