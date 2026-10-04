<?php

namespace App\Command;

use App\Repository\ProjectRepository;
use App\Service\ProjectShareImage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:project:share-images',
    description: 'Génère les visuels de partage (og:image) des projets',
)]
final class ProjectShareImagesCommand
{
    public function __construct(
        private readonly ProjectRepository $projects,
        private readonly ProjectShareImage $shareImage,
    ) {}

    public function __invoke(
        SymfonyStyle $io,
        #[Option('Régénère aussi les visuels existants')] bool $force = false,
    ): int {
        $missing = [];
        foreach ($this->projects->findAll() as $project) {
            if (!$force && null !== $this->shareImage->publicPath($project)) {
                continue;
            }
            if ($this->shareImage->generate($project)) {
                $io->writeln(sprintf('  %s : %s', $project->getName(), $this->shareImage->publicPath($project)));
            } else {
                $missing[] = $project->getName();
            }
        }

        if ($missing) {
            $io->warning('Capture introuvable pour : '.implode(', ', $missing));

            return Command::FAILURE;
        }

        $io->success('Visuels de partage à jour.');

        return Command::SUCCESS;
    }
}
