<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "web_catalogue");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// 1. Restore Main Menu with Contacto page
$defaultMenu = [
    'main' => [
        'items' => [
            ['label' => 'Inicio', 'link' => '/'],
            ['label' => 'Catálogo', 'link' => '/catalog'],
            ['label' => 'Contacto', 'link' => '/p/contacto']
        ]
    ],
    'footer' => [
        'items' => [
            ['label' => 'Inicio', 'link' => '/'],
            ['label' => 'Catálogo', 'link' => '/catalog']
        ]
    ]
];
$menuJson = $mysqli->real_escape_string(json_encode($defaultMenu));
$mysqli->query("UPDATE settings SET setting_value = '$menuJson' WHERE setting_key = 'navigation_menus'");

// 2. Restore Hero Image
$q = $mysqli->query("SELECT setting_value FROM settings WHERE setting_key = 'home_hero'");
$oldHeroRow = $q->fetch_assoc();
$imagePath = '';
if ($oldHeroRow) {
    $oldHeroData = json_decode($oldHeroRow['setting_value'], true);
    $imagePath = $oldHeroData['image_path'] ?? '/uploads/hero/hero_1782762796_0.png'; // Fallback to an existing image
} else {
    $imagePath = '/uploads/hero/hero_1782762796_0.png';
}

$heroSlides = [
    [
        'title' => 'La mejor tecnología al mejor precio',
        'subtitle' => 'Descubre nuestra increíble selección de productos electrónicos de alta gama. Equipos listos para llevar tu productividad y entretenimiento al siguiente nivel.',
        'btn_text' => 'Ver Catálogo',
        'btn_link' => '/catalog',
        'layout' => 'text_left',
        'image_path' => $imagePath
    ]
];
$heroSlidesJson = $mysqli->real_escape_string(json_encode($heroSlides));
$mysqli->query("UPDATE settings SET setting_value = '$heroSlidesJson' WHERE setting_key = 'home_hero_slides'");

echo 'RESTORED';
