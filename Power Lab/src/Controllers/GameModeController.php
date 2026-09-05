<?php
namespace App\Controllers;

use App\Core\JsonResponse;
use App\Core\Request;
use App\Repositories\GameModeRepository;

class GameModeController
{
    public function __construct(private GameModeRepository $gameModeRepository) {}

    public function getAll(Request $request, array $params): void
    {
        $gameModes = $this->gameModeRepository->findAll();

        JsonResponse::send(array_values(array_map(fn ($gm) => [
            'id' => $gm->getId(),
            'name' => $gm->getName(),
            'description' => $gm->getDescription()
        ], $gameModes)));
    }

    public function getById(Request $request, array $params): void
    {
        $gameMode = $this->gameModeRepository->findById((int) $params['id']);

        if ($gameMode === null) {
            JsonResponse::send(['error' => 'Modo de jogo não encontrado'], 404);
            return;
        }

        JsonResponse::send([
            'id' => $gameMode->getId(),
            'name' => $gameMode->getName(),
            'description' => $gameMode->getDescription()
        ]);
    }
}