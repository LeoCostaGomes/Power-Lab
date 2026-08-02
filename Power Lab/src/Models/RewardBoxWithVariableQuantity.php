<?php

namespace App\Models;

use App\Interfaces\ItemType;
use App\Models\RewardBox;

class RewardBoxWithVariableQuantity extends RewardBox
{
    public function __construct(
        int $id,
        private int $minQuantity,
        private int $maxQuantity,
        ItemCategory $itemCategory,
        int $weightChance
    ) {
        parent::__construct($id, $itemCategory, $weightChance);
    }

    public function getMinQuantity(): int
    {
        return $this->minQuantity;
    }

    public function getMaxQuantity(): int
    {
        return $this->maxQuantity;
    }
}
?>