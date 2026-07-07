<?php
require 'app/Core/Database.php';
\ = require 'config/database.php';
\ = 'mysql:host='.\['host'].';port='.\['port'].';dbname='.\['database'].';charset='.\['charset'];
\ = new PDO(\, \['username'], \['password']);
\ = \->query('SHOW COLUMNS FROM offers');
print_r(\->fetchAll(PDO::FETCH_ASSOC));
