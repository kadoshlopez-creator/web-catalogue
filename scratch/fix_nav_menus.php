<?php
// Script simple para actualizar navigation_menus sin dependencias externas
$pdo = new PDO("mysql:host=127.0.0.1;dbname=web_catalogue;charset=utf8mb4", "root", "");

$menus = [
    'main' => [
        'items' => [
            ['label' => 'Inicio',   'link' => '/'],
            ['label' => 'Catálogo', 'link' => '/catalogo'],
        ]
    ],
    'footer' => [
        'items' => [
            ['label' => 'Inicio',         'link' => '/'],
            ['label' => 'Catálogo',       'link' => '/catalogo'],
            ['label' => 'Sobre Nosotros', 'link' => '/p/sobre-nosotros'],
        ]
    ]
];

$json = json_encode($menus, JSON_UNESCAPED_UNICODE);

$stmt = $pdo->prepare("UPDATE settings SET setting_value = :val WHERE setting_key = 'navigation_menus'");
$stmt->bindValue(':val', $json);
$stmt->execute();

echo "OK: navigation_menus actualizado.\n";
echo "Valor: " . $json . "\n";
