<?php

namespace App\Tests\Service;

use App\Entity\Project;
use App\Service\ProjectMetaDescription;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ProjectMetaDescriptionTest extends TestCase
{
    /**
     * @return iterable<string, array{?string}>
     */
    public static function descriptions(): iterable
    {
        yield 'vide' => [null];
        yield 'très courte' => ['<p>Portfolio personnel</p>'];
        yield 'courte' => ['<p>Refonte technique du site en Symfony, pour avoir un back-office permettant de gérer le contenu.</p>'];
        yield 'longue' => ['<p>'.str_repeat('Site web de la maison-exposition sur le causse, avec galerie et agenda. ', 5).'</p>'];
    }

    #[DataProvider('descriptions')]
    public function testLengthStaysWithinGoogleLimits(?string $description): void
    {
        $project = (new Project())
            ->setName('Portfolio')
            ->setTechnos('React, Symfony')
            ->setDescription($description);

        $meta = (new ProjectMetaDescription())->forProject($project);

        self::assertGreaterThanOrEqual(120, mb_strlen($meta), $meta);
        self::assertLessThanOrEqual(155, mb_strlen($meta), $meta);
        self::assertStringNotContainsString('<', $meta);
    }

    public function testShortDescriptionIsKeptAndCompleted(): void
    {
        $project = (new Project())
            ->setName('Portfolio')
            ->setTechnos('React, Symfony')
            ->setDescription('<p>Portfolio personnel</p>');

        self::assertStringStartsWith(
            'Portfolio personnel. Projet Portfolio réalisé par Sylvain Pillet',
            (new ProjectMetaDescription())->forProject($project),
        );
    }
}
