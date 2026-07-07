<?php
require 'app/Core/Database.php';
$config = require 'config/database.php';
$dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
$pdo = new PDO($dsn, $config['username'], $config['password']);
$stmt = $pdo->query('SHOW COLUMNS FROM offer_targets');
$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($cols as $c) echo $c['Field'] . ' -> ' . $c['Type'] . "\n";
