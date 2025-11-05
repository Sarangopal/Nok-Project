# Registration Form Test Data

## Test Case 1: New Member Registration (Valid Data)

### Step 1: Membership Details
- **Already Member Toggle**: OFF (New Member)
- **Full Name**: `Test User Kumar`
- **Age**: `30`
- **Gender**: `Male`
- **Email**: `testuser@example.com` ⭐
- **Kuwait Mobile**: `+965 50123456`
- **WhatsApp**: `+965 50123456`

### Step 2: Professional Details
- **Department**: `Cardiology`
- **Job Title**: `Staff Nurse`
- **Institution**: `Al Amiri Hospital`
- **Passport Number**: `A1234567`
- **Civil ID**: `123456789012`
- **Blood Group**: `O+`

### Step 3: Address & Nominee
- **Full Address**: `Block 5, Street 10, Salmiya, Kuwait - 12345`
- **India Phone**: `+91 9876543210`
- **Nominee Name**: `John Doe`
- **Relationship**: `Brother`
- **Nominee Contact**: `+91 9876543210`
- **Guardian Name**: (Leave empty if not minor)
- **Guardian Contact**: (Leave empty)

### Step 4: Bank Details (Optional)
- **Account Holder Name**: `Test User Kumar`
- **Account Number**: `1234567890`
- **IFSC Code**: `SBIN0001234`
- **Bank Branch**: `State Bank of India, Kerala`

---

## Test Case 2: Email Duplicate Check Test

### First Registration
Use the data from Test Case 1 above.

### Second Registration (Should FAIL)
Try registering again with:
- **Email**: `testuser@example.com` (Same as first)
- **Civil ID**: `987654321098` (Different)
- **Mobile**: `+965 51234567` (Different)

**Expected Result**: Email field should show error: ⚠️ "This email is already registered."

---

## Test Case 3: Email Validation Tests

### Invalid Email Formats (Should show validation error)
- `testuser` (Missing @ and domain)
- `testuser@` (Missing domain)
- `testuser@example` (Missing TLD)
- `@example.com` (Missing username)
- `test user@example.com` (Space in email)

### Valid Email Formats (Should pass validation)
- `testuser@example.com`
- `test.user@example.co.in`
- `test_user123@gmail.com`
- `user+tag@domain.org`

---

## Expected Behavior

### Email Field Validation:
1. **On Input** (800ms debounce):
   - Shows "Checking..." in orange while validating
   - Validates email format using regex
   - Checks for duplicates via AJAX
   - Shows ✓ "Looks good!" in green if valid
   - Shows ✗ error message in red if invalid

2. **On Blur** (when leaving field):
   - Immediately validates without debounce
   - Same validation as above

3. **Backend Validation** (on form submit):
   - Required field check
   - Email format validation
   - Unique email check in database
   - Max 255 characters

---

## How to Test

1. **Open Browser**: http://127.0.0.1:8000/registration
2. **Fill Step 1** with test data above
3. **Focus on Email Field** and type slowly to see real-time validation
4. **Try Invalid Email**: `testuser` - Should show format error
5. **Try Valid Email**: `testuser@example.com` - Should show "Checking..." then "Looks good!"
6. **Complete Form** and submit
7. **Try Duplicate**: Register again with same email - Should be blocked

---

## Troubleshooting

### If Email Validation Doesn't Work:

1. **Check Browser Console** (F12):
   - Look for JavaScript errors
   - Check network tab for AJAX requests to `/registration/check-email`

2. **Verify Routes**:
   ```bash
   php artisan route:list | grep registration
   ```

3. **Clear Cache**:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

4. **Check Database**:
   - Ensure `registrations` table has `email` column
   - Check if email has unique index

---

## Current Status: ✅ ALL CHECKS PASSED

- ✅ Email input field exists (line 76)
- ✅ Email regex validation configured (line 290)
- ✅ AJAX duplicate check function exists (lines 354-387)
- ✅ Event listeners attached (lines 772-790)
- ✅ Backend validation configured (line 140)
- ✅ All routes registered properly
- ✅ Email sending on registration (lines 182-188)

**The email field should be working properly!** If you encounter issues, check:
1. Browser JavaScript console for errors
2. Network tab to see if AJAX requests are being made
3. Laravel logs: `storage/logs/laravel.log`

