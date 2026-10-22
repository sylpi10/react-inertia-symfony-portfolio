<?php

namespace App\Tests\Service;

use App\Entity\Project;
use App\Entity\Review;
use App\Enum\Audience;
use App\Service\PagePresenter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use function PHPUnit\Framework\assertSame;

final class PagePresenterTest extends KernelTestCase
{
    public function project(): Project
    {
        $project = new Project();
        $project->setName('Labelmaker')->setSlug('Labelmaker');
        $review = new Review()->setText('validé')
        ->setAuthor('Zizou')
        ->setValidated(true);
        $project->addReview($review);
        $review2 = new Review()->setText('non validé')
        ->setAuthor('John Lennon')->setValidated(false);
        $project->addReview($review2);

        return $project;
    }

    public function testProjectOnlySendsValidatedReviews(): void
    {
        $project = $this->project();
        $presenter = static::getContainer()->get(PagePresenter::class);
        $data = $presenter->project($project, Audience::Client);
        self::assertSame(['validé'], array_column($data['reviews'], 'text'));
    }

    public function testProjectReviewsAreNewestFirst(): void
    {
        $project = $this->project();
        $project->getReviews()[0]->setCreatedAt(new \DateTimeImmutable('2026-01-10'));
        $project->getReviews()[1]->setCreatedAt(new \DateTimeImmutable('2026-02-10'))
        ->setValidated(true);
        $presenter = static::getContainer()->get(PagePresenter::class);
        $data = $presenter->project($project, Audience::Client);
        self:assertSame(['non validé', 'validé'],
            array_column($data['reviews'], 'text'));
    }
}
