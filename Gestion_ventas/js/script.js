let carrito = [];

function agregarAlCarrito() {
    const select = document.getElementById('select_producto');
    const cantInput = document.getElementById('cant_producto');
    
    if (!select.value) return alert("Selecciona un producto");

    const itemOption = select.options[select.selectedIndex];
    const stockDisponible = parseInt(itemOption.getAttribute('data-stock'));
    const cantidadSolicitada = parseInt(cantInput.value);

    const existente = carrito.find(p => p.id === select.value);
    const cantidadEnCarrito = existente ? existente.cantidad : 0;

    if ((cantidadEnCarrito + cantidadSolicitada) > stockDisponible) {
        alert("¡No hay suficiente stock! Disponible: " + stockDisponible);
        return;
    }

    if (existente) {
        existente.cantidad += cantidadSolicitada;
    } else {
        carrito.push({
            id: select.value,
            nombre: itemOption.text,
            precio: parseFloat(itemOption.getAttribute('data-precio')),
            cantidad: cantidadSolicitada
        });
    }
    renderizarCarrito();
}

function renderizarCarrito() {
    const cuerpo = document.getElementById('cuerpo_carrito');
    cuerpo.innerHTML = ""; 
    let sumaPreciosBase = 0;

    carrito.forEach((prod, index) => {
        let subtotalFila = prod.precio * prod.cantidad;
        sumaPreciosBase += subtotalFila;

        cuerpo.innerHTML += `
            <tr>
                <td>${prod.nombre}</td>
                <td>${prod.cantidad}</td>
                <td>$${subtotalFila.toFixed(2)}</td>
                <td><button onclick="eliminar(${index})">Eliminar</button></td>
            </tr>`;
    });

    hacerCalculosIVA(sumaPreciosBase);
}

function hacerCalculosIVA(subtotal) {
    const tasa = 0.16;
    let impuesto = subtotal * tasa;
    let totalFinal = subtotal + impuesto;

    document.getElementById('subtotal_venta').innerText = `$${subtotal.toFixed(2)}`;
    document.getElementById('iva_venta').innerText = `$${impuesto.toFixed(2)}`;
    document.getElementById('total_venta').innerText = `$${totalFinal.toFixed(2)}`;
}

function actualizarTotalesFinales(subtotal) {
    const tasaIVA = 0.16;
    let montoIVA = subtotal * tasaIVA;
    let totalConIVA = subtotal + montoIVA;

    document.getElementById('subtotal_venta').innerText = `$${subtotal.toFixed(2)}`;
    document.getElementById('iva_venta').innerText = `$${montoIVA.toFixed(2)}`;
    document.getElementById('total_venta').innerText = `$${totalConIVA.toFixed(2)}`;

    if(document.getElementById('hidden_subtotal')){
        document.getElementById('hidden_subtotal').value = subtotal.toFixed(2);
        document.getElementById('hidden_iva').value = montoIVA.toFixed(2);
        document.getElementById('hidden_total').value = totalConIVA.toFixed(2);
    }
}

function eliminar(index) {
    carrito.splice(index, 1);
    renderizarCarrito();
}

async function finalizarVenta() {
    if (carrito.length === 0) return alert("El carrito está vacío");

    const subtotalVenta = document.getElementById('subtotal_venta').innerText.replace('$', '');
    const ivaVenta = document.getElementById('iva_venta').innerText.replace('$', '');
    const totalVenta = document.getElementById('total_venta').innerText.replace('$', '');

    const datosVenta = {
        carrito: carrito,
        subtotal: subtotalVenta,
        impuesto: ivaVenta,
        total: totalVenta
    };

    try {
        const response = await fetch('api/procesar_venta_multiple.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datosVenta)
        });

        if (response.ok) {
            alert("Venta procesada con éxito");
            location.reload();
        } else {
            alert("Hubo un error al procesar la venta");
        }
    } catch (e) {
        alert("Error de conexión con el servidor");
    }
}

function filtrarProductos() {
    const texto = document.getElementById('buscar_txt').value.toLowerCase();
    const select = document.getElementById('select_producto');
    const opciones = select.options;

    for (let i = 0; i < opciones.length; i++) {
        const nombreProducto = opciones[i].text.toLowerCase();
        opciones[i].style.display = nombreProducto.includes(texto) ? "" : "none";
    }
}