<?php

namespace App\GameDetails\Application\Service;

use App\GameDetails\Application\Exception\GameDetailsDoesNotExists;
use App\GameDetails\Application\Repository\GameDetailsRepository;
use App\GameDetails\Domain\GameDetails;
use App\SharedKernel\Service\GeminiAIService;

readonly class GameDetailsService
{
    public function __construct(
        private GameDetailsRepository $gameDetailsRepository,
        private GeminiAIService $geminiAIService,
    ) {
    }

    public function get(int $gameId): GameDetails
    {
        $details = $this->gameDetailsRepository->find($gameId);
        if (!$details) {
            throw new GameDetailsDoesNotExists();
        }
        return $details;
    }

    public function upsert(GameDetails $gameDetails): void
    {
        $this->gameDetailsRepository->upsert($gameDetails);
    }

    public function generate(int $gameId): ?GameDetails
    {
        if ($details = $this->gameDetailsRepository->find($gameId)) {
            return $details;
        }

        $gameName = $this->gameDetailsRepository->getName($gameId);

        $data = $this->geminiAIService->generate(
            [
                'text' => sprintf(
                    'Przygotuj zestaw danych, który opiszę grę o nazwie %s w języku polskim.',
                    $gameName,
                ),
            ],
            [
                'type' => 'object',
                'nullable' => true,
                'description' => 'Obiekt reprezentujący informacje o grze, null jeśli nie udało się zebrać informacji.',
                'required' => [
                    'description',
                    'duration',
                    'min_players',
                    'max_players',
                    'categories',
                ],
                'properties' => [
                    'description' => [
                        'type' => 'string',
                        'nullable' => false,
                        'description' => 'Opis tematyki oraz zasad gry w języku polskim. Minimalna długość: 1000 zzs',
                    ],
                    'duration' => [
                        'type' => 'integer',
                        'nullable' => false,
                        'description' => 'Orientacyjna długość trwania rozgrywki w minutach',
                    ],
                    'min_players' => [
                        'type' => 'integer',
                        'nullable' => false,
                        'description' => 'Minimalna liczba graczy',
                    ],
                    'max_players' => [
                        'type' => 'integer',
                        'nullable' => false,
                        'description' => 'Maksymalna liczba graczy',
                    ],
                    'categories' => [
                        'type' => 'array',
                        'nullable' => false,
                        'description' => 'Kategorie, do których pasuje dana gra',
                        'items' => [
                            'type' => 'object',
                            'required' => [
                                'name',
                            ],
                            'properties' => [
                                'name' => [
                                    'type' => 'string',
                                    'nullable' => false,
                                    'description' => 'Nazwa kategorii, zaczynająca się wielką literą.',
                                ]
                            ]
                        ],
                    ],
                ]
            ]
        );
        if (!$data) {
            return null;
        }

        return GameDetails::fromArray(
            array_merge(
                $data,
                [
                    'game_id' => $gameId,
                ],
            )
        );
    }
}