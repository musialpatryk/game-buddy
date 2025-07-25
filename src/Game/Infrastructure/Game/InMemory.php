<?php

namespace App\Game\Infrastructure\Game;

use App\Game\Application\Dto\CreateGameDto;
use App\Game\Application\Dto\GameFilters;
use App\Game\Application\Dto\UpdateGameDto;
use App\Game\Application\Repository\GameRepository;
use App\Game\Domain\Game;
use App\Game\Domain\GameCollection;

class InMemory implements GameRepository
{
    private static int $lastId = 1;
    private static GameCollection $games;

    public function __construct()
    {
        if (!isset(self::$games)) {
            self::$games = new GameCollection([]);
        }
    }

    public function getAll(GameFilters $gameFilters): GameCollection
    {
        return self::$games;
    }

    public function findById(int $id): ?Game
    {
        $game = self::$games->findById($id);
        if (!$game) {
            return null;
        }
        return clone $game;
    }

    public function findByName(string $name): ?Game
    {
        $game = self::$games->findByName($name);
        if (!$game) {
            return null;
        }
        return clone $game;
    }

    public function create(CreateGameDto $gameDto): Game
    {
        $game = new Game(
            self::$lastId++,
            $gameDto->getName(),
        );
        self::$games->add($game);
        return $game;
    }

    public function update(int $id, UpdateGameDto $gameToUpdate): Game
    {
        $game = new Game(
            $id,
            $gameToUpdate->getName(),
        );
        self::$games->removeById($id);
        self::$games->add($game);
        return $game;
    }

    public function delete(int $id): void
    {
        self::$games->removeById($id);
    }
}