<?php

namespace App\Repositories;

use App\Core\DataBase;
use App\Models\Particle;
use App\Models\Image;
use PDO;

class ParticleRepository extends AbstractRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
        parent::__construct();
    }

    protected function load(): void
    {
        $stmt = $this->db->query('SELECT * FROM tb_particle');

        foreach ($stmt->fetchAll() as $row) {
            $particle = new Particle(
                id: (int) $row['id_particle'],
                name: $row['name'],
                spriteParticle: new Image($row['sprite_particle'], $row['mime_type_sprite']),
                gifParticle: new Image($row['gif_particle'], $row['mime_type_gif']),
            );

            $this->items[$particle->getId()] = $particle;
        }
    }

    public function findById(int $id): ?Particle
    {
        return parent::findById($id);
    }

    /**
     * @return Particle[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }
}