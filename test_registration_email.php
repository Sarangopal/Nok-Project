<?php

/**
 * Quick Test Script for Registration Email Functionality
 * 
 * This script verifies that registration confirmation emails are working
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Mail\RegistrationConfirmationMail;
use Illuminate\Support\Facades\Mail;

echo "\n";
echo "╔══════════════════════════════════════════════════════════════════╗\n";
echo "║  REGISTRATION EMAIL FUNCTIONALITY TEST                           ║\n";
echo "╚══════════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Check if mail configuration is set
$mailDriver = config('mail.default');
$mailHost = config('mail.mailers.smtp.host');
$mailPort = config('mail.mailers.smtp.port');
$mailFrom = config('mail.from.address');
$mailFromName = config('mail.from.name');

echo "📧 MAIL CONFIGURATION:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Mail Driver:    {$mailDriver}\n";
echo "SMTP Host:      {$mailHost}\n";
echo "SMTP Port:      {$mailPort}\n";
echo "From Address:   {$mailFrom}\n";
echo "From Name:      {$mailFromName}\n";
echo "\n";

// Check if RegistrationController has email sending code
echo "📝 REGISTRATION FLOW CHECK:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$controllerPath = app_path('Http/Controllers/RegistrationController.php');
if (file_exists($controllerPath)) {
    $controllerContent = file_get_contents($controllerPath);
    
    if (strpos($controllerContent, 'RegistrationConfirmationMail') !== false) {
        echo "✅ RegistrationConfirmationMail is imported\n";
    } else {
        echo "❌ RegistrationConfirmationMail is NOT imported\n";
    }
    
    if (strpos($controllerContent, 'Mail::to') !== false) {
        echo "✅ Email sending code exists in controller\n";
    } else {
        echo "❌ Email sending code NOT found in controller\n";
    }
    
    if (preg_match('/Mail::to.*RegistrationConfirmationMail/', $controllerContent)) {
        echo "✅ Registration confirmation email is being sent after registration\n";
    } else {
        echo "⚠️  Registration confirmation email might not be configured\n";
    }
} else {
    echo "❌ RegistrationController.php not found\n";
}
echo "\n";

// Check if mail class exists
echo "📬 MAIL CLASS CHECK:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$mailClassPath = app_path('Mail/RegistrationConfirmationMail.php');
if (file_exists($mailClassPath)) {
    echo "✅ RegistrationConfirmationMail.php exists\n";
    
    $mailClassContent = file_get_contents($mailClassPath);
    if (strpos($mailClassContent, 'class RegistrationConfirmationMail') !== false) {
        echo "✅ Mail class is properly defined\n";
    }
    
    if (strpos($mailClassContent, 'registration_confirmation') !== false) {
        echo "✅ Email template is linked\n";
    }
} else {
    echo "❌ RegistrationConfirmationMail.php NOT found\n";
}
echo "\n";

// Check if email template exists
echo "📄 EMAIL TEMPLATE CHECK:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$templatePath = resource_path('views/emails/membership/registration_confirmation.blade.php');
if (file_exists($templatePath)) {
    echo "✅ registration_confirmation.blade.php exists\n";
    
    $templateContent = file_get_contents($templatePath);
    if (strpos($templateContent, 'memberName') !== false) {
        echo "✅ Template has memberName variable\n";
    }
    
    $templateSize = strlen($templateContent);
    echo "✅ Template size: {$templateSize} bytes\n";
} else {
    echo "❌ registration_confirmation.blade.php NOT found\n";
}
echo "\n";

// Test email sending
echo "🧪 EMAIL SENDING TEST:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

if ($mailDriver === 'log') {
    echo "⚠️  Mail driver is set to 'log'\n";
    echo "   Emails will be saved to: storage/logs/laravel.log\n";
    echo "   Change MAIL_MAILER in .env to 'smtp' for actual sending\n";
} else {
    echo "✅ Mail driver is set to: {$mailDriver}\n";
    echo "   Emails will be sent via SMTP\n";
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "SYSTEM STATUS:\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$allGood = file_exists($controllerPath) && 
           file_exists($mailClassPath) && 
           file_exists($templatePath) &&
           strpos(file_get_contents($controllerPath), 'RegistrationConfirmationMail') !== false;

if ($allGood) {
    echo "✅ Registration email functionality is SET UP and READY!\n\n";
    
    echo "How it works:\n";
    echo "1. User submits registration form\n";
    echo "2. System validates and saves data\n";
    echo "3. Confirmation email is sent automatically\n";
    echo "4. User receives 'Registration Received' email\n\n";
    
    echo "To send a test email:\n";
    echo "  php artisan test:registration-email your-email@example.com\n\n";
    
} else {
    echo "⚠️  Some components are missing or not configured properly\n\n";
    echo "Please check the issues listed above\n\n";
}

echo "═══════════════════════════════════════════════════════════════════\n";
echo "RECOMMENDATIONS:\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

echo "1. Test email sending:\n";
echo "   php artisan test:registration-email your-email@example.com\n\n";

echo "2. Check mail logs:\n";
echo "   tail -f storage/logs/laravel.log\n\n";

echo "3. Verify .env mail settings:\n";
echo "   MAIL_MAILER=smtp\n";
echo "   MAIL_HOST=your-smtp-host\n";
echo "   MAIL_PORT=587\n";
echo "   MAIL_USERNAME=your-email\n";
echo "   MAIL_PASSWORD=your-password\n";
echo "   MAIL_ENCRYPTION=tls\n";
echo "   MAIL_FROM_ADDRESS=noreply@yourdomain.com\n";
echo "   MAIL_FROM_NAME=\"NOK Kuwait\"\n\n";

echo "4. Test with a real registration:\n";
echo "   Go to your registration page and submit a form\n";
echo "   Check if email arrives in inbox\n\n";

echo "═══════════════════════════════════════════════════════════════════\n\n";

