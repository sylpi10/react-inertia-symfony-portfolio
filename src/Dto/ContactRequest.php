<?php

namespace App\Dto;

// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Enum\Audience;
use App\Enum\QuoteBudget;
use App\Enum\QuoteDeadline;
use App\Enum\QuoteProjectType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final readonly class ContactRequest
{
    public function __construct(
        #[Assert\NotBlank(message: "Votre nom est requis.")] #[
            Assert\Length(
                min: 3,
                max: 100,
                minMessage: "Votre nom doit faire au moins {{ limit }} caractères.",
                maxMessage: "Votre nom ne peut pas dépasser {{ limit }} caractères.",
            ),
        ]
        public string $name = "",
        #[Assert\NotBlank(message: "Votre email est requis.")] #[
            Assert\Email(message: "Votre email n'est pas valide."),
            Assert\Length(
                max: 100,
                maxMessage: "Votre email ne peut pas dépasser {{ limit }} caractères.",
            ),
        ]
        public string $email = "",
        #[Assert\NotBlank(message: "Le message ne peut pas être vide.")] #[
            Assert\Length(
                min: 10,
                max: 400,
                minMessage: "Votre message doit faire au moins {{ limit }} caractères.",
                maxMessage: "Votre message ne peut pas dépasser {{ limit }} caractères.",
            ),
        ]
        public string $message = "",
        public string $website = "",
        // page d'où vient le message (accueil ou page équipe)
        public Audience $audience = Audience::Client,
        // demande de devis, page création de site : type et délai requis (validateQuote), budget facultatif
        public ?QuoteProjectType $projectType = null,
        public ?QuoteBudget $budget = null,
        public ?QuoteDeadline $deadline = null,
    ) {
        // honeypot
    }

    // champs du devis affichés seulement sur la page création de site
    #[Assert\Callback]
    public function validateQuote(ExecutionContextInterface $context): void
    {
        if (Audience::Client !== $this->audience) {
            return;
        }
        if (null === $this->projectType) {
            $context->buildViolation("Précisez le type de projet.")
                ->atPath("projectType")
                ->addViolation();
        }
        if (null === $this->deadline) {
            $context->buildViolation("Précisez le délai souhaité.")
                ->atPath("deadline")
                ->addViolation();
        }
    }
}
