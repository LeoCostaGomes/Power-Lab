<?php

namespace App\Models;

use App\Models\Image;

class Modifier
{
    private int $id;
    private string $name;
    private string $description;
    private Image $spriteIcon;

    public function __construct(
        int $id,
        string $name,
        string $description,
        Image $spriteIcon
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->spriteIcon = $spriteIcon;
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
}