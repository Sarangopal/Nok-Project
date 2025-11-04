# Resend Credentials Test Suite

## Overview
This test suite validates the "Resend Credentials" functionality in the admin panel (`/admin/registrations`). The button allows administrators to reset a member's password and send new login credentials via email.

## Test Coverage

### ✅ Test 1: Password Generation and Saving
- **Test:** `resend_credentials_generates_new_password_and_saves_it()`
- **Validates:** 
  - New password is generated in correct format
  - Password is properly hashed and saved to database
  - Old password hash is replaced

### ✅ Test 2: Email Sending
- **Test:** `resend_credentials_sends_email_with_new_password()`
- **Validates:**
  - Email is sent to correct recipient
  - Email contains the new password
  - MembershipCardMail is used correctly

### ✅ Test 3: Email Content Verification
- **Test:** `resend_credentials_email_contains_correct_member_details()`
- **Validates:**
  - Email contains member's email, civil ID, and name
  - Password is correctly included in email data

### ✅ Test 4: Password Format Validation
- **Test:** `generated_password_follows_expected_format()`
- **Validates:**
  - Password format: `NOK###XyZ!` (NOK + 3 digits + uppercase + lowercase + !)
  - Password length is 8-10 characters
  - Generated passwords are unique

### ✅ Test 5: Database Update
- **Test:** `resend_credentials_updates_password_in_database()`
- **Validates:**
  - Password is persisted in database
  - Old password no longer works
  - New password works correctly

### ✅ Test 6: Error Handling
- **Test:** `resend_credentials_saves_password_even_if_email_sending_fails()`
- **Validates:**
  - Password is saved before email sending
  - System handles email failures gracefully

### ✅ Test 7: Visibility Conditions
- **Test:** `resend_credentials_only_works_for_approved_members_with_password()`
- **Validates:**
  - Button only visible for approved members
  - Button only visible when password exists

### ✅ Test 8: Email Attachments
- **Test:** `resend_credentials_sends_email_with_membership_card_attachment()`
- **Validates:**
  - Email includes PDF membership card attachment

### ✅ Test 9: Password Uniqueness
- **Test:** `resend_credentials_generates_unique_passwords_each_time()`
- **Validates:**
  - Multiple password generations produce unique passwords

### ✅ Test 10: Download Link
- **Test:** `resend_credentials_email_includes_download_link()`
- **Validates:**
  - Email contains membership card download link
  - Download link includes member ID

## Running the Tests

```bash
# Run all Resend Credentials tests
php artisan test --filter ResendCredentialsTest

# Run specific test
php artisan test --filter resend_credentials_generates_new_password_and_saves_it

# Run with verbose output
php artisan test tests/Feature/ResendCredentialsTest.php --verbose
```

## Test Data

Tests use:
- **Registration Factory** for creating test members
- **Mail::fake()** for email testing
- **RefreshDatabase** trait for clean database state

## Expected Behavior

1. **Password Generation:**
   - Format: `NOK###XyZ!`
   - Example: `NOK456Ab!`, `NOK789Xy!`

2. **Email Content:**
   - Subject: "Your Membership Card - Nightingales of Kuwait"
   - Contains: NOK ID, Email, Civil ID, New Password
   - Includes: Membership card PDF attachment
   - Includes: Download link for membership card

3. **Database Changes:**
   - Password hash is updated
   - Old password becomes invalid
   - New password is immediately usable

4. **Error Handling:**
   - Password saved even if email fails
   - Exception is caught and logged
   - User notified of success/failure

## Notes

- Tests simulate the Filament action logic since Filament actions are difficult to test directly
- All tests use `Mail::fake()` to prevent actual email sending
- Database is refreshed before each test for isolation
- Tests verify both success and edge cases

