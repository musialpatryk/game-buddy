<?php

namespace App\GameDetails\UI;

use App\GameDetails\Application\Service\GameDetailsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GameDetailsController extends AbstractController
{
    public function __construct(
        private readonly GameDetailsService $gameDetailsService,
    ) {
    }

    #[Route(
        path: '/game-details/{gameId}',
        name: 'game-details-item',
        methods: ['GET'])
    ]
    public function get(int $gameId): JsonResponse
    {
        return $this->json(
            $this->gameDetailsService->get($gameId),
        );
    }

}