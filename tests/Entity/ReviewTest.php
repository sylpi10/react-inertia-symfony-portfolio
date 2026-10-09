<?php

namespace App\Tests\Entity;

use App\Entity\Project;
use App\Entity\Review;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ReviewTest extends KernelTestCase
{
    public function testNewReviewIsPendingAndDated(): void
    {
        $review = new Review();

        self::assertFalse($review->isValidated());
        self::assertEqualsWithDelta(time(), $review->getCreatedAt()->getTimestamp(), 5);
    }

    public function testLinkingFromEitherSideUpdatesBoth(): void
    {
        $review = new Review();
        $ava = (new Project())->setName('Ava');
        $labelmaker = (new Project())->setName('Labelmaker');

        // un même avis peut porter sur plusieurs projets
        $review->addProject($ava);
        $labelmaker->setReview($review);

        self::assertCount(2, $review->getProjects());
        self::assertSame($review, $ava->getReview());
        self::assertSame($review, $labelmaker->getReview());

        $ava->setReview(null);

        self::assertFalse($review->getProjects()->contains($ava));
        self::assertNull($ava->getReview());
    }

    public function testAProjectHasOnlyOneReview(): void
    {
        $first = new Review();
        $second = new Review();
        $ava = (new Project())->setName('Ava');

        $first->addProject($ava);
        $second->addProject($ava);

        // rattacher le projet au second avis le retire du premier
        self::assertSame($second, $ava->getReview());
        self::assertCount(0, $first->getProjects());
        self::assertCount(1, $second->getProjects());
    }

    public function testReviewNeedsAuthorTextAndAtLeastOneProject(): void
    {
        $validator = self::getContainer()->get(ValidatorInterface::class);

        $violations = $validator->validate(new Review());
        $fields = array_map(fn ($v) => $v->getPropertyPath(), iterator_to_array($violations));
        self::assertEqualsCanonicalizing(['author', 'text', 'projects'], $fields);

        $review = (new Review())
            ->setAuthor('Marie')
            ->setText('Super travail.')
            ->addProject((new Project())->setName('Ava'));
        self::assertCount(0, $validator->validate($review));
    }
}
