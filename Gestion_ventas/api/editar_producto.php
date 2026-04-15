<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitización de datos
    $id = intval($_POST['id']);
    $codigo = $conn->real_escape_string($_POST['codigo']); // <-- Nuevo campo
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);

    // Sentencia SQL actualizada con el campo 'codigo'
    $sql = "UPDATE productos SET 
                codigo='$codigo', 
                nombre='$nombre', 
                precio=$precio, 
                stock=$stock 
            WHERE id=$id";

    if ($conn->query($sql)) {
        // Redirección con éxito
        header("Location: ../inventario.php?status=updated");
        exit(); // Es buena práctica poner exit() después de un header Location
    } else {
        echo "Error actualizando: " . $conn->error;
    }
}
?>
