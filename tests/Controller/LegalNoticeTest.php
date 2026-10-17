<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class LegalNoticeTest extends WebTestCase
{
    public function testLegalNoticeIsServedButNotIndexed(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/mentions-legales');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextSame('title', 'Mentions légales | Sylvain Pillet');
        self::assertSame('noindex, follow', $crawler->filter('meta[name="robots"]')->attr('content'));
        self::assertSame('http://localhost/mentions-legales', $crawler->filter('link[rel="canonical"]')->attr('href'));
        $page = json_decode($crawler->filter('script[data-page="app"]')->text(), true, flags: \JSON_THROW_ON_ERROR);
        self::assertSame('LegalNotice', $page['component']);
    }
}
