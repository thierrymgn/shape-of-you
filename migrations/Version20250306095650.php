<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250306095650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE wardrobe_item ADD "references" VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE wardrobe_item ADD partner_links VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE wardrobe_item DROP "references"');
        $this->addSql('ALTER TABLE wardrobe_item DROP partner_links');
    }
}
