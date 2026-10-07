<?php

namespace App\Enum;

// formulaire de contact, page création de site : délai souhaité
enum QuoteDeadline: string
{
    case Asap = "asap";
    case WithinOneMonth = "1-month";
    case OneToThreeMonths = "1-3-months";
    case OverThreeMonths = "over-3-months";
    case Flexible = "flexible";

    public function label(): string
    {
        return match ($this) {
            self::Asap => "Dès que possible",
            self::WithinOneMonth => "Sous un mois",
            self::OneToThreeMonths => "D'ici 1 à 3 mois",
            self::OverThreeMonths => "Dans plus de 3 mois",
            self::Flexible => "Pas de date précise",
        };
    }
}
