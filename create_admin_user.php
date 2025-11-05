<?php

/**
 * Create/Verify Admin User for Filament Login
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "\n";
echo "╔══════════════════════════════════════════════════════════════════╗\n";
echo "║  ADMIN USER CREATION/VERIFICATION                                ║\n";
echo "╚══════════════════════════════════════════════════════════════════╝\n";
echo "\n";

$email = 'admin@gmail.com';
$password = 'secret';
$name = 'Admin User';

echo "Checking for existing admin user...\n";
echo "─────────────────────────────────────────────────────────────────\n";

$user = User::where('email', $email)->first();

if ($user) {
    echo "✅ Admin user EXISTS\n";
    echo "   Email: {$user->email}\n";
    echo "   Name: {$user->name}\n";
    echo "   Created: {$user->created_at}\n\n";
    
    echo "Updating password to ensure it's correct...\n";
    $user->password = Hash::make($password);
    $user->save();
    echo "✅ Password updated!\n\n";
} else {
    echo "⚠️  Admin user does NOT exist. Creating...\n\n";
    
    $user = User::create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
        'email_verified_at' => now(),
    ]);
    
    echo "✅ Admin user CREATED successfully!\n";
    echo "   Email: {$user->email}\n";
    echo "   Name: {$user->name}\n\n";
}

echo "═══════════════════════════════════════════════════════════════════\n";
echo "LOGIN CREDENTIALS\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

echo "🌐 Login URL: http://127.0.0.1:8000/admin/login\n";
echo "📧 Email: {$email}\n";
echo "🔑 Password: {$password}\n\n";

echo "═══════════════════════════════════════════════════════════════════\n";
echo "VERIFICATION\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

// Test password
$testPassword = Hash::check($password, $user->password);
if ($testPassword) {
    echo "✅ Password verification: PASSED\n";
} else {
    echo "❌ Password verification: FAILED\n";
}

// Check if user can access admin panel
$canAccess = $user->canAccessPanel(app(\Filament\Panel::class));
echo "✅ Can access admin panel: " . ($canAccess ? 'YES' : 'NO') . "\n";

echo "\n";
echo "═══════════════════════════════════════════════════════════════════\n";
echo "ALL USERS IN DATABASE\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

$allUsers = User::all();
echo "Total users: {$allUsers->count()}\n\n";

foreach ($allUsers as $u) {
    echo "• {$u->name}\n";
    echo "  Email: {$u->email}\n";
    echo "  Created: {$u->created_at->format('M d, Y H:i:s')}\n";
    echo "\n";
}

echo "═══════════════════════════════════════════════════════════════════\n";
echo "STATUS: ✅ READY TO LOGIN\n";
echo "═══════════════════════════════════════════════════════════════════\n\n";

echo "Next steps:\n";
echo "1. Go to: http://127.0.0.1:8000/admin/login\n";
echo "2. Enter email: {$email}\n";
echo "3. Enter password: {$password}\n";
echo "4. Click 'Sign in'\n\n";

