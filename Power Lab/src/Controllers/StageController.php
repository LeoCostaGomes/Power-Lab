<?php
namespace App\Controllers;

use App\Core\JsonResponse;
use App\Core\Request;
use App\Models\Paddle;
use App\Repositories\StageRepository;
use App\Models\Stage;

class StageController
{
    public function __construct(private StageRepository $stageRepository) {}

    public function getAll(Request $request, array $params): void
    {
        $stages = $this->stageRepository->findAll();
        JsonResponse::send(array_values(array_map([$this, 'formatStage'], $stages)));
    }

    public function getById(Request $request, array $params): void
    {
        $stage = $this->stageRepository->findById((int) $params['id']);

        if ($stage === null) {
            JsonResponse::send(['error' => 'Stage não encontrado'], 404);
            return;
        }

        JsonResponse::send($this->formatStage($stage));
    }

    private function formatStage(Stage $stage): array
    {
        return [
            'id' => $stage->getId(),
            'name' => $stage->getNameStage(),
            'paddleBot' => $this->formatPaddleBot($stage->getPaddleBot()),
            'territory' => $stage->getNameTerritory(),
            'difficulty' => $stage->getNameDifficulty(),
            'enemyType' => $stage->getNameEnemyType(),
            'sprite' => $stage->getRewardStage()->getRewardSprite()
        ];
    }

    private function formatPaddleBot(Paddle $paddleBot): array
    {
        return [
            'id' => $paddleBot->getId(),
            'name' => $paddleBot->getName(),
            //'sprite' => $paddleBot->getSprite()->getBase64Src(),
        ];
    }
}