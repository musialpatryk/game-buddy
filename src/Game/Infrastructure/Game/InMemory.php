<?php

namespace App\Game\Infrastructure\Game;

use App\Game\Application\Dto\CreateGameDto;
use App\Game\Application\Dto\UpdateGameDto;
use App\Game\Application\Repository\GameRepository;
use App\Game\Domain\Game;
use App\Game\Domain\GameCollection;

class InMemory implements GameRepository
{

    public function getAll(): GameCollection
    {
        // TODO: Implement getAll() method.
    }

    public function findById(int $id): ?Game
    {
        // TODO: Implement findById() method.
    }

    public function findByName(string $name): ?Game
    {
        // TODO: Implement findByName() method.
    }

    public function create(CreateGameDto $gameDto): Game
    {
        // TODO: Implement create() method.
    }

    public function update(UpdateGameDto $gameToUpdate): Game
    {
        // TODO: Implement update() method.
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }
}