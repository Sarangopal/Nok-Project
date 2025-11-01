# 📅 Renewal Reminder Scheduler Setup Guide

This guide explains how to set up automatic renewal reminder emails that run daily.

---

## 🔧 How It Works

The system automatically sends renewal reminder emails to members at the following intervals:

- **30 days before** card expiry
- **15 days before** card expiry
- **7 days before** card expiry
- **1 day before** card expiry
- **On expiry day** (expired today)

---

## ⚙️ Configuration

### 1. Scheduler Configuration

The scheduler is configured in `routes/console.php`:

```php
Schedule::command('members:send-renewal-reminders')
    ->dailyAt('08:00')
    ->timezone('Asia/Kuwait')
    ->name('Send Renewal Reminder Emails')
    ->description('Automatically sends renewal reminder emails to members')
    ->emailOutputOnFailure('admin@yourdomain.com');
```

**Settings:**
- **Frequency:** Daily at 08:00 AM (Kuwait time)
- **Timezone:** Asia/Kuwait
- **Failure Notifications:** Sends email to admin if command fails

---

### 2. Reminder Command

The command is located at: `app/Console/Commands/SendRenewalReminders.php`

**Features:**
- ✅ Sends reminders for multiple intervals (30, 15, 7, 1, 0 days)
- ✅ Prevents duplicate reminders
- ✅ Logs all sent emails to database
- ✅ Handles errors gracefully
- ✅ Tracks failed deliveries

---

## 🚀 Production Setup

### Step 1: Enable Laravel Scheduler (Cron Job)

On your production server (Hostinger, AWS, etc.), add this cron job:

```bash
* * * * * cd /path/to/nok-kuwait && php artisan schedule:run >> /dev/null 2>&1
```

**Example for Hostinger:**
1. Login to cPanel
2. Go to **Cron Jobs**
3. Add new cron job with command:
   ```
   * * * * * cd /home/username/public_html && php artisan schedule:run >> /dev/null 2>&1
   ```

**Important:**
- Replace `/home/username/public_html` with your actual project path
- This cron runs **every minute** and Laravel checks if any scheduled tasks need to run
- The scheduler itself runs the reminder command **daily at 08:00 AM**

---

### Step 2: Verify Scheduler is Working

Check if scheduler is configured correctly:

```bash
php artisan schedule:list
```

**Expected Output:**
```
  0 8 * * *  members:send-renewal-reminders ....................... Send Renewal Reminder Emails
              Next Due: Tomorrow at 08:00 AM
```

---

### Step 3: Test Manual Execution

Run the command manually to test:

```bash
php artisan members:send-renewal-reminders
```

**With verbose output:**
```bash
php artisan members:send-renewal-reminders --verbose
```

**For specific days only:**
```bash
php artisan members:send-renewal-reminders --days=30,15,7
```

---

## 📧 Email Configuration

### 1. Configure Mail Driver

Update `.env` with your email settings:

**For Gmail:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="NOK Kuwait"
```

**For Mailgun:**
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.com
MAILGUN_SECRET=your-mailgun-secret
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="NOK Kuwait"
```

**For SendGrid:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="NOK Kuwait"
```

---

### 2. Test Email Sending

Send a test email:

```php
php artisan tinker

>>> Mail::raw('Test email', function($msg) {
        $msg->to('developer1.bten@gmail.com')->subject('Test');
    });
```

If emails send successfully, the reminder system will work correctly.

---

## 🔍 Monitoring & Debugging

### 1. Check Scheduled Tasks Status

```bash
php artisan schedule:list
```

Shows all scheduled commands and their next run time.

---

### 2. View Reminder Logs

Check database table: `renewal_reminders`

**Via Tinker:**
```php
php artisan tinker

>>> App\Models\RenewalReminder::latest()->take(10)->get(['member_name', 'email', 'days_before_expiry', 'status', 'created_at']);
```

---

### 3. Admin Dashboard

View all sent reminders in admin panel:
- Navigate to: **Admin → Memberships → Reminder Emails**
- Filter by: Reminder Type, Status, Date
- Export for reporting

---

### 4. Check Laravel Logs

```bash
tail -f storage/logs/laravel.log
```

Look for:
- `[RENEWAL REMINDERS]` log entries
- Email sending errors
- Database errors

---

## 🛠️ Troubleshooting

### Problem: Reminders Not Sending

**Solution 1: Check Cron Job**
```bash
# Verify cron is running
sudo service cron status

# Check cron logs
grep CRON /var/log/syslog
```

**Solution 2: Check Laravel Scheduler**
```bash
# Run scheduler manually
php artisan schedule:run
```

**Solution 3: Check Permissions**
```bash
# Ensure Laravel can write logs
chmod -R 775 storage/logs
chown -R www-data:www-data storage
```

---

### Problem: Emails Going to Spam

**Solutions:**
1. **Use a verified domain** (not Gmail) for sending emails
2. **Set up SPF, DKIM, and DMARC records**
3. **Use a transactional email service** (Mailgun, SendGrid, AWS SES)
4. **Warm up your domain** by gradually increasing email volume

---

### Problem: Duplicate Reminders

The system prevents duplicates by checking:
- Registration ID
- Card expiry date
- Days before expiry
- Status = 'sent'

If duplicates occur, check database table `renewal_reminders` for integrity.

---

## 📊 Statistics & Reporting

### Daily Reminder Report

Create a daily report command:

```php
php artisan tinker

>>> App\Models\RenewalReminder::whereDate('created_at', today())
        ->groupBy('days_before_expiry')
        ->selectRaw('days_before_expiry, count(*) as count')
        ->get();
```

Example output:
```
30 days before: 5 emails
15 days before: 3 emails
7 days before: 2 emails
1 day before: 1 email
Total: 11 emails sent today
```

---

## 🎯 Best Practices

1. **Monitor Daily:**
   - Check admin panel for sent reminders
   - Verify no failed deliveries
   - Review member feedback

2. **Email Content:**
   - Keep subject lines clear: "Your NOK Kuwait Membership Expires in X Days"
   - Include renewal link
   - Add contact information

3. **Timing:**
   - Current: 08:00 AM Kuwait time
   - Adjust if needed based on member activity patterns
   - Avoid weekends if preferred

4. **Backup:**
   - Export reminder logs monthly
   - Keep records for audit purposes
   - Archive old reminders (older than 1 year)

---

## 📝 Testing Checklist

Before deploying to production:

- [ ] Cron job configured and running
- [ ] Scheduler appears in `php artisan schedule:list`
- [ ] Manual command execution successful
- [ ] Email sending works (test with real email)
- [ ] Emails not going to spam
- [ ] Database logging working
- [ ] Admin panel displays reminders correctly
- [ ] Duplicate prevention working
- [ ] Error handling tested
- [ ] Timezone configured correctly

---

## 🔗 Related Files

| File | Purpose |
|------|---------|
| `routes/console.php` | Scheduler configuration |
| `app/Console/Commands/SendRenewalReminders.php` | Reminder command |
| `app/Models/RenewalReminder.php` | Database model |
| `app/Mail/RenewalReminderMail.php` | Email template |
| `resources/views/emails/membership/renewal_reminder.blade.php` | Email view |
| `database/migrations/*_create_renewal_reminders_table.php` | Database schema |

---

## 📞 Support

For issues or questions:
- Check Laravel logs: `storage/logs/laravel.log`
- Review cron logs: `/var/log/syslog`
- Admin panel: "Reminder Emails" section
- Database: `renewal_reminders` table

---

**Last Updated:** November 1, 2025  
**Version:** 1.0

