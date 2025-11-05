<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationConfirmationMail;

class TestRegistrationEmail extends Command
{
    protected $signature = 'mail:test-registration {email}';
    protected $description = 'Test registration confirmation email';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->line("========================================");
        $this->info("📧 Testing Registration Confirmation Email");
        $this->line("========================================");
        $this->line("");
        
        $this->info("→ Target Email: {$email}");
        $this->info("→ SMTP Host: " . config('mail.mailers.smtp.host'));
        $this->info("→ SMTP Port: " . config('mail.mailers.smtp.port'));
        $this->info("→ From Address: " . config('mail.from.address'));
        $this->line("");
        
        $this->info("Sending email...");
        
        try {
            Mail::to($email)->send(new RegistrationConfirmationMail([
                'memberName' => 'Test User Kumar',
            ]));
            
            $this->line("");
            $this->info("✅ SUCCESS! Email sent successfully!");
            $this->line("");
            $this->info("📬 Check your inbox at: {$email}");
            $this->info("⚠️  Also check spam/junk folder if not in inbox.");
            $this->line("========================================");
            
            return 0;
        } catch (\Throwable $e) {
            $this->line("");
            $this->error("❌ FAILED! Could not send email!");
            $this->line("");
            $this->error("Error Message:");
            $this->error($e->getMessage());
            $this->line("");
            $this->error("File: " . $e->getFile() . " (Line: " . $e->getLine() . ")");
            $this->line("========================================");
            
            if ($this->option('verbose')) {
                $this->line("");
                $this->error("Stack Trace:");
                $this->error($e->getTraceAsString());
            }
            
            return 1;
        }
    }
}
