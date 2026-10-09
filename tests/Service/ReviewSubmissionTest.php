<?php

namespace App\Tests\Service;

use App\Dto\ReviewRequest;
use App\Entity\Project;
use App\Entity\Review;
use App\Repository\ProjectRepository;
use App\Service\ReviewSubmission;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final class ReviewSubmissionTest extends KernelTestCase
{
    private Project $maha;
    private Project $ava;

    protected function setUp(): void
    {
        $this->maha = $this->project(3, 'Maha');
        $this->ava = $this->project(7, 'Ava');
    }

    public function testReviewIsSavedPendingWithConsentOnTheChosenProjects(): void
    {
        $saved = null;
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects(self::once())->method('persist')->willReturnCallback(function (Review $review) use (&$saved) {
            $saved = $review;
        });
        // l'id est attribué à l'enregistrement
        $em->expects(self::once())->method('flush')->willReturnCallback(function () use (&$saved) {
            (new \ReflectionProperty(Review::class, 'id'))->setValue($saved, 12);
        });
        $sent = null;
        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects(self::once())->method('send')->willReturnCallback(function (Email $email) use (&$sent) {
            $sent = $email;
        });

        $review = $this->submission($em, $mailer)->submit($this->request([3, 7, 3], '  Gérante, La cuisine de Maha '));

        self::assertSame($saved, $review);
        self::assertSame('Marie D.', $review->getAuthor());
        self::assertSame('Gérante, La cuisine de Maha', $review->getAuthorRole());
        self::assertFalse($review->isValidated());
        self::assertNotNull($review->getConsentedAt());
        self::assertSame([$this->maha, $this->ava], $review->getProjects()->toArray());
        self::assertSame($review, $this->maha->getReview());

        self::assertSame('Nouvel avis à valider – Marie D. – Maha, Ava', $sent->getSubject());
        self::assertStringStartsWith('Marie D., Gérante, La cuisine de Maha (Maha, Ava) :', $sent->getTextBody());
        self::assertStringContainsString('Site livré dans les temps', $sent->getTextBody());
        self::assertStringContainsString('http://localhost/admin/review/12/edit', $sent->getTextBody());
    }

    public function testProjectAlreadyReviewedIsRefusedWithoutSaving(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects(self::never())->method('persist');
        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects(self::never())->method('send');
        // 9 : déjà un avis, ou inconnu
        $submission = $this->submission($em, $mailer);

        try {
            $submission->submit($this->request([3, 9]));
            self::fail('ValidationFailedException attendue');
        } catch (ValidationFailedException $e) {
            self::assertSame('projects', $e->getViolations()->get(0)->getPropertyPath());
        }
        self::assertNull($this->maha->getReview());
    }

    public function testMailFailureDoesNotLoseTheReview(): void
    {
        $em = $this->createStub(EntityManagerInterface::class);
        $em->method('persist')->willReturnCallback(function (Review $review) {
            (new \ReflectionProperty(Review::class, 'id'))->setValue($review, 12);
        });
        $mailer = $this->createStub(MailerInterface::class);
        $mailer->method('send')->willThrowException(new TransportException('SMTP down'));

        $review = $this->submission($em, $mailer)->submit($this->request([3]));

        self::assertSame(12, $review->getId());
        // poste non renseigné : rien à afficher sous le nom
        self::assertNull($review->getAuthorRole());
    }

    private function submission(EntityManagerInterface $em, MailerInterface $mailer): ReviewSubmission
    {
        // projets sans avis : 3 et 7
        $repository = $this->createStub(ProjectRepository::class);
        $repository->method('findWithoutReview')->willReturnCallback(fn (array $ids) => array_values(array_filter(
            [$this->maha, $this->ava],
            fn (Project $p) => \in_array($p->getId(), $ids, true),
        )));

        return new ReviewSubmission(
            $repository,
            $em,
            $mailer,
            self::getContainer()->get(UrlGeneratorInterface::class),
            new NullLogger(),
        );
    }

    /**
     * @param list<int> $projects
     */
    private function request(array $projects, string $authorRole = ''): ReviewRequest
    {
        return new ReviewRequest(
            author: 'Marie D.',
            authorRole: $authorRole,
            text: 'Site livré dans les temps, et je le modifie seule sans difficulté.',
            projects: $projects,
            consent: true,
        );
    }

    private function project(int $id, string $name): Project
    {
        $project = (new Project())->setName($name);
        (new \ReflectionProperty(Project::class, 'id'))->setValue($project, $id);

        return $project;
    }
}
