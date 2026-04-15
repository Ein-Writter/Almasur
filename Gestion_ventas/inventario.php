<?php 
include 'config/db.php'; 
include 'includes/header.php'; 
include 'includes/sidebar.php'; 

$productos = $conn->query("SELECT * FROM productos ORDER BY nombre ASC");

if (!$productos) {
    die("Error en la base de datos: " . $conn->error);
}
?>

<main class="main-content">
    <div class="header-pagina">
        <h1>📦 Gestión de Almacén</h1>
        <div style="display: flex; gap: 15px; align-items: center; margin-top: 15px;">
            <div class="search-container" style="flex-grow: 1; position: relative;">
                <input type="text" id="inputBusqueda" placeholder="Buscar producto..." 
                       style="width: 100%; padding: 12px 40px; border-radius: 8px; background: #1e293b; color: white; border: 1px solid #334155;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 15px; top: 15px; color: #64748b;"></i>
            </div>
            <button class="btn-primario" onclick="abrirModal()">
                <i class="fa-solid fa-plus"></i> Nuevo Producto
            </button>
        </div>
    </div>

    <div class="card-moderna">
        <table class="tabla-moderna" id="tablaProductos">
            <thead>
                <tr>
                    <th onclick="ordenarTabla(0)" style="cursor:pointer">Producto <i class="fa-solid fa-sort"></i></th>
                    <th onclick="ordenarTabla(1)" style="cursor:pointer">Stock <i class="fa-solid fa-sort"></i></th>
                    <th onclick="ordenarTabla(2)" style="cursor:pointer">Precio <i class="fa-solid fa-sort"></i></th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="cuerpoTabla">
                <?php while($p = $productos->fetch_assoc()): ?>
                <tr>
                    <td><strong><?php echo $p['nombre']; ?></strong></td>
                    <td data-valor="<?php echo $p['stock']; ?>"><?php echo $p['stock']; ?> uds.</td>
                    <td data-valor="<?php echo $p['precio']; ?>">$<?php echo number_format($p['precio'], 2); ?></td>
                    <td>
                        <span class="badge" style="background: <?php echo ($p['stock'] <= 5) ? '#ef4444' : '#10b981'; ?>;">
                            <?php echo ($p['stock'] <= 5) ? 'Bajo Stock' : 'Disponible'; ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn-mini edit" onclick='abrirModal(<?php echo json_encode($p); ?>)'>
                            <i class="fa-solid fa-pen"></i>
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

<div id="modalProducto" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitulo"><i class="fa-solid fa-box-open"></i> Producto</h2>
            <button onclick="cerrarModal()" class="btn-close">&times;</button>
        </div>
        
        <div id="alert-container" style="display:none; background: rgba(239, 68, 68, 0.2); color: #f87171; padding: 10px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #ef4444; font-size: 0.9rem;">
            <i class="fa-solid fa-triangle-exclamation"></i> <span id="alert-msg"></span>
        </div>

        <form action="api/guardar_producto.php" method="POST" id="formProducto" onsubmit="return validarFormulario(event)">
            <input type="hidden" name="id" id="p_id">
            
            <div class="grid-form">
                <div class="form-group" style="grid-column: span 1;">
                    <label>Código</label>
                    <input type="text" name="codigo" id="p_codigo" class="input-dark" required placeholder="Ej: PROD-001">
                </div>

                <div class="form-group" style="grid-column: span 1;">
                    <label>Nombre</label>
                    <input type="text" name="nombre" id="p_nombre" class="input-dark" required>
                </div>

                <div class="form-group">
                    <label>Precio ($)</label>
                    <input type="number" step="0.01" name="precio" id="p_precio" class="input-dark" required>
                </div>

                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" id="p_stock" class="input-dark" required>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" onclick="cerrarModal()" class="btn-rojo">Cancelar</button>
                <button type="submit" class="btn-azul" id="btnGuardar">Guardar Producto</button>
            </div>
        </form>
    </div>
</div>
</main>

<style>
    .modal-overlay { 
        position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
        background: rgba(0,0,0,0.85); z-index: 2000; 
        backdrop-filter: blur(5px); display: none; align-items: center; justify-content: center; 
    }
    .modal-content { 
        background: #1e1e2d; padding: 30px; border-radius: 12px; 
        width: 95%; max-width: 500px; color: white; border: 1px solid #334155; 
    }
    .grid-form { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    .input-dark { width: 100%; background: #0f172a; border: 1px solid #334155; color: white; padding: 12px; border-radius: 6px; margin-top: 5px; outline: none; }
    .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #334155; padding-bottom: 15px; margin-bottom: 20px; }
    .modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; }
</style>

<script>
// FUNCIÓN UNIFICADA: Si recibe 'p', edita. Si no, crea.
function abrirModal(p = null) {
    const form = document.getElementById('formProducto');
    const alertBox = document.getElementById('alert-container');
    alertBox.style.display = 'none'; // Limpiar avisos previos
    form.reset();

    if (p) {
        document.getElementById('modalTitulo').innerHTML = '<i class="fa-solid fa-pen"></i> Editar Producto';
        document.getElementById('p_id').value = p.id;
        document.getElementById('p_codigo').value = p.codigo || ''; // Asegúrate de traer el código del fetch
        document.getElementById('p_nombre').value = p.nombre;
        document.getElementById('p_precio').value = p.precio;
        document.getElementById('p_stock').value = p.stock;
        form.action = "api/editar_producto.php";
    } else {
        document.getElementById('modalTitulo').innerHTML = '<i class="fa-solid fa-plus"></i> Nuevo Producto';
        document.getElementById('p_id').value = "";
        form.action = "api/guardar_producto.php";
    }
    document.getElementById('modalProducto').style.display = 'flex';
}

function validarFormulario(event) {
    const codigo = document.getElementById('p_codigo').value.trim();
    const precio = parseFloat(document.getElementById('p_precio').value);
    const stock = parseInt(document.getElementById('p_stock').value);
    const alertBox = document.getElementById('alert-container');
    const alertMsg = document.getElementById('alert-msg');

    let errores = [];

    if (codigo.length < 3) errores.push("El código debe tener al menos 3 caracteres.");
    if (isNaN(precio) || precio <= 0) errores.push("El precio debe ser mayor a 0.");
    if (isNaN(stock) || stock < 1) errores.push("El stock no puede ser 0.");

    if (errores.length > 0) {
        event.preventDefault(); // Detiene el envío del formulario
        alertMsg.innerText = errores.join(" | ");
        alertBox.style.display = 'block';
        return false;
    }

    return true;
}

function cerrarModal() {
    document.getElementById('modalProducto').style.display = 'none';
}

// Buscador optimizado
document.getElementById('inputBusqueda').addEventListener('keyup', function() {
    let filtro = this.value.toLowerCase();
    document.querySelectorAll('#cuerpoTabla tr').forEach(fila => {
        fila.style.display = fila.innerText.toLowerCase().includes(filtro) ? '' : 'none';
    });
});

// Ordenar tabla
function ordenarTabla(n) {
    let tabla = document.getElementById("tablaProductos");
    let filas = Array.from(tabla.rows).slice(1);
    let dir = tabla.getAttribute('data-dir') === 'asc' ? 'desc' : 'asc';
    tabla.setAttribute('data-dir', dir);

    filas.sort((a, b) => {
        let v1 = a.cells[n].getAttribute('data-valor') || a.cells[n].innerText.trim().toLowerCase();
        let v2 = b.cells[n].getAttribute('data-valor') || b.cells[n].innerText.trim().toLowerCase();
        return dir === 'asc' ? (isNaN(v1) ? v1.localeCompare(v2) : v1 - v2) : (isNaN(v1) ? v2.localeCompare(v1) : v2 - v1);
    });

    filas.forEach(f => document.getElementById('cuerpoTabla').appendChild(f));
}

// Cerrar al hacer clic fuera
window.onclick = (e) => { if (e.target.classList.contains('modal-overlay')) cerrarModal(); }
</script>
