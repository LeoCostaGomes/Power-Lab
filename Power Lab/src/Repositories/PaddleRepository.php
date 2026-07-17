<?php

namespace App\Repositories;

use App\Database\DataBase;
use App\Models\Paddle;
use PDO;
use RuntimeException;

class PaddleRepository extends AbstractRepository
{
    private PDO $db;
    private TerritoryRepository $territoryRepository;

    public function __construct(TerritoryRepository $territoryRepository)
    {
        $this->db = DataBase::getInstance();
        $this->territoryRepository = $territoryRepository;
        parent::__construct();
    }

    protected function load(): void
    {
        $stmt = $this->db->query('SELECT * FROM paddles');

        foreach ($stmt->fetchAll() as $row) {
            $territory = $this->territoryRepository->findById((int) $row['territory_id']);

            if ($territory === null) {
                throw new RuntimeException(
                    "Territory {$row['territory_id']} não encontrado para o Paddle {$row['id']}."
                );
            }

            $paddle = new Paddle(
                id: (int) $row['id'],
                name: $row['name'],
                descriptionOfStages: json_decode($row['description_of_stages'], true) ?? [],
                territoryBelonging: $territory,
            );

            $this->items[$paddle->getId()] = $paddle;
        }
    }

    public function findById(int $id): ?Paddle
    {
        return parent::findById($id);
    }

    /**
     * @return Paddle[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }
}