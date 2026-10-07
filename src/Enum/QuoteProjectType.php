<?php

namespace App\Enum;

// formulaire de contact, page création de site : type de projet
enum QuoteProjectType: string
{
    case Creation = "creation";
    case Redesign = "redesign";
    case Application = "application";
    case Maintenance = "maintenance";

    public function label(): string
    {
        return match ($this) {
            self::Creation => "Création de site",
            self::Redesign => "Refonte",
            self::Application => "Application sur mesure",
            self::Maintenance => "Hébergement et maintenance",
        };
    }
}
