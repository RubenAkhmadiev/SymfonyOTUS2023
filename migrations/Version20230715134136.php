<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230715134136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "user_telegram_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE "user" DROP telegram_id');
        $this->addSql('ALTER TABLE user_telegram ADD id INT NOT NULL');
        $this->addSql('ALTER TABLE user_telegram ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "user_telegram_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "user_telegram" DROP CONSTRAINT "user_telegram_pkey"');
        $this->addSql('ALTER TABLE "user_telegram" DROP id');
        $this->addSql('ALTER TABLE "user" ADD telegram_id INT NOT NULL');
    }
}
