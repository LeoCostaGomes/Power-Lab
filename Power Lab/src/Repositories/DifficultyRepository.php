<?php

namespace App\Repositories;

use App\Core\DataBase;
use App\Models\Difficulty;
use PDO;

class DifficultyRepository extends AbstractRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
        parent::__construct();
    }

    protected function load(): void
    {
        $stmt = $this->db->query('SELECT * FROM tb_difficulty');

        foreach ($stmt->fetchAll() as $row) {
            $difficulty = new Difficulty(
                id: (int) $row['id_difficulty'],
                name: $row['name'],
            );

            $this->items[$difficulty->getId()] = $difficulty;
        }
    }

    public function findById(int $id): ?Difficulty
    {
        return parent::findById($id);
    }

    /**
     * @return Difficulty[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }
}
?>