<?php
namespace App\Models;

use App\Models\RewardBox;
class RewardBoxWithVariableQuantity extends RewardBox
{
    public function __construct(
        private int $minQuantity,
        private int $maxQuantity,
        ItemType $itemType,
        int $weightChance
    ) {
        parent::__construct($itemType, $weightChance);
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