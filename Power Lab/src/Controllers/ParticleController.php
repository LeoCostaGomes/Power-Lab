<?php
namespace App\Controllers;

use App\Core\JsonResponse;
use App\Core\Request;
use App\Repositories\ParticleRepository;

class ParticleController
{
    public function __construct(private ParticleRepository $particleRepository) {}

    public function getAll(Request $request, array $params): void
    {
        $particles = $this->particleRepository->findAll();

        JsonResponse::send(array_values(array_map(fn ($p) => [
            'id' => $p->getId(),
            'name' => $p->getName(),
            'sprite' => $p->getSprite()->getBase64Src(),
        ], $particles)));
    }

    public function getById(Request $request, array $params): void
    {
        $particle = $this->particleRepository->findById((int) $params['id']);

        if ($particle === null) {
            JsonResponse::send(['error' => 'Ultimate não encontrada'], 404);
            return;
        }

        JsonResponse::send([
            'id' => $particle->getId(),
            'name' => $particle->getName(),
            'sprite' => $particle->getSprite()->getBase64Src(),
        ]);
    }
}