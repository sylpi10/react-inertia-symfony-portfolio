<?php

namespace App\Tests\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

final class ProjectDetailsAudienceTest extends WebTestCase
{
    public function testClientVersionShowsItsOwnDescriptionAndCanonical(): void
    {
        $client = static::createClient();
        $this->stubProject($this->project()->setClientDescription('<p>Un site que la cliente modifie elle-même.</p>'));

        $crawler = $client->request('GET', '/creation-site-web/projets/maha');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextSame('title', 'Maha – Création de site web | Sylvain Pillet');
        self::assertSame('http://localhost/creation-site-web/projets/maha', $crawler->filter('link[rel="canonical"]')->attr('href'));
        $page = $this->inertiaPage($crawler);
        self::assertSame('client', $page['props']['audience']);
        self::assertSame('<p>Un site que la cliente modifie elle-même.</p>', $page['props']['project']['description']);
        self::assertStringStartsWith('Un site que la cliente modifie elle-même.', $crawler->filter('meta[name="description"]')->attr('content'));
    }

    public function testClientTitleStartsWithTheNeedWhenSet(): void
    {
        $client = static::createClient();
        $client->disableReboot();
        $this->stubProject($this->project()->setClientSeoTitle('Site web pour cheffe à domicile'));

        $client->request('GET', '/creation-site-web/projets/maha');
        self::assertSelectorTextSame('title', 'Site web pour cheffe à domicile – Maha | Sylvain Pillet');

        // l'accueil s'adresse aux équipes : titre inchangé
        $client->request('GET', '/projets/maha');
        self::assertSelectorTextSame('title', 'Maha – Projet web | Sylvain Pillet');
    }

    public function testTeamVersionKeepsTheMainDescription(): void
    {
        $client = static::createClient();
        $this->stubProject($this->project()->setClientDescription('<p>Un site que la cliente modifie elle-même.</p>'));

        $crawler = $client->request('GET', '/projets/maha');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextSame('title', 'Maha – Projet web | Sylvain Pillet');
        $page = $this->inertiaPage($crawler);
        self::assertSame('team', $page['props']['audience']);
        self::assertSame('<p>Symfony, back-office EasyAdmin.</p>', $page['props']['project']['description']);
    }

    public function testClientVersionWithoutOwnTextPointsToTheMainPage(): void
    {
        $client = static::createClient();
        $this->stubProject($this->project());

        $crawler = $client->request('GET', '/creation-site-web/projets/maha');

        self::assertResponseIsSuccessful();
        // même texte que /projets/maha : pas de contenu dupliqué indexé
        self::assertSame('http://localhost/projets/maha', $crawler->filter('link[rel="canonical"]')->attr('href'));
        self::assertSame('<p>Symfony, back-office EasyAdmin.</p>', $this->inertiaPage($crawler)['props']['project']['description']);
    }

    public function testUnknownProjectRendersTheErrorPageWithA404(): void
    {
        $client = static::createClient();
        $repository = $this->createStub(ProjectRepository::class);
        $repository->method('findOneBy')->willReturn(null);
        static::getContainer()->set(ProjectRepository::class, $repository);

        $crawler = $client->request('GET', '/projets/inconnu');

        // vrai 404 : Google ne prend pas la page d'erreur pour une page valide
        self::assertResponseStatusCodeSame(404);
        self::assertSelectorTextSame('title', 'Page introuvable | Sylvain Pillet');
        self::assertSame('noindex, follow', $crawler->filter('meta[name="robots"]')->attr('content'));
        self::assertSame('Error', $this->inertiaPage($crawler)['component']);
    }

    private function project(): Project
    {
        return (new Project())
            ->setName('Maha')
            ->setSlug('maha')
            ->setDate('2025')
            ->setTechnos('Symfony')
            ->setBackground('maha.webp')
            ->setDetailPic('maha-full.webp')
            ->setDescription('<p>Symfony, back-office EasyAdmin.</p>');
    }

    private function stubProject(Project $project): void
    {
        $repository = $this->createStub(ProjectRepository::class);
        $repository->method('findOneBy')->willReturn($project);
        $repository->method('findAdjacent')->willReturn(['previous' => null, 'next' => null]);
        static::getContainer()->set(ProjectRepository::class, $repository);
    }

    /**
     * @return array<string, mixed>
     */
    private function inertiaPage(Crawler $crawler): array
    {
        return json_decode($crawler->filter('script[data-page="app"]')->text(), true, flags: \JSON_THROW_ON_ERROR);
    }
}
