<?php

namespace App\Collection\Application\Repository;

interface CollectionRepository
{
    public function assign(int $userId, int $gameId): void;
    public function remove(int $userId, int $gameId): void;
    public function has(int $userId, int $gameId): bool;
}