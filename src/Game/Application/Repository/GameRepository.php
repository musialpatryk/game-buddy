<?php

namespace App\Game\Application\Repository;

use App\Game\Application\Dto\CreateGameDto;
use App\Game\Application\Dto\UpdateGameDto;
use App\Game\Domain\Game;
use App\Game\Domain\GameCollection;

interface GameRepository
{
    public function getAll(): GameCollection;
    public function findById(int $id): ?Game;
    public function findByName(string $name): ?Game;
    public function create(CreateGameDto $gameDto): Game;
    public function update(int $id, UpdateGameDto $gameToUpdate): Game;

    public function delete(int $id): void;
}