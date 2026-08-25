<?php

namespace App\Models;

class IP
{
    public function __construct(private string $IP)
    {
        
    }

    public function getIP(): string
    {
        return $this->IP;
    }

    public function compareIP(string $ipToCompare): bool
    {
        return $this->getIP() === $ipToCompare;
    }
}
