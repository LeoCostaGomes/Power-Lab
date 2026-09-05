<?php
namespace App\Controllers;

use App\Core\JsonResponse;
use App\Core\Request;
use App\Repositories\GameVersionRepository;

class GameVersionController
{
    public function __construct(private GameVersionRepository $gameVersionRepository) {}

    public function getAll(Request $request, array $params): void
    {
        $gameVersions = $this->gameVersionRepository->findAll();

        JsonResponse::send(array_values(array_map(fn ($version) => [
            'id' => $version->getId(),
            'versionCode' => $version->getVersionCode(),
            'changeLog' => $version->getChangelog()
        ], $gameVersions)));
    }

    public function getById(Request $request, array $params): void
    {
        $gameVersion = $this->gameVersionRepository->findById((int) $params['id']);

        if ($gameVersion === null) {
            JsonResponse::send(['error' => 'Versão do jogo não encontrada'], 404);
            return;
        }

        JsonResponse::send([
            'id' => $gameVersion->getId(),
            'versionCode' => $gameVersion->getVersionCode(),
            'changeLog' => $gameVersion->getChangelog()
        ]);
    }
}