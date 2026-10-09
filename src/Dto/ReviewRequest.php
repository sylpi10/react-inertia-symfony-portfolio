<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

// formulaire public d'avis (/avis)
final readonly class ReviewRequest
{
    /**
     * @param list<int> $projects ids des projets concernés
     */
    public function __construct(
        #[Assert\NotBlank(message: "Votre nom est requis.")]
        #[
            Assert\Length(
                min: 2,
                max: 100,
                minMessage: "Votre nom doit faire au moins {{ limit }} caractères.",
                maxMessage: "Votre nom ne peut pas dépasser {{ limit }} caractères.",
            ),
        ]
        public string $author = "",
        // facultatif : poste, entreprise
        #[
            Assert\Length(
                max: 100,
                maxMessage: "Ce champ ne peut pas dépasser {{ limit }} caractères.",
            ),
        ]
        public string $authorRole = "",
        #[Assert\NotBlank(message: "L'avis ne peut pas être vide.")]
        #[
            Assert\Length(
                min: 20,
                max: 1000,
                minMessage: "Votre avis doit faire au moins {{ limit }} caractères.",
                maxMessage: "Votre avis ne peut pas dépasser {{ limit }} caractères.",
            ),
        ]
        public string $text = "",
        #[Assert\Count(min: 1, minMessage: "Choisissez au moins un projet.")]
        #[Assert\All([new Assert\Type("int")])]
        public array $projects = [],
        #[Assert\IsTrue(message: "Votre accord est nécessaire pour publier l'avis.")]
        public bool $consent = false,
        // honeypot
        public string $website = "",
    ) {}
}
