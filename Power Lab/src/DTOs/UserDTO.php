<?php

namespace App\DTOs;

final readonly class UserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $ip
    ) {}
}
