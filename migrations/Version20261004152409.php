<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261004152409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Projets : ordre d\'affichage (position)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project ADD position INT NOT NULL');
        // ordre de départ = ordre actuel (par id)
        $this->addSql('UPDATE project SET position = id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project DROP position');
    }
}
