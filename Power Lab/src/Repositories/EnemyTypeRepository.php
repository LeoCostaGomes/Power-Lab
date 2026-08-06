<?php

namespace App\Repositories;

use App\Core\DataBase;
use App\Models\EnemyType;
use PDO;

class EnemyTypeRepository extends AbstractRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
        parent::__construct();
    }

    protected function load(): void
    {
        $stmt = $this->db->query('SELECT * FROM tb_enemy_type');

        foreach ($stmt->fetchAll() as $row) {
            $enemyType = new EnemyType(
                id: (int) $row['id_enemy_type'],
                name: $row['name'],
            );

            $this->items[$enemyType->getId()] = $enemyType;
        }
    }

    public function findById(int $id): ?EnemyType
    {
        return parent::findById($id);
    }

    /**
     * @return EnemyType[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }
}
?>