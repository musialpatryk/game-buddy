<?php

namespace App\GameDetails\Infrastructure\GameDetails;

use App\GameDetails\Application\Repository\GameDetailsRepository;
use App\GameDetails\Domain\GameDetails;
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
                JSON_ARRAYAGG(
                    JSON_OBJECT(\'id\', gc.id, \'name\', gc.name)
                ) as categories
            FROM game_details gd
            LEFT JOIN game_category_assignment gca ON gca.game_id = gd.game_id
            INNER JOIN game_category gc ON gc.id = gca.category_id
            WHERE gd.game_id = :game_id
            GROUP BY :game_id',
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
}