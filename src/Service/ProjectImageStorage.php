<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Enregistre les captures uploadées depuis l'admin, toujours en webp : le
 * front et les visuels de partage s'attendent à ce format.
 */
final class ProjectImageStorage
{
    public const string UPLOAD_DIR = 'public/images/projects/';
    public const string BASE_PATH = 'images/projects/';

    private const int QUALITY = 82;

    // signature imposée par l'option "upload_new" d'EasyAdmin
    public function store(UploadedFile $file, string $uploadDir, string $fileName): void
    {
        $path = rtrim($uploadDir, '/').'/'.$fileName;

        if ('image/webp' === $file->getMimeType()) {
            $file->move(\dirname($path), basename($path));
            chmod($path, 0o644);

            return;
        }

        $image = imagecreatefromstring((string) file_get_contents($file->getPathname()));
        if (false === $image) {
            throw new \RuntimeException(sprintf('Image illisible : %s', $file->getClientOriginalName()));
        }

        // conserve la transparence des PNG
        imagepalettetotruecolor($image);
        imagealphablending($image, false);
        imagesavealpha($image, true);

        imagewebp($image, $path, self::QUALITY);
        chmod($path, 0o644);
    }
}
