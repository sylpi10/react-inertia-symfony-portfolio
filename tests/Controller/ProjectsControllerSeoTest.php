<?php

namespace App\Tests\Controller;

use App\Entity\Experience;
use App\Entity\Offer;
use App\Entity\Project;
use App\Repository\ExperienceRepository;
use App\Repository\OfferRepository;
use App\Repository\ProjectRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProjectsControllerSeoTest extends WebTestCase
{
    public function testHomeRendersSeoTagsServerSide(): void
    {
        $client = static::createClient();
        $this->stubProjects();
        $this->stubTimeline([]);

        $crawler = $client->request('GET', '/');
        $title = $crawler->filter('title')->text();
        self::assertResponseIsSuccessful();
        self::assertSelectorTextSame('title', $title);
        $description = $crawler
            ->filter('meta[name="description"]')
            ->attr('content');
        self::assertStringStartsWith(
            'Développeur fullstack React et Symfony à Toulouse',
            $description,
        );
        // missions à distance : hors de Toulouse aussi
        self::assertStringContainsString('full remote', $description);
        self::assertLessThanOrEqual(155, mb_strlen($description));
        self::assertSame(
            'http://localhost/',
            $crawler->filter('link[rel="canonical"]')->attr('href'),
        );
        self::assertSame(
            'http://localhost/images/sylvain-pillet.jpg',
            $crawler->filter('meta[property="og:image"]')->attr('content'),
        );
        self::assertSame(
            '/favicon.ico',
            $crawler->filter('link[rel="icon"]')->attr('href'),
        );

        $person = json_decode(
            $crawler->filter('script[type="application/ld+json"]')->text(),
            true,
            flags: \JSON_THROW_ON_ERROR,
        );
        self::assertSame('Person', $person['@type']);
        self::assertSame('Toulouse', $person['address']['addressLocality']);
        // areaServed n'existe pas sur Person : erreur de validation schema.org
        self::assertArrayNotHasKey('areaServed', $person);

        // fiche Google Business : mêmes coordonnées, reliée à la personne
        $business = json_decode(
            $crawler
                ->filter('script[type="application/ld+json"]')
                ->eq(1)
                ->text(),
            true,
            flags: \JSON_THROW_ON_ERROR,
        );
        self::assertSame('ProfessionalService', $business['@type']);
        self::assertSame('Sylvain Pillet', $business['name']);
        self::assertSame('+33688103175', $business['telephone']);
        self::assertSame('31400', $business['address']['postalCode']);
        self::assertSame('À partir de 1 500 €', $business['priceRange']);
        self::assertSame($person['@id'], $business['founder']['@id']);
        self::assertSame(
            ['https://share.google/cjVgdToR7DqgAuS4P'],
            $business['sameAs'],
        );
    }

    public function testIndexNowKeyFileServesTheKey(): void
    {
        $client = static::createClient();
        $client->request('GET', '/indexnow.txt');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame(
            'Content-Type',
            'text/plain; charset=UTF-8',
        );
        self::assertSame(
            $_ENV['INDEXNOW_KEY'],
            $client->getResponse()->getContent(),
        );
    }

    public function testHomeExposesTimelineWithLinkedProjects(): void
    {
        $client = static::createClient();
        $this->stubProjects();

        $project = new Project()
            ->setName('Labelmaker')
            ->setSlug('labelmaker')
            ->setTechnos('React')
            ->setDescription('<p>Secret</p>');
        $experience = new Experience()
            ->setTitle('Développeur web')
            ->setOrganization('Ludilabel')
            ->setPeriod('2021/2026')
            ->setDescription('<p>Développement front-end</p>')
            ->setTechnos('Php, Symfony')
            ->addProject($project);
        $this->stubTimeline([$experience]);

        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        $page = json_decode(
            $crawler->filter('script[data-page="app"]')->text(),
            true,
            flags: \JSON_THROW_ON_ERROR,
        );
        $timeline = $page['props']['experiences'];
        self::assertCount(1, $timeline);
        self::assertSame('Ludilabel', $timeline[0]['organization']);
        self::assertSame('2021/2026', $timeline[0]['period']);
        // seuls nom et slug du projet lié sont exposés, pas sa description
        self::assertSame(
            [['name' => 'Labelmaker', 'slug' => 'labelmaker']],
            $timeline[0]['projects'],
        );
    }

    public function testClientPageExposesOffersWithExampleProjects(): void
    {
        $client = static::createClient();
        $this->stubProjects();
        $this->stubTimeline([]);

        $offer = new Offer()
            ->setTitle('Sites vitrines avec back-office')
            ->setDescription('<p>Un site que vous modifiez vous-même.</p>')
            ->addProject(
                new Project()
                    ->setName('La cuisine de Maha')
                    ->setSlug('la-cuisine-de-maha')
                    ->setTechnos('Symfony'),
            );
        $offers = $this->createStub(OfferRepository::class);
        $offers->method('findForHome')->willReturn([$offer]);
        static::getContainer()->set(OfferRepository::class, $offers);

        $crawler = $client->request('GET', '/creation-site-web');

        self::assertResponseIsSuccessful();
        $page = json_decode(
            $crawler->filter('script[data-page="app"]')->text(),
            true,
            flags: \JSON_THROW_ON_ERROR,
        );
        $offers = $page['props']['offers'];
        self::assertSame(
            'Sites vitrines avec back-office',
            $offers[0]['title'],
        );
        self::assertSame(
            [['name' => 'La cuisine de Maha', 'slug' => 'la-cuisine-de-maha']],
            $offers[0]['projects'],
        );
    }

    private function stubProjects(): void
    {
        static::getContainer()->set(
            ProjectRepository::class,
            $this->createStub(ProjectRepository::class),
        );
        // aucun avis : la section reste vide
        static::getContainer()->set(
            ReviewRepository::class,
            $this->createStub(ReviewRepository::class),
        );
    }

    /**
     * @param list<Experience> $experiences
     */
    private function stubTimeline(array $experiences): void
    {
        $repository = $this->createStub(ExperienceRepository::class);
        $repository->method('findForTimeline')->willReturn($experiences);
        static::getContainer()->set(ExperienceRepository::class, $repository);
    }
}
