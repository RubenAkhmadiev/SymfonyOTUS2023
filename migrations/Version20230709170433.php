<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230709170433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Test products';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO item (id, name, price) values (1, \'Orange1\', 10)');
        $this->addSql('INSERT INTO item (id, name, price) values (2, \'Orange2\', 10)');
        $this->addSql('INSERT INTO item (id, name, price) values (3, \'Orange3\', 10)');
        $this->addSql('INSERT INTO item (id, name, price) values (4, \'Orange4\', 10)');
        $this->addSql('INSERT INTO item (id, name, price) values (5, \'Orange5\', 10)');
        $this->addSql('INSERT INTO item (id, name, price) values (6, \'Orange6\', 10)');
        $this->addSql('INSERT INTO item (id, name, price) values (7, \'Orange7\', 10)');
        $this->addSql('INSERT INTO item (id, name, price) values (8, \'Orange8\', 10)');
        $this->addSql('INSERT INTO item (id, name, price) values (9, \'Orange9\', 10)');
        $this->addSql('INSERT INTO item (id, name, price) values (10, \'Orange10\', 10)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM item');
    }
}
