<?php

namespace App\Tests\Entity;

use App\Entity\Experience;
use App\Entity\Project;
use PHPUnit\Framework\TestCase;

final class ExperienceProjectRelationTest extends TestCase
{
    public function testLinkingFromEitherSideUpdatesBoth(): void
    {
        $freelance = new Experience();
        $ludilabel = new Experience();
        $project = (new Project())->setName('Labelmaker');

        // projet lancé pendant une étape, repris pendant une autre
        $freelance->addProject($project);
        $project->addExperience($ludilabel);

        self::assertTrue($freelance->getProjects()->contains($project));
        self::assertTrue($ludilabel->getProjects()->contains($project));
        self::assertCount(2, $project->getExperiences());
    }

    public function testUnlinkingFromTheInverseSideUpdatesTheOwningSide(): void
    {
        $experience = new Experience();
        $project = (new Project())->setName('Ava');
        $experience->addProject($project);

        $project->removeExperience($experience);

        self::assertCount(0, $experience->getProjects());
        self::assertCount(0, $project->getExperiences());
    }
}
