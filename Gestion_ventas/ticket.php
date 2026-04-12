<?php
include 'config/db.php';

$id_venta = isset($_GET['id']) ? intval($_GET['id']) : 0;

$res_config = $conn->query("SELECT * FROM configuracion WHERE id = 1");
$config = $res_config->fetch_assoc();

$sql_venta = "SELECT v.*, u.nombre as vendedor, c.nombre as cliente_nombre, c.identidad as cliente_id 
              FROM ventas v 
              LEFT JOIN usuarios u ON v.id_usuario = u.id 
              LEFT JOIN clientes c ON v.id_cliente = c.id 
              WHERE v.id = $id_venta";

$res_venta = $conn->query($sql_venta);

if (!$res_venta || $res_venta->num_rows == 0) {
    die("Venta no encontrada.");
}
$venta = $res_venta->fetch_assoc();

$detalles = $conn->query("SELECT d.*, p.nombre 
                          FROM detalle_ventas d 
                          JOIN productos p ON d.id_producto = p.id 
                          WHERE d.id_venta = $id_venta");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #<?php echo $id_venta; ?></title>
    <style>
        * { box-sizing: border-box; }
        body { 
            font-family: 'Courier New', Courier, monospace; 
            width: 100%; max-width: 58mm; 
            margin: 0 auto; padding: 2mm; 
            font-size: 13px; line-height: 1.2;
        }
        .text-center { text-align: center; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        .tabla { width: 100%; border-collapse: collapse; }
        .total { text-align: right; font-weight: bold; font-size: 16px; margin-top: 5px; }
        .datos-cliente { font-size: 12px; margin: 5px 0; }
        @media print { .btn-print { display: none; } }
    </style>
</head>
<body onload="window.print();">

    <div class="text-center">
        <strong><?php echo strtoupper($config['nombre_negocio']); ?></strong><br>
        RUC: <?php echo $config['ruc']; ?><br>
        Tel: <?php echo $config['telefono']; ?>
    </div>

    <div class="divider"></div>
    
    <div class="datos-cliente">
        <strong>RECEPTOR:</strong><br>
        Nombre: <?php echo !empty($venta['cliente_nombre']) ? strtoupper($venta['cliente_nombre']) : "CONSUMIDOR FINAL"; ?><br>
        ID/CI: <?php echo !empty($venta['cliente_id']) ? $venta['cliente_id'] : "S/N"; ?>
    </div>

    <div class="divider"></div>
    
    <div>
        Ticket: #<?php echo str_pad($id_venta, 6, "0", STR_PAD_LEFT); ?><br>
        Fecha: <?php echo date("d/m/Y H:i", strtotime($venta['fecha'])); ?><br>
        Vendedor: <?php echo $venta['vendedor'] ?? 'Gral'; ?>
    </div>

    <div class="divider"></div>

    <table class="tabla">
        <thead>
            <tr>
                <th align="left">Cant</th>
                <th align="left">Prod</th>
                <th align="right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php while($item = $detalles->fetch_assoc()): ?>
            <tr>
                <td><?php echo $item['cantidad']; ?></td>
                <td><?php echo substr($item['nombre'], 0, 15); ?></td>
                <td align="right"><?php echo $config['moneda'] . number_format($item['precio'] * $item['cantidad'], 2); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="total">
        TOTAL: <?php echo $config['moneda'] . number_format($venta['total'], 2); ?>
    </div>

    <div class="divider"></div>
    <div class="text-center">
        <?php echo $config['mensaje_factura']; ?>
    </div>
</body>
</html>