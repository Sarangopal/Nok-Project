# Member Password Reset & Change - Test Documentation

## Overview
Comprehensive test suite for member password reset and change functionality.

## Test Files

### 1. Feature Tests

#### `tests/Feature/MemberPasswordResetTest.php`
Tests the email-based password reset flow for members.

**Test Cases:**
- ✅ `member_can_view_password_reset_request_form()` - Verifies reset request page loads
- ✅ `member_can_request_password_reset_link()` - Tests email reset link request
- ✅ `member_receives_error_for_invalid_email()` - Validates error handling for non-existent emails
- ✅ `member_can_view_password_reset_form_with_valid_token()` - Tests reset form display with valid token
- ✅ `member_can_reset_password_with_valid_token()` - Verifies successful password reset
- ✅ `member_cannot_reset_password_with_invalid_token()` - Tests token validation
- ✅ `member_cannot_reset_password_with_mismatched_passwords()` - Validates password confirmation
- ✅ `member_cannot_reset_password_with_short_password()` - Tests password length requirement (min 8 chars)
- ✅ `password_reset_token_expires_after_configured_time()` - Verifies token expiration (60 minutes)

#### `tests/Feature/MemberDashboardPasswordChangeTest.php`
Tests the dashboard password change functionality.

**Test Cases:**
- ✅ `member_can_access_dashboard_when_authenticated()` - Tests authenticated access
- ✅ `member_cannot_access_dashboard_when_not_authenticated()` - Tests auth guard
- ✅ `member_can_change_password_with_correct_current_password()` - Tests successful password change
- ✅ `member_cannot_change_password_with_incorrect_current_password()` - Validates current password verification
- ✅ `password_is_hashed_when_saved()` - Verifies automatic password hashing
- ✅ `member_can_login_with_new_password_after_change()` - Tests login after password change

### 2. Unit Tests

#### `tests/Unit/MemberPasswordHashingTest.php`
Tests password hashing and validation logic.

**Test Cases:**
- ✅ `password_is_automatically_hashed_on_create()` - Tests auto-hashing on member creation
- ✅ `password_is_automatically_hashed_on_update()` - Tests auto-hashing on password update
- ✅ `already_hashed_password_is_not_double_hashed()` - Prevents double hashing
- ✅ `password_verification_works_correctly()` - Tests Hash::check() functionality
- ✅ `nok_style_password_format_is_generated_correctly()` - Tests NOK password format (NOK123Ab!)
- ✅ `password_minimum_length_requirement()` - Validates 8-character minimum

## Running Tests

### Run All Password Tests
```bash
php artisan test --filter=MemberPassword
```

### Run Specific Test Files
```bash
# Feature tests
php artisan test tests/Feature/MemberPasswordResetTest.php
php artisan test tests/Feature/MemberDashboardPasswordChangeTest.php

# Unit tests
php artisan test tests/Unit/MemberPasswordHashingTest.php
```

### Run Individual Test
```bash
php artisan test --filter=member_can_reset_password_with_valid_token
```

## Test Coverage

### Routes Tested
- `GET /member/password/reset` - Password reset request form
- `POST /member/password/email` - Send reset link email
- `GET /member/password/reset/{token}` - Password reset form
- `POST /member/password/reset` - Process password reset
- `/member/panel` - Member dashboard (password change)
- `/member/panel/login` - Member login

### Components Tested
- `MemberPasswordResetController` - Password reset logic
- `Member` model - Password hashing mutator
- `MemberResetPasswordNotification` - Reset email notification
- `MemberDashboard` - Dashboard password change action

### Security Features Verified
- ✅ Current password verification required
- ✅ Password confirmation matching
- ✅ Minimum password length (8 characters)
- ✅ Automatic password hashing (bcrypt)
- ✅ Token expiration (60 minutes)
- ✅ Invalid token rejection
- ✅ Invalid email rejection
- ✅ Authentication guards

## Factory

### `database/factories/MemberFactory.php`
Factory for generating test member data.

**Usage:**
```php
// Create approved member
$member = Member::factory()->create();

// Create pending member
$member = Member::factory()->pending()->create();

// Create rejected member
$member = Member::factory()->rejected()->create();

// Create renewed member
$member = Member::factory()->renewed()->create();

// Create with custom attributes
$member = Member::factory()->create([
    'email' => 'test@example.com',
    'password' => 'testpassword',
]);
```

## Configuration

### Password Broker (`config/auth.php`)
```php
'passwords' => [
    'members' => [
        'provider' => 'members',
        'table' => 'password_reset_tokens',
        'expire' => 60,  // Token expires in 60 minutes
        'throttle' => 60, // Rate limit: 60 seconds between requests
    ],
],
```

## Success Criteria
✅ All 21 tests passing  
✅ 100% coverage of password reset flow  
✅ Security best practices implemented  
✅ Email notifications working  
✅ Token expiration validated  
✅ Password hashing automatic  

## Notes
- Tests use `RefreshDatabase` trait to reset database after each test
- Member factory provides realistic test data
- Password reset tokens stored in `password_reset_tokens` table
- Email notifications can be faked in tests with `Notification::fake()`

