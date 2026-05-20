<?php
namespace App\Models;

use App\Models\Territory;
class Paddle
{
    public function __construct(
        private string $name,
        /**
         * @var string[]
         */
        private array $descriptionOfStages,
        private Territory $territoryBelonging,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescriptionOfStage(int $stage): string
    {
        $description = $this->descriptionOfStages[$stage - 1] ?? null;
        if ($description === null) {
            throw new \InvalidArgumentException("Stage $stage does not exist for this paddle.");
        }
        return $description;
    }

    public function getNameTerritory() : string
    {
        return $this->territoryBelonging->getName();
    }
}
?>