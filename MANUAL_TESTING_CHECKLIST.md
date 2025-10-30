# 📋 Manual Testing Checklist - Complete Renewal Flow

## ✅ Pre-Testing Setup

### 1. Ensure Email Configuration is Working
```bash
# Check .env file
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@nokkuwait.org
MAIL_FROM_NAME="NOK Kuwait"
```

### 2. Check Cron Job is Set Up (for automatic reminders)
```bash
# On server, add to crontab:
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

### 3. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## 🧪 PART 1: Admin Panel Testing

### Test 1: Login to Admin Panel
- [ ] Navigate to: `http://127.0.0.1:8000/admin/login`
- [ ] Login credentials:
  - Email: `admin@example.com`
  - Password: (your admin password)
- [ ] ✅ Successful login redirects to admin dashboard

---

### Test 2: Approve New Registration

**Steps:**
1. [ ] Go to: `/admin/registrations`
2. [ ] Find a pending registration
3. [ ] Click "Approve" button
4. [ ] Confirm approval in modal

**Verify:**
- [ ] ✅ Status changes to "approved"
- [ ] ✅ `card_issued_at` is set to current date
- [ ] ✅ `card_valid_until` is set to December 31 of current year
- [ ] ✅ Email sent to member with membership card
- [ ] ✅ Success notification appears

**Database Check:**
```sql
SELECT 
    memberName, 
    login_status, 
    card_issued_at, 
    card_valid_until 
FROM registrations 
WHERE id = [member_id];
```

Expected:
- `login_status`: "approved"
- `card_valid_until`: "2025-12-31" (if approved in 2025)

---

### Test 3: View Renewal Requests

**Steps:**
1. [ ] Go to: `/admin/renewal-requests`
2. [ ] Should see list of members who requested renewal
3. [ ] Check columns:
   - [ ] Member Name
   - [ ] Email
   - [ ] Current Expiry Date
   - [ ] Renewal Status (pending/approved)
   - [ ] Payment Proof (if uploaded)

---

### Test 4: Approve Renewal Request (Same Year)

**Setup:**
- Current Date: December 15, 2025
- Member's current expiry: December 31, 2025

**Steps:**
1. [ ] Go to: `/admin/renewal-requests`
2. [ ] Find pending renewal request
3. [ ] Click "Approve Renewal" button
4. [ ] Read modal message: "This will extend membership validity to end of current year: Dec 31, 2025"
5. [ ] Click "Confirm"

**Verify:**
- [ ] ✅ `renewal_status` changes to "approved"
- [ ] ✅ `last_renewed_at` is set to current timestamp
- [ ] ✅ `renewal_count` incremented by 1
- [ ] ✅ `card_valid_until` updated to "2025-12-31" (same year!)
- [ ] ✅ Email sent with updated membership card
- [ ] ✅ Success notification: "Renewal approved and card sent to member email"

**Important Note:**
> If renewal is approved in SAME YEAR (e.g., Dec 15, 2025), the card still expires Dec 31, 2025.
> Member must renew again in 2026 for year 2026!

**Database Check:**
```sql
SELECT 
    memberName,
    renewal_status,
    card_valid_until,
    last_renewed_at,
    renewal_count
FROM registrations 
WHERE id = [member_id];
```

Expected:
- `renewal_status`: "approved"
- `card_valid_until`: "2025-12-31 00:00:00"
- `renewal_count`: incremented

---

### Test 5: Approve Renewal Request (New Year)

**Setup:**
- Current Date: January 10, 2026
- Member's current expiry: December 31, 2025 (EXPIRED)

**Steps:**
1. [ ] Go to: `/admin/renewal-requests`
2. [ ] Find pending renewal request from expired member
3. [ ] Click "Approve Renewal"
4. [ ] Read modal: "This will extend membership validity to end of current year: Dec 31, 2026"
5. [ ] Click "Confirm"

**Verify:**
- [ ] ✅ `card_valid_until` updated to "2026-12-31" (NEW year!)
- [ ] ✅ `renewal_count` incremented
- [ ] ✅ Email sent with updated card
- [ ] ✅ Member can now access member panel

**Database Check:**
```sql
SELECT 
    memberName,
    card_valid_until,
    renewal_count
FROM registrations 
WHERE id = [member_id];
```

Expected:
- `card_valid_until`: "2026-12-31 00:00:00"

---

### Test 6: Check Renewal Reminders Log

**Steps:**
1. [ ] Go to: `/admin/reminder-emails` (if resource exists)
2. [ ] View sent renewal reminders

Or check database:
```sql
SELECT * FROM renewal_reminders 
ORDER BY created_at DESC 
LIMIT 20;
```

**Verify:**
- [ ] Reminders sent at correct intervals (30, 15, 7, 1, 0 days)
- [ ] No duplicate reminders for same member/date
- [ ] Status shows "sent" or "failed"

---

## 🧪 PART 2: Member Panel Testing

### Test 7: Member Login

**Steps:**
1. [ ] Navigate to: `http://127.0.0.1:8000/member/panel/login`
2. [ ] Login with:
   - Email: (approved member email)
   - Password: (member password, e.g., "NOK1234")
3. [ ] ✅ Successful login redirects to member dashboard

---

### Test 8: View Member Dashboard

**Steps:**
1. [ ] After login, view dashboard
2. [ ] Check displayed information:
   - [ ] Member name
   - [ ] NOK ID
   - [ ] Card expiry date
   - [ ] Days remaining (if not expired)
   - [ ] Renewal button visibility

**Verify Display:**
- If card NOT expired:
  - [ ] Shows "Valid until: Dec 31, 2025"
  - [ ] Shows days remaining
  - [ ] "Request Renewal" button may be visible (if within 30 days)
  
- If card EXPIRED:
  - [ ] Shows "Expired" status
  - [ ] "Request Renewal" button visible and prominent

---

### Test 9: Request Renewal (Member Action)

**Steps:**
1. [ ] On member dashboard, locate "Request Renewal" button
2. [ ] Click the button
3. [ ] Fill renewal form (if any):
   - [ ] Upload payment proof (if required)
4. [ ] Click "Submit Renewal Request"

**Verify:**
- [ ] ✅ Success message appears
- [ ] ✅ Button changes to "Renewal Pending" or disabled
- [ ] ✅ `renewal_requested_at` timestamp set in database
- [ ] ✅ `renewal_status` set to "pending"

**Database Check:**
```sql
SELECT 
    memberName,
    renewal_status,
    renewal_requested_at
FROM registrations 
WHERE id = [member_id];
```

Expected:
- `renewal_status`: "pending"
- `renewal_requested_at`: current timestamp

---

### Test 10: Test Renewal Button (Admin Test Feature)

**Steps:**
1. [ ] On member dashboard, find "Test Renewal" button (if exists for testing)
2. [ ] Click it
3. [ ] Verify it works same as regular renewal button

---

## 🧪 PART 3: Email Testing

### Test 11: New Registration Approval Email

**Trigger:**
- Admin approves new registration

**Verify Email:**
- [ ] ✅ Sent to member email
- [ ] ✅ Subject: "Membership Card" or similar
- [ ] ✅ Contains:
  - [ ] Member name
  - [ ] NOK ID
  - [ ] Card issue date
  - [ ] Card expiry date (Dec 31, YYYY)
  - [ ] Login credentials (password)
  - [ ] QR code or download link
  - [ ] Member panel login link

**Check Email Content:**
```blade
Card Valid Until: December 31, 2025
```

---

### Test 12: Renewal Approval Email

**Trigger:**
- Admin approves renewal request

**Verify Email:**
- [ ] ✅ Sent to member email
- [ ] ✅ Subject: "Membership Renewal Approved" or similar
- [ ] ✅ Contains:
  - [ ] Updated expiry date
  - [ ] Renewal confirmation message
  - [ ] Download link for updated card
  - [ ] QR code updated

**Important Check:**
> The expiry date in email MUST match `card_valid_until` in database!

---

### Test 13: Renewal Reminder Email (Automatic)

**Trigger:**
- Cron job runs daily at 8:00 AM
- Or manually: `php artisan members:send-renewal-reminders`

**Verify Email:**
- [ ] ✅ Sent to members expiring in 30, 15, 7, 1, 0 days
- [ ] ✅ Subject: "Membership Renewal Reminder"
- [ ] ✅ Contains:
  - [ ] Member name
  - [ ] Current expiry date
  - [ ] Days remaining (30, 15, 7, 1, or "expired")
  - [ ] Link to request renewal
  - [ ] Instructions

**Manual Test:**
```bash
php artisan members:send-renewal-reminders
```

---

## 🧪 PART 4: Database Verification

### Test 14: Verify card_valid_until Updates

**Test Case 1: Same Year Renewal**
```sql
-- Setup: Member with expiry 2025-12-31, renewed on 2025-12-15
SELECT 
    memberName,
    DATE(card_valid_until) as expiry_date,
    renewal_count
FROM registrations 
WHERE memberName = 'Test Member';
```

Expected: `expiry_date` = "2025-12-31" (same year)

**Test Case 2: New Year Renewal**
```sql
-- Setup: Member with expiry 2025-12-31, renewed on 2026-01-10
SELECT 
    memberName,
    DATE(card_valid_until) as expiry_date,
    renewal_count
FROM registrations 
WHERE memberName = 'Test Member';
```

Expected: `expiry_date` = "2026-12-31" (new year)

---

### Test 15: Verify All Members Expire on Dec 31

```sql
SELECT 
    memberName,
    DATE(card_issued_at) as joined_date,
    DATE(card_valid_until) as expiry_date,
    MONTH(card_valid_until) as expiry_month,
    DAY(card_valid_until) as expiry_day
FROM registrations 
WHERE login_status = 'approved' OR renewal_status = 'approved'
ORDER BY card_issued_at;
```

**Verify:**
- [ ] ✅ ALL members have `expiry_month` = 12
- [ ] ✅ ALL members have `expiry_day` = 31
- [ ] ✅ Expiry year matches their last renewal year

---

## 🧪 PART 5: Multi-Year Testing Scenario

### Test 16: Complete Member Journey (2025-2027)

**Year 2025:**
1. [ ] Member joins: Jan 15, 2025
2. [ ] Admin approves: Jan 20, 2025
3. [ ] Verify: `card_valid_until` = 2025-12-31
4. [ ] Dec 1, 2025: Renewal reminder sent
5. [ ] Dec 15, 2025: Member requests renewal
6. [ ] Dec 20, 2025: Admin approves
7. [ ] Verify: `card_valid_until` = 2025-12-31 (still same year!)

**Year 2026:**
8. [ ] Jan 5, 2026: Member requests renewal (card expired)
9. [ ] Jan 10, 2026: Admin approves
10. [ ] Verify: `card_valid_until` = 2026-12-31 ✅
11. [ ] Dec 1, 2026: Renewal reminder sent
12. [ ] Dec 10, 2026: Member requests renewal
13. [ ] Dec 15, 2026: Admin approves
14. [ ] Verify: `card_valid_until` = 2026-12-31 (still same year!)

**Year 2027:**
15. [ ] Jan 3, 2027: Member requests renewal
16. [ ] Jan 5, 2027: Admin approves
17. [ ] Verify: `card_valid_until` = 2027-12-31 ✅

**Final Verification:**
- [ ] `renewal_count` = 4 (or appropriate number)
- [ ] All emails sent correctly
- [ ] Database records accurate
- [ ] Member can access panel

---

## 🧪 PART 6: Edge Cases

### Test 17: Renewal on December 31, 2025

**Scenario:**
- Current: Dec 31, 2025, 11:00 PM
- Member: Expires Dec 31, 2025
- Action: Request renewal

**Steps:**
1. [ ] Set date to Dec 31, 2025, 23:00
2. [ ] Member requests renewal
3. [ ] Admin approves same day
4. [ ] Verify: `card_valid_until` = 2025-12-31 (same day!)
5. [ ] Next day (Jan 1, 2026): Member requests again
6. [ ] Admin approves
7. [ ] Verify: `card_valid_until` = 2026-12-31 ✅

---

### Test 18: Multiple Renewals Same Day

**Scenario:**
- Multiple members request renewal same day

**Steps:**
1. [ ] Create 5 test members
2. [ ] All request renewal on same day
3. [ ] Admin approves all
4. [ ] Verify: All have same `card_valid_until` (Dec 31 of year)

---

## 📊 Final Verification Checklist

### Database State
- [ ] All approved members have `card_valid_until` = "YYYY-12-31"
- [ ] `renewal_count` increments correctly
- [ ] `last_renewed_at` timestamps accurate
- [ ] No null `card_valid_until` for approved members

### Email Functionality
- [ ] New registration emails sent
- [ ] Renewal approval emails sent
- [ ] Renewal reminder emails sent (automatic)
- [ ] All emails contain correct expiry dates
- [ ] Email content matches database

### UI/UX
- [ ] Admin panel shows correct dates
- [ ] Member dashboard shows correct dates
- [ ] Renewal buttons work
- [ ] Status badges display correctly
- [ ] Notifications appear

### Calendar Year System
- [ ] All cards expire Dec 31 (calendar year)
- [ ] Same-year renewals keep same year-end
- [ ] New-year renewals extend to new year-end
- [ ] Multi-year journey works correctly

---

## 🎯 Success Criteria

All tests must pass:
- ✅ card_valid_until updates correctly on approval
- ✅ Emails sent with correct expiry dates
- ✅ Database values accurate
- ✅ Calendar year validity enforced (Dec 31)
- ✅ Multi-year renewals work
- ✅ Member panel renewal works
- ✅ Admin approval flow works
- ✅ Automatic reminders sent

---

## 📝 Test Results Log

**Date Tested:** _________________

**Tester:** _________________

**Results:**
- [ ] All tests passed
- [ ] Some tests failed (list below)
- [ ] Requires fixes

**Issues Found:**
1. 
2. 
3. 

**Notes:**


---

## 🛠️ Automated Tests

Run all automated tests:
```bash
# Complete end-to-end test
php artisan test tests/Feature/CompleteRenewalFlowEndToEndTest.php

# Calendar year validity tests
php artisan test tests/Unit/CalendarYearValidityTest.php

# Renewal approval tests
php artisan test tests/Feature/RenewalApprovalUpdatesValidityTest.php

# All renewal tests
php artisan test --filter=Renewal

# All tests
php artisan test
```

**All automated tests should pass before manual testing!**





