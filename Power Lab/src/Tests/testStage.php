<?php
    namespace App\Tests;

require_once __DIR__ . '/../../autoloader.php';

use App\Repositories\TerritoryRepository;
use App\Repositories\PaddleRepository;
use App\Repositories\UltimateRepository;
use App\Repositories\SkinRepository;
use App\Repositories\ParticleRepository;
use App\Repositories\ItemCategoryRepository;
use App\Repositories\BoxTypeRepository;
use App\Repositories\DifficultyRepository;
use App\Repositories\ObjectiveRepository;
use App\Repositories\EnemyTypeRepository;
use App\Repositories\ModifierRepository;
use App\Repositories\StageRepository;
use App\Factories\ItemTypeFactory;
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
    echo "== Carregando repositories (na ordem de dependência) ==\n";

    $territoryRepository = new TerritoryRepository();
    echo "Territory: " . count($territoryRepository->findAll()) . " carregados\n";

    $paddleRepository = new PaddleRepository($territoryRepository);
    echo "Paddle: " . count($paddleRepository->findAll()) . " carregados\n";

    $ultimateRepository = new UltimateRepository($territoryRepository);
    echo "Ultimate: " . count($ultimateRepository->findAll()) . " carregados\n";

    $skinRepository = new SkinRepository();
    echo "Skin: " . count($skinRepository->findAll()) . " carregados\n";

    $particleRepository = new ParticleRepository();
    echo "Particle: " . count($particleRepository->findAll()) . " carregados\n";

    $itemCategoryRepository = new ItemCategoryRepository();
    echo "ItemCategory: " . count($itemCategoryRepository->findAll()) . " carregados\n";

    $boxTypeRepository = new BoxTypeRepository($itemCategoryRepository);
    echo "BoxType: " . count($boxTypeRepository->findAll()) . " carregados\n";

    $difficultyRepository = new DifficultyRepository();
    echo "Difficulty: " . count($difficultyRepository->findAll()) . " carregados\n";

    $objectiveRepository = new ObjectiveRepository();
    echo "Objective: " . count($objectiveRepository->findAll()) . " carregados\n";

    $enemyTypeRepository = new EnemyTypeRepository();
    echo "EnemyType: " . count($enemyTypeRepository->findAll()) . " carregados\n";

    $modifierRepository = new ModifierRepository();
    echo "Modifier: " . count($modifierRepository->findAll()) . " carregados\n";

    // Passando os 5 repositories sempre, sem contar com os defaults null
    // (ver bug de tipagem explicado no chat).
    $itemTypeFactory = new ItemTypeFactory(
        $boxTypeRepository
    );

    $stageRepository = new StageRepository(
        $territoryRepository,
        $paddleRepository,
        $ultimateRepository,
        $skinRepository,
        $particleRepository,
        $difficultyRepository,
        $objectiveRepository,
        $enemyTypeRepository,
        $itemTypeFactory,
        $modifierRepository
    );
    echo "Stage: " . count($stageRepository->findAll()) . " carregados\n";

    echo "\n== Checando relações ==\n";

    $stages = $stageRepository->findAll();

    if ($stages !== null) {
    foreach ($stages as $stage) {

        echo "Stage #{$stage->getId()}: {$stage->getNameStage()}\n";
        echo "  Territory: {$stage->getNameTerritory()}\n";
        echo "  Difficulty: {$stage->getNameDifficulty()}\n";
        echo "  EnemyType: {$stage->getNameEnemyType()}\n";
        echo "  Paddle bot: {$stage->getPaddleBot()->getName()} (stage {$stage->getPaddleStage()})\n";
        echo "  Ultimate bot: {$stage->getUltimateBot()->getName()}\n";
        echo "  Skin bot: {$stage->getSkinBot()->getName()}\n";
        echo "  Particle bot: {$stage->getParticleBot()->getName()}\n";
        echo "  Objective: {$stage->getObjective()->getName()} (qtd {$stage->getObjectiveQuantity()})\n";
        echo "  Reward: {$stage->getRewardStage()->getRewardText()} (qtd {$stage->getRewardStageQuantity()})\n";

        echo "  Modifiers:\n";
        foreach ($stage->getModifiers() as $i => $modifier) {
            $nome = $modifier?->getName() ?? '(nenhum)';
            echo "    " . ($i + 1) . ". {$nome}\n";
        }
        echo "\n";
    }
    } else {
        echo "Nenhum Stage carregado — confira se tb_stage tem linhas.\n";
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

foreach ($stageRepository->findAll() as $stage) {
    echo "<h3>Stage #{$stage->getId()}: " . htmlspecialchars($stage->getNameStage()) . "</h3>";

    echo renderImage($stage->getRewardStage()->getRewardSprite(), "Reward: {$stage->getRewardStage()->getRewardText()}");

    foreach ($stage->getModifiers() as $modifier) {
        if ($modifier !== null) {
            echo renderImage($modifier->getSpriteIcon(), "Modifier: {$modifier->getName()}");
        }
    }
}
?>