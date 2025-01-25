<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250125185656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE outfit (id SERIAL NOT NULL, customer_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, occasion VARCHAR(100) NOT NULL, season VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_320296019395C3F3 ON outfit (customer_id)');
        $this->addSql('COMMENT ON COLUMN outfit.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN outfit.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE outfit_item (id SERIAL NOT NULL, outfit_id INT NOT NULL, wardrobe_item_id INT NOT NULL, position INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_98142D2AE96E385 ON outfit_item (outfit_id)');
        $this->addSql('CREATE INDEX IDX_98142D23B14586B ON outfit_item (wardrobe_item_id)');
        $this->addSql('COMMENT ON COLUMN outfit_item.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN outfit_item.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE outfit ADD CONSTRAINT FK_320296019395C3F3 FOREIGN KEY (customer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE outfit_item ADD CONSTRAINT FK_98142D2AE96E385 FOREIGN KEY (outfit_id) REFERENCES outfit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE outfit_item ADD CONSTRAINT FK_98142D23B14586B FOREIGN KEY (wardrobe_item_id) REFERENCES wardrobe_item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE outfit DROP CONSTRAINT FK_320296019395C3F3');
        $this->addSql('ALTER TABLE outfit_item DROP CONSTRAINT FK_98142D2AE96E385');
        $this->addSql('ALTER TABLE outfit_item DROP CONSTRAINT FK_98142D23B14586B');
        $this->addSql('DROP TABLE outfit');
        $this->addSql('DROP TABLE outfit_item');
    }
}
