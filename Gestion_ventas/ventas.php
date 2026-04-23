<?php 
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
?>

<main class="main-content">
    <div class="header-pagina">
        <h1>🛒 Nueva Venta</h1>
        <p>Selecciona productos y genera el cobro.</p>
    </div>

    <div class="venta-container" style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
        <div class="card-moderna">
            <div class="search-container" style="position: relative; margin-bottom: 20px;">
                <input type="text" id="buscar_producto" class="input-dark" 
                       placeholder="Escribe el nombre o código del producto..." autocomplete="off"
                       style="width: 100%; padding: 15px 40px; border-radius: 8px;">
                <i class="fa-solid fa-barcode" style="position: absolute; left: 15px; top: 18px; color: #64748b;"></i>
            </div>
            
            <div id="resultados_busqueda" class="contenedor-tabla" style="max-height: 450px; overflow-y: auto;">
                <p style="text-align:center; color: #64748b; padding: 20px;">Los resultados aparecerán aquí...</p>
            </div>
        </div>

        <div class="card-moderna">
            <h3 style="margin-top:0;"><i class="fa-solid fa-receipt"></i> Detalle de Venta</h3>
            <hr style="border: 1px solid #334155; margin: 15px 0;">
            
            <div id="carrito_lista" style="min-height: 250px; max-height: 400px; overflow-y: auto;">
                <p style="text-align:center; color:#888;">El carrito está vacío</p>
            </div>
            
            <hr style="border: 1px solid #334155; margin: 15px 0;">
            
            <div class="totales">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; color: #94a3b8;">
                    <span>Subtotal:</span> <span id="subtotal">$0.00</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; color: #94a3b8;">
                    <span>IVA (16%):</span> <span id="iva_display">$0.00</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.4rem; color: #10b981; margin-top: 10px;">
                    <span>TOTAL:</span> <span id="total_final">$0.00</span>
                </div>
            </div>
            
            <button class="btn-primario" style="width: 100%; padding: 15px; margin-top: 20px; font-size: 1rem;" onclick="finalizarVenta()">
                <i class="fa-solid fa-cash-register"></i> FINALIZAR VENTA
            </button>
        </div>
    </div>

<div class="card-moderna" style="margin-top: 20px;">
    <div style="margin-bottom: 15px;">
        <h3 style="margin:0;"><i class="fa-solid fa-user-tag"></i> Datos del Cliente</h3>
        <p style="font-size: 0.8rem; color: #64748b; margin-top: 5px;">Busca por DNI para asignar la venta o registrar uno nuevo.</p>
    </div>
    
    <div class="search-box-cliente" style="display: flex; gap: 10px;">
        <input type="text" id="dni_busqueda" class="input-dark" placeholder="DNI o Identidad del cliente..." style="flex-grow: 1;">
        <button type="button" onclick="buscarCliente()" class="btn-azul">
            <i class="fa-solid fa-magnifying-glass"></i> Buscar
        </button>
    </div>
    
    <div id="info_cliente" class="cliente-status-bar" style="margin-top: 15px; padding: 12px; border: 1px solid #334155; border-radius: 6px; background: #0f172a; display: flex; align-items: center; justify-content: space-between;">
        <span><i class="fa-solid fa-circle-info"></i> Cliente: <strong>Público General</strong></span>
    </div>
    
    <input type="hidden" name="id_cliente" id="id_cliente_seleccionado" value="1">
</div>
    <div id="modalCliente" class="modal-overlay" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; justify-content: center; align-items: center;">
        <div class="modal-content" style="background: #1e293b; padding: 25px; border-radius: 12px; width: 450px; border: 1px solid #334155;">
            <h2 style="color: #38bdf8; margin-top:0;"><i class="fa-solid fa-user-plus"></i> Registrar Cliente</h2>
            <form id="formNuevoCliente">
                <div style="margin-bottom: 15px;">
                    <label>Identidad (Cédula/DNI):</label>
                    <input type="text" id="m_identidad" class="input-dark" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Nombre Completo:</label>
                    <input type="text" id="m_nombre" class="input-dark" required oninput="this.value = this.value.replace(/[^a-zA-ZñÑ\s]/g, '')">
                </div>
                <div style="margin-bottom: 20px;">
                    <label>Teléfono:</label>
                    <input type="text" id="m_telefono" class="input-dark" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="cerrarModal()" class="btn-rojo">Cancelar</button>
                    <button type="button" onclick="guardarClienteRapido()" class="btn-azul">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
let carrito = [];

// 1. BUSCAR PRODUCTOS (Con auto-añadido para Escáner)
document.getElementById('buscar_producto').addEventListener('input', function(e) {
    let query = e.target.value.trim().toLowerCase(); // Limpiamos espacios y pasamos a minúsculas
    
    if(query.length > 1) {
        fetch(`api/buscar_productos.php?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                console.log("Productos recibidos:", data); // Depuración: Ver qué llega del servidor

                // Buscamos coincidencia exacta de código (limpiando ambos lados)
                const productoExacto = data.find(p => {
                    const codLimpio = String(p.codigo).trim().toLowerCase();
                    return codLimpio === query;
                });
                
                if (productoExacto) {
                    console.log("¡Coincidencia exacta encontrada!", productoExacto);
                    agregarAlCarrito(productoExacto.id, productoExacto.nombre, productoExacto.precio, productoExacto.stock);
                    e.target.value = ""; 
                    document.getElementById('resultados_busqueda').innerHTML = "";
                } else {
                    renderResultados(data);
                }
            })
            .catch(err => console.error("Error en la búsqueda:", err));
    } else {
        document.getElementById('resultados_busqueda').innerHTML = "";
    }
});
function renderResultados(productos) {
    const contenedor = document.getElementById('resultados_busqueda');
    if (productos.length === 0) {
        contenedor.innerHTML = '<p style="padding:10px; color:gray;">No se encontraron productos.</p>';
        return;
    }
    contenedor.innerHTML = productos.map(p => `
        <div class="item-busqueda" onclick="agregarAlCarrito(${p.id}, '${p.nombre}', ${p.precio}, ${p.stock})" 
             style="display: flex; justify-content: space-between; padding: 12px; border-bottom: 1px solid #334155; cursor: pointer;">
            <div>
                <strong>${p.nombre}</strong> <br>
                <small style="color: #94a3b8;">Código: ${p.codigo} | Stock: ${p.stock}</small>
            </div>
            <div style="text-align: right;">
                <span style="color: #10b981; font-weight: bold;">$${p.precio}</span>
            </div>
        </div>
    `).join('');
}

// 2. GESTIÓN DEL CARRITO
function agregarAlCarrito(id, nombre, precio, stock) {
    if(stock <= 0) return alert("Producto agotado");
    
    let item = carrito.find(p => p.id === id);
    if(item) {
        if(item.cantidad < stock) item.cantidad++;
        else alert("Sin más existencias en stock");
    } else {
        carrito.push({id, nombre, precio, cantidad: 1, stock_max: stock});
    }
    actualizarVistaCarrito();
}

function actualizarVistaCarrito() {
    const contenedor = document.getElementById('carrito_lista');
    let subtotal = 0;
    
    if (carrito.length === 0) {
        contenedor.innerHTML = '<p style="text-align:center; color:#888;">El carrito está vacío</p>';
        actualizarTotales(0);
        return;
    }

    contenedor.innerHTML = carrito.map((item, index) => {
        subtotal += item.precio * item.cantidad;
        return `
            <div style="display:flex; justify-content:space-between; padding: 10px 0; border-bottom: 1px solid #334155;">
                <div>
                    <span style="font-weight:bold;">${item.nombre}</span><br>
                    <small>${item.cantidad} x $${parseFloat(item.precio).toFixed(2)}\</small\>\
                </div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <span style="font-weight:bold;">$${(item.precio * item.cantidad).toFixed(2)}</span>
                    <button onclick="eliminarDelCarrito(${index})" style="background:#ef4444; border:none; border-radius:4px; color:white; padding: 3px 7px; cursor:pointer;">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>`;
    }).join('');

    actualizarTotales(subtotal);
}

function actualizarTotales(subtotal) {
    const iva = subtotal * 0.16;
    const total = subtotal + iva;
    document.getElementById('subtotal').innerText = `$${subtotal.toFixed(2)}`;
    document.getElementById('iva_display').innerText = `$${iva.toFixed(2)}`;
    document.getElementById('total_final').innerText = `$${total.toFixed(2)}`;
}

// Para restar solo uno
function eliminarDelCarrito(index) {
    if (carrito[index].cantidad > 1) {
        carrito[index].cantidad--;
    } else {
        carrito.splice(index, 1);
    }
    actualizarVistaCarrito();
}

// 3. GESTIÓN DE CLIENTES (Flujo inteligente)
function buscarCliente() {
    let dni = document.getElementById('dni_busqueda').value.trim();
    if (!dni) return;

    fetch('api/buscar_cliente.php?dni=' + dni)
        .then(res => res.json())
        .then(data => {
            const infoBox = document.getElementById('info_cliente');
            if (data.existe) {
                infoBox.innerHTML = `
                    <span>✅ Cliente: <strong style="color:white;">${data.nombre}</strong></span>
                    <button onclick="desvincularCliente()" class="btn-mini" style="background:#475569; margin-left:10px;">Cambiar</button>
                `;
                infoBox.style.borderColor = "#10b981";
                document.getElementById('id_cliente_seleccionado').value = data.id;
            } else {
                infoBox.innerHTML = `
                    <span>❌ No encontrado.</span> 
                    <button onclick="abrirModalConDNI('${dni}')" style="background:none; border:none; color:#38bdf8; cursor:pointer; text-decoration:underline; font-size:0.9rem;">¿Registrar ahora?</button>
                `;
                infoBox.style.borderColor = "#ef4444";
                document.getElementById('id_cliente_seleccionado').value = "1";
            }
        });
}

function abrirModalConDNI(dni) {
    document.getElementById('m_identidad').value = dni; // Pasa el DNI buscado al modal automáticamente
    abrirModal();
}

function desvincularCliente() {
    document.getElementById('id_cliente_seleccionado').value = "1";
    document.getElementById('dni_busqueda').value = "";
    document.getElementById('info_cliente').innerHTML = `<span><i class="fa-solid fa-circle-info"></i> Cliente: <strong>Público General</strong></span>`;
    document.getElementById('info_cliente').style.borderColor = "#334155";
}

// 4. FINALIZAR PROCESO
function finalizarVenta() {
    if (carrito.length === 0) return alert("Añade productos primero");

    const datosVenta = {
        id_cliente: document.getElementById('id_cliente_seleccionado').value || 1,
        subtotal: parseFloat(document.getElementById('subtotal').innerText.replace('$', '')),
        iva: parseFloat(document.getElementById('iva_display').innerText.replace('$', '')),
        total: parseFloat(document.getElementById('total_final').innerText.replace('$', '')),
        items: carrito 
    };

    fetch('api/procesar_venta.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datosVenta)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'ok') {
            alert("¡Venta completada!");
            window.open(`ticket.php?id=${data.id_venta}`, 'Ticket', 'width=400,height=600');
            window.location.reload();
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(err => alert("Error en el servidor"));
}

function abrirModal() { document.getElementById('modalCliente').style.display = 'flex'; }
function cerrarModal() { 
    // Limpia los textos escritos en los inputs
    document.getElementById('formNuevoCliente').reset(); 
    
    // Oculta el modal
    document.getElementById('modalCliente').style.display = 'none'; 
}

function guardarClienteRapido() {
    // 1. Recogemos los datos del modal
    const datos = {
        identidad: document.getElementById('m_identidad').value.trim(),
        nombre: document.getElementById('m_nombre').value.trim(),
        telefono: document.getElementById('m_telefono').value.trim()
    };

    // 2. Validación básica
    if(datos.identidad === "" || datos.nombre === "") {
        alert("Por favor, completa el DNI y el Nombre.");
        return;
    }

    // 3. Envío al servidor vía AJAX
    fetch('api/registrar_cliente_ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
    })
    .then(res => res.json())
    .then(res => {
        if(res.success) {
            // Si se guardó, lo seleccionamos automáticamente en la venta
            document.getElementById('id_cliente_seleccionado').value = res.id;
            
            const infoBox = document.getElementById('info_cliente');
            infoBox.innerHTML = `
                <span>✅ Cliente: <strong style="color:white;">${datos.nombre}</strong></span>
                <button onclick="desvincularCliente()" class="btn-mini" style="background:#475569; margin-left:10px;">Cambiar</button>
            `;
            infoBox.style.borderColor = "#10b981";
            
            // Cerramos y limpiamos
            cerrarModal();
            alert("Cliente registrado y seleccionado para esta venta.");
        } else {
            alert("Error: " + res.mensaje);
        }
    })
    .catch(err => {
        console.error("Error en registro:", err);
        alert("Hubo un problema al conectar con el servidor.");
    });
}
</script>