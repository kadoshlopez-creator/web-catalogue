<?php
require 'vendor/autoload.php';
$db = App\Core\Database::getConnection();
$val = $db->query("SELECT setting_value FROM settings WHERE setting_key='footer_settings'")->fetchColumn();
print_r(json_decode($val, true));
