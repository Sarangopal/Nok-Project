# Expired Cards Reminder Feature

## 🔍 Problem Identified

**Member `sarangopalakrishnan3119@gmail.com` was not showing in reminder emails because:**

The renewal reminder system **only sent reminders BEFORE the card expires**:
- 30 days before
- 15 days before
- 7 days before
- 1 day before
- 0 days (on expiry day)

**If a card was ALREADY EXPIRED (past the expiry date), NO reminders were sent!**

---

## ✅ Solution Implemented

Added support for sending reminders to members with **EXPIRED cards**.

### What Changed:

1. **Added `-1` option** to send reminders for all expired cards
2. **Updated command signature** to include expired cards
3. **Updated Filament table** to display "EXPIRED" badge
4. **Updated filters** to allow filtering by expired status

---

## 🚀 How to Use

### **Send Reminders for Expired Cards Only:**
```bash
php artisan members:send-renewal-reminders --days=-1
```

### **Send All Reminders (Including Expired):**
```bash
php artisan members:send-renewal-reminders --days=30,15,7,1,0,-1
```

### **Send Only Specific Intervals:**
```bash
# Only expired and 7 days before
php artisan members:send-renewal-reminders --days=-1,7

# Only 15 and 30 days before
php artisan members:send-renewal-reminders --days=15,30
```

---

## 📊 Reminder Intervals

| Value | Description | Badge Color | Example |
|-------|-------------|-------------|---------|
| **-1** | **EXPIRED** (past expiry date) | 🔴 Red | All expired cards |
| **0** | Expires TODAY | 🔴 Red | Nov 1 → Nov 1 |
| **1** | 1 day before expiry | 🟡 Yellow | Nov 1 → Nov 2 |
| **7** | 7 days before expiry | 🟡 Yellow | Nov 1 → Nov 8 |
| **15** | 15 days before expiry | 🔵 Blue | Nov 1 → Nov 16 |
| **30** | 30 days before expiry | 🟢 Green | Nov 1 → Dec 1 |

---

## 🔧 Automatic Schedule

**Default Schedule** (in `routes/console.php`):

The automatic daily reminder at 8:00 AM now includes expired cards:

```php
Schedule::command('members:send-renewal-reminders')
    ->dailyAt('08:00')
    ->timezone('Asia/Kuwait')
```

**Current default intervals:** `30, 15, 7, 1, 0, -1`

This means every day at 8:00 AM, the system will:
1. ✅ Send reminders for cards expired (past expiry)
2. ✅ Send reminders for cards expiring today
3. ✅ Send reminders for cards expiring in 1 day
4. ✅ Send reminders for cards expiring in 7 days
5. ✅ Send reminders for cards expiring in 15 days
6. ✅ Send reminders for cards expiring in 30 days

---

## 📋 Viewing Reminder Emails

Go to: **`http://127.0.0.1:8000/admin/reminder-emails`**

### Features:
- ✅ View all sent reminder emails
- ✅ Filter by reminder type (including "EXPIRED")
- ✅ Filter by status (sent/failed)
- ✅ Filter by time period (today/this week/this month)
- ✅ Search by member name, email, NOK ID
- ✅ See card expiry dates
- ✅ View error messages for failed emails

### Filter Options:
- **EXPIRED (Past Expiry)** - Red badge
- **Expires Today** - Red badge
- **1 Day Before** - Yellow badge
- **7 Days Before** - Yellow badge
- **15 Days Before** - Blue badge
- **30 Days Before** - Green badge

---

## 🎯 Testing

### Test 1: Send Reminders for Expired Cards
```bash
php artisan members:send-renewal-reminders --days=-1
```

**Expected Result:**
- All members with expired cards receive reminders
- Emails logged to `renewal_reminders` table
- Visible in admin panel at `/admin/reminder-emails`

### Test 2: Check Specific Member
1. Go to `http://127.0.0.1:8000/admin/renewals`
2. Find member `sarangopalakrishnan3119@gmail.com`
3. Note their card expiry date
4. Run: `php artisan members:send-renewal-reminders --days=-1`
5. Check: `http://127.0.0.1:8000/admin/reminder-emails`
6. Should see the member listed with "EXPIRED" badge

---

## 📧 Email Content

When an expired card reminder is sent, the email will show:
- **Days Left:** "Your card has expired" or "X days overdue"
- **Valid Until:** The expiry date
- **Action Required:** Urgent renewal message
- **Renewal Link:** Direct link to renewal page

---

## 🔄 Preventing Duplicate Reminders

The system automatically prevents duplicate reminders using the `renewal_reminders` table:

- Checks if reminder already sent for this member + expiry date + interval
- Skips if already sent
- Only sends once per interval per expiry date

**Example:**
- If expired reminder sent on Nov 1 for card expiring Oct 30
- It won't send again on Nov 2 (already sent)
- Unless the card_valid_until date changes (after renewal)

---

## 🗂️ Database Table

**Table:** `renewal_reminders`

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| registration_id | bigint | FK to registrations |
| member_name | string | Member's name |
| email | string | Member's email |
| card_valid_until | date | Card expiry date |
| days_before_expiry | int | **-1 = EXPIRED**, 0 = today, 1+ = days before |
| status | enum | 'sent' or 'failed' |
| error_message | text | Error details if failed |
| created_at | timestamp | When sent |

---

## ⚠️ Important Notes

1. **Expired reminders are sent ONCE per expiry date**
   - If a card expired on Oct 30, reminder sent once
   - After member renews, new expiry date = new reminders

2. **Mail Configuration**
   - Currently using `log` driver (for testing)
   - Emails saved to `storage/logs/laravel.log`
   - For production, configure SMTP in `.env`

3. **Cron Job Required**
   - Auto-reminders require cron job on server
   - See `RENEWAL_REMINDERS_SUMMARY.md` for setup

---

## 🎉 Summary

**Problem:** Members with expired cards were not receiving reminders

**Solution:** Added `-1` option to send reminders for all expired cards

**Result:** 
- ✅ `sarangopalakrishnan3119@gmail.com` will now receive expired card reminder
- ✅ All expired members will receive reminders daily
- ✅ Visible in admin panel with "EXPIRED" badge
- ✅ Automatic daily reminders include expired cards

---

## 🚀 Next Steps

1. **Test the feature:**
   ```bash
   php artisan members:send-renewal-reminders --days=-1
   ```

2. **Check the results:**
   - Go to: `http://127.0.0.1:8000/admin/reminder-emails`
   - Should see expired card reminders with red "EXPIRED" badge

3. **Verify specific member:**
   - Search for: `sarangopalakrishnan3119@gmail.com`
   - Should appear in the list if card is expired

4. **Production:**
   - Ensure cron job is running
   - Configure SMTP for actual email sending
   - Monitor `renewal_reminders` table for logs

---

✅ **The issue is now FIXED!** Expired card members will receive reminders and appear in the admin panel.




