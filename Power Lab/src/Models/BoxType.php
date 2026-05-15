<?php
namespace App\Models;

use App\Models\RewardBox;
class BoxType
{
    public function __construct(
        private string $name,
        private Image $boxIcon,
        /**
        * @var RewardBox[]
        */
        private array $rewardBoxes
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBoxIcon(): Image
    {
        return $this->boxIcon;
    }

    public function getRewardBoxes(): array
    {
        return $this->rewardBoxes;
    }

    public function getRealChanceOfEachItem(): array
    {
        $totalWeight = array_reduce($this->rewardBoxes, function ($carry, $rewardBox) {
            return $carry + $rewardBox->getWeightChance();
        }, 0);

        $realChances = [];
        foreach ($this->rewardBoxes as $rewardBox) {
            $realChances[] = [
                'itemType' => $rewardBox->getItemType(),
                'realChance' => ($rewardBox->getWeightChance() / $totalWeight) * 100
            ];
        }

        return $realChances;
    }
}