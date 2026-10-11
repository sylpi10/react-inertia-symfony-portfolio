<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261011144757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Avis : relation plusieurs-à-plusieurs avec les projets (un projet peut avoir plusieurs avis), liens existants conservés';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE review_project (review_id INT NOT NULL, project_id INT NOT NULL, INDEX IDX_B45D64193E2E969B (review_id), INDEX IDX_B45D6419166D1F9C (project_id), PRIMARY KEY (review_id, project_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE review_project ADD CONSTRAINT FK_B45D64193E2E969B FOREIGN KEY (review_id) REFERENCES review (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review_project ADD CONSTRAINT FK_B45D6419166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        // liens existants (project.review_id) recopiés avant la suppression de la colonne
        $this->addSql('INSERT INTO review_project (review_id, project_id) SELECT review_id, id FROM project WHERE review_id IS NOT NULL');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY `FK_2FB3D0EE3E2E969B`');
        $this->addSql('DROP INDEX IDX_2FB3D0EE3E2E969B ON project');
        $this->addSql('ALTER TABLE project DROP review_id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project ADD review_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT `FK_2FB3D0EE3E2E969B` FOREIGN KEY (review_id) REFERENCES review (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE3E2E969B ON project (review_id)');
        // retour à un avis par projet : le plus ancien est gardé, les autres liens sont perdus
        $this->addSql('UPDATE project p SET p.review_id = (SELECT MIN(rp.review_id) FROM review_project rp WHERE rp.project_id = p.id)');
        $this->addSql('ALTER TABLE review_project DROP FOREIGN KEY FK_B45D64193E2E969B');
        $this->addSql('ALTER TABLE review_project DROP FOREIGN KEY FK_B45D6419166D1F9C');
        $this->addSql('DROP TABLE review_project');
    }
}
