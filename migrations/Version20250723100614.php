<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250723100614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add basic game table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE game (
                id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
                name VARCHAR(255) NOT NULL
            );',
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            'DROP TABLE game;',
        );
    }
}
