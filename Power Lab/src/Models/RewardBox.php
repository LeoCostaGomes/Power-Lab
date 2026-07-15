<?php

namespace App\Models;

use App\Interfaces\ItemType;

class RewardBox
{
    public function __construct(
        private int $id,
        private ItemType $itemType,
        private int $weightChance
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getItemType(): ItemType
    {
        return $this->itemType;
    }

    public function getWeightChance(): int
    {
        return $this->weightChance;
    }
}
?>