<?php

namespace App\Repositories;

use App\Core\DataBase;
use App\Factories\ItemTypeFactory;
use App\Models\Stage;
use PDO;
use RuntimeException;

class StageRepository extends AbstractRepository
{
    private PDO $db;
    private TerritoryRepository $territoryRepository;
    private PaddleRepository $paddleRepository;
    private UltimateRepository $ultimateRepository;
    private SkinRepository $skinRepository;
    private ParticleRepository $particleRepository;
    private DifficultyRepository $difficultyRepository;
    private ObjectiveRepository $objectiveRepository;
    private EnemyTypeRepository $enemyTypeRepository;
    private ItemTypeFactory $itemTypeFactory;
    private ModifierRepository $modifierRepository;

    public function __construct(TerritoryRepository $territoryRepository, PaddleRepository $paddleRepository, UltimateRepository $ultimateRepository, SkinRepository $skinRepository, ParticleRepository $particleRepository, DifficultyRepository $difficultyRepository, ObjectiveRepository $objectiveRepository, EnemyTypeRepository $enemyTypeRepository, ItemTypeFactory $itemTypeFactory, ModifierRepository $modifierRepository)
    {
        $this->db = DataBase::getInstance();
        $this->territoryRepository = $territoryRepository;
        $this->paddleRepository = $paddleRepository;
        $this->ultimateRepository = $ultimateRepository;
        $this->skinRepository = $skinRepository;
        $this->particleRepository = $particleRepository;
        $this->difficultyRepository = $difficultyRepository;
        $this->objectiveRepository = $objectiveRepository;
        $this->enemyTypeRepository = $enemyTypeRepository;
        $this->itemTypeFactory = $itemTypeFactory;
        $this->modifierRepository = $modifierRepository;
        parent::__construct();
    }

    protected function load(): void
    {
        $stmt = $this->db->query('SELECT * FROM tb_stage');

        foreach ($stmt->fetchAll() as $row) {
            $territory = $this->territoryRepository->findById((int) $row['fk_unlockable_in_territory']);

            $modifiers = [
                $row['fk_modifier1'] ? $this->modifierRepository->findById((int) $row['fk_modifier1']) : null,
                $row['fk_modifier2'] ? $this->modifierRepository->findById((int) $row['fk_modifier2']) : null,
                $row['fk_modifier3'] ? $this->modifierRepository->findById((int) $row['fk_modifier3']) : null
            ];

            $this->verifyDependencies();

            $stage = new Stage(
                id: (int) $row['id_stage'],
                name: $row['name'],
                paddleBot: $this->paddleRepository->findById((int) $row['fk_paddle_bot']),
                paddleStage: $row['fk_paddle_stage'],
                ultimateBot: $this->ultimateRepository->findById((int) $row['fk_ultimate']),
                skinBot: $this->skinRepository->findById((int) $row['fk_skin']),
                particleBot: $this->particleRepository->findById((int) $row['fk_particle']),
                difficulty: $this->difficultyRepository->findById((int) $row['fk_difficulty']),
                objective: $this->objectiveRepository->findById((int) $row['fk_objective']),
                objectiveQuantity: $row['objective_quantity'],
                rewardStage: $this->itemTypeFactory->createItemType($row['reward']),
                rewardStageQuantity: $row['reward_quantity'],
                stageModifiers: $modifiers,
                enemyType: $this->enemyTypeRepository->findById((int) $row['fk_enemy_type']),
                territoryOfThisStage: $territory,
            );

            $this->items[$stage->getId()] = $stage;
        }
    }

    private function verifyDependencies(): void
    {
        if ($this->territoryRepository === null) {
            throw new RuntimeException('TerritoryRepository não foi injetado no StageRepository.');
        }

        if ($this->paddleRepository === null) {
            throw new RuntimeException('PaddleRepository não foi injetado no StageRepository.');
        }

        if ($this->ultimateRepository === null) {
            throw new RuntimeException('UltimateRepository não foi injetado no StageRepository.');
        }

        if ($this->skinRepository === null) {
            throw new RuntimeException('SkinRepository não foi injetado no StageRepository.');
        }

        if ($this->particleRepository === null) {
            throw new RuntimeException('ParticleRepository não foi injetado no StageRepository.');
        }

        if ($this->difficultyRepository === null) {
            throw new RuntimeException('DifficultyRepository não foi injetado no StageRepository.');
        }

        if ($this->objectiveRepository === null) {
            throw new RuntimeException('ObjectiveRepository não foi injetado no StageRepository.');
        }

        if ($this->enemyTypeRepository === null) {
            throw new RuntimeException('EnemyTypeRepository não foi injetado no StageRepository.');
        }

        if ($this->itemTypeFactory === null) {
            throw new RuntimeException('ItemTypeFactory não foi injetado no StageRepository.');
        }

        if ($this->modifierRepository === null) {
            throw new RuntimeException('ModifierRepository não foi injetado no StageRepository.');
        }
    }

    public function findById(int $id): ?Stage
    {
        return parent::findById($id);
    }

    /**
     * @return Stage[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }
}