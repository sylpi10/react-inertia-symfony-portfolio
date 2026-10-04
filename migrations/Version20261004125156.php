<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261004125156 extends AbstractMigration
{
    // la page injecte la description en HTML : en texte brut, tout s'affichait en un seul bloc
    private const string DESCRIPTION = <<<'HTML'
        <p>Refonte technique du site d'une cheffe à domicile en Haute-Garonne : passage d'un site statique à une application Symfony avec back-office, pour une gestion autonome de tous les contenus.</p>

        <h2>Le contexte</h2>
        <p>La cuisine de Maha est l'activité d'une cheffe à domicile installée près de Toulouse. Elle se déplace chez ses clients pour des repas privés, des événements et des prestations sur mesure. Son site présente ses menus, ses plats et les avis de ses clients, et permet de la contacter pour réserver une prestation.</p>

        <h2>La problématique</h2>
        <p>La première version du site reposait sur Eleventy, un générateur de site statique. C'était rapide et léger, mais chaque modification demandait de toucher au code puis de redéployer : un nouveau menu, un plat de saison, un nouvel avis client. La cheffe dépendait donc entièrement d'un développeur pour faire vivre son site, alors que son offre évolue au fil des saisons.</p>

        <h2>La solution</h2>
        <p>J'ai reconstruit le site avec Symfony et Twig, en conservant le design existant. J'ai ajouté un back-office complet basé sur EasyAdmin. Depuis son espace d'administration sécurisé, Maha gère elle-même :</p>
        <ul>
            <li>ses menus et ses plats (descriptions, photos, mise en avant) ;</li>
            <li>les avis clients affichés sur le site ;</li>
            <li>les demandes de contact, qui sont enregistrées en base et lui sont notifiées par e-mail.</li>
        </ul>
        <p>Les données sont stockées dans une base MySQL gérée avec Doctrine. Les comptes administrateurs se créent via une commande dédiée, et le formulaire de contact utilise le composant Form de Symfony avec validation côté serveur.</p>
        <p>La migration s'est faite sans interruption de service : la nouvelle application a été développée en parallèle de l'ancien site, puis basculée une fois prête. Le site est hébergé chez o2switch, sous PHP 8.</p>

        <h2>Le résultat</h2>
        <p>La cheffe est désormais autonome sur 100 % de son contenu. Elle met à jour ses menus en quelques minutes, sans intervention technique. Les demandes de prestation ne passent plus seulement par la messagerie : elles sont centralisées dans le back-office, ce qui évite d'en perdre. Le site reste rapide et responsive, sur ordinateur comme sur mobile.</p>
        HTML;

    public function getDescription(): string
    {
        return 'La cuisine de Maha : description mise en forme en HTML';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE project SET description = ? WHERE slug = 'la-cuisine-de-maha'", [self::DESCRIPTION]);
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('Contenu éditorial : l\'ancienne version en texte brut n\'est pas conservée.');
    }
}
