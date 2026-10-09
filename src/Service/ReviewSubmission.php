<?php

namespace App\Service;

use App\Dto\ReviewRequest;
use App\Entity\Review;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
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
    ) {}

    /**
     * @throws ValidationFailedException projet inconnu ou ayant déjà un avis
     */
    public function submit(ReviewRequest $data): Review
    {
        $ids = array_values(array_unique($data->projects));
        $projects = $this->projects->findWithoutReview($ids);
        // un projet n'a qu'un avis : déjà pris entre l'affichage du formulaire et l'envoi
        if (\count($projects) !== \count($ids)) {
            throw new ValidationFailedException($data, new ConstraintViolationList([
                new ConstraintViolation(
                    "Un des projets choisis a déjà reçu un avis, rechargez la page.",
                    null,
                    [],
                    $data,
                    "projects",
                    $data->projects,
                ),
            ]));
        }

        $review = new Review()
            ->setAuthor($data->author)
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
        $projects = implode(", ", $review->getProjects()->map(fn ($p) => $p->getName())->toArray());

        try {
            $this->mailer->send(
                new Email()
                    ->to("syl.pillet@hotmail.fr")
                    ->from("sylpi@sylvainpillet.com")
                    ->subject(sprintf("Nouvel avis à valider – %s – %s", $review->getAuthor(), $projects))
                    ->text(sprintf(
                        "%s (%s) :\n\n%s\n\nValider : %s",
                        $review->getAuthor(),
                        $projects,
                        $review->getText(),
                        $this->urlGenerator->generate(
                            "admin_review_edit",
                            ["entityId" => $review->getId()],
                            UrlGeneratorInterface::ABSOLUTE_URL,
                        ),
                    )),
            );
        } catch (TransportExceptionInterface $e) {
            $this->logger->error("Mail de nouvel avis non envoyé", ["exception" => $e, "review" => $review->getId()]);
        }
    }
}
