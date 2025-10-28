<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Create admin user
$admin = User::updateOrCreate(
    ['email' => 'admin@gmail.com'],
    [
        'name' => 'Admin User',
        'password' => 'secret', // Will be hashed automatically by the 'hashed' cast
    ]
);

echo "✓ Admin user created successfully!\n";
echo "Email: admin@gmail.com\n";
echo "Password: secret\n";

