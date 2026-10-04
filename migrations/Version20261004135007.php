<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261004135007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Parcours : tables experience et experience_project (many-to-many avec project)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, organization VARCHAR(100) NOT NULL, period VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, technos VARCHAR(255) DEFAULT NULL, position INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE experience_project (experience_id INT NOT NULL, project_id INT NOT NULL, INDEX IDX_6A983B7C46E90E27 (experience_id), INDEX IDX_6A983B7C166D1F9C (project_id), PRIMARY KEY (experience_id, project_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE experience_project ADD CONSTRAINT FK_6A983B7C46E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experience_project ADD CONSTRAINT FK_6A983B7C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE experience_project DROP FOREIGN KEY FK_6A983B7C46E90E27');
        $this->addSql('ALTER TABLE experience_project DROP FOREIGN KEY FK_6A983B7C166D1F9C');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE experience_project');
    }
}
