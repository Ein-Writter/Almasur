<?php
include '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Capturamos el ID. Si no existe (es nuevo), valdrá 0.
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    $nombre  = $conn->real_escape_string($_POST['nombre']);
    $usuario = $conn->real_escape_string($_POST['usuario']);
    $email   = $conn->real_escape_string($_POST['email']);
    $rol     = $conn->real_escape_string($_POST['rol']);

    if ($id > 0) {
        // --- LÓGICA PARA ACTUALIZAR (EDITAR) ---
        
        // Empezamos la consulta básica
        $sql = "UPDATE usuarios SET 
                nombre = '$nombre', 
                usuario = '$usuario', 
                email = '$email', 
                rol = '$rol'";

        // Solo si el admin escribió una contraseña nueva, la encriptamos y la añadimos
        if (!empty($_POST['password'])) {
            $password_encriptada = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $sql .= ", password = '$password_encriptada'";
        }

        $sql .= " WHERE id = $id";
        $mensaje_exito = "updated";

    } else {
        // --- LÓGICA PARA INSERTAR (NUEVO) ---
        
        // En un nuevo usuario, la contraseña SÍ es obligatoria
        $password_encriptada = password_hash($_POST['password'], PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO usuarios (nombre, usuario, email, password, rol) 
                VALUES ('$nombre', '$usuario', '$email', '$password_encriptada', '$rol')";
        $mensaje_exito = "success";
    }

    // Ejecutamos la consulta (sea UPDATE o INSERT)
    if ($conn->query($sql)) {
        header("Location: ../empleados.php?status=$mensaje_exito");
        exit();
    } else {
        echo "Error en la operación: " . $conn->error;
    }
}
?>