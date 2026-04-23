<?php
include 'config/db.php';
$id_u = $_SESSION['usuario_id'];
$id_venta = $_GET['id'];
$res = $conn->query("SELECT dv.*, p.nombre FROM detalle_ventas dv 
                     JOIN productos p ON dv.id_producto = p.id 
                     WHERE dv.id_venta = $id_venta");
?>
<div class="card" style="margin: 40px; color: white; background: #1a1a2e;">
    <h2>Detalle de Factura #<?php echo $id_venta; ?></h2>
    <hr>
    <?php while($item = $res->fetch_assoc()): ?>
        <p><?php echo $item['nombre']; ?> x <?php echo $item['cantidad']; ?> - $<?php echo $item['precio_unitario']; ?></p>
    <?php endwhile; ?>
    <button onclick="window.print()" class="btn-filtro">Imprimir Factura</button>
</div>
<div style="text-align: right; margin-top: 10px;">
    <p>Subtotal: $<?php echo number_format($info_venta['subtotal'], 2); ?></p>
    <p>IVA (16%): $<?php echo number_format($info_venta['impuesto'], 2); ?></p>
    <h3 style="border-top: 1px solid #333; display: inline-block; padding-top: 5px;">
        TOTAL: $<?php echo number_format($info_venta['total'], 2); ?>
    </h3>
</div>