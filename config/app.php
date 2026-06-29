<?php

return [
    'name' => $_ENV['APP_NAME'] ?? 'Catálogo Web',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    'timezone' => 'America/Lima', // Ajustar según zona horaria
    'debug' => ($_ENV['APP_ENV'] ?? 'production') === 'development',
];
