<?php

namespace App\Service;

use App\Entity\Project;
use App\Enum\Audience;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Prop "seo" des pages, lue par base.html.twig : title, meta description,
 * canonical et visuel de partage.
 */
final class PageSeo
{
    // accueil = page équipe (recruteurs, CTO, agences), le cœur de l'activité
    private const string HOME_TITLE = "Sylvain Pillet – Développeur fullstack React / Symfony à Toulouse";
    // 155 caractères max pour ne pas être tronquée
    private const string HOME_DESCRIPTION = "Développeur fullstack React et Symfony à Toulouse : 5 ans en e-commerce, interfaces, API, tests. Disponible pour renforcer votre équipe.";
    private const string CLIENT_TITLE = "Création de site web à Toulouse – Développeur freelance | Sylvain Pillet";
    // l'offre d'abord, l'expérience ensuite
    private const string CLIENT_DESCRIPTION = "Développeur web freelance à Toulouse : sites vitrines avec back-office, refontes, applications React/Next.js pour indépendants et TPE. 5 ans d’expérience.";

    public function __construct(
        private readonly ProjectSummary $summary,
        private readonly ProjectShareImage $shareImage,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {}

    /**
     * Accueil (équipe) ou page création de site (client).
     *
     * @return array{title: string, description: string}
     */
    public function audiencePage(Audience $audience): array
    {
        return Audience::Team === $audience
            ? ["title" => self::HOME_TITLE, "description" => self::HOME_DESCRIPTION]
            : ["title" => self::CLIENT_TITLE, "description" => self::CLIENT_DESCRIPTION];
    }

    /**
     * @return array<string, mixed>
     */
    public function project(Project $project, Audience $audience): array
    {
        $seo = [
            "title" => sprintf(
                "%s – %s | Sylvain Pillet",
                $project->getName(),
                Audience::Team === $audience
                    ? "Projet web"
                    : "Création de site web",
            ),
            "description" => $this->summary->metaDescription(
                $project,
                $audience,
            ),
            "image" => $this->shareImage($project),
        ];
        // sans texte propre, la page client répète celle de l'accueil : on la rattache à celle-ci
        if (
            Audience::Client === $audience &&
            !$project->hasClientDescription()
        ) {
            $seo["canonical"] = $this->urlGenerator->generate(
                "projects_details",
                ["slug" => $project->getSlug()],
                UrlGeneratorInterface::ABSOLUTE_URL,
            );
        }

        return $seo;
    }

    /**
     * Page obligatoire mais sans intérêt dans les résultats de recherche.
     *
     * @return array{title: string, description: string, robots: string}
     */
    public function legalNotice(): array
    {
        return [
            "title" => "Mentions légales | Sylvain Pillet",
            "description" => "Mentions légales du site de Sylvain Pillet, développeur web à Toulouse : éditeur, hébergeur, données personnelles.",
            "robots" => "noindex, follow",
        ];
    }

    /**
     * Formulaire d'avis : lien envoyé aux clients, pas une page à trouver.
     *
     * @return array{title: string, description: string, robots: string}
     */
    public function review(): array
    {
        return [
            "title" => "Laisser un avis | Sylvain Pillet",
            "description" => "Donnez votre avis sur le site ou l'application réalisé avec Sylvain Pillet, développeur web à Toulouse.",
            "robots" => "noindex, nofollow",
        ];
    }

    /**
     * @return array{title: string, description: string, robots: string}
     */
    public function error(int $status): array
    {
        return [
            "title" => sprintf(
                "%s | Sylvain Pillet",
                404 === $status ? "Page introuvable" : "Erreur",
            ),
            "description" => self::HOME_DESCRIPTION,
            "robots" => "noindex, follow",
        ];
    }

    /**
     * @return array{url: string, width: int, height: int, alt: string}|null
     */
    private function shareImage(Project $project): ?array
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
