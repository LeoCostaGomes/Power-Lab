<?php

namespace App\Repositories;

use App\Core\DataBase;
use App\Models\GameVersion;
use PDO;

class GameVersionRepository extends AbstractRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
        parent::__construct();
    }

    protected function load(): void
    {
        $stmt = $this->db->query('SELECT * FROM tb_game_version');

        foreach ($stmt->fetchAll() as $row) {
            $gameVersion = new GameVersion(
                id: (int) $row['id_game_version'],
                versionCode: $row['version_code'],
                changelog: $row['version_log']
            );

            $this->items[$gameVersion->getId()] = $gameVersion;
        }
    }

    public function findById(int $id): ?GameVersion
    {
        return parent::findById($id);
    }

    /**
     * @return GameVersion[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }
}