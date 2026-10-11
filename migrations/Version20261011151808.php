<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261011151808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Projets : réglage des avis à trois valeurs (fermé, un seul avis, plusieurs avis) à la place de la case ouvert aux avis';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project ADD review_policy VARCHAR(10) DEFAULT \'single\' NOT NULL');
        // case « Ouvert aux avis » décochée : fermé ; cochée : un seul avis (valeur par défaut)
        $this->addSql('UPDATE project SET review_policy = \'closed\' WHERE reviewable = 0');
        $this->addSql('ALTER TABLE project DROP reviewable');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project ADD reviewable TINYINT DEFAULT 1 NOT NULL');
        // « un seul avis » et « plusieurs avis » redeviennent tous deux « ouvert »
        $this->addSql('UPDATE project SET reviewable = 0 WHERE review_policy = \'closed\'');
        $this->addSql('ALTER TABLE project DROP review_policy');
    }
}
