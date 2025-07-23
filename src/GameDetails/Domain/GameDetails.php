<?php

namespace App\GameDetails\Domain;

use Webmozart\Assert\Assert;

readonly class GameDetails implements \JsonSerializable
{
    private const GAME_ID = 'game_id';
    private const DESCRIPTION = 'description';
    private const CATEGORIES = 'categories';

    public function __construct(
        private int $gameId,
        private string $description,
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

    public static function fromArray(array $data): self
    {
        Assert::integerish($data[self::GAME_ID]);
        Assert::stringNotEmpty($data[self::DESCRIPTION]);
        Assert::keyExists($data, self::CATEGORIES);

        return new self(
            $data[self::GAME_ID],
            $data[self::DESCRIPTION],
            CategoryCollection::fromArray($data[self::CATEGORIES]),
        );
    }

    public function toArray(): array
    {
        return [
            self::GAME_ID => $this->gameId,
            self::DESCRIPTION => $this->description,
            self::CATEGORIES => $this->categories,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}