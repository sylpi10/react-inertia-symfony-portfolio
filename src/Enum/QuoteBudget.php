<?php

namespace App\Enum;

// formulaire de contact, page création de site : budget indicatif (facultatif)
enum QuoteBudget: string
{
    case Under1500 = "under-1500";
    case From1500To2500 = "1500-2500";
    case From2500To4000 = "2500-4000";
    case Over4000 = "over-4000";
    case Unknown = "unknown";

    public function label(): string
    {
        return match ($this) {
            self::Under1500 => "Moins de 1 500 €",
            self::From1500To2500 => "1 500 à 2 500 €",
            self::From2500To4000 => "2 500 à 4 000 €",
            self::Over4000 => "Plus de 4 000 €",
            self::Unknown => "Je ne sais pas encore",
        };
    }
}
