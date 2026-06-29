<?php

use App\Core\Application;
use App\Core\Env;

require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno primero para que APP_ENV esté disponible
Env::load(__DIR__ . '/../.env');

$isDebug = (($_ENV['APP_ENV'] ?? 'production') === 'development');
error_reporting($isDebug ? E_ALL : 0);
ini_set('display_errors', $isDebug ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', dirname(__DIR__) . '/storage/logs/php_errors.log');

// Inicializar la aplicación
$app = new Application(dirname(__DIR__));

// Registrar rutas
require_once __DIR__ . '/../routes/web.php';

// Ejecutar la aplicación
$app->run();
