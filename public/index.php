<?php
// public/index.php

// Cargar configuración global
require_once dirname(__DIR__) . '/config/config.php';

// Cargar archivos principales
require_once ROOT_PATH . '/app/core/Router.php';
require_once ROOT_PATH . '/app/core/Controller.php';
require_once ROOT_PATH . '/app/core/Model.php';

// Iniciar sesión
session_start();

// Iniciar el router
$router = new Router();