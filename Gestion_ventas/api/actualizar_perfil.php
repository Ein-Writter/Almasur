<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_SESSION['usuario_id'];
    
    // Limpieza de datos
    $nombre  = mysqli_real_escape_string($conn, $_POST['nombre']);
    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']); 
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $pass    = $_POST['new_password'];

    // SQL Base
    $sql = "UPDATE usuarios SET nombre = '$nombre', usuario = '$usuario', email = '$email' ";

    // Manejo de Contraseña
    if (!empty($pass)) {
        if (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}$/', $pass)) {
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            $sql .= ", password = '$hashed_pass' ";
        } else {
            header("Location: ../perfil.php?error=password_weak");
            exit;
        }
    }

    // --- ARREGLO DE FOTO DE PERFIL ---
    if (!empty($_FILES['foto']['name'])) {
        $extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $permitidos = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extension, $permitidos)) {
            // Nombre único: evita que todos se llamen 'jairo.png'
            // Usamos el ID del usuario y el tiempo actual
            $nombre_archivo = "perfil_" . $id . "_" . time() . "." . $extension; 
            $ruta_destino = "../uploads/" . $nombre_archivo;
            $ruta_db = "uploads/" . $nombre_archivo;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino)) {
                $sql .= ", foto_perfil = '$ruta_db' "; 
                
                // ACTUALIZACIÓN CRÍTICA: Actualizamos la sesión para que el sidebar cambie ya
                $_SESSION['foto_perfil'] = $ruta_db; 
            }
        }
    }

    $sql .= " WHERE id = $id";

    if ($conn->query($sql)) {
        $_SESSION['nombre'] = $nombre; // Actualiza el nombre en el sidebar también
        header("Location: ../perfil.php?success=1");
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}
