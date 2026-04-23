<?php
include '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Recoger y limpiar datos
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $codigo = $conn->real_escape_string(trim($_POST['codigo']));
    $nombre = $conn->real_escape_string(trim($_POST['nombre']));
    $categoria = $conn->real_escape_string($_POST['categoria']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);

    // 2. VALIDACIÓN: Evitar códigos de barras duplicados (solo para productos nuevos)
    if ($id == 0) {
        $check = $conn->query("SELECT id FROM productos WHERE codigo = '$codigo'");
        if ($check->num_rows > 0) {
            header("Location: ../inventario.php?error=codigo_duplicado");
            exit();
        }
    }

    if ($id > 0) {
        // --- ACTUALIZAR PRODUCTO EXISTENTE ---
        $sql = "UPDATE productos SET 
                codigo = '$codigo', 
                nombre = '$nombre', 
                categoria = '$categoria', 
                precio = $precio, 
                stock = $stock 
                WHERE id = $id";
        $status = "updated";
    } else {
        // --- INSERTAR NUEVO PRODUCTO ---
        $sql = "INSERT INTO productos (codigo, nombre, categoria, precio, stock) 
                VALUES ('$codigo', '$nombre', '$categoria', $precio, $stock)";
        $status = "success";
    }

    // 3. Ejecutar consulta
    if ($conn->query($sql)) {
        header("Location: ../inventario.php?status=$status");
    } else {
        // Si hay un error, lo mostramos para depurar
        echo "Error en la base de datos: " . $conn->error;
    }
}
?>