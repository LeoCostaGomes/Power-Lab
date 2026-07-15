<?php

namespace App\Models;

use App\Interfaces\ItemType;
use App\Models\Skin;

class SkinItemType implements ItemType
{
    public function __construct(
        private int $id,
        private Skin $skin
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRewardText(): string
    {
        return $this->skin->getName();
    }

    public function getRewardSprite(): Image
    {
        // Pegar do View das skin, quando ele estiver pronto.
        return new Image("iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==", "image/png");
    }
}
?>