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
            $territory = $this->territoryRepository->findById((int) $row['fk_id_territory']);

            $modifiers = [
                $row['fk_modifier1'] ? $this->modifierRepository->findById((int) $row['fk_modifier1']) : null,
                $row['fk_modifier2'] ? $this->modifierRepository->findById((int) $row['fk_modifier2']) : null,
                $row['fk_modifier3'] ? $this->modifierRepository->findById((int) $row['fk_modifier3']) : null
            ];

            $stage = new Stage(
                id: (int) $row['id_stage'],
                name: $row['name'],
                paddleBot: $this->paddleRepository->findById((int) $row['fk_id_paddle']),
                paddleStage: (int) $row['paddle_stage'],
                ultimateBot: $this->ultimateRepository->findById((int) $row['fk_id_ult']),
                skinBot: $this->skinRepository->findById((int) $row['fk_id_skin']),
                particleBot: $this->particleRepository->findById((int) $row['fk_id_particle']),
                difficulty: $this->difficultyRepository->findById((int) $row['fk_difficulty']),
                objective: $this->objectiveRepository->findById((int) $row['fk_objective']),
                objectiveQuantity: (int) $row['objective_quantity'],
                rewardStage: $this->itemTypeFactory->createItemType($row['reward']),
                rewardStageQuantity: (int) $row['reward_quantity'],
                stageModifiers: $modifiers,
                enemyType: $this->enemyTypeRepository->findById((int) $row['fk_enemy_type']),
                territoryOfThisStage: $territory,
            );

            $this->items[$stage->getId()] = $stage;
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