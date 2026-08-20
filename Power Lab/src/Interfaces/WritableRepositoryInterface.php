<?php

namespace App\Interfaces;

use App\Interfaces\RepositoryInterface;

interface WritableRepositoryInterface extends RepositoryInterface
{
    public function create(object $data): bool;

    public function update(int $id, object $data): bool;

    public function delete(int $id): bool;
}