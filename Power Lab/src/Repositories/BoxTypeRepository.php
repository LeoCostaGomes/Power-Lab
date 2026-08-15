<?php

namespace App\Repositories;

use App\Core\DataBase;
use App\Models\BoxType;
use App\Models\Image;
use App\Models\RewardBox;
use App\Models\RewardBoxWithVariableQuantity;
use PDO;

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

        $stmt2 = $this->db->query('SELECT * FROM tb_box_reward');

        foreach ($stmt->fetchAll() as $row) {

            $idBox = (int) $row['id_box_type'];

            /**
            * @var RewardBox[]
            */
            $rewards = [];

            foreach ($stmt2->fetchAll() as $rowReward)
            {
                $reward;
                if ($rowReward['fk_id_box'] !== $idBox) continue;

                if ($rowReward['quantity_min'] === null)
                {
                    $reward = new RewardBox(
                        id: (int) $row['id_box_reward'],
                        itemCategory: $this->itemCategoryRepository->findById($row['fk_id_item_category']),
                        weightChance: (int) $row['weight_chance']
                    );
                }
                else
                {
                    $reward = new RewardBoxWithVariableQuantity(
                        id: (int) $row['id_box_reward'],
                        itemCategory: $this->itemCategoryRepository->findById($row['fk_id_item_category']),
                        weightChance: (int) $row['weight_chance'],
                        minQuantity: $row['quantity_min'],
                        maxQuantity: $row['quantity_max']
                    );
                }
                $rewards += $reward;
            }

            $boxType = new BoxType(
                id: (int) $row['id_box_type'],
                name: $row['name'],
                boxIcon: new Image($row['box_icon'], $row['mime_type_image']),
                rewardBoxes: $rewards
            );

            $this->items[$boxType->getId()] = $boxType;
        }
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