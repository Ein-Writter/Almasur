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
        <div style="display: flex; gap: 15px; align-items: center;">
            <div class="search-container" style="flex-grow: 1; position: relative;">
                <input type="text" id="inputBusqueda" placeholder="Buscar producto..." 
                       style="width: 100%; padding: 12px 40px; border-radius: 8px; background: #1e293b; color: white; border: 1px solid #334155;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 15px; top: 15px; color: #64748b;"></i>
            </div>
            <button class="btn-accion add" onclick="abrirModal()">
                <i class="fa-solid fa-plus"></i> Nuevo Producto
            </button>
            <div id="modalEdit" class="modal-overlay" style="display:none;">
    <div class="modal-content card-moderna" style="max-width: 450px; margin: 80px auto;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>✏️ Editar Producto</h3>
            <span onclick="cerrarModalEditar()" style="cursor:pointer; font-size: 24px;">&times;</span>
        </div>
        <form action="api/editar_producto.php" method="POST" style="margin-top: 20px;">
            <input type="hidden" name="id" id="edit_id">
            
            <label>Nombre del Producto</label>
            <input type="text" name="nombre" id="edit_nombre" class="input-dark" required style="width:100%; margin-bottom:15px;">
            
            <div style="display: flex; gap: 10px; margin-bottom:15px;">
                <div style="flex:1;">
                    <label>Precio ($)</label>
                    <input type="number" step="0.01" name="precio" id="edit_precio" class="input-dark" required style="width:100%;">
                </div>
                <div style="flex:1;">
                    <label>Stock</label>
                    <input type="number" name="stock" id="edit_stock" class="input-dark" required style="width:100%;">
                </div>
            </div>
            <button type="submit" class="btn-accion edit" style="width:100%; background: #3b82f6;">Actualizar Producto</button>
        </form>
    </div>
</div>
        </div>
    </div>

    <div class="card-moderna">
        <table class="tabla-moderna" id="tablaProductos">
            <thead>
                <tr>
                    <th onclick="ordenarTabla(0)" style="cursor:pointer">Producto <i class="fa-solid fa-sort"></i></th>
                    <th onclick="ordenarTabla(1)" style="cursor:pointer">Stock <i class="fa-solid fa-sort"></i></th>
                    <th onclick="ordenarTabla(2)" style="cursor:pointer">Precio <i class="fa-solid fa-sort"></i></th>
                    <th>Estado</i></th>
                    <th>Acciones</i></th>
                </tr>
            </thead>
            <tbody id="cuerpoTabla">
                <?php while($p = $productos->fetch_assoc()): ?>
                <tr>
    <td><strong><?php echo $p['nombre']; ?></strong></td>
    <td data-valor="<?php echo $p['stock']; ?>"><?php echo $p['stock']; ?> uds.</td>
    <td data-valor="<?php echo $p['precio']; ?>">$<?php echo number_format($p['precio'], 2); ?></td>
    <td>
        <?php if($p['stock'] <= 5): ?>
            <span class="badge" style="background: #ef4444;">Bajo Stock</span>
        <?php else: ?>
            <span class="badge" style="background: #10b981;">Disponible</span>
        <?php endif; ?>
    </td>
    <td>
        <button class="btn-mini edit" onclick="abrirModalEditar(<?php echo htmlspecialchars(json_encode($p)); ?>)">
            <i class="fa-solid fa-pen"></i>
        </button>
    </td>
</tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

<div id="modalProducto" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitulo"><i class="fa-solid fa-box-open"></i> Nuevo Producto</h2>
            <button onclick="cerrarModalProducto()" class="btn-close">&times;</button>
        </div>
        
        <form action="api/guardar_producto.php" method="POST" id="formProducto">
            <input type="hidden" name="id" id="p_id">
            
            <div class="grid-form">
                <div class="form-group">
                    <label>Nombre del Producto</label>
                    <input type="text" name="nombre" id="p_nombre" class="input-dark" required placeholder="Ej: Teclado Mecánico">
                    
                    <label style="margin-top:15px; display:block;">Categoría</label>
                    <select name="id_categoria" id="p_categoria" class="input-dark" style="width:100%;">
                        <option value="1">Electrónica</option>
                        <option value="2">Hogar</option>
                        <option value="3">Oficina</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Precio ($)</label>
                    <input type="number" step="0.01" name="precio" id="p_precio" class="input-dark" required placeholder="0.00">
                    
                    <label style="margin-top:15px; display:block;">Stock Inicial</label>
                    <input type="number" name="stock" id="p_stock" class="input-dark" required placeholder="0">
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" onclick="cerrarModalProducto()" class="btn-rojo">Cancelar</button>
                <button type="submit" class="btn-azul" id="btnGuardar">
                    <i class="fa-solid fa-save"></i> Guardar Producto
                </button>
            </div>
        </form>
    </div>
</div>

</main>
<style>
<style>
    .modal-overlay { 
        position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
        background: rgba(0,0,0,0.85); z-index: 1000; 
        backdrop-filter: blur(5px); display: none; align-items: center; justify-content: center; 
    }
    .modal-content { 
        background: #1e1e2d; padding: 30px; border-radius: 12px; 
        width: 95%; max-width: 700px; color: white; border: 1px solid #334155; 
    }
    .grid-form { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #334155; padding-bottom: 15px; margin-bottom: 20px; }
    .modal-footer { display: flex; justify-content: flex-end; gap: 15px; border-top: 1px solid #334155; padding-top: 20px; margin-top: 20px; }
    
    .input-dark { width: 100%; background: #0f172a; border: 1px solid #334155; color: white; padding: 12px; border-radius: 6px; margin-top: 5px; outline: none; }
    .input-dark:focus { border-color: #38bdf8; }
    .btn-close { background: none; border: none; color: #64748b; font-size: 24px; cursor: pointer; }
    .btn-close:hover { color: white; }
</style></style>

<script>
function abrirModal() {
    document.getElementById('formProducto').reset(); 
    document.getElementById('p_id').value = "";
    document.getElementById('modalTitulo').innerHTML = '<i class="fa-solid fa-plus"></i> Nuevo Producto';
    document.getElementById('btnGuardar').innerText = "Guardar Producto";
    document.getElementById('formProducto').action = "api/guardar_producto.php"; 
    document.getElementById('modalProducto').style.display = 'flex';
}

function abrirModalEditar(producto) {
    document.getElementById('p_id').value = producto.id;
    document.getElementById('p_nombre').value = producto.nombre;
    document.getElementById('p_precio').value = producto.precio;
    document.getElementById('p_stock').value = producto.stock;
    // document.getElementById('p_categoria').value = producto.id_categoria;

    document.getElementById('modalTitulo').innerHTML = '<i class="fa-solid fa-pen"></i> Editar Producto';
    document.getElementById('btnGuardar').innerText = "Actualizar Producto";
    document.getElementById('formProducto').action = "api/editar_producto.php"; 
    document.getElementById('modalProducto').style.display = 'flex';
}

function cerrarModalProducto() {
    document.getElementById('modalProducto').style.display = 'none';
}

window.onclick = function(event) {
    let modal = document.getElementById('modalProducto');
    if (event.target == modal) cerrarModalProducto();
}
document.getElementById('inputBusqueda').addEventListener('keyup', function() {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll('#cuerpoTabla tr');
    filas.forEach(f => f.style.display = f.innerText.toLowerCase().includes(filtro) ? '' : 'none');
});

function ordenarTabla(n) {
    let tabla = document.getElementById("tablaProductos");
    let filas = Array.from(tabla.rows).slice(1);
    let direccion = tabla.getAttribute('data-dir') === 'asc' ? 'desc' : 'asc';
    tabla.setAttribute('data-dir', direccion);

    filas.sort((a, b) => {
        let v1 = a.cells[n].getAttribute('data-valor') || a.cells[n].innerText.toLowerCase();
        let v2 = b.cells[n].getAttribute('data-valor') || b.cells[n].innerText.toLowerCase();
        
        if (!isNaN(v1) && !isNaN(v2)) return direccion === 'asc' ? v1 - v2 : v2 - v1;
        return direccion === 'asc' ? v1.localeCompare(v2) : v2.localeCompare(v1);
    });

    filas.forEach(f => document.getElementById('cuerpoTabla').appendChild(f));
}
function abrirModalEditar(producto) {
    document.getElementById('edit_id').value = producto.id;
    document.getElementById('edit_nombre').value = producto.nombre;
    document.getElementById('edit_precio').value = producto.precio;
    document.getElementById('edit_stock').value = producto.stock;
    document.getElementById('modalEdit').style.display = 'block';
}

function cerrarModalEditar() {
    document.getElementById('modalEdit').style.display = 'none';
}
</script>