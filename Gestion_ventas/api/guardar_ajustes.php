<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Limpieza de datos de texto para seguridad
    $nombre    = mysqli_real_escape_string($conn, $_POST['nombre_negocio']);
    $ruc       = mysqli_real_escape_string($conn, $_POST['ruc']);
    $direccion = mysqli_real_escape_string($conn, $_POST['direccion']);
    $telefono  = mysqli_real_escape_string($conn, $_POST['telefono']);
    $mensaje   = mysqli_real_escape_string($conn, $_POST['mensaje_factura']);
    $moneda    = mysqli_real_escape_string($conn, $_POST['moneda']);

    // 2. Procesar el Logo del Negocio
    $sql_logo = ""; // Variable para añadir al UPDATE si hay imagen nueva
    
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $extension = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $permitidos = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extension, $permitidos)) {
            // Nombre único usando el tiempo actual para que el cambio sea instantáneo
            $nombre_archivo = "logo_sistema_" . time() . "." . $extension;
            $ruta_destino = "../uploads/" . $nombre_archivo; // Carpeta física
            $ruta_db = "uploads/" . $nombre_archivo;        // Ruta para la BD

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $ruta_destino)) {
                // Preparamos el pedazo de SQL para actualizar la ruta del logo
                $sql_logo = ", logo = '$ruta_db' ";
            }
        }
    }

    // 3. Actualización general en la base de datos
    $sql = "UPDATE configuracion SET 
            nombre_negocio = '$nombre', 
            ruc = '$ruc', 
            direccion = '$direccion', 
            telefono = '$telefono', 
            mensaje_factura = '$mensaje', 
            moneda = '$moneda' 
            $sql_logo 
            WHERE id = 1";

    if ($conn->query($sql)) {
        // Redirigir con éxito
        header("Location: ../ajustes.php?status=ok");
    } else {
        // Redirigir con error de base de datos
        header("Location: ../ajustes.php?status=error&msg=" . urlencode($conn->error));
    }
} else {
    // Si alguien intenta entrar directamente al archivo sin POST
    header("Location: ../ajustes.php");
}
?>
