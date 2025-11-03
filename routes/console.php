<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

/**
 * ┌─────────────────────────────────────────────────────────────┐
 * │  AUTOMATIC RENEWAL REMINDER EMAILS                          │
 * ├─────────────────────────────────────────────────────────────┤
 * │  This command runs DAILY at 08:00 AM                        │
 * │  It sends reminder emails to members whose cards:           │
 * │  • Are expired (past expiry date)                           │
 * │  • Expire in 30 days                                        │
 * │  • Expire in 15 days                                        │
 * │  • Expire in 7 days                                         │
 * │  • Expire in 1 day                                          │
 * │  • Expire today (0 days)                                    │
 * │                                                             │
 * │  ⚠️ IMPORTANT: Requires cron job setup on server!          │
 * │  See DEPLOYMENT_HOSTINGER.md for setup instructions        │
 * │                                                             │
 * │  Manual test: php artisan members:send-renewal-reminders   │
 * └─────────────────────────────────────────────────────────────┘
 */
Schedule::command('members:send-renewal-reminders')
    ->dailyAt('08:00')
    ->timezone('Asia/Kuwait')  // Adjust timezone as needed
    ->name('Send Renewal Reminder Emails')
    ->description('Automatically sends renewal reminder emails to members')
    ->emailOutputOnFailure('admin@yourdomain.com');  // Optional: Get notified if it fails
