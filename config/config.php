<?php
// config/config.php

// Definir constantes globales
define('ROOT_PATH', dirname(__DIR__));
define('BASE_URL', 'http://localhost/vrossoc/public');
define('APP_NAME', 'Vrossoc');

// Configuración de base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'vrossoc_db');
define('DB_USER', 'root');
define('DB_PASS', '');
// config/config.php - Agregar al final

// MercadoPago Credentials
define('MP_ACCESS_TOKEN', 'TEST-5505605385241502-021818-7de434feeaf8fb7ef08078afe3d8db9d-1408635962'); // Tu token de prueba
define('MP_PUBLIC_KEY', 'TEST-456db952-4d7a-46db-8e43-ce32bb34b2f6'); // Tu public key de prueba
define('MP_NOTIFICATION_URL', BASE_URL . '/webhook/mercadopago'); // URL para notificaciones