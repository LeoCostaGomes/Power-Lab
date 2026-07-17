<?php

namespace App\Interfaces;

use App\Interfaces\RepositoryInterface;

interface WritableRepositoryInterface extends RepositoryInterface
{
    public function create(mixed $data): bool;

    public function update(int $id, mixed $data): bool;

    public function delete(int $id): bool;
}