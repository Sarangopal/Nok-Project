<?php

/**
 * NOK Kuwait - System Health Check Script
 * 
 * This script performs comprehensive health checks on the application
 * to ensure all functionality is working properly.
 * 
 * Usage: php health-check.php
 */

require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

class HealthChecker
{
    private $results = [];
    private $criticalFailures = 0;
    private $warnings = 0;

    public function run()
    {
        echo "\n";
        echo "╔════════════════════════════════════════════════════════╗\n";
        echo "║     NOK Kuwait - System Health Check                  ║\n";
        echo "╚════════════════════════════════════════════════════════╝\n";
        echo "\n";
        echo "Running comprehensive health checks...\n";
        echo str_repeat("─", 60) . "\n\n";

        $this->checkDatabaseConnection();
        $this->checkDatabaseTables();
        $this->checkCriticalData();
        $this->checkFilePermissions();
        $this->checkEnvironmentVariables();
        $this->checkWebServerAccess();
        $this->checkMemberAuthentication();
        $this->checkAdminPanel();
        $this->checkPublicPages();
        $this->checkEmailConfiguration();
        $this->checkStorageDirectories();
        $this->checkComposerDependencies();
        $this->checkApplicationKey();
        $this->checkDebugMode();
        $this->checkQueueConfiguration();

        $this->displaySummary();
    }

    private function checkDatabaseConnection()
    {
        echo "[1/15] Checking database connection... ";
        try {
            DB::connection()->getPdo();
            $this->pass("Connected to: " . DB::connection()->getDatabaseName());
        } catch (\Exception $e) {
            $this->fail("Database connection failed: " . $e->getMessage(), true);
        }
    }

    private function checkDatabaseTables()
    {
        echo "[2/15] Checking database tables... ";
        try {
            $requiredTables = [
                'users',
                'registrations',
                'renewals',
                'events',
                'offers',
                'contact_messages',
                'verification_attempts',
            ];

            $existingTables = DB::select('SHOW TABLES');
            $tableNames = array_map(function($table) {
                return array_values((array)$table)[0];
            }, $existingTables);

            $missingTables = array_diff($requiredTables, $tableNames);

            if (empty($missingTables)) {
                $this->pass("All required tables exist (" . count($requiredTables) . " tables)");
            } else {
                $this->fail("Missing tables: " . implode(', ', $missingTables), true);
            }
        } catch (\Exception $e) {
            $this->fail("Error checking tables: " . $e->getMessage(), true);
        }
    }

    private function checkCriticalData()
    {
        echo "[3/15] Checking critical data... ";
        try {
            $adminCount = DB::table('users')->count();
            $memberCount = DB::table('registrations')->count();

            if ($adminCount > 0 && $memberCount >= 0) {
                $this->pass("Admins: $adminCount, Members: $memberCount");
            } else if ($adminCount === 0) {
                $this->warn("No admin users found in database");
            }
        } catch (\Exception $e) {
            $this->fail("Error checking data: " . $e->getMessage());
        }
    }

    private function checkFilePermissions()
    {
        echo "[4/15] Checking file permissions... ";
        $directories = [
            'storage',
            'storage/app',
            'storage/framework',
            'storage/framework/cache',
            'storage/framework/sessions',
            'storage/framework/views',
            'storage/logs',
            'bootstrap/cache',
        ];

        $issues = [];
        foreach ($directories as $dir) {
            $path = base_path($dir);
            if (!is_writable($path)) {
                $issues[] = $dir;
            }
        }

        if (empty($issues)) {
            $this->pass("All required directories are writable");
        } else {
            $this->fail("Not writable: " . implode(', ', $issues), true);
        }
    }

    private function checkEnvironmentVariables()
    {
        echo "[5/15] Checking environment variables... ";
        $required = [
            'APP_NAME',
            'APP_ENV',
            'APP_KEY',
            'APP_URL',
            'DB_CONNECTION',
            'DB_HOST',
            'DB_DATABASE',
        ];

        $missing = [];
        foreach ($required as $var) {
            if (empty(env($var))) {
                $missing[] = $var;
            }
        }

        if (empty($missing)) {
            $this->pass("All required variables set");
        } else {
            $this->warn("Missing: " . implode(', ', $missing));
        }
    }

    private function checkWebServerAccess()
    {
        echo "[6/15] Checking web server access... ";
        try {
            $url = env('APP_URL', 'http://localhost');
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $this->pass("Homepage accessible (HTTP 200)");
            } else {
                $this->warn("Homepage returned HTTP $httpCode");
            }
        } catch (\Exception $e) {
            $this->warn("Could not check web access: " . $e->getMessage());
        }
    }

    private function checkMemberAuthentication()
    {
        echo "[7/15] Checking member authentication... ";
        try {
            $testMember = DB::table('registrations')
                ->where('renewal_status', 'approved')
                ->first();

            if ($testMember) {
                $this->pass("Member authentication system configured");
            } else {
                $this->warn("No approved members found for testing");
            }
        } catch (\Exception $e) {
            $this->warn("Error checking authentication: " . $e->getMessage());
        }
    }

    private function checkAdminPanel()
    {
        echo "[8/15] Checking admin panel... ";
        try {
            if (class_exists(\Filament\Facades\Filament::class)) {
                $this->pass("Filament admin panel installed");
            } else {
                $this->warn("Filament not detected");
            }
        } catch (\Exception $e) {
            $this->warn("Error checking admin panel: " . $e->getMessage());
        }
    }

    private function checkPublicPages()
    {
        echo "[9/15] Checking public pages... ";
        try {
            $viewsExist = [
                'index' => file_exists(resource_path('views/index.blade.php')),
                'about' => file_exists(resource_path('views/about.blade.php')),
                'contact' => file_exists(resource_path('views/contact.blade.php')),
            ];

            $existingViews = array_filter($viewsExist);
            if (count($existingViews) >= 2) {
                $this->pass("Key views found (" . count($existingViews) . " views)");
            } else {
                $this->warn("Some views may be missing");
            }
        } catch (\Exception $e) {
            $this->warn("Error checking views: " . $e->getMessage());
        }
    }

    private function checkEmailConfiguration()
    {
        echo "[10/15] Checking email configuration... ";
        $mailer = env('MAIL_MAILER');
        $fromAddress = env('MAIL_FROM_ADDRESS');

        if ($mailer && $fromAddress) {
            $this->pass("Email configured ($mailer)");
        } else {
            $this->warn("Email not fully configured");
        }
    }

    private function checkStorageDirectories()
    {
        echo "[11/15] Checking storage directories... ";
        try {
            $disk = Storage::disk('local');
            if ($disk->exists('.')) {
                $this->pass("Storage system operational");
            } else {
                $this->warn("Storage may have issues");
            }
        } catch (\Exception $e) {
            $this->warn("Error checking storage: " . $e->getMessage());
        }
    }

    private function checkComposerDependencies()
    {
        echo "[12/15] Checking composer dependencies... ";
        if (file_exists(base_path('vendor/autoload.php'))) {
            $this->pass("Composer dependencies installed");
        } else {
            $this->fail("Composer dependencies missing - run: composer install", true);
        }
    }

    private function checkApplicationKey()
    {
        echo "[13/15] Checking application key... ";
        if (env('APP_KEY')) {
            $this->pass("Application key is set");
        } else {
            $this->fail("Application key missing - run: php artisan key:generate", true);
        }
    }

    private function checkDebugMode()
    {
        echo "[14/15] Checking debug mode... ";
        $debug = env('APP_DEBUG', false);
        $env = env('APP_ENV', 'production');

        if ($env === 'production' && $debug) {
            $this->warn("Debug mode enabled in production!");
        } else {
            $this->pass("Debug mode: " . ($debug ? 'ON' : 'OFF') . " (Env: $env)");
        }
    }

    private function checkQueueConfiguration()
    {
        echo "[15/15] Checking queue configuration... ";
        $queueDriver = env('QUEUE_CONNECTION', 'sync');
        $this->pass("Queue driver: $queueDriver");
    }

    private function pass($message)
    {
        echo "✓ PASS - $message\n";
        $this->results['pass'][] = $message;
    }

    private function warn($message)
    {
        echo "⚠ WARN - $message\n";
        $this->results['warn'][] = $message;
        $this->warnings++;
    }

    private function fail($message, $critical = false)
    {
        echo "✗ FAIL - $message\n";
        $this->results['fail'][] = $message;
        if ($critical) {
            $this->criticalFailures++;
        }
    }

    private function displaySummary()
    {
        echo "\n";
        echo str_repeat("═", 60) . "\n";
        echo "                    HEALTH CHECK SUMMARY\n";
        echo str_repeat("═", 60) . "\n\n";

        $passed = count($this->results['pass'] ?? []);
        $warnings = count($this->results['warn'] ?? []);
        $failed = count($this->results['fail'] ?? []);
        $total = $passed + $warnings + $failed;

        echo "Total Checks: $total\n";
        echo "✓ Passed:     $passed\n";
        echo "⚠ Warnings:   $warnings\n";
        echo "✗ Failed:     $failed\n";

        if ($this->criticalFailures > 0) {
            echo "\n";
            echo "╔════════════════════════════════════════════════════════╗\n";
            echo "║  ⚠️  CRITICAL ISSUES DETECTED - IMMEDIATE ACTION NEEDED║\n";
            echo "╚════════════════════════════════════════════════════════╝\n";
            exit(1);
        } elseif ($warnings > 0) {
            echo "\n";
            echo "⚠️  System is operational but has warnings.\n";
            echo "   Please review and address warnings when possible.\n";
            exit(0);
        } else {
            echo "\n";
            echo "✓ All systems operational - No issues detected!\n";
            exit(0);
        }
    }
}

// Run the health checker
$checker = new HealthChecker();
$checker->run();




