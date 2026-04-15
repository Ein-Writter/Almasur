<?php 
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'config/db.php';

$resultado = $conn->query("SELECT * FROM configuracion WHERE id = 1");
$config = $resultado->fetch_assoc();

if (!$config) {
    $config = [
        'nombre_negocio' => 'Almasur',
        'ruc' => '',
        'direccion' => '',
        'telefono' => '',
        'mensaje_factura' => '',
        'moneda' => '$'
    ];
}?>

<main class="main-content">
    <div class="header-pagina">
        <h1>⚙️ Ajustes del Sistema</h1>
        <p>Configura los detalles de tu negocio y el formato de tus facturas.</p>
    </div>

    <div class="dashboard-grid">
        <div class="card-moderna" style="grid-column: span 2;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                <i class="fa-solid fa-file-invoice" style="color: var(--accent); font-size: 1.5rem;"></i>
                <h3 style="margin: 0;">Personalización de Factura / Ticket</h3>
            </div>

            <form action="api/guardar_ajustes.php" method="POST" enctype="multipart/form-data">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    
                    <div class="form-group">
                        <label>Nombre del Negocio</label>
                        <input type="text" name="nombre_negocio" class="input-dark" value="<?php echo $config['nombre_negocio'] ?? ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Documento Identificador (RUC / NIT / DNI)</label>
                        <input type="text" name="ruc" class="input-dark" value="<?php echo $config['ruc'] ?? ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Dirección</label>
                        <input type="text" name="direccion" class="input-dark" value="<?php echo $config['direccion'] ?? ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Teléfono de Contacto</label>
                        <input type="text" name="telefono" class="input-dark" value="<?php echo $config['telefono'] ?? ''; ?>">
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label>Mensaje al Pie de Factura (Agradecimiento)</label>
                        <textarea name="mensaje_factura" class="input-dark" rows="3"><?php echo $config['mensaje_factura'] ?? ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Logo del Negocio (Subir nueva imagen)</label>
                        <input type="file" name="logo" class="input-dark">
                    </div>

                    <div class="form-group">
                        <label>Moneda</label>
                        <select name="moneda" class="input-dark">
                            <option value="$" <?php if(($config['moneda'] ?? '') == '$') echo 'selected'; ?>>Dólar ($)</option>
                            <option value="S/" <?php if(($config['moneda'] ?? '') == 'S/') echo 'selected'; ?>>Soles (S/)</option>
                            <option value="€" <?php if(($config['moneda'] ?? '') == '€') echo 'selected'; ?>>Euro (€)</option>
                        </select>
                    </div>
                </div>

                <div style="margin-top: 30px; text-align: right;">
                    <button type="submit" class="btn-accion add" style="padding: 12px 30px;">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>

        <div class="card-moderna">
            <h3>👁️ Vista Previa</h3>
            <div id="preview-ticket" style="background: #fff; color: #000; padding: 15px; border-radius: 4px; font-family: 'Courier New', Courier, monospace; font-size: 12px; margin-top: 15px;">
                <div style="text-align: center; border-bottom: 1px dashed #000; padding-bottom: 10px;">
                    <strong id="p-nombre"><?php echo $config['nombre_negocio'] ?? 'Tu Negocio'; ?></strong><br>
                    <span id="p-ruc"><?php echo $config['ruc'] ?? '00000000'; ?></span>
                </div>
                <div style="margin: 10px 0;">
                    Cant. | Producto | Total<br>
                    ---------------------------<br>
                    1 x Producto Ejemplo $10.00
                </div>
                <div style="text-align: center; border-top: 1px dashed #000; padding-top: 10px;">
                    <span id="p-mensaje"><?php echo $config['mensaje_factura'] ?? '¡Gracias por su compra!'; ?></span>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Selección de todos los campos
    const inputNombre = document.querySelector('input[name="nombre_negocio"]');
    const inputRuc = document.querySelector('input[name="ruc"]');
    const inputDireccion = document.querySelector('input[name="direccion"]');
    const inputTelefono = document.querySelector('input[name="telefono"]');
    const textareaMensaje = document.querySelector('textarea[name="mensaje_factura"]');

    // --- VALIDACIONES DE ENTRADA (Bloqueo de caracteres) ---

    // Nombre del Negocio: Solo letras, números y espacios (Máx 50 caracteres)
    if (inputNombre) {
        inputNombre.setAttribute("maxlength", "50");
        inputNombre.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Z ]/g, '');
            document.getElementById('p-nombre').innerText = this.value || 'Tu Negocio';
        });
    }

    // RUC / DNI: Solo letras y números (Sin espacios)
    if (inputRuc) {
        inputRuc.setAttribute("maxlength", "20");
        inputRuc.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
            document.getElementById('p-ruc').innerText = this.value || '00000000';
        });
    }

    // Dirección: Letras, números, espacios, puntos, comas y guiones
    if (inputDireccion) {
        inputDireccion.setAttribute("maxlength", "100");
        inputDireccion.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Z0-9 .,#-]/g, '');
        });
    }

    // Teléfono: Solo números y el signo +
    if (inputTelefono) {
        inputTelefono.setAttribute("maxlength", "11");
        inputTelefono.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9+]/g, '');
        });
    }

    // Mensaje al Pie: Limitar a 150 caracteres para no deformar el ticket
    if (textareaMensaje) {
        textareaMensaje.setAttribute("maxlength", "150");
        textareaMensaje.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Z.,#- ]/g, '');
            document.getElementById('p-mensaje').innerText = this.value || '¡Gracias por su compra!';
        });
    }
});
</script>
