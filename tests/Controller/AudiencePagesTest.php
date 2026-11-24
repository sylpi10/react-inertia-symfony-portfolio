<?php

namespace App\Tests\Controller;

use App\Entity\Experience;
use App\Entity\Project;
use App\Entity\Review;
use App\Repository\ExperienceRepository;
use App\Repository\OfferRepository;
use App\Repository\ProjectRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

final class AudiencePagesTest extends WebTestCase
{
    public function testClientPageRendersItsOwnSeoTags(): void
    {
        $client = static::createClient();
        $this->stubProjects([]);
        $this->stubTimeline();
        $this->stubReviews();
        $this->stubOffers();

        $crawler = $client->request('GET', '/creation-site-web');

        $title = $crawler->filter('title')->text();

        self::assertResponseIsSuccessful();
        self::assertSelectorTextSame('title', $title);
        $description = $crawler
            ->filter('meta[name="description"]')
            ->attr('content');
        self::assertStringStartsWith(
            'Développeur web freelance à Toulouse : sites vitrines',
            $description,
        );
        self::assertLessThanOrEqual(155, mb_strlen($description));
        self::assertSame(
            'http://localhost/creation-site-web',
            $crawler->filter('link[rel="canonical"]')->attr('href'),
        );

        $page = $this->inertiaPage($crawler);
        self::assertSame('Client', $page['component']);
        self::assertSame('client', $page['props']['audience']);
    }

    public function testHomeIsTheTeamPageWithoutOffers(): void
    {
        $client = static::createClient();
        $this->stubProjects([]);
        $this->stubTimeline();
        $this->stubReviews();

        $page = $this->inertiaPage($client->request('GET', '/'));

        self::assertSame('Team', $page['component']);
        self::assertSame('team', $page['props']['audience']);
        // les services s'adressent aux clients : absents de l'accueil
        self::assertArrayNotHasKey('offers', $page['props']);
    }

    public function testEachPageListsAllProjectsInItsOwnOrder(): void
    {
        $client = static::createClient();
        // deux requêtes : garder les stubs dans le même kernel
        $client->disableReboot();
        $this->stubProjects([
            $this->project('maha', 1, 3),
            $this->project('ava', 2, 2),
            $this->project('labelmaker', 3, 1),
        ]);
        $this->stubTimeline();
        $this->stubReviews();
        $this->stubOffers();

        $team = $this->inertiaPage($client->request('GET', '/'));
        $clientPage = $this->inertiaPage(
            $client->request('GET', '/creation-site-web'),
        );

        self::assertSame(
            ['labelmaker', 'ava', 'maha'],
            array_column($team['props']['projects'], 'slug'),
        );
        self::assertSame(
            ['maha', 'ava', 'labelmaker'],
            array_column($clientPage['props']['projects'], 'slug'),
        );
        // vignette générée (public/images/projects/thumbs) ; pas encore pour « maha » : le front reprend background
        self::assertSame(
            '/images/projects/thumbs/labelmaker.webp',
            $team['props']['projects'][0]['thumbnail'],
        );
        self::assertNull($team['props']['projects'][2]['thumbnail']);
    }

    public function testEachPageShowsItsOwnTimelineDescriptions(): void
    {
        $client = static::createClient();
        $client->disableReboot();
        $this->stubProjects([]);
        $this->stubOffers();
        $this->stubTimeline([
            new Experience()
                ->setTitle('Développeur web')
                ->setOrganization('Ludilabel')
                ->setPeriod('2021/2026')
                ->setDescription('<p>Architecture hexagonale</p>')
                ->setClientDescription('<p>Une boutique en ligne</p>'),
            new Experience()
                ->setTitle('Formation')
                ->setOrganization('Simplon')
                ->setPeriod('2019')
                ->setDescription('<p>Bases du web</p>'),
        ]);
        $this->stubReviews();

        $team = $this->inertiaPage($client->request('GET', '/'));
        $clientPage = $this->inertiaPage(
            $client->request('GET', '/creation-site-web'),
        );

        self::assertSame(
            ['<p>Architecture hexagonale</p>', '<p>Bases du web</p>'],
            array_column($team['props']['experiences'], 'description'),
        );
        // sans texte client, celui de l'accueil est repris
        self::assertSame(
            ['<p>Une boutique en ligne</p>', '<p>Bases du web</p>'],
            array_column($clientPage['props']['experiences'], 'description'),
        );
    }

    public function testEachPageShowsItsOwnProjectMiniDescription(): void
    {
        $client = static::createClient();
        $client->disableReboot();
        $this->stubProjects([
            $this->project('maha', 1, 1)
                ->setMiniDescription('<p>Symfony, back-office EasyAdmin</p>')
                ->setMiniClientDescription(
                    '<p>Un site que la cliente modifie elle-même</p>',
                ),
            $this->project('ava', 2, 2)->setMiniDescription('<p>Next.js</p>'),
        ]);
        $this->stubTimeline();
        $this->stubReviews();
        $this->stubOffers();

        $team = $this->inertiaPage($client->request('GET', '/'));
        $clientPage = $this->inertiaPage(
            $client->request('GET', '/creation-site-web'),
        );

        self::assertSame(
            ['<p>Symfony, back-office EasyAdmin</p>', '<p>Next.js</p>'],
            array_column($team['props']['projects'], 'miniDescription'),
        );
        // sans texte client, celui de l'accueil est repris
        self::assertSame(
            [
                '<p>Un site que la cliente modifie elle-même</p>',
                '<p>Next.js</p>',
            ],
            array_column($clientPage['props']['projects'], 'miniDescription'),
        );
        // la version client n'est pas envoyée telle quelle au navigateur
        self::assertArrayNotHasKey(
            'miniClientDescription',
            $clientPage['props']['projects'][0],
        );
    }

    public function testContactRedirectsBackToTheClientPage(): void
    {
        $client = static::createClient();

        // honeypot rempli : pas d'écriture en base ni d'envoi de mail
        $client->jsonRequest('POST', '/contact', [
            'name' => 'Boulangerie',
            'email' => 'contact@example.com',
            'message' => 'Bonjour, il me faudrait un site.',
            'website' => 'spam',
            'audience' => 'client',
            'projectType' => 'creation',
            'deadline' => 'flexible',
        ]);

        self::assertResponseRedirects('/creation-site-web');
    }

    public function testContactSentFromAProjectPageReturnsToIt(): void
    {
        $client = static::createClient();

        $client->jsonRequest(
            'POST',
            '/contact',
            [
                'name' => 'Boulangerie',
                'email' => 'contact@example.com',
                'message' => 'Bonjour, un site comme celui-ci ?',
                'website' => 'spam',
                'audience' => 'client',
                'projectType' => 'creation',
                'deadline' => 'flexible',
            ],
            [
                'HTTP_REFERER' => 'http://localhost/creation-site-web/projets/maha',
            ],
        );

        self::assertResponseRedirects('/creation-site-web/projets/maha');
    }

    public function testContactIgnoresARefererFromAnotherSite(): void
    {
        $client = static::createClient();

        $client->jsonRequest(
            'POST',
            '/contact',
            [
                'name' => 'Boulangerie',
                'email' => 'contact@example.com',
                'message' => 'Bonjour, il me faudrait un site.',
                'website' => 'spam',
                'audience' => 'client',
                'projectType' => 'creation',
                'deadline' => 'flexible',
            ],
            ['HTTP_REFERER' => 'https://evil.example/phishing'],
        );

        // pas de redirection vers un site extérieur
        self::assertResponseRedirects('/creation-site-web');
    }

    public function testQuoteRequestRequiresProjectTypeAndDeadline(): void
    {
        $client = static::createClient();
        $client->disableReboot();
        $this->stubProjects([]);
        $this->stubTimeline();
        $this->stubReviews();
        $this->stubOffers();

        // envoyé comme le formulaire Inertia : erreurs renvoyées sur la page d'origine
        $client->jsonRequest(
            'POST',
            '/contact',
            [
                'name' => 'Boulangerie',
                'email' => 'contact@example.com',
                'message' => 'Bonjour, il me faudrait un site.',
                'audience' => 'client',
                'budget' => 'unknown',
            ],
            [
                'HTTP_X_INERTIA' => 'true',
                'HTTP_REFERER' => 'http://localhost/creation-site-web',
            ],
        );

        self::assertResponseRedirects(
            'http://localhost/creation-site-web',
            303,
        );
        $page = $this->inertiaPage(
            $client->request('GET', '/creation-site-web'),
        );
        self::assertSame(
            'Précisez le type de projet.',
            $page['props']['errors']['projectType'],
        );
        self::assertSame(
            'Précisez le délai souhaité.',
            $page['props']['errors']['deadline'],
        );
        // budget facultatif
        self::assertArrayNotHasKey('budget', $page['props']['errors']);
    }

    public function testTeamMessageNeedsNoQuoteFields(): void
    {
        $client = static::createClient();

        $client->jsonRequest('POST', '/contact', [
            'name' => 'Recruteur',
            'email' => 'rh@example.com',
            'message' => 'Bonjour, un poste à pourvoir.',
            'website' => 'spam',
            'audience' => 'team',
        ]);

        self::assertResponseRedirects('/');
    }

    public function testBothPagesShowTheValidatedReviews(): void
    {
        $client = static::createClient();
        $client->disableReboot();
        $this->stubProjects([]);
        $this->stubTimeline();
        $this->stubOffers();
        $maha = $this->project('la-cuisine-de-maha', 1, 1)->setName(
            'La cuisine de Maha',
        );
        $review = new Review()
            ->setAuthor('Marie D.')
            ->setAuthorRole('Gérante')
            ->setText('Site livré dans les temps.')
            ->setGoogleRating(5)
            ->setGoogleReviewUrl('https://share.google/abc')
            ->setCreatedAt(new \DateTimeImmutable('2026-10-01T09:30:00+00:00'))
            ->addProject($maha);
        new \ReflectionProperty(Review::class, 'id')->setValue($review, 4);
        $this->stubReviews([$review]);

        foreach (['/', '/creation-site-web'] as $url) {
            $page = $this->inertiaPage($client->request('GET', $url));

            self::assertSame(
                [
                    [
                        'id' => 4,
                        'author' => 'Marie D.',
                        'authorRole' => 'Gérante',
                        'postedAt' => '2026-10-01T09:30:00+00:00',
                        'text' => 'Site livré dans les temps.',
                        'googleRating' => 5,
                        'googleReviewUrl' => 'https://share.google/abc',
                        'projects' => [
                            [
                                'name' => 'La cuisine de Maha',
                                'slug' => 'la-cuisine-de-maha',
                            ],
                        ],
                    ],
                ],
                $page['props']['reviews'],
                $url,
            );
        }
    }

    public function testSitemapListsTheClientPage(): void
    {
        $client = static::createClient();
        $this->stubProjects([]);

        $client->request('GET', '/sitemap.xml');

        self::assertResponseIsSuccessful();
        self::assertStringContainsString(
            '<loc>http://localhost/creation-site-web</loc>',
            $client->getResponse()->getContent(),
        );
    }

    /**
     * Vrai findOrderedFor() (tri par page), sur des projets en mémoire à la place de la base.
     *
     * @param list<Project> $projects
     */
    private function stubProjects(array $projects): void
    {
        $repository = self::getStubBuilder(ProjectRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['findBy', 'findAll'])
            ->getStub();
        $repository->method('findAll')->willReturn($projects);
        $repository
            ->method('findBy')
            ->willReturnCallback(static function (
                array $criteria,
                array $orderBy,
            ) use ($projects): array {
                $field = array_key_first($orderBy);
                usort(
                    $projects,
                    static fn (Project $a, Project $b): int => 'teamPosition' ===
                    $field
                        ? $a->getTeamPosition() <=> $b->getTeamPosition()
                        : $a->getPosition() <=> $b->getPosition(),
                );

                return $projects;
            });
        static::getContainer()->set(ProjectRepository::class, $repository);
    }

    private function stubOffers(): void
    {
        $repository = $this->createStub(OfferRepository::class);
        $repository->method('findForHome')->willReturn([]);
        static::getContainer()->set(OfferRepository::class, $repository);
    }

    /**
     * @param list<Experience> $experiences
     */
    private function stubTimeline(array $experiences = []): void
    {
        $repository = $this->createStub(ExperienceRepository::class);
        $repository->method('findForTimeline')->willReturn($experiences);
        static::getContainer()->set(ExperienceRepository::class, $repository);
    }

    /**
     * @param list<Review> $reviews avis déjà validés
     */
    private function stubReviews(array $reviews = []): void
    {
        $repository = $this->createStub(ReviewRepository::class);
        $repository->method('findValidated')->willReturn($reviews);
        static::getContainer()->set(ReviewRepository::class, $repository);
    }

    private function project(
        string $slug,
        int $position,
        int $teamPosition,
    ): Project {
        return new Project()
            ->setName(ucfirst($slug))
            ->setSlug($slug)
            ->setDate('2025')
            ->setTechnos('React')
            ->setBackground("$slug.webp")
            ->setPosition($position)
            ->setTeamPosition($teamPosition);
    }

    /**
     * @return array<string, mixed>
     */
    private function inertiaPage(Crawler $crawler): array
    {
        return json_decode(
            $crawler->filter('script[data-page="app"]')->text(),
            true,
            flags: \JSON_THROW_ON_ERROR,
        );
    }
}
