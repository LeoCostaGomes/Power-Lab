<?php
namespace App\Models;

class Skin {
    public function __construct(
        private string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
?>