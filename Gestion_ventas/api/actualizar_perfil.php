<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_SESSION['usuario_id'];
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['new_password'];

    $sql = "UPDATE usuarios SET nombre = '$nombre', email = '$email' ";

    if (!empty($pass)) {
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
        $sql .= ", password = '$hashed_pass' ";
    }

    if (!empty($_FILES['foto']['name'])) {
        $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nombre_archivo = "user_" . $id . "." . $extension;
        $ruta_destino = "../assets/img/" . $nombre_archivo;
        $ruta_db = "assets/img/" . $nombre_archivo;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino)) {
            $sql .= ", foto = '$ruta_db' ";
            $_SESSION['foto'] = $ruta_db; 
            }
    }

    $sql .= " WHERE id = $id";

    if ($conn->query($sql)) {
        $_SESSION['nombre'] = $nombre; 
        header("Location: ../perfil.php?success=1");
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}
?>