<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Sanitización de datos (aseguramos que los tipos sean correctos)
    $id = intval($_POST['id']);
    $codigo = $conn->real_escape_string(trim($_POST['codigo']));
    $nombre = $conn->real_escape_string(trim($_POST['nombre']));
    $categoria = $conn->real_escape_string($_POST['categoria']); // <-- Agregado
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
	$check = $conn->query("SELECT id FROM productos WHERE codigo = '$codigo' AND id != $id");
if ($check->num_rows > 0) {
    header("Location: ../inventario.php?error=codigo_duplicado");
    exit();
}

    // 2. Sentencia SQL con todos los campos incluyendo 'categoria'
    $sql = "UPDATE productos SET 
                codigo = '$codigo', 
                nombre = '$nombre', 
                categoria = '$categoria', 
                precio = $precio, 
                stock = $stock 
            WHERE id = $id";

    // 3. Ejecución y manejo de respuesta
    if ($conn->query($sql)) {
        // Redirección con éxito
        header("Location: ../inventario.php?status=updated");
        exit(); 
    } else {
        // En caso de error, mostramos el mensaje de SQL para depurar
        echo "Error actualizando el producto: " . $conn->error;
    }
}
?>
