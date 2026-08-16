<?php
    namespace App\Tests;

require_once __DIR__ . '/../../autoloader.php';

use App\Repositories\ItemCategoryRepository;
use App\Core\DataBase;
use PDO;

header('Content-Type: text/html; charset=utf-8');

echo "<pre>";

$pdo = DataBase::getInstance();

echo "Conexão aberta com sucesso.\n";
echo "Servidor: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";

$stmt = $pdo->query('SELECT DATABASE() AS db_atual');
$row = $stmt->fetch();
echo "Banco conectado: {$row['db_atual']}\n";

try {
    echo "== Carregando repositories ==\n";

    $itemCategoryRepository = new ItemCategoryRepository();
    echo "ItemCategory: " . count($itemCategoryRepository->findAll()) . " carregados\n";

    echo "\n== Checando relações ==\n";

    $categories = $itemCategoryRepository->findAll();

    if ($categories !== null) {
    foreach ($categories as $category) {

        echo "ItemCategory #{$category->getId()}: {$category->getName()}\n";
    }
    } else {
        echo "Nenhuma ItemCategory carregada — confira se tb_item_category tem linhas.\n";
    }

    echo "\n== Tudo certo ==\n";
} catch (\Throwable $e) {
    echo "\n== FALHOU ==\n";
    echo get_class($e) . ": " . $e->getMessage() . "\n";
    echo "</pre>";
    exit;
}

echo "</pre>";
?>