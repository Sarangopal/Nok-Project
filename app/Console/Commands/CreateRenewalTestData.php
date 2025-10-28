<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Registration;

class CreateRenewalTestData extends Command
{
    protected $signature = 'renewals:create-test-data';
    protected $description = 'Create dummy test data for the Renewals page';

    public function handle(): int
    {
        $this->info('=== CREATING TEST DATA FOR RENEWALS PAGE ===');
        $this->newLine();

        // Get approved members
        $approvedMembers = Registration::where(function($query) {
            $query->where('login_status', 'approved')
                  ->orWhere('renewal_status', 'approved');
        })->limit(4)->get();

        if ($approvedMembers->count() < 2) {
            $this->error('Not enough approved members to create test data');
            return self::FAILURE;
        }

        $this->line('Creating test scenarios:');
        $this->newLine();

        // Scenario 1: Card expired 5 days ago
        $member1 = $approvedMembers[0];
        $member1->card_valid_until = now()->subDays(5);
        $member1->save();
        $this->line("1️⃣ {$member1->memberName}");
        $this->line("   Card Expired: " . $member1->card_valid_until->format('M d, Y') . " (5 days ago)");
        $this->line("   Status: ❌ EXPIRED");
        $this->newLine();

        // Scenario 2: Card expiring in 3 days
        $member2 = $approvedMembers[1];
        $member2->card_valid_until = now()->addDays(3);
        $member2->save();
        $this->line("2️⃣ {$member2->memberName}");
        $this->line("   Card Expires: " . $member2->card_valid_until->format('M d, Y') . " (3 days)");
        $this->line("   Status: 🔴 CRITICAL - Expiring very soon");
        $this->newLine();

        // Scenario 3: Card expiring in 15 days
        if (isset($approvedMembers[2])) {
            $member3 = $approvedMembers[2];
            $member3->card_valid_until = now()->addDays(15);
            $member3->save();
            $this->line("3️⃣ {$member3->memberName}");
            $this->line("   Card Expires: " . $member3->card_valid_until->format('M d, Y') . " (15 days)");
            $this->line("   Status: 🟠 WARNING - Needs attention");
            $this->newLine();
        }

        // Scenario 4: Card expiring in 28 days
        if (isset($approvedMembers[3])) {
            $member4 = $approvedMembers[3];
            $member4->card_valid_until = now()->addDays(28);
            $member4->save();
            $this->line("4️⃣ {$member4->memberName}");
            $this->line("   Card Expires: " . $member4->card_valid_until->format('M d, Y') . " (28 days)");
            $this->line("   Status: 🟢 OK - Still within notice period");
            $this->newLine();
        }

        // Verify
        $this->info('=== VERIFICATION ===');
        $this->newLine();

        $renewalsPageMembers = Registration::where(function($query) {
            $query->where('login_status', 'approved')
                  ->orWhere('renewal_status', 'approved');
        })
        ->whereNotNull('card_valid_until')
        ->where('card_valid_until', '<=', now()->addDays(30))
        ->orderBy('card_valid_until', 'asc')
        ->get();

        $this->info("Members now appearing on Renewals page: " . $renewalsPageMembers->count());
        $this->newLine();

        if ($renewalsPageMembers->count() > 0) {
            $this->line('List:');
            foreach ($renewalsPageMembers as $member) {
                $daysUntil = (int) now()->diffInDays($member->card_valid_until, false);
                $status = $daysUntil < 0 ? "EXPIRED (" . abs($daysUntil) . " days ago)" : "Expires in {$daysUntil} days";
                
                $this->line("  - {$member->memberName}: {$member->card_valid_until->format('M d, Y')} ({$status})");
            }
            $this->newLine();
        }

        $this->info('✅ Test data created successfully!');
        $this->line('📄 Visit: http://127.0.0.1:8000/admin/renewals to see the results');
        $this->newLine();

        $this->comment('=== TO RESET DATA BACK TO NORMAL ===');
        $this->line('Run: php artisan renewals:reset-test-data');
        $this->newLine();

        return self::SUCCESS;
    }
}

