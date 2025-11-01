# ⚡ Quick Cron Job Setup for Hostinger

## 🎯 What You Need To Do (5 Minutes)

### 1️⃣ Log in to Hostinger
- Go to [hpanel.hostinger.com](https://hpanel.hostinger.com)
- Select your hosting account

### 2️⃣ Open Cron Jobs
```
Dashboard → Advanced → Cron Jobs
```

### 3️⃣ Add New Cron Job

**Frequency:** Every Minute
```
Minute: *
Hour: *
Day: *
Month: *
Weekday: *
```

**Command:**
```bash
cd /home/YOUR_USERNAME/domains/YOUR_DOMAIN/public_html && php artisan schedule:run >> /dev/null 2>&1
```

**⚠️ HOW TO FIND YOUR USERNAME & PATH:**

**EASIEST METHOD:**
1. In Hostinger hPanel, go to **Files → File Manager**
2. Look at the **top path bar** - it shows:
   ```
   /home/u987654321/domains/nok-kuwait.com/public_html
   ```
3. **Copy that EXACT path!** That's your full path.
4. `u987654321` is your username (the `uXXXXXXXXX` part)

**Then replace:**
- `YOUR_USERNAME` → Your actual username (e.g., `u987654321`)
- `YOUR_DOMAIN` → Your domain (e.g., `nok-kuwait.com`)

**Or better yet, just use the ENTIRE path from File Manager!**

### 4️⃣ Save & Done! ✅

---

## 📝 Example

If your:
- Username: `u987654321`
- Domain: `nok-kuwait.com`

Then your command is:
```bash
cd /home/u987654321/domains/nok-kuwait.com/public_html && php artisan schedule:run >> /dev/null 2>&1
```

---

## ✅ How to Verify It's Working

### Option A: Via SSH (Recommended)
```bash
# Connect to SSH, then run:
cd /home/YOUR_USERNAME/domains/YOUR_DOMAIN/public_html
php artisan schedule:list
```

You should see:
```
  0 8 * * *  members:send-renewal-reminders ........ Next Due: Tomorrow at 08:00
```

### Option B: Check Logs Tomorrow
After 08:00 AM tomorrow, check:
```
http://127.0.0.1:8000/admin/reminder-emails
```

You should see new reminder emails if there are members expiring in 30/15/7/1/0 days.

### Option C: Test Manually Right Now
Via SSH:
```bash
php artisan members:send-renewal-reminders
```

See immediate results if any emails match today's criteria.

---

## 🆘 Can't Find Your Path?

### Via Hostinger File Manager (EASIEST!):
1. Go to **Files → File Manager**
2. **The path is shown at the top bar** (usually in gray text)
3. It looks like: `/home/u987654321/domains/nok-kuwait.com/public_html`
4. **Click on it to select, then Ctrl+C to copy**
5. Use that EXACT path in your cron command

**Visual Guide:**
```
┌────────────────────────────────────────────────┐
│ Hostinger File Manager                         │
├────────────────────────────────────────────────┤
│ 📍 Path: /home/u987654321/domains/           │ ← This path!
│          nok-kuwait.com/public_html            │
├────────────────────────────────────────────────┤
│ 📁 app/                                        │
│ 📁 bootstrap/                                  │
│ 📁 config/                                     │
│ 📄 artisan                                     │
└────────────────────────────────────────────────┘
```

### Via SSH:
```bash
pwd
```

### Via FTP Details:
1. **Hostinger hPanel → Files → FTP Accounts**
2. Look for your **username** (e.g., `u987654321`)

Typical Hostinger paths:
```
/home/uXXXXXXXXX/domains/yourdomain.com/public_html
```

**Note:** The `u123456789` in examples is just a placeholder - use YOUR actual username!

---

## ❓ FAQ

**Q: Will this cost extra?**  
A: No, cron jobs are included in Hostinger hosting.

**Q: How often do emails send?**  
A: Daily at 08:00 AM (Kuwait time).

**Q: Can I change the time?**  
A: Yes! Edit `routes/console.php`:
```php
Schedule::command('members:send-renewal-reminders')
    ->dailyAt('10:00')  // Change to 10:00 AM
```

**Q: What if cron jobs are disabled?**  
A: Contact Hostinger support or see `DEPLOYMENT_HOSTINGER.md` for alternatives.

**Q: How do I know if emails are actually sending?**  
A: Check the "Reminder Emails" page in your admin panel after 08:00 AM.

---

## 📚 More Details

For complete setup instructions, troubleshooting, and advanced options:  
👉 See **DEPLOYMENT_HOSTINGER.md**

---

**That's it! You're done!** 🎉

The system will now automatically send renewal reminder emails every day at 08:00 AM.

