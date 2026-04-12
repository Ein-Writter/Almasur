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
            <div class="search-container">
    <input type="text" id="buscar_producto" placeholder="Escribe el nombre o código del producto..." autocomplete="off">
</div>
            
            <div id="resultados_busqueda" class="contenedor-tabla" style="max-height: 400px; overflow-y: auto;">
                </div>
        </div>

        <div class="card-moderna">
            <h3>Detalle de Venta</h3>
            <hr>
            <div id="carrito_lista" style="min-height: 200px;">
                <p style="text-align:center; color:#888;">El carrito está vacío</p>
            </div>
            <hr>
            <div class="totales">
    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
        <span>Subtotal:</span> <span id="subtotal">$0.00</span>
    </div>
    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
        <span>IVA (16%):</span> <span id="iva_display">$0.00</span>
    </div>
    <hr>
    <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.3rem; color: #10b981;">
        <span>TOTAL:</span> <span id="total_final">$0.00</span>
    </div>
</div>
            <br>
            <button class="btn-accion edit" style="width: 100%; padding: 15px;" onclick="finalizarVenta()">
                <i class="fa-solid fa-check-to-slot"></i> FINALIZAR VENTA
            </button>
        </div>
    </div>

<div class="card-moderna" style="margin-top: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h3 style="margin:0;"><i class="fa-solid fa-user-tag"></i> Datos del Cliente</h3>
        <button type="button" onclick="abrirModal()" class="btn-nuevo-cliente">
            <i class="fa-solid fa-plus"></i> Nuevo
        </button>
    </div>
    
    <div class="search-box-cliente">
        <input type="text" id="dni_busqueda" class="input-dark" placeholder="DNI o Identidad del cliente...">
        <button type="button" onclick="buscarCliente()" class="btn-buscar">
            <i class="fa-solid fa-magnifying-glass"></i> Buscar
        </button>
    </div>
    
    <div id="info_cliente" class="cliente-status-bar">
        <i class="fa-solid fa-circle-info"></i> Cliente: <span>Público General</span>
    </div>
    
    <input type="hidden" name="id_cliente" id="id_cliente_seleccionado" value="">
</div>

<div id="modalCliente" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0; color: #38bdf8;"><i class="fa-solid fa-user-plus"></i> Registrar Nuevo Cliente</h2>
            <button onclick="cerrarModal()" style="background:none; border:none; color:gray; cursor:pointer; font-size:1.5rem;">&times;</button>
        </div>
        
        <form id="formNuevoCliente">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display:block; margin-bottom:8px;">Identidad (Cédula/DNI):</label>
                    <input type="text" id="m_identidad" class="input-dark" placeholder="Ej: 12345678" required>
                </div>
                <div>
                    <label style="display:block; margin-bottom:8px;">Teléfono:</label>
                    <input type="text" id="m_telefono" class="input-dark" placeholder="Ej: 0412-0000000">
                </div>
            </div>

            <label style="display:block; margin-bottom:8px;">Nombre Completo:</label>
            <input type="text" id="m_nombre" class="input-dark" placeholder="Nombre y Apellido" required>
            
            <div style="display: flex; justify-content: flex-end; gap: 15px; margin-top: 10px;">
                <button type="button" onclick="cerrarModal()" class="btn-rojo" style="padding: 12px 25px;">Cancelar</button>
                <button type="button" onclick="guardarClienteRapido()" class="btn-azul" style="padding: 12px 25px;">
                    <i class="fa-solid fa-save"></i> Guardar Cliente
                </button>
            </div>
        </form>
    </div>
</div>
</main>
<script>
let carrito = [];

document.getElementById('buscar_producto').addEventListener('input', function(e) {
    let query = e.target.value;
    if(query.length > 2) {
        fetch(`api/buscar_productos.php?q=${query}`)
            .then(res => res.json())
            .then(data => {
                renderResultados(data);
            });
    }
});

function agregarAlCarrito(id, nombre, precio, stock) {
    if(stock <= 0) return alert("Producto sin stock");
    
    let item = carrito.find(p => p.id === id);
    if(item) {
        if(item.cantidad < stock) item.cantidad++;
        else alert("Límite de stock alcanzado");
    } else {
        carrito.push({id, nombre, precio, cantidad: 1});
    }
    actualizarVistaCarrito();
}

function actualizarVistaCarrito() {
    const contenedor = document.getElementById('carrito_lista');
    let subtotal = 0;
    const tasaIVA = 0.16; 

    if (carrito.length === 0) {
        contenedor.innerHTML = '<p style="text-align:center; color:#888;">El carrito está vacío</p>';
        document.getElementById('subtotal').innerText = `$0.00`;
        document.getElementById('iva_display').innerText = `$0.00`;
        document.getElementById('total_final').innerText = `$0.00`;
        return;
    }

    contenedor.innerHTML = carrito.map((item, index) => {
        const precioLinea = item.precio * item.cantidad;
        subtotal += precioLinea;
        
        return `
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; padding: 5px; border-bottom: 1px solid #f1f5f9;">
                <div style="flex-grow: 1;">
                    <span style="font-weight:bold;">${item.nombre}</span> <br>
                    <small>${item.cantidad} x $${item.precio.toFixed(2)}</small>
                </div>
                <div style="text-align: right; display: flex; align-items: center; gap: 10px;">
                    <span style="font-weight:bold;">$${precioLinea.toFixed(2)}</span>
                    <button onclick="eliminarDelCarrito(${index})" style="background: #ef4444; color: white; border: none; border-radius: 4px; padding: 2px 8px; cursor: pointer;">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </div>`;
    }).join('');

    const montoIVA = subtotal * tasaIVA;
    const totalFinal = subtotal + montoIVA;

    document.getElementById('subtotal').innerText = `$${subtotal.toFixed(2)}`;
    document.getElementById('iva_display').innerText = `$${montoIVA.toFixed(2)}`;
    document.getElementById('total_final').innerText = `$${totalFinal.toFixed(2)}`;
}

function eliminarDelCarrito(index) {
    carrito.splice(index, 1);
    actualizarVistaCarrito();
}

function finalizarVenta() {
    if (carrito.length === 0) return alert("El carrito está vacío");

    const subtotal = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
    const tasaIVA = 0.16;
    const montoIVA = subtotal * tasaIVA;
    const totalFinal = subtotal + montoIVA;

    const datosVenta = {
        id_cliente: document.getElementById('id_cliente_seleccionado').value, 
        subtotal: subtotal,
        iva: montoIVA,
        total: totalFinal,
        items: carrito 
    };

    fetch('api/procesar_venta.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datosVenta)
    })
    .then(res => res.text()) 
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.status === 'ok') {
                alert("¡Venta realizada con éxito!");
                
                const urlTicket = `ticket.php?id=${data.id_venta}`;
                window.open(urlTicket, 'Ticket', 'width=400,height=600');
                window.location.reload(); 
            } else {
                alert("Error: " + data.message);
            }
        } catch (e) {
            console.error("Respuesta sucia del servidor:", text);
            alert("Error en el servidor. Revisa la consola.");
        }
    });
}

fetch('api/procesar_venta.php', {
    method: 'POST',
    body: JSON.stringify(datosVenta) 
})

    fetch('api/procesar_venta.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datosVenta)
    })
    .then(res => res.text())
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.status === 'ok') {
                alert("¡Venta realizada con éxito!");
                
                const urlTicket = `ticket.php?id=${data.id_venta}`;
                window.open(urlTicket, 'Ticket', 'width=400,height=600');
                
                window.location.reload(); 
            } else {
                alert("Error: " + data.message);
            }
        } catch (e) {
            console.error("Respuesta no válida del servidor:", text);
            alert("Error del servidor. Revisa la consola (F12).");
        }
    });

function renderResultados(productos) {
    const contenedor = document.getElementById('resultados_busqueda');
    
    if (productos.length === 0) {
        contenedor.innerHTML = '<p style="padding:10px; color:gray;">No se encontraron productos con stock.</p>';
        return;
    }

    contenedor.innerHTML = productos.map(p => `
        <div class="item-busqueda" onclick="agregarAlCarrito(${p.id}, '${p.nombre}', ${p.precio}, ${p.stock})">
            <div>
                <strong>${p.nombre}</strong> <br>
                <small style="color: #64748b;">Stock disponible: ${p.stock}</small>
            </div>
            <div style="text-align: right;">
                <span style="font-weight: bold; color: #10b981;">$${p.precio}</span> <br>
                <button class="btn-mini"> + Añadir</button>
            </div>
        </div>
    `).join('');
}

function buscarCliente() {
    let dni = document.getElementById('dni_busqueda').value;
    if (dni == "") return;

    fetch('api/buscar_cliente.php?dni=' + dni)
        .then(response => response.json())
        .then(data => {
            const infoBox = document.getElementById('info_cliente');
            if (data.existe) {
                infoBox.innerHTML = `<i class="fa-solid fa-check-circle"></i> Cliente: <span>${data.nombre}</span>`;
                infoBox.style.borderColor = "#10b981"; 
                infoBox.style.color = "#10b981";
                document.getElementById('id_cliente_seleccionado').value = data.id;
            } else {
                infoBox.innerHTML = `<i class="fa-solid fa-circle-xmark"></i> No encontrado. <a href="#" onclick="abrirModal()" style="color:white; text-decoration:underline;">¿Crear nuevo?</a>`;
                infoBox.style.borderColor = "#ef4444"; 
                infoBox.style.color = "#ef4444";
                document.getElementById('id_cliente_seleccionado').value = "";
            }
        });
}

function abrirModal() { 
    const modal = document.getElementById('modalCliente');
    modal.style.display = 'flex'; 
}

function cerrarModal() { 
    const modal = document.getElementById('modalCliente');
    modal.style.display = 'none'; 
}

function guardarClienteRapido() {
    const datos = {
        identidad: document.getElementById('m_identidad').value,
        nombre: document.getElementById('m_nombre').value,
        telefono: document.getElementById('m_telefono').value
    };

    if(datos.identidad == "" || datos.nombre == "") {
        alert("Por favor llena los campos obligatorios");
        return;
    }

    fetch('api/registrar_cliente_ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
    })
    .then(res => res.json())
    .then(res => {
        if(res.success) {
            document.getElementById('id_cliente_seleccionado').value = res.id;
            document.getElementById('info_cliente').innerHTML = "✅ Cliente: " + datos.nombre;
            document.getElementById('dni_busqueda').value = datos.identidad;
            cerrarModal();
            alert("Cliente registrado y seleccionado");
        } else {
            alert("Error: " + res.mensaje);
        }
    });
}
</script>