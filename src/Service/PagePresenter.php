<?php

namespace App\Service;

use App\Entity\Project;
use App\Entity\Review;
use App\Enum\Audience;
use App\Repository\ExperienceRepository;
use App\Repository\OfferRepository;
use App\Repository\ProjectRepository;
use App\Repository\ReviewRepository;
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
        private readonly ReviewRepository $reviewRepository,
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

    /**
     * Avis validés, les plus récents d'abord ; postedAt en ISO 8601 (formatDay côté front),
     * projets réduits au nom et au slug pour le lien vers leur page.
     *
     * @return list<array{id: int, author: string, authorRole: ?string, postedAt: string, text: string, projects: list<array{name: string, slug: string}>}>
     */
    public function reviews(): array
    {
        return array_map(
            fn(Review $review) => [
                "id" => $review->getId(),
                "author" => $review->getAuthor(),
                "authorRole" => $review->getAuthorRole(),
                "postedAt" => $review->getCreatedAt()->format(\DATE_ATOM),
                "text" => $review->getText(),
                "projects" => array_map(
                    fn(Project $project) => [
                        "name" => $project->getName(),
                        "slug" => $project->getSlug(),
                    ],
                    $review->getProjects()->getValues(),
                ),
            ],
            $this->reviewRepository->findValidated(),
        );
    }
}
