<?php

namespace App\EventListener;

use App\Service\PageSeo;
use Nytodev\InertiaBundle\Service\Inertia;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

// page Error du site, avec le vrai code HTTP (404 pour un projet introuvable) :
// après la sécurité (1) et le log de l'erreur (0), avant la page d'erreur Symfony (-128)
#[AsEventListener(event: KernelEvents::EXCEPTION, priority: -64)]
final class InertiaErrorListener
{
    public function __construct(
        private readonly Inertia $inertia,
        private readonly PageSeo $seo,
        #[Autowire("%kernel.debug%")]
        private readonly bool $debug,
    ) {}

    public function __invoke(ExceptionEvent $event): void
    {
        $request = $event->getRequest();
        // back-office (EasyAdmin) et fichiers sitemap.xml, llms.txt… : erreurs Symfony
        if (
            !$event->isMainRequest() ||
            "html" !== $request->getRequestFormat() ||
            str_starts_with($request->getPathInfo(), "/admin")
        ) {
            return;
        }

        $exception = $event->getThrowable();
        $status =
            $exception instanceof HttpExceptionInterface
                ? $exception->getStatusCode()
                : 500;
        // en dev, garder la page de debug de Symfony pour les vraies erreurs
        if ($this->debug && $status >= 500) {
            return;
        }

        $response = $this->inertia->render("Error", [
            "status" => $status,
            "seo" => $this->seo->error($status),
        ]);
        $response->setStatusCode($status);
        $event->setResponse($response);
        $event->allowCustomResponseCode();
    }
}
