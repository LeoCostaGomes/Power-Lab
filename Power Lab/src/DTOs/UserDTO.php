<?php

namespace App\DTOs;

final class UserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $ip
    ) {}
}
