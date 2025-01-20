<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250120194445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE wardrobe_item (id SERIAL NOT NULL, customer_id INT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, brand VARCHAR(100) DEFAULT NULL, size VARCHAR(20) NOT NULL, color VARCHAR(50) NOT NULL, image VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, season VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_12D46B789395C3F3 ON wardrobe_item (customer_id)');
        $this->addSql('CREATE INDEX IDX_12D46B7812469DE2 ON wardrobe_item (category_id)');
        $this->addSql('COMMENT ON COLUMN wardrobe_item.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN wardrobe_item.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE wardrobe_item ADD CONSTRAINT FK_12D46B789395C3F3 FOREIGN KEY (customer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wardrobe_item ADD CONSTRAINT FK_12D46B7812469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE wardrobe_item DROP CONSTRAINT FK_12D46B789395C3F3');
        $this->addSql('ALTER TABLE wardrobe_item DROP CONSTRAINT FK_12D46B7812469DE2');
        $this->addSql('DROP TABLE wardrobe_item');
    }
}
