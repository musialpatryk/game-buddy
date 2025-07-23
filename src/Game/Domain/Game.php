<?php

namespace App\Game\Domain;

use Webmozart\Assert\Assert;

readonly class Game implements \JsonSerializable
{
    private const ID_KEY = 'id';
    private const NAME_KEY = 'name';

    public function __construct(
        private int $id,
        private string $name,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function fromArray(array $data): self
    {
        Assert::integerish($data[self::ID_KEY]);
        Assert::stringNotEmpty($data[self::NAME_KEY]);

        return new self(
            $data[self::ID_KEY],
            $data[self::NAME_KEY],
        );
    }

    public function toArray(): array
    {
        return [
            self::ID_KEY => $this->id,
            self::NAME_KEY => $this->name,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}