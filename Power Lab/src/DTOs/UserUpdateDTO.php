<?php

namespace App\DTOs;

class UserUpdateDTO
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $email = null,
        public readonly ?string $password = null,
        public readonly ?string $ip = null,
    ) {
    }
}