<?php

namespace App\Game\Application\Dto;

final readonly class GameFilters
{
    public const USER_ID = 'userId';

    /**
     * @param array<string, mixed> $filters
     */
    public function __construct(private array $filters)
    {
    }

    public function getUserId(): ?int
    {
        if (!isset($this->filters[self::USER_ID])) {
            return null;
        }

        return (int)$this->filters[self::USER_ID];
    }
}