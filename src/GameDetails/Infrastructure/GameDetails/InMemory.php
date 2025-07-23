<?php

namespace App\GameDetails\Infrastructure\GameDetails;

use App\GameDetails\Application\Repository\GameDetailsRepository;
use App\GameDetails\Domain\GameDetails;

class InMemory implements GameDetailsRepository
{
    public function find(int $gameId): ?GameDetails
    {
        return null;
    }
}