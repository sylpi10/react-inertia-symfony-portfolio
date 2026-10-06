<?php

namespace App\Service;

use App\Entity\Project;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Visuel de partage (og:image) d'un projet : le haut de sa capture d'écran,
 * recadré au format 1,91:1 attendu par les réseaux sociaux. Le même cadrage,
 * en webp, sert de vignette aux cartes projet.
 */
final class ProjectShareImage
{
    public const int WIDTH = 1200;
    public const int HEIGHT = 630;

    private const string SOURCE_DIR = '/images/projects';
    private const string TARGET_DIR = '/images/projects/og';
    private const string THUMBNAIL_DIR = '/images/projects/thumbs';

    public function __construct(
        #[Autowire('%kernel.project_dir%/public')]
        private readonly string $publicDir,
    ) {}

    /**
     * Chemin public du visuel, ou null s'il n'a pas encore été généré.
     */
    public function publicPath(Project $project): ?string
    {
        $path = $this->targetPath($project);

        return is_file($this->publicDir.$path) ? $path : null;
    }

    /**
     * Chemin public de la vignette des cartes projet, ou null si elle n'a pas encore été générée.
     */
    public function thumbnailPath(Project $project): ?string
    {
        $path = $this->thumbnailTargetPath($project);

        return is_file($this->publicDir.$path) ? $path : null;
    }

    /**
     * Génère le visuel et la vignette depuis la capture du projet.
     *
     * @return bool false si la capture source est introuvable
     */
    public function generate(Project $project): bool
    {
        // les noms en base peuvent être en .jpg alors que les fichiers sont en .webp
        $source = $this->publicDir.self::SOURCE_DIR.'/'.preg_replace('/\.(jpe?g|png)$/i', '.webp', (string) $project->getDetailPic());
        if (!is_file($source) || false === $image = imagecreatefromwebp($source)) {
            return false;
        }

        // bande du haut de la capture (en-tête du site) au bon ratio
        $width = imagesx($image);
        $height = min(imagesy($image), (int) round($width * self::HEIGHT / self::WIDTH));

        $target = imagecreatetruecolor(self::WIDTH, self::HEIGHT);
        imagecopyresampled($target, $image, 0, 0, 0, 0, self::WIDTH, self::HEIGHT, $width, $height);

        // jpeg pour les réseaux sociaux (webp mal lu par certains), webp pour le site
        return $this->save($target, $this->targetPath($project), fn (string $path): bool => imagejpeg($target, $path, 82))
            && $this->save($target, $this->thumbnailTargetPath($project), fn (string $path): bool => imagewebp($target, $path, 82));
    }

    /**
     * @param callable(string): bool $write
     */
    private function save(\GdImage $image, string $publicPath, callable $write): bool
    {
        $path = $this->publicDir.$publicPath;
        if (!is_dir(\dirname($path))) {
            mkdir(\dirname($path), 0o755, true);
        }

        return $write($path) && chmod($path, 0o644);
    }

    private function targetPath(Project $project): string
    {
        return self::TARGET_DIR.'/'.$project->getSlug().'.jpg';
    }

    private function thumbnailTargetPath(Project $project): string
    {
        return self::THUMBNAIL_DIR.'/'.$project->getSlug().'.webp';
    }
}
