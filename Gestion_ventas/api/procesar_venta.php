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
    
    // Extraer datos del pago mixto
    $p_usd     = $data['metodos_pago']['efectivo_usd'] ?? 0;
    $p_bs_f    = $data['metodos_pago']['efectivo_bs'] ?? 0;
    $p_bs_d    = $data['metodos_pago']['digital_bs'] ?? 0;
    $vuelto    = $data['metodos_pago']['vuelto_usd'] ?? 0;
    $tasa_v    = $data['metodos_pago']['tasa_usada'] ?? 1;

    $id_cliente = (!empty($data['id_cliente'])) ? intval($data['id_cliente']) : "NULL";

    // Insertar con el desglose de pago
    $sql_v = "INSERT INTO ventas (id_usuario, id_cliente, subtotal, impuesto, total, pago_usd, pago_bs_efectivo, pago_bs_digital, vuelto_usd, tasa_momento, fecha) 
              VALUES ($u_id, $id_cliente, $subtotal, $iva, $total, $p_usd, $p_bs_f, $p_bs_d, $vuelto, $tasa_v, NOW())";
    
    if (!$conn->query($sql_v)) {
        throw new Exception("Error en Venta: " . $conn->error);
    }

    $venta_id = $conn->insert_id;

    if (isset($data['items']) && is_array($data['items'])) {
        foreach ($data['items'] as $item) {
            $id_p = intval($item['id']);
            $can = intval($item['cantidad']);
            $pre = floatval($item['precio']);

            // Insertar detalle
            $conn->query("INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio) VALUES ($venta_id, $id_p, $can, $pre)");
            
            // Descontar Stock
            $conn->query("UPDATE productos SET stock = stock - $can WHERE id = $id_p");
        }
    }

    ob_clean(); 
    echo json_encode(['status' => 'ok', 'id_venta' => $venta_id]);

} catch (Exception $e) {
    if (ob_get_length()) ob_clean();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
exit;