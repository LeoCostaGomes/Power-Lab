<?php
namespace App\Models;

use App\Interfaces\ItemType;
class RewardBox
{
    public function __construct(
        private ItemType $itemType,
        private int $weightChance
    ) {
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