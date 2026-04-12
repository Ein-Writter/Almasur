<?php 
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'config/db.php';

$stock_bajo = $conn->query("SELECT nombre, stock, precio FROM productos WHERE stock <= 5 ORDER BY stock ASC");

$facturas = $conn->query("SELECT v.id, v.fecha, v.total, u.nombre as vendedor 
                          FROM ventas v 
                          JOIN usuarios u ON v.id_usuario = u.id 
                          ORDER BY v.fecha DESC LIMIT 10");

$sql_grafica = "SELECT DATE(fecha) as dia, SUM(total) as total_dia 
                FROM ventas 
                WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DATE(fecha) 
                ORDER BY dia ASC";
$res_grafica = $conn->query($sql_grafica);

$labels = [];
$totales = [];

while($row = $res_grafica->fetch_assoc()) {
    $labels[] = $row['dia'];
    $totales[] = $row['total_dia'];
}
?>
<main class="main-content">
    <div class="header-pagina">
        <h1>📊 Reportes y Control</h1>
    </div>

    <div class="grid-reportes" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        
        <div class="card-moderna">
            <h3>⚠️ Stock Bajo (Crítico)</h3>
            <table class="tabla-moderna">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Stock</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $stock_bajo->fetch_assoc()): ?>
                    <tr style="color: <?php echo $row['stock'] <= 2 ? '#ef4444' : '#f59e0b'; ?>">
                        <td><?php echo $row['nombre']; ?></td>
                        <td><strong><?php echo $row['stock']; ?></strong></td>
                        <td><a href="inventario.php" class="btn-mini">Surtir</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="card-moderna">
            <h3>📄 Últimas Facturas</h3>
            <table class="tabla-moderna">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Total</th>
                        <th>Vendedor</th>
                        <th>Ticket</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($f = $facturas->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $f['id']; ?></td>
                        <td>$<?php echo number_format($f['total'], 2); ?></td>
                        <td><?php echo $f['vendedor']; ?></td>
                        <td>
                            <button onclick="imprimirTicket(<?php echo $f['id']; ?>)" class="btn-mini" style="background: #38bdf8;">
                                <i class="fa-solid fa-print"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
<br>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="card-moderna" style="grid-column: span 2; margin-bottom: 20px;">
    <h3>📈 Rendimiento de Ventas (Últimos 7 días)</h3>
    <div style="height: 300px;">
        <canvas id="graficaVentas"></canvas>
    </div>
</div>
</main>
<script>
    function imprimirTicket(id) {
    const url = `ticket.php?id=${id}`;
    const ancho = 400;
    const alto = 600;
    const x = (screen.width / 2) - (ancho / 2);
    const y = (screen.height / 2) - (alto / 2);
    
    window.open(url, 'Ticket', `width=${ancho},height=${alto},left=${x},top=${y}`);
}

const ctx = document.getElementById('graficaVentas').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Ventas Diarias ($)',
            data: <?php echo json_encode($totales); ?>,
            backgroundColor: 'rgba(56, 189, 248, 0.5)',
            borderColor: '#38bdf8',
            borderWidth: 2,
            borderRadius: 5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(255, 255, 255, 0.1)' }
            },
            x: {
                grid: { display: false }
            }
        },
        plugins: {
            legend: { display: false }
        }
    }
});
</script>