<?php

namespace App\Controller;

use App\Entity\Project;
use App\Enum\Audience;
use App\Repository\ExperienceRepository;
use App\Repository\OfferRepository;
use App\Repository\ProjectRepository;
use App\Service\ProjectShareImage;
use App\Service\ProjectSummary;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Response;
use Nytodev\InertiaBundle\Service\Inertia;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProjectsController extends AbstractController
{
    // accueil = page équipe (recruteurs, CTO, agences), le cœur de l'activité
    private const string HOME_TITLE = "Sylvain Pillet – Développeur fullstack React / Symfony à Toulouse";
    // 155 caractères max pour ne pas être tronquée
    private const string HOME_DESCRIPTION = "Développeur fullstack React et Symfony à Toulouse : 5 ans en e-commerce, interfaces, API, tests. Disponible pour renforcer votre équipe.";
    private const string CLIENT_TITLE = "Création de site web à Toulouse – Développeur freelance | Sylvain Pillet";
    // l'offre d'abord, l'expérience ensuite
    private const string CLIENT_DESCRIPTION = "Développeur web freelance à Toulouse : sites vitrines avec back-office, refontes, applications React/Next.js pour indépendants et TPE. 5 ans d’expérience.";
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
        private readonly ExperienceRepository $experienceRepository,
        private readonly OfferRepository $offerRepository,
        private readonly NormalizerInterface $normalizer,
    ) {}

    #[Route("/", name: "home", methods: ["GET"])]
    public function getHome(): Response
    {
        try {
            return $this->renderAudiencePage(Audience::Team, [
                "seo" => [
                    "title" => self::HOME_TITLE,
                    "description" => self::HOME_DESCRIPTION,
                ],
            ]);
        } catch (\Throwable $e) {
            return $this->renderError($e);
        }
    }

    // même portfolio, présenté aux indépendants et TPE qui cherchent un site
    #[Route("/creation-site-web", name: "client", methods: ["GET"])]
    public function getClientPage(): Response
    {
        try {
            // normalisés à part : les projets cités n'exposent que nom et slug
            $offers = $this->normalizer->normalize(
                $this->offerRepository->findForHome(),
                context: ["groups" => ["offer:list"]],
            );

            return $this->renderAudiencePage(Audience::Client, [
                "offers" => $offers,
                "seo" => [
                    "title" => self::CLIENT_TITLE,
                    "description" => self::CLIENT_DESCRIPTION,
                ],
            ]);
        } catch (\Throwable $e) {
            return $this->renderError($e);
        }
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

    private function renderProjectDetails(Project $project, Audience $audience): Response
    {
        try {
            $adjacent = $this->projectRepository->findAdjacent($project, $audience);
            $data = $this->normalizer->normalize(
                $project,
                context: ["groups" => ["project:detail"]],
            );
            $data["description"] = $project->getDescriptionFor($audience);

            $seo = [
                "title" => sprintf(
                    "%s – %s | Sylvain Pillet",
                    $project->getName(),
                    Audience::Team === $audience ? "Projet web" : "Création de site web",
                ),
                "description" => $this->summary->metaDescription($project, $audience),
                "image" => $this->shareImageSeo($project),
            ];
            // sans texte propre, la page client répète celle de l'accueil : on la rattache à celle-ci
            if (Audience::Client === $audience && !$project->hasClientDescription()) {
                $seo["canonical"] = $this->generateUrl(
                    "projects_details",
                    ["slug" => $project->getSlug()],
                    UrlGeneratorInterface::ABSOLUTE_URL,
                );
            }

            return $this->inertia->render("ProjectDetails", [
                "audience" => $audience->value,
                "project" => $data,
                "previous" => $this->projectLink($adjacent["previous"], $audience),
                "next" => $this->projectLink($adjacent["next"], $audience),
                "seo" => $seo,
            ]);
        } catch (\Throwable $e) {
            return $this->renderError($e);
        }
    }

    /**
     * Page d'accueil d'un public : ses projets, le parcours et les props propres à la page.
     *
     * @param array<string, mixed> $props
     */
    private function renderAudiencePage(Audience $audience, array $props): Response
    {
        $timeline = $this->experienceRepository->findForTimeline();
        $experiences = $this->normalizer->normalize(
            $timeline,
            context: ["groups" => ["experience:list"]],
        );
        // missions décrites pour le public de la page
        foreach ($timeline as $i => $experience) {
            $experiences[$i]["description"] = $experience->getDescriptionFor($audience);
        }

        return $this->inertia->render(
            Audience::Team === $audience ? "Team" : "Client",
            [
                "audience" => $audience->value,
                "projects" => $this->projectRepository->findOrderedFor($audience),
                "experiences" => $experiences,
                ...$props,
            ],
            ["groups" => ["project:list"]],
        );
    }

    private function renderError(\Throwable $e): Response
    {
        error_log("[API PROJECTS ERROR] " . $e->getMessage());

        return $this->inertia->render("Error", [
            "message" => "La page n'existe pas",
            "seo" => self::ERROR_SEO,
        ]);
    }

    /**
     * @return array{slug: string, name: string, teaser: string, background: ?string}|null
     */
    private function projectLink(?Project $project, Audience $audience): ?array
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
