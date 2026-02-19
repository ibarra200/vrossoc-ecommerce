<?php
// app/controllers/CheckoutController.php

class CheckoutController extends Controller {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Mostrar formulario de checkout
     */
    public function index() {
        $data = [
            'titulo' => 'Finalizar Compra - Vrossoc',
            'active_menu' => 'carrito'
        ];
        
        $this->view('checkout/index', $data);
    }
    
    /**
     * Procesar el checkout (guardar datos y mostrar resumen)
     */
    public function procesar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/vrossoc/public/checkout/index');
            return;
        }
        
        // Guardar datos de envío en sesión
        $_SESSION['checkout'] = [
            'nombre' => $_POST['nombre'] ?? '',
            'email' => $_POST['email'] ?? '',
            'telefono' => $_POST['telefono'] ?? '',
            'direccion' => $_POST['direccion'] ?? '',
            'ciudad' => $_POST['ciudad'] ?? '',
            'provincia' => $_POST['provincia'] ?? '',
            'codigo_postal' => $_POST['codigo_postal'] ?? '',
            'instrucciones' => $_POST['instrucciones'] ?? ''
        ];
        
        $this->redirect('/vrossoc/public/checkout/resumen');
    }
    
    /**
     * Mostrar resumen de la compra
     */
    public function resumen() {
        if (!isset($_SESSION['checkout'])) {
            $this->redirect('/vrossoc/public/checkout/index');
            return;
        }
        
        $data = [
            'titulo' => 'Resumen de Compra - Vrossoc',
            'checkout' => $_SESSION['checkout'],
            'active_menu' => 'carrito'
        ];
        
        $this->view('checkout/resumen', $data);
    }
    
    /**
     * API: Recibir el carrito y crear el pedido
     */
    public function apiConfirmar() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $carrito = $input['carrito'] ?? [];
        $checkout = $_SESSION['checkout'] ?? null;
        
        if (empty($carrito) || !$checkout) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
            return;
        }
        
        // Calcular total
        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        
        // Generar número de pedido
        $numero_pedido = 'VRO-' . date('Ymd') . '-' . rand(1000, 9999);
        
        // Guardar en sesión el pedido confirmado
        $_SESSION['ultimo_pedido'] = [
            'numero' => $numero_pedido,
            'fecha' => date('d/m/Y H:i'),
            'total' => $total,
            'items' => $carrito,
            'envio' => $checkout
        ];
        
        // Limpiar checkout de la sesión
        unset($_SESSION['checkout']);
        
        echo json_encode([
            'success' => true,
            'numero_pedido' => $numero_pedido
        ]);
    }
    
    /**
     * Página de éxito
     */
    public function exito($numero_pedido = null) {
        if (!isset($_SESSION['ultimo_pedido']) || $_SESSION['ultimo_pedido']['numero'] !== $numero_pedido) {
            $this->redirect('/vrossoc/public/');
            return;
        }
        
        $data = [
            'titulo' => '¡Compra Exitosa! - Vrossoc',
            'pedido' => $_SESSION['ultimo_pedido'],
            'active_menu' => ''
        ];
        
        $this->view('checkout/exito', $data);
        
        // Limpiar el último pedido de la sesión después de mostrarlo
        unset($_SESSION['ultimo_pedido']);
    }
}