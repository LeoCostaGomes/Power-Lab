<?php
namespace App\Controllers;

use App\Core\JsonResponse;
use App\Core\Request;
use App\Repositories\PaddleRepository;

class PaddleController
{
    public function __construct(private PaddleRepository $paddleRepository)
    {
    }

    public function getAll(Request $request, array $params): void
    {
        $paddles = $this->paddleRepository->findAll();
        JsonResponse::send(array_map(fn($p) => ['id' => $p->getId(), 'name' => $p->getName(), 'descriptions' => $p->getAllDescriptions(), 'territory' => $p->getNameTerritory()], $paddles));
    }

    public function getById(Request $request, array $params): void
    {
        $paddle = $this->paddleRepository->findById((int) $params['id']);

        if ($paddle === null) {
            JsonResponse::send(['error' => 'Paddle não encontrado'], 404);
            return;
        }

        JsonResponse::send(['id' => $paddle->getId(), 'name' => $paddle->getName(), 'descriptions' => $paddle->getAllDescriptions(), 'territory' => $paddle->getNameTerritory()]);
    }
}
?>