<?php
    namespace App\Models;

    class GameVersion
    {
        public function __construct(
            private int $id,
            private string $versionCode,
            private string $changelog
        ) {
        }

        public function getId(): int
        {
            return $this->id;
        }

        public function getVersionCode(): string
        {
            return $this->versionCode;
        }

        public function getChangelog(): string
        {
            return $this->changelog;
        }
    }
?>