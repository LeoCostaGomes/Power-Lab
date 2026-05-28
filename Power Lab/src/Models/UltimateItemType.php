<?php
namespace App\Models;

use App\Interfaces\ItemType;
use App\Models\Ultimate;

class UltimateItemType implements ItemType
{
    public function __construct(
        private Ultimate $ultimate
    ) {
    }

    public function getRewardText() : string
    {
        return $this->ultimate->getName();
    }

    public function getRewardSprite() : Image
    {
        return $this->ultimate->getSpriteIcon();
    }
}
?>