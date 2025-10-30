<?php

namespace App\Services;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageOptimizer
{
    /**
     * Optimize an image file in-place: resize if overly large and recompress.
     */
    public static function optimize(string $absolutePath): void
    {
        $manager = new ImageManager(new Driver());

        $image = $manager->read($absolutePath);

        // Resize if width is greater than 2000px, keep aspect ratio
        if ($image->width() > 2000) {
            $image->scaleDown(width: 2000);
        }

        // Re-encode according to source type with sensible quality
        $extension = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));

        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $image->encodeByExtension('jpg', quality: 82);
                break;
            case 'png':
                $image->encodeByExtension('png', quality: 8); // compression level 0-9
                break;
            case 'webp':
                $image->encodeByExtension('webp', quality: 80);
                break;
            default:
                // Leave other formats as-is
                break;
        }

        $image->save($absolutePath);
    }
}


