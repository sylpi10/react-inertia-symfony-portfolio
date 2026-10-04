<?php

namespace App\Tests\Service;

use App\Entity\Project;
use App\Service\ProjectSummary;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ProjectSummaryTest extends TestCase
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

        $meta = (new ProjectSummary())->metaDescription($project);

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
            (new ProjectSummary())->metaDescription($project),
        );
    }

    public function testTeaserIsTheFirstSentence(): void
    {
        $project = (new Project())
            ->setName('Ava')
            ->setTechnos('React, Symfony')
            ->setDescription('<p>Application web de jeu de cartes. Mode multijoueurs et mode contre l\'ordinateur.</p>');

        self::assertSame('Application web de jeu de cartes', (new ProjectSummary())->teaser($project));
    }

    public function testTeaserIsShortenedAndFallsBackToTechnos(): void
    {
        $summary = new ProjectSummary();
        $long = (new Project())
            ->setName('Utopix')
            ->setTechnos('Next.js')
            ->setDescription('<p>Site web de la maison-exposition de Jo Pillet sur le causse de Sauveterre, avec galerie et agenda.</p>');
        $empty = (new Project())->setName('Portfolio')->setTechnos('React, Symfony');

        self::assertLessThanOrEqual(60, mb_strlen($summary->teaser($long)));
        self::assertStringEndsWith('…', $summary->teaser($long));
        self::assertSame('React, Symfony', $summary->teaser($empty));
    }

    public function testHtmlBlocksAreSeparatedInPlainText(): void
    {
        $project = (new Project())
            ->setName('La cuisine de Maha')
            ->setTechnos('Symfony')
            ->setDescription('<p>Refonte technique du site.</p><h2>Le contexte</h2><p>Une cheffe à domicile.</p>');

        self::assertStringStartsWith(
            'Refonte technique du site. Le contexte Une cheffe à domicile.',
            (new ProjectSummary())->metaDescription($project),
        );
    }
}
