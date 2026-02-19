<?php require_once '../app/views/layout/header.php'; ?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="text-center"><?php echo $data['titulo']; ?></h1>
    </div>
</div>

<div class="row">
    <?php if (empty($data['productos'])): ?>
        <div class="col-12 text-center">
            <p class="alert alert-info">No hay productos disponibles</p>
        </div>
    <?php else: ?>
        <?php foreach ($data['productos'] as $producto): ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100 producto-card">
                    <?php 
                    // Buscar imagen principal o usar placeholder
                    $imagen = 'https://placehold.co/300x300';
                    ?>
                    <img src="<?php echo $imagen; ?>" class="card-img-top" alt="<?php echo $producto['nombre']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $producto['nombre']; ?></h5>
                        <p class="card-text text-truncate"><?php echo $producto['descripcion']; ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 text-primary">$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></span>
                            <?php if ($producto['precio_oferta']): ?>
                                <span class="text-danger">$<?php echo number_format($producto['precio_oferta'], 0, ',', '.'); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="/vrossoc/public/producto/show/<?php echo $producto['id']; ?>" class="btn btn-primary w-100">
                            Ver detalles
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>