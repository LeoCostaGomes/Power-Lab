<?php

namespace App\DTOs;

class UserUpdateDTO
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?string $ip = null,
    ) {
    }
}