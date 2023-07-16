<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230716214724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE backoffice.product_category (product_id INT NOT NULL, category_id INT NOT NULL, PRIMARY KEY(product_id, category_id))');
        $this->addSql('CREATE INDEX IDX_9F45084B4584665A ON backoffice.product_category (product_id)');
        $this->addSql('CREATE INDEX IDX_9F45084B12469DE2 ON backoffice.product_category (category_id)');
        $this->addSql('ALTER TABLE backoffice.product_category ADD CONSTRAINT FK_9F45084B4584665A FOREIGN KEY (product_id) REFERENCES "backoffice"."product" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE backoffice.product_category ADD CONSTRAINT FK_9F45084B12469DE2 FOREIGN KEY (category_id) REFERENCES "backoffice"."category" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE backoffice.product_category DROP CONSTRAINT FK_9F45084B4584665A');
        $this->addSql('ALTER TABLE backoffice.product_category DROP CONSTRAINT FK_9F45084B12469DE2');
        $this->addSql('DROP TABLE backoffice.product_category');
    }
}
