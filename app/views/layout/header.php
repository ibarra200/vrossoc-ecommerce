<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vrossoc - <?php echo $data['titulo'] ?? 'Tienda de ropa'; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tu CSS personalizado -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/vrossoc/public/">VROSSOC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/vrossoc/public/">Inicio</a>
                    </li>
                    <li class="nav-item">
    <a class="nav-link" href="/vrossoc/public/producto/index">Productos</a>
</li>
                    <li class="nav-item">
                        <a class="nav-link" href="/vrossoc/public/carrito">Carrito</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="container my-4">