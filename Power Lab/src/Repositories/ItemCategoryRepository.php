<?php

namespace App\Repositories;

use App\Core\DataBase;
use App\Models\ItemCategory;
use PDO;

class ItemCategoryRepository extends AbstractRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
        parent::__construct();
    }

    protected function load(): void
    {
        $stmt = $this->db->query('SELECT * FROM tb_item_category');

        foreach ($stmt->fetchAll() as $row) {
            $itemCategory = new ItemCategory(
                id: (int) $row['id_item_category'],
                name: $row['item_type'],
            );

            $this->items[$itemCategory->getId()] = $itemCategory;
        }
    }

    public function findById(int $id): ?ItemCategory
    {
        return parent::findById($id);
    }

    /**
     * @return ItemCategory[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }
}