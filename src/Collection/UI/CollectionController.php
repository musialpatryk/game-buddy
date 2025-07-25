<?php

namespace App\Collection\UI;

use App\Collection\Application\Service\CollectionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CollectionController extends AbstractController
{
    public function __construct(
        private readonly CollectionService $collectionService,
    ) {
    }

    #[Route(
        path: '/collection-item/{gameId}',
        name: 'collection-item',
        methods: ['GET'])
    ]
    public function exists(int $gameId): JsonResponse
    {
        $this->collectionService->has($gameId);

        return $this->json(
            $this->collectionService->has($gameId)
        );
    }

    #[Route(
        path: '/collection-item/{gameId}',
        name: 'collection-item-assign',
        methods: ['PUT'])
    ]
    public function assign(int $gameId): JsonResponse
    {
        $this->collectionService->assign($gameId);

        return $this->json(
            null,
            Response::HTTP_NO_CONTENT
        );
    }

    #[Route(
        path: '/collection-item/{gameId}',
        name: 'collection-item-remove',
        methods: ['DELETE'])
    ]
    public function remove(int $gameId): JsonResponse
    {
        $this->collectionService->remove($gameId);

        return $this->json(
            null,
            Response::HTTP_NO_CONTENT
        );
    }
}