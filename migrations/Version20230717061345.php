<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230717061345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS item');
        $this->addSql('DROP TABLE IF EXISTS item_order');
        $this->addSql('CREATE TABLE product_order (product_id INT NOT NULL, order_id INT NOT NULL, PRIMARY KEY(product_id, order_id))');
        $this->addSql('CREATE INDEX IDX_DF8E8848126F525E ON product_order (product_id)');
        $this->addSql('CREATE INDEX IDX_DF8E88488D9F6D38 ON product_order (order_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE item (id INT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE item_order (item_id INT NOT NULL, order_id INT NOT NULL, PRIMARY KEY(item_id, order_id))');
        $this->addSql('CREATE INDEX IDX_DF8E8848126F525E ON item_order (item_id)');
        $this->addSql('CREATE INDEX IDX_DF8E88488D9F6D38 ON item_order (order_id)');
        $this->addSql('DROP TABLE IF EXISTS product_order');
    }
}
