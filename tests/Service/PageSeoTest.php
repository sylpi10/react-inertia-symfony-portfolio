<?php

namespace App\Tests\Service;

use App\Enum\Audience;
use App\Service\PageSeo;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

// longueurs recommandées par les outils SEO : au-delà, Google coupe ; en deçà, la place est perdue
final class PageSeoTest extends KernelTestCase
{
    /**
     * @return iterable<string, array{\Closure(PageSeo): array{title: string, description: string}}>
     */
    public static function pages(): iterable
    {
        yield 'accueil' => [
            fn (PageSeo $seo) => $seo->audiencePage(Audience::Team),
        ];
        yield 'création de site' => [
            fn (PageSeo $seo) => $seo->audiencePage(Audience::Client),
        ];
        yield 'page locale' => [fn (PageSeo $seo) => $seo->local()];
    }

    #[DataProvider('pages')]
    public function testTitleLengthIsWithinSeoLimits(\Closure $page): void
    {
        $title = $page(static::getContainer()->get(PageSeo::class))['title'];

        self::assertGreaterThanOrEqual(30, mb_strlen($title), $title);
        self::assertLessThanOrEqual(60, mb_strlen($title), $title);
    }
}
