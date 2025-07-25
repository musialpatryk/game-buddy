<?php

namespace App\Collection\Infrastructure\User;

use App\Collection\Application\Repository\UserRepository;
use App\Collection\Domain\User;

class InMemory implements UserRepository
{
    private const DEFAULT_USER = 1;

    public function findCurrent(): ?User
    {
        return new User(
            self::DEFAULT_USER,
            sprintf(
                'User %s', self::DEFAULT_USER,
            ),
        );
    }
}