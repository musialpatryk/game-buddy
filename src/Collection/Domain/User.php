<?php

namespace App\Collection\Domain;

use Webmozart\Assert\Assert;

readonly class User
{
    private const ID = 'id';
    private const USERNAME = 'username';

    public function __construct(
        private int $id,
        private string $username,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public static function fromArray(array $data): self
    {
        Assert::integerish($data[self::ID]);
        Assert::string($data[self::USERNAME]);

        return new self(
            $data[self::ID],
            $data[self::USERNAME],
        );
    }
}