<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;

abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * @var array<int, object>
     */
    protected array $items = [];

    public function __construct()
    {
        $this->load();
    }

    /**
     * Busca todos os registros no banco e preenche $items.
     * Cada Repository concreto decide como montar os próprios objetos.
     */
    abstract protected function load(): void;

    public function findById(int $id): ?object
    {
        return $this->items[$id] ?? null;
    }

    /**
     * @return object[]
     */
    public function findAll(): array
    {
        return $this->items;
    }
}