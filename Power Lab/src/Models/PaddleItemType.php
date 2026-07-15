<?php
namespace App\Models;

use App\Interfaces\ItemType;
use App\Models\Paddle;

class PaddleItemType implements ItemType
{
    public function __construct(
        private Paddle $paddle
    ) {
    }

    public function getRewardText() : string
    {
        return $this->paddle->getName();
    }

    public function getRewardSprite() : Image
    {
        // Pegar do View das skin, quando ele estiver pronto.
        return new Image("iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==", "image/png");
    }
}
?>