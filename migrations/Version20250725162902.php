<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250725162902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Store user game collection';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS game_user_assignment (
                game_id BIGINT UNSIGNED NOT NULL,
                user_id BIGINT UNSIGNED NOT NULL,
                PRIMARY KEY(game_id, user_id),
                FOREIGN KEY(game_id) REFERENCES game (id)
            )'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            'DROP TABLE game_user_assignment'
        );
    }
}
