<?php

namespace App\Game\Application\Dto;

use Webmozart\Assert\Assert;

abstract readonly class GameDto
{
    private const NAME_KEY = 'name';

    public function __construct(
        private string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function fromArray(array $data): static
    {
        Assert::stringNotEmpty($data[self::NAME_KEY]);

        return new static(
            $data[self::NAME_KEY],
        );
    }
}