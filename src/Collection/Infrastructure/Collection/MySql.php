<?php

namespace App\Collection\Infrastructure\Collection;

use App\Collection\Application\Repository\CollectionRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;

class MySql implements CollectionRepository
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function assign(int $userId, int $gameId): void
    {
        $this->connection->executeStatement(
            'INSERT INTO game_user_assignment (user_id, game_id) 
            VALUES (:user_id, :game_id) ON DUPLICATE KEY UPDATE game_id = VALUES(game_id)',
            [
                'user_id' => $userId,
                'game_id' => $gameId
            ],
            [
                'user_id' => ParameterType::INTEGER,
                'game_id' => ParameterType::INTEGER,
            ]
        );
    }

    public function remove(int $userId, int $gameId): void
    {
        $this->connection->executeStatement(
            'DELETE FROM game_user_assignment gua
            WHERE gua.game_id = :game_id AND gua.user_id = :user_id',
            [
                'user_id' => $userId,
                'game_id' => $gameId
            ],
            [
                'user_id' => ParameterType::INTEGER,
                'game_id' => ParameterType::INTEGER,
            ]
        );
    }

    public function has(int $userId, int $gameId): bool
    {
        return (bool)$this->connection->fetchOne(
            'SELECT gua.user_id FROM game_user_assignment gua
            WHERE gua.game_id = :game_id AND gua.user_id = :user_id',
            [
                'user_id' => $userId,
                'game_id' => $gameId
            ],
            [
                'user_id' => ParameterType::INTEGER,
                'game_id' => ParameterType::INTEGER,
            ]
        );
    }
}