<?php
namespace App\Controllers;

use App\Core\JsonResponse;
use App\Core\Request;
use App\Repositories\UltimateRepository;

class UltimateController
{
    public function __construct(private UltimateRepository $ultimateRepository) {}

    public function getAll(Request $request, array $params): void
    {
        $ultimates = $this->ultimateRepository->findAll();

        JsonResponse::send(array_values(array_map(fn ($u) => [
            'id' => $u->getId(),
            'name' => $u->getName(),
            'description' => $u->getDescription(),
            'sprite' => $u->getSpriteIcon()->getBase64Src(),
            'territory' => $u->getNameTerritory(),
        ], $ultimates)));
    }

    public function getById(Request $request, array $params): void
    {
        $ultimate = $this->ultimateRepository->findById((int) $params['id']);

        if ($ultimate === null) {
            JsonResponse::send(['error' => 'Ultimate não encontrada'], 404);
            return;
        }

        JsonResponse::send([
            'id' => $ultimate->getId(),
            'name' => $ultimate->getName(),
            'description' => $ultimate->getDescription(),
            'sprite' => $ultimate->getSpriteIcon()->getBase64Src(),
            'territory' => $ultimate->getNameTerritory(),
        ]);
    }
}