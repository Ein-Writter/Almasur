<?php 
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'config/db.php';

$id_usuario = $_SESSION['usuario_id'];
$user = $conn->query("SELECT * FROM usuarios WHERE id = $id_usuario")->fetch_assoc();
?>

<main class="main-content">
    <div class="header-pagina">
        <h1>👤 Mi Perfil</h1>
        <p>Gestiona tu información personal y seguridad.</p>
    </div>

    <div class="dashboard-grid">
        <div class="card-moderna">
            <form action="api/actualizar_perfil.php" method="POST" enctype="multipart/form-data">
                <div class="text-center" style="margin-bottom: 20px;">
                    <img src="<?php echo $user['foto_perfil'] ?? 'assets/img/default_avatar.jpg'; ?>" 
                         style="width: 100px; height: 100px; border-radius: 50%; border: 3px solid var(--accent); object-fit: cover;">
                    <br><br>
                    <label class="btn-mini" style="background: var(--main-bg); cursor: pointer;">
                        Cambiar Foto
                        <input type="file" name="foto" style="display: none;">
                    </label>
                </div>

                <div class="form-group">
                    <label>Nombre Completo</label>
                    <input type="text" name="nombre" class="input-dark" value="<?php echo $user['nombre']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Correo Electrónico / Usuario</label>
                    <input type="text" name="email" class="input-dark" value="<?php echo $user['email'] ?? $user['usuario']; ?>" required>
                </div>

                <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 20px 0;">
                
                <h3>🔒 Cambiar Contraseña</h3>
                <p style="font-size: 0.8rem; color: var(--text-dim);">Deja en blanco si no deseas cambiarla.</p>
                
                <div class="form-group">
                    <label>Nueva Contraseña</label>
                    <input type="password" name="new_password" class="input-dark" placeholder="••••••••">
                </div>

                <button type="submit" class="btn-accion add" style="width: 100%; margin-top: 20px;">
                    Guardar Cambios
                </button>
            </form>
        </div>

        <div class="card-moderna">
            <h3>Información de Cuenta</h3>
            <ul style="list-style: none; padding: 0; color: var(--text-dim);">
                <li style="margin-bottom: 15px;">
                    <strong>Rol de Usuario:</strong><br>
                    <span style="color: var(--accent);"><?php echo strtoupper($user['rol']); ?></span>
                </li>
                <li style="margin-bottom: 15px;">
                    <strong>ID de Usuario:</strong><br>
                    #<?php echo $user['id']; ?>
                </li>
                <li>
                    <strong>Último Acceso:</strong><br>
                    <span><?php echo date("d/m/Y H:i"); ?></span>
                </li>
            </ul>
        </div>
    </div>
</main>