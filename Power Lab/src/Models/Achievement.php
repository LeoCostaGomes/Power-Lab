<?php
    namespace App\Models;

    use App\Interfaces\ItemType;

    class Achievement
    {
        public function __construct(
        private string $name,
        private string $objective,
        private Image $icon,
        private ItemType $reward,
        private int $quantityReward
        ) {
        }

        public function getName(): string
        {
            return $this->name;
        }

        public function getObjective(): string
        {
            return $this->objective;
        }

        public function getIcon(): Image
        {
            return $this->icon;
        }

        public function getReward(): ItemType
        {
            return $this->reward;
        }

        public function getQuantityReward(): int
        {
            return $this->quantityReward;
        }
    }
?>