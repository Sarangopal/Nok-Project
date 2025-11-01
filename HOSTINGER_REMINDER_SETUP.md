# 📧 Setting Up Automatic Reminder Emails on Hostinger

Complete guide to configure automatic renewal reminder emails on Hostinger hosting.

---

## 🎯 Overview

The renewal reminder system will automatically send emails to members at these intervals:
- **30 days before** card expires
- **15 days before** card expires
- **7 days before** card expires
- **1 day before** card expires
- **On expiry day** (expired today)

**Runs daily at 08:00 AM (Kuwait time)** automatically.

---

## 📋 Step-by-Step Setup on Hostinger

### Step 1: Login to Hostinger hPanel

1. Go to: https://www.hostinger.com/
2. Click "**Login**" → "**hPanel**"
3. Select your hosting plan
4. Navigate to your website's control panel

---

### Step 2: Access Cron Jobs

1. In hPanel, find "**Advanced**" section
2. Click on "**Cron Jobs**"
3. Or search for "Cron Jobs" in the search bar

---

### Step 3: Create New Cron Job

#### A. Common Settings

Click "**Create New Cron Job**" and configure:

**1. Schedule:**
- Select: **Common Settings**
- Choose: **Once Per Minute (*****)** ← This is correct!

**Why every minute?**
- The cron runs Laravel's scheduler every minute
- Laravel itself decides what to run and when
- Our reminder command is configured to run **daily at 08:00 AM**
- Laravel handles the timing automatically

#### B. Command to Execute

Enter this command:

```bash
cd /home/u123456789/domains/yourdomain.com/public_html && /usr/bin/php8.2 artisan schedule:run >> /dev/null 2>&1
```

**Important: Customize the path!**

**Option 1: Find Your Path**
1. Go to hPanel → **Files** → **File Manager**
2. Navigate to your Laravel project root folder
3. Copy the full path (e.g., `/home/u123456789/domains/nokkuwait.com/public_html`)

**Option 2: Use SSH to Find Path**
```bash
pwd
# Output: /home/u123456789/domains/yourdomain.com/public_html
```

**Option 3: Common Hostinger Paths**
- `/home/u123456789/domains/yourdomain.com/public_html`
- `/home/u123456789/domains/yourdomain.com/public_html/nok-kuwait`
- `/home/username/public_html`

**PHP Version:**
- Replace `/usr/bin/php8.2` with your PHP version
- Common options: `php8.1`, `php8.2`, `php8.3`
- Check your PHP version in hPanel → **PHP** → **PHP Configuration**

---

### Step 4: Verify Cron Job Command

**Final command format:**
```bash
cd [YOUR_LARAVEL_ROOT_PATH] && [PHP_PATH] artisan schedule:run >> /dev/null 2>&1
```

**Example 1 (Standard):**
```bash
cd /home/u123456789/domains/nokkuwait.com/public_html && /usr/bin/php8.2 artisan schedule:run >> /dev/null 2>&1
```

**Example 2 (With Subdirectory):**
```bash
cd /home/u123456789/domains/nokkuwait.com/public_html/nok-kuwait && /usr/bin/php8.2 artisan schedule:run >> /dev/null 2>&1
```

**Example 3 (Alternative PHP Path):**
```bash
cd /home/u123456789/domains/nokkuwait.com/public_html && php artisan schedule:run >> /dev/null 2>&1
```

---

### Step 5: Set Email for Notifications (Optional)

In the Cron Job settings:
- **Email:** Your admin email (e.g., admin@nokkuwait.com)
- **Purpose:** Get notified if cron job fails
- **Recommendation:** Enable this for monitoring

---

### Step 6: Save and Enable

1. Click "**Create**" or "**Save**"
2. Verify cron job appears in the list
3. Ensure it's **enabled** (toggle should be ON)

---

## 🔧 Email Configuration on Hostinger

### Method 1: Use Hostinger's SMTP (Recommended)

Update your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="NOK Kuwait"
```

**Steps to create email account:**
1. hPanel → **Emails** → **Email Accounts**
2. Create: `noreply@yourdomain.com`
3. Set a strong password
4. Use these credentials in `.env`

---

### Method 2: Use Gmail (For Testing Only)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com
MAIL_FROM_NAME="NOK Kuwait"
```

**Note:** 
- Gmail requires "App Password" (not regular password)
- Google Account → Security → 2-Step Verification → App Passwords
- Gmail may mark as spam or block if sending too many emails

---

### Method 3: Use Third-Party Service (Production Recommended)

**Mailgun (Free tier: 5,000 emails/month)**
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=mg.yourdomain.com
MAILGUN_SECRET=your-mailgun-api-key
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="NOK Kuwait"
```

**SendGrid (Free tier: 100 emails/day)**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

---

## ✅ Testing on Hostinger

### 1. SSH into Hostinger

**Enable SSH:**
1. hPanel → **Advanced** → **SSH Access**
2. Enable SSH
3. Note your credentials

**Connect via SSH:**
```bash
ssh u123456789@yourdomain.com
```

Or use **hPanel's Web Terminal** (easier):
1. hPanel → **Advanced** → **Terminal**
2. Opens a web-based terminal

---

### 2. Navigate to Project

```bash
cd public_html
# or
cd public_html/nok-kuwait
```

---

### 3. Test Scheduler

```bash
php artisan schedule:list
```

**Expected Output:**
```
  0 8 * * *  members:send-renewal-reminders
              Next Due: Tomorrow at 08:00 AM
```

---

### 4. Test Manual Reminder Sending

```bash
php artisan members:send-renewal-reminders --verbose
```

**Expected Output:**
```
Checking members for renewal reminders...
⏭️  Skipped: Test Member - 30 Days - Already sent 30 days reminder
⏭️  Skipped: Test Member - 15 Days - Already sent 15 days reminder
Renewal reminders sent: 0
```

(Skipped because already sent today - this is correct!)

---

### 5. Test Email Sending

```bash
php artisan tinker
```

```php
Mail::raw('Test email from Hostinger', function($msg) {
    $msg->to('developer1.bten@gmail.com')
        ->subject('Test Email - NOK Kuwait');
});
// Press Ctrl+D to exit
```

**Check:**
1. Email received in inbox?
2. If not, check spam folder
3. Check Laravel logs: `storage/logs/laravel.log`

---

## 🔍 Verify Cron Job is Working

### Method 1: Check Cron Execution

Create a test file to verify cron runs:

**In hPanel Terminal:**
```bash
cd public_html
echo "*/1 * * * * date >> /home/u123456789/cron-test.log" | crontab -
```

Wait 2-3 minutes, then check:
```bash
cat /home/u123456789/cron-test.log
```

If you see timestamps, cron is working! ✅

---

### Method 2: Check Laravel Scheduler Logs

Add logging to scheduler (optional):

Edit `routes/console.php`:
```php
Schedule::command('members:send-renewal-reminders')
    ->dailyAt('08:00')
    ->timezone('Asia/Kuwait')
    ->name('Send Renewal Reminder Emails')
    ->appendOutputTo(storage_path('logs/scheduler.log'));
```

Check logs:
```bash
tail -f storage/logs/scheduler.log
```

---

### Method 3: Check Admin Panel

1. Go to: **http://yourdomain.com/admin/reminder-emails**
2. Check for new entries daily at 08:00 AM
3. Verify status = "sent"

---

## 🐛 Troubleshooting on Hostinger

### Problem 1: Cron Job Not Running

**Solution:**
```bash
# Check crontab
crontab -l

# Should show:
* * * * * cd /home/u123456789/domains/yourdomain.com/public_html && php artisan schedule:run >> /dev/null 2>&1
```

If empty, recreate cron job in hPanel.

---

### Problem 2: Permission Denied

**Solution:**
```bash
# Fix permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R u123456789:u123456789 storage
```

---

### Problem 3: Artisan Command Not Found

**Solution:**
```bash
# Verify you're in the right directory
pwd
# Should show: /home/u123456789/domains/yourdomain.com/public_html

# Check if artisan exists
ls -la artisan

# Use full path to PHP
/usr/bin/php8.2 artisan schedule:list
```

---

### Problem 4: Emails Not Sending

**Solutions:**

**A. Check SMTP Settings**
```bash
php artisan tinker
>>> config('mail.mailers.smtp');
```

**B. Check Laravel Logs**
```bash
tail -f storage/logs/laravel.log
```

**C. Test SMTP Connection**
```bash
telnet smtp.hostinger.com 465
# Should connect successfully
```

**D. Verify Email Account Exists**
- hPanel → **Emails** → **Email Accounts**
- Ensure the email in `MAIL_FROM_ADDRESS` exists

**E. Check SPF/DKIM Records**
- hPanel → **Emails** → **Email Deliverability**
- Verify SPF and DKIM records are configured

---

### Problem 5: Wrong Timezone

**Solution:**

Check current timezone:
```bash
php artisan tinker
>>> config('app.timezone');
>>> now()->timezone;
```

Update `.env`:
```env
APP_TIMEZONE=Asia/Kuwait
```

Clear cache:
```bash
php artisan config:clear
php artisan cache:clear
```

---

## 📊 Monitoring on Hostinger

### 1. Daily Checks

**Via Admin Panel:**
- Visit: `http://yourdomain.com/admin/reminder-emails`
- Filter: "Sent Today"
- Verify emails sent at 08:00 AM

---

### 2. Log Monitoring

**View Laravel Logs:**
```bash
tail -f storage/logs/laravel.log
```

**Search for Errors:**
```bash
grep -i "error" storage/logs/laravel.log | tail -20
```

---

### 3. Database Check

**Via SSH:**
```bash
php artisan tinker
```

```php
// Check reminders sent today
App\Models\RenewalReminder::whereDate('created_at', today())->count();

// View latest reminders
App\Models\RenewalReminder::latest()->take(5)->get(['member_name', 'days_before_expiry', 'status', 'created_at']);
```

---

## 🎯 Best Practices for Hostinger

### 1. Email Delivery

✅ **DO:**
- Use Hostinger's SMTP for better deliverability
- Create a dedicated email: `noreply@yourdomain.com`
- Set up SPF and DKIM records (Hostinger does this automatically)
- Send from your domain (not Gmail)

❌ **DON'T:**
- Use Gmail for production (will be rate-limited)
- Send too many emails at once (Hostinger has limits)
- Use "no-reply" email without creating the actual email account

---

### 2. Resource Management

**Hostinger Limits:**
- Shared Hosting: Limited CPU/memory
- Entry Point: ~25 emails per hour
- Avoid running heavy tasks during peak hours

**Optimize:**
```php
// In SendRenewalReminders.php
// Add delays between emails to avoid hitting limits
foreach ($members as $member) {
    Mail::to($member->email)->send(new RenewalReminderMail($member, $days));
    
    // Add 1 second delay (optional, for shared hosting)
    sleep(1);
}
```

---

### 3. Backup & Monitoring

**Weekly Tasks:**
1. Check reminder logs in admin panel
2. Verify no failed emails
3. Export logs for records
4. Monitor Hostinger email quota

**Monthly Tasks:**
1. Review and clean old reminder logs (>6 months)
2. Check SPF/DKIM records still valid
3. Verify cron job still running
4. Update documentation if needed

---

## 📞 Hostinger Support

If you encounter issues:

1. **Live Chat:** 24/7 available in hPanel
2. **Ticket System:** hPanel → Help → Submit Ticket
3. **Knowledge Base:** https://support.hostinger.com/

**Useful Articles:**
- "How to Create a Cron Job"
- "How to Use SSH Access"
- "How to Set Up Email Accounts"

---

## ✅ Final Checklist

Before going live:

- [ ] Cron job created in hPanel (every minute)
- [ ] Command path verified (tested in SSH)
- [ ] Email account created (noreply@yourdomain.com)
- [ ] SMTP settings configured in `.env`
- [ ] SPF/DKIM records configured (check Email Deliverability)
- [ ] Test email sent successfully
- [ ] Scheduler showing correct next run time
- [ ] Admin panel displays reminders correctly
- [ ] Laravel logs show no errors
- [ ] Tested with real email addresses
- [ ] Emails not going to spam
- [ ] Cron notification email configured

---

## 📋 Quick Reference

**Cron Job Command (Hostinger):**
```bash
cd /home/u123456789/domains/yourdomain.com/public_html && /usr/bin/php8.2 artisan schedule:run >> /dev/null 2>&1
```

**Schedule:** Every minute (*****)

**Email Settings (.env):**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=ssl
```

**Test Commands:**
```bash
php artisan schedule:list
php artisan members:send-renewal-reminders --verbose
php artisan config:clear
php artisan cache:clear
```

---

**Last Updated:** November 1, 2025  
**Hosting Provider:** Hostinger  
**Tested On:** Hostinger Premium/Business Plans

