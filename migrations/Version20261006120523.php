<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261006120523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Deux modes : descriptions « création de site » (projets, parcours) et ordre des projets sur l\'accueil';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE experience ADD client_description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD client_description LONGTEXT DEFAULT NULL, ADD team_position INT NOT NULL');
        // accueil (page équipe) : projets techniques en tête, puis l'ordre actuel
        $this->addSql('UPDATE project SET team_position = position + 10');
        $this->addSql("UPDATE project SET team_position = 1 WHERE slug = 'labelmaker'");
        $this->addSql("UPDATE project SET team_position = 2 WHERE slug = 'ludilabel'");
        $this->addSql("UPDATE project SET team_position = 3 WHERE slug = 'ava'");
        $this->addSql("UPDATE project SET team_position = 4 WHERE slug = 'portfolio'");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE experience DROP client_description');
        $this->addSql('ALTER TABLE project DROP client_description, DROP team_position');
    }
}
