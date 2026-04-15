<?php
include '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Captura y sanitización de datos
    $codigo = $conn->real_escape_string($_POST['codigo']); // Nuevo campo
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $precio = floatval($_POST['precio']);
    $stock  = intval($_POST['stock']);

    // Validación básica
    if (empty($codigo) || empty($nombre) || $precio < 0 || $stock < 0) {
        header("Location: ../inventario.php?error=datos_invalidos");
        exit;
    }

    // OPCIONAL: Verificar si el código ya existe para no duplicar SKU
    $checkCodigo = $conn->query("SELECT id FROM productos WHERE codigo = '$codigo'");
    if ($checkCodigo->num_rows > 0) {
        header("Location: ../inventario.php?error=codigo_duplicado");
        exit;
    }

    // SQL con el nuevo campo codigo
    $sql = "INSERT INTO productos (codigo, nombre, precio, stock) 
            VALUES ('$codigo', '$nombre', $precio, $stock)";

    if ($conn->query($sql)) {
        header("Location: ../inventario.php?status=success");
    } else {
        header("Location: ../inventario.php?error=" . urlencode($conn->error));
    }
} else {
    header("Location: ../inventario.php");
}

$conn->close();
exit;
?>
