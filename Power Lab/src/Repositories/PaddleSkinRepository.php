<?php

namespace App\Repositories;

use App\Core\DataBase;
use App\Models\Image;
use PDO;
use App\Repositories\SkinRepository;


class PaddleSkinRepository
{
    private PDO $db;
    /**
     * @var Image[][]
     */
    private array $paddlesSkins = [];
    private SkinRepository $skinRepository;
    private PaddleRepository $paddleRepository;

    public function __construct(SkinRepository $skinRepository, PaddleRepository $paddleRepository)
    {
        $this->db = DataBase::getInstance();
        $this->skinRepository = $skinRepository;
        $this->paddleRepository = $paddleRepository;
        $this->load();
    }

    protected function load(): void
    {
        $stmt = $this->db->query('SELECT * FROM tb_paddle_skin');

        foreach ($stmt->fetchAll() as $row) {
            $paddleSkin = new Image(
                data: $row['sprite_paddle_skin'],
                mimeType: $row['mime_type_sprite']
            );

            $this->paddlesSkins[$this->paddleRepository->findById($row['fk_id_paddle'])->getId()][$this->skinRepository->findById($row['fk_id_skin'])->getId()]
            = $paddleSkin;
        }
    }

    public function findById(int $idPaddle, int $idSkin): ?Image
    {
        return $this->paddlesSkins[$idPaddle][$idSkin] ?? null;
    }

    /**
     * @return Image[][]
     */
    public function findAll(): array
    {
        return $this->paddlesSkins;
    }


}
?>