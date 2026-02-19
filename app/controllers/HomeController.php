<?php
// app/controllers/HomeController.php

class HomeController extends Controller {
    
    private $productoModel;
    
    public function __construct() {
        $this->productoModel = $this->model('Producto');
    }
    
    public function index() {
    // Obtener productos destacados
    $destacados = $this->productoModel->getDestacados(4);
    
    $data = [
        'titulo' => 'Bienvenido a Vrossoc',
        'descripcion' => 'Tu tienda de ropa online',
        'destacados' => $destacados,
        'active_menu' => 'inicio'
    ];
    
    $this->view('inicio', $data);
}

    
    public function about() {
        echo "Acerca de Vrossoc";
    }
    
}