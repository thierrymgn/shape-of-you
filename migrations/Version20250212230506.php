<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250212230506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE category (id SERIAL NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, icon VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, lft INT NOT NULL, rgt INT NOT NULL, level INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_64C19C1727ACA70 ON category (parent_id)');
        $this->addSql('COMMENT ON COLUMN category.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN category.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE follow (id SERIAL NOT NULL, follower_id INT DEFAULT NULL, following_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_68344470AC24F853 ON follow (follower_id)');
        $this->addSql('CREATE INDEX IDX_683444701816E3A3 ON follow (following_id)');
        $this->addSql('COMMENT ON COLUMN follow.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE outfit (id SERIAL NOT NULL, customer_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, occasion VARCHAR(100) NOT NULL, season VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_public BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_320296019395C3F3 ON outfit (customer_id)');
        $this->addSql('COMMENT ON COLUMN outfit.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN outfit.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE outfit_item (id SERIAL NOT NULL, outfit_id INT NOT NULL, wardrobe_item_id INT NOT NULL, position INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_98142D2AE96E385 ON outfit_item (outfit_id)');
        $this->addSql('CREATE INDEX IDX_98142D23B14586B ON outfit_item (wardrobe_item_id)');
        $this->addSql('COMMENT ON COLUMN outfit_item.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN outfit_item.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE profile (id SERIAL NOT NULL, height NUMERIC(5, 2) DEFAULT NULL, weight NUMERIC(5, 2) DEFAULT NULL, body_type VARCHAR(50) DEFAULT NULL, style_preferences JSON NOT NULL, color_preferences JSON NOT NULL, size_preferences JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE tag (id SERIAL NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_389B783989D9B62 ON tag (slug)');
        $this->addSql('COMMENT ON COLUMN tag.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN tag.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, profile_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649CCFA12B8 ON "user" (profile_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('CREATE TABLE wardrobe_item (id SERIAL NOT NULL, customer_id INT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, brand VARCHAR(100) DEFAULT NULL, size VARCHAR(20) NOT NULL, color VARCHAR(50) NOT NULL, image VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, season VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_12D46B789395C3F3 ON wardrobe_item (customer_id)');
        $this->addSql('CREATE INDEX IDX_12D46B7812469DE2 ON wardrobe_item (category_id)');
        $this->addSql('COMMENT ON COLUMN wardrobe_item.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN wardrobe_item.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE wardrobe_item_tag (id SERIAL NOT NULL, wardrobe_item_id INT NOT NULL, tag_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B20673983B14586B ON wardrobe_item_tag (wardrobe_item_id)');
        $this->addSql('CREATE INDEX IDX_B2067398BAD26311 ON wardrobe_item_tag (tag_id)');
        $this->addSql('COMMENT ON COLUMN wardrobe_item_tag.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN wardrobe_item_tag.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow ADD CONSTRAINT FK_68344470AC24F853 FOREIGN KEY (follower_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow ADD CONSTRAINT FK_683444701816E3A3 FOREIGN KEY (following_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE outfit ADD CONSTRAINT FK_320296019395C3F3 FOREIGN KEY (customer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE outfit_item ADD CONSTRAINT FK_98142D2AE96E385 FOREIGN KEY (outfit_id) REFERENCES outfit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE outfit_item ADD CONSTRAINT FK_98142D23B14586B FOREIGN KEY (wardrobe_item_id) REFERENCES wardrobe_item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wardrobe_item ADD CONSTRAINT FK_12D46B789395C3F3 FOREIGN KEY (customer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wardrobe_item ADD CONSTRAINT FK_12D46B7812469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wardrobe_item_tag ADD CONSTRAINT FK_B20673983B14586B FOREIGN KEY (wardrobe_item_id) REFERENCES wardrobe_item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wardrobe_item_tag ADD CONSTRAINT FK_B2067398BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE category DROP CONSTRAINT FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE follow DROP CONSTRAINT FK_68344470AC24F853');
        $this->addSql('ALTER TABLE follow DROP CONSTRAINT FK_683444701816E3A3');
        $this->addSql('ALTER TABLE outfit DROP CONSTRAINT FK_320296019395C3F3');
        $this->addSql('ALTER TABLE outfit_item DROP CONSTRAINT FK_98142D2AE96E385');
        $this->addSql('ALTER TABLE outfit_item DROP CONSTRAINT FK_98142D23B14586B');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649CCFA12B8');
        $this->addSql('ALTER TABLE wardrobe_item DROP CONSTRAINT FK_12D46B789395C3F3');
        $this->addSql('ALTER TABLE wardrobe_item DROP CONSTRAINT FK_12D46B7812469DE2');
        $this->addSql('ALTER TABLE wardrobe_item_tag DROP CONSTRAINT FK_B20673983B14586B');
        $this->addSql('ALTER TABLE wardrobe_item_tag DROP CONSTRAINT FK_B2067398BAD26311');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE follow');
        $this->addSql('DROP TABLE outfit');
        $this->addSql('DROP TABLE outfit_item');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE wardrobe_item');
        $this->addSql('DROP TABLE wardrobe_item_tag');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
