<?php

namespace App\Repositories;

use App\Core\DataBase;
use App\Models\Modifier;
use App\Models\Image;
use PDO;

class ModifierRepository extends AbstractRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
        parent::__construct();
    }

    protected function load(): void
    {
        $stmt = $this->db->query('SELECT * FROM tb_modifier');

        foreach ($stmt->fetchAll() as $row) {

            $modifier = new Modifier(
                id: (int) $row['id_modifier'],
                name: $row['name'],
                description: $row['modifier_description'],
                spriteIcon: new Image($row['sprite_modifier'], $row['mime_type_sprite'])
            );

            $this->items[$modifier->getId()] = $modifier;
        }
    }

    public function findById(int $id): ?Modifier
    {
        return parent::findById($id);
    }

    /**
     * @return Modifier[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }
}