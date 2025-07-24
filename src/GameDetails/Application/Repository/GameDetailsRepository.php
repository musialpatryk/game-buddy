<?php

namespace App\GameDetails\Application\Repository;

use App\GameDetails\Domain\GameDetails;

interface GameDetailsRepository
{
    public function find(int $gameId): ?GameDetails;
    public function getName(int $gameId): string;
    public function upsert(GameDetails $gameDetails);
}