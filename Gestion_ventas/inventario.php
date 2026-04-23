<?php 
include 'config/db.php'; 
include 'includes/header.php'; 
include 'includes/sidebar.php'; 

// Traemos solo los productos activos. 
// Si quieres ocultar también los de stock 0, añade: AND stock > 0
$productos = $conn->query("SELECT * FROM productos WHERE estado = 1 ORDER BY nombre ASC");
?>

<main class="main-content">
    <div class="header-pagina">
        <h1><i class="fa-solid fa-boxes-stacked"></i> Gestión de Almacén</h1>
        
        <div style="display: flex; gap: 15px; align-items: center; margin-top: 15px; flex-wrap: wrap;">
            
            <div class="search-container" style="flex-grow: 1; position: relative; min-width: 300px;">
                <input type="text" id="inputBusqueda" placeholder="Buscar por nombre, código o categoría..." 
                       style="width: 100%; padding: 12px 45px; border-radius: 8px; background: #1e293b; color: white; border: 1px solid #334155; outline: none;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 15px; top: 15px; color: #64748b;"></i>
            </div>

			<div>
				<label style="color: #94a3b8; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; gap: 8px;">
					<input type="checkbox" id="verSinStock" checked style="accent-color: #3b82f6;"> 
					Stock Cero
				</label>
			</div>
            <button class="btn-primario" onclick="abrirModal()" style="background: #3b82f6; color: white; padding: 12px 20px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-plus"></i> Nuevo Producto
            </button>
            
        </div>
    </div>
	<div class="card-moderna">
        <table class="tabla-moderna" id="tablaProductos">
            <thead>
                <tr>
                    <th onclick="ordenarTabla(0)" style="cursor:pointer">Código <i class="fa-solid fa-barcode"></i></th>
                    <th onclick="ordenarTabla(1)" style="cursor:pointer">Producto <i class="fa-solid fa-sort"></i></th>
                    <th>Categoría</th>
                    <th onclick="ordenarTabla(3)" style="cursor:pointer">Stock <i class="fa-solid fa-sort"></i></th>
                    <th onclick="ordenarTabla(4)" style="cursor:pointer">Precio <i class="fa-solid fa-sort"></i></th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="cuerpoTabla">
                <?php while($p = $productos->fetch_assoc()): ?>
                <tr>
                    <td style="font-family: monospace; color: #94a3b8;"><?php echo $p['codigo']; ?></td>
                    <td><strong><?php echo $p['nombre']; ?></strong></td>
                    <td><span class="badge-categoria"><?php echo $p['categoria'] ?? 'Sin categoría'; ?></span></td>
                    <td data-valor="<?php echo $p['stock']; ?>"><?php echo $p['stock']; ?> uds.</td>
                    <td data-valor="<?php echo $p['precio']; ?>">$<?php echo number_format($p['precio'], 2); ?></td>
                    <td>
                        <span class="badge" style="background: <?php echo ($p['stock'] <= 5) ? 'rgba(239, 68, 68, 0.2)' : 'rgba(16, 185, 129, 0.2)'; ?>; color: <?php echo ($p['stock'] <= 5) ? '#f87171' : '#10b981'; ?>;">
                            <?php echo ($p['stock'] <= 5) ? 'Bajo Stock' : 'Disponible'; ?>
                        </span>
                    </td>
					<td>
						<button class="btn-mini edit" onclick='abrirModal(<?php echo json_encode($p); ?>)'>
							<i class="fa-solid fa-pen"></i>
						</button>
						
						<button class="btn-mini delete" onclick="descontinuarProducto(<?php echo $p['id']; ?>, '<?php echo $p['nombre']; ?>')" style="background: #ef4444;">
							<i class="fa-solid fa-eye-slash"></i>
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
        
        <form action="api/guardar_producto.php" method="POST" id="formProducto" onsubmit="return validarFormulario(event)">
            <input type="hidden" name="id" id="p_id">
            
            <div class="grid-form">
				<div class="form-group" style="grid-column: span 2;">
					<label>Código de Barras / SKU</label>
					<input type="text" 
						name="codigo" 
						id="p_codigo" 
						class="input-dark" 
						required 
						maxlength="30" 
						pattern="[A-Za-z0-9\-]+" 
						title="Solo letras, números y guiones"
						placeholder="Escanea o escribe el código">
				</div>

                <div class="form-group" style="grid-column: span 1;">
                    <label>Nombre del Producto</label>
                    <input type="text" name="nombre" id="p_nombre" class="input-dark" required>
                </div>

                <div class="form-group" style="grid-column: span 1;">
                    <label>Categoría</label>
                    <select name="categoria" id="p_categoria" class="input-dark">
                        <option value="General">General</option>
                        <option value="Alimentos">Alimentos</option>
						<option value="Uso Diario">Uso Diario</option>
                        <option value="Ferretería">Ferretería</option>
                        <option value="Plomería">Plomería</option>
                        <option value="Electronica">Electronica</option>
                        <option value="Herramientas">Herramientas</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Precio Unitario ($)</label>
                    <input type="number" step="0.01" name="precio" id="p_precio" class="input-dark" required>
                </div>

                <div class="form-group">
                    <label>Stock Inicial</label>
                    <input type="number" name="stock" id="p_stock" class="input-dark" required>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" onclick="cerrarModal()" class="btn-rojo">Cancelar</button>
                <button type="submit" class="btn-azul" id="btnGuardar">Guardar Cambios</button>
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
	.badge-categoria {
    background: #334155;
    color: #cbd5e1;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    text-transform: uppercase;
}
</style>
<script>
// 1. GESTIÓN DEL MODAL (CREAR / EDITAR)
function abrirModal(p = null) {
    const form = document.getElementById('formProducto');
    const alertBox = document.getElementById('alert-container');
    if(alertBox) alertBox.style.display = 'none';
    form.reset();

    if (p) {
        document.getElementById('modalTitulo').innerHTML = '<i class="fa-solid fa-pen"></i> Editar Producto';
        document.getElementById('p_id').value = p.id;
        document.getElementById('p_codigo').value = p.codigo || '';
        document.getElementById('p_nombre').value = p.nombre;
        document.getElementById('p_categoria').value = p.categoria || 'General';
        document.getElementById('p_precio').value = p.precio;
        document.getElementById('p_stock').value = p.stock;
        form.action = "api/editar_producto.php";
    } else {
        document.getElementById('modalTitulo').innerHTML = '<i class="fa-solid fa-plus"></i> Nuevo Producto';
        document.getElementById('p_id').value = "";
        document.getElementById('p_categoria').value = "General";
        form.action = "api/guardar_producto.php";
    }
    document.getElementById('modalProducto').style.display = 'flex';
    setTimeout(() => document.getElementById('p_codigo').focus(), 100);
}

function cerrarModal() {
    document.getElementById('modalProducto').style.display = 'none';
}

// 2. VALIDACIÓN DE FORMULARIO
function validarFormulario(event) {
    const codigo = document.getElementById('p_codigo').value.trim();
    const precio = parseFloat(document.getElementById('p_precio').value);
    const stock = parseInt(document.getElementById('p_stock').value);
    const alertBox = document.getElementById('alert-container');
    const alertMsg = document.getElementById('alert-msg');

    let errores = [];
    if (codigo.length < 3) errores.push("El código debe tener al menos 3 caracteres.");
    if (isNaN(precio) || precio <= 0) errores.push("El precio debe ser mayor a 0.");
    if (isNaN(stock) || stock < 0) errores.push("El stock no puede ser negativo.");

    if (errores.length > 0) {
        event.preventDefault();
        alertMsg.innerText = errores.join(" | ");
        alertBox.style.display = 'block';
        return false;
    }
    return true;
}

// 3. FILTRADO DINÁMICO (BUSCADOR + STOCK 0)
function filtrarTabla() {
    const filtro = document.getElementById('inputBusqueda').value.toLowerCase();
    const checkStock = document.getElementById('verSinStock');
    const mostrarSinStock = checkStock ? checkStock.checked : true; // Si no hay checkbox, muestra todo
    const filas = document.querySelectorAll('#cuerpoTabla tr');

    filas.forEach(fila => {
        const textoFila = fila.innerText.toLowerCase();
        const celdaStock = fila.querySelector('td[data-valor]');
        const stock = celdaStock ? parseInt(celdaStock.getAttribute('data-valor')) : 0;

        const coincideTexto = textoFila.includes(filtro);
        const coincideStock = mostrarSinStock || stock > 0;

        fila.style.display = (coincideTexto && coincideStock) ? '' : 'none';
    });
}

// Eventos para el filtrado
document.getElementById('inputBusqueda').addEventListener('keyup', filtrarTabla);
if(document.getElementById('verSinStock')) {
    document.getElementById('verSinStock').addEventListener('change', filtrarTabla);
}

// 4. ORDENAR TABLA
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

// 5. ACCIONES Y ALERTAS
function descontinuarProducto(id, nombre) {
    if (confirm(`¿Estás seguro de que deseas ocultar "${nombre}" del inventario?`)) {
        window.location.href = `api/eliminar_producto.php?id=${id}`;
    }
}

// Cerrar al hacer clic fuera del modal
window.onclick = (e) => { if (e.target.classList.contains('modal-overlay')) cerrarModal(); }

// Manejo de mensajes por URL
const urlParams = new URLSearchParams(window.location.search);
const status = urlParams.get('status');
const error = urlParams.get('error');

if (status === 'success') alert("¡Producto registrado correctamente!");
if (status === 'updated') alert("¡Producto actualizado!");
if (status === 'hidden') alert("El producto ha sido ocultado del inventario.");
if (error === 'codigo_duplicado') alert("Error: El código de barras ya está registrado.");
if (codigo.length > 30) errores.push("El código es demasiado largo (máx 50).");
// Opcional: Validar que no tenga espacios
if (/\s/.test(codigo)) errores.push("El código no puede contener espacios.");
</script>