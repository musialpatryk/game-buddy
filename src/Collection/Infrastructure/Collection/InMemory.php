<?php

namespace App\Collection\Infrastructure\Collection;

use App\Collection\Application\Repository\CollectionRepository;

class InMemory implements CollectionRepository
{
    private array $assignments = [];

    public function assign(int $userId, int $gameId): void
    {
        $this->assignments[$userId] = $gameId;
    }

    public function remove(int $userId, int $gameId): void
    {
        unset($this->assignments[$userId]);
    }

    public function has(int $userId, int $gameId): bool
    {
        return isset($this->assignments[$userId]);
    }
}