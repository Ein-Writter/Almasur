<?php 
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'config/db.php'; // Asegúrate de incluir la conexión

// Obtenemos la tasa de la tabla configuración
$res_config = $conn->query("SELECT tasa_dolar FROM configuracion WHERE id = 1");
$datos_config = $res_config->fetch_assoc();
$tasa_actual = $datos_config['tasa_dolar'] ?? 1;
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
	
<div id="modal-pago" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 1100; justify-content: center; align-items: center;">
    <div class="modal-content" style="background: #1e293b; padding: 25px; border-radius: 12px; width: 450px; border: 1px solid #334155;">
        <h2 style="color: #10b981; margin:0 0 15px 0; text-align: center;">Finalizar Venta</h2>
        
        <div style="background: #0f172a; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <div style="display:flex; justify-content: space-between;">
                <span>Total a Pagar:</span>
                <strong id="monto-total-usd" style="color: #fff;">$0.00</strong>
            </div>
            <div style="display:flex; justify-content: space-between; color: #38bdf8;">
                <span>Equivalente:</span>
                <strong id="monto-total-bs">Bs. 0.00</strong>
            </div>
            <hr style="border: 0.5px solid #334155; margin: 10px 0;">
            <div style="display:flex; justify-content: space-between; font-weight: bold;">
                <span>Restante por cobrar:</span>
                <span id="monto-restante" style="color: #ef4444;">$0.00</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label style="font-size: 0.8rem; color: #94a3b8;">Efectivo $</label>
                <input type="number" id="pago_usd" class="input-dark pago-input" ...>
            </div>
            <div class="form-group">
                <label style="font-size: 0.8rem; color: #94a3b8;">Efectivo Bs</label>
                <input type="number" id="pago_bs" class="input-dark pago-input" ...>
            </div>
            <div class="form-group">
                <label style="font-size: 0.8rem; color: #94a3b8;">Pago Móvil / Transf. (Bs)</label>
                <input type="number" id="pago_digital" class="input-dark pago-input" ...>
            </div>
            <div class="form-group">
                <label style="font-size: 0.8rem; color: #94a3b8;">Vuelto ($)</label>
                <input type="text" id="vuelto_final" class="input-dark" readonly style="width: 100%; border-color: #38bdf8; color: #38bdf8; font-weight: bold;" value="0.00">
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px;">
            <button type="button" onclick="cerrarModalPago()" class="btn-rojo">Cancelar</button>
            <button type="button" id="btn-confirmar-pago" onclick="confirmarVentaFinal()" class="btn-azul" style="background: #10b981; opacity: 0.5;" disabled>
                Confirmar Venta
            </button>
        </div>
    </div>
</div></main>

<script>
let carrito = [];
const tasaDia = <?php echo $tasa_actual; ?>;
let ventaTemporal = null; 

// --- 1. BUSCAR PRODUCTOS ---
document.getElementById('buscar_producto').addEventListener('input', function(e) {
    let query = e.target.value.trim().toLowerCase();
    if(query.length > 1) {
        fetch(`api/buscar_productos.php?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                const productoExacto = data.find(p => String(p.codigo).trim().toLowerCase() === query);
                if (productoExacto) {
                    agregarAlCarrito(productoExacto.id, productoExacto.nombre, productoExacto.precio, productoExacto.stock);
                    e.target.value = ""; 
                    document.getElementById('resultados_busqueda').innerHTML = "";
                } else {
                    renderResultados(data);
                }
            }).catch(err => console.error("Error:", err));
    } else {
        document.getElementById('resultados_busqueda').innerHTML = "";
    }
});

function renderResultados(productos) {
    const contenedor = document.getElementById('resultados_busqueda');
    if (productos.length === 0) {
        contenedor.innerHTML = '<p style="padding:10px; color:gray;">No encontrado.</p>';
        return;
    }
    contenedor.innerHTML = productos.map(p => `
        <div class="item-busqueda" onclick="agregarAlCarrito(${p.id}, '${p.nombre}', ${p.precio}, ${p.stock})" 
             style="display: flex; justify-content: space-between; padding: 12px; border-bottom: 1px solid #334155; cursor: pointer;">
            <div><strong>${p.nombre}</strong><br><small>Stock: ${p.stock}</small></div>
            <div><span style="color: #10b981; font-weight: bold;">$${p.precio}</span></div>
        </div>
    `).join('');
}

// --- 2. GESTIÓN DEL CARRITO ---
function agregarAlCarrito(id, nombre, precio, stock) {
    if(stock <= 0) return alert("Producto agotado");
    let item = carrito.find(p => p.id === id);
    if(item) {
        if(item.cantidad < stock) item.cantidad++;
        else alert("Sin stock");
    } else {
        carrito.push({id, nombre, precio, cantidad: 1, stock_max: stock});
    }
    actualizarVistaCarrito();
}

function actualizarVistaCarrito() {
    const contenedor = document.getElementById('carrito_lista');
    let subtotal = 0;
    if (carrito.length === 0) {
        contenedor.innerHTML = '<p style="text-align:center; color:#888;">Vacío</p>';
        actualizarTotales(0);
        return;
    }
    contenedor.innerHTML = carrito.map((item, index) => {
        subtotal += item.precio * item.cantidad;
        return `<div style="display:flex; justify-content:space-between; padding: 10px 0; border-bottom: 1px solid #334155;">
                    <div>${item.nombre}<br><small>${item.cantidad} x $${parseFloat(item.precio).toFixed(2)}</small></div>
                    <button onclick="eliminarDelCarrito(${index})" class="btn-rojo" style="padding:2px 5px;"><i class="fa-solid fa-trash"></i></button>
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

function eliminarDelCarrito(index) {
    if (carrito[index].cantidad > 1) carrito[index].cantidad--;
    else carrito.splice(index, 1);
    actualizarVistaCarrito();
}

// --- 3. GESTIÓN DEL MODAL DE PAGO ---
function finalizarVenta() {
    if (carrito.length === 0) return alert("Añade productos primero");

    const total = parseFloat(document.getElementById('total_final').innerText.replace('$', ''));
    
    ventaTemporal = {
        id_cliente: document.getElementById('id_cliente_seleccionado').value || 1,
        subtotal: parseFloat(document.getElementById('subtotal').innerText.replace('$', '')),
        iva: parseFloat(document.getElementById('iva_display').innerText.replace('$', '')),
        total: total,
        items: carrito 
    };

    document.getElementById('pago_usd').value = "0.00";
    document.getElementById('pago_bs').value = "0.00";
    document.getElementById('pago_digital').value = "0.00";
    
    document.getElementById('monto-total-usd').innerText = `$${total.toFixed(2)}`;
    document.getElementById('monto-total-bs').innerText = `Bs. ${(total * tasaDia).toFixed(2)}`;
    
    calcularPagos();
    document.getElementById('modal-pago').style.display = 'flex';
}

function cerrarModalPago() {
    document.getElementById('modal-pago').style.display = 'none';
}

// Delegación de eventos para los inputs de pago
document.addEventListener('input', function(e) {
    if(e.target.classList.contains('pago-input')) {
        calcularPagos();
    }
});

function calcularPagos() {
    if(!ventaTemporal) return;
    const totalUSD = ventaTemporal.total; 
    
    const pUSD     = parseFloat(document.getElementById('pago_usd').value) || 0;
    const pBsEf    = parseFloat(document.getElementById('pago_bs').value) || 0;
    const pBsDig   = parseFloat(document.getElementById('pago_digital').value) || 0;
    
    // Sumamos todo convirtiendo Bs a USD
    const totalPagadoEnUSD = pUSD + (pBsEf / tasaDia) + (pBsDig / tasaDia);
    const restante = totalUSD - totalPagadoEnUSD;
    
    const btn = document.getElementById('btn-confirmar-pago');
    const displayRestante = document.getElementById('monto-restante');
    const displayVuelto = document.getElementById('vuelto_final');

    if (restante <= 0.009) { // Margen pequeño para errores de redondeo
        displayRestante.innerText = "$0.00";
        displayRestante.style.color = "#10b981";
        displayVuelto.value = Math.abs(restante).toFixed(2);
        btn.disabled = false;
        btn.style.opacity = "1";
    } else {
        displayRestante.innerText = `$${restante.toFixed(2)}`;
        displayRestante.style.color = "#ef4444";
        displayVuelto.value = "0.00";
        btn.disabled = true;
        btn.style.opacity = "0.5";
    }
}

function confirmarVentaFinal() {
    const pagos = {
        efectivo_usd: parseFloat(document.getElementById('pago_usd').value) || 0,
        efectivo_bs:  parseFloat(document.getElementById('pago_bs').value) || 0,
        digital_bs:   parseFloat(document.getElementById('pago_digital').value) || 0,
        vuelto_usd:   parseFloat(document.getElementById('vuelto_final').value) || 0,
        tasa_usada:   tasaDia
    };

    const datosFinales = { 
        ...ventaTemporal, 
        metodos_pago: pagos 
    };

    fetch('api/procesar_venta.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datosFinales)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'ok') {
            alert("¡Venta procesada con éxito!");
            window.open(`ticket.php?id=${data.id_venta}`, 'Ticket', 'width=400,height=600');
            window.location.reload();
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(err => alert("Error de red: " + err));
}
</script>