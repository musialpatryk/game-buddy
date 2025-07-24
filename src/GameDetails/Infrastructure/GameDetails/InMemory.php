<?php

namespace App\GameDetails\Infrastructure\GameDetails;

use App\GameDetails\Application\Repository\GameDetailsRepository;
use App\GameDetails\Domain\GameDetails;

class InMemory implements GameDetailsRepository
{
    private ?GameDetails $gameDetails = null;

    public function find(int $gameId): ?GameDetails
    {
        return $this->gameDetails;
    }

    public function getName(int $gameId): string
    {
        return 'Test';
    }

    public function upsert(GameDetails $gameDetails)
    {
        $this->gameDetails = $gameDetails;
    }
}