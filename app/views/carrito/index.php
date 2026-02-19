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
                
                <button class="btn btn-primary w-100 mb-2" id="btnCheckout" disabled>
                    Finalizar compra
                </button>
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
                    <img src="https://via.placeholder.com/50x30" alt="MercadoPago" class="img-fluid">
                    <img src="https://via.placeholder.com/50x30" alt="Visa" class="img-fluid">
                    <img src="https://via.placeholder.com/50x30" alt="Mastercard" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template para item del carrito (oculto) -->
<template id="template-carrito-item">
    <div class="carrito-item mb-3 pb-3 border-bottom">
        <div class="row">
            <div class="col-3">
                <img src="" class="img-fluid rounded" alt="Producto">
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
// Clase Carrito para manejar la lógica
class Carrito {
    constructor() {
        this.items = this.cargarCarrito();
        this.total = 0;
        this.subtotal = 0;
        this.envio = 0; // Podemos calcular después
    }
    
    // Cargar carrito desde localStorage
    cargarCarrito() {
        const carrito = localStorage.getItem('vrossoc_carrito');
        return carrito ? JSON.parse(carrito) : [];
    }
    
    // Guardar carrito en localStorage
    guardarCarrito() {
        localStorage.setItem('vrossoc_carrito', JSON.stringify(this.items));
        this.actualizarContador();
    }
    
    // Agregar item al carrito
    agregarItem(producto) {
        // Buscar si ya existe el mismo producto (mismo ID, color y talle)
        const existente = this.items.find(item => 
            item.producto_id === producto.producto_id && 
            item.color === producto.color && 
            item.talle === producto.talle
        );
        
        if (existente) {
            existente.cantidad += producto.cantidad;
        } else {
            this.items.push(producto);
        }
        
        this.guardarCarrito();
        this.mostrarNotificacion('Producto agregado al carrito');
    }
    
    // Quitar item del carrito
    quitarItem(index) {
        this.items.splice(index, 1);
        this.guardarCarrito();
        this.renderizarCarrito();
    }
    
    // Actualizar cantidad
    actualizarCantidad(index, cantidad) {
        if (cantidad <= 0) {
            this.quitarItem(index);
            return;
        }
        
        // Validar contra stock (esto se hará con el backend)
        this.items[index].cantidad = cantidad;
        this.guardarCarrito();
        this.renderizarCarrito();
    }
    
    // Vaciar carrito
    vaciar() {
        this.items = [];
        this.guardarCarrito();
        this.renderizarCarrito();
    }
    
    // Actualizar contador en el navbar
    actualizarContador() {
        const totalItems = this.items.reduce((sum, item) => sum + item.cantidad, 0);
        const contador = document.getElementById('contador-carrito');
        if (contador) {
            contador.textContent = totalItems;
            contador.style.display = totalItems > 0 ? 'inline' : 'none';
        }
    }
    
    // Mostrar notificación
    mostrarNotificacion(mensaje) {
        // Crear elemento de alerta
        const alerta = document.createElement('div');
        alerta.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
        alerta.style.zIndex = '9999';
        alerta.innerHTML = `
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alerta);
        
        // Auto-cerrar después de 3 segundos
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }
    
    // Renderizar carrito en la página
    async renderizarCarrito() {
        const contenedor = document.getElementById('carrito-items');
        const subtotalSpan = document.getElementById('subtotal');
        const totalSpan = document.getElementById('total');
        const btnCheckout = document.getElementById('btnCheckout');
        
        if (this.items.length === 0) {
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
            btnCheckout.disabled = true;
            return;
        }
        
        // Mostrar loading
        contenedor.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Actualizando...</span>
                </div>
            </div>
        `;
        
        try {
            // Sincronizar con backend para precios actualizados
            const response = await fetch('/vrossoc/public/carrito/apiActualizar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ items: this.items })
            });
            
            const data = await response.json();
            
            // Actualizar items con datos del backend
            this.items = data.items.map(item => ({
                ...item,
                cantidad: item.cantidad
            }));
            
            // Guardar versión actualizada
            this.guardarCarrito();
            
            // Renderizar items
            contenedor.innerHTML = '';
            const template = document.getElementById('template-carrito-item');
            
            this.items.forEach((item, index) => {
                const clone = template.content.cloneNode(true);
                const itemDiv = clone.querySelector('.carrito-item');
                
                // Setear datos
                clone.querySelector('img').src = item.imagen;
                clone.querySelector('.producto-nombre').textContent = item.nombre;
                clone.querySelector('.producto-color').textContent = item.color;
                clone.querySelector('.producto-talle').textContent = item.talle;
                clone.querySelector('.producto-precio').textContent = `$${item.precio.toLocaleString('es-AR')}`;
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
                    e.target.value = nuevaCant;
                    this.actualizarCantidad(index, nuevaCant);
                });
                
                btnMenos.addEventListener('click', () => {
                    const nuevaCant = item.cantidad - 1;
                    if (nuevaCant >= 1) {
                        this.actualizarCantidad(index, nuevaCant);
                    }
                });
                
                btnMas.addEventListener('click', () => {
                    const nuevaCant = item.cantidad + 1;
                    if (nuevaCant <= item.stock) {
                        this.actualizarCantidad(index, nuevaCant);
                    }
                });
                
                btnEliminar.addEventListener('click', () => {
                    this.quitarItem(index);
                });
                
                contenedor.appendChild(clone);
            });
            
            // Actualizar totales
            this.subtotal = data.total;
            this.total = this.subtotal + this.envio;
            
            subtotalSpan.textContent = `$${this.subtotal.toLocaleString('es-AR')}`;
            totalSpan.textContent = `$${this.total.toLocaleString('es-AR')}`;
            btnCheckout.disabled = false;
            
        } catch (error) {
            console.error('Error al sincronizar carrito:', error);
            contenedor.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Error al cargar el carrito</h5>
                    <p class="text-muted">Intenta nuevamente</p>
                    <button class="btn btn-primary" onclick="location.reload()">Reintentar</button>
                </div>
            `;
        }
    }
}

// Inicializar carrito
const carrito = new Carrito();

// Renderizar cuando la página cargue
document.addEventListener('DOMContentLoaded', () => {
    carrito.renderizarCarrito();
    
    // Evento para finalizar compra
    document.getElementById('btnCheckout')?.addEventListener('click', async () => {
        const btn = document.getElementById('btnCheckout');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Procesando...';
        
        try {
            const response = await fetch('/vrossoc/public/carrito/apiCheckout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ items: carrito.items })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Redirigir a página de pago
                window.location.href = data.redirect;
            } else {
                alert('Error al procesar la compra');
                btn.disabled = false;
                btn.innerHTML = 'Finalizar compra';
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al procesar la compra');
            btn.disabled = false;
            btn.innerHTML = 'Finalizar compra';
        }
    });
});

// Función para agregar al carrito (usada desde detalle de producto)
function agregarAlCarrito(producto) {
    carrito.agregarItem(producto);
}
</script>

<?php require_once '../app/views/layout/footer.php'; ?>