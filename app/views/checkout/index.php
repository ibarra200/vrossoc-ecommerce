<?php require_once '../app/views/layout/header.php'; ?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="text-center">Finalizar Compra</h1>
        <p class="text-center text-muted">Completá tus datos para recibir el pedido</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Datos de envío</h5>
            </div>
            <div class="card-body">
                <form action="/vrossoc/public/checkout/procesar" method="POST" id="form-checkout">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre completo *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono *</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="codigo_postal" class="form-label">Código Postal *</label>
                            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección *</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Calle y número" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ciudad" class="form-label">Ciudad *</label>
                            <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="provincia" class="form-label">Provincia *</label>
                            <select class="form-select" id="provincia" name="provincia" required>
                                <option value="">Seleccionar</option>
                                <option value="Buenos Aires">Buenos Aires</option>
                                <option value="CABA">CABA</option>
                                <option value="Catamarca">Catamarca</option>
                                <option value="Chaco">Chaco</option>
                                <option value="Chubut">Chubut</option>
                                <option value="Córdoba">Córdoba</option>
                                <option value="Corrientes">Corrientes</option>
                                <option value="Entre Ríos">Entre Ríos</option>
                                <option value="Formosa">Formosa</option>
                                <option value="Jujuy">Jujuy</option>
                                <option value="La Pampa">La Pampa</option>
                                <option value="La Rioja">La Rioja</option>
                                <option value="Mendoza">Mendoza</option>
                                <option value="Misiones">Misiones</option>
                                <option value="Neuquén">Neuquén</option>
                                <option value="Río Negro">Río Negro</option>
                                <option value="Salta">Salta</option>
                                <option value="San Juan">San Juan</option>
                                <option value="San Luis">San Luis</option>
                                <option value="Santa Cruz">Santa Cruz</option>
                                <option value="Santa Fe">Santa Fe</option>
                                <option value="Santiago del Estero">Santiago del Estero</option>
                                <option value="Tierra del Fuego">Tierra del Fuego</option>
                                <option value="Tucumán">Tucumán</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="instrucciones" class="form-label">Instrucciones especiales (opcional)</label>
                        <textarea class="form-control" id="instrucciones" name="instrucciones" rows="2" placeholder="Ej: Dejar en portería, timbre 3, etc."></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="/vrossoc/public/carrito/index" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Volver al carrito
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Continuar con el resumen <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Resumen del pedido</h5>
            </div>
            <div class="card-body">
                <div id="resumen-carrito">
                    <!-- Se cargará con JavaScript -->
                    <div class="text-center py-3">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Cargar resumen del carrito desde localStorage
document.addEventListener('DOMContentLoaded', function() {
    const carrito = JSON.parse(localStorage.getItem('vrossoc_carrito')) || [];
    const resumenDiv = document.getElementById('resumen-carrito');
    
    if (carrito.length === 0) {
        window.location.href = '/vrossoc/public/carrito/index';
        return;
    }
    
    let html = '';
    let total = 0;
    
    carrito.forEach(item => {
        const subtotal = item.precio * item.cantidad;
        total += subtotal;
        
        html += `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <span class="fw-bold">${item.cantidad}x</span> ${item.nombre}
                    <br><small class="text-muted">${item.color} / ${item.talle}</small>
                </div>
                <span class="fw-bold">$${subtotal.toLocaleString('es-AR')}</span>
            </div>
        `;
    });
    
    html += `
        <hr>
        <div class="d-flex justify-content-between">
            <span class="h6">Total:</span>
            <span class="h6 text-primary">$${total.toLocaleString('es-AR')}</span>
        </div>
    `;
    
    resumenDiv.innerHTML = html;
});
</script>

<?php require_once '../app/views/layout/footer.php'; ?>