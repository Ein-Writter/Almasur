<?php 
include 'includes/header.php'; 
include 'includes/sidebar.php'; 

if ($_SESSION['rol'] !== 'Administrador') {
    echo "<script>alert('Acceso denegado'); window.location.href='menu.php';</script>";
    exit;
}

$usuarios = $conn->query("SELECT id, nombre, usuario, email, rol FROM usuarios ORDER BY nombre ASC");
?>

<main class="main-content">
    <div class="header-pagina" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h1>👥 Gestión de Empleados</h1>
            <p style="color: #94a3b8;">Administra los accesos y niveles de seguridad de tu equipo.</p>
        </div>
        <button class="btn-accion add" onclick="abrirModalEmpleado()">
            <i class="fa-solid fa-user-plus"></i> Nuevo Empleado
        </button>
    </div>

    <div class="card-moderna">
        <table class="tabla-moderna">
            <thead>
                <tr>
                    <th>Nombre Completo</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Nivel de Acceso</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($user = $usuarios->fetch_assoc()): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($user['nombre'] ?? 'Sin nombre'); ?></strong></td>
                    <td><code style="color: #38bdf8;"><?php echo htmlspecialchars($user['usuario']); ?></code></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <span class="badge <?php echo ($user['rol'] == 'Administrador') ? 'badge-verde' : 'badge-azul'; ?>">
                            <?php echo ($user['rol'] == 'Administrador') ? '⚡ Admin' : '👤 Empleado'; ?>
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <button class="btn-mini edit" title="Editar Permisos" onclick='abrirEditarEmpleado(<?php echo json_encode($user); ?>)'>
                            <i class="fa-solid fa-user-gear"></i>
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<div id="modalEmpleado" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitulo"><i class="fa-solid fa-user-shield"></i> Registro de Personal</h2>
            <button onclick="cerrarModalEmpleado()" class="btn-close">&times;</button>
        </div>
        
        <form action="api/empleados_acciones.php" method="POST" id="formEmpleado">
            <input type="hidden" name="id" id="emp_id">
            
            <div class="grid-form">
                <div class="form-group">
                <label>Nombre Completo</label>
                <input type="text" name="nombre" id="emp_nombre" class="input-dark" required placeholder="Ej: Juan Pérez">
                <small id="name_error" style="color: #ef4444; display: none; margin-top: 5px;">
                    ⚠️ El nombre solo puede contener letras y espacios.
                </small>                    
                    <label style="margin-top:15px; display:block;">Correo Electrónico</label>
                    <input type="email" name="email" id="emp_email" class="input-dark" required placeholder="juan@empresa.com">
                </div>

                <div class="form-group">
                    <label>Nombre de Usuario</label>
                    <input type="text" name="usuario" id="emp_usuario" class="input-dark" required placeholder="jperez2024">
                    
                    <label style="margin-top:15px; display:block;">Contraseña</label>
                    <input type="password" name="password" id="emp_password" class="input-dark" placeholder="Mínimo 8 caracteres">
                    <small id="pass_help" style="color: #64748b; display:none;">Dejar en blanco para no cambiar.</small>

                        <ul id="password-requirements" style="list-style: none; padding: 10px; margin-top: 5px; font-size: 0.75rem; background: #0f172a; border-radius: 6px;">
                            <li id="req-length" class="invalid">✖ 8+ caracteres</li>
                            <li id="req-upper" class="invalid">✖ Una mayúscula</li>
                            <li id="req-number" class="invalid">✖ Un número</li>
                            <li id="req-special" class="invalid">✖ Un carácter (@$!%*?)</li>
                        </ul>
                </div>
            </div>

            <div style="margin-top:20px;">
                <label>Asignar Rol de Acceso</label>
                <select name="rol" id="emp_rol" class="input-dark" style="width:100%;">
                    <option value="Empleado">👤 Empleado (Ventas e Inventario)</option>
                    <option value="Administrador">⚡ Administrador (Control Total)</option>
                </select>
            </div>
            
            <div class="modal-footer">
                <button type="button" onclick="cerrarModalEmpleado()" class="btn-rojo">Cancelar</button>
                <button type="submit" class="btn-azul">
                    <i class="fa-solid fa-save"></i> <span id="btnTexto">Registrar Empleado</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .badge { padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: bold; }
    .badge-verde { background: #064e3b; color: #34d399; border: 1px solid #059669; }
    .badge-azul { background: #1e3a8a; color: #93c5fd; border: 1px solid #2563eb; }
</style>

<script>
function abrirModalEmpleado() {
    document.getElementById('formEmpleado').reset();
    document.getElementById('emp_id').value = "";
    document.getElementById('modalTitulo').innerHTML = '<i class="fa-solid fa-user-plus"></i> Nuevo Empleado';
    document.getElementById('btnTexto').innerText = "Registrar Empleado";
    document.getElementById('pass_help').style.display = 'none';
    document.getElementById('emp_password').required = true;
    document.getElementById('modalEmpleado').style.display = 'flex';
}

function abrirEditarEmpleado(user) {
    document.getElementById('emp_id').value = user.id;
    document.getElementById('emp_nombre').value = user.nombre;
    document.getElementById('emp_email').value = user.email;
    document.getElementById('emp_usuario').value = user.usuario;
    document.getElementById('emp_rol').value = user.rol;
    
    document.getElementById('modalTitulo').innerHTML = '<i class="fa-solid fa-user-gear"></i> Editar Permisos';
    document.getElementById('btnTexto').innerText = "Actualizar Datos";
    document.getElementById('pass_help').style.display = 'block';
    document.getElementById('emp_password').required = false; 
    document.getElementById('modalEmpleado').style.display = 'flex';
}

function cerrarModalEmpleado() {
    document.getElementById('modalEmpleado').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('modalEmpleado');
    if (event.target == modal) cerrarModalEmpleado();
}

document.addEventListener('DOMContentLoaded', () => {
    const passInput = document.getElementById('emp_password');
    const reqs = {
        length: document.getElementById('req-length'),
        upper: document.getElementById('req-upper'),
        number: document.getElementById('req-number'),
        special: document.getElementById('req-special')
    };

    passInput.addEventListener('input', () => {
        const val = passInput.value;
        
        // Si el campo está vacío y estamos editando, no mostrar error (es opcional)
        if (val === "" && !passInput.required) {
             Object.values(reqs).forEach(el => el.className = 'invalid');
             return;
        }

        const checks = {
            length: val.length >= 8,
            upper: /[A-Z]/.test(val),
            number: /[0-9]/.test(val),
            special: /[@$!%*?&]/.test(val)
        };

        // Actualizar visualmente cada requisito
        reqs.length.className = checks.length ? 'valid' : 'invalid';
        reqs.length.innerText = (checks.length ? '✔' : '✖') + ' 8+ caracteres';

        reqs.upper.className = checks.upper ? 'valid' : 'invalid';
        reqs.upper.innerText = (checks.upper ? '✔' : '✖') + ' Una mayúscula';

        reqs.number.className = checks.number ? 'valid' : 'invalid';
        reqs.number.innerText = (checks.number ? '✔' : '✖') + ' Un número';

        reqs.special.className = checks.special ? 'valid' : 'invalid';
        reqs.special.innerText = (checks.special ? '✔' : '✖') + ' Un carácter (@$!%*?)';
    });

    const nameInput = document.getElementById('emp_nombre');
const nameError = document.getElementById('name_error');

if (nameInput) {
    nameInput.addEventListener('input', () => {
        // Esta RegEx permite letras (a-z, A-Z), espacios, y caracteres latinos (á, é, í, ó, ú, ñ)
        const nameRegEx = /^[a-zA-ZÀ-ÿ\s]+$/;
        
        if (nameInput.value !== "" && !nameRegEx.test(nameInput.value)) {
            nameError.style.display = 'block';
            nameInput.style.borderColor = '#ef4444'; // Borde rojo si hay error
        } else {
            nameError.style.display = 'none';
            nameInput.style.borderColor = ''; // Restaurar borde original
        }
    });
}

});
document.getElementById('formEmpleado').addEventListener('submit', function(e) {
    const nameInput = document.getElementById('emp_nombre').value;
    const nameRegEx = /^[a-zA-ZÀ-ÿ\s]+$/;

    if (!nameRegEx.test(nameInput)) {
        e.preventDefault(); // Detener el envío
        alert("Por favor, corrige el nombre antes de continuar.");
        return;
    }
    
    // Aquí también podrías validar la contraseña antes de enviar si es un nuevo empleado
});

// Tip: En tu función abrirModalEmpleado(), añade esta línea para resetear los iconos:
// Object.values(reqs).forEach(el => el.className = 'invalid');
</script>
