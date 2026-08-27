<?php

use App\Controllers\PaddleController;
use App\Controllers\ParticleController;
use App\Controllers\UltimateController;
use App\Repositories\TerritoryRepository;
use App\Repositories\PaddleRepository;
use App\Repositories\UltimateRepository;
use App\Repositories\SkinRepository;
use App\Repositories\ParticleRepository;
use App\Repositories\PaddleSkinRepository;
use App\Repositories\ItemCategoryRepository;
use App\Repositories\BoxTypeRepository;
use App\Repositories\DifficultyRepository;
use App\Repositories\ObjectiveRepository;
use App\Repositories\EnemyTypeRepository;
use App\Repositories\ModifierRepository;
use App\Repositories\StageRepository;
use App\Repositories\UserRepository;
use App\Factories\ItemTypeFactory;
use App\Core\Router;
use App\Core\Request;

// ---- Repositories sem dependência ----
$territoryRepository = new TerritoryRepository();
$skinRepository = new SkinRepository();
$particleRepository = new ParticleRepository();
$itemCategoryRepository = new ItemCategoryRepository();
$difficultyRepository = new DifficultyRepository();
$objectiveRepository = new ObjectiveRepository();
$enemyTypeRepository = new EnemyTypeRepository();
$modifierRepository = new ModifierRepository();
$userRepository = new UserRepository();

// ---- Dependem das de cima ----
$paddleRepository = new PaddleRepository($territoryRepository);
$ultimateRepository = new UltimateRepository($territoryRepository);
$boxTypeRepository = new BoxTypeRepository($itemCategoryRepository);

// ---- Dependem de duas ou mais ----
$paddleSkinRepository = new PaddleSkinRepository($skinRepository, $paddleRepository);

$itemTypeFactory = new ItemTypeFactory(
    $boxTypeRepository,
    $paddleRepository,
    $ultimateRepository,
    $skinRepository,
    $particleRepository
);

// ---- Stage, que depende de quase tudo ----
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

// ---- Controllers, já com as Repositories que cada um precisa ----
$paddleController = new PaddleController($paddleRepository);
$ultimateController = new UltimateController($ultimateRepository);
$particleController = new ParticleController($particleRepository);

// ---- Rotas ----
$router = new Router();

$router->get('/paddles', [$paddleController, 'getAll']);
$router->get('/paddles/{id}', [$paddleController, 'getById']);

$router->get('/ultimates', [$ultimateController, 'getAll']);
$router->get('/ultimates/{id}', [$ultimateController, 'getById']);

$router->get('/particle', [$particleController, 'getAll']);
$router->get('/particle/{id}', [$particleController, 'getById']);

//$router->post('/users', [$userController, 'create']);
//$router->put('/users/{id}', [$userController, 'update']);
//$router->delete('/users/{id}', [$userController, 'delete']);

// ---- Despacha a requisição ----
try {
    $router->dispatch(new Request());
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Erro interno no servidor']);
    // Loga o erro de verdade em algum lugar que só você vê (arquivo de log, por exemplo) —
    // nunca devolve $e->getMessage() puro na resposta, mesma lógica do die() do DataBase.php.
}