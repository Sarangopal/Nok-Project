# 📧 Email Configuration Guide
**Nightingales of Kuwait - Membership Management System**

---

## 🎯 Quick Setup (Gmail - Recommended)

### Step 1: Get Gmail App Password

1. Go to your Google Account: https://myaccount.google.com/security
2. Enable **2-Step Verification** (if not already enabled)
3. Go to **App Passwords**: https://myaccount.google.com/apppasswords
4. Select:
   - App: **Mail**
   - Device: **Other (Custom name)**
5. Enter name: **NOK System**
6. Click **Generate**
7. Copy the 16-character password (remove spaces)

### Step 2: Update `.env` File

Open your `.env` file and add/update these lines:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_16_char_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="Nightingales of Kuwait"
```

**Replace:**
- `your_email@gmail.com` → Your actual Gmail address
- `your_16_char_app_password` → The password from Step 1

### Step 3: Clear Configuration Cache

```bash
php artisan config:clear
php artisan cache:clear
```

### Step 4: Test Email Sending

```bash
php artisan tinker
```

Then run:
```php
Mail::raw('Test email from NOK System', function($msg) { 
    $msg->to('test@example.com')->subject('Test Email'); 
});
```

If you see `= true`, email configuration is working! ✅

---

## 📧 Email Types in the System

### 1. **Membership Approval Email**
- **When:** Admin approves a new registration
- **Contains:**
  - Member details (Name, NOK ID, Civil ID)
  - Login credentials (Email, Civil ID, Password)
  - Membership card PDF attachment
  - Link to member dashboard
- **Template:** `resources/views/emails/membership/card.blade.php`

### 2. **Renewal Approval Email**
- **When:** Admin approves a renewal request
- **Contains:**
  - Renewed membership details
  - Updated expiry date
  - Renewed membership card PDF
- **Template:** Same as above (detects renewal automatically)

### 3. **Renewal Reminder Emails**
- **When:** Automatic reminders before card expiry
- **Schedule:**
  - 30 days before expiry
  - 15 days before expiry
  - 7 days before expiry
  - 1 day before expiry
- **Template:** `resources/views/emails/membership/renewal_reminder.blade.php`

### 4. **Registration Confirmation**
- **When:** Member submits new registration form
- **Contains:** Confirmation of submission, next steps
- **Template:** `resources/views/emails/membership/registration_confirmation.blade.php`

---

## 🔄 Alternative Email Providers

### Option 2: SendGrid (Free 100 emails/day)

1. Sign up at https://sendgrid.com
2. Get your API key
3. Update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=YOUR_SENDGRID_API_KEY
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@nokkw.org
MAIL_FROM_NAME="Nightingales of Kuwait"
```

### Option 3: Mailtrap (Testing Only)

Perfect for testing without sending real emails:

1. Sign up at https://mailtrap.io
2. Get your credentials from the inbox
3. Update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@nokkw.org
MAIL_FROM_NAME="Nightingales of Kuwait"
```

---

## ⚡ Enable Queues for Better Performance (Optional)

### Why Use Queues?
- Emails send in background (faster page load)
- Failed emails can be retried automatically
- Better user experience

### Setup:

1. Update `.env`:
```env
QUEUE_CONNECTION=database
```

2. Run migrations (if not already run):
```bash
php artisan queue:table
php artisan migrate
```

3. Start queue worker:
```bash
php artisan queue:work
```

4. For production, use Supervisor to keep queue worker running:
```bash
sudo apt install supervisor
```

Create `/etc/supervisor/conf.d/nok-worker.conf`:
```ini
[program:nok-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/your/project/storage/logs/worker.log
```

---

## 🕐 Schedule Renewal Reminders

### For Development:
```bash
php artisan schedule:work
```

### For Production (Cron Job):

Add to crontab:
```bash
crontab -e
```

Add this line:
```
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

This runs the Laravel scheduler every minute, which handles:
- Renewal reminders (30, 15, 7, 1 days before expiry)
- Any other scheduled tasks

---

## 🧪 Testing Emails

### Test 1: Send Test Email
```bash
php artisan tinker
```
```php
Mail::raw('Test from NOK', function($m) { 
    $m->to('your@email.com')->subject('Test'); 
});
```

### Test 2: Test Membership Approval Email
```php
$member = App\Models\Registration::find(1);
Mail::to($member->email)->send(new App\Mail\MembershipCardMail([
    'record' => $member,
    'password' => 'NOK1234'
]));
```

### Test 3: Test Renewal Reminder
```php
$member = App\Models\Registration::where('renewal_status', 'approved')->first();
Mail::to($member->email)->send(new App\Mail\RenewalReminderMail($member));
```

---

## 🐛 Troubleshooting

### Issue 1: "Connection could not be established"
**Solution:** Check firewall, ensure port 587 is open
```bash
telnet smtp.gmail.com 587
```

### Issue 2: "Invalid credentials"
**Solution:** 
- Verify app password (not your regular Gmail password)
- Check 2-Step Verification is enabled
- Regenerate app password

### Issue 3: "Failed to authenticate on SMTP server"
**Solution:**
- Clear config cache: `php artisan config:clear`
- Check `.env` has correct credentials
- Try port 465 with `MAIL_ENCRYPTION=ssl`

### Issue 4: Emails not sending (no error)
**Solution:**
- Check `storage/logs/laravel.log` for errors
- Verify `MAIL_FROM_ADDRESS` is valid
- Test with `php artisan tinker`

### Issue 5: PDF attachment not showing
**Solution:**
- Run `php artisan storage:link`
- Check QR codes exist: `ls storage/app/public/members/qrcodes/`
- Verify `dompdf` is installed: `composer show | grep dompdf`

---

## 📊 Email Logs

Check email activity:
```bash
tail -f storage/logs/laravel.log
```

Filter for email errors:
```bash
grep -i "mail" storage/logs/laravel.log
```

---

## ✅ Verification Checklist

- [ ] Gmail App Password generated
- [ ] `.env` updated with email credentials
- [ ] Config cache cleared
- [ ] Test email sent successfully
- [ ] Membership approval email tested
- [ ] PDF attachment received correctly
- [ ] QR codes display in PDF
- [ ] Renewal reminder schedule configured
- [ ] Queue worker running (if using queues)
- [ ] Cron job added for scheduler (production)

---

## 🚀 Production Recommendations

1. **Use a dedicated email service** (SendGrid, Mailgun, Amazon SES)
2. **Enable queues** for better performance
3. **Set up monitoring** for failed emails
4. **Add email logging** to track all sent emails
5. **Configure SPF and DKIM** records for better deliverability
6. **Use professional domain** (noreply@nokkw.org instead of Gmail)

---

## 📞 Support

If you encounter issues:
1. Check `storage/logs/laravel.log`
2. Verify `.env` configuration
3. Test with simple `Mail::raw()` first
4. Check email provider documentation

**Ready to send emails?** Start with the Quick Setup section above! 🎉






