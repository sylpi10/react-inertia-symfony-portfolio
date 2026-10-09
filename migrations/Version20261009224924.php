<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261009224924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Avis : poste / entreprise de l\'auteur ; projets ouverts aux avis';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project ADD reviewable TINYINT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE review ADD author_role VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP reviewable');
        $this->addSql('ALTER TABLE review DROP author_role');
    }
}
