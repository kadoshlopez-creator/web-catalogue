<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "web_catalogue");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$res = $mysqli->query("SELECT setting_value FROM settings WHERE setting_key = 'navigation_menus'");
$row = $res->fetch_assoc();
$menus = json_decode($row['setting_value'], true);

// Add missing items to footer
$footerLinks = array_column($menus['footer']['items'], 'link');
if (!in_array('/p/sobre-nosotros', $footerLinks)) {
    $menus['footer']['items'][] = ['label' => 'Sobre Nosotros', 'link' => '/p/sobre-nosotros'];
}
if (!in_array('/p/contacto', $footerLinks)) {
    $menus['footer']['items'][] = ['label' => 'Contacto', 'link' => '/p/contacto'];
}

$newVal = $mysqli->real_escape_string(json_encode($menus));
$mysqli->query("UPDATE settings SET setting_value = '$newVal' WHERE setting_key = 'navigation_menus'");
echo 'Footer updated';
