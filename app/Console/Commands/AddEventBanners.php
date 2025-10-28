<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;

class AddEventBanners extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:add-banners';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add sample banner images to events without banners';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Adding banner images to events...');
        $this->newLine();

        // Define banner images for each category
        $bannerImages = [
            'Cultural' => 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=1200&h=800&fit=crop&q=80',
            'Celebration' => 'https://images.unsplash.com/photo-1530103862676-de8c9debad1d?w=1200&h=800&fit=crop&q=80',
            'Health & Wellness' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1200&h=800&fit=crop&q=80',
            'Professional Development' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=1200&h=800&fit=crop&q=80',
            'Family' => 'https://images.unsplash.com/photo-1511895426328-dc8714191300?w=1200&h=800&fit=crop&q=80',
            'Training' => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=1200&h=800&fit=crop&q=80',
            'Community Service' => 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=1200&h=800&fit=crop&q=80',
        ];

        $events = Event::whereNull('banner_image')->orWhere('banner_image', '')->get();

        if ($events->isEmpty()) {
            $this->info('No events found without banner images.');
            return Command::SUCCESS;
        }

        $this->info("Found {$events->count()} events without banner images.");
        $this->newLine();

        foreach ($events as $event) {
            $this->line("Processing: {$event->title}");
            
            // Get banner image URL based on category
            $imageUrl = $bannerImages[$event->category] ?? 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1200&h=800&fit=crop&q=80';
            
            try {
                // Download image
                $imageContent = @file_get_contents($imageUrl);
                
                if ($imageContent === false) {
                    $this->error("  ❌ Failed to download image");
                    continue;
                }
                
                // Generate unique filename
                $filename = 'event-banner-' . $event->id . '-' . time() . '.jpg';
                
                // Save to storage/app/public/events/
                Storage::disk('public')->put('events/' . $filename, $imageContent);
                
                // Update event with banner image path
                $event->update([
                    'banner_image' => 'events/' . $filename
                ]);
                
                $this->info("  ✅ SUCCESS (Category: {$event->category})");
                
            } catch (\Exception $e) {
                $this->error("  ❌ ERROR: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info('Done! All events now have banner images.');
        $this->info('Banner images are stored in: storage/app/public/events/');

        return Command::SUCCESS;
    }
}
