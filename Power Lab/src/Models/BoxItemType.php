<?php
namespace App\Models;

use App\Interfaces\ItemType;

class BoxItemType implements ItemType
{
    public function __construct(
        private BoxType $boxType
    ) {
    }

    public function getRewardText() : string
    {
        return $this->boxType->getName();
    }

    public function getRewardSprite() : Image
    {
        return $this->boxType->getBoxIcon();
    }
}
?>