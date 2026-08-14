<?php
    namespace App\Factories;

    use App\Models\PaddleItemType;
    use App\Repositories\PaddleRepository;
    use App\Repositories\ParticleRepository;
    use App\Repositories\SkinRepository;
    use App\Repositories\UltimateRepository;

    class ItemTypeFactory
    {
        private PaddleRepository $paddleRepository;
        private UltimateRepository $ultimateRepository;
        private SkinRepository $skinRepository;
        private ParticleRepository $particleRepository;
        private 
        public function __construct()
        {

        }

        public function createItemType(string $rewardCode): ItemType
        {
            $parts = explode('/', $rewardCode);

            $item = $parts[0];
            $id = (int) $parts[1];
        }

        private function difineItemType(string $type): ItemType
        {
            switch ($type)
            {
                case "Paddle":
                    new PaddleItemType();
            }
        }
    }
?>