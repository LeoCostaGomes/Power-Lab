<?php
    namespace App\Tests;

require_once __DIR__ . '/../../autoloader.php';

use App\Repositories\ItemCategoryRepository;
use App\Repositories\BoxTypeRepository;
use App\Core\DataBase;
use App\Models\Image;
use App\Models\RewardBoxWithVariableQuantity;
use PDO;

header('Content-Type: text/html; charset=utf-8');

function renderImage(Image $image, string $label): string
{
    $src = $image->getBase64Src();
    $label = htmlspecialchars($label);

    return "<div style=\"display:inline-block;margin:8px;text-align:center;font-family:monospace;font-size:12px;vertical-align:top;\">"
        . "<img src=\"{$src}\" style=\"width:120px;height:120px;border:1px solid #ccc;display:block;margin:0 auto 4px;\">"
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

    $itemCategoryRepository = new ItemCategoryRepository();
    echo "ItemCategory: " . count($itemCategoryRepository->findAll()) . " carregados\n";

    $boxTypeRepository = new BoxTypeRepository($itemCategoryRepository);
    echo "BoxType: " . count($boxTypeRepository->findAll()) . " carregados\n";

    echo "\n== Checando relações ==\n";

    $boxes = $boxTypeRepository->findAll();

    if ($boxes !== null) {
    foreach ($boxes as $box) {

        echo "BoxType #{$box->getId()}: {$box->getName()}\n";

        foreach ($box->getRewardBoxes() as $rewardBox) {
            if ($rewardBox instanceof RewardBoxWithVariableQuantity) {
                echo "  - {$rewardBox->getItemCategory()->getName()}"
                    . " (qtd {$rewardBox->getMinQuantity()}-{$rewardBox->getMaxQuantity()},"
                    . " peso {$rewardBox->getWeightChance()})\n";
            } else {
                echo "  - {$rewardBox->getItemCategory()->getName()}"
                    . " (peso {$rewardBox->getWeightChance()})\n";
            }
        }

        echo "\n  Chance real de cada item:\n";
        foreach ($box->getRealChanceOfEachItem() as $chance) {
            $nome = $chance["itemCategory"]->getName();
            $percentual = number_format($chance["realChance"], 2);
            if (isset($chance["minQuantity"]) && isset($chance["maxQuantity"]))
                echo "  - {$nome} (qtd {$chance["minQuantity"]}-{$chance["maxQuantity"]}): {$percentual}%\n";
            else
                echo "  - {$nome}: {$percentual}%\n";
        }
        echo "\n";
    }
    } else {
        echo "Nenhum BoxType carregado — confira se tb_box_type tem linhas.\n";
    }

    echo "\n== Tudo certo ==\n";
} catch (\Throwable $e) {
    echo "\n== FALHOU ==\n";
    echo get_class($e) . ": " . $e->getMessage() . "\n";
    echo "</pre>";
    exit;
}

echo "</pre>";

echo "<h2>Ícones carregados</h2>";

foreach ($boxTypeRepository->findAll() as $box) {
    echo renderImage($box->getBoxIcon(), "BoxType #{$box->getId()}: {$box->getName()}");
}
?>