<?php

namespace App\Tests\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class LocalPageTest extends WebTestCase
{
    public function testLocalPageIsIndexableWithItsOwnSeoTags(): void
    {
        $client = static::createClient();
        $this->stubProjects();
        $crawler = $client->request("GET", "/developpeur-web-toulouse");

        $title = $crawler->filter("title")->text();

        self::assertLessThanOrEqual(60, mb_strlen($title), $title);

        self::assertResponseIsSuccessful();
        self::assertSelectorTextSame(
            "title",
            "Développeur web freelance à Toulouse | Sylvain Pillet",
        );
        $description = $crawler
            ->filter('meta[name="description"]')
            ->attr("content");
        self::assertStringStartsWith(
            "Développeur web freelance à Toulouse",
            $description,
        );
        self::assertLessThanOrEqual(155, mb_strlen($description));
        self::assertSame(
            "index, follow",
            $crawler->filter('meta[name="robots"]')->attr("content"),
        );
        self::assertSame(
            "http://localhost/developpeur-web-toulouse",
            $crawler->filter('link[rel="canonical"]')->attr("href"),
        );

        $page = json_decode(
            $crawler->filter('script[data-page="app"]')->text(),
            true,
            flags: \JSON_THROW_ON_ERROR,
        );
        self::assertSame("LocalToulouse", $page["component"]);
        // mode client : navigation et liens projet de la page création de site
        self::assertSame("client", $page["props"]["audience"]);
        self::assertSame(
            ["toulouse-sport-club"],
            array_column($page["props"]["projects"], "slug"),
        );
    }

    public function testLocalPageIsListedInTheSitemap(): void
    {
        $client = static::createClient();
        $this->stubProjects();

        $client->request("GET", "/sitemap.xml");

        self::assertResponseIsSuccessful();
        self::assertStringContainsString(
            "<loc>http://localhost/developpeur-web-toulouse</loc>",
            (string) $client->getResponse()->getContent(),
        );
    }

    public function testContactFormSentFromTheLocalPageComesBackToIt(): void
    {
        $client = static::createClient();

        // honeypot rempli : pas d'écriture en base ni d'envoi de mail
        $client->jsonRequest(
            "POST",
            "/contact",
            [
                "name" => "Boulangerie",
                "email" => "contact@example.com",
                "message" => "Bonjour, il me faudrait un site.",
                "website" => "spam",
                "audience" => "client",
                "projectType" => "creation",
                "deadline" => "flexible",
            ],
            ["HTTP_REFERER" => "http://localhost/developpeur-web-toulouse"],
        );

        self::assertResponseRedirects("/developpeur-web-toulouse");
    }

    private function stubProjects(): void
    {
        $project = new Project()
            ->setName("Toulouse Sport Club")
            ->setSlug("toulouse-sport-club")
            ->setDate("2019-2026")
            ->setTechnos("Next.js")
            ->setBackground("tsc.webp");
        $projects = $this->createStub(ProjectRepository::class);
        $projects->method("findOrderedFor")->willReturn([$project]);
        $projects->method("findAll")->willReturn([$project]);
        static::getContainer()->set(ProjectRepository::class, $projects);
    }
}
