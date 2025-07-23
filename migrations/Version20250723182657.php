<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250723182657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add game details';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS game_details (
                game_id BIGINT UNSIGNED PRIMARY KEY NOT NULL,
                description LONGTEXT NULL,
                FOREIGN KEY(game_id) REFERENCES game (id)
            )'
        );
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS game_category (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL
            )'
        );
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS game_category_assignment (
                game_id BIGINT UNSIGNED NOT NULL,
                category_id BIGINT UNSIGNED NOT NULL,
                PRIMARY KEY(game_id, category_id),
                FOREIGN KEY(game_id) REFERENCES game (id),
                FOREIGN KEY(category_id) REFERENCES game_category (id)
            )'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS game_category_assignment');
        $this->addSql('DROP TABLE IF EXISTS game_details');
        $this->addSql('DROP TABLE IF EXISTS game_category');
    }
}
