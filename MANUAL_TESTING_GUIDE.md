# Manual Testing Guide for Registration Form

## 🚀 Quick Start

### 1. Start the Server
```bash
php artisan serve
```

### 2. Open Browser
Navigate to: `http://127.0.0.1:8000/registration`

---

## ✅ Testing Checklist

### Gender Field (Step 1)
- [ ] Click on "Male" radio button
- [ ] Verify NO "Looks good" message appears
- [ ] Click on "Female" radio button  
- [ ] Click on "Others" radio button
- [ ] Verify "Transgender" option does NOT exist
- [ ] Try to proceed without selecting gender
- [ ] Verify error message: "✗ Please select a gender."

---

### Email Field (Step 1)

#### Test 1: Valid Email
- [ ] Type: `test@example.com`
- [ ] Wait 1 second for validation
- [ ] Verify green border appears
- [ ] Verify "✓ Looks good!" message

#### Test 2: Invalid Email Format
- [ ] Type: `invalid-email`
- [ ] Wait 1 second
- [ ] Verify red border appears
- [ ] Verify error message appears

#### Test 3: Duplicate Email
- [ ] First register with: `duplicate@example.com`
- [ ] Try to register again with same email
- [ ] Wait 1 second after typing
- [ ] Verify message: "⚠️ This email is already registered."
- [ ] Verify red border appears

---

### Kuwait Mobile Field (Step 1)

#### Test 1: Valid Kuwait Number
- [ ] Verify country flag shows Kuwait (+965)
- [ ] Type: `51234567` (8 digits starting with 5)
- [ ] Wait 1 second for validation
- [ ] Verify green border appears
- [ ] Try: `61234567` (starts with 6)
- [ ] Try: `91234567` (starts with 9)
- [ ] All should be valid

#### Test 2: Invalid Kuwait Number - Wrong Starting Digit
- [ ] Type: `41234567` (starts with 4)
- [ ] Wait 1 second
- [ ] Verify error: "Kuwait number must start with 5, 6, or 9"

#### Test 3: Invalid Kuwait Number - Wrong Length
- [ ] Type: `512345` (only 6 digits)
- [ ] Wait 1 second
- [ ] Verify error: "Kuwait number must be 8 digits"

#### Test 4: Duplicate Phone Number
- [ ] Register with: `51234567`
- [ ] Try to register again with same number
- [ ] Wait 1 second after typing
- [ ] Verify message: "⚠️ This phone number is already registered."

---

### WhatsApp Field (Step 1)

#### Test 1: Check Helper Text
- [ ] Verify text below field: "Include your country code for WhatsApp."

#### Test 2: Country Selection
- [ ] Click on country flag dropdown
- [ ] Search for different countries
- [ ] Select "India" (+91)
- [ ] Type: `9876543210`
- [ ] Verify number is formatted correctly

#### Test 3: Kuwait WhatsApp
- [ ] Select Kuwait (+965)
- [ ] Type: `51234567`
- [ ] Verify validation works same as mobile field

---

### India Phone Field (Step 3)

#### Test 1: Valid India Number
- [ ] Navigate to Step 3 (Address section)
- [ ] Verify India Phone field has India flag (+91)
- [ ] Type: `9876543210` (starts with 9)
- [ ] Wait 1 second
- [ ] Verify green border
- [ ] Try: `8876543210` (starts with 8)
- [ ] Try: `7876543210` (starts with 7)
- [ ] Try: `6876543210` (starts with 6)
- [ ] All should be valid

#### Test 2: Invalid India Number - Wrong Starting Digit
- [ ] Type: `5876543210` (starts with 5)
- [ ] Wait 1 second
- [ ] Verify error: "India number must start with 6-9"

#### Test 3: Invalid India Number - Wrong Length
- [ ] Type: `987654321` (only 9 digits)
- [ ] Wait 1 second
- [ ] Verify error: "India number must be 10 digits"

---

### Form Submission

#### Test 1: Complete Valid Registration
Fill all fields with valid data:

**Step 1:**
- Name: `Test User`
- Age: `30`
- Gender: `Male`
- Email: `test123@example.com`
- Mobile: `51234567` (Kuwait)
- WhatsApp: `51234567` (Kuwait)

**Step 2:**
- Department: `Nursing`
- Job Title: `Senior Nurse`
- Institution: `Kuwait Hospital`
- Passport: `A1234567`
- Civil ID: `123456789012` (exactly 12 digits)
- Blood Group: `A+`

**Step 3:**
- Address: `123 Main Street, Kuwait City, 12345`
- India Phone: `9876543210`
- Nominee Name: `Jane Doe`
- Nominee Relation: `Spouse`
- Nominee Contact: `+919876543210`

**Step 4:**
- Bank details (optional - can skip)
- Click Submit

**Verify:**
- [ ] Form submits without page reload
- [ ] Success message appears in SweetAlert
- [ ] Message says: "✅ Registration successful!"
- [ ] Page redirects/refreshes after 3 seconds

#### Test 2: Submission with Errors
- [ ] Leave some required fields empty
- [ ] Try to click "Next Step" or "Submit"
- [ ] Verify button is disabled OR shows validation errors
- [ ] Fill missing fields
- [ ] Try again
- [ ] Should proceed successfully

---

### Performance Testing

#### Test 1: Multiple Users
- [ ] Open 3 browser tabs
- [ ] Fill form in all tabs simultaneously
- [ ] Submit from all tabs at the same time
- [ ] Verify all submissions complete successfully
- [ ] No hanging or freezing

#### Test 2: Real-time Validation Performance
- [ ] Type rapidly in email field
- [ ] Verify validation doesn't trigger on every keystroke (debounced)
- [ ] Stop typing
- [ ] Verify validation happens ~800ms after last keystroke

#### Test 3: Network Timeout
- [ ] Disconnect internet
- [ ] Type in email field
- [ ] Wait for validation attempt
- [ ] Verify form doesn't hang
- [ ] Reconnect internet
- [ ] Try again - should work

---

### Mobile Testing

#### Test on Mobile Device or Browser DevTools
- [ ] Open Chrome DevTools (F12)
- [ ] Click "Toggle device toolbar" (Ctrl+Shift+M)
- [ ] Select iPhone or Android device
- [ ] Navigate to registration form
- [ ] Verify phone inputs work correctly
- [ ] Verify country dropdowns work on touch
- [ ] Verify form is responsive
- [ ] Complete registration on mobile view

---

### Browser Compatibility

Test on multiple browsers:
- [ ] Google Chrome
- [ ] Mozilla Firefox
- [ ] Microsoft Edge
- [ ] Safari (if available)

---

## 🎯 Expected Results Summary

### ✅ What Should Work
1. Gender field shows: Male, Female, Others (NOT Transgender)
2. Gender validation doesn't show "Looks good"
3. Real-time email validation with duplicate checking
4. Real-time phone validation with duplicate checking
5. Kuwait numbers: 8 digits, starts with 5/6/9
6. India numbers: 10 digits, starts with 6/7/8/9
7. WhatsApp field accepts any country code
8. intl-tel-input library displays flags and country codes
9. Form submits via AJAX without page reload
10. Success/error messages appear clearly
11. Multiple users can register simultaneously
12. Validation is debounced (800ms delay)
13. All validations happen on server-side too

### ❌ What Should NOT Work
1. Selecting "Transgender" (option removed)
2. Kuwait number starting with 1/2/3/4/7/8
3. Kuwait number with less than or more than 8 digits
4. India number starting with 1/2/3/4/5
5. India number with less than or more than 10 digits
6. Duplicate email registration
7. Duplicate phone registration
8. Age less than 18
9. Civil ID not exactly 12 digits
10. Invalid email format

---

## 📸 Screenshot Checklist

Take screenshots of:
- [ ] Empty form (Step 1)
- [ ] Gender field with "Others" option
- [ ] Phone field with intl-tel-input (showing flag)
- [ ] Email validation - green (valid)
- [ ] Email validation - red (duplicate)
- [ ] Phone validation - red (invalid format)
- [ ] WhatsApp field with helper text
- [ ] India phone field (Step 3)
- [ ] Success message after submission
- [ ] Database entry created

---

## 🐛 Common Issues & Solutions

### Issue: intl-tel-input not showing
- **Solution:** Clear browser cache and reload
- **Check:** Console for JavaScript errors

### Issue: Validation not triggering
- **Solution:** Check internet connection
- **Check:** Laravel server is running
- **Check:** Routes are registered correctly

### Issue: Form submission fails
- **Solution:** Check Laravel logs: `storage/logs/laravel.log`
- **Check:** Database connection in `.env`
- **Check:** All required fields are filled

### Issue: Phone validation not working
- **Solution:** Wait at least 1 second after typing
- **Solution:** Ensure intl-tel-input loaded (check console)
- **Solution:** Verify country is selected correctly

---

## ✅ Final Verification

After all tests:
- [ ] All features working as expected
- [ ] No console errors in browser
- [ ] No errors in Laravel logs
- [ ] Database entries created correctly
- [ ] Email validation working
- [ ] Phone validation working
- [ ] Form performs well with multiple users
- [ ] Mobile responsive
- [ ] Cross-browser compatible

---

## 📝 Test Results

Record your test results:

| Test | Status | Notes |
|------|--------|-------|
| Gender Field | ✅ / ❌ | |
| Email Validation | ✅ / ❌ | |
| Phone Validation (Kuwait) | ✅ / ❌ | |
| Phone Validation (India) | ✅ / ❌ | |
| WhatsApp Field | ✅ / ❌ | |
| Duplicate Checking | ✅ / ❌ | |
| Form Submission | ✅ / ❌ | |
| Performance | ✅ / ❌ | |
| Mobile Responsive | ✅ / ❌ | |

---

## 🎉 Testing Complete!

If all tests pass, the registration form is ready for production! 🚀

