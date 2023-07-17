<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230717192924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE item_id_seq CASCADE');
        $this->addSql('ALTER TABLE "order" ADD status VARCHAR(255)');
        $this->addSql('ALTER TABLE product_order ADD CONSTRAINT FK_5475E8C44584665A FOREIGN KEY (product_id) REFERENCES "backoffice"."product" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_order ADD CONSTRAINT FK_5475E8C48D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER INDEX idx_df8e8848126f525e RENAME TO IDX_5475E8C44584665A');
        $this->addSql('ALTER INDEX idx_df8e88488d9f6d38 RENAME TO IDX_5475E8C48D9F6D38');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE product_order DROP CONSTRAINT FK_5475E8C44584665A');
        $this->addSql('ALTER TABLE product_order DROP CONSTRAINT FK_5475E8C48D9F6D38');
        $this->addSql('ALTER INDEX idx_5475e8c48d9f6d38 RENAME TO idx_df8e88488d9f6d38');
        $this->addSql('ALTER INDEX idx_5475e8c44584665a RENAME TO idx_df8e8848126f525e');
        $this->addSql('ALTER TABLE "order" DROP status');
    }
}
