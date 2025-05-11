<?php

namespace App\Game\Domain;

use Webmozart\Assert\Assert;

class GameCollection
{
    /**
     * @var Game[]
     */
    private array $games = [];

    /**
     * @param Game[] $games
     */
    public function __construct(
        array $games,
    ) {
        foreach ($games as $game) {
            $this->add($game);
        }
    }

    public function add(Game $game): void
    {
        $this->games[$game->getId()] = $game;
    }

    public function findById(int $id): ?Game
    {
        return $this->games[$id] ?? null;
    }

    public function findByName(string $name): ?Game
    {
        foreach ($this->games as $game) {
            if ($game->getName() === $name) {
                return $game;
            }
        }
        return null;
    }

    public function removeById(int $id): void
    {
        unset($this->games[$id]);
    }

    public static function fromArray(array $data): self
    {
        Assert::allIsArray($data);

        return new self(
            array_map(
                static fn (array $gameData) => Game::fromArray($gameData),
                $data,
            ),
        );
    }

    public function toArray(): array
    {
        return array_map(
            static fn (Game $game) => $game->toArray(),
            $this->games,
        );
    }
}