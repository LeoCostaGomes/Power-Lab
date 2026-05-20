<?php
namespace App\Models;

class Particle{
    public function __construct(
        private string $name,
        private Image $spriteParticle,
        private Image $gifParticle,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSprite() : Image
    {
        return $this->spriteParticle;
    }

    public function getGif(): Image
    {
        return $this->gifParticle;
    }
}
?>