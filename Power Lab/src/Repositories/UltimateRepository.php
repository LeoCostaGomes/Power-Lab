<?php

namespace App\Repositories;

use App\Database\DataBase;
use App\Models\Ultimate;
use App\Models\Image;
use PDO;
use RuntimeException;

class UltimateRepository extends AbstractRepository
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
        $stmt = $this->db->query('SELECT * FROM tb_ultimate');

        foreach ($stmt->fetchAll() as $row) {
            $territory = $this->territoryRepository->findById((int) $row['fk_unlockable_in_territory']);

            if ($territory === null) {
                throw new RuntimeException(
                    "Territory {$row['fk_unlockable_in_territory']} não encontrado para o Ultimate {$row['id_ultimate']}."
                );
            }

            $ultimate = new Ultimate(
                id: (int) $row['id_ultimate'],
                name: $row['name'],
                description: $row['description'],
                spriteIcon: new Image($row['ultimate_sprite'], $row['mime_type_sprite']),
                territoryBelonging: $territory,
            );

            $this->items[$ultimate->getId()] = $ultimate;
        }
    }

    public function findById(int $id): ?Ultimate
    {
        return parent::findById($id);
    }

    /**
     * @return Ultimate[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }
}