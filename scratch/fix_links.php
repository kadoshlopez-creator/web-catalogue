<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "web_catalogue");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$mysqli->query("UPDATE settings SET setting_value = REPLACE(setting_value, '/catalog', '/catalogo') WHERE setting_key IN ('navigation_menus', 'home_hero_slides', 'home_hero')");
$mysqli->query("UPDATE settings SET setting_value = REPLACE(setting_value, '/catalogoo', '/catalogo') WHERE setting_key IN ('navigation_menus', 'home_hero_slides', 'home_hero')");
echo "Updated DB Links";
