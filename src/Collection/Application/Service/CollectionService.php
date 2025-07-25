<?php

namespace App\Collection\Application\Service;

use App\Collection\Application\Exception\CurrentUserNotFound;
use App\Collection\Application\Repository\CollectionRepository;
use App\Collection\Application\Repository\UserRepository;
use App\Collection\Domain\User;

final readonly class CollectionService
{
    public function __construct(
        private UserRepository $userRepository,
        private CollectionRepository $collectionRepository
    ) {
    }

    public function has(int $gameId): bool
    {
        return $this->collectionRepository->has(
            $this->getCurrentUserId(),
            $gameId
        );
    }

    public function assign(int $gameId): void
    {
        $this->collectionRepository->assign(
            $this->getCurrentUserId(),
            $gameId,
        );
    }

    public function remove(int $gameId): void
    {
        $this->collectionRepository->remove(
            $this->getCurrentUserId(),
            $gameId,
        );
    }

    public function getCurrentUserId(): int
    {
        $currentUser = $this->userRepository->findCurrent();
        if (!$currentUser) {
            throw new CurrentUserNotFound();
        }

        return $currentUser->getId();
    }
}