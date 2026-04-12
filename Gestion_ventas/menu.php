<?php 
include 'includes/header.php'; 
include 'includes/sidebar.php'; 

$id_u = $_SESSION['usuario_id'];
$user_data = $conn->query("SELECT * FROM usuarios WHERE id = $id_u")->fetch_assoc();
$hoy = date('Y-m-d');
$ventas_hoy = $conn->query("SELECT SUM(total) as total FROM ventas WHERE fecha = '$hoy'")->fetch_assoc();
$prod_bajos = $conn->query("SELECT COUNT(*) as cuenta FROM productos WHERE stock <= 5")->fetch_assoc();
?>

<main class="main-content">
    <div class="header-pagina">
        <h1><i class="fa-solid fa-chart-pie"></i> Menu</h1>
        <p>Bienvenido de nuevo, Este es el estado de Almasur hoy.</p>
    </div>

    <div class="dashboard-grid">
        
        <div class="card-stats">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <i class="fa-solid fa-dollar-sign"></i>
            </div>
            <div class="stat-data">
                <span class="stat-title">Ventas de Hoy</span>
                <h2 class="stat-value">$<?php echo number_format($ventas_hoy['total'] ?? 0, 2); ?></h2>
            </div>
        </div>

        <div class="card-stats">
            <div class="stat-icon" style="background: rgba(248, 113, 113, 0.1); color: #f87171;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="stat-data">
                <span class="stat-title">Stock Bajo</span>
                <h2 class="stat-value"><?php echo $prod_bajos['cuenta']; ?> <small>productos</small></h2>
            </div>
        </div>

        <div class="card-stats">
            <div class="stat-icon" style="background: rgba(56, 189, 248, 0.1); color: #38bdf8;">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <div class="stat-data">
                <span class="stat-title">Órdenes Pendientes</span>
                <h2 class="stat-value">12</h2>
            </div>
        </div>

    </div>
</main>

</body>
</html>