<?php
namespace App\Factories;

use App\Interfaces\ItemType;
use App\Models\BoxItemType;
use App\Models\PaddleItemType;
use App\Models\ParticleItemType;
use App\Models\PongCoinsItemType;
use App\Models\SkinItemType;
use App\Models\UltimateItemType;
use App\Repositories\BoxTypeRepository;
use App\Repositories\PaddleRepository;
use App\Repositories\ParticleRepository;
use App\Repositories\SkinRepository;
use App\Repositories\UltimateRepository;
use Exception;

class ItemTypeFactory
{
    private ?PaddleRepository $paddleRepository;
    private ?UltimateRepository $ultimateRepository;
    private ?SkinRepository $skinRepository;
    private ?ParticleRepository $particleRepository;
    private BoxTypeRepository $boxTypeRepository;
    public function __construct(
        BoxTypeRepository $boxTypeRepository,
        ?PaddleRepository $paddleRepository = null,
        ?UltimateRepository $ultimateRepository = null,
        ?SkinRepository $skinRepository = null,
        ?ParticleRepository $particleRepository = null
    ) {
        $this->boxTypeRepository = $boxTypeRepository;
        $this->paddleRepository = $paddleRepository;
        $this->ultimateRepository = $ultimateRepository;
        $this->skinRepository = $skinRepository;
        $this->particleRepository = $particleRepository;
    }

    public function createItemType(string $rewardCode): ItemType
    {
        $item = $rewardCode;
        $id = 0;
        if ($rewardCode !== "Pongcoin") {
            $parts = explode('/', $rewardCode);

            $item = $parts[0];
            $id = (int) $parts[1];
        }
        return $this->defineItemType($item, $id);
    }

    private function defineItemType(string $type, int $id): ItemType
    {
        switch ($type) {
            case "Paddle":
                if ($this->paddleRepository === null) throw new Exception("paddleRepository é null");
                return new PaddleItemType($this->paddleRepository->findById($id));
                break;
            case "Ultimate":
                if ($this->ultimateRepository === null) throw new Exception("ultimateRepository é null");
                return new UltimateItemType($this->ultimateRepository->findById($id));
                break;
            case "Skin":
                if ($this->skinRepository === null) throw new Exception("skinRepository é null");
                return new SkinItemType($this->skinRepository->findById($id));
                break;
            case "Particle":
                if ($this->particleRepository === null) throw new Exception("particleRepository é null");
                return new ParticleItemType($this->particleRepository->findById($id));
                break;
            case "Box":
                if ($this->boxTypeRepository === null) throw new Exception("boxTypeRepository é null");
                return new BoxItemType($this->boxTypeRepository->findById($id));
                break;
            case "Pongcoin":
                return new PongCoinsItemType();
                break;
            default:
                throw new Exception("Tipo de item desconhecido: " . $type);
            
        }
    }
}
?>