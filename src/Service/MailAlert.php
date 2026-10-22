<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Notifier\Bridge\Ntfy\NtfyOptions;
use Symfony\Component\Notifier\Message\PushMessage;
use Symfony\Component\Notifier\TexterInterface;

/**
 * Alerte push (ntfy) à chaque mail du site : envoyé, ou pas envoyé (une alerte par mail
 * serait perdue avec lui). Pas de données du visiteur dans l'alerte, elle transite
 * par un service tiers : juste de quoi retrouver l'élément dans l'admin.
 */
final class MailAlert
{
    public function __construct(
        private readonly TexterInterface $texter,
        private readonly LoggerInterface $logger,
    ) {
    }

    // $subject : "Message de contact n°12", "Avis n°5 à valider"…
    public function sent(string $subject): void
    {
        $this->push(
            'Portfolio : nouveau mail',
            $subject.' : mail envoyé.',
            new NtfyOptions()->addTag('email'),
        );
    }

    public function failed(string $subject, \Throwable $exception): void
    {
        $this->push(
            'Portfolio : mail non envoyé',
            \sprintf(
                "%s : enregistré mais mail pas envoyé (consultable dans l'admin).\n%s",
                $subject,
                mb_substr($exception->getMessage(), 0, 300),
            ),
            new NtfyOptions()
                ->setPriority(NtfyOptions::PRIORITY_HIGH)
                ->addTag('warning'),
        );
    }

    // une alerte ratée ne doit pas faire échouer la requête du visiteur
    private function push(string $title, string $body, NtfyOptions $options): void
    {
        try {
            $this->texter->send(new PushMessage($title, $body, $options));
        } catch (\Throwable $e) {
            $this->logger->error('Alerte ntfy impossible', ['exception' => $e]);
        }
    }
}
