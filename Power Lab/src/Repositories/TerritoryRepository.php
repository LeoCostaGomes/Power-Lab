<?php

namespace App\Repositories;

use App\Database\DataBase;
use App\Models\Territory;
use PDO;

class TerritoryRepository extends AbstractRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
        parent::__construct();
    }

    protected function load(): void
    {
        $stmt = $this->db->query('SELECT * FROM territories');

        foreach ($stmt->fetchAll() as $row) {
            $territory = new Territory(
                id: (int) $row['id'],
                name: $row['name'],
            );

            $this->items[$territory->getId()] = $territory;
        }
    }

    public function findById(int $id): ?Territory
    {
        return parent::findById($id);
    }

    /**
     * @return Territory[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }
}