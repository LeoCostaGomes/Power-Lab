<?php
namespace App\Controllers;

use App\Core\JsonResponse;
use App\Core\Request;
use App\Repositories\PaddleSkinRepository;

class PaddleSkinController
{
    public function __construct(private PaddleSkinRepository $paddleSkinRepository) {}

    public function getAll(Request $request, array $params): void
    {
        $result = [];

        foreach ($this->paddleSkinRepository->findAll() as $paddleId => $skins) {
            foreach ($skins as $skinId => $image) {
                $result[] = [
                    'paddleId' => $paddleId,
                    'skinId' => $skinId,
                    'sprite' => $image->getBase64Src(),
                ];
            }
        }

        JsonResponse::send($result);
    }

    public function getById(Request $request, array $params): void
    {
        $paddleId = (int) $params['paddleId'];
        $skinId = (int) $params['skinId'];

        $image = $this->paddleSkinRepository->findById($paddleId, $skinId);

        if ($image === null) {
            JsonResponse::send(['error' => 'Combinação de raquete e skin não encontrada'], 404);
            return;
        }

        JsonResponse::send([
            'paddleId' => $paddleId,
            'skinId' => $skinId,
            'sprite' => $image->getBase64Src(),
        ]);
    }

    public function getAllSkinsFromPaddleById(Request $request, array $params): void
    {
        $paddleId = (int) $params['paddleId'];

        // ?? [] evita "Undefined array key" se o paddleId não existir
        // ou se essa raquete simplesmente não tiver nenhuma skin ainda.
        $skins = $this->paddleSkinRepository->findAll()[$paddleId] ?? [];

        $result = [];
        foreach ($skins as $skinId => $image) {
            $result[] = [
                'skinId' => $skinId,
                'sprite' => $image->getBase64Src(),
            ];
        }

        JsonResponse::send($result);
    }

    public function getAllPaddlesFromSkinById(Request $request, array $params): void
    {
        $skinId = (int) $params['skinId'];

        // Não tem atalho direto: a estrutura é [paddleId][skinId], então
        // "todas as raquetes com essa skin" precisa varrer todas as raquetes
        // e checar se esse skinId aparece em cada uma.
        $result = [];
        foreach ($this->paddleSkinRepository->findAll() as $paddleId => $skins) {
            if (isset($skins[$skinId])) {
                $result[] = [
                    'paddleId' => $paddleId,
                    'sprite' => $skins[$skinId]->getBase64Src(),
                ];
            }
        }

        JsonResponse::send($result);
    }
}