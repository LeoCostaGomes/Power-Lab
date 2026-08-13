<?php

namespace App\Tests;

require_once __DIR__ . '/../../autoloader.php';

use App\Repositories\ModifierRepository;
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
    echo "== Carregando repository ==\n";

    $modifierRepository = new ModifierRepository();

    $modifiers = $modifierRepository->findAll();

    echo "Modifier: " . count($modifiers) . " carregados\n";

    echo "\n== Checando Modifiers ==\n";

    if ($modifiers !== null && count($modifiers) > 0) {

        foreach ($modifiers as $modifier) {

            echo "Modifier #{$modifier->getId()}: {$modifier->getName()}\n";
            echo "  Descrição: {$modifier->getDescription()}\n";
            echo "  Sprite: carregado com sucesso\n";
            echo "\n";
        }

    } else {
        echo "Nenhum Modifier carregado — confira se a tabela possui linhas.\n";
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

echo "<h3>Modifiers</h3>";

foreach ($modifierRepository->findAll() as $modifier) {

    echo renderImage(
        $modifier->getSpriteIcon(),
        "Modifier #{$modifier->getId()}: {$modifier->getName()}"
    );
}
?>