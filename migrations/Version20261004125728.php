<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261004125728 extends AbstractMigration
{
    private const array COLUMNS = ['background', 'detail_pic', 'detail_pic_mobile'];

    public function getDescription(): string
    {
        return 'Images projets : noms en base alignés sur les fichiers .webp, pour que l\'admin les retrouve';
    }

    public function up(Schema $schema): void
    {
        // calculé en PHP : REGEXP_REPLACE n'existe pas en MySQL 5.7
        $rows = $this->connection->fetchAllAssociative('SELECT id, background, detail_pic, detail_pic_mobile FROM project');
        foreach ($rows as $row) {
            foreach (self::COLUMNS as $column) {
                $webp = null === $row[$column] ? null : preg_replace('/\.(jpe?g|png)$/i', '.webp', $row[$column]);
                if ($webp !== $row[$column]) {
                    $this->addSql(sprintf('UPDATE project SET %s = ? WHERE id = ?', $column), [$webp, $row['id']]);
                }
            }
        }
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('Les extensions d\'origine ne sont pas conservées ; les fichiers .webp restent valides.');
    }
}
