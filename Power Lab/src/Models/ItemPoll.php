<?php
    namespace App\Models;

    use App\Models\Image;
    use App\Models\User;

    class ItemPoll
    {
        public function __construct(
            private string $name,
            private string $description,
            private Image $conceptArt,
            private bool $securityToView,
            private User $userGaveIdeia) {
        }

        public function getName(): string
        {
            return $this->name;
        }

        public function getDescription(): string
        {
            return $this->description;
        }

        public function getConceptArt(): Image
        {
            return $this->conceptArt;
        }

        public function getSecurityToView(): bool
        {
            return $this->securityToView;
        }

        public function getNameUserGaveIdeia()
        {
            return $this->userGaveIdeia->getName();
        }
    }
?>