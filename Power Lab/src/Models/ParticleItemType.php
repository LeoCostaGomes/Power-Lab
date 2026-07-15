<?php

namespace App\Models;

use App\Interfaces\ItemType;
use App\Models\Particle;

class ParticleItemType implements ItemType
{
    public function __construct(
        private int $id,
        private Particle $particle
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRewardText(): string
    {
        return $this->particle->getName();
    }

    public function getRewardSprite(): Image
    {
        return $this->particle->getGif();
    }
}