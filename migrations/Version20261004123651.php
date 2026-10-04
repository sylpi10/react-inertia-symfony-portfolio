<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261004123651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Projets : slug pour les URLs /projets/{slug}';
    }

    public function up(Schema $schema): void
    {
        // colonne d'abord nullable, le temps de calculer le slug des projets existants
        $this->addSql('ALTER TABLE project ADD slug VARCHAR(100) DEFAULT NULL');

        $slugger = new AsciiSlugger('fr');
        $slugs = [];
        foreach ($this->connection->fetchAllKeyValue('SELECT id, name FROM project ORDER BY id') as $id => $name) {
            $slug = $slugger->slug($name)->lower()->toString();
            // deux projets du même nom : le second garde son id en suffixe
            if (\in_array($slug, $slugs, true)) {
                $slug .= '-'.$id;
            }
            $slugs[] = $slug;
            $this->addSql('UPDATE project SET slug = ? WHERE id = ?', [$slug, $id]);
        }

        $this->addSql('ALTER TABLE project MODIFY slug VARCHAR(100) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE989D9B62 ON project (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_2FB3D0EE989D9B62 ON project');
        $this->addSql('ALTER TABLE project DROP slug');
    }
}
