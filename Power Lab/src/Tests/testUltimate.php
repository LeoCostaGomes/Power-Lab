<?php
    namespace App\Tests;

require_once __DIR__ . '/../../autoloader.php';

use App\Repositories\TerritoryRepository;
use App\Repositories\UltimateRepository;
use App\Core\DataBase;
use App\Models\Image;
use PDO;

header('Content-Type: text/html; charset=utf-8');

function renderImage(Image $image, string $label): string
{
    $src = $image->getBase64Src();
    $label = htmlspecialchars($label);

    return "<div style=\"display:inline-block;margin:8px;text-align:center;font-family:monospace;font-size:12px;vertical-align:top;\">"
        . "<img src=\"{$src}\" style=\"max-width:120px;max-height:120px;border:1px solid #ccc;display:block;margin:0 auto 4px;\">"
        . $label
        . "</div>";
}

echo "<pre>";

$pdo = DataBase::getInstance();

echo "Conexão aberta com sucesso.\n";
echo "Servidor: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";

$stmt = $pdo->query('SELECT DATABASE() AS db_atual');
$row = $stmt->fetch();
echo "Banco conectado: {$row['db_atual']}\n";

try {
    echo "== Carregando repositories ==\n";

    $territoryRepository = new TerritoryRepository();
    echo "Territory: " . count($territoryRepository->findAll()) . " carregados\n";

    $ultimateRepository = new UltimateRepository($territoryRepository);
    echo "Ultimate: " . count($ultimateRepository->findAll()) . " carregados\n";

    echo "\n== Checando relações ==\n";

    $ultimates = $ultimateRepository->findAll();

    if ($ultimates !== null) {
    foreach ($ultimates as $ultimate) {

        echo "Ultimate #{$ultimate->getId()}: {$ultimate->getName()}\n";
        echo "  Descrição: {$ultimate->getDescription()}\n";
        echo "  Territory: {$ultimate->getNameTerritory()}\n";
    }
    } else {
        echo "Nenhum Ultimate carregado — confira se tb_ultimate tem linhas.\n";
    }

    echo "\n== Tudo certo ==\n";
} catch (\Throwable $e) {
    echo "\n== FALHOU ==\n";
    echo get_class($e) . ": " . $e->getMessage() . "\n";
    echo "</pre>";
    exit;
}

echo "</pre>";

echo "<h2>Imagens carregadas</h2>";

echo "<h3>Ultimates</h3>";
foreach ($ultimateRepository->findAll() as $ultimate) {
    echo renderImage($ultimate->getSpriteIcon(), "Ultimate #{$ultimate->getId()}: {$ultimate->getName()}");
}
?>