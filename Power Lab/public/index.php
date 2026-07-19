<?php

require_once __DIR__ . '/../autoloader.php';

use App\Repositories\TerritoryRepository;
use App\Repositories\PaddleRepository;
use App\Repositories\UltimateRepository;
use App\Repositories\ParticleRepository;
use App\Repositories\SkinRepository;
use App\Repositories\PaddleSkinRepository;
use App\Core\DataBase;

header('Content-Type: text/plain; charset=utf-8');

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

        // Confirma que o cache em memória devolve a MESMA instância,
        // não uma nova consulta ao banco
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
}