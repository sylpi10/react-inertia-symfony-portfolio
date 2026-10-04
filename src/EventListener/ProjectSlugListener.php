<?php

namespace App\EventListener;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\String\Slugger\SluggerInterface;

// les projets existants ont reçu leur slug par migration ; les nouveaux le
// reçoivent ici, sauf s'il a été choisi à la main
#[AsEntityListener(event: Events::prePersist, entity: Project::class)]
final class ProjectSlugListener
{
    public function __construct(private readonly SluggerInterface $slugger) {}

    public function prePersist(Project $project): void
    {
        if (null === $project->getSlug()) {
            $project->setSlug($this->slugger->slug($project->getName())->lower()->toString());
        }
    }
}
