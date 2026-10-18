<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Service\IndexNow;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SeoController extends AbstractController
{
    public function __construct(
        private readonly ProjectRepository $projects,
        private readonly IndexNow $indexNow,
    ) {
    }

    #[Route('/sitemap.xml', name: 'sitemap', format: 'xml')]
    public function sitemap(): Response
    {
        return $this->render('seo/sitemap.xml.twig', [
            'projects' => $this->projects->findAll(),
        ]);
    }

    #[Route('/llms.txt', name: 'llms', format: 'txt')]
    public function llms(): Response
    {
        return $this->render('seo/llms.txt.twig', [
            'projects' => $this->projects->findAll(),
        ]);
    }

    // fichier de clé lu par IndexNow pour vérifier que les soumissions viennent du site
    #[Route('/indexnow.txt', name: 'indexnow_key', format: 'txt')]
    public function indexNowKey(): Response
    {
        return new Response($this->indexNow->getKey(), headers: [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
