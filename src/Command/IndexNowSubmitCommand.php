<?php

namespace App\Command;

use App\Repository\ProjectRepository;
use App\Service\IndexNow;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsCommand(
    name: 'app:indexnow:submit',
    description: 'Soumet les URLs du sitemap à IndexNow',
)]
final class IndexNowSubmitCommand
{
    public function __construct(
        private readonly IndexNow $indexNow,
        private readonly ProjectRepository $projects,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {}

    public function __invoke(SymfonyStyle $io): int
    {
        $urls = [
            $this->urlGenerator->generate('home', [], UrlGeneratorInterface::ABSOLUTE_URL),
            $this->urlGenerator->generate('client', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ];
        foreach ($this->projects->findAll() as $project) {
            $urls[] = $this->urlGenerator->generate('projects_details', ['slug' => $project->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);
            // mêmes règles que le sitemap : version client seulement avec son propre texte
            if ($project->hasClientDescription()) {
                $urls[] = $this->urlGenerator->generate('client_projects_details', ['slug' => $project->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);
            }
        }

        $status = $this->indexNow->submit($urls);
        if (!\in_array($status, [200, 202], true)) {
            $io->error(sprintf('IndexNow a refusé la soumission (HTTP %d).', $status));

            return Command::FAILURE;
        }

        $io->listing($urls);
        $io->success(sprintf('%d URLs soumises à IndexNow (HTTP %d).', \count($urls), $status));

        return Command::SUCCESS;
    }
}
