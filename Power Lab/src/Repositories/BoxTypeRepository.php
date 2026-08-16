<?php

namespace App\Repositories;

use App\Core\DataBase;
use App\Models\BoxType;
use App\Models\RewardBox;
use App\Models\Image;
use App\Models\RewardBoxWithVariableQuantity;
use PDO;
use RuntimeException;

class BoxTypeRepository extends AbstractRepository
{
    private PDO $db;
    private ItemCategoryRepository $itemCategoryRepository;

    public function __construct(ItemCategoryRepository $itemCategoryRepository)
    {
        $this->db = DataBase::getInstance();
        $this->itemCategoryRepository = $itemCategoryRepository;
        parent::__construct();
    }

    protected function load(): void
    {
        $stmt = $this->db->query('SELECT * FROM tb_box_type');

        foreach ($stmt->fetchAll() as $row) {
            $boxId = (int) $row['id_box_type'];

            $boxType = new BoxType(
                id: $boxId,
                name: $row['name'],
                boxIcon: new Image($row['box_icon'], $row['mime_type_image']),
                rewardBoxes: $this->loadRewardBoxes($boxId),
            );

            $this->items[$boxType->getId()] = $boxType;
        }
    }

    /**
     * @return RewardBox[]
     */
    private function loadRewardBoxes(int $boxId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM tb_box_reward WHERE fk_id_box = :box_id');
        $stmt->execute(['box_id' => $boxId]);

        $rewardBoxes = [];
        foreach ($stmt->fetchAll() as $row) {
            $itemCategory = $this->itemCategoryRepository->findById((int) $row['fk_id_item_category']);

            if ($itemCategory === null) {
                throw new RuntimeException(
                    "ItemCategory {$row['fk_id_item_category']} não encontrada para BoxReward {$row['id_box_reward']}."
                );
            }

            if ($row['quantity_min'] === null) {
                $rewardBoxes[] = new RewardBox(
                    id: (int) $row['id_box_reward'],
                    itemCategory: $itemCategory,
                    weightChance: (int) $row['weight_chance'],
                );
                continue;
            }

            $rewardBoxes[] = new RewardBoxWithVariableQuantity(
                    id: (int) $row['id_box_reward'],
                    itemCategory: $itemCategory,
                    // quantity_min/quantity_max sao NULLABLE no banco (nem todo item empilha,
                    // ex: Skin/Ultimate/Paddle). Sem quantidade definida, assumo 0 -- ajusta se
                    // sua regra de negocio preferir outro valor default (ex: 1).
                    minQuantity: (int) ($row['quantity_min'] ?? 0),
                    maxQuantity: (int) ($row['quantity_max'] ?? 0),
                    weightChance: (int) $row['weight_chance'],
            );
        }

        return $rewardBoxes;
    }

    public function findById(int $id): ?BoxType
    {
        return parent::findById($id);
    }

    /**
     * @return BoxType[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }
}
