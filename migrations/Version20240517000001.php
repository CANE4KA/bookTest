<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240517000001 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(sql: 'CREATE TABLE books
                            (
                                id INT AUTO_INCREMENT NOT NULL,
                                name TEXT(50) NOT NULL,
                                author TEXT(30) NOT NULL,
                                year YEAR NOT NULL,
                                PRIMARY KEY(id),
                                UNIQUE KEY name_key (name(50))
                            )'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE books');
    }
}
