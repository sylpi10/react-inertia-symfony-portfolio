<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261004151506 extends AbstractMigration
{
    /**
     * Contenu initial de la section « Ce que je peux faire pour vous », dans
     * l'ordre d'affichage ; modifiable ensuite dans le back-office.
     * "projects" : slugs des projets cités en exemple.
     */
    private const array OFFERS = [
        [
            'title' => 'Sites vitrines avec back-office',
            'description' => '<p>Un site rapide et bien référencé, avec un espace d\'administration pour modifier vous-même vos textes, vos photos et vos offres, sans dépendre d\'un développeur.</p>',
            'projects' => ['la-cuisine-de-maha', 'directicimes'],
        ],
        [
            'title' => 'Refonte et modernisation de sites existants',
            'description' => '<p>Votre site est lent, daté ou difficile à faire évoluer ? Je le reconstruis sur une base moderne, en conservant votre design, vos contenus et votre référencement.</p>',
            'projects' => ['utopix', 'la-cuisine-de-maha'],
        ],
        [
            'title' => 'Applications web sur mesure en React / Next.js',
            'description' => '<p>Outils métier, plateformes ou applications interactives : de la conception à la mise en ligne, avec une interface soignée et un code fait pour durer.</p>',
            'projects' => ['ava', 'toulouse-sport-club'],
        ],
        [
            'title' => 'Performance et SEO technique',
            'description' => '<p>Core Web Vitals, rendu serveur, données structurées, maillage interne : un site qui s\'affiche vite et que Google comprend, pour gagner en visibilité.</p>',
            'projects' => ['portfolio'],
        ],
    ];

    public function getDescription(): string
    {
        return 'Services : contenu initial et projets cités en exemple';
    }

    public function up(Schema $schema): void
    {
        foreach (self::OFFERS as $position => $offer) {
            $this->addSql(
                'INSERT INTO offer (title, description, position) VALUES (?, ?, ?)',
                [$offer['title'], $offer['description'], $position],
            );

            // liens par slug : les id de projets peuvent différer entre les bases
            foreach ($offer['projects'] as $slug) {
                $this->addSql(
                    'INSERT INTO offer_project (offer_id, project_id) SELECT o.id, p.id FROM offer o, project p WHERE o.position = ? AND p.slug = ?',
                    [$position, $slug],
                );
            }
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM offer_project');
        $this->addSql('DELETE FROM offer');
    }
}
