<?php

namespace App\Repositories;

use App\Models\Skin;
use App\Core\DataBase;
use PDO;

class SkinRepository extends AbstractRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
        parent::__construct();
    }

    protected function load(): void
    {
        $stmt = $this->db->query('SELECT * FROM tb_skin');

        foreach ($stmt->fetchAll() as $row) {
            $skin = new Skin(
                id: (int) $row['id_skin'],
                name: $row['name']
            );

            $this->items[$skin->getId()] = $skin;
        }
    }

    public function findById(int $id): ?Skin
    {
        return parent::findById($id);
    }

    /**
     * @return Skin[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }


}
?>