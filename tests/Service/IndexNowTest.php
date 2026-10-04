<?php

namespace App\Tests\Service;

use App\Service\IndexNow;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class IndexNowTest extends KernelTestCase
{
    public function testSubmitsUrlsWithKeyAndKeyLocation(): void
    {
        $response = new MockResponse('', ['http_code' => 202]);
        $indexNow = new IndexNow(
            new MockHttpClient($response),
            static::getContainer()->get(UrlGeneratorInterface::class),
            'test-key',
        );

        $status = $indexNow->submit(['http://localhost/', 'http://localhost/projets/ava']);

        self::assertSame(202, $status);
        self::assertSame('POST', $response->getRequestMethod());
        self::assertSame('https://api.indexnow.org/indexnow', $response->getRequestUrl());
        self::assertSame([
            'host' => 'localhost',
            'key' => 'test-key',
            'keyLocation' => 'http://localhost/indexnow.txt',
            'urlList' => ['http://localhost/', 'http://localhost/projets/ava'],
        ], json_decode($response->getRequestOptions()['body'], true));
    }
}
