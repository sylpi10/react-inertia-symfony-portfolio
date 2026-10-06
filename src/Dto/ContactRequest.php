<?php

namespace App\Dto;

// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Enum\Audience;
use Symfony\Component\Validator\Constraints as Assert;

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
    ) {
        // honeypot
    }
}
