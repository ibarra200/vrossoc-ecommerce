<?php
// app/controllers/HomeController.php

class HomeController extends Controller {
    
    public function index() {
        $data = [
            'titulo' => 'Bienvenido a Vrossoc',
            'descripcion' => 'Tu tienda de ropa online'
        ];
        
        $this->view('inicio', $data);
    }
    
    public function about() {
        echo "Acerca de Vrossoc";
    }
}