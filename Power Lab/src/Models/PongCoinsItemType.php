<?php
namespace App\Models;

use App\Interfaces\ItemType;

class PongCoinsItemType implements ItemType
{
    public function __construct(
    ) {
    }

    public function getRewardText() : string
    {
        return "Pong Coins";
    }

    public function getRewardSprite() : Image
    {
        // depois vou pegar o sprite da moeda
        return new Image("iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==", "image/png");
    }
}
?>