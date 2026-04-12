let carrito = [];
let productoSeleccionado = null;

const buscador = document.getElementById('buscar_producto');
const sugerencias = document.getElementById('lista_sugerencias');

buscador.addEventListener('input', function() {
    let q = this.value;
    if (q.length < 2) {
        sugerencias.style.display = 'none';
        return;
    }

    fetch('api/buscar_productos.php?q=' + q)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                let html = "";
                data.forEach(p => {
                    html += `
                    <div class="sugerencia-item" 
                         style="padding: 10px; cursor: pointer; border-bottom: 1px solid rgba(255,255,255,0.1);"
                         onclick="seleccionarProducto('${p.id}', '${p.nombre}', ${p.precio}, ${p.stock})">
                        <strong>${p.nombre}</strong> - $${p.precio} <span style="float:right; font-size: 0.8em; color: #4ade80;">Stock: ${p.stock}</span>
                    </div>`;
                });
                sugerencias.innerHTML = html;
                sugerencias.style.display = 'block';
            } else {
                sugerencias.style.display = 'none';
            }
        });
});

function seleccionarProducto(id, nombre, precio, stock) {
    productoSeleccionado = { id, nombre, precio, stock };
    buscador.value = nombre; 
    sugerencias.style.display = 'none'; 
    document.getElementById('cant_producto').focus();
}

document.addEventListener('mouseover', function(e) {
    if (e.target.classList.contains('sugerencia-item')) {
        e.target.style.background = 'rgba(76, 201, 240, 0.2)';
    }
});
document.addEventListener('mouseout', function(e) {
    if (e.target.classList.contains('sugerencia-item')) {
        e.target.style.background = 'transparent';
    }
});

function agregarAlCarrito() {
    let cant = parseInt(document.getElementById('cant_producto').value);

    if(productoSeleccionado) {
        if(cant > productoSeleccionado.stock) {
            alert("No hay suficiente stock. Disponible: " + productoSeleccionado.stock);
            return;
        }

        carrito.push({
            id: productoSeleccionado.id,
            nombre: productoSeleccionado.nombre,
            precio: parseFloat(productoSeleccionado.precio),
            cantidad: cant
        });

        document.getElementById('buscar_producto').value = "";
        productoSeleccionado = null;
        renderizarCarrito(); 
    } else {
        alert("Primero busca y selecciona un producto");
    }
}
function actualizarCifras(sumaBase) {
    const tasaIVA = 0.16;
    let montoIVA = sumaBase * tasaIVA;
    let granTotal = sumaBase + montoIVA;

    document.getElementById('txt_subtotal').innerText = `$${sumaBase.toFixed(2)}`;
    document.getElementById('txt_iva').innerText = `$${montoIVA.toFixed(2)}`;
    document.getElementById('txt_total').innerText = `$${granTotal.toFixed(2)}`;

    document.getElementById('input_subtotal').value = sumaBase.toFixed(2);
    document.getElementById('input_impuesto').value = montoIVA.toFixed(2);
    document.getElementById('input_total').value = granTotal.toFixed(2);
    document.getElementById('carrito_datos').value = JSON.stringify(carrito);
}

function enviarVenta() {
    if (carrito.length === 0) {
        alert("El carrito está vacío");
        return;
    }
    document.getElementById('form_venta').submit();
}