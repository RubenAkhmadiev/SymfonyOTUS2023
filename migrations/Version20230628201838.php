<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230628201838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA backoffice');
        $this->addSql('CREATE SEQUENCE "backoffice"."partner_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "backoffice"."user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "backoffice"."partner" (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "backoffice"."user" (id INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "backoffice"."partner_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "backoffice"."user_id_seq" CASCADE');
        $this->addSql('DROP TABLE "backoffice"."partner"');
        $this->addSql('DROP TABLE "backoffice"."user"');
    }
}
