<?php
namespace App\Models;

use App\Interfaces\ItemType;
use App\Models\Particle;

class ParticleItemType implements ItemType
{
    public function __construct(
        private Particle $particle
    ) {
    }

    public function getRewardText() : string
    {
        return $this->particle->getName();
    }

    public function getRewardSprite() : Image
    {
        return $this->particle->getGif();
    }
}
?>