<?php
// app/controllers/CarritoController.php

class CarritoController extends Controller {
    
    private $productoModel;
    
    public function __construct() {
        $this->productoModel = $this->model('Producto');
    }
    
    /**
     * Mostrar vista del carrito
     */
    public function index() {
        $data = [
            'titulo' => 'Carrito de Compras - Vrossoc'
        ];
        
        $this->view('carrito/index', $data);
    }
    
    /**
     * API: Obtener información actualizada de productos en el carrito
     * Recibe un array de items y devuelve precios actualizados
     */
    public function apiActualizar() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $items = $input['items'] ?? [];
        
        $respuesta = [
            'items' => [],
            'total' => 0
        ];
        
        foreach ($items as $item) {
            $producto = $this->productoModel->getConDetalles($item['producto_id']);
            
            if ($producto) {
                // Buscar la variante específica
                $variante = null;
                foreach ($producto['variantes'] as $v) {
                    if ($v['color'] === $item['color'] && $v['talle'] === $item['talle']) {
                        $variante = $v;
                        break;
                    }
                }
                
                if ($variante) {
                    $precio = $producto['precio_oferta'] ?? $producto['precio'];
                    
                    $itemActualizado = [
                        'producto_id' => $producto['id'],
                        'nombre' => $producto['nombre'],
                        'color' => $item['color'],
                        'talle' => $item['talle'],
                        'precio' => $precio,
                        'cantidad' => $item['cantidad'],
                        'stock' => $variante['stock'],
                        'imagen' => $producto['imagenes'][0]['imagen_url'] ?? 'https://via.placeholder.com/100'
                    ];
                    
                    $respuesta['items'][] = $itemActualizado;
                    $respuesta['total'] += $precio * $item['cantidad'];
                }
            }
        }
        
        echo json_encode($respuesta);
    }
    
    /**
     * API: Procesar checkout (por ahora solo simula)
     */
    public function apiCheckout() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Aquí luego integraremos MercadoPago
        // Por ahora devolvemos éxito simulado
        
        echo json_encode([
            'success' => true,
            'message' => 'Checkout procesado (modo simulación)',
            'redirect' => '/vrossoc/public/pago/exito'
        ]);
    }
}