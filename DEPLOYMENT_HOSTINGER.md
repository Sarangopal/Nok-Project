# Laravel Scheduler Setup for Hostinger

## ✅ Yes, Laravel Scheduler Works on Hostinger!

Laravel's task scheduler works perfectly fine on Hostinger shared hosting. You just need to set up a cron job in the Hostinger control panel.

---

## 📋 Step-by-Step Setup on Hostinger

### Step 1: Access Hostinger Control Panel (hPanel)

1. Log in to your Hostinger account
2. Go to your hosting dashboard
3. Click on **"Advanced"** → **"Cron Jobs"**

### Step 2: Create the Cron Job

In the Cron Jobs section, you'll need to add a new cron job with these settings:

#### **Common Settings:**
- **Minute:** `*`
- **Hour:** `*`
- **Day:** `*`
- **Month:** `*`
- **Weekday:** `*`

This translates to: **Run every minute** (`* * * * *`)

#### **Command to Execute:**

Replace `/home/YOUR_USERNAME/domains/YOUR_DOMAIN/public_html` with your actual path:

```bash
cd /home/YOUR_USERNAME/domains/YOUR_DOMAIN/public_html && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

**Example:**
If your Hostinger username is `u123456789` and domain is `nok-kuwait.com`:

```bash
cd /home/u123456789/domains/nok-kuwait.com/public_html && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

### Step 3: Find Your Exact Path on Hostinger

If you're not sure about your path, connect via SSH or File Manager:

1. **Via File Manager:**
   - Go to Hostinger → File Manager
   - The path is shown at the top (usually `/home/uXXXXXXXXX/domains/yourdomain.com/public_html`)

2. **Via SSH:**
   ```bash
   pwd
   ```

### Step 4: Find PHP Binary Path

Hostinger usually has PHP at `/usr/bin/php`, but to confirm:

1. **Via SSH:**
   ```bash
   which php
   ```

2. **Common Hostinger PHP paths:**
   - `/usr/bin/php` (most common)
   - `/usr/local/bin/php`
   - `/opt/alt/php82/usr/bin/php` (if using specific PHP version)

---

## 🎯 Complete Cron Job Examples

### Option 1: Simple (Recommended)
```bash
* * * * * cd /home/u123456789/domains/nok-kuwait.com/public_html && php artisan schedule:run >> /dev/null 2>&1
```

### Option 2: With Full PHP Path
```bash
* * * * * cd /home/u123456789/domains/nok-kuwait.com/public_html && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

### Option 3: With Output Logging (For Debugging)
```bash
* * * * * cd /home/u123456789/domains/nok-kuwait.com/public_html && php artisan schedule:run >> /home/u123456789/cron.log 2>&1
```

---

## 🔍 What This Cron Job Does

1. **Runs every minute:** Laravel's scheduler checks what tasks need to run
2. **Executes scheduled tasks:** Based on your schedule in `routes/console.php`
3. **Your renewal reminders:** Will run daily at 08:00 as configured

**Current Schedule:**
```php
// From routes/console.php
Schedule::command('members:send-renewal-reminders')->dailyAt('08:00');
```

This means:
- Cron runs every minute
- Laravel checks if it's 08:00
- If yes, sends renewal reminder emails
- If no, does nothing

---

## ✅ Verify It's Working

### Method 1: Check Cron Job Logs (Recommended)

1. Temporarily change the cron command to log output:
   ```bash
   * * * * * cd /home/u123456789/domains/nok-kuwait.com/public_html && php artisan schedule:run >> /home/u123456789/cron.log 2>&1
   ```

2. Wait a few minutes, then check the log file via File Manager or SSH:
   ```bash
   tail -f /home/u123456789/cron.log
   ```

3. You should see:
   ```
   No scheduled commands are ready to run.
   ```
   (This is normal - it means the scheduler is working, just waiting for 08:00)

### Method 2: Check Laravel Logs

After 08:00 AM, check your Laravel logs:
```bash
tail -f storage/logs/laravel.log
```

### Method 3: Test Manually via SSH

If you have SSH access:

```bash
cd /home/u123456789/domains/nok-kuwait.com/public_html
php artisan schedule:run
```

You should see output like:
```
No scheduled commands are ready to run.
```

Or if it's 08:00:
```
Running scheduled command: Artisan::call("members:send-renewal-reminders")
```

### Method 4: Test the Reminder Command Directly

```bash
php artisan members:send-renewal-reminders
```

This will show you if emails are being sent:
```
✓ Sent to John Doe (john@example.com) - 30 days before expiry
✓ Sent to Jane Smith (jane@example.com) - 15 days before expiry
Renewal reminders sent: 2
```

---

## 🚨 Common Issues & Solutions

### Issue 1: "Permission Denied"

**Solution:** Make sure the cron job runs as your user:
```bash
* * * * * cd /home/u123456789/domains/nok-kuwait.com/public_html && php artisan schedule:run >> /dev/null 2>&1
```

### Issue 2: "Command not found"

**Solution:** Use full path to PHP:
```bash
* * * * * cd /home/u123456789/domains/nok-kuwait.com/public_html && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

### Issue 3: "Cron runs but emails not sending"

**Check:**
1. Is it 08:00? (The scheduler only runs at that time)
2. Are there members with cards expiring in exactly 30, 15, 7, 1, or 0 days?
3. Do members have `login_status = 'approved'`?
4. Is email configured properly? (Check `.env` file)

**Test manually:**
```bash
php artisan members:send-renewal-reminders
```

### Issue 4: "Storage permissions"

Laravel needs write access to `storage/` and `bootstrap/cache/`:
```bash
chmod -R 775 storage bootstrap/cache
```

---

## 📧 Email Configuration Check

Make sure your `.env` file has email settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=your-email@yourdomain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=your-email@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Hostinger SMTP Settings:**
- Host: `smtp.hostinger.com`
- Port: `465` (SSL) or `587` (TLS)
- Username: Your full email address
- Password: Your email password

---

## 🎯 Quick Setup Checklist

- [ ] Log in to Hostinger hPanel
- [ ] Go to Advanced → Cron Jobs
- [ ] Add cron job: `* * * * *`
- [ ] Set command with your correct path
- [ ] Save the cron job
- [ ] Wait 2-3 minutes
- [ ] Check logs to verify it's running
- [ ] Test manually: `php artisan members:send-renewal-reminders`
- [ ] Verify email settings in `.env`
- [ ] Check storage permissions

---

## 📊 What Happens Next

Once set up:

1. **Every minute:** Cron checks if any scheduled tasks should run
2. **Daily at 08:00:** Your renewal reminder command executes
3. **Automatic emails:** Members get reminders at 30, 15, 7, 1, and 0 days before expiry
4. **Logged in database:** All sent emails are recorded in `renewal_reminders` table
5. **Visible in admin:** Check sent emails at `/admin/reminder-emails`

---

## 🔧 Alternative: If Cron Jobs Don't Work

Some shared hosting providers restrict cron jobs. If Hostinger doesn't allow it:

### Option 1: Use External Cron Service

1. Sign up for a free service like:
   - [cron-job.org](https://cron-job.org)
   - [EasyCron](https://www.easycron.com)

2. Create a special route in your app to trigger the scheduler
3. Have the external service ping that URL every minute

### Option 2: Trigger via Website Route (Not Recommended)

Create a protected route that runs the scheduler (less reliable).

---

## 💡 Pro Tips

1. **Test during off-hours:** Run the command manually first to ensure emails work
2. **Monitor logs:** Keep an eye on logs for the first few days
3. **Set reminders:** Put a note to check the system after the first scheduled run
4. **Email quota:** Check if Hostinger has daily email limits (usually 100-500/day)

---

## 📞 Need Help?

If you encounter issues:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Test command manually: `php artisan members:send-renewal-reminders`
3. Verify cron is running: Check cron logs
4. Contact Hostinger support if cron job section is unavailable

---

## ✅ Summary

**Yes, it works on Hostinger!** Just add this cron job in hPanel:

```bash
* * * * * cd /home/YOUR_USERNAME/domains/YOUR_DOMAIN/public_html && php artisan schedule:run >> /dev/null 2>&1
```

Replace `YOUR_USERNAME` and `YOUR_DOMAIN` with your actual values, and you're done! 🎉

