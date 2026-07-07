<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "web_catalogue");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$res = $mysqli->query("SELECT setting_value FROM settings WHERE setting_key = 'navigation_menus'");
$row = $res->fetch_assoc();
echo $row['setting_value'];
