<?php

namespace App\Tests\Controller;

use App\Dto\ReviewRequest;
use App\Entity\Project;
use App\Entity\Review;
use App\Repository\ProjectRepository;
use App\Service\ReviewSubmission;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;

// enregistrement et mail : voir ReviewSubmissionTest
final class ReviewControllerTest extends WebTestCase
{
    public function testFormListsReviewableProjectsAndIsNotIndexed(): void
    {
        $client = static::createClient();
        $repository = $this->createStub(ProjectRepository::class);
        $repository
            ->method("findReviewable")
            ->willReturn([$this->project(3, "Maha"), $this->project(7, "Ava")]);
        static::getContainer()->set(ProjectRepository::class, $repository);

        $crawler = $client->request("GET", "/avis");

        self::assertResponseIsSuccessful();
        self::assertSame(
            "noindex, nofollow",
            $crawler->filter('meta[name="robots"]')->attr("content"),
        );
        $page = $this->inertiaPage($crawler);
        self::assertSame("Review", $page["component"]);
        self::assertSame(
            [["id" => 3, "name" => "Maha"], ["id" => 7, "name" => "Ava"]],
            $page["props"]["projects"],
        );
    }

    public function testValidReviewIsSubmittedAndThanked(): void
    {
        $client = $this->client();
        $submission = $this->createMock(ReviewSubmission::class);
        $submission
            ->expects(self::once())
            ->method("submit")
            ->with(
                self::callback(
                    fn(ReviewRequest $data) => "Marie D." === $data->author &&
                        [3] === $data->projects &&
                        $data->consent,
                ),
            )
            ->willReturn(new Review());
        static::getContainer()->set(ReviewSubmission::class, $submission);

        $this->post($client, ["projects" => [3]]);

        self::assertResponseRedirects("/avis");
        self::assertSame(
            "Merci pour votre avis ! Il sera publié après relecture.",
            $this->flash($client, "success"),
        );
    }

    public function testConsentIsRequired(): void
    {
        $client = $this->client();
        $this->expectNoSubmission();

        $this->post($client, ["projects" => [3], "consent" => false]);

        self::assertResponseStatusCodeSame(303);
        self::assertSame(
            "Votre accord est nécessaire pour publier l'avis.",
            $this->errors($client)["consent"],
        );
    }

    public function testTooShortReviewIsRefused(): void
    {
        $client = $this->client();
        $this->expectNoSubmission();
        $this->post($client, [
            "projects" => [3],
            "text" => "text 21 characterssss",
        ]);
        self::assertResponseStatusCodeSame(303);
        self::assertSame(
            "Votre avis doit faire au moins 20 caractères.",
            $this->errors($client)["text"],
        );
    }

    public function testReviewWithoutProjectIsAccepted(): void
    {
        $client = $this->client();
        $submission = $this->createMock(ReviewSubmission::class);
        $submission
            ->expects(self::once())
            ->method("submit")
            ->with(
                self::callback(
                    fn(ReviewRequest $data) => [] === $data->projects,
                ),
            )
            ->willReturn(new Review());
        static::getContainer()->set(ReviewSubmission::class, $submission);

        // avis sur le travail en général, sans projet coché
        $this->post($client, ["projects" => []]);

        self::assertResponseRedirects("/avis");
        self::assertSame(
            "Merci pour votre avis ! Il sera publié après relecture.",
            $this->flash($client, "success"),
        );
    }

    public function testRefusedProjectIsReportedOnTheForm(): void
    {
        $client = $this->client();
        $submission = $this->createStub(ReviewSubmission::class);
        $submission
            ->method("submit")
            ->willThrowException(
                new ValidationFailedException(
                    null,
                    new ConstraintViolationList([
                        new ConstraintViolation(
                            "Un des projets choisis n'est plus ouvert aux avis, rechargez la page.",
                            null,
                            [],
                            null,
                            "projects",
                            [3],
                        ),
                    ]),
                ),
            );
        static::getContainer()->set(ReviewSubmission::class, $submission);

        $this->post($client, ["projects" => [3]]);

        self::assertResponseStatusCodeSame(303);
        self::assertSame(
            "Un des projets choisis n'est plus ouvert aux avis, rechargez la page.",
            $this->errors($client)["projects"],
        );
    }

    public function testHoneypotLooksLikeASuccessButSavesNothing(): void
    {
        $client = $this->client();
        $this->expectNoSubmission();

        $this->post($client, [
            "projects" => [3],
            "website" => "http://spam.example",
        ]);

        self::assertResponseRedirects("/avis");
        self::assertSame(
            "Merci pour votre avis ! Il sera publié après relecture.",
            $this->flash($client, "success"),
        );
    }

    public function testSubmissionsAreRateLimited(): void
    {
        $client = $this->client();
        $submission = $this->createMock(ReviewSubmission::class);
        $submission
            ->expects(self::exactly(3))
            ->method("submit")
            ->willReturn(new Review());
        static::getContainer()->set(ReviewSubmission::class, $submission);

        // limite : 3 envois par heure et par IP
        for ($i = 0; $i < 4; ++$i) {
            $this->post($client, ["projects" => [3]]);
        }

        self::assertResponseRedirects("/avis");
        self::assertSame(
            "Trop d'envois, réessayez dans une heure.",
            $this->flash($client, "error"),
        );
    }

    // compteur du limiteur remis à zéro : il est conservé d'un test à l'autre
    private function client(): KernelBrowser
    {
        $client = static::createClient();
        // même noyau pour le POST et la page qui suit : les stubs sont conservés
        $client->disableReboot();
        static::getContainer()
            ->get("limiter.review_form")
            ->create("127.0.0.1")
            ->reset();

        return $client;
    }

    private function post(KernelBrowser $client, array $data): void
    {
        $client->request(
            "POST",
            "/avis",
            server: [
                "CONTENT_TYPE" => "application/json",
                "HTTP_X_INERTIA" => "true",
                "HTTP_REFERER" => "http://localhost/avis",
            ],
            content: json_encode(
                $data + [
                    "author" => "Marie D.",
                    "text" =>
                        "Site livré dans les temps, et je le modifie seule sans difficulté.",
                    "consent" => true,
                    "website" => "",
                ],
                \JSON_THROW_ON_ERROR,
            ),
        );
    }

    private function expectNoSubmission(): void
    {
        $submission = $this->createMock(ReviewSubmission::class);
        $submission->expects(self::never())->method("submit");
        static::getContainer()->set(ReviewSubmission::class, $submission);
    }

    // page affichée après la redirection : erreurs de validation…
    private function errors(KernelBrowser $client): array
    {
        return $this->followToPage($client)["props"]["errors"] ?? [];
    }

    // … ou message flash
    private function flash(KernelBrowser $client, string $type): ?string
    {
        $page = $this->followToPage($client);

        return $page["flash"][$type] ??
            ($page["props"]["flash"][$type] ?? null);
    }

    private function followToPage(KernelBrowser $client): array
    {
        // la page du formulaire liste les projets : pas de base en test
        $repository = $this->createStub(ProjectRepository::class);
        $repository->method("findReviewable")->willReturn([]);
        static::getContainer()->set(ProjectRepository::class, $repository);

        // requête Inertia : la page revient en JSON
        $client->followRedirect();

        return json_decode(
            $client->getResponse()->getContent(),
            true,
            flags: \JSON_THROW_ON_ERROR,
        );
    }

    private function project(int $id, string $name): Project
    {
        $project = new Project()->setName($name);
        new \ReflectionProperty(Project::class, "id")->setValue($project, $id);

        return $project;
    }

    private function inertiaPage(Crawler $crawler): array
    {
        return json_decode(
            $crawler->filter('script[data-page="app"]')->text(),
            true,
            flags: \JSON_THROW_ON_ERROR,
        );
    }
}
