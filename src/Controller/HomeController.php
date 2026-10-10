<?php

namespace App\Controller;

use App\Enum\Audience;
use App\Service\PagePresenter;
use App\Service\PageSeo;
use Nytodev\InertiaBundle\Service\Inertia;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// pages d'accueil du site, une par public ; les pages projet sont dans ProjectsController
class HomeController extends AbstractController
{
    public function __construct(
        private readonly Inertia $inertia,
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

    /**
     * Page d'accueil d'un public : ses projets, le parcours, les avis et les props propres à la page.
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
                "reviews" => $this->presenter->reviews(),
                "seo" => $this->seo->audiencePage($audience),
                ...$props,
            ],
        );
    }
}
