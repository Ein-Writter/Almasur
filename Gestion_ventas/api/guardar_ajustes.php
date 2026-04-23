<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Limpiamos los datos para evitar errores
    $nombre = $conn->real_escape_string($_POST['nombre_negocio']);
    $ruc = $conn->real_escape_string($_POST['ruc']);
    $tel = $conn->real_escape_string($_POST['telefono']);
    $moneda = $conn->real_escape_string($_POST['moneda']);
    $mensaje = $conn->real_escape_string($_POST['mensaje_factura']);

    // Intentamos actualizar la fila 1 (que es la que usa el ticket)
    $sql = "UPDATE configuracion SET 
            nombre_negocio = '$nombre', 
            ruc = '$ruc', 
            telefono = '$tel', 
            moneda = '$moneda', 
            mensaje_factura = '$mensaje' 
            WHERE id = 1";

    if ($conn->query($sql)) {
        // Si se guardó bien, regresamos a ajustes con un mensaje de éxito
        header("Location: ../ajustes.php?status=ok");
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}
?>