<?php require_once '../app/views/layout/header.php'; ?>

<div class="row">
    <div class="col-12 text-center">
        <div class="mb-4">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
        </div>
        <h1 class="mb-3">¡Gracias por tu compra!</h1>
        <p class="lead mb-4">Tu pedido ha sido confirmado</p>
        
        <div class="card mx-auto mb-4" style="max-width: 500px;">
            <div class="card-body">
                <h5 class="card-title">Detalle del pedido</h5>
                <p class="mb-1"><strong>Número:</strong> <?php echo $data['pedido']['numero']; ?></p>
                <p class="mb-1"><strong>Fecha:</strong> <?php echo $data['pedido']['fecha']; ?></p>
                <p class="mb-1"><strong>Total:</strong> $<?php echo number_format($data['pedido']['total'], 0, ',', '.'); ?></p>
                <hr>
                <h6>Productos:</h6>
                <?php foreach ($data['pedido']['items'] as $item): ?>
                    <p class="mb-1 small">
                        <?php echo $item['cantidad']; ?>x <?php echo $item['nombre']; ?> 
                        (<?php echo $item['color']; ?> / <?php echo $item['talle']; ?>)
                    </p>
                <?php endforeach; ?>
            </div>
        </div>
        
        <p>Te enviaremos un email con los detalles del pedido.</p>
        
        <div class="mt-4">
            <a href="/vrossoc/public/" class="btn btn-primary">Volver al inicio</a>
            <a href="/vrossoc/public/producto/index" class="btn btn-outline-primary ms-2">Seguir comprando</a>
        </div>
    </div>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>