<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230706182242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE address_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE address (id INT NOT NULL, profile_id INT NOT NULL, city VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, building VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D4E6F81CCFA12B8 ON address (profile_id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81CCFA12B8 FOREIGN KEY (profile_id) REFERENCES user_profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_profile DROP addresses');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE address_id_seq CASCADE');
        $this->addSql('ALTER TABLE address DROP CONSTRAINT FK_D4E6F81CCFA12B8');
        $this->addSql('DROP TABLE address');
        $this->addSql('ALTER TABLE user_profile ADD addresses JSON NOT NULL');
    }
}
