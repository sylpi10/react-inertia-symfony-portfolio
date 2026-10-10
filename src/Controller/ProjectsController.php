<?php

namespace App\Controller;

use App\Entity\Project;
use App\Enum\Audience;
use App\Repository\ProjectRepository;
use App\Service\PagePresenter;
use App\Service\PageSeo;
use Nytodev\InertiaBundle\Service\Inertia;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// pages détail d'un projet (les pages d'accueil sont dans HomeController) ;
// les erreurs (projet introuvable…) sont rendues par InertiaErrorListener
class ProjectsController extends AbstractController
{
    public function __construct(
        private readonly Inertia $inertia,
        private readonly ProjectRepository $projectRepository,
        private readonly PagePresenter $presenter,
        private readonly PageSeo $seo,
    ) {}

    // ancienne URL par id, conservée pour les liens et l'index existants
    #[
        Route(
            "/project/{id}",
            name: "projects_details_legacy",
            requirements: ["id" => "\\d+"],
            methods: ["GET"],
        ),
    ]
    #[
        Route(
            "/projects/{id}",
            name: "projects_details_legacy_plural",
            requirements: ["id" => "\\d+"],
            methods: ["GET"],
        ),
    ]
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
        return $this->renderProjectDetails($project, Audience::Team);
    }

    // même projet, présenté aux clients : description et navigation de la page création de site
    #[
        Route(
            "/creation-site-web/projets/{slug}",
            name: "client_projects_details",
            methods: ["GET"],
        ),
    ]
    public function getClientProjectDetails(
        #[MapEntity(mapping: ["slug" => "slug"])] Project $project,
    ): Response {
        return $this->renderProjectDetails($project, Audience::Client);
    }

    private function renderProjectDetails(
        Project $project,
        Audience $audience,
    ): Response {
        $adjacent = $this->projectRepository->findAdjacent($project, $audience);

        return $this->inertia->render("ProjectDetails", [
            "audience" => $audience->value,
            "project" => $this->presenter->project($project, $audience),
            "previous" => $this->presenter->link(
                $adjacent["previous"],
                $audience,
            ),
            "next" => $this->presenter->link($adjacent["next"], $audience),
            "seo" => $this->seo->project($project, $audience),
        ]);
    }
}
