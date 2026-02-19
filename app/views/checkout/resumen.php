<?php require_once '../app/views/layout/header.php'; ?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="text-center">Resumen de Compra</h1>
        <p class="text-center text-muted">Revisá que todos los datos sean correctos</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <!-- Datos de envío -->
        <div class="card mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Datos de envío</h5>
                <a href="/vrossoc/public/checkout/index" class="btn btn-sm btn-outline-secondary">Editar</a>
            </div>
            <div class="card-body">
                <p><strong>Nombre:</strong> <span id="envio-nombre"><?php echo $data['checkout']['nombre']; ?></span></p>
                <p><strong>Email:</strong> <span id="envio-email"><?php echo $data['checkout']['email']; ?></span></p>
                <p><strong>Teléfono:</strong> <span id="envio-telefono"><?php echo $data['checkout']['telefono']; ?></span></p>
                <p><strong>Dirección:</strong> <span id="envio-direccion"><?php echo $data['checkout']['direccion']; ?></span></p>
                <p><strong>Ciudad:</strong> <span id="envio-ciudad"><?php echo $data['checkout']['ciudad']; ?></span>, <span id="envio-provincia"><?php echo $data['checkout']['provincia']; ?></span> (CP: <span id="envio-cp"><?php echo $data['checkout']['codigo_postal']; ?></span>)</p>
                <?php if (!empty($data['checkout']['instrucciones'])): ?>
                    <p><strong>Instrucciones:</strong> <span id="envio-instrucciones"><?php echo $data['checkout']['instrucciones']; ?></span></p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Productos (se cargarán con JS) -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Productos</h5>
            </div>
            <div class="card-body" id="productos-container">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Total a pagar</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span class="fw-bold" id="resumen-subtotal">$0</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Envío:</span>
                    <span class="fw-bold">$0</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <span class="h5">Total:</span>
                    <span class="h5 text-primary" id="resumen-total">$0</span>
                </div>
                
                <button type="button" class="btn btn-primary w-100 mb-2" id="btnConfirmar">
                    Confirmar compra
                </button>
                <p class="text-muted small text-center mb-0">
                    Al confirmar, simularás una compra exitosa
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Cargar carrito desde localStorage
const carrito = JSON.parse(localStorage.getItem('vrossoc_carrito')) || [];

// Verificar que haya productos
if (carrito.length === 0) {
    window.location.href = '/vrossoc/public/carrito/index';
}

// Renderizar productos
function renderizarProductos() {
    const container = document.getElementById('productos-container');
    let html = '';
    let total = 0;
    
    carrito.forEach((item, index) => {
        const subtotal = item.precio * item.cantidad;
        total += subtotal;
        
        html += `
            <div class="row mb-3 align-items-center">
                <div class="col-2">
                    <img src="${item.imagen || 'https://placehold.co/100x100'}" class="img-fluid rounded" alt="${item.nombre}">
                </div>
                <div class="col-6">
                    <h6>${item.nombre}</h6>
                    <p class="text-muted small mb-0">
                        Color: ${item.color} | Talle: ${item.talle}
                    </p>
                    <p class="text-muted small mb-0">
                        Cantidad: ${item.cantidad}
                    </p>
                </div>
                <div class="col-4 text-end">
                    <span class="fw-bold">$${subtotal.toLocaleString('es-AR')}</span>
                </div>
            </div>
            ${index < carrito.length - 1 ? '<hr>' : ''}
        `;
    });
    
    container.innerHTML = html;
    
    // Actualizar totales
    document.getElementById('resumen-subtotal').textContent = '$' + total.toLocaleString('es-AR');
    document.getElementById('resumen-total').textContent = '$' + total.toLocaleString('es-AR');
    
    return total;
}

// Renderizar al cargar
const totalCompra = renderizarProductos();

// Evento para confirmar compra
document.getElementById('btnConfirmar').addEventListener('click', async function() {
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Procesando...';
    
    try {
        // Enviar carrito y datos de envío al backend
        const response = await fetch('/vrossoc/public/checkout/apiConfirmar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                carrito: carrito
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Limpiar carrito de localStorage
            localStorage.removeItem('vrossoc_carrito');
            
            // Redirigir a página de éxito
            window.location.href = '/vrossoc/public/checkout/exito/' + data.numero_pedido;
        } else {
            alert('Error al procesar la compra: ' + (data.error || 'Error desconocido'));
            btn.disabled = false;
            btn.innerHTML = 'Confirmar compra';
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al procesar la compra');
        btn.disabled = false;
        btn.innerHTML = 'Confirmar compra';
    }
});
</script>

<?php require_once '../app/views/layout/footer.php'; ?>