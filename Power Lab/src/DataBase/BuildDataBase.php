<?php
require_once __DIR__ . '/../../autoloader.php';

use App\Core\DataBase;

$pdo = DataBase::getInstance();
$seedsDir = __DIR__ . '/seeds';

$reset = file_get_contents(__DIR__ . '/refresh.sql');
$pdo->exec($reset);

$pdo->exec("USE powerlab");

$schema = file_get_contents(__DIR__ . '/schema.sql');
$pdo->exec($schema);

foreach (glob("{$seedsDir}/*.sql") as $file) {
    echo "Rodando " . basename($file) . "...\n";
    $sql = file_get_contents($file);
    $pdo->exec($sql);
}

echo "Pronto.\n";

//   /c/xampp/php/php.exe "/c/xampp/htdocs/Power Lab/Power-Lab/Power Lab/src/DataBase/BuildDataBase.php"
?>