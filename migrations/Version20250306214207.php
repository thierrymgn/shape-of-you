<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250306214207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE acquisition (id SERIAL NOT NULL, purchase_date DATE NOT NULL, price NUMERIC(10, 2) NOT NULL, store VARCHAR(255) NOT NULL, condition VARCHAR(255) NOT NULL, warrenty_end DATE NOT NULL, receipt_image VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN acquisition.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN acquisition.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE ai_analysis (id SERIAL NOT NULL, outfit_id_id INT DEFAULT NULL, wardrobe_item_id_id INT DEFAULT NULL, analysis_type VARCHAR(255) NOT NULL, results JSON NOT NULL, confidence_score NUMERIC(5, 2) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1FD54150ED808AAB ON ai_analysis (outfit_id_id)');
        $this->addSql('CREATE INDEX IDX_1FD541504A7BE2EB ON ai_analysis (wardrobe_item_id_id)');
        $this->addSql('COMMENT ON COLUMN ai_analysis.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE category (id SERIAL NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, icon VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, lft INT NOT NULL, rgt INT NOT NULL, level INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_64C19C1727ACA70 ON category (parent_id)');
        $this->addSql('COMMENT ON COLUMN category.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN category.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE comment (id SERIAL NOT NULL, user_id_id INT DEFAULT NULL, post_id_id INT DEFAULT NULL, comment_id_id INT DEFAULT NULL, content TEXT NOT NULL, level INT NOT NULL, replies_count INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526C9D86650F ON comment (user_id_id)');
        $this->addSql('CREATE INDEX IDX_9474526CE85F12B8 ON comment (post_id_id)');
        $this->addSql('CREATE INDEX IDX_9474526CD6DE06A6 ON comment (comment_id_id)');
        $this->addSql('COMMENT ON COLUMN comment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN comment.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE comment_like (id SERIAL NOT NULL, comment_id_id INT DEFAULT NULL, user_id_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8A55E25FD6DE06A6 ON comment_like (comment_id_id)');
        $this->addSql('CREATE INDEX IDX_8A55E25F9D86650F ON comment_like (user_id_id)');
        $this->addSql('COMMENT ON COLUMN comment_like.created_at IS \'(DC2Type:datetime_immutable)\'');
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
        $this->addSql('CREATE TABLE partner (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, website_url VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, api_key VARCHAR(255) DEFAULT NULL, api_secret VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, commission_rate NUMERIC(5, 2) DEFAULT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN partner.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN partner.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE partner_order (id SERIAL NOT NULL, user_id_id INT DEFAULT NULL, partner_id_id INT DEFAULT NULL, order_reference VARCHAR(255) NOT NULL, total_amount NUMERIC(10, 2) NOT NULL, commission_amount NUMERIC(10, 2) DEFAULT NULL, status VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E6153FFD9D86650F ON partner_order (user_id_id)');
        $this->addSql('CREATE INDEX IDX_E6153FFD6C783232 ON partner_order (partner_id_id)');
        $this->addSql('COMMENT ON COLUMN partner_order.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN partner_order.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE partner_product (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, price NUMERIC(10, 2) NOT NULL, sale_price NUMERIC(10, 2) DEFAULT NULL, product_url VARCHAR(255) NOT NULL, image_url VARCHAR(255) NOT NULL, category VARCHAR(100) NOT NULL, brand VARCHAR(100) NOT NULL, stock_status VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN partner_product.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN partner_product.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE post_like (id SERIAL NOT NULL, user_id_id INT DEFAULT NULL, post_id_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_653627B89D86650F ON post_like (user_id_id)');
        $this->addSql('CREATE INDEX IDX_653627B8E85F12B8 ON post_like (post_id_id)');
        $this->addSql('COMMENT ON COLUMN post_like.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE profile (id SERIAL NOT NULL, height NUMERIC(5, 2) DEFAULT NULL, weight NUMERIC(5, 2) DEFAULT NULL, body_type VARCHAR(50) DEFAULT NULL, style_preferences JSON NOT NULL, color_preferences JSON NOT NULL, size_preferences JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE social_post (id SERIAL NOT NULL, author_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, image VARCHAR(255) NOT NULL, likes_count INT DEFAULT NULL, comments_count INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_159BBFE9F675F31B ON social_post (author_id)');
        $this->addSql('COMMENT ON COLUMN social_post.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN social_post.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE tag (id SERIAL NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_389B783989D9B62 ON tag (slug)');
        $this->addSql('COMMENT ON COLUMN tag.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN tag.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, profile_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, google_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649CCFA12B8 ON "user" (profile_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('CREATE TABLE wardrobe_item (id SERIAL NOT NULL, customer_id INT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, brand VARCHAR(100) DEFAULT NULL, size VARCHAR(20) NOT NULL, color VARCHAR(50) NOT NULL, ref_references VARCHAR(255) DEFAULT NULL, partner_links VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, season VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_12D46B789395C3F3 ON wardrobe_item (customer_id)');
        $this->addSql('CREATE INDEX IDX_12D46B7812469DE2 ON wardrobe_item (category_id)');
        $this->addSql('COMMENT ON COLUMN wardrobe_item.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN wardrobe_item.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE wardrobe_item_partner_product (id SERIAL NOT NULL, wardrobe_item_id_id INT DEFAULT NULL, partner_product_id_id INT DEFAULT NULL, similarity_score NUMERIC(5, 2) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_304BF7E24A7BE2EB ON wardrobe_item_partner_product (wardrobe_item_id_id)');
        $this->addSql('CREATE INDEX IDX_304BF7E292E0D84A ON wardrobe_item_partner_product (partner_product_id_id)');
        $this->addSql('COMMENT ON COLUMN wardrobe_item_partner_product.created_at IS \'(DC2Type:datetime_immutable)\'');
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
        $this->addSql('ALTER TABLE ai_analysis ADD CONSTRAINT FK_1FD54150ED808AAB FOREIGN KEY (outfit_id_id) REFERENCES outfit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ai_analysis ADD CONSTRAINT FK_1FD541504A7BE2EB FOREIGN KEY (wardrobe_item_id_id) REFERENCES wardrobe_item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C9D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CE85F12B8 FOREIGN KEY (post_id_id) REFERENCES social_post (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CD6DE06A6 FOREIGN KEY (comment_id_id) REFERENCES comment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment_like ADD CONSTRAINT FK_8A55E25FD6DE06A6 FOREIGN KEY (comment_id_id) REFERENCES comment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment_like ADD CONSTRAINT FK_8A55E25F9D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow ADD CONSTRAINT FK_68344470AC24F853 FOREIGN KEY (follower_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow ADD CONSTRAINT FK_683444701816E3A3 FOREIGN KEY (following_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE outfit ADD CONSTRAINT FK_320296019395C3F3 FOREIGN KEY (customer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE outfit_item ADD CONSTRAINT FK_98142D2AE96E385 FOREIGN KEY (outfit_id) REFERENCES outfit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE outfit_item ADD CONSTRAINT FK_98142D23B14586B FOREIGN KEY (wardrobe_item_id) REFERENCES wardrobe_item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE partner_order ADD CONSTRAINT FK_E6153FFD9D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE partner_order ADD CONSTRAINT FK_E6153FFD6C783232 FOREIGN KEY (partner_id_id) REFERENCES partner (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post_like ADD CONSTRAINT FK_653627B89D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post_like ADD CONSTRAINT FK_653627B8E85F12B8 FOREIGN KEY (post_id_id) REFERENCES social_post (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE social_post ADD CONSTRAINT FK_159BBFE9F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wardrobe_item ADD CONSTRAINT FK_12D46B789395C3F3 FOREIGN KEY (customer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wardrobe_item ADD CONSTRAINT FK_12D46B7812469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wardrobe_item_partner_product ADD CONSTRAINT FK_304BF7E24A7BE2EB FOREIGN KEY (wardrobe_item_id_id) REFERENCES wardrobe_item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wardrobe_item_partner_product ADD CONSTRAINT FK_304BF7E292E0D84A FOREIGN KEY (partner_product_id_id) REFERENCES partner_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wardrobe_item_tag ADD CONSTRAINT FK_B20673983B14586B FOREIGN KEY (wardrobe_item_id) REFERENCES wardrobe_item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wardrobe_item_tag ADD CONSTRAINT FK_B2067398BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ai_analysis DROP CONSTRAINT FK_1FD54150ED808AAB');
        $this->addSql('ALTER TABLE ai_analysis DROP CONSTRAINT FK_1FD541504A7BE2EB');
        $this->addSql('ALTER TABLE category DROP CONSTRAINT FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C9D86650F');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CE85F12B8');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CD6DE06A6');
        $this->addSql('ALTER TABLE comment_like DROP CONSTRAINT FK_8A55E25FD6DE06A6');
        $this->addSql('ALTER TABLE comment_like DROP CONSTRAINT FK_8A55E25F9D86650F');
        $this->addSql('ALTER TABLE follow DROP CONSTRAINT FK_68344470AC24F853');
        $this->addSql('ALTER TABLE follow DROP CONSTRAINT FK_683444701816E3A3');
        $this->addSql('ALTER TABLE outfit DROP CONSTRAINT FK_320296019395C3F3');
        $this->addSql('ALTER TABLE outfit_item DROP CONSTRAINT FK_98142D2AE96E385');
        $this->addSql('ALTER TABLE outfit_item DROP CONSTRAINT FK_98142D23B14586B');
        $this->addSql('ALTER TABLE partner_order DROP CONSTRAINT FK_E6153FFD9D86650F');
        $this->addSql('ALTER TABLE partner_order DROP CONSTRAINT FK_E6153FFD6C783232');
        $this->addSql('ALTER TABLE post_like DROP CONSTRAINT FK_653627B89D86650F');
        $this->addSql('ALTER TABLE post_like DROP CONSTRAINT FK_653627B8E85F12B8');
        $this->addSql('ALTER TABLE social_post DROP CONSTRAINT FK_159BBFE9F675F31B');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649CCFA12B8');
        $this->addSql('ALTER TABLE wardrobe_item DROP CONSTRAINT FK_12D46B789395C3F3');
        $this->addSql('ALTER TABLE wardrobe_item DROP CONSTRAINT FK_12D46B7812469DE2');
        $this->addSql('ALTER TABLE wardrobe_item_partner_product DROP CONSTRAINT FK_304BF7E24A7BE2EB');
        $this->addSql('ALTER TABLE wardrobe_item_partner_product DROP CONSTRAINT FK_304BF7E292E0D84A');
        $this->addSql('ALTER TABLE wardrobe_item_tag DROP CONSTRAINT FK_B20673983B14586B');
        $this->addSql('ALTER TABLE wardrobe_item_tag DROP CONSTRAINT FK_B2067398BAD26311');
        $this->addSql('DROP TABLE acquisition');
        $this->addSql('DROP TABLE ai_analysis');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE comment_like');
        $this->addSql('DROP TABLE follow');
        $this->addSql('DROP TABLE outfit');
        $this->addSql('DROP TABLE outfit_item');
        $this->addSql('DROP TABLE partner');
        $this->addSql('DROP TABLE partner_order');
        $this->addSql('DROP TABLE partner_product');
        $this->addSql('DROP TABLE post_like');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE social_post');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE wardrobe_item');
        $this->addSql('DROP TABLE wardrobe_item_partner_product');
        $this->addSql('DROP TABLE wardrobe_item_tag');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
