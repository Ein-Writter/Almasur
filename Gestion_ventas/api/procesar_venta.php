<?php
ob_start(); 
session_start();
header('Content-Type: application/json');
include '../config/db.php';

error_reporting(0);
ini_set('display_errors', 0);

$json = file_get_contents('php://input');
$data = json_decode($json, true);

try {
    if (!isset($_SESSION['usuario_id'])) {
        throw new Exception("Sesión no iniciada");
    }

    $u_id     = $_SESSION['usuario_id'];
    $subtotal = $data['subtotal'] ?? 0;
    $iva      = $data['iva'] ?? 0;
    $total    = $data['total'] ?? 0;
    
    $id_cliente = (!empty($data['id_cliente'])) ? intval($data['id_cliente']) : "NULL";

    $sql_v = "INSERT INTO ventas (id_usuario, id_cliente, subtotal, impuesto, total, fecha) 
              VALUES ($u_id, $id_cliente, $subtotal, $iva, $total, NOW())";
    
    if (!$conn->query($sql_v)) {
        throw new Exception("Error en Venta: " . $conn->error);
    }

    $venta_id = $conn->insert_id;

    if (isset($data['items']) && is_array($data['items'])) {
        foreach ($data['items'] as $item) {
            $id_p = intval($item['id']);
            $can = intval($item['cantidad']);
            $pre = floatval($item['precio']);

            $conn->query("INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio) VALUES ($venta_id, $id_p, $can, $pre)");
            $conn->query("UPDATE productos SET stock = stock - $can WHERE id = $id_p");
        }
    }

    ob_clean(); 
    echo json_encode(['status' => 'ok', 'id_venta' => $venta_id]);

} catch (Exception $e) {
    ob_clean();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
exit; 