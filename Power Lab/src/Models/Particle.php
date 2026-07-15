<?php

namespace App\Models;

use App\Models\Image;

class Particle
{
    public function __construct(
        private int $id,
        private string $name,
        private Image $spriteParticle,
        private Image $gifParticle,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSprite(): Image
    {
        return $this->spriteParticle;
    }

    public function getGif(): Image
    {
        return $this->gifParticle;
    }
}