<?php

namespace App\Models;

use App\Models\Image;
use App\Models\Territory;

class Ultimate
{
    public function __construct(
        private int $id,
        private string $name,
        private string $description,
        private Image $spriteIcon,
        private Territory $territoryBelonging,
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSpriteIcon(): Image
    {
        return $this->spriteIcon;
    }

    public function getNameTerritory(): string
    {
        return $this->territoryBelonging->getName();
    }
}
?>