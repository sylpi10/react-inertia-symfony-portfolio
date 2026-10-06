<?php

namespace App\Service;

use App\Entity\Project;
use App\Enum\Audience;
use App\Repository\ExperienceRepository;
use App\Repository\OfferRepository;
use App\Repository\ProjectRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Données des pages, prêtes pour Inertia et adaptées au public de la page.
 */
final class PagePresenter
{
    public function __construct(
        private readonly ExperienceRepository $experienceRepository,
        private readonly OfferRepository $offerRepository,
        private readonly ProjectRepository $projectRepository,
        private readonly NormalizerInterface $normalizer,
        private readonly ProjectSummary $summary,
        private readonly ProjectShareImage $shareImage,
    ) {}

    /**
     * Cartes projet, dans l'ordre de la page, avec le texte court du public de la page ;
     * thumbnail : vignette générée depuis la capture (null tant qu'elle n'existe pas,
     * le front reprend alors background).
     *
     * @return list<array<string, mixed>>
     */
    public function projects(Audience $audience): array
    {
        $projects = $this->projectRepository->findOrderedFor($audience);
        $data = $this->normalizer->normalize(
            $projects,
            context: ["groups" => ["project:list"]],
        );
        foreach ($projects as $i => $project) {
            $data[$i]["miniDescription"] = $project->getMiniDescriptionFor(
                $audience,
            );
            $data[$i]["thumbnail"] = $this->shareImage->thumbnailPath($project);
        }

        return $data;
    }

    /**
     * Parcours, avec les missions décrites pour le public de la page.
     *
     * @return list<array<string, mixed>>
     */
    public function experiences(Audience $audience): array
    {
        $timeline = $this->experienceRepository->findForTimeline();
        $experiences = $this->normalizer->normalize(
            $timeline,
            context: ["groups" => ["experience:list"]],
        );
        foreach ($timeline as $i => $experience) {
            $experiences[$i]["description"] = $experience->getDescriptionFor(
                $audience,
            );
        }

        return $experiences;
    }

    /**
     * Services de la page création de site ; les projets cités n'exposent que nom et slug.
     *
     * @return list<array<string, mixed>>
     */
    public function offers(): array
    {
        return $this->normalizer->normalize(
            $this->offerRepository->findForHome(),
            context: ["groups" => ["offer:list"]],
        );
    }

    /**
     * Page détail d'un projet, avec la description du public de la page.
     *
     * @return array<string, mixed>
     */
    public function project(Project $project, Audience $audience): array
    {
        $data = $this->normalizer->normalize(
            $project,
            context: ["groups" => ["project:detail"]],
        );
        $data["description"] = $project->getDescriptionFor($audience);

        return $data;
    }

    /**
     * Lien précédent/suivant d'une page projet.
     *
     * @return array{slug: string, name: string, teaser: string, background: ?string}|null
     */
    public function link(?Project $project, Audience $audience): ?array
    {
        return $project
            ? [
                "slug" => $project->getSlug(),
                "name" => $project->getName(),
                "teaser" => $this->summary->teaser($project, $audience),
                "background" => $project->getBackground(),
            ]
            : null;
    }
}
