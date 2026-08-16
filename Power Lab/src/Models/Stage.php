<?php

namespace App\Models;

use App\Interfaces\ItemType;

class Stage
{
    private int $id;
    private string $name;
    private Paddle $paddleBot;
    private int $paddleStage;
    private Ultimate $ultimateBot;
    private Skin $skinBot;
    private Particle $particleBot;
    private Difficulty $difficulty;
    private Objective $objective;
    private int $objectiveQuantity;
    private ItemType $rewardStage;
    private int $rewardStageQuantity;
    /**
    * @var (Modifier|null)[]
    */
    private array $stageModifiers;
    private EnemyType $enemyType;
    private Territory $territoryOfThisStage;

    public function __construct(
        int $id,
        string $name,
        Paddle $paddleBot,
        int $paddleStage,
        Ultimate $ultimateBot,
        Skin $skinBot,
        Particle $particleBot,
        Difficulty $difficulty,
        Objective $objective,
        int $objectiveQuantity,
        ItemType $rewardStage,
        int $rewardStageQuantity,
        /**
        * @var (Modifier|null)[]
        */
        array $stageModifiers,
        EnemyType $enemyType,
        Territory $territoryOfThisStage
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->paddleBot = $paddleBot;
        $this->paddleStage = $paddleStage;
        $this->ultimateBot = $ultimateBot;
        $this->skinBot = $skinBot;
        $this->particleBot = $particleBot;
        $this->difficulty = $difficulty;
        $this->objective = $objective;
        $this->objectiveQuantity = $objectiveQuantity;
        $this->rewardStage = $rewardStage;
        $this->rewardStageQuantity = $rewardStageQuantity;
        $this->stageModifiers = $stageModifiers;
        $this->enemyType = $enemyType;
        $this->territoryOfThisStage = $territoryOfThisStage;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNameStage(): string
    {
        return $this->name;
    }

    public function getPaddleBot(): Paddle
    {
        return $this->paddleBot;
    }

    public function getPaddleStage(): int
    {
        return $this->paddleStage;
    }

    public function getUltimateBot(): Ultimate
    {
        return $this->ultimateBot;
    }

    public function getSkinBot(): Skin
    {
        return $this->skinBot;
    }

    public function getParticleBot(): Particle
    {
        return $this->particleBot;
    }

    public function getNameDifficulty(): string
    {
        return $this->difficulty->getName();
    }

    public function getObjective(): Objective
    {
        return $this->objective;
    }

    public function getObjectiveQuantity(): int
    {
        return $this->objectiveQuantity;
    }

    public function getRewardStage(): ItemType
    {
        return $this->rewardStage;
    }

    public function getRewardStageQuantity() : int
    {
        return $this->rewardStageQuantity;
    }

    public function getModifiers(): array
    {
        return $this->stageModifiers;
    }

    public function getNameEnemyType(): string
    {
        return $this->enemyType->getName();
    }

    public function getNameTerritory(): string
    {
        return $this->territoryOfThisStage->getName();
    }
}    