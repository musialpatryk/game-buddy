<?php

namespace App\GameDetails\Domain;

use Webmozart\Assert\Assert;

readonly class Category implements \JsonSerializable
{
    private const ID = 'id';
    private const NAME = 'name';

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
        Assert::integerish($data[self::ID]);
        Assert::stringNotEmpty($data[self::NAME]);

        return new self(
            $data[self::ID],
            $data[self::NAME],
        );
    }

    public function toArray(): array
    {
        return [
            self::ID => $this->id,
            self::NAME => $this->name,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}