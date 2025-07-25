<?php

namespace App\Game\Infrastructure\Game;

use App\Game\Application\Dto\CreateGameDto;
use App\Game\Application\Dto\GameFilters;
use App\Game\Application\Dto\UpdateGameDto;
use App\Game\Application\Repository\GameRepository;
use App\Game\Domain\Game;
use App\Game\Domain\GameCollection;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;

readonly class MySql implements GameRepository
{
    public function __construct(
        private Connection $connection,
    ) {
    }

    public function getAll(GameFilters $gameFilters): GameCollection
    {
        if ($userId = $gameFilters->getUserId()) {
            return GameCollection::fromArray(
                $this->connection->fetchAllAssociative(
                    'SELECT g.id, g.name FROM game g
                    INNER JOIN game_user_assignment gua ON g.id = gua.game_id
                    WHERE gua.user_id = :user_id',
                    [
                        'user_id' => $userId,
                    ],
                    [
                        'user_id' => ParameterType::INTEGER,
                    ]
                )
            );
        }

        return GameCollection::fromArray(
            $this->connection->fetchAllAssociative(
                'SELECT g.id, g.name FROM game g'
            )
        );
    }

    public function findById(int $id): ?Game
    {
        $data = $this->connection->fetchAssociative(
            'SELECT g.id, g.name FROM game g
                    WHERE g.id = :id',
            [
                'id' => $id,
            ],
            [
                'id' => ParameterType::INTEGER,
            ],
        );

        if (!$data) {
            return null;
        }

        return Game::fromArray($data);
    }

    public function findByName(string $name): ?Game
    {
        $data = $this->connection->fetchAssociative(
            'SELECT g.id, g.name FROM game g
                    WHERE g.name = :name',
            [
                'name' => $name,
            ],
            [
                'name' => ParameterType::STRING,
            ],
        );

        if (!$data) {
            return null;
        }

        return Game::fromArray($data);
    }

    public function create(CreateGameDto $gameDto): Game
    {
        $this->connection->executeStatement(
            'INSERT INTO game (name) VALUES (:name)',
            [
                'name' => $gameDto->getName(),
            ],
            [
                'name' => ParameterType::STRING,
            ]
        );

        return $this->findById($this->connection->lastInsertId());
    }

    public function update(int $id, UpdateGameDto $gameToUpdate): Game
    {
        $this->connection->executeStatement(
            'UPDATE game g
            SET name = :name
            WHERE id = :id',
            [
                'name' => $gameToUpdate->getName(),
                'id' => $id,
            ],
            [
                'name' => ParameterType::STRING,
                'id' => ParameterType::INTEGER,
            ]
        );

        return $this->findById($id);
    }

    public function delete(int $id): void
    {
        $this->connection->executeStatement(
            'DELETE FROM game WHERE id = :id',
            [
                'id' => $id,
            ],
            [
                'id' => ParameterType::INTEGER,
            ]
        );
    }
}