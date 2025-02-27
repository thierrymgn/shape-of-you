<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250216100748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ai_analysis ADD outfit_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ai_analysis ADD wardrobe_item_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ai_analysis ADD CONSTRAINT FK_1FD54150ED808AAB FOREIGN KEY (outfit_id_id) REFERENCES outfit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ai_analysis ADD CONSTRAINT FK_1FD541504A7BE2EB FOREIGN KEY (wardrobe_item_id_id) REFERENCES wardrobe_item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1FD54150ED808AAB ON ai_analysis (outfit_id_id)');
        $this->addSql('CREATE INDEX IDX_1FD541504A7BE2EB ON ai_analysis (wardrobe_item_id_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ai_analysis DROP CONSTRAINT FK_1FD54150ED808AAB');
        $this->addSql('ALTER TABLE ai_analysis DROP CONSTRAINT FK_1FD541504A7BE2EB');
        $this->addSql('DROP INDEX IDX_1FD54150ED808AAB');
        $this->addSql('DROP INDEX IDX_1FD541504A7BE2EB');
        $this->addSql('ALTER TABLE ai_analysis DROP outfit_id_id');
        $this->addSql('ALTER TABLE ai_analysis DROP wardrobe_item_id_id');
    }
}
