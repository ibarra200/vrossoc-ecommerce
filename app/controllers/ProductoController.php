<?php
// app/controllers/ProductoController.php

class ProductoController extends Controller {
    
    private $productoModel;
    
    public function __construct() {
        // Cargar el modelo Producto
        $this->productoModel = $this->model('Producto');
    }
    
    /**
     * Mostrar listado de productos (catálogo)
     */
  public function index() {
    // Obtener todos los productos
    $productos = $this->productoModel->getTodos();
    
    $data = [
        'titulo' => 'Catálogo de Productos - Vrossoc',
        'productos' => $productos,
        'active_menu' => 'productos'
    ];
    
    $this->view('productos/catalogo', $data);
}

    /**
     * Mostrar detalle de un producto
     */
    public function show($id) {
        // Obtener producto con detalles
        $producto = $this->productoModel->getConDetalles($id);
        
        if (!$producto) {
            // Producto no encontrado, redirigir a catálogo
            $this->redirect('/vrossoc/public/productos');
            return;
        }
        
        $data = [
            'titulo' => $producto['nombre'] . ' - Vrossoc',
            'producto' => $producto
        ];
        
        $this->view('productos/detalle', $data);
    }
    
    /**
     * Mostrar productos por categoría
     */
    public function categoria($categoriaId) {
        $productos = $this->productoModel->getPorCategoria($categoriaId);
        
        // Aquí podrías obtener el nombre de la categoría
        // Por ahora usamos un título genérico
        
        $data = [
            'titulo' => 'Categoría - Vrossoc',
            'productos' => $productos
        ];
        
        $this->view('productos/catalogo', $data);
    }
    
    /**
     * API para obtener productos (para JavaScript/Fetch)
     */
    public function apiGet($id = null) {
        header('Content-Type: application/json');
        
        if ($id) {
            $producto = $this->productoModel->getConDetalles($id);
            echo json_encode($producto);
        } else {
            $productos = $this->productoModel->getTodos();
            echo json_encode($productos);
        }
    }
}