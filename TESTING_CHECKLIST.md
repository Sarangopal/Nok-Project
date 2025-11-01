# NOK Website - Comprehensive Testing Checklist

## 1. REGISTRATION FORM TESTING (`/registration`)

### Test Case 1.1: New Member Registration
**URL:** http://127.0.0.1:8000/registration

**Steps:**
1. Leave "Already Member" checkbox **UNCHECKED**
2. Fill in Step 1 - Personal Details:
   - Name: `Sarah Johnson`
   - Age: `32`
   - Gender: Select `Female`
   - Email: `sarah.johnson@example.com`
   - Mobile (Kuwait): `+96512345678`
   - WhatsApp: `+96512345678`

3. Click "Next Step" → Should proceed to Step 2

4. Fill in Step 2 - Professional Details:
   - Department: `ICU`
   - Job Title: `Staff Nurse`
   - Institution: `Kuwait Hospital`
   - Passport No: `P1234567`
   - Civil ID: `287654321012345`

5. Click "Next Step" → Should proceed to Step 3

6. Fill in Step 3 - Additional Details:
   - Blood Group: `O+`
   - Phone India: `+919876543210`
   - Nominee Name: `John Johnson`
   - Nominee Relation: `Spouse`
   - Nominee Contact: `+96512345679`

7. Click "Submit" button

**Expected Results:**
- ✅ Form submission should succeed
- ✅ Redirect to success page
- ✅ Success message displayed
- ✅ Data saved in database with `login_status = 'pending'`
- ✅ No errors in browser console

---

### Test Case 1.2: Already Member Registration (Renewal)
**Steps:**
1. **CHECK** "Already Member" checkbox
2. Fill in the same fields as above but with different values:
   - Name: `Michael Smith`
   - Email: `michael.smith@example.com`
   - Civil ID: `287654321012346`

**Expected Results:**
- ✅ Form shows appropriate fields for renewal
- ✅ Submission creates renewal request
- ✅ `renewal_status = 'pending'` in database

---

### Test Case 1.3: Validation Testing
Test the following validations:

**Required Field Validation:**
- ❌ Try submitting with empty Name → Should show error
- ❌ Try submitting with empty Email → Should show error
- ❌ Try submitting with empty Mobile → Should show error
- ❌ Try submitting with empty Civil ID → Should show error

**Format Validation:**
- ❌ Invalid email format (e.g., `test@`) → Should show error
- ❌ Invalid mobile format → Should show error
- ❌ Age below 18 → Should show error
- ❌ Age above 100 → Should show error

**Duplicate Validation:**
- ❌ Try registering with same Civil ID twice → Should show error

**Expected:**
- ✅ All validation errors display clearly
- ✅ Form doesn't submit when validation fails
- ✅ Error messages are user-friendly

---

## 2. ADMIN DASHBOARD TESTING (`/admin/login`)

### Test Case 2.1: Admin Login
**URL:** http://127.0.0.1:8000/admin/login
**Credentials:**
- Email: `admin@gmail.com`
- Password: `secret`

**Expected:**
- ✅ Login successful
- ✅ Redirect to admin dashboard

---

### Test Case 2.2: New Registrations - View & Search
**URL:** http://127.0.0.1:8000/admin/registrations

**Steps:**
1. Check if the registration from Test 1.1 appears in the list
2. Test search functionality:
   - Search by name
   - Search by email
   - Search by civil ID
3. Test column toggles
4. Test filters

**Expected:**
- ✅ Sarah Johnson's registration appears
- ✅ Status shows "pending" badge
- ✅ Search works correctly
- ✅ Filters work correctly

---

### Test Case 2.3: View Registration Details
**Steps:**
1. Click "View" icon on Sarah Johnson's registration
2. Verify all details are displayed correctly

**Expected:**
- ✅ All personal details visible
- ✅ Professional details visible
- ✅ Additional details visible
- ✅ Status information correct

---

### Test Case 2.4: Approve Registration
**Steps:**
1. Click "Approve" button on Sarah Johnson's registration
2. Confirm the approval

**Expected:**
- ✅ NOK ID generated automatically (e.g., `NOK001002`)
- ✅ Password generated automatically
- ✅ Login status changed to "approved"
- ✅ Card validity set to end of current year
- ✅ Email sent with membership card and credentials
- ✅ Success notification displayed
- ✅ Badge changes to green "approved"

---

### Test Case 2.5: Edit Registration
**Steps:**
1. Click "Edit" button on the approved registration
2. Change Mobile number to `+96512345600`
3. Click "Save"

**Expected:**
- ✅ Edit form opens correctly
- ✅ All fields pre-filled with current values
- ✅ Changes save successfully
- ✅ Success notification displayed
- ✅ Updated value appears in the list

---

### Test Case 2.6: Reset Password
**Steps:**
1. Click "Reset Password" action on approved member
2. Confirm the reset

**Expected:**
- ✅ New password generated
- ✅ Email sent to member with new password
- ✅ Success notification displayed

---

### Test Case 2.7: Resend Credentials
**Steps:**
1. Click "Resend Credentials" action
2. Confirm

**Expected:**
- ✅ Email sent with membership card
- ✅ Success notification displayed

---

### Test Case 2.8: Reject Registration
**Steps:**
1. Create another test registration
2. Click "Reject" button
3. Confirm rejection

**Expected:**
- ✅ Status changed to "rejected"
- ✅ Badge shows as red/danger
- ✅ Rejection email sent (optional)
- ✅ Member cannot login

---

### Test Case 2.9: Delete Registration
**Steps:**
1. Create another test registration
2. Click "Delete" action
3. Confirm deletion

**Expected:**
- ✅ Confirmation dialog appears
- ✅ Record deleted from database
- ✅ No longer appears in list
- ✅ Success notification displayed

---

## 3. RENEWAL TESTING DATA

### Test Case 3.1: Create Manual Renewal Request
**URL:** http://127.0.0.1:8000/admin/renewal-requests

**Steps:**
1. Click "New Renewal Request"
2. Fill in details:
   - Select Member: Search and select approved member
   - Renewal Year: `2026`
   - Payment Status: `Pending`
3. Save

**Expected:**
- ✅ Renewal request created
- ✅ Status = "pending"
- ✅ Appears in renewal requests list

---

### Test Case 3.2: Approve Renewal
**URL:** http://127.0.0.1:8000/admin/renewal-requests

**Steps:**
1. Click "Approve" on the renewal request
2. Confirm

**Expected:**
- ✅ Renewal status changed to "approved"
- ✅ `last_renewed_at` updated to current date
- ✅ `renewal_count` incremented
- ✅ `card_valid_until` extended to end of next year
- ✅ Email sent with updated membership card
- ✅ Success notification

---

### Test Case 3.3: View Renewals History
**URL:** http://127.0.0.1:8000/admin/renewals

**Steps:**
1. Navigate to Renewals page
2. Search for the member's renewals
3. Check filters (by year, by status)

**Expected:**
- ✅ All approved renewals listed
- ✅ Renewal history for each member visible
- ✅ Search and filters work correctly

---

## 4. MEMBER PORTAL TESTING (`/member/login`)

### Test Case 4.1: Member Login
**URL:** http://127.0.0.1:8000/member/login

**Credentials:**
- Civil ID: `287654321012345` (Sarah Johnson)
- Password: `[Password from approval email]`

**Expected:**
- ✅ Login successful
- ✅ Redirect to member dashboard

---

### Test Case 4.2: Member Dashboard
**Steps:**
1. After login, check dashboard content
2. Verify member information displayed

**Expected:**
- ✅ Member name displayed
- ✅ NOK ID displayed
- ✅ Membership status visible
- ✅ Expiry date shown
- ✅ Profile information correct

---

### Test Case 4.3: Download Membership Card
**Steps:**
1. Click "Download Membership Card" button
2. Check downloaded file

**Expected:**
- ✅ PDF downloads successfully
- ✅ PDF contains all member details
- ✅ NOK ID visible
- ✅ Validity dates shown
- ✅ Professional layout

---

### Test Case 4.4: Member Profile Update
**Steps:**
1. Navigate to Profile/Settings
2. Update personal details (e.g., phone number)
3. Save changes

**Expected:**
- ✅ Profile form loads correctly
- ✅ Current details pre-filled
- ✅ Changes save successfully
- ✅ Updated values reflected immediately

---

### Test Case 4.5: Change Password
**Steps:**
1. Navigate to Change Password
2. Enter current password
3. Enter new password
4. Confirm new password
5. Submit

**Expected:**
- ✅ Password change successful
- ✅ Success message displayed
- ✅ Can login with new password
- ✅ Old password no longer works

---

### Test Case 4.6: Request Renewal
**Steps:**
1. Navigate to Renewal section
2. Click "Request Renewal"
3. Fill in renewal details
4. Submit request

**Expected:**
- ✅ Renewal request created
- ✅ Status shows as "pending"
- ✅ Admin receives notification
- ✅ Request appears in admin renewal-requests

---

## 5. MEMBERSHIP VERIFICATION TESTING

### Test Case 5.1: Verify Active Member
**URL:** http://127.0.0.1:8000/verify-membership

**Steps:**
1. Enter Civil ID: `287654321012345`
2. Click "Verify Membership"

**Expected:**
- ✅ Shows "🟢 Membership Verified — Active Member"
- ✅ Displays member details
- ✅ Shows NOK ID
- ✅ Shows expiry date
- ✅ Status badge is green/success

---

### Test Case 5.2: Verify with NOK ID
**Steps:**
1. Enter NOK ID instead of Civil ID
2. Click "Verify"

**Expected:**
- ✅ Works with NOK ID
- ✅ Same results as Test 5.1

---

### Test Case 5.3: Verify Pending Member
**Steps:**
1. Create a new registration (pending approval)
2. Try to verify with that Civil ID

**Expected:**
- ✅ Shows "⚪ Membership Pending Approval"
- ✅ Status badge is yellow/warning

---

### Test Case 5.4: Verify Invalid ID
**Steps:**
1. Enter random Civil ID: `999999999999999`
2. Click "Verify"

**Expected:**
- ✅ Shows "❌ No member found with this Civil ID or NOK ID"
- ✅ Status badge is red/danger

---

## 6. EVENTS MODULE TESTING

### Test Case 6.1: View Events List (Admin)
**URL:** http://127.0.0.1:8000/admin/events

**Expected:**
- ✅ All events displayed with banner images
- ✅ Banner column shows images correctly
- ✅ Search works
- ✅ Sorting works

---

### Test Case 6.2: Create New Event
**Steps:**
1. Click "New Event"
2. Fill in all details
3. Upload banner image
4. Save

**Expected:**
- ✅ Form loads correctly
- ✅ All fields work (text, date, image upload, rich editor)
- ✅ Banner image uploads successfully
- ✅ Event saved to database
- ✅ Success notification

---

### Test Case 6.3: Edit Event
**Steps:**
1. Click edit on any event
2. Change title and upload new banner
3. Save

**Expected:**
- ✅ Edit form pre-fills correctly
- ✅ Changes save successfully
- ✅ New banner image replaces old one

---

### Test Case 6.4: View Public Events
**URL:** http://127.0.0.1:8000/events

**Expected:**
- ✅ Events display with images
- ✅ Bootstrap pagination works
- ✅ Different images for different categories
- ✅ Event details show correctly
- ✅ Date, time, location visible

---

## 7. GALLERY MODULE TESTING

### Test Case 7.1: Upload Gallery Images (Admin)
**URL:** http://127.0.0.1:8000/admin/gallery/galleries

**Steps:**
1. Click "New Gallery"
2. Upload image
3. Add title and description
4. Save

**Expected:**
- ✅ Image uploads correctly
- ✅ Images stored in `storage/app/public/gallery/`
- ✅ Images display in admin list
- ✅ Success notification

---

### Test Case 7.2: View Public Gallery
**URL:** http://127.0.0.1:8000/gallery

**Expected:**
- ✅ All images display correctly
- ✅ No 404 errors
- ✅ Lightbox/modal works
- ✅ Responsive layout

---

## SUMMARY CHECKLIST

### Registration Module
- [ ] New member registration works
- [ ] Already member checkbox works
- [ ] All validation rules work correctly
- [ ] Multi-step form progresses smoothly
- [ ] Success message displays after submission
- [ ] Data saves correctly to database

### Admin Dashboard
- [ ] Login works
- [ ] View registrations
- [ ] Approve registration (auto-generates NOK ID & password)
- [ ] Reject registration
- [ ] Edit registration details
- [ ] Delete registration
- [ ] Reset password
- [ ] Resend credentials
- [ ] Search and filters work
- [ ] Email notifications sent correctly

### Renewal System
- [ ] Create renewal request
- [ ] Approve renewal (extends validity, updates counts)
- [ ] View renewal history
- [ ] Renewal emails sent

### Member Portal
- [ ] Member login works
- [ ] Dashboard displays correctly
- [ ] Profile updates work
- [ ] Password change works
- [ ] Membership card downloads
- [ ] Request renewal works

### Verification System
- [ ] Verify by Civil ID works
- [ ] Verify by NOK ID works
- [ ] Shows correct status (active/pending/expired)
- [ ] Handles invalid IDs gracefully

### Events & Gallery
- [ ] Events display with images (admin & public)
- [ ] CRUD operations work
- [ ] Pagination works
- [ ] Gallery images display correctly

---

## NOTES
- All tests should be performed in both desktop and mobile views
- Check browser console for JavaScript errors
- Verify email templates look professional
- Test with different browsers (Chrome, Firefox, Safari)
- Check responsive design on mobile devices

