<?php 
include 'config/db.php'; 
include 'includes/header.php'; 
include 'includes/sidebar.php'; 

// Traemos los productos activos
$productos = $conn->query("SELECT * FROM productos WHERE estado = 1 ORDER BY nombre ASC");
?>

<main class="main-content">
    <div class="header-pagina">
        <h1><i class="fa-solid fa-boxes-stacked"></i> Gestión de Almacén</h1>
        
        <div style="display: flex; gap: 15px; align-items: center; margin-top: 15px; flex-wrap: wrap;">
            <div class="search-container" style="flex-grow: 1; position: relative; min-width: 300px;">
                <input type="text" id="inputBusqueda" placeholder="Buscar por nombre, código, marca o categoría..." 
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
                    <th>Marca</th>
                    <th>Categoría</th>
                    <th onclick="ordenarTabla(4)" style="cursor:pointer">Stock <i class="fa-solid fa-sort"></i></th>
                    <th onclick="ordenarTabla(5)" style="cursor:pointer">Precio <i class="fa-solid fa-sort"></i></th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="cuerpoTabla">
                <?php while($p = $productos->fetch_assoc()): ?>
                <tr>
                    <td style="font-family: monospace; color: #94a3b8;"><?php echo $p['codigo']; ?></td>
                    <td><strong><?php echo $p['nombre']; ?></strong></td>
                    <td><span style="color: #cbd5e1;"><?php echo $p['marca'] ?? '---'; ?></span></td>
                    <td><span class="badge-categoria"><?php echo $p['categoria'] ?? 'GENERAL'; ?></span></td>
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

    <div class="modal-overlay" id="modalProducto" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 2000; display: none; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
        <div class="modal-content-dark" style="background: #1e293b; padding: 25px; border-radius: 12px; width: 95%; max-width: 600px; border: 1px solid #334155; color: white;">
            <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #334155; padding-bottom: 15px; margin-bottom: 20px;">
                <h3 id="modalTitulo"><i class="fa-solid fa-boxes-stacked"></i> Registro de Producto</h3>
                <span onclick="cerrarModal()" style="cursor:pointer; font-size: 1.5rem; color: #94a3b8;">&times;</span>
            </div>
            
            <form id="formProducto" action="api/guardar_producto.php" method="POST" onsubmit="return validarFormulario(event)">
                <input type="hidden" name="id" id="p_id">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div style="grid-column: span 2;">
                        <label style="color: #94a3b8; font-size: 0.9rem;">Código de Barras / SKU</label>
                        <div style="display: flex; gap: 5px; margin-top: 5px;">
                            <input type="text" name="codigo" id="p_codigo" class="input-dark" placeholder="Escanea o escribe" required style="flex: 1; background: #0f172a; border: 1px solid #334155; color: white; padding: 10px; border-radius: 6px;">
                            <button type="button" onclick="generarSKU()" style="background: #475569; color: white; border: none; padding: 0 15px; border-radius: 6px; cursor: pointer;">
                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label style="color: #94a3b8; font-size: 0.9rem;">Nombre del Producto</label>
                        <input type="text" name="nombre" id="p_nombre" class="input-dark" required style="width: 100%; background: #0f172a; border: 1px solid #334155; color: white; padding: 10px; border-radius: 6px; margin-top: 5px;">
                    </div>

                    <div>
                        <label style="color: #94a3b8; font-size: 0.9rem;">Marca</label>
                        <input type="text" name="marca" id="p_marca" class="input-dark" placeholder="Ej: Nestlé" style="width: 100%; background: #0f172a; border: 1px solid #334155; color: white; padding: 10px; border-radius: 6px; margin-top: 5px;">
                    </div>

                    <div>
                        <label style="color: #94a3b8; font-size: 0.9rem;">Categoría</label>
                        <select name="categoria" id="p_categoria" style="width: 100%; background: #0f172a; border: 1px solid #334155; color: white; padding: 10px; border-radius: 6px; margin-top: 5px;">
                            <option value="ALIMENTOS">🍎 Alimentos</option>
                            <option value="BEBIDAS">🥤 Bebidas</option>
                            <option value="LIMPIEZA">🧼 Limpieza</option>
                            <option value="GENERAL">📦 General</option>
                            <option value="ELECTRONICA">🔌 Electrónica</option>
                        </select>
                    </div>

                    <div>
                        <label style="color: #94a3b8; font-size: 0.9rem;">Precio Unitario ($)</label>
                        <input type="number" step="0.01" name="precio" id="p_precio" class="input-dark" required style="width: 100%; background: #0f172a; border: 1px solid #334155; color: white; padding: 10px; border-radius: 6px; margin-top: 5px;">
                    </div>

                    <div style="grid-column: span 2;">
                        <label style="color: #94a3b8; font-size: 0.9rem;">Stock Inicial</label>
                        <input type="number" name="stock" id="p_stock" class="input-dark" value="0" style="width: 100%; background: #0f172a; border: 1px solid #334155; color: white; padding: 10px; border-radius: 6px; margin-top: 5px;">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px;">
                    <button type="button" onclick="cerrarModal()" style="background: #475569; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">Cancelar</button>
                    <button type="submit" style="background: #3b82f6; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold;">
                        <i class="fa-solid fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
// 1. ABRIR Y CERRAR MODAL
function abrirModal(p = null) {
    const form = document.getElementById('formProducto');
    form.reset();

    if (p) {
        document.getElementById('modalTitulo').innerHTML = '<i class="fa-solid fa-pen"></i> Editar Producto';
        document.getElementById('p_id').value = p.id;
        document.getElementById('p_codigo').value = p.codigo || '';
        document.getElementById('p_nombre').value = p.nombre;
        document.getElementById('p_marca').value = p.marca || '';
        document.getElementById('p_categoria').value = p.categoria || 'GENERAL';
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

function cerrarModal() {
    document.getElementById('modalProducto').style.display = 'none';
}

// 2. GENERAR SKU
function generarSKU() {
    const prefijo = "PROD";
    const random = Math.floor(1000 + Math.random() * 9000);
    const fecha = new Date().getFullYear().toString().substr(-2);
    document.getElementById('p_codigo').value = `${prefijo}-${fecha}${random}`;
}

// 3. LIMPIAR CÓDIGO AL ESCRIBIR
document.getElementById('p_codigo').addEventListener('input', function() {
    this.value = this.value.toUpperCase().replace(/[^A-Z0-9-]/g, '');
});

// 4. FILTRADO DE TABLA
function filtrarTabla() {
    const filtro = document.getElementById('inputBusqueda').value.toLowerCase();
    const mostrarSinStock = document.getElementById('verSinStock').checked;
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

document.getElementById('inputBusqueda').addEventListener('keyup', filtrarTabla);
document.getElementById('verSinStock').addEventListener('change', filtrarTabla);

// 5. ACCIÓN DE ELIMINAR/OCULTAR
function descontinuarProducto(id, nombre) {
    if (confirm(`¿Ocultar "${nombre}" del inventario?`)) {
        window.location.href = `api/eliminar_producto.php?id=${id}`;
    }
}
</script>

<style>
    .badge-categoria { background: #334155; color: #cbd5e1; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; text-transform: uppercase; }
    .btn-mini { border: none; padding: 6px 10px; border-radius: 4px; cursor: pointer; color: white; transition: 0.2s; margin-right: 4px; }
    .btn-mini.edit { background: #3b82f6; }
    .btn-mini.edit:hover { background: #2563eb; }
    .tabla-moderna th { text-align: left; padding: 15px; background: #1e293b; color: #94a3b8; font-weight: 600; border-bottom: 1px solid #334155; }
    .tabla-moderna td { padding: 15px; border-bottom: 1px solid #334155; color: white; }
</style>
