<?php

namespace App\Controller;

use App\Dto\ContactRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Nytodev\InertiaBundle\Service\Inertia;
use App\Entity\Contact;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    public function __construct(
        protected MailerInterface $mailer,
        protected EntityManagerInterface $em,
        private readonly Inertia $inertia,
    ) {}

    #[Route("/contact", name: "app_contact", methods: ["POST"])]
    public function contact(
        #[MapRequestPayload] ContactRequest $data,
        Request $request,
    ): Response {
        // honeypot rempli = robot : même réponse qu'un succès, sans rien faire
        if ("" !== $data->website) {
            $this->inertia->flash(
                "success",
                "Message envoyé, je vous réponds vite !",
            );
            return $this->back($request, $data);
        }

        $contact = new Contact()
            ->setName($data->name)
            ->setEmail($data->email)
            ->setMessage($data->message)
            ->setDate(new \DateTime())
            ->setProjectType($data->projectType)
            ->setBudget($data->budget)
            ->setDeadline($data->deadline);
        $this->em->persist($contact);
        $this->em->flush();

        try {
            $this->mailer->send(
                new Email()
                    ->to("syl.pillet@hotmail.fr")
                    ->from("sylpi@sylvainpillet.com")
                    ->replyTo($data->email)
                    ->subject(
                        sprintf(
                            "Nouveau message du portfolio – %s – %s",
                            $data->audience->label(),
                            $data->name,
                        ),
                    )
                    ->text($this->mailBody($data)),
            );
            $this->inertia->flash(
                "success",
                "Message envoyé, je reviens vite vers vous !",
            );
        } catch (TransportExceptionInterface) {
            $this->inertia->flash(
                "error",
                "L'envoi a échoué, réessayez un peu plus tard.",
            );
        }

        return $this->back($request, $data);
    }

    // demande de devis : type, budget et délai en tête du message
    private function mailBody(ContactRequest $data): string
    {
        $quote = array_filter([
            "Type de projet" => $data->projectType?->label(),
            "Budget indicatif" => $data->budget?->label(),
            "Délai souhaité" => $data->deadline?->label(),
        ]);
        if (!$quote) {
            return $data->message;
        }

        $lines = array_map(
            fn (string $field, string $value): string => "$field : $value",
            array_keys($quote),
            $quote,
        );

        return implode("\n", $lines) . "\n\n" . $data->message;
    }

    // retour à la page du formulaire (accueil, création de site ou page projet) ;
    // referer absent ou d'un autre site : page d'accueil du mode
    private function back(Request $request, ContactRequest $data): Response
    {
        $referer = (string) $request->headers->get("referer");
        if ($request->getHost() === parse_url($referer, \PHP_URL_HOST)) {
            $query = parse_url($referer, \PHP_URL_QUERY);

            return $this->redirect(
                (parse_url($referer, \PHP_URL_PATH) ?: "/") .
                    ($query ? "?" . $query : ""),
            );
        }

        return $this->redirectToRoute($data->audience->route());
    }
}
