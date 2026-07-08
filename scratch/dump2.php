<?php
require 'vendor/autoload.php';
$db = App\Core\Database::getConnection();
$val = $db->query("SELECT setting_value FROM settings WHERE setting_key='custom_pages'")->fetchColumn();
file_put_contents('scratch/custom_pages_dump.json', $val);
