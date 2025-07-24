<?php

namespace App\GameDetails\Infrastructure\GameDetails;

use App\GameDetails\Application\Repository\GameDetailsRepository;
use App\GameDetails\Domain\CategoryCollection;
use App\GameDetails\Domain\GameDetails;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;

readonly class MySql implements GameDetailsRepository
{
    public function __construct(private Connection $connection)
    {
    }

    public function find(int $gameId): ?GameDetails
    {
        $data = $this->connection->fetchAssociative(
            'SELECT 
                gd.game_id,
                gd.description,
                gd.duration,
                gd.min_players,
                gd.max_players,
                JSON_ARRAYAGG(
                    JSON_OBJECT(\'id\', gc.id, \'name\', gc.name)
                ) as categories
            FROM game_details gd
            LEFT JOIN game_category_assignment gca ON gca.game_id = gd.game_id
            INNER JOIN game_category gc ON gc.id = gca.category_id
            WHERE gd.game_id = :game_id
            GROUP BY gd.game_id',
            [
                'game_id' => $gameId,
            ],
            [
                'game_id' => ParameterType::INTEGER,
            ]
        );

        if (!$data) {
            return null;
        }

        $data['categories'] = json_decode(
            $data['categories'],
            true,
            flags: JSON_THROW_ON_ERROR
        );

        return GameDetails::fromArray($data);
    }

    public function getName(int $gameId): string
    {
        return $this->connection->fetchOne(
            'SELECT g.name FROM game g
            WHERE g.id = :game_id',
            [
                'game_id' => $gameId,
            ],
            [
                'game_id' => ParameterType::INTEGER,
            ]
        );
    }

    public function upsert(GameDetails $gameDetails)
    {
        try {
            $this->connection->beginTransaction();

            $this->connection->executeStatement(
                'INSERT INTO game_details (game_id, description, duration, min_players, max_players) 
                VALUES (:game_id, :description, :duration, :min_players, :max_players)
                ON DUPLICATE KEY UPDATE game_id = VALUES(game_id)',
                [
                    'game_id' => $gameDetails->getGameId(),
                    'description' => $gameDetails->getDescription(),
                    'duration' => $gameDetails->getDuration(),
                    'min_players' => $gameDetails->getMinPlayers(),
                    'max_players' => $gameDetails->getMaxPlayers(),
                ],
            );
            $this->upsertCategories(
                $gameDetails->getCategories()
            );
            $this->assignCategories(
                $gameDetails->getGameId(),
                $gameDetails->getCategories(),
            );

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw $exception;
        }
    }

    private function upsertCategories(CategoryCollection $categoryCollection): void
    {
        $names = $categoryCollection->getNames();
        if (empty($names)) {
            return;
        }

        $placeholders = implode(', ', array_fill(0, count($names), '(?)'));
        $types = array_fill(0, count($names), ParameterType::STRING);
        $this->connection->executeStatement(
            'INSERT INTO game_category (name) 
                VALUES ' . $placeholders . '
                ON DUPLICATE KEY UPDATE name = VALUES(name)',
            $names,
            $types,
        );
    }

    private function assignCategories(
        int $gameId,
        CategoryCollection $categoryCollection,
    ): void {
        $this->connection->executeStatement(
            'INSERT INTO game_category_assignment (game_id, category_id)
                SELECT :game_id, gc.id FROM game_category gc
                WHERE gc.name IN (:category_names)
                ON DUPLICATE KEY UPDATE category_id = VALUES(category_id)',
            [
                'game_id' => $gameId,
                'category_names' => $categoryCollection->getNames()
            ],
            [
                'game_id' => ParameterType::INTEGER,
                'category_names' => ArrayParameterType::STRING,
            ]
        );
    }
}