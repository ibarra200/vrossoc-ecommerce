<?php require_once '../app/views/layout/header.php'; ?>

<?php $producto = $data['producto']; ?>

<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/vrossoc/public/">Inicio</a></li>
                <li class="breadcrumb-item"><a href="/vrossoc/public/producto/index">Productos</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $producto['nombre']; ?></li>
            </ol>
        </nav>
    </div>
</div>

<!-- Columna izquierda: Imágenes -->
<!-- Columna izquierda: Imágenes -->
<div class="col-md-6 mb-4">
    <!-- Imagen principal - 600x600 como antes -->
    <div class="mb-3">
        <img id="imagenPrincipal" src="https://placehold.co/300x300" class="img-fluid rounded w-100" alt="<?php echo $producto['nombre']; ?>">
    </div>
    
    <!-- Miniaturas - 150x150 como antes -->
    <?php if (!empty($producto['imagenes'])): ?>
    <div class="row g-2">
        <?php foreach ($producto['imagenes'] as $index => $imagen): ?>
        <div class="col-3">
            <img src="https://placehold.co/150x150" class="img-thumbnail miniatura w-100" 
                 alt="Miniatura <?php echo $index + 1; ?>"
                 onclick="document.getElementById('imagenPrincipal').src = this.src">
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
    
    <!-- Columna derecha: Información del producto -->
    <div class="col-md-6">
        <h1 class="mb-3"><?php echo $producto['nombre']; ?></h1>
        
        <!-- Precio -->
        <div class="mb-3">
            <?php if ($producto['precio_oferta']): ?>
                <span class="h2 text-danger">$<?php echo number_format($producto['precio_oferta'], 0, ',', '.'); ?></span>
                <span class="h5 text-muted text-decoration-line-through ms-2">$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></span>
            <?php else: ?>
                <span class="h2 text-primary">$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></span>
            <?php endif; ?>
        </div>
        
        <!-- Descripción -->
        <div class="mb-4">
            <h5>Descripción</h5>
            <p><?php echo nl2br($producto['descripcion']); ?></p>
        </div>
        
        <!-- Selector de Color (si hay variantes) -->
        <?php if (!empty($producto['variantes'])): ?>
            <?php 
            // Obtener colores únicos
            $colores = array_unique(array_column($producto['variantes'], 'color'));
            ?>
            <div class="mb-3">
                <h5>Color</h5>
                <div class="d-flex gap-2">
                    <?php foreach ($colores as $color): ?>
                    <button class="btn btn-outline-dark selector-color" data-color="<?php echo $color; ?>">
                        <?php echo $color; ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Selector de Talle (se actualizará por JS) -->
            <div class="mb-3">
                <h5>Talle</h5>
                <div class="d-flex gap-2" id="talles-container">
                    <!-- Los talles se cargarán vía JavaScript -->
                    <p class="text-muted">Selecciona un color primero</p>
                </div>
            </div>
            
            <!-- Stock disponible -->
            <div class="mb-3" id="stock-info">
                <p class="text-muted"><small>Selecciona un color y talle para ver stock</small></p>
            </div>
        <?php endif; ?>
        
        <!-- Botones de acción -->
        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-primary btn-lg flex-grow-1" id="btnAgregarCarrito" disabled>
                <i class="bi bi-cart-plus"></i> Agregar al carrito
            </button>
            <button class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-heart"></i>
            </button>
        </div>
        
        <!-- Información adicional -->
        <div class="card">
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="bi bi-truck"></i> Envíos a todo el país</li>
                    <li class="mb-2"><i class="bi bi-credit-card"></i> Pagá con MercadoPago</li>
                    <li><i class="bi bi-arrow-return-left"></i> Cambios y devoluciones</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Productos Relacionados (opcional) -->
<div class="row mt-5">
    <div class="col-12">
        <h3 class="text-center mb-4">También te puede interesar</h3>
    </div>
    <div class="col-md-3">
        <div class="card">
            <img src="https://placehold.co/300x300" class="card-img-top" alt="Producto relacionado">
            <div class="card-body">
                <h6 class="card-title">Producto relacionado</h6>
                <p class="card-text">$12,990</p>
            </div>
        </div>
    </div>
    <!-- Más productos relacionados... -->
</div>

<script>
// Datos de variantes desde PHP a JavaScript
const variantes = <?php echo json_encode($producto['variantes'] ?? []); ?>;

// Elementos del DOM
const colores = document.querySelectorAll('.selector-color');
const tallesContainer = document.getElementById('talles-container');
const stockInfo = document.getElementById('stock-info');
const btnAgregar = document.getElementById('btnAgregarCarrito');

let colorSeleccionado = null;
let talleSeleccionado = null;

// Función para actualizar talles según color seleccionado
function actualizarTalles(color) {
    // Filtrar variantes por color
    const variantesColor = variantes.filter(v => v.color === color);
    const talles = [...new Set(variantesColor.map(v => v.talle))];
    
    // Generar botones de talle
    let html = '';
    talles.forEach(talle => {
        const variante = variantesColor.find(v => v.talle === talle);
        const stock = variante ? variante.stock : 0;
        
        html += `<button class="btn btn-outline-dark selector-talle m-1" 
                        data-talle="${talle}" 
                        data-stock="${stock}"
                        ${stock === 0 ? 'disabled' : ''}>
                    ${talle} ${stock === 0 ? '(agotado)' : ''}
                 </button>`;
    });
    
    tallesContainer.innerHTML = html;
    
    // Agregar eventos a los nuevos botones de talle
    document.querySelectorAll('.selector-talle').forEach(btn => {
        btn.addEventListener('click', function() {
            // Quitar selección anterior
            document.querySelectorAll('.selector-talle').forEach(b => 
                b.classList.remove('btn-dark', 'active'));
            
            // Marcar este como seleccionado
            this.classList.add('btn-dark', 'active');
            
            talleSeleccionado = this.dataset.talle;
            const stock = this.dataset.stock;
            
            stockInfo.innerHTML = `<p class="text-success"><strong>Stock disponible:</strong> ${stock} unidades</p>`;
            
            // Habilitar botón de agregar al carrito
            btnAgregar.disabled = false;
        });
    });
    
    // Resetear selección de talle
    talleSeleccionado = null;
    stockInfo.innerHTML = '<p class="text-muted"><small>Selecciona un talle</small></p>';
    btnAgregar.disabled = true;
}

// Event listeners para colores
colores.forEach(btn => {
    btn.addEventListener('click', function() {
        // Quitar selección anterior
        colores.forEach(b => b.classList.remove('btn-dark', 'active'));
        
        // Marcar este como seleccionado
        this.classList.add('btn-dark', 'active');
        
        colorSeleccionado = this.dataset.color;
        actualizarTalles(colorSeleccionado);
    });
});

// Función para actualizar contador (por si no existe la global)
function actualizarContadorLocal() {
    const carrito = JSON.parse(localStorage.getItem('vrossoc_carrito')) || [];
    const totalItems = carrito.reduce((sum, item) => sum + (parseInt(item.cantidad) || 1), 0);
    const contador = document.getElementById('contador-carrito');
    
    if (contador) {
        if (totalItems > 0) {
            contador.textContent = totalItems;
            contador.style.display = 'inline';
        } else {
            contador.style.display = 'none';
        }
        console.log('Contador actualizado a:', totalItems);
    }
}

// Evento para agregar al carrito - VERSIÓN CORREGIDA
btnAgregar.addEventListener('click', function() {
    console.log('1. Botón clickeado');
    console.log('2. Color seleccionado:', colorSeleccionado);
    console.log('3. Talle seleccionado:', talleSeleccionado);
    
    if (colorSeleccionado && talleSeleccionado) {
        
        // Buscar la variante para verificar stock
        const variante = variantes.find(v => 
            v.color === colorSeleccionado && 
            v.talle === talleSeleccionado
        );
        
        console.log('4. Variante encontrada:', variante);
        
        if (!variante || variante.stock <= 0) {
            alert('❌ Producto sin stock');
            return;
        }
        
        // Crear objeto del producto
        const producto = {
            producto_id: <?php echo $producto['id']; ?>,
            nombre: '<?php echo addslashes($producto['nombre']); ?>',
            color: colorSeleccionado,
            talle: talleSeleccionado,
            precio: <?php echo ($producto['precio_oferta'] ?? $producto['precio']); ?>,
            cantidad: 1,
            stock: variante.stock,
            imagen: '<?php echo !empty($producto['imagenes'][0]['imagen_url']) ? $producto['imagenes'][0]['imagen_url'] : 'https://placehold.co/100x100'; ?>'
        };
        
        console.log('5. Producto a agregar:', producto);
        
        // Obtener carrito actual
        let carrito = JSON.parse(localStorage.getItem('vrossoc_carrito')) || [];
        console.log('6. Carrito actual:', carrito);
        
        // Buscar si ya existe el mismo producto
        const existenteIndex = carrito.findIndex(item => 
            item.producto_id === producto.producto_id && 
            item.color === producto.color && 
            item.talle === producto.talle
        );
        
        if (existenteIndex !== -1) {
            // Si existe, aumentar cantidad
            carrito[existenteIndex].cantidad = (parseInt(carrito[existenteIndex].cantidad) || 1) + 1;
            console.log('7. Producto existente, nueva cantidad:', carrito[existenteIndex].cantidad);
        } else {
            // Si no existe, agregar nuevo
            carrito.push(producto);
            console.log('7. Producto nuevo agregado');
        }
        
        // Guardar en localStorage
        localStorage.setItem('vrossoc_carrito', JSON.stringify(carrito));
        console.log('8. Carrito guardado:', carrito);
        
        // ACTUALIZAR CONTADOR - Múltiples métodos para asegurar
        actualizarContadorLocal();
        
        // También intentar con la función global si existe
        if (typeof window.actualizarContadorCarrito === 'function') {
            window.actualizarContadorCarrito();
        }
        
        // Forzar actualización del contador en el DOM
        const totalItems = carrito.reduce((sum, item) => sum + (parseInt(item.cantidad) || 1), 0);
        const contador = document.getElementById('contador-carrito');
        if (contador) {
            contador.textContent = totalItems;
            contador.style.display = 'inline';
            console.log('9. Contador forzado a:', totalItems);
        } else {
            console.log('9. ERROR: No se encontró el elemento contador-carrito');
        }
        
        // Mostrar notificación
        alert('✅ Producto agregado al carrito');
        
    } else {
        alert('⚠️ Debes seleccionar color y talle');
    }
});

// Inicializar contador al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    actualizarContadorLocal();
});
</script>

<style>
.miniatura {
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.3s;
}
.miniatura:hover {
    opacity: 1;
}
.selector-color, .selector-talle {
    min-width: 60px;
    transition: all 0.3s;
}
.selector-color:hover, .selector-talle:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
</style>

<?php require_once '../app/views/layout/footer.php'; ?>