<?php
include '../config/db.php';

$dni = $_GET['dni'] ?? '';
$res = $conn->query("SELECT id, nombre FROM clientes WHERE identidad = '$dni' LIMIT 1");

$respuesta = ['existe' => false];

if ($res->num_rows > 0) {
    $cliente = $res->fetch_assoc();
    $respuesta = [
        'existe' => true,
        'id' => $cliente['id'],
        'nombre' => $cliente['nombre']
    ];
}

echo json_encode($respuesta); 