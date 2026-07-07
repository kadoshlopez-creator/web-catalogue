<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'web_catalogue');
if ($mysqli->connect_error) die('Connection failed');

$res = $mysqli->query("SELECT setting_value FROM settings WHERE setting_key = 'custom_pages'");
$row = $res->fetch_assoc();
if ($row) {
    $pages = json_decode($row['setting_value'], true);
    if(is_array($pages)) {
        foreach($pages as &$p) {
            if(isset($p['slug'])) {
                $p['slug'] = ltrim($p['slug'], '/');
            }
        }
        $newVal = $mysqli->real_escape_string(json_encode($pages));
        $mysqli->query("UPDATE settings SET setting_value = '$newVal' WHERE setting_key = 'custom_pages'");
        echo 'Updated custom_pages slugs.';
    }
}
