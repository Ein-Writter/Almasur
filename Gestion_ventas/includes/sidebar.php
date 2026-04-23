<?php
include_once 'config/db.php';
if (!isset($_SESSION['usuario_id'])) { header("Location: index.php"); exit(); }

$u_id = $_SESSION['usuario_id'];
$rol_usuario = $_SESSION['usuario_rol'] ?? 'Empleado';
$foto_usuario = $_SESSION['usuario_foto'] ?? 'assets/img/default_avatar.jpg';
?>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <img id="logo-sidebar" src="uploads/almasur.png" alt="Logo" class="logo-sistema">
            <span class="business-name">ALMASUR</span>
        </div>
        <button id="toggle-btn" class="toggle-btn">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>

    <a href="perfil.php" class="user-link">
        <div class="user-panel">
            <div class="image">
                <img src="<?php echo $foto_usuario . '?v=' . time(); ?>" class="img-perfil-sidebar">
            </div>
            <div class="info">
                <p class="user-name"><?php echo $_SESSION['nombre']; ?></p>
                <small class="user-role"><?php echo $rol_usuario; ?></small>
            </div>
        </div>
    </a>

    <hr class="sidebar-divider">

    <nav class="sidebar-menu">
        <a href="menu.php"><i class="fa-solid fa-house"></i> <span>Inicio</span></a>
        <a href="inventario.php"><i class="fa-solid fa-box"></i> <span>Inventario</span></a>
        <a href="ventas.php"><i class="fa-solid fa-cart-plus"></i> <span>Ventas</span></a>
    
        <?php if ($rol_usuario === 'Administrador'): ?>
            <hr class="admin-divider">
            <a href="reportes.php"><i class="fa-solid fa-chart-line"></i> <span>Reportes</span></a>
            <a href="empleados.php"><i class="fa-solid fa-users"></i> <span>Empleados</span></a>
            <a href="ajustes.php"><i class="fa-solid fa-gear"></i> <span>Configuración</span></a>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <a href="logout.php" class="logout-link"><i class="fa-solid fa-right-from-bracket"></i> <span>Salir</span></a>
    </div>
</aside>

<style>
/* --- 1. BASE Y TRANSICIONES --- */
.sidebar { 
    transition: width 0.3s ease; 
    overflow-x: hidden; 
    display: flex;
    flex-direction: column;
}
.main-content { transition: margin-left 0.3s ease; }
.no-transition { transition: none !important; }

/* --- 2. ESTILOS MODO EXPANDIDO (POR DEFECTO) --- */
.user-link { text-decoration: none; display: block; }
.user-panel { text-align: center; padding: 20px 0; transition: all 0.3s; }
.img-perfil-sidebar { 
    width: 60px; height: 60px; 
    border-radius: 50%; 
    object-fit: cover; 
    border: 2px solid #38bdf8; 
    transition: all 0.3s; 
}
.user-name { color: white; margin: 0; font-weight: bold; white-space: nowrap; }
.user-role { color: #38bdf8; text-transform: uppercase; font-size: 0.7rem; white-space: nowrap; }

/* Margen para iconos en modo normal */
.sidebar-menu a i { margin-right: 10px; width: 20px; text-align: center; }

/* --- 3. TODO LO QUE CAMBIA AL COLAPSAR --- */
.sidebar.collapsed { width: 70px !important; }

/* Centrado de Header (Logo y Toggle) */
.sidebar.collapsed .sidebar-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 15px 0 !important;
}
.sidebar.collapsed .logo-container { margin-bottom: 15px; padding: 0 !important; }
.sidebar.collapsed .logo-sistema { width: 40px !important; margin: 0 !important; }

/* Centrado de Panel de Usuario */
.sidebar.collapsed .user-panel {
    display: flex;
    justify-content: center;
    padding: 15px 0 !important;
    pointer-events: none;
}
.sidebar.collapsed .img-perfil-sidebar { width: 45px !important; height: 45px !important; }

/* Centrado de Menú e Iconos */
.sidebar.collapsed .sidebar-menu a {
    display: flex;
    justify-content: center;
    padding: 12px 0 !important;
    width: 100%;
}
.sidebar.collapsed .sidebar-menu i { margin: 0 !important; font-size: 1.3rem; }

/* Centrado de Footer */
.sidebar.collapsed .sidebar-footer a {
    display: flex;
    justify-content: center;
    padding: 15px 0 !important;
}

/* OCULTAR TEXTOS (Unificado) */
.sidebar.collapsed .info,
.sidebar.collapsed .business-name,
.sidebar.collapsed .sidebar-menu span,
.sidebar.collapsed .sidebar-divider,
.sidebar.collapsed .admin-divider {
    display: none !important;
}

/* --- USER PANEL STYLES (Modo normal) --- */
.user-panel .info {
    margin: 0;
    padding: 0;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.user-panel { 
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px 0; 
    gap: 5px; /* Esto controla el espacio exacto entre foto, nombre y cargo */
}

.user-name { 
    color: white; 
    margin: 0 !important; /* Eliminamos el margen que lo empuja hacia abajo */
    padding: 0 !important;
    font-weight: bold; 
    white-space: nowrap; 
    text-align: center;
    line-height: 1.2; /* Ajusta la altura de la línea para que no ocupe espacio de más */
}

.user-role { 
    color: #38bdf8; 
    text-transform: uppercase; 
    font-size: 0.7rem; 
    margin: 0 !important;
    padding: 0 !important;
    white-space: nowrap; 
    line-height: 1;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggle-btn");
    const mainContent = document.querySelector(".main-content");

    const actualizarIcono = (isCollapsed) => {
        const icon = toggleBtn.querySelector("i");
        icon.classList.remove("fa-bars", "fa-chevron-right");
        icon.classList.add(isCollapsed ? "fa-chevron-right" : "fa-bars");
    };

    // 1. Cargar estado inicial
    const isCollapsed = localStorage.getItem("sidebarStatus") === "true";
    if (isCollapsed) {
        sidebar.classList.add("no-transition", "collapsed");
        if (mainContent) mainContent.classList.add("expanded");
        actualizarIcono(true);
        setTimeout(() => sidebar.classList.remove("no-transition"), 100);
    }

    // 2. Evento Único de Click
    toggleBtn.addEventListener("click", function() {
        const collapsed = sidebar.classList.toggle("collapsed");
        if (mainContent) mainContent.classList.toggle("expanded");
        
        localStorage.setItem("sidebarStatus", collapsed);
        actualizarIcono(collapsed);
    });
});
</script>