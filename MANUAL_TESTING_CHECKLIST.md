# 🧪 MANUAL TESTING CHECKLIST
**Project:** NOK Kuwait Admin Panel  
**Date:** {{ date('Y-m-d') }}

---

## 🔐 A. ADMIN PANEL TESTING

### 1. Authentication
- [ ] **Login Test**
  - Go to: `http://127.0.0.1:8000/admin/login`
  - Email: `admin@gmail.com`
  - Password: `secret`
  - ✅ Should login successfully
  - ✅ Should redirect to dashboard

- [ ] **Logout Test**
  - Click logout button
  - ✅ Should redirect to login page
  - ✅ Should clear session

- [ ] **Invalid Credentials**
  - Try wrong password
  - ✅ Should show error message
  - ✅ Should not login

---

### 2. Dashboard
- [ ] **Load Dashboard**
  - After login, dashboard should load
  - ✅ No console errors (F12 → Console)
  - ✅ All widgets load correctly
  - ✅ No broken images
  - ✅ Load time < 2 seconds

- [ ] **Navigation Menu**
  - ✅ All menu items visible
  - ✅ Badge counts show correctly
  - ✅ Clicking items navigates correctly

---

### 3. New Registrations (`/admin/registrations`)

#### Create New Registration
- [ ] **Open Create Form**
  - Click "New registration" button
  - Go to: `http://127.0.0.1:8000/admin/registrations/create`

- [ ] **Fill Required Fields**
  - Member Name: `Test User`
  - Age: `25`
  - Gender: `M`
  - Email: `test@example.com`
  - Mobile: `50123456`
  - Civil ID: `123456789012`

- [ ] **Test Duplicate Validation**
  - Try existing email: `test1day@example.com`
  - ✅ Should show error: "⚠️ This email is already registered"
  - Try existing Civil ID: `644582148057`
  - ✅ Should show error: "⚠️ This Civil ID is already registered"
  - Try existing Mobile
  - ✅ Should show error: "⚠️ This mobile number is already registered"

- [ ] **Submit Valid Form**
  - Fill all fields with NEW data
  - Click "Create"
  - ✅ Should create successfully
  - ✅ Should redirect to list page

#### List Registrations
- [ ] **View List**
  - Go to: `http://127.0.0.1:8000/admin/registrations`
  - ✅ Table loads correctly
  - ✅ Pagination works
  - ✅ Search works (try searching by name/email)

- [ ] **Filters**
  - Apply "Pending" filter
  - ✅ Should show only pending registrations
  - Apply "Approved" filter
  - ✅ Should show only approved

#### Actions
- [ ] **Approve Registration**
  - Find pending registration
  - Click "Approve" button
  - ✅ Should approve successfully
  - ✅ Should send email
  - ✅ Status changes to "Approved"

- [ ] **Resend Credentials**
  - Find approved member
  - Click "Resend Credentials"
  - ✅ Should generate new password
  - ✅ Should send email with credentials
  - ✅ Email subject: "Password Reset - Login Credentials Updated"

- [ ] **Reset Password**
  - Click "Reset Password"
  - ✅ Should generate new password
  - ✅ Should send email

- [ ] **Edit Registration**
  - Click "Edit" button
  - Change some fields
  - ✅ Should save successfully
  - ✅ Validation still works on edit

- [ ] **Delete Registration**
  - Click "Delete" button
  - Confirm deletion
  - ✅ Should delete successfully

---

### 4. Renewals (`/admin/renewals`)

- [ ] **View Renewals List**
  - Go to: `http://127.0.0.1:8000/admin/renewals`
  - ✅ "New Renewal" button is HIDDEN
  - ✅ Member type badges show:
    - Green for "new"
    - Blue for "existing"
  - ✅ Table loads correctly
  - ✅ Pagination works

- [ ] **View Action**
  - Click "View" button
  - ✅ Opens modal/form
  - ✅ Shows member details correctly

- [ ] **Edit Action**
  - Click "Edit" button
  - ✅ Opens edit form
  - ✅ Saves changes correctly

---

### 5. Renewal Requests (`/admin/renewal-requests`)

- [ ] **View List**
  - Go to: `http://127.0.0.1:8000/admin/renewal-requests`
  - ✅ Badge count shows correctly
  - ✅ Table loads correctly

- [ ] **View Details Modal**
  - Click "View" button
  - ✅ Modal opens
  - ✅ **Payment Proof Image:**
    - ✅ Image displays correctly
    - ✅ If missing, shows helpful error message
    - ✅ "View Full Size" button works
    - ✅ "Download Image" button works
  - ✅ **Member Details:**
    - ✅ Shows all updated details
    - ✅ Proper styling (green gradient section)
  - ✅ **Request Info:**
    - ✅ Shows NOK ID, Civil ID, dates
    - ✅ Status badge shows correctly

- [ ] **Approve Renewal**
  - Click "Approve Renewal" button
  - ✅ Confirms before approval
  - ✅ Approves successfully
  - ✅ Extends card validity
  - ✅ Sends email
  - ✅ Removes from pending list

---

### 6. Events (`/admin/events`)

- [ ] **Create Event**
  - Click "New event"
  - Fill form:
    - Title: `Test Event`
    - Event Date: Select future date
    - Display Order: `1`
  - ✅ Should create successfully

- [ ] **Test Duplicate Display Order**
  - Try to set Display Order = `1` (already exists)
  - ✅ Should show error: "This order number is already in use"
  - ✅ Form won't submit

- [ ] **List Events**
  - ✅ Events sorted by display_order
  - ✅ Events appear on homepage correctly

- [ ] **Edit Event**
  - Change display_order
  - ✅ Should save correctly
  - ✅ Should reflect on homepage

---

### 7. Gallery (`/admin/gallery`)

- [ ] **Upload Image**
  - Click "New gallery"
  - Upload image
  - ✅ Image uploads successfully
  - ✅ Image displays in list

- [ ] **View Gallery**
  - ✅ Images display correctly
  - ✅ Categories work
  - ✅ Pagination works

---

## 👤 B. MEMBER PANEL TESTING

### 1. Member Login
- [ ] **Login Test**
  - Go to: `http://127.0.0.1:8000/member/panel/login`
  - Email: Use member email from credentials
  - Password: Use password from credentials email
  - ✅ Should login successfully

- [ ] **Invalid Login**
  - Try wrong credentials
  - ✅ Should show error
  - ✅ Should not login

---

### 2. Member Dashboard
- [ ] **Load Dashboard**
  - ✅ Dashboard loads correctly
  - ✅ Shows membership card
  - ✅ Shows expiry date
  - ✅ No console errors

- [ ] **Membership Card**
  - ✅ Download PDF button works
  - ✅ Card displays correctly

- [ ] **Renewal Request**
  - If card expired/expiring:
    - ✅ "Request Renewal" button shows
    - ✅ Clicking opens renewal form
    - ✅ Can upload payment proof
    - ✅ Can submit request

---

## 🌐 C. FRONTEND TESTING

### 1. Homepage (`/`)
- [ ] **Load Page**
  - ✅ Page loads without errors
  - ✅ No console errors
  - ✅ Images load correctly

- [ ] **Events Carousel**
  - ✅ Events display correctly
  - ✅ Ordering works (display_order)
  - ✅ First event shows first
  - ✅ Carousel navigation works

- [ ] **Navigation**
  - ✅ All links work
  - ✅ Navigation menu works

---

### 2. Events Page (`/events`)
- [ ] **List Events**
  - ✅ Events display correctly
  - ✅ Pagination works
  - ✅ Search works

- [ ] **Event Detail**
  - ✅ Click event opens detail page
  - ✅ Shows full event information

---

## ⚡ D. PERFORMANCE TESTING

### 1. Load Times
- [ ] **Admin Dashboard**
  - Open browser DevTools (F12)
  - Go to Network tab
  - Load dashboard
  - ✅ Load time < 2 seconds
  - ✅ No failed requests

- [ ] **Filament Tables**
  - Load any list page
  - ✅ Loads quickly
  - ✅ No lag when scrolling
  - ✅ Pagination loads fast

### 2. Console Errors
- [ ] **Check Console**
  - Open DevTools (F12)
  - Go to Console tab
  - Navigate through pages
  - ✅ No red errors
  - ✅ No warnings (or minimal warnings)

---

## 🔒 E. SECURITY TESTING

### 1. CSRF Protection
- [ ] **Form Submission**
  - Try submitting form without CSRF token
  - ✅ Should be rejected

### 2. Authentication
- [ ] **Protected Routes**
  - Try accessing `/admin/registrations` without login
  - ✅ Should redirect to login

- [ ] **Member Routes**
  - Try accessing `/member/panel` without login
  - ✅ Should redirect to login

### 3. File Uploads
- [ ] **Invalid Files**
  - Try uploading .exe file
  - ✅ Should be rejected
  - ✅ Shows validation error

---

## 📊 F. TEST RESULTS SUMMARY

### Admin Panel
- ✅ **Passed:** [Fill in]
- ❌ **Failed:** [Fill in]
- ⚠️ **Warnings:** [Fill in]

### Member Panel
- ✅ **Passed:** [Fill in]
- ❌ **Failed:** [Fill in]
- ⚠️ **Warnings:** [Fill in]

### Frontend
- ✅ **Passed:** [Fill in]
- ❌ **Failed:** [Fill in]
- ⚠️ **Warnings:** [Fill in]

### Performance
- ✅ **Load Times:** [Fill in]
- ❌ **Slow Pages:** [Fill in]
- ⚠️ **Console Errors:** [Fill in]

---

## 📝 NOTES

**Issues Found:**
1. [Describe any issues]
2. [Describe any issues]

**Screenshots:**
- [Attach screenshots of any errors]

**Browser Used:**
- [Chrome/Firefox/Safari] Version: [Version]

---

**Tested By:** [Your Name]  
**Date:** {{ date('Y-m-d H:i:s') }}
