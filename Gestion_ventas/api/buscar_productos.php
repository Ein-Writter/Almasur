<?php
include '../config/db.php';

$q = $_GET['q'] ?? '';

$sql = "SELECT id, nombre, precio, stock FROM productos 
        WHERE nombre LIKE '%$q%' AND stock > 0 
        LIMIT 10";

$res = $conn->query($sql);
$productos = [];

while($row = $res->fetch_assoc()){
    $productos[] = $row;
}

echo json_encode($productos);
?>