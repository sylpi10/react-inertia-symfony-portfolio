<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261004151501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Services : tables offer et offer_project (projets cités en exemple)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, position INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE offer_project (offer_id INT NOT NULL, project_id INT NOT NULL, INDEX IDX_8EBB16E753C674EE (offer_id), INDEX IDX_8EBB16E7166D1F9C (project_id), PRIMARY KEY (offer_id, project_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE offer_project ADD CONSTRAINT FK_8EBB16E753C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_project ADD CONSTRAINT FK_8EBB16E7166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE offer_project DROP FOREIGN KEY FK_8EBB16E753C674EE');
        $this->addSql('ALTER TABLE offer_project DROP FOREIGN KEY FK_8EBB16E7166D1F9C');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE offer_project');
    }
}
