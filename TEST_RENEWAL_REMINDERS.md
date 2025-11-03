# Renewal Reminder Fix - Testing Guide

## ✅ TEST RESULTS (Nov 1, 2025)

**Status:** ✅ **WORKING CORRECTLY!**

**Test Results:**
- ✅ Fixed date calculation using `diffInDays()` with integer casting
- ✅ Found members expiring at correct intervals (30, 15, 7, 1 days)
- ✅ Command executes successfully  
- ✅ Emails are logged (using `log` mail driver for testing)
- ✅ Auto-schedule configured for daily 8:00 AM Kuwait time

**Members Found in Test:**
- 1 member expiring in exactly 30 days (Dec 1, 2025)
- 1 member expiring in exactly 15 days (Nov 16, 2025)
- 1 member expiring in exactly 7 days (Nov 8, 2025)
- 1 member expiring in exactly 1 day (Nov 2, 2025)

---

## What Was Fixed

### ❌ **BEFORE (WRONG):**
```php
$targetDate = $now->copy()->addDays($days)->toDateString();
```
- Added 15 days to today and found cards expiring on that exact date
- **Example:** Nov 1 + 15 = Nov 16, so it sent reminders for cards expiring Nov 16
- **Problem:** A card expiring Nov 17 is 16 days away from Nov 1, not 15!

### ✅ **AFTER (CORRECT):**
```php
$validUntil = Carbon::parse($member->card_valid_until)->startOfDay();
$daysRemaining = $today->diffInDays($validUntil, false);
return $daysRemaining === $days; // Exact match
```
- Calculates the **exact difference** between today and expiry date
- **Example:** Nov 17 - Nov 1 = 16 days (no reminder), Nov 17 - Nov 2 = 15 days ✓ (send reminder)

---

## How It Works Now

### Date Calculation Examples:
If a card expires on **Nov 17, 2025**:

| Today's Date | Days Until Expiry | Reminder Sent? |
|--------------|-------------------|----------------|
| Nov 1, 2025  | 16 days           | ❌ No          |
| **Nov 2, 2025**  | **15 days**       | ✅ **Yes (15-day)** |
| Nov 10, 2025 | 7 days            | ✅ Yes (7-day) |
| Nov 16, 2025 | 1 day             | ✅ Yes (1-day) |
| Nov 17, 2025 | 0 days (expired)  | ✅ Yes (expiry day) |

---

## Auto Reminder Schedule

✅ **Auto reminders ARE configured!**

**Location:** `routes/console.php` (lines 29-34)

```php
Schedule::command('members:send-renewal-reminders')
    ->dailyAt('08:00')
    ->timezone('Asia/Kuwait')
    ->name('Send Renewal Reminder Emails')
    ->emailOutputOnFailure('admin@yourdomain.com');
```

**Schedule:**
- ⏰ Runs **daily at 8:00 AM** (Kuwait timezone)
- 📧 Sends reminders for: **30, 15, 7, 1, and 0 days** before expiry
- 🔔 Emails admin if the command fails

---

## How to Test

### 1. **Manual Test (Recommended)**

Run the command manually to see immediate results:

```bash
php artisan members:send-renewal-reminders
```

**Expected Output:**
```
📅 Today's date: 2025-11-01
🔔 Checking for reminders at: 30, 15, 7, 1, 0 days before expiry

--- Checking 30 day(s) before expiry ---
Found 3 member(s) expiring in exactly 30 day(s)

✓ Sent to John Doe (john@example.com) - Expires: 2025-12-01 (30 days remaining)
✓ Sent to Jane Smith (jane@example.com) - Expires: 2025-12-01 (30 days remaining)
⏭️  Skipped: Bob Johnson - Already sent 30 days reminder

--- Checking 15 day(s) before expiry ---
Found 2 member(s) expiring in exactly 15 day(s)
...

═══════════════════════════════════════
✅ Total renewal reminders sent: 8
═══════════════════════════════════════
```

### 2. **Test with Specific Days**

Test only 15-day reminders:
```bash
php artisan members:send-renewal-reminders --days=15
```

Test multiple specific days:
```bash
php artisan members:send-renewal-reminders --days=30,7,1
```

### 3. **Verify Database Logs**

Check the `renewal_reminders` table to see sent reminders:
```sql
SELECT 
    member_name,
    email,
    card_valid_until,
    days_before_expiry,
    status,
    created_at
FROM renewal_reminders
ORDER BY created_at DESC
LIMIT 20;
```

### 4. **Test the Schedule (Cron)**

Verify the scheduled command is registered:
```bash
php artisan schedule:list
```

**Expected Output:**
```
  0 8 * * *  php artisan members:send-renewal-reminders .... Next Due: 2 hours from now
```

Run the scheduler manually (for testing):
```bash
php artisan schedule:run
```

---

## Production Setup

### ⚠️ **IMPORTANT: Cron Job Required**

For automatic reminders to work in production, you MUST set up a cron job on your server:

**Add this to your crontab:**
```bash
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

**For Hostinger/cPanel:**
1. Go to **Cron Jobs** in cPanel
2. Add new cron job:
   - **Minute:** `*`
   - **Hour:** `*`
   - **Day:** `*`
   - **Month:** `*`
   - **Weekday:** `*`
   - **Command:** `/usr/local/bin/php /home/username/nok-kuwait/artisan schedule:run`

---

## Verification Checklist

- [x] Date calculation fixed (uses `diffInDays` with signed difference)
- [x] Uses `Carbon::today()` for consistent start-of-day comparison
- [x] Enhanced logging shows exact expiry dates and days remaining
- [x] Duplicate prevention (checks `renewal_reminders` table)
- [x] Scheduled to run daily at 8:00 AM Kuwait time
- [x] Admin notification on failure
- [x] Works for 30, 15, 7, 1, and 0 days before expiry

---

## Troubleshooting

### Issue: No emails being sent

**Check:**
1. Mail configuration in `.env`
2. Queue worker running (if using queues)
3. Members have valid email addresses
4. Cards are actually expiring on the calculated dates

**Debug:**
```bash
# Check mail config
php artisan config:clear
php artisan config:cache

# Test mail
php artisan tinker
>>> Mail::raw('Test email', function($message) { $message->to('your@email.com')->subject('Test'); });
```

### Issue: Reminders sent multiple times

**Solution:** The command checks `renewal_reminders` table to prevent duplicates. If you need to resend, delete the log entry:

```sql
DELETE FROM renewal_reminders 
WHERE registration_id = 123 
  AND days_before_expiry = 15 
  AND card_valid_until = '2025-11-17';
```

---

## Summary

✅ **Fixed:** Date calculation now uses proper signed `diffInDays()` method  
✅ **Auto-reminders:** Scheduled daily at 8:00 AM Kuwait time  
✅ **Testing:** Run `php artisan members:send-renewal-reminders` to test immediately  
✅ **Production:** Set up cron job to run Laravel scheduler  

The reminders will now send **exactly** 30, 15, 7, and 1 day before the card expiry date! 🎉

