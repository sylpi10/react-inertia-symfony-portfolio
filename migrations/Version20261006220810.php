<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261006220810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Contact : type de projet, budget et délai des demandes de devis';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contact ADD project_type VARCHAR(20) DEFAULT NULL, ADD budget VARCHAR(20) DEFAULT NULL, ADD deadline VARCHAR(20) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contact DROP project_type, DROP budget, DROP deadline');
    }
}
