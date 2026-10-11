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
        $labelmaker->addReview($review);

        self::assertCount(2, $review->getProjects());
        self::assertTrue($ava->getReviews()->contains($review));
        self::assertTrue($labelmaker->getReviews()->contains($review));

        $ava->removeReview($review);

        self::assertFalse($review->getProjects()->contains($ava));
        self::assertCount(0, $ava->getReviews());
    }

    public function testAProjectCanHaveSeveralReviews(): void
    {
        $first = new Review();
        $second = new Review();
        $ava = (new Project())->setName('Ava');

        $first->addProject($ava);
        $second->addProject($ava);

        // le second avis s'ajoute au premier, il ne le remplace pas
        self::assertSame([$first, $second], $ava->getReviews()->toArray());
        self::assertCount(1, $first->getProjects());
        self::assertCount(1, $second->getProjects());
    }

    public function testReviewNeedsAuthorAndTextButNoProject(): void
    {
        $validator = self::getContainer()->get(ValidatorInterface::class);

        $violations = $validator->validate(new Review());
        $fields = array_map(fn ($v) => $v->getPropertyPath(), iterator_to_array($violations));
        // le projet est facultatif : avis sur le travail en général
        self::assertEqualsCanonicalizing(['author', 'text'], $fields);

        $review = (new Review())
            ->setAuthor('Marie')
            ->setText('Super travail.')
            ->addProject((new Project())->setName('Ava'));
        self::assertCount(0, $validator->validate($review));
    }

    public function testGoogleRatingAndLinkAreOptionalButChecked(): void
    {
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $review = (new Review())
            ->setAuthor('Marie')
            ->setText('Super travail.')
            ->addProject((new Project())->setName('Ava'));

        // avis du formulaire, sans équivalent Google
        self::assertCount(0, $validator->validate($review));
        self::assertNull($review->getGoogleRating());

        $review->setGoogleRating(5)->setGoogleReviewUrl('https://share.google/abc');
        self::assertCount(0, $validator->validate($review));

        $review->setGoogleRating(6)->setGoogleReviewUrl('pas un lien');
        $fields = array_map(fn ($v) => $v->getPropertyPath(), iterator_to_array($validator->validate($review)));
        self::assertEqualsCanonicalizing(['googleRating', 'googleReviewUrl'], $fields);
    }
}
