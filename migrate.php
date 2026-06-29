<?php

// Bloquear ejecución vía web — solo se permite desde la línea de comandos
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Acceso denegado. Este script solo puede ejecutarse desde la terminal.');
}

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Env;

// Cargar .env
Env::load(__DIR__ . '/.env');

$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$port = $_ENV['DB_PORT'] ?? '3306';
$database = $_ENV['DB_DATABASE'] ?? 'web_catalogue';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';

try {
    // 1. Conectar sin base de datos para crearla si no existe
    $pdo = new PDO("mysql:host=$host;port=$port", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Creating database '$database' if not exists...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    
    // 2. Conectar a la base de datos
    $pdo->exec("USE `$database`;");
    
    // 3. Ejecutar Migraciones
    echo "Running migrations...\n";
    $migrationFiles = glob(__DIR__ . '/database/migrations/*.sql');
    sort($migrationFiles);
    
    foreach ($migrationFiles as $file) {
        $sql = file_get_contents($file);
        $pdo->exec($sql);
        echo "Migrated: " . basename($file) . "\n";
    }
    
    // 4. Ejecutar Seeders
    echo "Running seeders...\n";
    $seederFiles = glob(__DIR__ . '/database/seeders/*.sql');
    sort($seederFiles);
    
    foreach ($seederFiles as $file) {
        $sql = file_get_contents($file);
        $pdo->exec($sql);
        echo "Seeded: " . basename($file) . "\n";
    }
    
    echo "\nDatabase migrated and seeded successfully!\n";
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage() . "\n");
}
