<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230711203629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD email VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP login');
        $this->addSql('ALTER TABLE "user" DROP creation_date');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE backoffice."user" ADD email VARCHAR(180) NOT NULL');
        $this->addSql('ALTER TABLE backoffice."user" ADD roles JSON NOT NULL');
        $this->addSql('ALTER TABLE backoffice."user" ADD password VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_909E28AE7927C74 ON backoffice."user" (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_909E28AE7927C74');
        $this->addSql('ALTER TABLE "backoffice"."user" DROP email');
        $this->addSql('ALTER TABLE "backoffice"."user" DROP roles');
        $this->addSql('ALTER TABLE "backoffice"."user" DROP password');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('ALTER TABLE "user" ADD login VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD creation_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP email');
    }
}
