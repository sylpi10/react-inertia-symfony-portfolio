<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261009222402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Avis (review) : un avis couvre un ou plusieurs projets, un projet a au plus un avis';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, author VARCHAR(100) NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL, validated TINYINT DEFAULT 0 NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE project ADD review_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE3E2E969B FOREIGN KEY (review_id) REFERENCES review (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE3E2E969B ON project (review_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE3E2E969B');
        $this->addSql('DROP INDEX IDX_2FB3D0EE3E2E969B ON project');
        $this->addSql('ALTER TABLE project DROP review_id');
        $this->addSql('DROP TABLE review');
    }
}
