<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Registration;
use App\Models\RenewalReminder;
use App\Mail\RenewalReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class SendRenewalReminders extends Command
{
    protected $signature = 'members:send-renewal-reminders {--days=30,15,7,1,0}';
    protected $description = 'Send renewal reminder emails to members approaching expiry';

    public function handle(): int
    {
        $daysList = collect(explode(',', (string) $this->option('days')))
            ->map(fn ($d) => (int) trim($d))
            // Include 0 so a final reminder is sent on the expiry day itself
            ->filter(fn ($d) => $d >= 0)
            ->unique()
            ->values();

        $now = now();
        $totalSent = 0;

        foreach ($daysList as $days) {
            if ($days === 0) {
                // For expired cards, find ALL expired cards (past date)
                $members = Registration::query()
                    ->where(function($query) {
                        $query->where('login_status', 'approved')
                              ->orWhere('renewal_status', 'approved');
                    })
                    ->whereDate('card_valid_until', '<', $now->toDateString())
                    ->get();
            } else {
                // For future dates, find exact match
                $targetDate = $now->copy()->addDays($days)->toDateString();
                $members = Registration::query()
                    ->where(function($query) {
                        $query->where('login_status', 'approved')
                              ->orWhere('renewal_status', 'approved');
                    })
                    ->whereDate('card_valid_until', '=', $targetDate)
                    ->get();
            }

            foreach ($members as $member) {
                // Check if reminder already sent to this member for this expiry date and days
                $alreadySent = RenewalReminder::where('registration_id', $member->id)
                    ->where('card_valid_until', $member->card_valid_until)
                    ->where('days_before_expiry', $days)
                    ->where('status', 'sent')
                    ->exists();
                
                if ($alreadySent) {
                    $this->line("⏭️  Skipped: {$member->memberName} - Already sent {$days} days reminder");
                    continue;
                }
                
                try {
                    Mail::to($member->email)->send(new RenewalReminderMail($member, $days));
                    $totalSent++;
                    
                    // Log successful reminder email
                    RenewalReminder::create([
                        'registration_id' => $member->id,
                        'member_name' => $member->memberName,
                        'email' => $member->email,
                        'card_valid_until' => $member->card_valid_until,
                        'days_before_expiry' => $days,
                        'status' => 'sent',
                    ]);
                    
                    $this->info("✓ Sent to {$member->memberName} ({$member->email}) - {$days} days before expiry");
                } catch (\Throwable $e) {
                    $this->error("Failed to send to {$member->email}: {$e->getMessage()}");
                    
                    // Log failed reminder email
                    RenewalReminder::create([
                        'registration_id' => $member->id,
                        'member_name' => $member->memberName,
                        'email' => $member->email,
                        'card_valid_until' => $member->card_valid_until,
                        'days_before_expiry' => $days,
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);
                }
            }
        }

        $this->info("Renewal reminders sent: {$totalSent}");
        return self::SUCCESS;
    }
}
