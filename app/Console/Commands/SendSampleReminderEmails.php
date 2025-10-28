<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Registration;
use App\Mail\RenewalReminderMail;
use Illuminate\Support\Facades\Mail;

class SendSampleReminderEmails extends Command
{
    protected $signature = 'members:send-sample-reminders {email : Email address to send samples to}';
    protected $description = 'Send sample reminder emails for all intervals (30, 15, 7, 1, 0 days) to a specific email';

    public function handle(): int
    {
        $targetEmail = $this->argument('email');
        
        $this->info("📧 Sending sample reminder emails to: {$targetEmail}");
        $this->newLine();

        // Create a sample member
        $sampleMember = new Registration([
            'nok_id' => 'NOK000001',
            'memberName' => 'Sample Member',
            'email' => $targetEmail,
            'civil_id' => '12345678901234',
            'mobile' => '+96550000000',
            'card_valid_until' => now()->addDays(30),
        ]);

        $intervals = [30, 15, 7, 1, 0];
        $bar = $this->output->createProgressBar(count($intervals));
        $bar->start();

        foreach ($intervals as $days) {
            try {
                // Update sample member's expiry date
                $sampleMember->card_valid_until = now()->addDays($days);
                
                // Send email
                Mail::to($targetEmail)->send(new RenewalReminderMail($sampleMember, $days));
                
                $bar->advance();
                
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Failed to send {$days}-day reminder: " . $e->getMessage());
                return self::FAILURE;
            }
        }

        $bar->finish();
        $this->newLine(2);
        
        $this->info('✅ Successfully sent 5 sample reminder emails:');
        $this->line('   • 30 days before expiry');
        $this->line('   • 15 days before expiry');
        $this->line('   • 7 days before expiry');
        $this->line('   • 1 day before expiry');
        $this->line('   • 0 days (expiry day)');
        $this->newLine();
        $this->info("📬 Check your inbox at: {$targetEmail}");
        $this->warn('💡 Also check your spam/junk folder!');

        return self::SUCCESS;
    }
}

