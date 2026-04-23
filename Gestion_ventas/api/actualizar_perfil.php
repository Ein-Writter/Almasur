<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_SESSION['usuario_id'];
    
    // 1. Recogemos los nuevos campos (incluyendo 'usuario')
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']); // <-- Nuevo campo
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['new_password'];

    // Empezamos el UPDATE con los campos básicos
    $sql = "UPDATE usuarios SET 
            nombre = '$nombre', 
            usuario = '$usuario', 
            email = '$email' ";

    // 2. Manejo de Contraseña con validación de lado del servidor
    if (!empty($pass)) {
        // Doble check de seguridad por si saltan la validación HTML
        if (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}$/', $pass)) {
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            $sql .= ", password = '$hashed_pass' ";
        } else {
            header("Location: ../perfil.php?error=password_weak");
            exit;
        }
    }

    // 3. Manejo de Foto (Cambiado a 'foto_perfil' para coincidir con tu SELECT)
// ... dentro de api/actualizar_perfil.php ...

if (!empty($_FILES['foto']['name'])) {
    $extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    $permitidos = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($extension, $permitidos)) {
        // SOLUCIÓN ANTI-CACHÉ 1: Nombre único con time()
        // Esto crea archivos como 'user_1_1713824000.jpg'
        $nombre_archivo = "user_" . $id . "_" . time() . "." . $extension; 
        $ruta_destino = "../assets/img/" . $nombre_archivo;
        $ruta_db = "assets/img/" . $nombre_archivo;

        // Opcional: Borrar la foto vieja para no llenar el servidor
        // $vieja_foto = $conn->query("SELECT foto_perfil FROM usuarios WHERE id = $id")->fetch_assoc()['foto_perfil'];
        // if($vieja_foto && file_exists("../".$vieja_foto) && strpos($vieja_foto, 'default') === false) { unlink("../".$vieja_foto); }

// ... dentro de api/actualizar_perfil.php ...
if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino)) {
    $sql .= ", foto_perfil = '$ruta_db' "; 
    
    // IMPORTANTE: El mismo nombre que pusimos en el paso 1 y 2
    $_SESSION['usuario_foto'] = $ruta_db; 
}

// También actualizamos el nombre por si lo cambió
$_SESSION['nombre'] = $nombre;
$_SESSION['usuario_rol'] = $_SESSION['usuario_rol']; // Mantenemos el que ya tenía
    }
}
// ... resto del script ...
    $sql .= " WHERE id = $id";

    if ($conn->query($sql)) {
        $_SESSION['nombre'] = $nombre; 
        header("Location: ../perfil.php?success=1");
    } else {
        echo "Error crítico: " . $conn->error;
    }
}
?>