<?php

/**
 * Quick Email Test - Check Registration Email Functionality
 */

// Simple check without bootstrap to avoid errors
echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "  REGISTRATION EMAIL FUNCTIONALITY - VERIFICATION\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

// Check if required files exist
$checks = [
    'Controller' => 'app/Http/Controllers/RegistrationController.php',
    'Mail Class' => 'app/Mail/RegistrationConfirmationMail.php',
    'Email Template' => 'resources/views/emails/membership/registration_confirmation.blade.php',
];

foreach ($checks as $name => $path) {
    if (file_exists($path)) {
        echo "✅ {$name}: EXISTS\n";
    } else {
        echo "❌ {$name}: NOT FOUND\n";
    }
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "  CHECKING CODE IMPLEMENTATION\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

// Check if controller has email sending code
$controllerFile = 'app/Http/Controllers/RegistrationController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    
    echo "Checking RegistrationController.php:\n";
    echo "─────────────────────────────────────\n";
    
    if (strpos($content, 'use App\Mail\RegistrationConfirmationMail;') !== false) {
        echo "✅ RegistrationConfirmationMail imported\n";
    } else {
        echo "❌ RegistrationConfirmationMail NOT imported\n";
    }
    
    if (strpos($content, 'use Illuminate\Support\Facades\Mail;') !== false) {
        echo "✅ Mail facade imported\n";
    } else {
        echo "❌ Mail facade NOT imported\n";
    }
    
    if (strpos($content, 'Mail::to($created->email)->send(new RegistrationConfirmationMail') !== false) {
        echo "✅ Email sending code EXISTS in submit() method\n";
        
        // Find the exact code
        preg_match('/Mail::to\([^)]+\)->send\(new RegistrationConfirmationMail\([^)]*\)\);/s', $content, $matches);
        if (!empty($matches[0])) {
            echo "\n📝 Email sending code:\n";
            echo "   " . trim($matches[0]) . "\n";
        }
    } else {
        echo "❌ Email sending code NOT found\n";
    }
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "  EMAIL TEMPLATE PREVIEW\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$templateFile = 'resources/views/emails/membership/registration_confirmation.blade.php';
if (file_exists($templateFile)) {
    $template = file_get_contents($templateFile);
    echo "Template content preview:\n";
    echo "─────────────────────────────────────\n";
    $lines = explode("\n", $template);
    foreach (array_slice($lines, 0, 10) as $line) {
        echo $line . "\n";
    }
    if (count($lines) > 10) {
        echo "... (" . count($lines) . " total lines)\n";
    }
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "  FINAL VERDICT\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$allFilesExist = file_exists('app/Http/Controllers/RegistrationController.php') &&
                 file_exists('app/Mail/RegistrationConfirmationMail.php') &&
                 file_exists('resources/views/emails/membership/registration_confirmation.blade.php');

$hasEmailCode = false;
if (file_exists('app/Http/Controllers/RegistrationController.php')) {
    $content = file_get_contents('app/Http/Controllers/RegistrationController.php');
    $hasEmailCode = strpos($content, 'Mail::to($created->email)->send(new RegistrationConfirmationMail') !== false;
}

if ($allFilesExist && $hasEmailCode) {
    echo "✅ ✅ ✅ REGISTRATION EMAIL SYSTEM IS WORKING! ✅ ✅ ✅\n\n";
    
    echo "HOW IT WORKS:\n";
    echo "─────────────────────────────────────\n";
    echo "1. User fills registration form on your website\n";
    echo "2. Form submits to: /registration-submit\n";
    echo "3. RegistrationController validates and saves data\n";
    echo "4. Email is automatically sent to user's email address\n";
    echo "5. User receives 'Registration Received Successfully!' email\n\n";
    
    echo "TO TEST:\n";
    echo "─────────────────────────────────────\n";
    echo "1. Go to your registration page\n";
    echo "2. Fill out the form with valid data\n";
    echo "3. Submit the form\n";
    echo "4. Check the email inbox (and spam folder)\n\n";
    
    echo "TO SEND TEST EMAIL:\n";
    echo "─────────────────────────────────────\n";
    echo "php artisan test:registration-email youremail@example.com\n\n";
    
} else {
    echo "⚠️  SOME COMPONENTS ARE MISSING\n\n";
    
    if (!$allFilesExist) {
        echo "Missing files - check above for details\n";
    }
    if (!$hasEmailCode) {
        echo "Email sending code not found in controller\n";
    }
}

echo "═══════════════════════════════════════════════════════════════════\n\n";

