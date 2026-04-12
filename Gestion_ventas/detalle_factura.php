<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit(); }

$id_venta = $_GET['id'];

$info_venta = $conn->query("SELECT v.*, u.nombre_negocio FROM ventas v 
                            JOIN usuarios u ON v.id_usuario = u.id 
                            WHERE v.id = $id_venta")->fetch_assoc();

$detalles = $conn->query("SELECT dv.*, p.nombre FROM detalle_ventas dv 
                          JOIN productos p ON dv.id_producto = p.id 
                          WHERE dv.id_venta = $id_venta");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle Factura #<?php echo $id_venta; ?></title>
    <link rel="stylesheet" href="css/menu_style.css">
</head>
<body style="background: #1a1a2e; color: white; padding: 40px;">

    <div class="card" style="max-width: 600px; margin: auto; background: white; color: #333; padding: 30px; border-radius: 0;">
        <div style="text-align: center; border-bottom: 2px dashed #ccc; padding-bottom: 20px;">
            <h2 style="margin:0;"><?php echo $info_venta['nombre_negocio']; ?></h2>
            <p>Factura de Venta: #<?php echo $id_venta; ?></p>
            <p>Fecha: <?php echo $info_venta['fecha']; ?></p>
        </div>

        <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid #eee;">
                    <th style="text-align: left;">Producto</th>
                    <th style="text-align: center;">Cant.</th>
                    <th style="text-align: right;">Precio</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while($item = $detalles->fetch_assoc()): 
                    $subtotal = $item['cantidad'] * $item['precio_unitario'];
                ?>
                <tr>
                    <td><?php echo $item['nombre']; ?></td>
                    <td style="text-align: center;"><?php echo $item['cantidad']; ?></td>
                    <td style="text-align: right;">$<?php echo $item['precio_unitario']; ?></td>
                    <td style="text-align: right;">$<?php echo number_format($subtotal, 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div style="margin-top: 20px; border-top: 2px solid #333; padding-top: 10px; text-align: right;">
            <h3>TOTAL: $<?php echo number_format($info_venta['total'], 2); ?></h3>
        </div>

        <div style="margin-top: 30px; text-align: center;" class="no-print">
            <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">🖨️ Imprimir Factura</button>
            <br><br>
            <a href="reportes.php" style="color: #666;">Volver a Reportes</a>
        </div>
    </div>

    <style>
        @media print { .no-print { display: none; } body { background: white; } .card { box-shadow: none; border: none; } }
    </style>
</body>
</html>