<?php

require_once __DIR__ . '/../autoloader.php';

use App\Repositories\TerritoryRepository;
use App\Repositories\PaddleRepository;
use App\Repositories\UltimateRepository;
use App\Repositories\ParticleRepository;
use App\Repositories\SkinRepository;
use App\Repositories\PaddleSkinRepository;
use App\Core\DataBase;
use App\Models\Image;

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

    $paddleRepository = new PaddleRepository($territoryRepository);
    echo "Paddle: " . count($paddleRepository->findAll()) . " carregados\n";

    $ultimateRepository = new UltimateRepository($territoryRepository);
    echo "Ultimate: " . count($ultimateRepository->findAll()) . " carregados\n";

    $particleRepository = new ParticleRepository();
    echo "Particle: " . count($particleRepository->findAll()) . " carregados\n";

    $skinRepository = new SkinRepository();
    echo "Skin: " . count($skinRepository->findAll()) . " carregados\n";

    $paddleSkinRepository = new PaddleSkinRepository($skinRepository, $paddleRepository);
    $totalPaddleSkins = array_sum(array_map('count', $paddleSkinRepository->findAll()));
    echo "PaddleSkin: {$totalPaddleSkins} combinações carregadas\n";

    echo "\n== Checando relações ==\n";

    $paddles = $paddleRepository->findAll();
    $firstPaddle = reset($paddles) ?: null;

    if ($firstPaddle !== null) {
        echo "Paddle #{$firstPaddle->getId()}: {$firstPaddle->getName()}\n";
        echo "  Territory: {$firstPaddle->getNameTerritory()}\n";

        try {
            echo "  Stage 1: {$firstPaddle->getDescriptionOfStage(1)}\n";
        } catch (\InvalidArgumentException $e) {
            echo "  Stage 1: (sem descrição ainda)\n";
        }

        $sameId = $firstPaddle->getId();
        $foundAgain = $paddleRepository->findById($sameId);
        echo "  findById({$sameId}) é a mesma instância do findAll()? "
            . ($foundAgain === $firstPaddle ? 'sim' : 'NÃO') . "\n";
    } else {
        echo "Nenhum Paddle carregado — confira se tb_paddle tem linhas.\n";
    }

    $ultimates = $ultimateRepository->findAll();
    $firstUltimate = reset($ultimates) ?: null;

    if ($firstUltimate !== null) {
        echo "Ultimate #{$firstUltimate->getId()}: {$firstUltimate->getName()}\n";
        echo "  Territory: {$firstUltimate->getNameTerritory()}\n";
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

echo "<h3>Particles (sprite + gif)</h3>";
$count = 0;
foreach ($particleRepository->findAll() as $particle) {
    echo renderImage($particle->getSprite(), "Particle #{$particle->getId()} sprite");
    echo renderImage($particle->getGif(), "Particle #{$particle->getId()} gif");
}

echo "<h3>Ultimates (spriteIcon)</h3>";
$count = 0;
foreach ($ultimateRepository->findAll() as $ultimate) {
    echo renderImage($ultimate->getSpriteIcon(), "Ultimate #{$ultimate->getId()}: {$ultimate->getName()}");
}

echo "<h3>Paddle + Skin</h3>";
$count = 0;
foreach ($paddleSkinRepository->findAll() as $paddleId => $skins) {
    foreach ($skins as $skinId => $image) {
        echo renderImage($image, "Paddle #{$paddleId} + Skin #{$skinId}");
    }
}