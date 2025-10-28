<?php

namespace App\Console\Commands;

use App\Models\Registration;
use App\Mail\MembershipCardMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ResendMembershipCards extends Command
{
    protected $signature = 'members:resend-cards {--nok-id=* : Specific NOK IDs to resend (optional)} {--all : Resend to all approved members}';
    protected $description = 'Resend membership cards to members (with corrected expiry dates)';

    public function handle(): int
    {
        $nokIds = $this->option('nok-id');
        $sendToAll = $this->option('all');
        
        if (empty($nokIds) && !$sendToAll) {
            $this->error('Please specify either --nok-id or --all flag');
            $this->line('Examples:');
            $this->line('  php artisan members:resend-cards --nok-id=NOK001006');
            $this->line('  php artisan members:resend-cards --nok-id=NOK001006 --nok-id=NOK001003');
            $this->line('  php artisan members:resend-cards --all');
            return self::FAILURE;
        }
        
        // Build query
        $query = Registration::query()
            ->where(function($q) {
                $q->where('login_status', 'approved')
                  ->orWhere('renewal_status', 'approved');
            })
            ->whereNotNull('card_valid_until');
        
        if (!$sendToAll) {
            $query->whereIn('nok_id', $nokIds);
        }
        
        $members = $query->get();
        
        if ($members->isEmpty()) {
            $this->warn('No members found!');
            return self::FAILURE;
        }
        
        $this->info("Found {$members->count()} member(s) to send cards to:");
        $this->newLine();
        
        if (!$this->confirm('Do you want to send membership cards to these members?')) {
            $this->info('Cancelled.');
            return self::SUCCESS;
        }
        
        $this->newLine();
        
        $sent = 0;
        $failed = 0;
        
        foreach ($members as $member) {
            $this->line("Sending to: {$member->nok_id} - {$member->memberName}");
            $this->line("  Email: {$member->email}");
            $this->line("  Card expires: {$member->card_valid_until->format('M d, Y')}");
            
            try {
                Mail::to($member->email)->send(new MembershipCardMail(['record' => $member]));
                $sent++;
                $this->info("  ✓ Sent successfully!");
            } catch (\Throwable $e) {
                $failed++;
                $this->error("  ✗ Failed: {$e->getMessage()}");
            }
            
            $this->newLine();
        }
        
        $this->newLine();
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("✓ Successfully sent: {$sent}");
        if ($failed > 0) {
            $this->error("✗ Failed: {$failed}");
        }
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        
        return self::SUCCESS;
    }
}

