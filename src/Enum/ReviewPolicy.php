<?php

namespace App\Enum;

// place d'un projet dans le formulaire d'avis (/avis), réglée dans l'admin
enum ReviewPolicy: string
{
    // jamais proposé : projets persos (Portfolio…)
    case Closed = 'closed';
    // proposé tant qu'il n'a aucun avis (validé ou en attente), retiré ensuite
    case Single = 'single';
    // toujours proposé, quel que soit le nombre d'avis
    case Open = 'open';

    public function label(): string
    {
        return match ($this) {
            self::Closed => 'Fermé aux avis',
            self::Single => 'Un seul avis',
            self::Open => 'Plusieurs avis',
        };
    }
}
