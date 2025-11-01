# Email Configuration Guide for NOK Kuwait

## Current Issue
Membership card emails are **NOT being sent** because the mail driver is set to `log` (emails are only written to log files, not actually sent).

## How to Fix Email Sending

### Option 1: Using Gmail (Recommended for Testing)

1. Edit your `.env` file (located in project root)
2. Update these settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-app-specific-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com
MAIL_FROM_NAME="Nightingales of Kuwait"
```

**Important for Gmail:**
- You MUST use an [App Password](https://support.google.com/accounts/answer/185833), not your regular Gmail password
- Go to: Google Account → Security → 2-Step Verification → App passwords
- Generate a new app password and use it in `MAIL_PASSWORD`

### Option 2: Using Mailtrap (Recommended for Development/Testing)

1. Sign up at https://mailtrap.io (free)
2. Get your SMTP credentials
3. Update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@nok-kuwait.com
MAIL_FROM_NAME="Nightingales of Kuwait"
```

### Option 3: Using Production SMTP (For Live Server)

Ask your hosting provider for SMTP details, then update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.your-domain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@your-domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="Nightingales of Kuwait"
```

## After Configuring

1. Clear Laravel cache:
```bash
php artisan config:clear
php artisan cache:clear
```

2. Test email sending:
```bash
php artisan tinker
```

Then in tinker:
```php
Mail::raw('Test email from NOK Kuwait', function($message) {
    $message->to('test@example.com')->subject('Test');
});
```

## Where Are Emails Currently Being Logged?

If mail driver is still `log`, emails are saved to:
```
storage/logs/laravel.log
```

## Resending Membership Card Email

To manually resend a membership card to a specific user:

```bash
php artisan tinker
```

Then:
```php
$user = App\Models\Registration::where('nok_id', 'NOK001002')->first();
Mail::to($user->email)->send(new App\Mail\MembershipCardMail($user));
```

## Troubleshooting

1. **"Connection refused" error**: Check MAIL_HOST and MAIL_PORT
2. **"Authentication failed"**: Check MAIL_USERNAME and MAIL_PASSWORD
3. **No error but email not received**: Check spam folder
4. **"Address in mailbox given [] does not comply"**: Set MAIL_FROM_ADDRESS in .env

## Current Mail Configuration Status

The mail driver is currently set to: **`log`**

This means:
- ✅ No emails are failing
- ❌ No emails are being sent
- 📝 All emails are just logged to `storage/logs/laravel.log`

**Action Required:** Configure a real mail driver to start sending emails!

