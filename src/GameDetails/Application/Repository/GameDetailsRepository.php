<?php

namespace App\GameDetails\Application\Repository;

use App\GameDetails\Domain\GameDetails;

interface GameDetailsRepository
{
    public function find(int $gameId): ?GameDetails;
}