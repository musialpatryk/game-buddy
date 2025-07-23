<?php

namespace App\GameDetails\Application\Service;

use App\GameDetails\Application\Exception\GameDetailsDoesNotExists;
use App\GameDetails\Application\Repository\GameDetailsRepository;
use App\GameDetails\Domain\GameDetails;

readonly class GameDetailsService
{
    public function __construct(
        private GameDetailsRepository $gameDetailsRepository,
    ) {
    }

    public function find(int $gameId): GameDetails
    {
        $details = $this->gameDetailsRepository->find($gameId);
        if (!$details) {
            throw new GameDetailsDoesNotExists();
        }
        return $details;
    }
}