<?php
namespace App\Controllers;

use App\Core\JsonResponse;
use App\Core\Request;
use App\Repositories\BoxTypeRepository;
use App\Models\BoxType;

class BoxTypeController
{
    public function __construct(private BoxTypeRepository $boxTypeRepository) {}

    public function getAll(Request $request, array $params): void
    {
        $boxes = $this->boxTypeRepository->findAll();
        JsonResponse::send(array_values(array_map([$this, 'formatBox'], $boxes)));
    }

    public function getById(Request $request, array $params): void
    {
        $box = $this->boxTypeRepository->findById((int) $params['id']);

        if ($box === null) {
            JsonResponse::send(['error' => 'Caixa não encontrada'], 404);
            return;
        }

        JsonResponse::send($this->formatBox($box));
    }

    private function formatBox(BoxType $box): array
    {
        $rewards = [];
        foreach ($box->getRealChanceOfEachItem() as $chance) {
            if (isset($chance["minQuantity"]) && isset($chance["maxQuantity"]))
            {
                $rewards[] = [
                    'category' => $chance['itemCategory']->getName(),
                    'chancePercent' => round($chance['realChance'], 2),
                    'minQuantity' => $chance['minQuantity'],
                    'maxQuantity' => $chance['maxQuantity'],
                ];
            } else {
                $rewards[] = [
                    'category' => $chance['itemCategory']->getName(),
                    'chancePercent' => round($chance['realChance'], 2),
                ];
            }
        }

        return [
            'id' => $box->getId(),
            'name' => $box->getName(),
            'sprite' => $box->getBoxIcon()->getBase64Src(),
            'rewards' => $rewards,
        ];
    }
}