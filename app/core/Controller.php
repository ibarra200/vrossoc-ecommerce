<?php
// app/core/Controller.php

class Controller {
    
    // Cargar modelo
    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }
    
    // Cargar vista
    public function view($view, $data = []) {
        // Extraer datos para que estén disponibles en la vista
        extract($data);
        
        $viewFile = '../app/views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Vista no encontrada: " . $view);
        }
    }
    
    // Redireccionar
    public function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
}