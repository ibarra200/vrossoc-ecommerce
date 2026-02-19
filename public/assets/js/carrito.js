// public/assets/js/carrito.js

// Función para actualizar contador del carrito en el navbar
function actualizarContadorCarrito() {
    // Obtener carrito de localStorage
    const carrito = JSON.parse(localStorage.getItem('vrossoc_carrito')) || [];
    
    // Calcular total de items (sumando cantidades)
    const totalItems = carrito.reduce((sum, item) => sum + (parseInt(item.cantidad) || 1), 0);
    
    // Buscar el elemento del contador
    const contador = document.getElementById('contador-carrito');
    
    if (contador) {
        if (totalItems > 0) {
            contador.textContent = totalItems;
            contador.style.display = 'inline';
            contador.classList.add('badge', 'bg-danger');
        } else {
            contador.style.display = 'none';
        }
        console.log('Contador actualizado:', totalItems); // Para debug
    } else {
        console.log('Elemento contador-carrito no encontrado');
    }
}

// Función para agregar al carrito (versión global)
function agregarAlCarrito(producto) {
    // Obtener carrito actual
    let carrito = JSON.parse(localStorage.getItem('vrossoc_carrito')) || [];
    
    // Buscar si ya existe el mismo producto
    const existenteIndex = carrito.findIndex(item => 
        item.producto_id === producto.producto_id && 
        item.color === producto.color && 
        item.talle === producto.talle
    );
    
    if (existenteIndex !== -1) {
        // Si existe, aumentar cantidad
        carrito[existenteIndex].cantidad = (parseInt(carrito[existenteIndex].cantidad) || 1) + 1;
    } else {
        // Si no existe, agregar nuevo (asegurar que tenga cantidad)
        producto.cantidad = producto.cantidad || 1;
        carrito.push(producto);
    }
    
    // Guardar en localStorage
    localStorage.setItem('vrossoc_carrito', JSON.stringify(carrito));
    
    // Actualizar contador
    actualizarContadorCarrito();
    
    // Mostrar notificación
    mostrarNotificacion('Producto agregado al carrito');
    
    return carrito;
}

// Función para mostrar notificación
function mostrarNotificacion(mensaje) {
    // Crear elemento de alerta
    const alerta = document.createElement('div');
    alerta.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
    alerta.style.zIndex = '9999';
    alerta.style.minWidth = '250px';
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

// Inicializar cuando carga la página
document.addEventListener('DOMContentLoaded', function() {
    console.log('carrito.js cargado');
    actualizarContadorCarrito();
});

// También actualizar cuando se muestra la página (por si se usa el botón "atrás")
window.addEventListener('pageshow', function() {
    actualizarContadorCarrito();
});