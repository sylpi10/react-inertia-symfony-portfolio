<?php

namespace App\Tests\EventListener;

use App\Entity\Project;
use App\EventListener\ProjectSlugListener;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\Slugger\AsciiSlugger;

final class ProjectSlugListenerTest extends TestCase
{
    /**
     * @return iterable<string, array{string, string}>
     */
    public static function names(): iterable
    {
        yield "espaces et majuscules" => [
            "Toulouse Sport Club",
            "toulouse-sport-club",
        ];
        yield "apostrophe et underscore" => ["l'exemple_31", "l-exemple-31"];
    }

    #[DataProvider("names")]
    public function testSlugIsBuiltFromNameWhenMissing(
        string $name,
        string $expected,
    ): void {
        $project = new Project()->setName($name);

        $this->listener()->prePersist($project);

        self::assertSame($expected, $project->getSlug());
    }

    // un slug déjà fourni (migration, import…) n'est jamais remplacé
    public function testExistingSlugIsKept(): void
    {
        $project = new Project()->setName("Test")->setSlug("mon-slug");

        $this->listener()->prePersist($project);

        self::assertSame("mon-slug", $project->getSlug());
    }

    private function listener(): ProjectSlugListener
    {
        return new ProjectSlugListener(new AsciiSlugger());
    }
}
