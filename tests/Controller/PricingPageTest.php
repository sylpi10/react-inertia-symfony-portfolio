<?php

namespace App\Tests\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PricingPageTest extends WebTestCase
{
    public function testPricingPageIsIndexableWithTheCurrentYear(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/creation-site-web/prix-site-vitrine');

        self::assertResponseIsSuccessful();

        $year = date('Y');
        $title = $crawler->filter('title')->text();
        self::assertSame("Prix d’un site vitrine en $year | Sylvain Pillet", $title);
        self::assertLessThanOrEqual(60, mb_strlen($title));

        $description = $crawler
            ->filter('meta[name="description"]')
            ->attr('content');
        self::assertStringContainsString('1 200 €', $description);
        self::assertLessThanOrEqual(155, mb_strlen($description));

        self::assertSame(
            'http://localhost/creation-site-web/prix-site-vitrine',
            $crawler->filter('link[rel="canonical"]')->attr('href'),
        );

        $page = json_decode(
            $crawler->filter('script[data-page="app"]')->text(),
            true,
            flags: \JSON_THROW_ON_ERROR,
        );
        self::assertSame('PrixSiteVitrine', $page['component']);
        self::assertSame('client', $page['props']['audience']);
        self::assertSame((int) $year, $page['props']['year']);
    }

    public function testPricingPageIsListedInTheSitemap(): void
    {
        $client = static::createClient();
        // sitemap : liste des projets lue en base, remplacée par une liste vide
        $projects = $this->createStub(ProjectRepository::class);
        $projects->method('findAll')->willReturn([]);
        static::getContainer()->set(ProjectRepository::class, $projects);

        $client->request('GET', '/sitemap.xml');

        self::assertResponseIsSuccessful();
        self::assertStringContainsString(
            '<loc>http://localhost/creation-site-web/prix-site-vitrine</loc>',
            (string) $client->getResponse()->getContent(),
        );
    }
}
