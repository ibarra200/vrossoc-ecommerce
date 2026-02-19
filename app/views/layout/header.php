<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vrossoc - <?php echo $data['titulo'] ?? 'Tienda de ropa'; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts (tipografía linda) -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Nuestro JS del carrito (SIEMPRE después de jQuery) -->
<script src="<?php echo BASE_URL; ?>/assets/js/carrito.js"></script>


</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/vrossoc/public/">
                <img src="<?php echo BASE_URL; ?>/assets/images/logo/logo.png" 
                     alt="Vrossoc" 
                     height="50"
                     onerror="this.src='https://placehold.co/150x50?text=VROSSOC'; this.onerror=null;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($data['active_menu'] ?? '') == 'inicio' ? 'active' : ''; ?>" href="/vrossoc/public/">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($data['active_menu'] ?? '') == 'productos' ? 'active' : ''; ?>" href="/vrossoc/public/producto/index">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($data['active_menu'] ?? '') == 'nuevo' ? 'active' : ''; ?>" href="/vrossoc/public/producto/index?nuevos">Nuevo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($data['active_menu'] ?? '') == 'ofertas' ? 'active' : ''; ?>" href="/vrossoc/public/producto/index?ofertas">Ofertas</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="/vrossoc/public/carrito/index">
                            <i class="bi bi-cart3 fs-5"></i>
                         <span id="contador-carrito" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">0</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-5"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/vrossoc/public/auth/login">Iniciar sesión</a></li>
                            <li><a class="dropdown-item" href="/vrossoc/public/auth/registro">Registrarse</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/vrossoc/public/auth/mis-pedidos">Mis pedidos</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="container my-4">
        <!-- Script de verificación del carrito -->
<script>
// Verificar que el contador existe y funciona
document.addEventListener('DOMContentLoaded', function() {
    console.log('Verificando contador...');
    const contador = document.getElementById('contador-carrito');
    if (contador) {
        console.log('✅ Contador encontrado en el DOM');
        
        // Cargar carrito y actualizar
        const carrito = JSON.parse(localStorage.getItem('vrossoc_carrito')) || [];
        const totalItems = carrito.reduce((sum, item) => sum + (parseInt(item.cantidad) || 1), 0);
        contador.textContent = totalItems;
        contador.style.display = totalItems > 0 ? 'inline' : 'none';
        console.log('Contador inicializado en:', totalItems);
    } else {
        console.log('❌ ERROR: No se encontró el elemento #contador-carrito');
    }
});
</script>