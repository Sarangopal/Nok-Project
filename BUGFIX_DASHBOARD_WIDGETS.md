# 🐛 Bug Fix: Dashboard Widgets Showing Empty Data

## Issue Found

The dashboard widgets were showing empty/incorrect data because they were filtering by the **wrong status field**.

---

## 🔍 Root Cause

### The Problem:

Your database has **TWO** status fields in the `registrations` table:

1. **`login_status`** - Controls whether a member is approved to access the system
   - Values: `pending`, `approved`, `rejected`
   - Used for: Member registration approval

2. **`renewal_status`** - Tracks renewal requests
   - Values: `pending`, `approved`, `rejected`
   - Used for: Renewal request workflow

### The Bug:

The dashboard widgets were incorrectly using `renewal_status` instead of `login_status`, causing:
- ❌ "Cards Expiring in 30 Days" showing empty
- ❌ "Active Members" count incorrect
- ❌ "Pending Approvals" count incorrect

---

## ✅ What Was Fixed

### 1. ExpiringSoon Widget (`app/Filament/Widgets/ExpiringSoon.php`)

**Before (WRONG):**
```php
Registration::query()
    ->where('renewal_status', 'approved')  // ❌ Wrong field!
    ->whereNotNull('card_valid_until')
    ->whereBetween('card_valid_until', [$now, $limit])
```

**After (CORRECT):**
```php
Registration::query()
    ->where('login_status', 'approved')  // ✅ Correct field!
    ->whereNotNull('card_valid_until')
    ->whereBetween('card_valid_until', [$now, $limit])
```

### 2. StatsOverview Widget (`app/Filament/Widgets/StatsOverview.php`)

**Before (WRONG):**
```php
$approvedMembers = Registration::where('renewal_status', 'approved')->count();  // ❌
$pendingMembers = Registration::where('renewal_status', 'pending')->count();    // ❌
```

**After (CORRECT):**
```php
$approvedMembers = Registration::where('login_status', 'approved')->count();  // ✅
$pendingMembers = Registration::where('login_status', 'pending')->count();    // ✅
```

---

## 📊 Expected Behavior Now

### Dashboard Cards:

1. **Total Members** - Shows all registrations ✅
2. **Active Members** - Shows members with `login_status = 'approved'` ✅
3. **Pending Approvals** - Shows members with `login_status = 'pending'` ✅
4. **Pending Renewals** - Shows renewals with `renewal_status = 'pending'` ✅

### Dashboard Table:

**"Cards Expiring in 30 Days"** - Now shows:
- Members with `login_status = 'approved'`
- Who have a `card_valid_until` date
- Expiring within the next 30 days

---

## 🧪 How to Test the Fix

### Step 1: Refresh the Dashboard
```
Go to: http://127.0.0.1:8000/admin
```

### Step 2: Check the Stats
You should now see:
- **Active Members** count matches approved registrations
- **Pending Approvals** count matches pending registrations

### Step 3: Check the Table
Scroll down to **"Cards Expiring in 30 Days"** table.

**If still empty:**
- It means you have no members with cards expiring in the next 30 days
- This is NORMAL if all your members have cards expiring far in the future or already expired

### Step 4: Verify with Data

Check your database:
```sql
-- Members expiring within 30 days
SELECT memberName, card_valid_until, login_status
FROM registrations
WHERE login_status = 'approved'
  AND card_valid_until IS NOT NULL
  AND card_valid_until BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY);
```

---

## 📋 Understanding the Fields

### When to use `login_status`:
- ✅ Checking if a member is approved/active
- ✅ Dashboard statistics (Active Members, Pending Approvals)
- ✅ Widgets showing member status
- ✅ Filtering members who can login

### When to use `renewal_status`:
- ✅ Tracking renewal requests
- ✅ Renewal request workflow
- ✅ Showing pending renewal approvals
- ✅ Historical renewal tracking

---

## 🎯 Quick Summary

**What was wrong:**
- Dashboard filtering by `renewal_status` instead of `login_status`

**What was fixed:**
- Changed to filter by `login_status` for member-related widgets
- Kept `renewal_status` for renewal-specific widgets

**Result:**
- Dashboard now shows correct data ✅
- "Cards Expiring in 30 Days" works properly ✅
- Member statistics are accurate ✅

---

## ⚠️ Important Note

If the "Cards Expiring in 30 Days" table is still empty, it means:

1. **No members with cards expiring in next 30 days** ✅ (This is normal!)
2. **Members don't have `card_valid_until` set** ⚠️ (Check registration approval process)
3. **Members are not approved yet** ⚠️ (Check login_status)

To check:
```
Go to: http://127.0.0.1:8000/admin/registrations
Filter by: Expiring Soon (≤30d)
```

If you see members there but not on dashboard, clear cache:
```bash
php artisan optimize:clear
```

---

## 🔧 Files Modified

1. `app/Filament/Widgets/ExpiringSoon.php` - Line 25
2. `app/Filament/Widgets/StatsOverview.php` - Lines 16-17

---

**Fixed on:** October 27, 2025  
**Bug Type:** Incorrect database field reference  
**Severity:** Medium (affects dashboard visibility, not data integrity)





