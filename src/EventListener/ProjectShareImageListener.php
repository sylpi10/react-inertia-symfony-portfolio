<?php

namespace App\EventListener;

use App\Entity\Project;
use App\Service\ProjectShareImage;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

// visuel de partage régénéré à chaque enregistrement depuis l'admin : la
// capture desktop a pu changer
final class ProjectShareImageListener
{
    public function __construct(private readonly ProjectShareImage $shareImage) {}

    #[AsEventListener]
    public function onPersisted(AfterEntityPersistedEvent $event): void
    {
        $this->generate($event->getEntityInstance());
    }

    #[AsEventListener]
    public function onUpdated(AfterEntityUpdatedEvent $event): void
    {
        $this->generate($event->getEntityInstance());
    }

    private function generate(object $entity): void
    {
        if ($entity instanceof Project) {
            $this->shareImage->generate($entity);
        }
    }
}
