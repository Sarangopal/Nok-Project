<?php

namespace App\Console\Commands;

use App\Models\Offer;
use App\Models\Registration;
use Illuminate\Console\Command;

class AssignTestOffer extends Command
{
    protected $signature = 'test:assign-offer {email}';
    protected $description = 'Create a test offer and assign it to a member by email';

    public function handle(): int
    {
        $email = $this->argument('email');
        
        $member = Registration::where('email', $email)->first();
        
        if (!$member) {
            $this->error("❌ No member found with email: {$email}");
            return self::FAILURE;
        }

        $this->info("Found member: {$member->memberName} ({$member->email})");
        
        if ($member->renewal_status !== 'approved') {
            $this->warn("⚠️  Member is not approved. Status: {$member->renewal_status}");
            $this->warn("Only approved members can see offers in their dashboard.");
        }

        // Create or get test offer
        $offer = Offer::firstOrCreate(
            ['title' => '10% Discount at XYZ Store'],
            [
                'body' => 'Get 10% off on all products at XYZ Store. Show your NOK membership card.',
                'promo_code' => 'NOK10',
                'active' => true,
                'starts_at' => now(),
                'ends_at' => now()->addMonths(3),
            ]
        );

        // Assign offer to member
        if (!$offer->registrations()->where('registration_id', $member->id)->exists()) {
            $offer->registrations()->attach($member->id);
            $this->info("✅ Offer assigned to {$member->memberName}");
        } else {
            $this->info("ℹ️  Offer already assigned to this member");
        }

        $this->newLine();
        $this->info("📊 Offer Details:");
        $this->line("  Title: {$offer->title}");
        $this->line("  Promo Code: {$offer->promo_code}");
        $this->line("  Active: " . ($offer->active ? 'Yes' : 'No'));
        $this->line("  Assigned to: " . $offer->registrations()->count() . " members");

        $this->newLine();
        $this->info("✅ Done! Member should now see this offer in their dashboard.");
        $this->info("   Login at: http://127.0.0.1:8000/member/login");

        return self::SUCCESS;
    }
}

