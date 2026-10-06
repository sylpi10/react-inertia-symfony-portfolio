<?php

namespace App\Controller;

use App\Service\PageSeo;
use Nytodev\InertiaBundle\Service\Inertia;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LegalController extends AbstractController
{
    public function __construct(
        private readonly Inertia $inertia,
        private readonly PageSeo $seo,
    ) {}

    // contenu statique, dans assets/pages/LegalNotice.tsx
    #[Route("/mentions-legales", name: "legal", methods: ["GET"])]
    public function legalNotice(): Response
    {
        return $this->inertia->render("LegalNotice", [
            "seo" => $this->seo->legalNotice(),
        ]);
    }
}
