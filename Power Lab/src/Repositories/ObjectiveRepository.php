<?php

namespace App\Repositories;

use App\Core\DataBase;
use App\Models\Objective;
use PDO;

class ObjectiveRepository extends AbstractRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
        parent::__construct();
    }

    protected function load(): void
    {
        $stmt = $this->db->query('SELECT * FROM tb_objective');

        foreach ($stmt->fetchAll() as $row) {
            $objective = new Objective(
                id: (int) $row['id_objective'],
                name: $row['name'],
                description: $row['description']
            );

            $this->items[$objective->getId()] = $objective;
        }
    }

    public function findById(int $id): ?Objective
    {
        return parent::findById($id);
    }

    /**
     * @return Objective[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }
}