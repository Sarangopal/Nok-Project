# Browser Testing Guide - Member Renewal Flow

## Quick Start Manual Testing

### Prerequisites
✅ Server running (Laravel development server or Laragon)  
✅ Database seeded with test member  
✅ Test member credentials ready

---

## 🌐 BROWSER TEST 1: Member Panel Login

### Steps:
1. **Open browser** and navigate to:
   ```
   http://localhost/member/panel/login
   ```

2. **Enter credentials:**
   - Civil ID: `TEST814485`
   - Password: `password123`

3. **Click "Login" button**

### ✅ Expected Results:
- Redirected to `/member/dashboard`
- See "Welcome, Renewal Test Member"
- Profile information displayed
- Membership card section visible
- Download PDF button present

### ❌ Troubleshooting:
- If login fails, run: `php verify_complete_renewal_system.php` to create test user
- Check Civil ID is correct (case-sensitive)

---

## 📋 BROWSER TEST 2: View Dashboard & Card Status

### What to Check:
1. **Profile Overview (left column)**
   - NOK ID displayed
   - Email address shown
   - Mobile number visible
   - Joining date present
   - Renewal date shown
   - Status displayed

2. **Membership Card Section (right column)**
   - Card validity information
   - Expiry warning (if applicable)
   - Download PDF button
   - Renewal request button (if card expiring soon)

### ✅ Expected Results:
Based on card expiry date:

**If Card Expiring Soon (≤30 days):**
```
⏰ Your membership expires in X day(s)
```
- "Request Early Renewal" button visible

**If Card Expired:**
```
⚠️ Your membership has expired on [DATE]
```
- "Request Renewal Now" button visible

**If Card Valid (>30 days):**
- No warning message
- Just "Download PDF" button

---

## 📝 BROWSER TEST 3: Submit Renewal Request

### Steps:
1. **On member dashboard**, locate renewal button
2. **Click** "Request Renewal Now" or "Request Early Renewal"
3. **Wait for page reload**

### ✅ Expected Results:
- Success message appears (blue alert box)
- Message says: "Renewal request submitted on [DATE]. Waiting for admin approval."
- Renewal button disappears
- Status shows as "pending"

### Database Verification:
```bash
php artisan tinker --execute="App\Models\Registration::where('civil_id', 'TEST814485')->first(['renewal_status', 'renewal_requested_at'])"
```

Expected output:
- `renewal_status`: "pending"
- `renewal_requested_at`: Current timestamp

---

## 👨‍💼 BROWSER TEST 4: Admin Panel - Approve Renewal

### Steps:
1. **Open new browser tab/window** and navigate to:
   ```
   http://localhost/admin
   ```

2. **Login with admin credentials**
   - Use your admin email and password

3. **Navigate to "Renewals" section**
   - Click on "Renewals" in the left sidebar

4. **Find the pending renewal**
   - Look for "Renewal Test Member"
   - Status should show "Renewal Pending" or similar badge

5. **View the record**
   - Click the "eye" icon to view details
   - Verify all member information

6. **Approve the renewal**
   - Click "Edit" action
   - Or use bulk approve action if available
   - Change `renewal_status` to "approved"
   - Update `card_valid_until` to December 31 of next year
   - Update `last_renewed_at` to current date
   - Increment `renewal_count`

### ✅ Expected Results:
- Record updated successfully
- Email sent to member (check mail logs)
- Card validity extended to Dec 31, next year

---

## 📥 BROWSER TEST 5: Download Renewed Card

### Steps:
1. **Go back to member panel** (first browser tab)
2. **Refresh the page** (F5)
3. **Verify updated status:**
   - No more "pending" message
   - Renewed expiry date displayed
   - Status shows "Approved"

4. **Click "Download PDF" button**

### ✅ Expected Results:
- PDF file downloads
- Opens in PDF viewer
- Shows member details:
  - NOK ID
  - Member name
  - Photo (if uploaded)
  - QR code
  - **Valid Until:** December 31, [next year]

---

## 🔍 BROWSER TEST 6: Logout & Re-login

### Steps:
1. **Click "Logout" button** on member dashboard
2. **Verify redirected** to login page
3. **Log in again** with same credentials
4. **Verify dashboard** loads correctly

### ✅ Expected Results:
- Logout successful (redirected to /member/panel/login)
- Re-login works with same credentials
- Dashboard shows updated renewal status
- All data persists

---

## ❌ BROWSER TEST 7: Test Failed Login

### Steps:
1. **Go to login page:** http://localhost/member/panel/login
2. **Enter wrong credentials:**
   - Civil ID: `WRONG123`
   - Password: `wrongpassword`
3. **Click Login**

### ✅ Expected Results:
- Login fails
- Error message displayed (red alert)
- Message: "These credentials do not match our records" or similar
- User stays on login page

---

## 🔒 BROWSER TEST 8: Test Unapproved Member

### Steps:
1. **Create unapproved member** (via database):
   ```bash
   php artisan tinker --execute="App\Models\Registration::create(['memberName' => 'Unapproved Test', 'email' => 'unapproved@test.com', 'civil_id' => 'UNAPP123', 'password' => bcrypt('password'), 'age' => 25, 'gender' => 'M', 'mobile' => '5551234', 'login_status' => 'pending', 'renewal_status' => 'pending'])"
   ```

2. **Try to login:**
   - Civil ID: `UNAPP123`
   - Password: `password`

### ✅ Expected Results:
- Login fails
- Error message: "Your membership is under review. Please wait for admin approval."
- Cannot access dashboard

---

## 📧 BROWSER TEST 9: Check Email Notifications

### Check Mailtrap/Mail Log:
1. **After admin approval**, check email inbox
2. **Verify email received** with:
   - Subject: Membership card details
   - Contains NOK ID
   - Contains login credentials (if new)
   - Shows renewed expiry date
   - PDF attachment (if configured)

### Log Location:
```bash
# Check Laravel log for sent emails
tail -f storage/logs/laravel.log

# Or use Mailtrap.io if configured
# Or check MailHog at http://localhost:8025
```

---

## 🎯 Complete Test Checklist

- [ ] Member login successful with Civil ID + Password
- [ ] Dashboard displays all member information
- [ ] Card expiry status correctly calculated
- [ ] Expiry warnings shown when appropriate
- [ ] Renewal request button appears when eligible
- [ ] Renewal request submits successfully
- [ ] Request status changes to "pending"
- [ ] Admin can view pending renewal requests
- [ ] Admin can approve renewal
- [ ] Card validity extends to Dec 31 next year
- [ ] Renewal count increments
- [ ] Email notification sent (if mail configured)
- [ ] Member can download PDF card
- [ ] PDF shows correct expiry date
- [ ] Logout works correctly
- [ ] Re-login works after logout
- [ ] Failed login shows error message
- [ ] Unapproved members cannot log in

---

## 📊 Visual Verification Points

### Dashboard Should Show:
```
┌─────────────────────────────────────────────────────────────┐
│  Welcome, Renewal Test Member                               │
│                                                              │
│  [Profile Overview]          [Membership Card]              │
│  NOK ID: NOK000012          Card Status: ✅ Valid           │
│  Email: renewal.test@...    Valid Until: 2026-12-31         │
│  Mobile: 555xxxxx           [Download PDF]                  │
│  Joining: 2024-11-28                                        │
│  Renewal: 2026-12-31        [Exclusive Offers]              │
│  Status: approved           • Offer 1                       │
│                             • Offer 2                       │
└─────────────────────────────────────────────────────────────┘
```

### Expiring Soon Warning:
```
┌─────────────────────────────────────────────┐
│ ⏰ Your membership expires in 15 day(s)     │
│                                             │
│ [Download PDF]  [Request Early Renewal]    │
└─────────────────────────────────────────────┘
```

### Expired Warning:
```
┌─────────────────────────────────────────────┐
│ ⚠️ Your membership has expired on 2025-10-15│
│                                             │
│ [Download PDF]  [Request Renewal Now]      │
└─────────────────────────────────────────────┘
```

### Pending Approval:
```
┌─────────────────────────────────────────────┐
│ ✅ Renewal request submitted on 2025-10-28  │
│    Waiting for admin approval.              │
│                                             │
│ [Download PDF]                              │
└─────────────────────────────────────────────┘
```

---

## 🚀 Quick Test Commands

```bash
# Reset test member for fresh test
php artisan tinker --execute="
\$m = App\Models\Registration::where('civil_id', 'TEST814485')->first();
\$m->renewal_requested_at = null;
\$m->renewal_status = 'approved';
\$m->card_valid_until = now()->addDays(15);
\$m->save();
echo 'Test member reset!';
"

# Create new test member with expiring card
php test_member_renewal_flow.php

# Send renewal reminders
php artisan members:send-renewal-reminders

# Check renewal reminders sent
php artisan tinker --execute="App\Models\RenewalReminder::latest()->take(5)->get(['member_name', 'days_before_expiry', 'status', 'created_at'])"

# Verify test member details
php artisan tinker --execute="App\Models\Registration::where('civil_id', 'TEST814485')->first(['memberName', 'email', 'civil_id', 'card_valid_until', 'renewal_status'])"
```

---

## 📸 Screenshot Checklist

Take screenshots of:
1. Login page
2. Successful login (dashboard)
3. Card expiring warning
4. Renewal request button
5. After submitting renewal (pending message)
6. Admin panel - renewals list
7. Downloaded PDF card
8. Failed login attempt

---

## ✅ Test Summary

After completing all browser tests, you should have verified:

**✅ Authentication & Authorization**
- Member login works
- Unapproved members blocked
- Wrong credentials rejected

**✅ Dashboard Functionality**
- Profile information displayed
- Card status calculated correctly
- Warnings shown appropriately

**✅ Renewal Request Flow**
- Request button appears when eligible
- Request submits successfully
- Status updates correctly

**✅ Admin Approval Process**
- Pending requests visible
- Approval updates database
- Card validity extends correctly

**✅ Card Management**
- PDF download works
- Correct dates on card
- Member information accurate

**All tests passing = System ready for production! 🎉**

---

**Testing completed on:** [Your Date]  
**Tested by:** [Your Name]  
**Result:** [ ] Pass  [ ] Fail  
**Notes:**





