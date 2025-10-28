# 🚀 Deployment Checklist: Development → Production

## 📊 What Works Where?

| Feature | Development (Localhost) | Production (Hostinger) |
|---------|------------------------|------------------------|
| Application Code | ✅ Works | ✅ Works (after deployment) |
| Manual Commands | ✅ Works | ✅ Works |
| **Automatic Scheduler** | ❌ **Doesn't run automatically** | ✅ **Works IF cron job set up** |
| Email Sending | ⚠️ Testing mode | ✅ Real emails |

---

## 🎯 Complete Deployment Checklist

### ✅ Phase 1: Code Deployment

- [ ] Upload all files to Hostinger
- [ ] Set up `.env` file with production settings
- [ ] Run database migrations
- [ ] Install Composer dependencies
- [ ] Set correct file permissions

### ✅ Phase 2: Email Configuration

Update your production `.env` file:

```env
# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Email Settings (CRITICAL for reminders!)
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=noreply@your-domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="NOK Kuwait"

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### ✅ Phase 3: Set Up Cron Job (REQUIRED!)

**This is the KEY step that makes it automatic!**

1. **Log in to Hostinger hPanel**
2. **Go to: Advanced → Cron Jobs**
3. **Add new cron job:**

   ```bash
   * * * * * cd /home/YOUR_USERNAME/domains/YOUR_DOMAIN/public_html && php artisan schedule:run >> /dev/null 2>&1
   ```

   **Example:**
   ```bash
   * * * * * cd /home/u123456789/domains/nok-kuwait.com/public_html && php artisan schedule:run >> /dev/null 2>&1
   ```

4. **Save the cron job**

---

## 🔍 Step-by-Step: What Happens After Deployment

### In Development (Before Deployment)
```
❌ Scheduler does NOT run automatically
✅ You can test manually: php artisan members:send-renewal-reminders
✅ Code is ready, but waits for manual trigger
```

### In Production (After Setting Up Cron)
```
✅ Every minute: Cron job runs schedule:run
✅ At 08:00 AM: Laravel checks "Should I send reminders?"
✅ If YES: Emails are sent automatically
✅ Logged: All emails recorded in database
✅ Visible: Check /admin/reminder-emails
```

---

## 🎬 Timeline: What You Need to Do

### Step 1: Deploy Your Code to Hostinger
- Upload files via FTP/Git
- Set up database
- Configure `.env` file
- Test website works

### Step 2: Configure Email (Production)
- Update `.env` with Hostinger SMTP settings
- Test email sending manually:
  ```bash
  php artisan tinker
  Mail::raw('Test', function($msg) { 
      $msg->to('your-email@example.com')->subject('Test'); 
  });
  ```

### Step 3: Set Up Cron Job (THE CRITICAL STEP!)
- Add cron job in Hostinger hPanel
- This makes it **AUTOMATIC**

### Step 4: Verify It's Working
- Wait until next day at 08:00 AM
- Check `/admin/reminder-emails` for sent emails
- Or test manually: `php artisan members:send-renewal-reminders`

---

## ⚡ Quick Test After Deployment

Once you've deployed and set up the cron job, test it:

### Test 1: Check Cron is Running
Via SSH:
```bash
cd /home/YOUR_USERNAME/domains/YOUR_DOMAIN/public_html
php artisan schedule:list
```

Expected output:
```
  0 8 * * *  members:send-renewal-reminders ... Next Due: Tomorrow at 08:00
```

### Test 2: Manual Email Test
```bash
php artisan members:send-renewal-reminders
```

If members are due for reminders, you'll see:
```
✓ Sent to John Doe (john@example.com) - 30 days before expiry
Renewal reminders sent: 1
```

### Test 3: Check Logs
```bash
tail -f storage/logs/laravel.log
```

Look for successful email sending logs.

---

## 🚨 Common Deployment Mistakes

### ❌ Mistake 1: Forgetting the Cron Job
**Problem:** Code is deployed but no cron job set up  
**Result:** Nothing happens automatically  
**Fix:** Add the cron job in Hostinger hPanel

### ❌ Mistake 2: Wrong Email Configuration
**Problem:** `.env` still has development email settings  
**Result:** Emails fail to send  
**Fix:** Update `MAIL_*` settings in production `.env`

### ❌ Mistake 3: Wrong File Permissions
**Problem:** Laravel can't write to `storage/` or `bootstrap/cache/`  
**Result:** Scheduler fails silently  
**Fix:** 
```bash
chmod -R 775 storage bootstrap/cache
chown -R YOUR_USERNAME:YOUR_USERNAME storage bootstrap/cache
```

### ❌ Mistake 4: Wrong Cron Path
**Problem:** Cron job points to wrong directory  
**Result:** Command not found  
**Fix:** Verify the exact path using File Manager or `pwd` in SSH

---

## ✅ Production Readiness Checklist

Before you consider it "production ready":

### Code & Configuration
- [ ] All code uploaded to Hostinger
- [ ] `.env` file configured for production
- [ ] Database migrated and seeded
- [ ] File permissions set correctly (775 for storage/)
- [ ] Composer dependencies installed (`composer install --no-dev`)

### Email System
- [ ] Production email credentials in `.env`
- [ ] Test email sent successfully
- [ ] `MAIL_FROM_ADDRESS` is a real, working email
- [ ] Email quota sufficient (check Hostinger limits)

### Scheduler System
- [ ] Cron job added in Hostinger hPanel
- [ ] Cron job command verified (correct path)
- [ ] `php artisan schedule:list` shows your command
- [ ] Manual test of reminder command works

### Verification
- [ ] Website accessible via domain
- [ ] Admin panel login works
- [ ] Database queries work
- [ ] Manual reminder test successful
- [ ] Logs show no errors

---

## 📅 Post-Deployment Monitoring

### Day 1 (Deployment Day)
- [ ] Verify cron job is active
- [ ] Check logs for any errors
- [ ] Test manual reminder command

### Day 2 (After First Auto-Run)
- [ ] Check at 08:05 AM if reminders ran
- [ ] Visit `/admin/reminder-emails` to see sent emails
- [ ] Check `storage/logs/laravel.log` for confirmation

### Week 1
- [ ] Monitor daily for successful runs
- [ ] Verify emails are being received
- [ ] Check no duplicate emails sent

---

## 🎯 Summary: What Makes It Automatic?

```
Development (Localhost):
├── ❌ No cron job
├── ❌ Scheduler doesn't run automatically
└── ✅ Manual testing only

Production (Hostinger):
├── ✅ Cron job set up in hPanel
├── ✅ Runs every minute: schedule:run
├── ✅ At 08:00 AM: Automatic reminders
└── ✅ Fully automatic system
```

---

## 🔑 The ONE Thing You MUST Do

**Set up the cron job on Hostinger!**

Without it:
- ❌ Code is there but dormant
- ❌ Nothing runs automatically
- ❌ Manual intervention needed daily

With it:
- ✅ Fully automatic
- ✅ Runs daily at 08:00 AM
- ✅ No manual work needed
- ✅ Set it and forget it

---

## 📞 Still Confused?

**Simple Answer:**

1. Deploy your code → ✅ You probably already did this
2. Set up cron job → ⚠️ **THIS IS THE MISSING PIECE**
3. Wait until 08:00 AM → ⏰ Automatic emails start

**The cron job is what makes it "automatic"!**

See:
- `QUICK_SETUP_CRON.md` - Fast setup guide
- `DEPLOYMENT_HOSTINGER.md` - Detailed instructions

