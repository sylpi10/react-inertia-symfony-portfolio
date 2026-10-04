<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Service\ProjectShareImage;
use App\Service\ProjectSummary;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Nytodev\InertiaBundle\Service\Inertia;

class ProjectsController extends AbstractController
{
    private const string HOME_TITLE = "Sylvain Pillet – Développeur Frontend / fullstack freelance à Toulouse";
    private const string HOME_DESCRIPTION = "Développeur web freelance basé à Toulouse, 5 ans d’expérience en e-commerce. Frontend, UI/UX et interfaces modernes, sans négliger le backend.";
    private const array ERROR_SEO = [
        "title" => "Page introuvable | Sylvain Pillet",
        "description" => self::HOME_DESCRIPTION,
        "robots" => "noindex, follow",
    ];

    public function __construct(
        protected ProjectRepository $projectRepository,
        private readonly Inertia $inertia,
        private readonly ProjectSummary $summary,
        private readonly ProjectShareImage $shareImage,
    ) {}

    #[Route("/", name: "home", methods: ["GET"])]
    public function getProjects(): Response
    {
        try {
            $projects = $this->projectRepository->findAll();
            return $this->inertia->render(
                "Home",
                [
                    "projects" => $projects,
                    "seo" => [
                        "title" => self::HOME_TITLE,
                        "description" => self::HOME_DESCRIPTION,
                    ],
                ],
                ["groups" => ["project:list"]],
            );
        } catch (\Throwable $e) {
            error_log("[API PROJECTS ERROR] " . $e->getMessage());
            return $this->inertia->render("Error", [
                "message" => "La page n'existe pas",
                "seo" => self::ERROR_SEO,
            ]);
        }
    }

    // ancienne URL par id, conservée pour les liens et l'index existants
    #[Route("/project/{id}", name: "projects_details_legacy", requirements: ["id" => "\\d+"], methods: ["GET"])]
    public function redirectLegacyProject(
        #[MapEntity(id: "id")] Project $project,
    ): Response {
        return $this->redirectToRoute(
            "projects_details",
            ["slug" => $project->getSlug()],
            Response::HTTP_MOVED_PERMANENTLY,
        );
    }

    #[Route("/projets/{slug}", name: "projects_details", methods: ["GET"])]
    public function getProjectDetails(
        #[MapEntity(mapping: ["slug" => "slug"])] Project $project,
    ): Response {
        try {
            $adjacent = $this->projectRepository->findAdjacent($project);

            return $this->inertia->render(
                "ProjectDetails",
                [
                    "project" => $project,
                    "previous" => $this->projectLink($adjacent["previous"]),
                    "next" => $this->projectLink($adjacent["next"]),
                    "seo" => [
                        "title" =>
                            $project->getName() .
                            " – Projet web | Sylvain Pillet",
                        "description" => $this->summary->metaDescription(
                            $project,
                        ),
                        "image" => $this->shareImageSeo($project),
                    ],
                ],
                ["groups" => ["project:detail"]],
            );
        } catch (\Throwable $e) {
            error_log("[API PROJECTS ERROR] " . $e->getMessage());
            return $this->inertia->render("Error", [
                "message" => "La page n'existe pas",
                "seo" => self::ERROR_SEO,
            ]);
        }
    }

    /**
     * @return array{slug: string, name: string, teaser: string, background: ?string}|null
     */
    private function projectLink(?Project $project): ?array
    {
        return $project
            ? [
                "slug" => $project->getSlug(),
                "name" => $project->getName(),
                "teaser" => $this->summary->teaser($project),
                "background" => $project->getBackground(),
            ]
            : null;
    }

    /**
     * @return array{url: string, width: int, height: int, alt: string}|null
     */
    private function shareImageSeo(Project $project): ?array
    {
        $path = $this->shareImage->publicPath($project);

        return $path
            ? [
                "url" => $path,
                "width" => ProjectShareImage::WIDTH,
                "height" => ProjectShareImage::HEIGHT,
                "alt" => sprintf("Capture du site %s", $project->getName()),
            ]
            : null;
    }
}
