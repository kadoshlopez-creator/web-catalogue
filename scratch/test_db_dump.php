<?php
require 'public/index.php';
$db = \Config\Database::connect();

$q1 = $db->query("SELECT setting_value FROM settings WHERE setting_key = 'navigation_menus'");
$r1 = $q1->getRow();
echo "NAVIGATION MENUS:\n";
echo $r1 ? $r1->setting_value : 'NOT FOUND';
echo "\n-----------------------\n";

$q2 = $db->query("SELECT setting_value FROM settings WHERE setting_key = 'home_hero_slides'");
$r2 = $q2->getRow();
echo "HERO SLIDES:\n";
echo $r2 ? $r2->setting_value : 'NOT FOUND';
echo "\n-----------------------\n";
