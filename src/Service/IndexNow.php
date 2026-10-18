<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Notifie les moteurs compatibles IndexNow (Bing, Yandex, Seznam…) que des
 * pages ont changé, plutôt que d'attendre leur prochain passage.
 */
final class IndexNow
{
    private const string ENDPOINT = 'https://api.indexnow.org/indexnow';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly UrlGeneratorInterface $urlGenerator,
        #[Autowire('%env(INDEXNOW_KEY)%')]
        private readonly string $key,
    ) {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param list<string> $urls URLs absolues, toutes sur le même hôte
     *
     * @return int code HTTP renvoyé par IndexNow (200 et 202 = accepté)
     */
    public function submit(array $urls): int
    {
        $keyLocation = $this->urlGenerator->generate('indexnow_key', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $response = $this->httpClient->request('POST', self::ENDPOINT, [
            'json' => [
                'host' => parse_url($keyLocation, \PHP_URL_HOST),
                'key' => $this->key,
                'keyLocation' => $keyLocation,
                'urlList' => $urls,
            ],
        ]);

        return $response->getStatusCode();
    }
}
