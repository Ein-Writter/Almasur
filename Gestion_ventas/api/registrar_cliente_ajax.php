<?php
include '../config/db.php';
$data = json_decode(file_get_contents('php://input'), true);

$identidad = $data['identidad'];
$nombre = $data['nombre'];
$telefono = $data['telefono'];

$check = $conn->query("SELECT id FROM clientes WHERE identidad = '$identidad'");

if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'mensaje' => 'Esa identidad ya existe']);
} else {
    $sql = "INSERT INTO clientes (identidad, nombre, telefono) VALUES ('$identidad', '$nombre', '$telefono')";
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false, 'mensaje' => $conn->error]);
    }
}