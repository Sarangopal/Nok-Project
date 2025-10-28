<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class VerifyAdminLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify admin login credentials';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== ADMIN VERIFICATION ===');
        $this->newLine();
        
        // Check if admin@gmail.com exists
        $admin = User::where('email', 'admin@gmail.com')->first();
        
        if ($admin) {
            $this->info('✓ Admin user found!');
            $this->line("  Name: {$admin->name}");
            $this->line("  Email: {$admin->email}");
            $this->line("  Created: {$admin->created_at}");
            $this->newLine();
            
            // Verify password
            if (Hash::check('secret', $admin->password)) {
                $this->info('✓ Password "secret" is CORRECT!');
                $this->newLine();
                
                // Try to authenticate
                if (Auth::attempt(['email' => 'admin@gmail.com', 'password' => 'secret'])) {
                    $this->info('✓ Authentication successful!');
                    $this->newLine();
                } else {
                    $this->error('✗ Authentication failed!');
                }
                
            } else {
                $this->error('✗ Password "secret" is INCORRECT!');
                $this->line('  The admin user exists but the password doesn\'t match.');
                $this->newLine();
            }
            
            // Show access info
            $this->info('=== Admin Panel Access ===');
            $this->line('You can access the admin panel at:');
            $this->line('  URL: http://127.0.0.1:8000/admin/login');
            $this->line('  Email: admin@gmail.com');
            $this->line('  Password: secret');
            $this->newLine();
            
        } else {
            $this->error('✗ Admin user NOT found!');
            $this->line('  Email "admin@gmail.com" does not exist in the database.');
            $this->newLine();
            $this->line('Creating admin user now...');
            
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => 'secret', // Will be hashed by setPasswordAttribute
            ]);
            
            $this->info('✓ Admin user created successfully!');
            $this->newLine();
        }
        
        // List all users
        $this->info('=== All Users in Database ===');
        $users = User::all();
        if ($users->count() > 0) {
            foreach ($users as $user) {
                $this->line("  - {$user->email} ({$user->name})");
            }
        } else {
            $this->line('  No users found.');
        }
        
        $this->newLine();
        $this->info('✓ Verification complete!');
        
        return Command::SUCCESS;
    }
}
