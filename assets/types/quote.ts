// demande de devis (page création de site) : mêmes valeurs que les enums PHP
// QuoteProjectType, QuoteBudget et QuoteDeadline, qui les valident côté serveur
export const projectTypes = [
    { value: "creation", label: "Création de site" },
    { value: "redesign", label: "Refonte" },
    { value: "application", label: "Application sur mesure" },
    { value: "maintenance", label: "Hébergement et maintenance" },
] as const;

export const budgets = [
    { value: "under-1500", label: "Moins de 1 500 €" },
    { value: "1500-2500", label: "1 500 à 2 500 €" },
    { value: "2500-4000", label: "2 500 à 4 000 €" },
    { value: "over-4000", label: "Plus de 4 000 €" },
    { value: "unknown", label: "Je ne sais pas encore" },
] as const;

export const deadlines = [
    { value: "asap", label: "Dès que possible" },
    { value: "1-month", label: "Sous un mois" },
    { value: "1-3-months", label: "D'ici 1 à 3 mois" },
    { value: "over-3-months", label: "Dans plus de 3 mois" },
    { value: "flexible", label: "Pas de date précise" },
] as const;
