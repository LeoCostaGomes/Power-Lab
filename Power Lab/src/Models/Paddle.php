<?php
namespace App\Models;

use App\Models\Territory;
class Paddle
{
    public function __construct(
        private int $id,
        private string $name,
        /**
         * @var string[]
         */
        private array $descriptionOfStages,
        private Territory $territoryBelonging,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getAllDescriptions(): array
    {
        $result = [];

        foreach ($this->descriptionOfStages as $stage => $description) {
            if ($description === '') {
                continue;
            }

            $result[$stage + 1] = $description;
        }

        return $result;
    }



    public function getNameTerritory(): string
    {
        return $this->territoryBelonging->getName();
    }
}
?>