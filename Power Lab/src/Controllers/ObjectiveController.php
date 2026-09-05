<?php
namespace App\Controllers;

use App\Core\JsonResponse;
use App\Core\Request;
use App\Repositories\ObjectiveRepository;

class ObjectiveController
{
    public function __construct(private ObjectiveRepository $objectiveRepository) {}

    public function getAll(Request $request, array $params): void
    {
        $objectives = $this->objectiveRepository->findAll();

        JsonResponse::send(array_values(array_map(fn ($obj) => [
            'id' => $obj->getId(),
            'name' => $obj->getName(),
            'description' => $obj->getDescription()
        ], $objectives)));
    }

    public function getById(Request $request, array $params): void
    {
        $objective = $this->objectiveRepository->findById((int) $params['id']);

        if ($objective === null) {
            JsonResponse::send(['error' => 'Objetivo não encontrado'], 404);
            return;
        }

        JsonResponse::send([
            'id' => $objective->getId(),
            'name' => $objective->getName(),
            'description' => $objective->getDescription()
        ]);
    }
}