<?php

namespace App\EventListener;

use Nytodev\InertiaBundle\Service\Inertia;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Props envoyées à toutes les pages Inertia (usePage().props côté front).
 */
#[AsEventListener(event: KernelEvents::REQUEST)]
final class InertiaShareListener
{
    public function __construct(
        private readonly Inertia $inertia,
        #[Autowire(env: 'PUBLIC_EMAIL')]
        private readonly string $publicEmail,
    ) {
    }

    public function __invoke(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $this->inertia->share('publicEmail', $this->publicEmail);
    }
}
