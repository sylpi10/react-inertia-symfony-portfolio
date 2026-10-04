<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261004135016 extends AbstractMigration
{
    /**
     * Reprise de la timeline codée en dur dans Parcours.tsx, dans l'ordre d'affichage.
     * "projects" : slugs des projets liés, déduits des dates ; à ajuster dans le back-office.
     */
    private const array EXPERIENCES = [
        [
            'title' => 'Développeur freelance',
            'organization' => 'Freelance',
            'period' => '2026',
            'description' => <<<'HTML'
                <p>Développement de sites web et applications web après échanges et compréhension des besoins du client</p>
                <p>Mise en ligne, gestion de l'hébergement</p>
                <p>Optimisation SEO via search console et ahrefs</p>
                <p>Optimisation des performances selon les résultats pagespeed insights</p>
                HTML,
            'technos' => 'React, Symfony, Next, InertiaJs, Sql, Docker, Html, Sass, Git',
            'projects' => ['utopix', 'la-cuisine-de-maha', 'ava', 'portfolio'],
        ],
        [
            'title' => 'Développeur web',
            'organization' => 'Ludilabel',
            'period' => '2021/2026',
            'description' => <<<'HTML'
                <p>Développement front-end du nouveau site en migration sur Magento 2 : Optimisations de performances, web core vitals, Seo, Accessibilité, optimisations UX/UI.</p>
                <p>Gestion, correction des erreurs en lien avec le SAV.</p>
                <p>Conception et Développement d'une application de personnalisation d'objets avec Symfony et React.</p>
                <p>Architecture hexagonale, gestion des states avec Redux, intégration selon les maquettes.</p>
                <p>Mises à jour des versions de Symfony et React.</p>
                <p>Améliorations front-end du site existant sur Magento 1. Développement, code reviews, mises en lignes.</p>
                HTML,
            'technos' => 'Php, Symfony, React, Magento, Sql, Docker, Html, Sass, Less, Git',
            'projects' => ['labelmaker', 'ludilabel'],
        ],
        [
            'title' => 'Concepteur Développeur',
            'organization' => '3WAcademy (alternance)',
            'period' => '2021/2023',
            'description' => <<<'HTML'
                <ul>
                    <li>Développement fullstack Symfony-React</li>
                    <li>Conception UML / Merise</li>
                    <li>UI/UX, SEO, &amp; accessibilité</li>
                    <li>Clean architecture, design patterns, SOLID</li>
                </ul>
                HTML,
            'technos' => 'Symfony, React, UML, Sql, Conception',
            'projects' => [],
        ],
        [
            'title' => 'Développeur freelance',
            'organization' => 'Freelance',
            'period' => '2020',
            'description' => <<<'HTML'
                <ul>
                    <li>Développement de sites vitrines</li>
                    <li>Développement e-commerce</li>
                    <li>Responsive Webdesign</li>
                    <li>Maquettage</li>
                    <li>Gestion de l’hébergement et mise en production</li>
                </ul>
                HTML,
            'technos' => 'Symfony, Django, MySql, PostgreSql, Sass, Git, Photoshop',
            'projects' => ['directicimes', 'atelier-chenoa', 'cabinet-de-neuro-psy'],
        ],
        [
            'title' => 'Formation Java/Angular',
            'organization' => 'Aelion',
            'period' => '2020',
            'description' => <<<'HTML'
                <ul>
                    <li>Développement fullstack Spring Boot-Angular</li>
                    <li>Clean architecture, DTO</li>
                    <li>UI/UX, performances, SEO, &amp; accessibilité</li>
                    <li>Git, déploiement</li>
                </ul>
                HTML,
            'technos' => 'Spring Boot, Angular, MySql, PostgreSql, Postman, Git',
            'projects' => [],
        ],
        [
            'title' => 'Formation Développeur Web',
            'organization' => 'Adrar',
            'period' => '2019',
            'description' => <<<'HTML'
                <ul>
                    <li>Développement Web</li>
                    <li>POO, MVC</li>
                    <li>Responsive Web Design</li>
                    <li>SEO, &amp; accessibilité</li>
                    <li>Maquettage</li>
                    <li>Conception</li>
                </ul>
                HTML,
            'technos' => 'Php, Javascript, UML, Merise, Photoshop, Sql, Git',
            'projects' => [],
        ],
        [
            'title' => 'Master 1 NUMIC',
            'organization' => 'Université Rennes 2',
            'period' => '2016/2017',
            'description' => <<<'HTML'
                <ul>
                    <li>Valorisation de l'audiovisuel à travers le numérique</li>
                    <li>Gestion de projet</li>
                    <li>Webdesign</li>
                    <li>Intégration web</li>
                    <li>Conception de visites virtuelles 360°</li>
                </ul>
                HTML,
            'technos' => 'Html, Css, Photoshop, Visites virtuelles 360°, Wordpress',
            'projects' => [],
        ],
        [
            'title' => 'Licence Cinéma',
            'organization' => 'Université Paul Valéry',
            'period' => '2010/2012',
            'description' => null,
            'technos' => null,
            'projects' => [],
        ],
    ];

    public function getDescription(): string
    {
        return 'Parcours : reprise du contenu de la timeline et liens vers les projets';
    }

    public function up(Schema $schema): void
    {
        foreach (self::EXPERIENCES as $position => $experience) {
            $this->addSql(
                'INSERT INTO experience (title, organization, period, description, technos, position) VALUES (?, ?, ?, ?, ?, ?)',
                [$experience['title'], $experience['organization'], $experience['period'], $experience['description'], $experience['technos'], $position],
            );

            // liens par slug : les id de projets peuvent différer entre les bases
            foreach ($experience['projects'] as $slug) {
                $this->addSql(
                    'INSERT INTO experience_project (experience_id, project_id) SELECT e.id, p.id FROM experience e, project p WHERE e.position = ? AND p.slug = ?',
                    [$position, $slug],
                );
            }
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM experience_project');
        $this->addSql('DELETE FROM experience');
    }
}
