<?php
namespace App\Models;

class BoxType
{
    public function __construct(
        private string $name,
        private Image $boxIcon,
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
}