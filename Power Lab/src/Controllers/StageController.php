<?php

namespace App\Controllers;

use App\Core\JsonResponse;
use App\Core\Request;
use App\Models\Paddle;
use App\Models\Skin;
use App\Models\Ultimate;
use App\Models\Particle;
use App\Models\Objective;
use App\Models\Modifier;
use App\Models\Stage;
use App\Repositories\StageRepository;
use App\Repositories\PaddleSkinRepository;
use InvalidArgumentException;

class StageController
{
    public function __construct(
        private StageRepository $stageRepository,
        private PaddleSkinRepository $paddleSkinRepository
    ) {}

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
            'paddleBot' => $this->formatPaddleBot(
                $stage->getPaddleBot(),
                $stage->getPaddleStage()
            ),
            'ultimateBot' => $this->formatUltimate($stage->getUltimateBot()),
            'skinBot' => $this->formatSkin($stage->getSkinBot(), $stage->getPaddleBot()->getId()),
            'particleBot' => $this->formatParticle($stage->getParticleBot()),
            'territory' => $stage->getNameTerritory(),
            'difficulty' => $stage->getNameDifficulty(),
            'enemyType' => $stage->getNameEnemyType(),
            'objective' => $this->formatObjective($stage->getObjective(), $stage->getObjectiveQuantity()),
            'reward' => [
                'text' => $stage->getRewardStage()->getRewardText(),
                'quantity' => $stage->getRewardStageQuantity(),
                'sprite' => $stage->getRewardStage()->getRewardSprite()->getBase64Src(),
            ],
            'modifiers' => array_map([$this, 'formatModifier'], $stage->getModifiers()),
        ];
    }

    private function formatPaddleBot(Paddle $paddleBot, int $paddleStageNumber): array
    {
        // Nem toda raquete tem descrição pra todos os 1-5 estágios ainda
        // (lembra do "ainda sendo lançadas aos poucos") -- por isso o try/catch
        // em vez de deixar a InvalidArgumentException vazar pra resposta da API.
        try {
            $stageDescription = $paddleBot->getDescriptionOfStage($paddleStageNumber);
        } catch (InvalidArgumentException $e) {
            $stageDescription = null;
        }

        return [
            'id' => $paddleBot->getId(),
            'name' => $paddleBot->getName(),
            'stageNumber' => $paddleStageNumber,
            'stageDescription' => $stageDescription
        ];
    }

    private function formatUltimate(Ultimate | null $ultimate): array | null
    {
        if ($ultimate === null) {
            return null;
        }
        return [
            'id' => $ultimate->getId(),
            'name' => $ultimate->getName(),
            'description' => $ultimate->getDescription(),
            'sprite' => $ultimate->getSpriteIcon()->getBase64Src(),
        ];
    }

    private function formatSkin(Skin $skinBot, int $idPaddleBot): array
    {
        $skinSprite = $this->paddleSkinRepository->findById($idPaddleBot, $skinBot->getId());

        return [
            'id' => $skinBot->getId(),
            'name' => $skinBot->getName(),
            'sprite' => $skinSprite?->getBase64Src(),
        ];
    }

    private function formatParticle(Particle | null $particle): array | null
    {
        if ($particle === null) {
            return null;
        }

        return [
            'id' => $particle->getId(),
            'name' => $particle->getName(),
            'sprite' => $particle->getSprite()->getBase64Src(),
        ];
    }

    private function formatObjective(Objective $objective, int $quantity): array
    {
        $quantityText = $quantity;
        if ($objective->getId() === 1) {
            $quantityText = $this->formatTime($quantity);
        }

        return [
            'id' => $objective->getId(),
            'name' => $objective->getName(),
            'description' => $objective->getDescription(),
            'quantity' => $quantityText,
        ];
    }

    private function formatTime(int $segundos): string
    {
        $minutos = intdiv($segundos, 60);
        $segundosRestantes = $segundos % 60;

        return "{$minutos}:" . str_pad($segundosRestantes, 2, "0", STR_PAD_LEFT);
    }

    private function formatModifier(?Modifier $modifier): ?array
    {
        if ($modifier === null) {
            return null;
        }

        return [
            'id' => $modifier->getId(),
            'name' => $modifier->getName(),
            'description' => $modifier->getDescription(),
            'sprite' => $modifier->getSpriteIcon()->getBase64Src(),
        ];
    }
}
