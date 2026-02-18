<?php require_once 'layout/header.php'; ?>

<div class="row">
    <div class="col-12 text-center">
        <h1><?php echo $data['titulo']; ?></h1>
        <p class="lead"><?php echo $data['descripcion']; ?></p>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card">
            <img src="https://via.placeholder.com/300" class="card-img-top" alt="Producto">
            <div class="card-body">
                <h5 class="card-title">Producto 1</h5>
                <p class="card-text">$12,990</p>
                <a href="#" class="btn btn-primary">Ver más</a>
            </div>
        </div>
    </div>
    <!-- Más productos... -->
</div>

<?php require_once 'layout/footer.php'; ?>