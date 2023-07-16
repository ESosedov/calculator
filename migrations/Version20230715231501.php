<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230715231501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create and fill table products';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE products (id BIGSERIAL NOT NULL, title VARCHAR(255) NOT NULL, cost INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON TABLE products IS \'Товар\'');
        $this->addSql('COMMENT ON COLUMN products.title IS \'Наименование товара\'');
        $this->addSql('COMMENT ON COLUMN products.cost IS \'Стоимость товара, евроцент\'');
        $this->addSql('INSERT INTO products (title, cost) VALUES (\'Iphone\', \'10000\')');
        $this->addSql('INSERT INTO products (title, cost) VALUES (\'Наушники\', \'2000\')');
        $this->addSql('INSERT INTO products (title, cost) VALUES (\'Чехол\', \'1000\')');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE products');
    }
}
