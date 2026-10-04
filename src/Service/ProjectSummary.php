<?php

namespace App\Service;

use App\Entity\Project;
use Symfony\Component\String\TruncateMode;

use function Symfony\Component\String\u;

final class ProjectMetaDescription
{
    private const int MIN_LENGTH = 120;
    private const int MAX_LENGTH = 155;

    // description en HTML en base : texte brut, ramené entre 120 et 155
    // caractères pour ne pas être jugée trop courte ni tronquée par Google
    public function forProject(Project $project): string
    {
        $text = u(
            html_entity_decode(strip_tags((string) $project->getDescription())),
        )->collapseWhitespace();
        $summary = sprintf(
            "Projet %s réalisé par Sylvain Pillet, développeur web freelance à Toulouse, de la conception à la mise en ligne. Technologies : %s.",
            $project->getName(),
            $project->getTechnos(),
        );

        if ($text->isEmpty()) {
            $text = u($summary);
        } elseif ($text->length() < self::MIN_LENGTH) {
            $text = $text->trimEnd(" .")->append(". ", $summary);
        }

        return $text
            ->truncate(self::MAX_LENGTH, "…", TruncateMode::WordBefore)
            ->toString();
    }
}
