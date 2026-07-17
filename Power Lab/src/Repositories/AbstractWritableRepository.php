<?php

namespace App\Repositories;

use App\Repositories\AbstractRepository;
use App\Interfaces\WritableRepositoryInterface;

abstract class AbstractWritableRepository extends AbstractRepository implements WritableRepositoryInterface
{
    abstract public function create(mixed $data): bool;

    abstract public function update(int $id, mixed $data): bool;

    public function delete(int $id): bool
    {
        if (!isset($this->items[$id])) {
            return false;
        }

        $this->deleteFromDatabase($id);
        unset($this->items[$id]);

        return true;
    }

    /**
     * Cada Repository concreto sabe apagar sua própria linha no banco.
     */
    abstract protected function deleteFromDatabase(int $id): void;
}