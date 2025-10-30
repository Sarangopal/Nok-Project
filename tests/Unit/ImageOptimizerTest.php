<?php

namespace Tests\Unit;

use App\Services\ImageOptimizer;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use PHPUnit\Framework\TestCase;

class ImageOptimizerTest extends TestCase
{
    public function test_optimizer_resizes_and_saves(): void
    {
        Storage::fake('public');

        $relative = 'gallery/test-big.jpg';
        $absolute = Storage::disk('public')->path($relative);

        // Ensure directory exists
        @mkdir(dirname($absolute), 0777, true);

        // Create a large image (3000x2000)
        $manager = new ImageManager(new Driver());
        $img = $manager->create(3000, 2000)->fill('#cccccc');
        $img->encodeByExtension('jpg', quality: 95)->save($absolute);

        $beforeSize = filesize($absolute);

        ImageOptimizer::optimize($absolute);

        $optimized = $manager->read($absolute);

        $this->assertLessThanOrEqual(2000, $optimized->width());
        $this->assertFileExists($absolute);
        $this->assertTrue(filesize($absolute) <= $beforeSize);
    }
}


