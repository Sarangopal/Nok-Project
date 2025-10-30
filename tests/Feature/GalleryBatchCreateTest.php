<?php

namespace Tests\Feature;

use App\Filament\Resources\Gallery\GalleryResource\Pages\CreateGallery;
use App\Models\Gallery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Orchestra\Testbench\TestCase;
use ReflectionClass;

class GalleryBatchCreateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Use sqlite in-memory for tests
        Config::set('database.default', 'sqlite');
        Config::set('database.connections.sqlite.database', ':memory:');

        Artisan::call('migrate');
    }

    public function test_creates_multiple_records_for_multiple_images(): void
    {
        Storage::fake('public');

        $img1 = $this->makeImage('gallery/a.jpg');
        $img2 = $this->makeImage('gallery/b.jpg');

        $data = [
            'title' => 'Event',
            'description' => 'Batch upload',
            'category' => 'others',
            'year' => (int) date('Y'),
            'display_order' => 0,
            'is_published' => true,
            'images' => [$img1, $img2],
        ];

        // Call protected handleRecordCreation via reflection
        $page = app(CreateGallery::class);
        $ref = new ReflectionClass($page);
        $method = $ref->getMethod('handleRecordCreation');
        $method->setAccessible(true);
        $method->invoke($page, $data);

        $this->assertDatabaseCount((new Gallery())->getTable(), 2);
    }

    private function makeImage(string $relative): string
    {
        $absolute = Storage::disk('public')->path($relative);
        @mkdir(dirname($absolute), 0777, true);
        $manager = new ImageManager(new Driver());
        $img = $manager->create(2200, 1400)->fill('#aaaaaa');
        $img->encodeByExtension('jpg', quality: 90)->save($absolute);
        return $relative;
    }
}


