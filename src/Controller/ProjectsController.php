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

// les erreurs (projet introuvable…) sont rendues par InertiaErrorListener
class ProjectsController extends AbstractController
{
    public function __construct(
        private readonly Inertia $inertia,
        private readonly ProjectRepository $projectRepository,
        private readonly PagePresenter $presenter,
        private readonly PageSeo $seo,
    ) {}

    // accueil = page équipe (recruteurs, CTO, agences)
    #[Route("/", name: "home", methods: ["GET"])]
    public function getHome(): Response
    {
        return $this->renderAudiencePage(Audience::Team);
    }

    // même portfolio, présenté aux indépendants et TPE qui cherchent un site
    #[Route("/creation-site-web", name: "client", methods: ["GET"])]
    public function getClientPage(): Response
    {
        return $this->renderAudiencePage(Audience::Client, [
            "offers" => $this->presenter->offers(),
        ]);
    }

    // ancienne URL par id, conservée pour les liens et l'index existants
    #[
        Route(
            "/project/{id}",
            name: "projects_details_legacy",
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

    /**
     * Page d'accueil d'un public : ses projets, le parcours et les props propres à la page.
     *
     * @param array<string, mixed> $props
     */
    private function renderAudiencePage(
        Audience $audience,
        array $props = [],
    ): Response {
        return $this->inertia->render(
            Audience::Team === $audience ? "Team" : "Client",
            [
                "audience" => $audience->value,
                "projects" => $this->presenter->projects($audience),
                "experiences" => $this->presenter->experiences($audience),
                "seo" => $this->seo->audiencePage($audience),
                ...$props,
            ],
        );
    }

    private function renderProjectDetails(
        Project $project,
        Audience $audience,
    ): Response {
        $adjacent = $this->projectRepository->findAdjacent(
            $project,
            $audience,
        );

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
