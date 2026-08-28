<?php
namespace App\Controllers;

use App\Core\JsonResponse;
use App\Core\Request;
use App\Repositories\SkinRepository;

class SkinController
{
    public function __construct(private SkinRepository $skinRepository) {}

    public function getAll(Request $request, array $params): void
    {
        $skins = $this->skinRepository->findAll();

        JsonResponse::send(array_values(array_map(fn ($s) => [
            'id' => $s->getId(),
            'name' => $s->getName(),
        ], $skins)));
    }

    public function getById(Request $request, array $params): void
    {
        $skin = $this->skinRepository->findById((int) $params['id']);

        if ($skin === null) {
            JsonResponse::send(['error' => 'Skin não encontrada'], 404);
            return;
        }

        JsonResponse::send([
            'id' => $skin->getId(),
            'name' => $skin->getName(),
        ]);
    }
}