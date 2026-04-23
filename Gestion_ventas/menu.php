<?php 
include 'includes/header.php'; 
include 'includes/sidebar.php'; 

$id_u = $_SESSION['usuario_id'];
$rol_u = $_SESSION['usuario_rol']; // Asumiendo que guardas el rol en la sesión

// Datos de usuario
$user_data = $conn->query("SELECT * FROM usuarios WHERE id = $id_u")->fetch_assoc();
$hoy = date('Y-m-d');

// Estadísticas
$ventas_hoy = $conn->query("SELECT SUM(total) as total FROM ventas WHERE fecha = '$hoy'")->fetch_assoc();
$prod_bajos = $conn->query("SELECT COUNT(*) as cuenta FROM productos WHERE stock <= 5")->fetch_assoc();

// 1. Últimas 5 ventas del usuario actual
$ultimas_ventas = $conn->query("SELECT v.id, v.total, v.fecha, c.nombre as cliente 
                                FROM ventas v 
                                LEFT JOIN clientes c ON v.id_cliente = c.id 
                                WHERE v.id_usuario = $id_u 
                                ORDER BY v.id DESC LIMIT 5");

// 2. Últimos 5 inicios de sesión (necesitas una tabla llamada 'logs_acceso')
$logs_acceso = $conn->query("SELECT l.*, u.usuario 
                             FROM logs_acceso l 
                             JOIN usuarios u ON l.id_usuario = u.id 
                             ORDER BY l.fecha_hora DESC LIMIT 5");
?>

<main class="main-content">
    <div class="header-pagina">
        <h1><i class="fa-solid fa-chart-pie"></i> Panel de Control</h1>
        <p>Bienvenido de nuevo, <strong><?php echo $user_data['nombre']; ?></strong>. Este es el resumen de hoy.</p>
    </div>

    <div class="dashboard-grid">
        <div class="card-stats">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <i class="fa-solid fa-dollar-sign"></i>
            </div>
            <div class="stat-data">
                <span class="stat-title">Ventas Totales (Hoy)</span>
                <h2 class="stat-value">$<?php echo number_format($ventas_hoy['total'] ?? 0, 2); ?></h2>
            </div>
        </div>

        <div class="card-stats">
            <div class="stat-icon" style="background: rgba(248, 113, 113, 0.1); color: #f87171;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="stat-data">
                <span class="stat-title">Alertas de Stock</span>
                <h2 class="stat-value"><?php echo $prod_bajos['cuenta']; ?> <small>items</small></h2>
            </div>
        </div>

        <?php if($rol_u == 'admin'): ?>
        <div class="card-stats">
            <div class="stat-icon" style="background: rgba(168, 85, 247, 0.1); color: #a855f7;">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div class="stat-data">
                <span class="stat-title">Modo Administrador</span>
                <h2 class="stat-value">Activo</h2>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 30px;">
        
        <div class="card-moderna">
            <h3><i class="fa-solid fa-receipt"></i> Mis Últimas Ventas</h3>
            <table class="tabla-moderna" style="font-size: 0.9rem; margin-top: 15px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($v = $ultimas_ventas->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $v['id']; ?></td>
                        <td><?php echo $v['cliente'] ?? 'Público General'; ?></td>
                        <td style="color: #10b981; font-weight: bold;">$<?php echo number_format($v['total'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="card-moderna">
            <h3><i class="fa-solid fa-clock-rotate-left"></i> Accesos al Sistema</h3>
            <table class="tabla-moderna" style="font-size: 0.9rem; margin-top: 15px;">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Fecha/Hora</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($logs_acceso): while($l = $logs_acceso->fetch_assoc()): ?>
                    <tr>
                        <td><i class="fa-solid fa-user-circle"></i> <?php echo $l['usuario']; ?></td>
                        <td style="color: #94a3b8;"><?php echo date('d/m H:i', strtotime($l['fecha_hora'])); ?></td>
                    </tr>
                    <?php endwhile; endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</main>