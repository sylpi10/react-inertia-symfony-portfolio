<?php

namespace App\Controller;

use App\Dto\ReviewRequest;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Service\PageSeo;
use App\Service\ReviewSubmission;
use Nytodev\InertiaBundle\Service\Inertia;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\RateLimiter\RateLimiterFactoryInterface;
use Symfony\Component\Routing\Attribute\Route;

// page à part, dont le lien est envoyé aux clients en fin de projet
class ReviewController extends AbstractController
{
    public function __construct(
        private readonly Inertia $inertia,
        private readonly PageSeo $seo,
    ) {}

    #[Route("/avis", name: "review", methods: ["GET"])]
    public function form(ProjectRepository $projects): Response
    {
        return $this->inertia->render("Review", [
            "seo" => $this->seo->review(),
            // un projet n'a qu'un avis : seuls ceux qui n'en ont pas sont proposés
            "projects" => array_map(
                fn (Project $project) => ["id" => $project->getId(), "name" => $project->getName()],
                $projects->findWithoutReview(),
            ),
        ]);
    }

    #[Route("/avis", name: "review_submit", methods: ["POST"])]
    public function submit(
        #[MapRequestPayload] ReviewRequest $data,
        Request $request,
        ReviewSubmission $submission,
        #[Target("review_form")] RateLimiterFactoryInterface $limiter,
    ): Response {
        if (!$limiter->create($request->getClientIp())->consume()->isAccepted()) {
            $this->inertia->flash("error", "Trop d'envois, réessayez dans une heure.");

            return $this->redirectToRoute("review");
        }

        // honeypot rempli = robot : même réponse qu'un succès, sans rien faire
        if ("" === $data->website) {
            $submission->submit($data);
        }
        $this->inertia->flash("success", "Merci pour votre avis ! Il sera publié après relecture.");

        return $this->redirectToRoute("review");
    }
}
