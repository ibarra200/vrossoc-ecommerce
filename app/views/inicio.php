<?php require_once 'layout/header.php'; ?>

<div class="row">
    <div class="col-12 text-center">
        <h1><?php echo $data['titulo']; ?></h1>
        <p class="lead"><?php echo $data['descripcion']; ?></p>
    </div>
</div>

<!-- Hero section (opcional) -->
<div class="row mt-4 mb-5">
    <div class="col-12">
        <div class="p-5 mb-4 bg-light rounded-3">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold">Nueva Colección</h1>
                <p class="col-md-8 fs-4">Descubre las últimas tendencias en Vrossoc</p>
                <a href="/vrossoc/public/producto/index" class="btn btn-primary btn-lg">Ver productos</a>
            </div>
        </div>
    </div>
</div>

<!-- Productos Destacados -->
<div class="row mb-4">
    <div class="col-12">
        <h2 class="text-center">Productos Destacados</h2>
    </div>
</div>

<div class="row">
    <?php if (empty($data['destacados'])): ?>
        <div class="col-12 text-center">
            <p class="alert alert-info">No hay productos destacados</p>
        </div>
    <?php else: ?>
        <?php foreach ($data['destacados'] as $producto): ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <img src="https://placehold.co/300x300" class="card-img-top" alt="<?php echo $producto['nombre']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $producto['nombre']; ?></h5>
                        <p class="card-text">$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></p>
                        <a href="/vrossoc/public/producto/show/<?php echo $producto['id']; ?>" class="btn btn-outline-primary">Ver más</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once 'layout/footer.php'; ?>