<?php

namespace App\Models;

class Email
{
    public function __construct(private string $email)
    {
        
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function compareEmail(String $emailToCompare): bool
    {
        return $this->getEmail() === $emailToCompare;
    }
}