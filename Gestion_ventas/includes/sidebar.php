<?php
include_once 'config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$u_id = $_SESSION['usuario_id'];
$rol_usuario = $_SESSION['rol'] ?? 'Empleado';

$query_side = $conn->query("SELECT * FROM usuarios WHERE id = $u_id");
$data_side = $query_side->fetch_assoc();

$side_foto = !empty($data_side['foto_perfil']) ? $data_side['foto_perfil'] : 'img/user_default.png';
$side_logo = !empty($data_side['logo']) ? $data_side['logo'] : 'img/logo_default.png';
$side_nom  = !empty($data_side['nombre_negocio']) ? $data_side['nombre_negocio'] : 'Almasur';
?>
<?php
include_once 'config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$u_id = $_SESSION['usuario_id'];
$rol_usuario = $_SESSION['rol'] ?? 'Empleado';

$query_side = $conn->query("SELECT * FROM usuarios WHERE id = $u_id");
$data_side = $query_side->fetch_assoc();

$side_foto = !empty($data_side['foto_perfil']) ? $data_side['foto_perfil'] : 'img/user_default.png';
$side_logo = !empty($data_side['logo']) ? $data_side['logo'] : 'img/logo_default.png';
$side_nom  = !empty($data_side['nombre_negocio']) ? $data_side['nombre_negocio'] : 'Almasur';
?>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <img src="uploads/almasur.png" alt="Almasur Logo" class="logo-sistema">
            <span class="business-name">ALMASUR</span>
        </div>
        <button id="toggle-btn" class="toggle-btn">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
<a href="perfil.php" style="text-decoration: none;">
    <div class="user-profile-sidebar">
        <div class="user-avatar">
            <?php 
            $foto_perfil = $_SESSION['foto_perfil'] ?? 'uploads/jairo.png'; 
            ?>
            <img src="<?php echo $foto_perfil; ?>" alt="Perfil">
        </div>
        <div class="user-info">
            <span class="user-name"><?php echo $_SESSION['nombre']; ?></span>
            <span class="user-role"><?php echo strtoupper($_SESSION['rol']); ?></span>
        </div>
    </div>
</a>
</div>
<hr class="sidebar-divider">
    <nav class="sidebar-menu">
        <a href="menu.php"><i class="fa-solid fa-house"></i> <span>Dashboard</span></a>
        <a href="ventas.php"><i class="fa-solid fa-cart-shopping"></i> <span>Ventas</span></a>
        <a href="inventario.php"><i class="fa-solid fa-box"></i> <span>Almacén</span></a>
        <a href="reportes.php"><i class="fa-solid fa-chart-line"></i> <span>Reportes</span></a>
        <?php if ($rol_usuario === 'Administrador'): ?>
        <hr style="border: 0.5px solid rgba(255,255,255,0.1); margin: 10px 0;">
        <a href="empleados.php"><i class="fa-solid fa-users-gear"></i> <span>Empleados</span></a>
        <a href="ajustes.php"><i class="fa-solid fa-gears"></i> <span>Configuración</span></a>
    <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <a href="logout.php" class="logout-link"><i class="fa-solid fa-right-from-bracket"></i> <span>Salir</span></a>
    </div>
</aside>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.querySelector(".main-content");
    const toggleBtn = document.getElementById("toggle-btn");

    toggleBtn.addEventListener("click", function() {
        sidebar.classList.toggle("collapsed");
        mainContent.classList.toggle("expanded");
        
        const icon = toggleBtn.querySelector("i");
        if (sidebar.classList.contains("collapsed")) {
            icon.classList.replace("fa-bars", "fa-chevron-right");
        } else {
            icon.classList.replace("fa-chevron-right", "fa-bars");
        }
    });
});
</script>

