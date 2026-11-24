// tarifs affichés sur la page création de site (Pricing.tsx) et la page prix
// (PrixSiteVitrine.tsx) ; le premier prix est aussi le priceRange de base.html.twig.
// Repères du marché (Codeur) : vitrine simple ~1 000 €, vitrine marketing
// 1 200 à 3 000 €, sur mesure 1 500 à 4 000 €
export type Plan = {
    title: string;
    description: string;
    price: string;
};

export const plans: Plan[] = [
    {
        title: "Site vitrine essentiel",
        description:
            "Quelques pages pour présenter votre activité, et un espace d’administration simple pour modifier vos textes et vos photos.",
        price: "à partir de 1 200 €",
    },
    {
        title: "Site vitrine sur mesure",
        description:
            "Un design personnalisé, un back-office complet pour gérer tous vos contenus et un référencement soigné.",
        price: "à partir de 2 200 €",
    },
    {
        title: "Refonte ou application sur mesure",
        description:
            "Reprise d’un site existant pour le moderniser, ou outil adapté à votre façon de travailler.",
        price: "sur devis",
    },
];
