<?php

namespace App\Service;

use App\Dto\ReviewRequest;
use App\Entity\Review;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;

/**
 * Avis envoyé depuis le formulaire public : enregistré en attente de validation,
 * puis signalé par mail.
 */
class ReviewSubmission
{
    public function __construct(
        private readonly ProjectRepository $projects,
        private readonly EntityManagerInterface $em,
        private readonly MailerInterface $mailer,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly LoggerInterface $logger,
        /** @var list<string> */
        #[Autowire(env: 'csv:NOTIFICATION_EMAIL')]
        private readonly array $notificationEmails,
        private readonly MailAlert $mailAlert,
    ) {
    }

    /**
     * @throws ValidationFailedException projet inconnu, fermé aux avis ou ayant déjà son seul avis
     */
    public function submit(ReviewRequest $data): Review
    {
        $ids = array_values(array_unique($data->projects));
        $projects = $ids ? $this->projects->findReviewable($ids) : [];
        // projet inconnu, ou sorti du formulaire depuis son affichage (fermé, ou avis reçu entre-temps)
        if (\count($projects) !== \count($ids)) {
            throw new ValidationFailedException($data, new ConstraintViolationList([new ConstraintViolation("Un des projets choisis n'est plus ouvert aux avis, rechargez la page.", null, [], $data, 'projects', $data->projects)]));
        }

        $review = new Review()
            ->setAuthor(trim($data->author))
            ->setAuthorRole('' !== trim($data->authorRole) ? trim($data->authorRole) : null)
            ->setText($data->text)
            ->setConsentedAt(new \DateTimeImmutable());
        foreach ($projects as $project) {
            $review->addProject($project);
        }
        $this->em->persist($review);
        $this->em->flush();

        $this->notify($review);

        return $review;
    }

    // l'avis est enregistré : un mail perdu ne doit pas faire échouer l'envoi
    private function notify(Review $review): void
    {
        // avis sans projet : sur le travail en général
        $projects = implode(', ', $review->getProjects()->map(fn ($p) => $p->getName())->toArray()) ?: 'sans projet';

        try {
            $this->mailer->send(
                new Email()
                    ->to(...$this->notificationEmails)
                    ->subject(sprintf('Nouvel avis à valider – %s – %s', $review->getAuthor(), $projects))
                    ->text(sprintf(
                        "%s%s (%s) :\n\n%s\n\nValider : %s",
                        $review->getAuthor(),
                        $review->getAuthorRole() ? ', '.$review->getAuthorRole() : '',
                        $projects,
                        $review->getText(),
                        $this->urlGenerator->generate(
                            'admin_review_edit',
                            ['entityId' => $review->getId()],
                            UrlGeneratorInterface::ABSOLUTE_URL,
                        ),
                    )),
            );
            $this->mailAlert->sent(sprintf('Avis n°%d à valider', $review->getId()));
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('Mail de nouvel avis non envoyé', ['exception' => $e, 'review' => $review->getId()]);
            $this->mailAlert->failed(sprintf('Avis n°%d à valider', $review->getId()), $e);
        }
    }
}
