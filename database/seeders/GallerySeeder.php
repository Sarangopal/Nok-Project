<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $galleries = [
            [
                'title' => 'Aaravam Event 2025',
                'description' => 'Annual Aaravam cultural celebration',
                'image' => 'nokw/assets/img/project/aaravam/nokkeve.webp',
                'category' => 'aaravam',
                'year' => 2025,
                'display_order' => 1,
                'is_published' => true,
            ],
            [
                'title' => 'Nightingales Gala 2024 - Event Highlight',
                'description' => 'Annual fundraising gala event',
                'image' => 'nokw/assets/img/project/nightingales2024/nok2024 (6).jpg',
                'category' => 'nightingales2024',
                'year' => 2024,
                'display_order' => 2,
                'is_published' => true,
            ],
            [
                'title' => 'Nightingales Gala 2024 - Cultural Performance',
                'description' => 'Traditional dance performance at the gala',
                'image' => 'nokw/assets/img/project/nightingales2024/nok2024 (7).jpg',
                'category' => 'nightingales2024',
                'year' => 2024,
                'display_order' => 3,
                'is_published' => true,
            ],
            [
                'title' => 'Nightingales Gala 2024 - Audience',
                'description' => 'Guests enjoying the evening',
                'image' => 'nokw/assets/img/project/nightingales2024/nok2024 (8).jpeg',
                'category' => 'nightingales2024',
                'year' => 2024,
                'display_order' => 4,
                'is_published' => true,
            ],
            [
                'title' => 'Nightingales Gala 2024 - Stage Performance',
                'description' => 'Musical performance on stage',
                'image' => 'nokw/assets/img/project/nightingales2024/nok2024 (9).jpeg',
                'category' => 'nightingales2024',
                'year' => 2024,
                'display_order' => 5,
                'is_published' => true,
            ],
            [
                'title' => 'Nightingales Gala 2024 - VIP Guests',
                'description' => 'Special guests at the event',
                'image' => 'nokw/assets/img/project/nightingales2024/nok2024 (10).jpeg',
                'category' => 'nightingales2024',
                'year' => 2024,
                'display_order' => 6,
                'is_published' => true,
            ],
            [
                'title' => 'Nightingales Gala 2024 - Group Photo',
                'description' => 'Memorable group photograph',
                'image' => 'nokw/assets/img/project/nightingales2024/nok2024 (11).jpeg',
                'category' => 'nightingales2024',
                'year' => 2024,
                'display_order' => 7,
                'is_published' => true,
            ],
            [
                'title' => 'Nightingales Gala 2024 - Award Ceremony',
                'description' => 'Recognition and awards presentation',
                'image' => 'nokw/assets/img/project/nightingales2024/nok2024 (12).jpeg',
                'category' => 'nightingales2024',
                'year' => 2024,
                'display_order' => 8,
                'is_published' => true,
            ],
            [
                'title' => 'Nightingales Gala 2024 - Entertainment',
                'description' => 'Live entertainment for guests',
                'image' => 'nokw/assets/img/project/nightingales2024/nok2024 (13).jpeg',
                'category' => 'nightingales2024',
                'year' => 2024,
                'display_order' => 9,
                'is_published' => true,
            ],
            [
                'title' => 'Nightingales Gala 2024 - Celebration',
                'description' => 'Celebrating together',
                'image' => 'nokw/assets/img/project/nightingales2024/nok2024 (14).jpeg',
                'category' => 'nightingales2024',
                'year' => 2024,
                'display_order' => 10,
                'is_published' => true,
            ],
            [
                'title' => 'Nightingales Gala 2024 - Highlights',
                'description' => 'Best moments of the night',
                'image' => 'nokw/assets/img/project/nightingales2024/nok2024 (15).jpeg',
                'category' => 'nightingales2024',
                'year' => 2024,
                'display_order' => 11,
                'is_published' => true,
            ],
            [
                'title' => 'Nightingales Gala 2024 - Finale',
                'description' => 'Grand finale of the event',
                'image' => 'nokw/assets/img/project/nightingales2024/nok2024 (16).jpeg',
                'category' => 'nightingales2024',
                'year' => 2024,
                'display_order' => 12,
                'is_published' => true,
            ],
            [
                'title' => 'Nightingales Gala 2024 - Community',
                'description' => 'Our wonderful community together',
                'image' => 'nokw/assets/img/project/nightingales2024/nok2024 (17).jpeg',
                'category' => 'nightingales2024',
                'year' => 2024,
                'display_order' => 13,
                'is_published' => true,
            ],
            [
                'title' => 'Nightingales Gala 2024 - Unity',
                'description' => 'Unity and celebration',
                'image' => 'nokw/assets/img/project/nightingales2024/nok2024 (18).jpeg',
                'category' => 'nightingales2024',
                'year' => 2024,
                'display_order' => 14,
                'is_published' => true,
            ],
            [
                'title' => 'Nightingales Gala 2024 - Memories',
                'description' => 'Creating lasting memories',
                'image' => 'nokw/assets/img/project/nightingales2024/nok2024 (19).jpeg',
                'category' => 'nightingales2024',
                'year' => 2024,
                'display_order' => 15,
                'is_published' => true,
            ],
            [
                'title' => 'Nightingales Gala 2024 - Success',
                'description' => 'Another successful event',
                'image' => 'nokw/assets/img/project/nightingales2024/nok2024 (20).jpeg',
                'category' => 'nightingales2024',
                'year' => 2024,
                'display_order' => 16,
                'is_published' => true,
            ],
        ];

        foreach ($galleries as $gallery) {
            Gallery::create($gallery);
        }

        $this->command->info('✓ Gallery seeded successfully with ' . count($galleries) . ' items!');
    }
}




