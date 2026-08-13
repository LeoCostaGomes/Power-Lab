<?php

namespace App\Repositories;

use App\Core\DataBase;
use App\Models\GameMode;
use PDO;

class GameModeRepository extends AbstractRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
        parent::__construct();
    }

    protected function load(): void
    {
        $stmt = $this->db->query('SELECT * FROM tb_game_mode');

        foreach ($stmt->fetchAll() as $row) {
            $gameMode = new GameMode(
                id: (int) $row['id_game_mode'],
                name: $row['name'],
                description: $row['description']
            );

            $this->items[$gameMode->getId()] = $gameMode;
        }
    }

    public function findById(int $id): ?GameMode
    {
        return parent::findById($id);
    }

    /**
     * @return GameMode[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }
}
?>