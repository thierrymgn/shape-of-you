<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250203105926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE tag (id SERIAL NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_389B783989D9B62 ON tag (slug)');
        $this->addSql('COMMENT ON COLUMN tag.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN tag.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE wardrobe_item_tag (id SERIAL NOT NULL, wardrobe_item_id INT NOT NULL, tag_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B20673983B14586B ON wardrobe_item_tag (wardrobe_item_id)');
        $this->addSql('CREATE INDEX IDX_B2067398BAD26311 ON wardrobe_item_tag (tag_id)');
        $this->addSql('COMMENT ON COLUMN wardrobe_item_tag.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN wardrobe_item_tag.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE wardrobe_item_tag ADD CONSTRAINT FK_B20673983B14586B FOREIGN KEY (wardrobe_item_id) REFERENCES wardrobe_item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wardrobe_item_tag ADD CONSTRAINT FK_B2067398BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE wardrobe_item_tag DROP CONSTRAINT FK_B20673983B14586B');
        $this->addSql('ALTER TABLE wardrobe_item_tag DROP CONSTRAINT FK_B2067398BAD26311');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE wardrobe_item_tag');
    }
}
