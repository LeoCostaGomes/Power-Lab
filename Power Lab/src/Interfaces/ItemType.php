<?php
    namespace App\Interfaces;

    use App\Models\Image;
    interface ItemType
    {
        public function getRewardText() : string;
        public function getRewardSprite() : Image;
    }
?>