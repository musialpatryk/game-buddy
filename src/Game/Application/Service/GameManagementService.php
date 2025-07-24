<?php

namespace App\Game\Application\Service;

use App\Game\Application\Dto\CreateGameDto;
use App\Game\Application\Dto\UpdateGameDto;
use App\Game\Application\Event\GameCreated;
use App\Game\Application\Event\GameUpdated;
use App\Game\Application\Exception\GameAlreadyExists;
use App\Game\Application\Exception\GameDoesNotExists;
use App\Game\Application\Repository\GameRepository;
use App\Game\Domain\Game;
use App\Game\Domain\GameCollection;
use Psr\EventDispatcher\EventDispatcherInterface;

readonly class GameManagementService
{
    public function __construct(
        private GameRepository $gameRepository,
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    public function get(int $id): Game
    {
        $searchedGame = $this->gameRepository->findById($id);
        if (!$searchedGame) {
            throw new GameDoesNotExists();
        }
        return $searchedGame;
    }

    public function getAll(): GameCollection
    {
        return $this->gameRepository->getAll();
    }

    public function create(CreateGameDto $newGame): Game
    {
        $existingGame = $this->gameRepository->findByName($newGame->getName());
        if ($existingGame) {
            throw new GameAlreadyExists();
        }

        $createdGame = $this->gameRepository->create($newGame);
        $this->dispatcher->dispatch(
            new GameCreated($createdGame)
        );

        return $createdGame;
    }

    public function update(
        int $id,
        UpdateGameDto $gameToUpdate,
    ): Game {
        $existingGame = $this->gameRepository->findById($id);
        if (!$existingGame) {
            throw new GameDoesNotExists();
        }

        $updatedGame = $this->gameRepository->update($id, $gameToUpdate);
        $this->dispatcher->dispatch(
            new GameUpdated($updatedGame)
        );

        return $updatedGame;
    }

    public function delete(int $id): void
    {
        $existingGame = $this->gameRepository->findById($id);
        if (!$existingGame) {
            throw new GameDoesNotExists();
        }

        $this->gameRepository->delete($id);
    }
}