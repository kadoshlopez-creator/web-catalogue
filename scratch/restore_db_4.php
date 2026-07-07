<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "web_catalogue");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// 1. Restore Main Menu with Contacto AND Sobre Nosotros
$defaultMenu = [
    'main' => [
        'items' => [
            ['label' => 'Inicio', 'link' => '/'],
            ['label' => 'Catálogo', 'link' => '/catalogo'],
            ['label' => 'Sobre Nosotros', 'link' => '/p/sobre-nosotros'],
            ['label' => 'Contacto', 'link' => '/p/contacto']
        ]
    ],
    'footer' => [
        'items' => [
            ['label' => 'Inicio', 'link' => '/'],
            ['label' => 'Catálogo', 'link' => '/catalogo']
        ]
    ]
];
$menuJson = $mysqli->real_escape_string(json_encode($defaultMenu));
$mysqli->query("UPDATE settings SET setting_value = '$menuJson' WHERE setting_key = 'navigation_menus'");

// 2. Restore Hero Image (ensuring it's there, in case they wiped it too)
$q = $mysqli->query("SELECT setting_value FROM settings WHERE setting_key = 'home_hero_slides'");
$oldHeroRow = $q->fetch_assoc();
$imagePath = '';
if ($oldHeroRow) {
    $oldHeroData = json_decode($oldHeroRow['setting_value'], true);
    if (isset($oldHeroData[0]['image_path']) && !empty($oldHeroData[0]['image_path'])) {
        $imagePath = $oldHeroData[0]['image_path'];
    } else {
        $imagePath = '/uploads/hero/hero_1782762796_0.png';
    }
} else {
    $imagePath = '/uploads/hero/hero_1782762796_0.png';
}

$heroSlides = [
    [
        'title' => 'La mejor tecnología al mejor precio',
        'subtitle' => 'Descubre nuestra increíble selección de productos electrónicos de alta gama. Equipos listos para llevar tu productividad y entretenimiento al siguiente nivel.',
        'btn_text' => 'Ver Catálogo',
        'btn_link' => '/catalogo',
        'layout' => 'text_left',
        'image_path' => $imagePath
    ]
];
$heroSlidesJson = $mysqli->real_escape_string(json_encode($heroSlides));
$mysqli->query("UPDATE settings SET setting_value = '$heroSlidesJson' WHERE setting_key = 'home_hero_slides'");

echo 'RESTORED PERFECTLY';
