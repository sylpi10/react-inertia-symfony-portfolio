<?php

namespace App\Enum;

/**
 * Public visé par une page : l'accueil parle aux équipes (recruteurs, CTO,
 * agences), /creation-site-web aux clients (indépendants, TPE).
 */
enum Audience: string
{
    case Client = "client";
    case Team = "team";

    public function label(): string
    {
        return match ($this) {
            self::Client => "Un site pour mon activité",
            self::Team => "Un développeur pour votre équipe",
        };
    }

    // route de la page dédiée à ce public
    public function route(): string
    {
        return match ($this) {
            self::Team => "home",
            self::Client => "client",
        };
    }
}
