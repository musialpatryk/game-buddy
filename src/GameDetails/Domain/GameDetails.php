<?php

namespace App\GameDetails\Domain;

use Webmozart\Assert\Assert;

readonly class GameDetails implements \JsonSerializable
{
    private const GAME_ID = 'game_id';
    private const DESCRIPTION = 'description';
    private const DURATION = 'duration';
    private const MIN_PLAYERS = 'min_players';
    private const MAX_PLAYERS = 'max_players';
    private const CATEGORIES = 'categories';

    public function __construct(
        private int $gameId,
        private string $description,
        private int $duration,
        private int $minPlayers,
        private int $maxPlayers,
        private CategoryCollection $categories,
    ) {
    }

    public function getGameId(): int
    {
        return $this->gameId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCategories(): CategoryCollection
    {
        return $this->categories;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function getMinPlayers(): int
    {
        return $this->minPlayers;
    }

    public function getMaxPlayers(): int
    {
        return $this->maxPlayers;
    }

    public static function fromArray(array $data): self
    {
        Assert::integerish($data[self::GAME_ID]);
        Assert::stringNotEmpty($data[self::DESCRIPTION]);
        Assert::integerish($data[self::DURATION]);
        Assert::integerish($data[self::MIN_PLAYERS]);
        Assert::integerish($data[self::MAX_PLAYERS]);
        Assert::keyExists($data, self::CATEGORIES);

        return new self(
            $data[self::GAME_ID],
            $data[self::DESCRIPTION],
            $data[self::DURATION],
            $data[self::MIN_PLAYERS],
            $data[self::MAX_PLAYERS],
            CategoryCollection::fromArray($data[self::CATEGORIES]),
        );
    }

    public function toArray(): array
    {
        return [
            self::GAME_ID => $this->gameId,
            self::DESCRIPTION => $this->description,
            self::DURATION => $this->duration,
            self::MIN_PLAYERS => $this->minPlayers,
            self::MAX_PLAYERS => $this->maxPlayers,
            self::CATEGORIES => $this->categories,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}