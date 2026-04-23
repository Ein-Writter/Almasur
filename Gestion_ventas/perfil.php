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

    <div class="dashboard-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
        <div class="card-moderna">
            <form action="api/actualizar_perfil.php" method="POST" enctype="multipart/form-data">
                
                <div class="text-center" style="margin-bottom: 30px;">
                    <div style="position: relative; display: inline-block;">
                        <img id="img_perfil" src="<?php echo !empty($user['foto_perfil']) ? $user['foto_perfil'] : 'assets/img/default_avatar.jpg'; ?>" 
                             style="width: 120px; height: 120px; border-radius: 50%; border: 3px solid var(--accent); object-fit: cover; background: #1e293b;">
                        
                        <label for="input_foto" style="position: absolute; bottom: 0; right: 0; background: var(--accent); color: white; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid var(--main-bg);">
                            <i class="fa-solid fa-camera"></i>
                        </label>
                        <input type="file" id="input_foto" name="foto" style="display: none;" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <p style="font-size: 0.8rem; color: var(--text-dim); margin-top: 10px;">Haz clic en la cámara para subir una foto</p>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label>Nombre Completo</label>
                        <input type="text" name="nombre" class="input-dark" value="<?php echo $user['nombre']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Nombre de Usuario</label>
                        <input type="text" name="usuario" class="input-dark" value="<?php echo $user['usuario']; ?>" required>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" class="input-dark" value="<?php echo $user['email']; ?>" required>
                </div>

                <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 30px 0;">
                
                <h3>🔒 Seguridad</h3>
                <p style="font-size: 0.8rem; color: var(--text-dim); margin-bottom: 15px;">La contraseña debe tener +8 caracteres, una mayúscula, un número y un carácter especial.</p>
                
                <div class="form-group">
                    <label>Nueva Contraseña</label>
                    <input type="password" 
                           name="new_password" 
                           id="pass"
                           class="input-dark" 
                           placeholder="Dejar en blanco para no cambiar"
                           pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}"
                           title="Debe contener al menos 8 caracteres, una mayúscula, un número y un carácter especial">
                </div>

                <button type="submit" class="btn-primario" style="width: 100%; margin-top: 25px; padding: 12px;">
                    <i class="fa-solid fa-save"></i> Guardar Cambios
                </button>
            </form>
        </div>

        <div class="card-moderna">
            <h3 style="margin-top:0;">Información de Cuenta</h3>
            <ul style="list-style: none; padding: 0; color: var(--text-dim);">
                <li style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <strong>Rol de Usuario:</strong><br>
                    <span class="badge" style="background: rgba(56, 189, 248, 0.1); color: #38bdf8; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem;"><?php echo strtoupper($user['rol']); ?></span>
                </li>
                <li style="margin-bottom: 15px;">
                    <strong>ID Interno:</strong><br>
                    <code style="color: var(--accent);">#<?php echo str_pad($user['id'], 5, "0", STR_PAD_LEFT); ?></code>
                </li>
                <li>
                    <strong>Sesión actual iniciada:</strong><br>
                    <span><?php echo date("d/m/Y H:i"); ?></span>
                </li>
            </ul>
        </div>
    </div>
</main>

<script>
// Función para previsualizar la imagen antes de subirla
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('img_perfil').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Validación visual en tiempo real para la contraseña (opcional pero recomendada)
document.getElementById('pass').addEventListener('input', function() {
    const pattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}$/;
    if(this.value.length > 0 && !pattern.test(this.value)) {
        this.style.borderColor = "#ef4444";
    } else {
        this.style.borderColor = "";
    }
});
</script>