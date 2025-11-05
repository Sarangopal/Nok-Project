<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationConfirmationMail;

// Temporary test route for email debugging
Route::get('/test-email-debug', function () {
    try {
        $testEmail = request('email', 'nightingalesofkuwait24@gmail.com');
        
        echo "<h1>Email Test Debug</h1>";
        echo "<h2>Configuration:</h2>";
        echo "<pre>";
        echo "MAIL_MAILER: " . config('mail.default') . "\n";
        echo "MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
        echo "MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
        echo "MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "\n";
        echo "MAIL_ENCRYPTION: " . config('mail.mailers.smtp.encryption') . "\n";
        echo "MAIL_FROM_ADDRESS: " . config('mail.from.address') . "\n";
        echo "MAIL_FROM_NAME: " . config('mail.from.name') . "\n";
        echo "</pre>";
        
        echo "<h2>Sending Test Email to: {$testEmail}</h2>";
        echo "<p>Please wait...</p>";
        
        Mail::to($testEmail)->send(new RegistrationConfirmationMail([
            'memberName' => 'Test User Kumar',
        ]));
        
        echo "<h3 style='color: green;'>✅ SUCCESS! Email sent successfully!</h3>";
        echo "<p>Check your inbox at: <strong>{$testEmail}</strong></p>";
        echo "<p><em>Also check spam/junk folder</em></p>";
        
    } catch (\Exception $e) {
        echo "<h3 style='color: red;'>❌ ERROR!</h3>";
        echo "<pre style='color: red;'>";
        echo "Message: " . $e->getMessage() . "\n\n";
        echo "File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n\n";
        echo "Stack Trace:\n" . $e->getTraceAsString();
        echo "</pre>";
    }
    
    echo "<hr>";
    echo "<p><a href='/registration'>← Back to Registration</a></p>";
});

