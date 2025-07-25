<?php

namespace App\Game\UI;

use App\Game\Application\Dto\CreateGameDto;
use App\Game\Application\Dto\GameFilters;
use App\Game\Application\Dto\UpdateGameDto;
use App\Game\Application\Service\GameManagementService;
use App\SharedKernel\Request\PayloadFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GameController extends AbstractController
{
    private const ASSIGNED_TO_USER = 'assignedToUser';

    public function __construct(
        private readonly GameManagementService $gameManagementService,
    ) {
    }

    #[Route(
        path: '/games/{id}',
        name: 'games-item',
        methods: ['GET'])
    ]
    public function get(int $id): JsonResponse
    {
        return $this->json(
            $this->gameManagementService->get($id),
        );
    }

    #[Route(
        path: '/games',
        name: 'games-list',
        methods: ['GET'])
    ]
    public function getAll(Request $request): JsonResponse
    {
        return $this->json(
            $this->gameManagementService->getAll(
                new GameFilters($request->query->all())
            ),
        );
    }

    #[Route(
        path: '/games',
        name: 'games-create',
        methods: ['POST'])
    ]
    public function create(Request $request): JsonResponse
    {
        $payload = PayloadFactory::fromRequest($request);
        $createdGame = $this->gameManagementService->create(
            CreateGameDto::fromArray($payload),
        );

        return $this->json(
            $createdGame,
            Response::HTTP_CREATED,
        );
    }

    #[Route(
        path: '/games/{id}',
        name: 'games-update',
        methods: ['PUT'])
    ]
    public function update(int $id, Request $request): JsonResponse
    {
        $payload = PayloadFactory::fromRequest($request);
        $updatedGame = $this->gameManagementService->update(
            $id,
            UpdateGameDto::fromArray($payload),
        );

        return $this->json(
            $updatedGame->toArray(),
            Response::HTTP_OK,
        );
    }

    #[Route(
        path: '/games/{id}',
        name: 'games-delete',
        methods: ['DELETE'])
    ]
    public function delete(int $id): JsonResponse
    {
        $this->gameManagementService->delete($id);

        return $this->json(
            null,
            Response::HTTP_NO_CONTENT
        );
    }
}