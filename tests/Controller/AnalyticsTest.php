<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// MATOMO_URL : https://stats.example.test en test (.env.test), vide en dev
final class AnalyticsTest extends WebTestCase
{
    protected function tearDown(): void
    {
        $_ENV['MATOMO_URL'] = $_SERVER['MATOMO_URL'] =
            'https://stats.example.test';
        parent::tearDown();
    }

    public function testConfiguredSiteIsTrackedWithoutCookies(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/mentions-legales');

        self::assertResponseIsSuccessful();
        self::assertSame(
            'https://stats.example.test/matomo.js',
            $crawler->filter('script[src$="matomo.js"]')->attr('src'),
        );
        $script = $crawler
            ->filter('script:not([src])')
            ->reduce(fn ($node) => str_contains($node->text(), '_paq'))
            ->text();
        self::assertStringContainsString(
            '["setTrackerUrl", "https:\/\/stats.example.test\/matomo.php"]',
            str_replace("'", '"', $script),
        );
        self::assertStringContainsString("['setSiteId', \"1\"]", $script);
        // exemption de consentement : les cookies sont coupés avant la première page vue
        self::assertLessThan(
            strpos($script, 'trackPageView'),
            strpos($script, 'disableCookies'),
        );
    }

    public function testNothingIsTrackedWithoutMatomoUrl(): void
    {
        $_ENV['MATOMO_URL'] = $_SERVER['MATOMO_URL'] = '';
        $client = static::createClient();

        $client->request('GET', '/mentions-legales');

        self::assertResponseIsSuccessful();
        self::assertStringNotContainsString(
            '_paq',
            $client->getResponse()->getContent(),
        );
        self::assertStringNotContainsString(
            'matomo.js',
            $client->getResponse()->getContent(),
        );
    }
}
