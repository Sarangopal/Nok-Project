<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationConfirmationMail;

class TestRegistrationEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:registration-email {email} {--name=Test User}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the registration confirmation email';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        $name = $this->option('name');

        $this->info("📧 Sending test registration confirmation email...");
        $this->newLine();
        
        $this->line("To: {$email}");
        $this->line("Name: {$name}");
        $this->newLine();

        try {
            Mail::to($email)->send(new RegistrationConfirmationMail([
                'memberName' => $name,
            ]));

            $this->newLine();
            $this->info("✅ Email sent successfully!");
            $this->newLine();
            
            $this->line("Check the following:");
            $this->line("1. ✉️  Check your email inbox: {$email}");
            $this->line("2. 📂 Check spam/junk folder if not in inbox");
            $this->line("3. ⚙️  Verify mail settings in .env file");
            $this->newLine();

            // Display mail configuration
            $this->info("Current Mail Configuration:");
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->line("Mailer: " . config('mail.default'));
            $this->line("Host: " . config('mail.mailers.smtp.host'));
            $this->line("Port: " . config('mail.mailers.smtp.port'));
            $this->line("From: " . config('mail.from.address'));
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->newLine();
            $this->error("❌ Failed to send email!");
            $this->error($e->getMessage());
            $this->newLine();
            
            $this->warn("Common Issues:");
            $this->line("1. Check your .env mail configuration");
            $this->line("2. Verify SMTP credentials are correct");
            $this->line("3. Ensure mail server allows connections");
            $this->line("4. Check if firewall is blocking mail port");
            $this->newLine();

            return self::FAILURE;
        }
    }
}

