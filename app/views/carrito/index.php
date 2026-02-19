<?php require_once '../app/views/layout/header.php'; ?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="text-center"><?php echo $data['titulo']; ?></h1>
    </div>
</div>

<div class="row">
    <!-- Columna del carrito -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Productos <span class="badge bg-primary" id="contador-items">0</span></h5>
            </div>
            <div class="card-body" id="carrito-items">
                <!-- Los items se cargarán vía JavaScript -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Columna del resumen -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Resumen de compra</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span class="fw-bold" id="subtotal">$0</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Envío:</span>
                    <span class="fw-bold" id="envio">$0</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <span class="h5">Total:</span>
                    <span class="h5 text-primary" id="total">$0</span>
                </div>
                
                <a href="/vrossoc/public/checkout/index" class="btn btn-primary w-100 mb-2" id="btnCheckout">
                    Finalizar compra
                </a>
                <a href="/vrossoc/public/producto/index" class="btn btn-outline-secondary w-100">
                    Seguir comprando
                </a>
            </div>
        </div>
        
        <!-- Medios de pago -->
        <div class="card mt-3">
            <div class="card-body">
                <h6>Aceptamos:</h6>
                <div class="d-flex gap-2">
                    <img src="https://placehold.co/50x30/000000/ffffff?text=MP" alt="MercadoPago" class="img-fluid">
                    <img src="https://placehold.co/50x30/1A1F71/ffffff?text=Visa" alt="Visa" class="img-fluid">
                    <img src="https://placehold.co/50x30/EB001B/ffffff?text=MC" alt="Mastercard" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template para item del carrito -->
<template id="template-carrito-item">
    <div class="carrito-item mb-3 pb-3 border-bottom">
        <div class="row">
            <div class="col-3">
                <img src="" class="img-fluid rounded" alt="Producto" style="width: 100px; height: 100px; object-fit: cover;">
            </div>
            <div class="col-9">
                <div class="row">
                    <div class="col-md-7">
                        <h6 class="producto-nombre"></h6>
                        <p class="text-muted small mb-1">
                            Color: <span class="producto-color"></span> | 
                            Talle: <span class="producto-talle"></span>
                        </p>
                        <p class="text-muted small mb-2 stock-info"></p>
                    </div>
                    <div class="col-md-5">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="input-group input-group-sm" style="width: 120px;">
                                <button class="btn btn-outline-secondary btn-cantidad-menos" type="button">-</button>
                                <input type="number" class="form-control text-center input-cantidad" value="1" min="1" max="99">
                                <button class="btn btn-outline-secondary btn-cantidad-mas" type="button">+</button>
                            </div>
                            <span class="fw-bold producto-precio ms-2"></span>
                            <button class="btn btn-link text-danger btn-eliminar" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// Función para cargar y mostrar el carrito desde localStorage
function cargarCarrito() {
    const carrito = JSON.parse(localStorage.getItem('vrossoc_carrito')) || [];
    const contenedor = document.getElementById('carrito-items');
    const contadorItems = document.getElementById('contador-items');
    const subtotalSpan = document.getElementById('subtotal');
    const totalSpan = document.getElementById('total');
    const btnCheckout = document.getElementById('btnCheckout');
    
    console.log('Cargando carrito desde localStorage:', carrito);
    
    if (carrito.length === 0) {
        contenedor.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-cart-x" style="font-size: 4rem;"></i>
                <h4 class="mt-3">Tu carrito está vacío</h4>
                <p class="text-muted">Explora nuestros productos y encuentra lo que buscas</p>
                <a href="/vrossoc/public/producto/index" class="btn btn-primary">Ver productos</a>
            </div>
        `;
        subtotalSpan.textContent = '$0';
        totalSpan.textContent = '$0';
        contadorItems.textContent = '0';
        btnCheckout.classList.add('disabled');
        return;
    }
    
    // Renderizar items
    contenedor.innerHTML = '';
    const template = document.getElementById('template-carrito-item');
    let total = 0;
    let totalItems = 0;
    
    carrito.forEach((item, index) => {
        const subtotal = item.precio * item.cantidad;
        total += subtotal;
        totalItems += item.cantidad;
        
        const clone = template.content.cloneNode(true);
        const itemDiv = clone.querySelector('.carrito-item');
        
        // Setear datos
        clone.querySelector('img').src = item.imagen || 'https://placehold.co/100x100';
        clone.querySelector('.producto-nombre').textContent = item.nombre;
        clone.querySelector('.producto-color').textContent = item.color;
        clone.querySelector('.producto-talle').textContent = item.talle;
        clone.querySelector('.producto-precio').textContent = '$' + subtotal.toLocaleString('es-AR');
        clone.querySelector('.input-cantidad').value = item.cantidad;
        
        // Stock info
        const stockInfo = clone.querySelector('.stock-info');
        if (item.stock < 5) {
            stockInfo.innerHTML = `<span class="text-warning">¡Últimas ${item.stock} unidades!</span>`;
        } else {
            stockInfo.innerHTML = `<span class="text-success">Stock disponible: ${item.stock}</span>`;
        }
        
        // Event listeners
        const inputCantidad = clone.querySelector('.input-cantidad');
        const btnMenos = clone.querySelector('.btn-cantidad-menos');
        const btnMas = clone.querySelector('.btn-cantidad-mas');
        const btnEliminar = clone.querySelector('.btn-eliminar');
        
        inputCantidad.addEventListener('change', (e) => {
            let nuevaCant = parseInt(e.target.value) || 1;
            nuevaCant = Math.min(nuevaCant, item.stock);
            nuevaCant = Math.max(nuevaCant, 1);
            
            // Actualizar en el array
            carrito[index].cantidad = nuevaCant;
            localStorage.setItem('vrossoc_carrito', JSON.stringify(carrito));
            
            // Recargar la vista
            cargarCarrito();
            
            // Actualizar contador del navbar
            if (typeof window.actualizarContadorCarrito === 'function') {
                window.actualizarContadorCarrito();
            }
        });
        
        btnMenos.addEventListener('click', () => {
            const nuevaCant = carrito[index].cantidad - 1;
            if (nuevaCant >= 1) {
                carrito[index].cantidad = nuevaCant;
                localStorage.setItem('vrossoc_carrito', JSON.stringify(carrito));
                cargarCarrito();
                if (typeof window.actualizarContadorCarrito === 'function') {
                    window.actualizarContadorCarrito();
                }
            }
        });
        
        btnMas.addEventListener('click', () => {
            const nuevaCant = carrito[index].cantidad + 1;
            if (nuevaCant <= item.stock) {
                carrito[index].cantidad = nuevaCant;
                localStorage.setItem('vrossoc_carrito', JSON.stringify(carrito));
                cargarCarrito();
                if (typeof window.actualizarContadorCarrito === 'function') {
                    window.actualizarContadorCarrito();
                }
            }
        });
        
        btnEliminar.addEventListener('click', () => {
            carrito.splice(index, 1);
            localStorage.setItem('vrossoc_carrito', JSON.stringify(carrito));
            cargarCarrito();
            if (typeof window.actualizarContadorCarrito === 'function') {
                window.actualizarContadorCarrito();
            }
        });
        
        contenedor.appendChild(clone);
    });
    
    // Actualizar totales
    subtotalSpan.textContent = '$' + total.toLocaleString('es-AR');
    totalSpan.textContent = '$' + total.toLocaleString('es-AR');
    contadorItems.textContent = totalItems;
    btnCheckout.classList.remove('disabled');
}

// Cargar carrito cuando la página esté lista
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página de carrito cargada');
    cargarCarrito();
});

// También recargar cuando se muestra la página (por si viene de atrás)
window.addEventListener('pageshow', function() {
    console.log('Página de carrito mostrada');
    cargarCarrito();
});
</script>

<?php require_once '../app/views/layout/footer.php'; ?>