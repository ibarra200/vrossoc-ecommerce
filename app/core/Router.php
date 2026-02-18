<?php
// app/core/Router.php

class Router {
    protected $controller = 'HomeController'; // Cambiado de 'home' a 'HomeController'
    protected $method = 'index';
    protected $params = [];
    
    public function __construct() {
        $url = $this->getUrl();
        
        // Buscar controlador en la URL
        if (isset($url[0])) {
            // Convertir a formato: nombre + 'Controller' (ej: producto → ProductoController)
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerFile = '../app/controllers/' . $controllerName . '.php';
            
            if (file_exists($controllerFile)) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }
        
        // Requerir el controlador
        require_once '../app/controllers/' . $this->controller . '.php';
        
        // Instanciar el controlador
        $this->controller = new $this->controller();
        
        // Buscar método en la URL
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }
        
        // Obtener parámetros
        $this->params = $url ? array_values($url) : [];
        
        // Llamar al método del controlador con los parámetros
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
    
    public function getUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}