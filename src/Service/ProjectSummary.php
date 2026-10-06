<?php

namespace App\Service;

use App\Entity\Project;
use App\Enum\Audience;
use Symfony\Component\String\TruncateMode;
use Symfony\Component\String\UnicodeString;

use function Symfony\Component\String\u;

/**
 * Textes courts dérivés de la description HTML d'un projet, celle du public de la page.
 */
final class ProjectSummary
{
    private const int META_MIN_LENGTH = 120;
    private const int META_MAX_LENGTH = 155;
    private const int TEASER_MAX_LENGTH = 60;

    // ramenée entre 120 et 155 caractères pour ne pas être jugée trop courte
    // ni tronquée par Google
    public function metaDescription(Project $project, Audience $audience = Audience::Team): string
    {
        $text = $this->plainText($project, $audience);
        $summary = sprintf(
            "Projet %s réalisé par Sylvain Pillet, développeur web freelance à Toulouse, de la conception à la mise en ligne. Technologies : %s.",
            $project->getName(),
            $project->getTechnos(),
        );

        if ($text->isEmpty()) {
            $text = u($summary);
        } elseif ($text->length() < self::META_MIN_LENGTH) {
            $text = $text->trimEnd(" .")->append(". ", $summary);
        }

        return $text
            ->truncate(self::META_MAX_LENGTH, "…", TruncateMode::WordBefore)
            ->toString();
    }

    // accroche des liens vers un projet : la première phrase de la description,
    // ou à défaut les technos
    public function teaser(Project $project, Audience $audience = Audience::Team): string
    {
        $text = $this->plainText($project, $audience);
        $sentence = $text->isEmpty()
            ? u((string) $project->getTechnos())
            : u(preg_split('/(?<=[.!?])\s/u', $text->toString(), 2)[0])->trimEnd(" .");

        return $sentence
            ->truncate(self::TEASER_MAX_LENGTH, "…", TruncateMode::WordBefore)
            ->toString();
    }

    private function plainText(Project $project, Audience $audience): UnicodeString
    {
        // balises remplacées par un espace : les blocs (<p>, <h2>, <li>) ne se collent pas
        $text = preg_replace('/<[^>]*>/', ' ', (string) $project->getDescriptionFor($audience));

        return u(html_entity_decode($text))->collapseWhitespace();
    }
}
