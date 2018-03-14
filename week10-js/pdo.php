<?php

$pdo = new PDO('mysql:host=sql205.byethost33.com;port=3306;dbname=b33_21360817_play', 
   'b33_21360817', '20092012sjzhao');
// $pdo = new PDO('mysql:host=localhost;port=8889;dbname=misc', 
//    'fred', 'zap');
// See the "errors" folder for details...
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



