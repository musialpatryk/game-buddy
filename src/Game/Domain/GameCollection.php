<?php

namespace App\Game\Domain;

use Webmozart\Assert\Assert;

readonly class GameCollection
{
    /**
     * @param Game[] $games
     */
    public function __construct(
        private array $games,
    ) {
    }

    public function findByName(string $name): ?Game
    {
        $game = null;
        foreach ($this->games as $game) {
            if ($game->getName() === $name) {
                return $game;
            }
        }
        return $game;
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
}