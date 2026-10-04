<?php

namespace App\Tests\Controller;

use App\Entity\Experience;
use App\Entity\Project;
use App\Repository\ExperienceRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProjectsControllerSeoTest extends WebTestCase
{
    public function testHomeRendersSeoTagsServerSide(): void
    {
        $client = static::createClient();
        $repository = $this->createStub(ProjectRepository::class);
        $repository->method('findAll')->willReturn([]);
        static::getContainer()->set(ProjectRepository::class, $repository);
        $this->stubTimeline([]);

        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextSame('title', 'Sylvain Pillet – Développeur Frontend / fullstack freelance à Toulouse');
        self::assertStringContainsString('freelance basé à Toulouse', $crawler->filter('meta[name="description"]')->attr('content'));
        self::assertSame('http://localhost/', $crawler->filter('link[rel="canonical"]')->attr('href'));
        self::assertSame('http://localhost/images/sylvain-pillet.jpg', $crawler->filter('meta[property="og:image"]')->attr('content'));
        self::assertSame('/favicon.ico', $crawler->filter('link[rel="icon"]')->attr('href'));

        $person = json_decode($crawler->filter('script[type="application/ld+json"]')->text(), true, flags: \JSON_THROW_ON_ERROR);
        self::assertSame('Person', $person['@type']);
        self::assertSame('Toulouse', $person['address']['addressLocality']);
        // areaServed n'existe pas sur Person : erreur de validation schema.org
        self::assertArrayNotHasKey('areaServed', $person);
    }

    public function testIndexNowKeyFileServesTheKey(): void
    {
        $client = static::createClient();
        $client->request('GET', '/indexnow.txt');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('Content-Type', 'text/plain; charset=UTF-8');
        self::assertSame($_ENV['INDEXNOW_KEY'], $client->getResponse()->getContent());
    }

    public function testHomeExposesTimelineWithLinkedProjects(): void
    {
        $client = static::createClient();
        $projects = $this->createStub(ProjectRepository::class);
        $projects->method('findAll')->willReturn([]);
        static::getContainer()->set(ProjectRepository::class, $projects);

        $project = (new Project())->setName('Labelmaker')->setSlug('labelmaker')->setTechnos('React')->setDescription('<p>Secret</p>');
        $experience = (new Experience())
            ->setTitle('Développeur web')
            ->setOrganization('Ludilabel')
            ->setPeriod('2021/2026')
            ->setDescription('<p>Développement front-end</p>')
            ->setTechnos('Php, Symfony')
            ->addProject($project);
        $this->stubTimeline([$experience]);

        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        $page = json_decode($crawler->filter('script[data-page="app"]')->text(), true, flags: \JSON_THROW_ON_ERROR);
        $timeline = $page['props']['experiences'];
        self::assertCount(1, $timeline);
        self::assertSame('Ludilabel', $timeline[0]['organization']);
        self::assertSame('2021/2026', $timeline[0]['period']);
        // seuls nom et slug du projet lié sont exposés, pas sa description
        self::assertSame([['name' => 'Labelmaker', 'slug' => 'labelmaker']], $timeline[0]['projects']);
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
