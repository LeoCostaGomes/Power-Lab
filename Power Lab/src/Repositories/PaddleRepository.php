<?php

namespace App\Repositories;

use App\Core\DataBase;
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
        $stmt = $this->db->query('SELECT * FROM tb_paddle');

        foreach ($stmt->fetchAll() as $row) {
            $territory = $this->territoryRepository->findById((int) $row['fk_unlockable_in_territory']);

            if ($territory === null) {
                throw new RuntimeException(
                    "Territory {$row['fk_unlockable_in_territory']} não encontrado para o Paddle {$row['id_paddle']}."
                );
            }

            // As descrições ainda estão sendo lançadas aos poucos: como as colunas
            // são NOT NULL, uma descrição que ainda não existe vem como '' (nunca null).
            // Descartamos as vazias e reindexamos, assumindo que o "buraco" fica só
            // no final (stage 4, 5...) e não no meio da sequência.
            $descriptions = array_values(array_filter([
                $row['description1'],
                $row['description2'],
                $row['description3'],
                $row['description4'],
                $row['description5'],
            ], fn (string $description) => trim($description) !== ''));

            $paddle = new Paddle(
                id: (int) $row['id_paddle'],
                name: $row['name'],
                descriptionOfStages: $descriptions,
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