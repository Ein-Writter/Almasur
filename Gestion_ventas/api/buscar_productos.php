<?php
include '../config/db.php';

// 1. Limpiamos la entrada para evitar errores y ataques (SQL Injection)
$q = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

if ($q === '') {
    echo json_encode([]);
    exit;
}

// 2. BUSQUEDA AMPLIADA: Ahora busca por NOMBRE o por CÓDIGO
// También añadimos 'codigo' al SELECT para que el JS pueda reconocerlo
$sql = "SELECT id, codigo, nombre, precio, stock FROM productos 
        WHERE (nombre LIKE '%$q%' OR codigo LIKE '%$q%') 
        AND stock > 0 
        AND estado = 1
        LIMIT 10";

$res = $conn->query($sql);
$productos = [];

if ($res) {
// ... dentro del while en buscar_productos.php
	while($row = $res->fetch_assoc()){
		$row['id'] = intval($row['id']);
		$row['codigo'] = trim((string)$row['codigo']); // Forzamos string y quitamos espacios
		$row['precio'] = floatval($row['precio']);
		$row['stock'] = intval($row['stock']);
		$productos[] = $row;
	}
}

// 3. Importante: Enviamos el encabezado JSON para que el JS no se confunda
header('Content-Type: application/json');
echo json_encode($productos);
?>