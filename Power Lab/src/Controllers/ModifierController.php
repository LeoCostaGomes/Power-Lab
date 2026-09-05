<?php
namespace App\Controllers;

use App\Core\JsonResponse;
use App\Core\Request;
use App\Repositories\ModifierRepository;

class ModifierController
{
    public function __construct(private ModifierRepository $modifierRepository) {}

    public function getAll(Request $request, array $params): void
    {
        $modifiers = $this->modifierRepository->findAll();

        JsonResponse::send(array_values(array_map(fn ($m) => [
            'id' => $m->getId(),
            'name' => $m->getName(),
            'description' => $m->getDescription(),
            'sprite' => $m->getSpriteIcon()->getBase64Src(),
        ], $modifiers)));
    }

    public function getById(Request $request, array $params): void
    {
        $modifier = $this->modifierRepository->findById((int) $params['id']);

        if ($modifier === null) {
            JsonResponse::send(['error' => 'Modificador não encontrado'], 404);
            return;
        }

        JsonResponse::send([
            'id' => $modifier->getId(),
            'name' => $modifier->getName(),
            'description' => $modifier->getDescription(),
            'sprite' => $modifier->getSpriteIcon()->getBase64Src(),
        ]);
    }
}